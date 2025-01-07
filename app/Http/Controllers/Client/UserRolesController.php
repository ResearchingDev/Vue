<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\UserPermission;
use App\Models\SubUserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserRolesController extends Controller
{

    public function store(Request $request)
    {
        // Validate the form data
        $validatedData = $request->validate([
            'roleCode' => 'required|string|max:50|unique:sub_user_roles,role_unique_code',
            'roleName' => 'required|string|max:100|unique:sub_user_roles,role_name',
            'status' => 'required|in:Active,Inactive',
            'userAccess' => 'required|array|min:1',
            'userAccess.*' => 'string|in:Web Access,Mobile Access',
            'permissions' => 'required|array|min:1',
            'permissions.*.moduleID' => 'integer', // Add this line
            'permissions.*.delete' => 'required|boolean',
            'permissions.*.update' => 'required|boolean',
            'permissions.*.add' => 'required|boolean',
            'permissions.*.view' => 'required|boolean',
        ]);
        try {
            // Determine access types
            $hasWebAccess = in_array('Web Access', $validatedData['userAccess']) ? 'Yes' : 'No';
            $hasMobileAccess = in_array('Mobile Access', $validatedData['userAccess']) ? 'Yes' : 'No';
            // Create the role in the `roles` table
            $role = SubUserRole::create([
                'role_unique_code' => $validatedData['roleCode'],
                'role_name' => $validatedData['roleName'],
                'status' => $validatedData['status'],
                'web_access' => $hasWebAccess,
                'mobile_access' => $hasMobileAccess,
            ]);
            // Store permissions in the `permissions` table
            foreach ($validatedData['permissions'] as $permission) {
                $hasDeleteAccess = !empty($permission['delete']) ? 'Yes' : 'No';
                $hasUpdateAccess = !empty($permission['update']) ? 'Yes' : 'No';
                $hasAddAccess = !empty($permission['add']) ? 'Yes' : 'No';
                $hasViewAccess = !empty($permission['view']) ? 'Yes' : 'No';
                UserPermission::create([
                    'role_id' => $role->id, // Use the created role's ID
                    'user_id' => '77',
                    'menu_id' => $permission['moduleID'], // Use module_id from request
                    'can_delete' => $hasDeleteAccess,
                    'can_update' => $hasUpdateAccess,
                    'can_add' => $hasAddAccess,
                    'can_view' => $hasViewAccess,
                ]);
            }
            return response()->json([
                'status' => 'success',
                'message' => 'Role and permissions created successfully',
                'data' => $role,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create role and permissions',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function modules_list(Request $request)
    {
        // Get the data by joining sub_clients and sub_users
        $module_menus = DB::table('sub_module_menus')
            ->select(
                'sub_module_menus.id',
                'sub_module_menus.parent_id',
                'sub_module_menus.module_name as name',
                'sub_module_menus.module_type',
                'sub_module_menus.unique_code',
                'sub_module_menus.sequence_order',
                'sub_module_menus.status'
            )
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $module_menus,
        ], 200);
    }
    public function list(Request $request)
    {
        // Get pagination, sorting, and search parameters
        $limit = $request->input('length', 10);
        $start = $request->input('start', 0);
        $searchValue = $request->input('search.value', '');
        $orderColumnIndex = $request->input('order.0.column', 0);
        $orderDirection = $request->input('order.0.dir', 'asc');

        // Map column index to actual database columns
        $columns = ['role_name', 'role_unique_code', 'web_access', 'mobile_access', 'status'];
        $orderColumn = $columns[$orderColumnIndex] ?? 'id';

        // Query the sub_user_roles table
        $query = DB::table('sub_user_roles')
            ->select('id', 'role_name', 'role_unique_code', 'web_access', 'mobile_access', 'status')
            ->where('role_name', '!=', 'Super Admin')
            ->where('role_name', '!=', 'Client')
            ->where('deleted_at', null);

        // Apply search filter
        if (!empty($searchValue)) {
            $query->where(function ($q) use ($searchValue) {
                $q->where('role_name', 'like', "%$searchValue%")
                    ->orWhere('web_access', 'like', "%$searchValue%")
                    ->orWhere('mobile_access', 'like', "%$searchValue%")
                    ->orWhere('status', 'like', "%$searchValue%");
            });
        }

        // Get the filtered and paginated results
        $filteredRecords = $query->count();
        $users = $query->orderBy($orderColumn, $orderDirection)
            ->offset($start)
            ->limit($limit)
            ->get();

        // Total records count
        $totalRecords = DB::table('sub_user_roles')->count();

        // Return a properly structured JSON response
        return response()->json([
            'draw' => (int) $request->input('draw', 1),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $users,
        ]);
    }
    public function edit(Request $request)
    {
          $user_role_id = $request->id;
          // Find the client by ID
          $userrole = SubUserRole::findOrFail($user_role_id);
          // Optionally, you can eager load the 'user' relationship if needed
          $userrole->load('user_permission');
          return response()->json([
            'status' => 'success',
            'data' => $userrole,
        ], 200);
    }

    public function destroy(Request $request)
    {
        try {
            $user_role_id = $request->id;
            // Find the role by ID
            $userRole = SubUserRole::findOrFail($user_role_id);
            // Delete related user_permission records
            $userRole->user_permission()->delete();
            // Delete the role itself
            $userRole->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Role and associated permissions deleted successfully.',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete role. Please try again later.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
