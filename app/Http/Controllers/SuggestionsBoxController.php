<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SuggestionBox;

class SuggestionsBoxController extends Controller
{
    public function index(){
      return view('suggestions.index');
    }

    public function create(){
      return view('suggestions.create');
    }

    public function show(SuggestionBox $suggestion){
       return view('suggestions.show', compact('suggestion'));
    }

    public function edit(int $suggestion){
      
      return view('suggestions.edit', compact('suggestion'));
    }
}
