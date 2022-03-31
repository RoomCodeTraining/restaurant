<?php

namespace App\Http\Controllers;

use App\Models\AccessCard;
use Illuminate\Http\Request;

class AccessCardsController extends Controller
{
    public function reload(AccessCard $accessCard)
    {
        $accessCard->load('user', 'paymentMethod');
        return view('access-cards.reload', compact('accessCard'));
    }
}
