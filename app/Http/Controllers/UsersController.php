<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\States\Order\Cancelled;
use App\States\Order\Completed;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(User::class, 'user');
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
        $latestOrders = $user->orders()
            ->whereNotState('state', Cancelled::class)
            ->join('menus', 'menus.id', '=', 'orders.menu_id')
            ->latest('menus.served_at')
            ->limit(5)->get();

        return view('users.show', [
            'user' => $user->load('accessCard', 'role', 'organization', 'department', 'employeeStatus', 'userType'),
            'totalOrders' => $user->orders()->count(),
            'totalOrdersCompleted' => $user->orders()->whereState('state', Completed::class)->count(),
            'latestOrders' => $user->orders()
                ->whereNotState('state', Cancelled::class)
                ->join('menus', 'menus.id', '=', 'orders.menu_id')
                ->latest('menus.served_at')
                ->limit(5)->get()
        ]);
    }

    public function edit(User $user)
    {
        return view('users.edit', [
            'user' => $user->load('accessCard', 'role', 'organization', 'department', 'employeeStatus', 'userType')
        ]);
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
