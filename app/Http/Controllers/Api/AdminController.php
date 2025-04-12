<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class AdminController extends Controller
{

public function assignRole(Request $request)
{
    $request->validate([
        'user_id' => 'required|exists:users,id',
        'role' => 'required|in:admin,manager,employee'
    ]);

    $user = User::findOrFail($request->user_id);
    $user->syncRoles([$request->role]); // Remove old roles and assign new

    return response()->json(['message' => 'Role assigned successfully']);
}

}
