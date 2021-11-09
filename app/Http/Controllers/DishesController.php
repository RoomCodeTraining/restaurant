<?php

namespace App\Http\Controllers;

use App\Models\Dish;
use App\Models\DishType;
use Illuminate\Http\Request;

class DishesController extends Controller
{
    public function index()
    {
        return view('dishes.index');
    }

    public function create()
    {
        $dishesTypes = DishType::all();
        return view('dishes.create');
    }

    public function show(Dish $dish)
    {
        return view('dishes.show', compact('dish'));
    }

    public function edit(Dish $dish)
    {
        return view('dishes.edit', compact('dish'));
    }

    public function destroy(Dish $dish)
    {
        $dish->delete();

        return redirect()->route('dishes.index');
    }
}
