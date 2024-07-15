<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Address;

class AddressController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $query = $user->addresses();

        if ($request->has('country')) {
            $query->where('country', $request->country);
        }

        if ($request->has('city')) {
            $query->where('city', $request->city);
        }
        if ($request->has('state')) {
            $query->where('state', $request->state);
        }
        
        $addresses = $query->get();
        return response()->json($addresses);
    }

    public function show($id)
    {
        $address = Address::where('user_id', Auth::id())->findOrFail($id);
        return response()->json($address);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $address = $user->addresses()->create($request->all());
        return response()->json($address, 201);
    }

    public function update(Request $request, $id)
    {
        $address = Address::where('user_id', Auth::id())->findOrFail($id);
        $address->update($request->all());
        return response()->json($address);
    }

    public function destroy($id)
    {
        $address = Address::where('user_id', Auth::id())->findOrFail($id);
        $address->delete();
        return response()->json(['message' => 'Liven API informs: Address deleted successfully']);
    }
}
