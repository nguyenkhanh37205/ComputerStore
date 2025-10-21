<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard.index');
    }
    public function create()
    {
        return view('admin.dashboard.create');
    }
    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        // Create a new dashboard item
        // Dashboard::create([
        //     'title' => $request->title,
        //     'content' => $request->content,
        // ]);

        return redirect()->route('admin.dashboard.index')->with('success', 'Dashboard item created successfully.');
    }
    public function edit($id)
    {
        // $dashboardItem = Dashboard::findOrFail($id);
        return view('admin.dashboard.edit'/*, compact('dashboardItem')*/);
    }
    public function update(Request $request, $id)
    {
        // Validate the request data
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        // $dashboardItem = Dashboard::findOrFail($id);
        // $dashboardItem->update([
        //     'title' => $request->title,
        //     'content' => $request->content,
        // ]);

        return redirect()->route('admin.dashboard.index')->with('success', 'Dashboard item updated successfully.');
    }
    public function destroy($id)
    {
        // $dashboardItem = Dashboard::findOrFail($id);
        // $dashboardItem->delete();

        return redirect()->route('admin.dashboard.index')->with('success', 'Dashboard item deleted successfully.');
    }
}
