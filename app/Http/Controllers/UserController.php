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
        $this->authorize('viewAny', User::class);

        return User::paginate();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::find($id);

        $this->authorize('view', $user);

        return $user;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::find($id);

        $this->authorize('update', $user);

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
    public function destroy(string $id)
    {
        $user = User::find($id);

        $this->authorize('delete', $user);

        return $user->delete();
    }
}
