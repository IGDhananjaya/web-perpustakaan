<?php

namespace App\Livewire;
// use Illuminate\Contracts\View\View::layoutData();
use App\Models\Buku;
use App\Models\Pengembalian;
use App\Models\Pinjam;
use App\Models\User;
use Livewire\Component;

class HomeComponent extends Component
{
    public function render()
    {
        $x['title'] = 'Home Perpustakaan';
        $data['member'] = User::where('jenis', 'member')->count();
        $data['buku'] = Buku::count();
        $data['pinjam'] = Pinjam::where('status', 'pinjam')->count();
        $data['kembali'] = Pengembalian::count();
        // Tambahkan $data sebagai parameter kedua
        return view('livewire.home-component', $data)->layoutData($x);
    }
}
