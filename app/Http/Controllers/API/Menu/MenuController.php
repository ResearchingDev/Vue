<?php

namespace App\Http\Controllers\API\Menu;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SubModuleMenu;

class MenuController extends Controller
{
    /**
     * Display a listing of the Menus.
     */
    public function list(Request $request)
    {
        try {
            // Get the role from the request data
            $userRole = $request->input('role_code');

            // Initialize the menu list
            $menus = SubModuleMenu::query();

            // Fetch menus based on the role
            if ($userRole === 'admin') {
                // Fetch all menus for admin
                $menus = $menus->get();
            } else {
                // Fetch menus for other roles
                $menus = $menus->where('role', $userRole)->get();
            }

            // Convert the menus collection to an array
            $menus = $menus->toArray();

            // Initialize an empty array for storing the hierarchical structure
            $menuTree = [];

            // Build the menu tree by categorizing menus based on parent_id
            foreach ($menus as $key => $menu) {
                // If the menu has no parent (parent_id is null or empty), it's a root menu
                if (empty($menu['parent_id'])) {
                    // Store the root menu
                    $parentId = $menu['id'];
                    // Filter out the child menus by parent_id
                    $children = array_filter($menus, function ($menuItem) use ($parentId) {
                        return $menuItem['parent_id'] == $parentId;
                    });
                    // Add children to the parent menu
                    if(!empty($children))  $menus[$key]['children'] = $children;
                    // Add the root menu to the menu tree
                    $menuTree[] = $menus[$key];
                }
            }

            // Filter the final menus to only include root-level items
            $menuTree = array_filter($menus, function ($menu) {
                return empty($menu['parent_id']);
            });

            // Return success response with the menu tree
            return response()->apiResponse('success', 'Menu List', $menuTree, 200);
        } catch (\Exception $e) {
            // Return error response in case of an exception
            return response()->json([
                'success' => false,
                'message' => 'Error fetching menus',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
