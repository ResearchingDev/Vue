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
    }
    public function index(Request $request)
    {
        // Get the page number and limit from the request
        $limit = $request->input('length', 10);
        $start = $request->input('start', 0);
    
        // Get the data by joining sub_clients and sub_users
        $users = DB::table('sub_clients')
            ->join('sub_users', 'sub_clients.id', '=', 'sub_users.client_id') // Join sub_clients with sub_users on client_id
            ->select(
                'sub_clients.id as client_id',
                'sub_clients.client_name',
                'sub_clients.email as client_email',
                'sub_users.id as user_id',
                'sub_users.first_name',
                'sub_users.last_name',
                'sub_users.phone_number',
                'sub_users.user_type',
                'sub_users.email as user_email',
                'sub_users.status',
                'sub_users.created_at as user_created_at'
            )
            ->offset($start)
            ->limit($limit)
            ->get();
    
        // Count the total number of records in sub_clients
        $totalRecords = DB::table('sub_clients')->count();
    
        // Return a properly structured JSON response
        return response()->json([
            'draw' => (int) $request->input('draw', 1),      
            'recordsTotal' => $totalRecords,                
            'recordsFiltered' => $totalRecords,                 
            'data' => $users,                                   
        ]);
    }
    

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
        ]);

        DB::beginTransaction();

        try {
            // Insert into `sub_clients` table
            $subClient = SubClient::create([
                'client_name' => $request->client_name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'address' => $request->address,
                'status' => $request->status,
            ]);

            // Insert into `users` table
            $user = User::create([
                'client_id' => $subClient->id,
                'role_id' => 2, // Replace with your role logic
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'secondary_password' => Hash::make($request->password),
                'first_name' => $request->client_name,
                'last_name' => '',
                'phone_number' => $request->phone_number,
                'alter_phone_number' => $request->alternate_phone_number,
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
    public function show(SubClient $client)
    {
        $client->load('user');
        return response()->json($client, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SubClient $client)
    {
        $request->validate([
            'client_name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:sub_clients,email,' . $client->id,
            'phone_number' => 'sometimes|required|string|max:15',
            'alternate_phone_number' => 'nullable|string|max:15',
            'address' => 'nullable|string|max:500',
            'status' => 'required|in:Active,Inactive',
            'username' => 'sometimes|required|string|unique:sub_users,username,' . $client->id,
            'password' => 'nullable|string|min:8',
        ]);

        DB::beginTransaction();

        try {
            // Update `sub_clients` table
            $client->update([
                'client_name' => $request->client_name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'alternate_phone_number' => $request->alternate_phone_number,
                'address' => $request->address,
                'status' => $request->status,
            ]);

            // Update corresponding user
            $user = User::where('client_id', $client->id)->first();
            if ($user) {
                $user->update([
                    'username' => $request->username,
                    'email' => $request->email,
                    'password' => $request->password ? Hash::make($request->password) : $user->password,
                ]);
            }

            DB::commit();

            return response()->json(['message' => 'Client updated successfully', 'data' => $client], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error updating client and user',
                'error' => $e->getMessage(),
            ], 500);
        }
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
