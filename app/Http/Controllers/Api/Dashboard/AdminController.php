<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Dashboard\StoreAdminRequest;
use App\Http\Requests\Api\Dashboard\UpdateAdminRequest;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{

       // List all admins
       public function index()
       {
           $admins = Admin::all();
           return response()->json($admins);
       }

       // Show a single admin
    //    public function show(Admin $admin)
    //    {
    //        return response()->json($admin);
    //    }

       // Create a new admin
       public function store(StoreAdminRequest $request)
       {
           $validated = $request->validated();
           $validated['password'] = Hash::make($validated['password']);

           $admin = Admin::create($validated);
           return response()->json($admin, 201);
       }

       // Update an existing admin
       public function update(UpdateAdminRequest $request,  $id)
       {
           $validated = $request->validated();
           $admin =Admin::findOrFail($id);
           if (isset($validated['password'])) {
               $validated['password'] = Hash::make($validated['password']);
           }

           $admin->update($validated);
           return response()->json($admin);
       }

       // Delete an admin
       public function destroy(Admin $admin)
       {
           $admin->delete();
           return response()->json(['message' => 'Admin deleted successfully.']);
       }
}
