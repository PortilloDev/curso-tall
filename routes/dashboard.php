<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Models\Subscriber;
use App\Http\Controllers\SubscriberController;

Route::get('/', function () {
    return view('dashboard');
})->name('dashboard');


Route::get('subscriber', [SubscriberController::class, 'all'])->name('subscriber.all');
