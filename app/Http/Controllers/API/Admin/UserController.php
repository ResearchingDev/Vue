<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function list(Request $request)
    {
        // Get pagination, sorting, and search parameters
        $limit = $request->input('length', 10);
        $start = $request->input('start', 0);
        $searchValue = $request->input('search.value', '');
        $orderColumnIndex = $request->input('order.0.column', 0);
        $orderDirection = $request->input('order.0.dir', 'asc');

        // Map column index to actual database columns
        $columns = ['id', 'first_name', 'last_name', 'phone_number', 'user_type', 'email', 'status', 'created_at'];
        $orderColumn = $columns[$orderColumnIndex] ?? 'id';

        // Query the sub_users table
        $query = DB::table('sub_users')
            ->select('id', 'first_name', 'last_name', 'phone_number', 'user_type', 'email', 'status', 'created_at');

        // Apply search filter
        if (!empty($searchValue)) {
            $query->where(function ($q) use ($searchValue) {
                $q->where('first_name', 'like', "%$searchValue%")
                    ->orWhere('last_name', 'like', "%$searchValue%")
                    ->orWhere('email', 'like', "%$searchValue%");
            });
        }

        // Get the filtered and paginated results
        $filteredRecords = $query->count();
        $users = $query->orderBy($orderColumn, $orderDirection)
            ->offset($start)
            ->limit($limit)
            ->get();

        // Total records count
        $totalRecords = DB::table('sub_users')->count();

        // Return a properly structured JSON response
        return response()->json([
            'draw' => (int) $request->input('draw', 1),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $users,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|email|unique:sub_users,email',
            'password' => 'required|string|min:6',
            'secondary_password' => 'required|string|min:6', 
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:15',
            'alter_phone_number' => 'nullable|string|max:15',
            'status' => 'required|in:Active,Inactive',
            'user_type' => 'required|in:Super Admin,Client,User',
            'can_login' => 'required|in:Yes,No',
        ]);

        // Encrypt passwords
        $validatedData['password'] = bcrypt($validatedData['password']);
        $validatedData['secondary_password'] = bcrypt($validatedData['secondary_password']);  

        // Create the user
        $user = User::create([
            'username' => $validatedData['username'],
            'email' => $validatedData['email'],
            'password' => $validatedData['password'],
            'secondary_password' => $validatedData['secondary_password'],  // Use the correct value
            'first_name' => $validatedData['first_name'],
            'last_name' => $validatedData['last_name'],
            'phone_number' => $validatedData['phone_number'],
            'alter_phone_number' => $validatedData['alter_phone_number'],
            'status' => $validatedData['status'],
            'user_type' => $validatedData['user_type'],
            'can_login' => $validatedData['can_login'],
        ]);

        return response()->json([
            'message' => 'User created successfully',
            'data' => $user,
        ], 201);
    }

    
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $user = User::findOrFail($id);
        return response()->json($user);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Find the user by ID and return user data for editing
        $user = User::findOrFail($id);
        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validate the incoming request
        $validatedData = $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|email|unique:sub_users,email,' . $id,
            'password' => 'nullable|string|min:6',
            'secondary_password' => 'nullable|string|min:6', 
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:15',
            'alter_phone_number' => 'nullable|string|max:15',
            'status' => 'required|in:Active,Inactive',
            'user_type' => 'required|in:Super Admin,Client,User',
            'can_login' => 'required|in:Yes,No',
        ]);

        // Find the user by ID
        $user = User::findOrFail($id);

        // If password is provided, encrypt it
        if ($request->has('password')) {
            $validatedData['password'] = bcrypt($validatedData['password']);
        }
        
        // If secondary password is provided, encrypt it
        if ($request->has('secondary_password')) {
            $validatedData['secondary_password'] = bcrypt($validatedData['secondary_password']);
        }

        // Update the user data
        $user->update($validatedData);

        return response()->json([
            'message' => 'User updated successfully',
            'data' => $user,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json(null, 204);
    }
}
