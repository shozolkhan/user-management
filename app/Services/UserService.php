<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Collection;

class UserService
{
    // Get all users for DataTables
    public function getAllUsers()
    {
        return User::select(['id', 'name', 'email', 'created_at']);
    }

    // Create a new user
    public function createUser(array $data): User
    {
        return User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password'] ?? 'password123'),
        ]);
    }

    // Update user
    public function updateUser(User $user, array $data): User
    {
        $user->update([
            'name'  => $data['name'],
            'email' => $data['email'],
        ]);

        return $user;
    }

    // Delete user
    public function deleteUser(User $user): bool
    {
        return $user->delete();
    }

    // Export all users (for Excel / PDF)
    public function exportUsers(): Collection
    {
        return User::all();
    }
}
