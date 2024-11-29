<?php

// app/Http/Controllers/PermissionController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Cache;

use Illuminate\Support\Facades\Log;


class PermissionController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin')->except(['index']);
    }

    public function index()
    {
        $roles = Role::all(); // Retrieve all roles
        $allPermissions = Permission::all(); // Retrieve all permissions

        return view('permissions.index', compact('roles', 'allPermissions'));
    }

    public function create()
    {

        return view('permissions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:permissions|max:255',
        ]);

        Permission::create(['name' => $request->input('name')]);

        return redirect()->route('permissions.index')->with('success', 'Permission created successfully');
    }

    public function edit(Permission $permission)
    {
        return view('permissions.edit', compact('permission'));
    }

    public function update(Request $request, Permission $permission)
    {
        $request->validate([
            'name' => 'required|unique:permissions|max:255',
        ]);

        $permission->update(['name' => $request->input('name')]);

        return redirect()->route('permissions.index')->with('success', 'Permission updated successfully');
    }

    public function destroy(Role $role, Permission $permission)
    {
        // Check if the role and permission exist
        abort_if(!$role || !$permission, 404);

        // Check if the permission is attached to the role
        if ($role->hasPermissionTo($permission)) {
            // Detach the permission from the role
            $role->revokePermissionTo($permission);

                return redirect()->route('permissions.show', ['role' => $role])
                    ->with('success', 'Permission removed from role successfully');
        }

        return redirect()->route('permissions.show', $role)
            ->with('error', 'Permission not found for the specified role');
    }

    // Show all permissions for a specific user type (role)
    public function show(Role $role)
    {
        // Check if the role exists
        if (!$role) {
            abort(404); // or handle it in a way suitable for your application
        }

        // Load the permissions for the specified role
        $role->load('permissions');

        // Get the permissions from the relationship
        $permissions = $role->permissions;

        return view('permissions.show', compact('role', 'permissions'));
    }


    // Assign Permissions to Roles
    public function assignToRole(Request $request, Role $role)
    {
        $request->validate([
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,id',
        ]);
    
        $selectedPermissionIds = $request->input('permissions');
        $existingPermissionIds = $role->permissions->pluck('id')->toArray();
    
        // Check for duplicate permissions already assigned to the role
        $duplicatePermissionIds = array_intersect($selectedPermissionIds, $existingPermissionIds);
    
        if (!empty($duplicatePermissionIds)) {
            $duplicatePermissions = Permission::whereIn('id', $duplicatePermissionIds)->pluck('name')->toArray();
            $message = 'The following permissions already exist for the role: ' . implode(', ', $duplicatePermissions);
    
            return redirect()->route('permissions.index')->with('warning', $message);
        }
    
        // If there are new permissions to attach, proceed
        $newPermissionIds = array_diff($selectedPermissionIds, $existingPermissionIds);
    
        if (!empty($newPermissionIds)) {
            // Retrieve the new permissions and attach them to the role
            $permissions = Permission::whereIn('id', $newPermissionIds)->get();
            $role->permissions()->attach($permissions);


           // Log the activity with the 'event' and 'properties' specified
           foreach ($permissions as $permission) {
            activity()
                ->event('role_permission_assigned')
                ->withProperties([
                    'role' => $role->name,
                    'permission' => $permission->name,
                ])
                ->log("Permission '{$permission->name}' assigned to role '{$role->name}'");
        }

              // Clear the permission cache manually (optional, as syncPermissions should handle it)
                Cache::forget("spatie.permission.cache");
    
            return redirect()->route('permissions.index')->with('success', 'Permissions assigned to role successfully');
        }
    
        // No new permissions to attach
        return redirect()->route('permissions.index')->with('info', 'No new permissions were selected.');
    }
    
}



