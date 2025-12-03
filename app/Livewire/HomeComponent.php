<?php

namespace App\Livewire;
// use Illuminate\Contracts\View\View::layoutData();
use Livewire\Component;

class HomeComponent extends Component
{
    public function render()
    {
        $x ['title']= 'Home Perpustakaan';
        return view('livewire.home-component')->layoutData($x);
    }
}
