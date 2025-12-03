<?php

use App\Livewire\HomeComponent;
use App\Livewire\LoginComponent;
use Illuminate\Support\Facades\Route;

// CARA YANG BENAR: Panggil class langsung (seperti route login di bawahnya)
Route::get('/', HomeComponent::class)->middleware('auth')->name('home');

Route::get('/login', LoginComponent::class)->name('login');
Route::get('/logout', [LoginComponent::class, 'keluar'])->name('logout');