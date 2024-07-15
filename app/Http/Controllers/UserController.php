<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function show()
    {
        $user = Auth::user();
        $user->load('addresses');
        return response()->json($user);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $data = $request->only(['name', 'email']);
        
        if ($request->has('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);
        return response()->json($user);
    }

    public function destroy()
    {
        $user = Auth::user();
        $user->delete();
        return response()->json(['message' => 'Liven API informs: User deleted successfully']);
    }
}
