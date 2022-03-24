<?php
use App\Models\Todo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function PHPUnit\Framework\assertTrue;

beforeEach(function () {
  \Illuminate\Support\Facades\Artisan::call('migrate');
  \Pest\Laravel\seed();

  $this->evaluationDate = '2021-12-31'; //Date d'evaluation 2021-12-31
  return assertTrue('/login', 'GET');
});
