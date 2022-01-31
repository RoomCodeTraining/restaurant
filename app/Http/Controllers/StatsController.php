<?php

namespace App\Http\Controllers;

use App\Models\Dish;
use App\Models\Order;
use App\Models\UserType;
use Illuminate\Http\Request;
use App\States\Order\Completed;
use App\States\Order\Confirmed;

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
      foreach (Order::whereState('state',[Confirmed::class, Completed::class])->get() as $key => $order) {
          foreach ($types as $key => $value) {
  
              if(!empty($data) && isset($data[$value->name])){
                $data[$value->name] += 1;
              }

              if($value->id == $order->user->user_type_id){
                 $data[$value->name] = 1;
              }

          }
      }
      
      return view('statistics.users', compact('data'));
    } 
}
