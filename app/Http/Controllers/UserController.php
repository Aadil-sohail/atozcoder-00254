<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view users')->only('index');
        $this->middleware('permission:create users')->only(['create', 'store']);
        $this->middleware('permission:edit users')->only(['edit', 'update']);
        $this->middleware('permission:delete users')->only('destroy');
        $this->middleware('permission:create users|edit users')->only(['checkEmail', 'checkUsername']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $users = User::with('roles')->orderBy('name')->paginate(10);
        $roles = Role::orderBy('name')->pluck('name');

        return view('users.index', compact('users', 'roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request): RedirectResponse
    {
        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        if ($request->filled('role')) {
            $user->assignRole($request->role);
        }

        return redirect()->route('users.index')->with('status', 'User created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user): View
    {
        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $user->name     = $request->name;
        $user->username = $request->username;
        $user->email    = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        $user->syncRoles($request->filled('role') ? [$request->role] : []);

        return redirect()->route('users.index')->with('status', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user): RedirectResponse
    {
        if ($user->is(auth()->user())) {
            return redirect()->route('users.index')->with('error', "You can't delete your own account.");
        }

        $user->delete();

        return redirect()->route('users.index')->with('status', 'User deleted successfully.');
    }


     // ajax function for checking email availability
    public function checkEmail(Request $request)
    {
        $email = $request->input('email');
        $userId = $request->input('user_id');
        if (empty($email)) {
            return response()->json(['available' => false, 'message' => 'Email is required']);
        }

        $query = User::where('email', $email)->where('close', '1');

        if ($userId) {
            $query->where('id', '!=', $userId);
        }

        $exists = $query->exists();

        return response()->json([
            'available' => !$exists,
            'message' => $exists ? 'The email has already been taken.' : 'Email is available.'
        ]);
    }

    // ajax function for checking username availability
    public function checkUsername(Request $request)
    {
        $username = $request->input('username');
        $userId = $request->input('user_id');

        if (empty($username)) {
            return response()->json(['available' => false, 'message' => 'Username is required']);
        }

        $query = User::where('username', $username)->where('close', '1');

        if ($userId) {
            $query->where('id', '!=', $userId);
        }

        $exists = $query->exists();

        return response()->json([
            'available' => !$exists,
            'message' => $exists ? 'The username has already been taken.' : 'Username is available.'
        ]);
    }
}
