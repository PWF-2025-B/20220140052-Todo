<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        if ($search) {
            $users = User::where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
            })
            ->where('id', '!=', 1)
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();
        } else {
            $users = User::where('id', '!=', 1)
                         ->orderBy('name')
                         ->paginate(20);
        }

        return view('user.index', compact('users'));
    }

    public function makeadmin(User $user)
    {
        if ($user->id == 1) {
            return back()->with('danger', 'Cannot change this user.');
        }

        $user->timestamps = false;
        $user->is_admin = true;
        $user->save();

        return back()->with('success', 'Make admin successfully!');
    }

    public function removeadmin(User $user)
    {
        if ($user->id == 1) {
            return back()->with('danger', 'Cannot remove admin rights from this user.');
        }

        $user->timestamps = false;
        $user->is_admin = false;
        $user->save();

        return back()->with('success', 'Remove admin successfully!');
    }

    public function destroy(User $user)
    {
        if ($user->id != 1) {
            $user->delete();
            return back()->with('success', 'Delete user successfully!');
        }

        return redirect()->route('user.index')->with('danger', 'Delete user failed!');
    }
}