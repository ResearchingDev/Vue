<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SubClient;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function list(Request $request)
    {
        $limit = $request->input('length', 10);
        $start = $request->input('start', 0); 
        $search = $request->input('search', ''); 
        $orderColumnIndex = $request->input('order.0.column', 0); // Column index for ordering
        $orderDirection = $request->input('order.0.dir', 'asc'); // Order direction ('asc' or 'desc')
        $columns = [
            'sub_clients.id',
            'sub_clients.client_name',
            'sub_clients.email',
            'sub_users.phone_number',
            'sub_users.created_at',
            'sub_users.status'
        ];
        $orderColumn = $columns[$orderColumnIndex] ?? 'sub_clients.id';

        // Base query
        $query = DB::table('sub_clients')
            ->join('sub_users', 'sub_clients.id', '=', 'sub_users.client_id')
            ->where('sub_users.user_type', '=', 'Client')
            ->select(
                'sub_clients.id as client_id',
                'sub_clients.client_name',
                'sub_clients.email as client_email',
                'sub_users.id as user_id',
                'sub_users.phone_number',
                'sub_users.created_at as user_created_at',
                'sub_users.status'
            );
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('sub_clients.client_name', 'LIKE', "%$search%")
                ->orWhere('sub_clients.email', 'LIKE', "%$search%")
                ->orWhere('sub_users.phone_number', 'LIKE', "%$search%")
                ->orWhere('sub_users.status', 'LIKE', "%$search%");
            });
        }
        $filteredQuery = clone $query;
        $query->orderBy($orderColumn, $orderDirection);
        $data = $query->offset($start)->limit($limit)->get();
        $totalRecords = DB::table('sub_clients')->count(); 
        $totalFiltered = $filteredQuery->count();       

        return response()->json([
            'draw' => (int) $request->input('draw', 1),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalFiltered,
            'data' => $data,
        ]);
    }
    public function index(Request $request) {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'client_name' => 'required|string|max:255',
            'email' => 'required|email|unique:sub_clients,email',
            'phone_number' => 'required|string|max:15',
            'alternate_phone_number' => 'nullable|string|max:15',
            'address' => 'nullable|string|max:500',
            'status' => 'required|in:Active,Inactive',
            'username' => 'required|string|unique:sub_users,username',
            'password' => 'required|string|min:8',
            'profile_picture' => 'required|image|mimes:jpg,jpeg,png,gif|max:2048', // Validate image upload
        ]);

        DB::beginTransaction();

        try {
            // Store the uploaded image and get the file path
            $imagePath = $request->file('profile_picture')->store('profile_pictures', 'public');
            // Insert into `sub_clients` table
            $subClient = SubClient::create([
                'client_name' => $request->client_name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'address' => $request->address,
                'status' => $request->status,
                'logo' => $imagePath
            ]);
            $subClientId = $subClient->id;
            // Insert into `users` table
            $user = User::create(attributes: [
                'client_id' => $subClientId,
                'role_id' => 2, // Replace with your role logic
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'secondary_password' => Hash::make($request->password),
                'first_name' => $request->client_name,
                'last_name' => '',
                'phone_number' => $request->phone_number,
                'alter_phone_number' => $request->alternate_phone_number,
                'profile_picture' => $imagePath, // Make sure to update profile_picture for the user as well
                'user_type' => 'Client'
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Client and User created successfully',
                'data' => [
                    'sub_client' => $subClient,
                    'user' => $user,
                ]
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error creating client and user',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Find the client by ID
        $client = SubClient::findOrFail($id);

        // Optionally, you can eager load the 'user' relationship if needed
        $client->load('user');

        // Return the client data in JSON format with a 200 status code
        return response()->json($client, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validate the incoming data
        $validated = $request->validate([
            'client_name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:sub_clients,email,' . $id,
            'phone_number' => 'sometimes|required|string|max:15',
            'alternate_phone_number' => 'nullable|string|max:15',
            'address' => 'nullable|string|max:500',
            'status' => 'required|in:Active,Inactive',
            'username' => 'sometimes|required|string',
            'password' => 'nullable|string|min:8',
            'profile_picture' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048', // Validate image upload
        ]);

        // Find the client by ID
        $client = SubClient::findOrFail($id);

        // Initialize image path to null in case no image is uploaded
        $imagePath = $client->logo;  // Default to the current image if no new one is uploaded

        // Update the client with validated data
        $client->update([
            'client_name' => $request->client_name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'address' => $request->address,
            'status' => $request->status,
        ]);

        // Handle profile picture upload if it exists
        if ($request->hasFile('profile_picture')) {
            // Store the uploaded image and get the file path
            $imagePath = $request->file('profile_picture')->store('profile_pictures', 'public');

            // Update the client's logo with the new image path
            $client->logo = $imagePath;
            $client->save();
        }

        // Update corresponding user information
        $user = User::where('client_id', $client->id)->first();
        if ($user) {
            $user->update([
                'username' => $request->username,
                'email' => $request->email,
                'password' => $request->password ? Hash::make($request->password) : $user->password,
                'phone_number' => $request->phone_number,
                'alter_phone_number' => $request->alternate_phone_number,
                'profile_picture' => $imagePath, // Make sure to update profile_picture for the user as well
            ]);
        }

        // Return success response
        return response()->json([
            'message' => 'Client and user updated successfully',
            'data' => $client
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SubClient $client)
    {
        DB::beginTransaction();

        try {
            // Delete associated user
            User::where('client_id', $client->id)->delete();

            // Delete sub-client
            $client->delete();

            DB::commit();

            return response()->json(['message' => 'Client deleted successfully'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error deleting client and user',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
