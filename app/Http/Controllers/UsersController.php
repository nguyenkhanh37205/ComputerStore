<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function index()
    {
        return view('admin.users.index');
    }
    public function create()
    {
        return view('admin.users.create');
    }
    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Create a new user
        // User::create([
        //     'name' => $request->name,
        //     'email' => $request->email,
        //     'password' => bcrypt($request->password),
        // ]);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }
    public function edit($id)
    {
        // $user = User::findOrFail($id);
        return view('admin.users.edit'/*, compact('user')*/);
    }
    public function update(Request $request, $id)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);
    
        // $user = User::findOrFail($id);
        // $user->update([
        //     'name' => $request->name,
        //     'email' => $request->email,
        //     'password' => $request->password ? bcrypt($request->password) : $user->password,
        // ]);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }
    public function destroy($id)
    {
        // $user = User::findOrFail($id);
        // $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }
}