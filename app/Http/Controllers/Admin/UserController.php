<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin|super-admin']);
    }

    public function index()
    {
        $users = User::with('roles')->paginate(20);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'phone' => 'nullable|string|max:20',
            'role' => 'required|exists:roles,name',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'is_active' => true,
        ]);

        $user->assignRole($request->role);

        return redirect()->route('admin.users.index')->with('success', 'تم إنشاء المستخدم بنجاح');
    }

    public function show(User $user)
    {
        $user->load('roles', 'specialization');
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        $user->load('roles');
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'is_active' => 'boolean',
        ]);

        $user->update($request->only(['name', 'email', 'phone', 'is_active']));

        if ($request->has('role')) {
            $user->syncRoles([$request->role]);
        }

        return redirect()->route('admin.users.index')->with('success', 'تم تحديث المستخدم بنجاح');
    }

    public function destroy(User $user)
    {
        // Prevent deleting super admin
        if ($user->hasRole('super-admin')) {
            return redirect()->back()->with('error', 'لا يمكن حذف مدير النظام الرئيسي');
        }

        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'تم حذف المستخدم بنجاح');
    }

    public function activate(User $user)
    {
        $user->update(['is_active' => true]);
        return redirect()->back()->with('success', 'تم تفعيل المستخدم بنجاح');
    }

    public function deactivate(User $user)
    {
        // Prevent deactivating super admin
        if ($user->hasRole('super-admin')) {
            return redirect()->back()->with('error', 'لا يمكن إلغاء تفعيل مدير النظام الرئيسي');
        }

        $user->update(['is_active' => false]);
        return redirect()->back()->with('success', 'تم إلغاء تفعيل المستخدم بنجاح');
    }

    public function assignRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|exists:roles,name',
        ]);

        $user->syncRoles([$request->role]);
        return redirect()->back()->with('success', 'تم تحديث دور المستخدم بنجاح');
    }
}