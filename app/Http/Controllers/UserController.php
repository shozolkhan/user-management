<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Services\UserService;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    // Show users page
    public function index()
    {
        return view('users.index');
    }

    // DataTables JSON
    public function data(Request $request)
    {
        $query = $this->userService->getAllUsers();

        return DataTables::of($query)
            ->addColumn('action', function ($user) {
                return '
                    <button 
                        class="editBtn"
                        data-id="' . $user->id . '"
                        data-name="' . $user->name . '"
                        data-email="' . $user->email . '"
                    >
                        Edit
                    </button>

                    <button onclick="deleteUser(' . $user->id . ')">
                        Delete
                    </button>
                ';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    // Store new user
    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
        ]);

        $this->userService->createUser($request->only('name', 'email'));

        return response()->json(['success' => true]);
    }

    // Update existing user
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        $this->userService->updateUser($user, $request->only('name', 'email'));

        return response()->json(['success' => true]);
    }

    // Delete user
    public function destroy(User $user)
    {
        $this->userService->deleteUser($user);

        return response()->json(['success' => true]);
    }
}
