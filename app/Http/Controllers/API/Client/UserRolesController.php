<?php

namespace App\Http\Controllers\API\Client;

use App\Http\Controllers\Controller;
use App\Models\UserPermission;
use App\Models\SubUserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserRolesController extends Controller
{
    //Store the User Roles and Permissions
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
            $clientId = Auth::user()->client_id;
            // Create the role in the `roles` table
            $role = SubUserRole::create([
                'client_id' => $clientId,
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
                    'client_id' => $clientId,
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
    //List the User Modules
    public function modules_list()
    {
        // Get the data by joining sub_clients and sub_users
        $module_menus = DB::table('sub_module_menus')
            ->select(
                'sub_module_menus.id',
                'sub_module_menus.parent_id',
                'sub_module_menus.title as name',
                'sub_module_menus.type as module_type',
                'sub_module_menus.unique_code',
                'sub_module_menus.sort_order',
                'sub_module_menus.status'
            )
            ->get();
        return response()->json([
            'status' => 'success',
            'data' => $module_menus,
        ], 200);
    }
    //List the User Roles in Datatable
    public function list(Request $request)
    {
        $clientId = Auth::user()->client_id;
        // Get pagination, sorting, and search parameters
        $limit = $request->input('length', 10);
        $start = $request->input('start', 0);
        $searchValue = $request->input('search', '');
        $orderColumnIndex = $request->input('order.0.column', 0);
        $orderDirection = $request->input('order.0.dir', 'asc');
        // Map column index to actual database columns
        $columns = ['role_name', 'role_unique_code', 'web_access', 'mobile_access', 'status'];
        $orderColumn = $columns[$orderColumnIndex] ?? 'id';
        // Query the sub_user_roles table
        $query = DB::table('sub_user_roles')
            ->select('id', 'role_name', 'role_unique_code', 'web_access', 'mobile_access', 'status')
            ->where('role_unique_code', '!=', 'client')
            ->where('client_id', '=', $clientId)
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
    //List the Particular User Roles and Permisssions
    public function show(string $user_role_id)
    {
        // Find the client by ID
        $userrole = SubUserRole::findOrFail($user_role_id);
        // Optionally, you can eager load the 'user' relationship if needed
        $userrole->load('user_permission.moduleMenu');
        return response()->json([
            'status' => 'success',
            'data' => $userrole,
        ], 200);
    }
    //Delete the User Roles and Permisssions
    public function destroy(string $user_role_id)
    {
        try {
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
    //Update the User Roles and Permissions
    public function update(Request $request, $id)
    {
        // Validate the form data
        $validatedData = $request->validate([
            'roleCode' => "required|string|max:50|unique:sub_user_roles,role_unique_code,{$id}",
            'roleName' => "required|string|max:100|unique:sub_user_roles,role_name,{$id}",
            'status' => 'required|in:Active,Inactive',
            'userAccess' => 'required|array|min:1',
            'userAccess.*' => 'string|in:Web Access,Mobile Access',
            'permissions' => 'required|array|min:1',
            'permissions.*.moduleID' => 'integer',
            'permissions.*.delete' => 'required|boolean',
            'permissions.*.update' => 'required|boolean',
            'permissions.*.add' => 'required|boolean',
            'permissions.*.view' => 'required|boolean',
        ]);
        try {
            $clientId = Auth::user()->client_id;
            // Find the role and update its details
            $role = SubUserRole::findOrFail($id);
            $role->update([
                'client_id' => $clientId,
                'role_unique_code' => $validatedData['roleCode'],
                'role_name' => $validatedData['roleName'],
                'status' => $validatedData['status'],
                'web_access' => in_array('Web Access', $validatedData['userAccess']) ? 'Yes' : 'No',
                'mobile_access' => in_array('Mobile Access', $validatedData['userAccess']) ? 'Yes' : 'No',
            ]);
            // Prepare updated permissions
            $permissionsData = collect($validatedData['permissions'])->map(function ($permission) use ($role) {
                return [
                    'role_id' => $role->id,
                    'user_id' => '77',
                    'menu_id' => $permission['moduleID'],
                    'can_delete' => $permission['delete'] ? 'Yes' : 'No',
                    'can_update' => $permission['update'] ? 'Yes' : 'No',
                    'can_add' => $permission['add'] ? 'Yes' : 'No',
                    'can_view' => $permission['view'] ? 'Yes' : 'No',
                ];
            });
            // Sync permissions: update existing, add new, and delete removed
            $existingPermissions = UserPermission::where('role_id', $id)->get()->keyBy('menu_id');
            $newPermissions = $permissionsData->keyBy('menu_id');
            // Update or create permissions
            $newPermissions->each(function ($data, $menuID) use ($existingPermissions) {
                if ($existingPermissions->has($menuID)) {
                    $existingPermissions[$menuID]->update($data);
                } else {
                    UserPermission::create($data);
                }
            });
            // Delete removed permissions
            $removedMenuIDs = $existingPermissions->keys()->diff($newPermissions->keys());
            UserPermission::whereIn('menu_id', $removedMenuIDs)->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Role and permissions updated successfully',
                'data' => $role,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update role and permissions',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
