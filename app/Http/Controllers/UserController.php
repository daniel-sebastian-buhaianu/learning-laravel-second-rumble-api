<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return User::paginate();
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return $user;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'email' => ['email', 'max:255', Rule::unique('users')->ignore($user)],
            'is_admin' => ['boolean']
        ]);

        try {
            $user->update([
                'email' => $request->input('email', $user->email)
            ]);
        } catch(\Exception $e) {
            return response([
                'message' => $e->getMessage()
            ], 500);    
        }

        if ($request->user()->cannot('updateIsAdmin', $user)) {
            return $user;
        }

        try {
            $user->update([
                'is_admin' => $request->input('is_admin', $user->is_admin),
            ]);

            return $user;
        } catch(\Exception $e) {
            return response([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        return $user->delete();
    }
}
