<?php

namespace App\Http\Controllers;

use App\Models\Dish;
use App\Models\Order;
use App\Models\UserType;
use Illuminate\Http\Request;

class StatsController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function dishStats()
    {
        $dishes = Dish::with('orders')->get();
        return view('statistics.dishes', compact('dishes'));
    }

    public function usersTypeStats(){
       $data = [];
      $types = UserType::with('users')->get();
      foreach (Order::all() as $key => $order) {
          foreach ($types as $key => $value) {
              if($value->id == $order->user->user_type_id){
                 $data[$value->name] += 1;
              }
          }
      }

       $data = Collect($data);
      return view('statistics.users', compact('data'));
    } 
}
