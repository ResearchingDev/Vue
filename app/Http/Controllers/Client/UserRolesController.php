<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\UserPermission;
use App\Models\SubUserRole;
use Illuminate\Http\Request;

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
            'permissions.*.moduleName' => 'required|string|max:100',
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
                    'role_id' => $role->id,
                    'user_id' => '77',
                    'menu_id' => '77',
                    'module_name' => $permission['moduleName'],
                    'can_delete' => $hasDeleteAccess,
                    'can_update' => $hasUpdateAccess,
                    'can_add' => $hasAddAccess,
                    'can_view' => $hasViewAccess,
                ]);
            }
            return response()->json([
                'message' => 'Role and permissions created successfully',
                'data' => $role,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create role and permissions',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
