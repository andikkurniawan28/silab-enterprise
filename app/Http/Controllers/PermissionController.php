<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Setup;
use App\Models\Feature;
use App\Models\Permission;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PermissionController extends Controller
{
    public function index()
    {
        $setup = Setup::init();
        $roles = Role::all();
        return view('permission.index', compact('setup', 'roles'));
    }

    public function adjust($role_id)
    {
        $setup = Setup::init();
        $features = Feature::all();
        $permissions = Permission::where('role_id', $role_id)->get();
        $role = Role::whereId($role_id)->get()->last();
        return view('permission.adjust', compact('setup', 'features', 'permissions', 'role'));
    }

    public function update(Request $request, $role_id)
    {
        $request->validate([
            'feature_ids' => 'nullable|array',
            'feature_ids.*' => 'exists:features,id',
        ]);

        DB::transaction(function () use ($request, $role_id) {
            Permission::where('role_id', $role_id)->delete();
            if (!empty($request->feature_ids)) {
                $permissions = array_map(function ($feature_id) use ($role_id) {
                    return [
                        'role_id' => $role_id,
                        'feature_id' => $feature_id,
                    ];
                }, $request->feature_ids);
                Permission::insert($permissions);
            }
        });

        ActivityLog::insert([
            "user_id" => Auth::id(),
            "description" => "Permission for role '{$request->role}' were updated.",
        ]);

        return redirect()->back()->with('success', 'Permission has been updated');
    }
}
