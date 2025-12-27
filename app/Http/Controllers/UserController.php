<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function index()
    {
        return view('users.index');
    }

    public function data(Request $request)
    {
        $query = User::select(['id', 'name', 'email', 'created_at']);

        return DataTables::of($query)
            ->addColumn('action', function ($user) {
                return '
                    <button 
                        class="editBtn"
                        data-id="'.$user->id.'"
                        data-name="'.$user->name.'"
                        data-email="'.$user->email.'"
                    >
                        Edit
                    </button>

                    <button onclick="deleteUser('.$user->id.')">
                        Delete
                    </button>
                ';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => bcrypt('password123'),
        ]);

        return response()->json(['success' => true]);
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
        ]);

        $user->update([
            'name'  => $request->name,
            'email' => $request->email,
        ]);

        return response()->json(['success' => true]);
    }

    public function destroy(User $user)
    {
        $user->delete();

        return response()->json(['success' => true]);
    }
}
