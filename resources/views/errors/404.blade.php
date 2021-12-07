@extends('layouts.error')
@section('content')
<h1 class="font-bold text-blue-600 text-9xl">404</h1>
<p
  class="mb-2 text-2xl font-bold text-center text-gray-800 md:text-3xl"
>
  <span class="text-red-500">Oops!</span> Page non trouvée
</p>
<p class="mb-8 text-center text-gray-500 md:text-lg">
    La page que vous chercher de trouver n'existe pas.
</p>
@endsection
@section('image')
<img 
src="{{ asset('images/404.svg') }}"
alt="img"
class="object-cover w-200 h-200"
/>
@endsection
