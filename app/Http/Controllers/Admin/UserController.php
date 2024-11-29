<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

use App\Models\UserRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;


class UserController extends Controller
{

    public function makeAdmin()
    {
        $user = User::where('email', 'withanageshihan@gmail.com')->first();

        if ($user) {
            $adminRole = Role::where('name', 'admin')->first();

            if ($adminRole) {
                $user->assignRole($adminRole);
                return "Role 'admin' assigned to user successfully.";
            }

            return "Role 'admin' not found.";
        }

        return "User not found.";
    }


    public function index()
    {
        $users = User::paginate(10); //get all users
        $roles = Role::all(); //get all roles

        return view('admin.users.index', compact('users', 'roles'));
    }

    public function edit(User $user)
{
    // Assuming you are retrieving roles from your database
    $roles = Role::all();

    // Pass $user and $roles to the view
    return view('admin.users.edit', compact('user', 'roles'));
}

    public function update(Request $request, User $user)
    {
        // Handle user update logic here
        $user->update($request->all());

        return redirect()->route('users.index')->with('success', 'User updated successfully');
    }

    public function destroy(User $user)
    {
        // Delete the related userRequest
        $userRequest = $user->userRequest;
        
        if ($userRequest) {
            $userRequest->delete();
        }
    
        // Handle user deletion logic here
        $user->delete();
    
        return redirect()->route('users.index')->with('success', 'User deleted successfully');
    }

    //Assign Roles
    public function assignRole(Request $request, User $user)
    {
        // Validate the incoming request data
        $request->validate([
            'role' => 'required|exists:roles,name',
        ]);

        // Get the role by name
        $role = Role::where('name', $request->input('role'))->first();

        if (!$role) {
            return redirect()->back()->with('error', 'Role not found.');
        }

        // Start a database transaction
        DB::beginTransaction();

        try {
            // Assign the role to the user
            $user->assignRole($role);

            // Update the user request status to 'Approved'
            $userRequest = UserRequest::where('user_id', $user->id)->first();
            
            if ($userRequest) {
                $userRequest->update(['status' => 'Approved']);
            }

            // Commit the transaction
            DB::commit();

            return redirect()->back()->with('success', 'Role assigned successfully.');
        } catch (\Exception $e) {
            // Rollback the transaction in case of an error
            DB::rollback();

            return redirect()->back()->with('error', 'An error occurred while assigning the role.');
        }
    }


    //Unassign Roles
    public function removeRole(Request $request, User $user)
    {
        // Validate the incoming request data
        $request->validate([
            'remove_role' => 'required|exists:roles,name',
        ]);

        // Get the role by name
        $role = Role::where('name', $request->input('remove_role'))->first();

        if (!$role) {
            return redirect()->back()->with('error', 'Role not found.');
        }

        // Start a database transaction
        DB::beginTransaction();

        try {
            // Remove the role from the user
            $user->removeRole($role);

            // Update the user request status to "pending" if it exists
            $userRequest = UserRequest::where('user_id', $user->id)->first();

            if ($userRequest) {
                $userRequest->update(['status' => 'pending']);
            }

            // Commit the transaction
            DB::commit();

            return redirect()->back()->with('success', 'Role removed successfully.');
        } catch (\Exception $e) {
            // Rollback the transaction in case of an error
            DB::rollback();

            return redirect()->back()->with('error', 'An error occurred while removing the role.');
        }
    }

}
