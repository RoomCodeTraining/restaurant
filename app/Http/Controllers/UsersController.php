<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function __construct()
    {
        // $this->authorizeResource(User::class, 'user');
    }

    public function index()
    {
        return view('users.index');
    }

    public function create()
    {
        return view('users.create');
    }

    public function show(Request $request, User $user)
    {
        return view('users.show', [
            'user' => $user->load('accessCard', 'role', 'organization', 'department', 'employeeStatus', 'userType'),
            'totalOrders' => Order::where('user_id', $user->id)->count(),
            'totalOrdersCompleted' => Order::where(['user_id' => $user->id, 'is_completed' => true])->count(),
            'latestOrders' => Order::where('user_id', $user->id)->latest()->limit(5)->get()
        ]);
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $user->update($request->all());

        return redirect()->route('users.show', $user)->banner('This is great');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('users.index');
    }
}
