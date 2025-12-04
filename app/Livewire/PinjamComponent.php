<?php

namespace App\Livewire;

use App\Models\Buku;
use App\Models\Pinjam;
use App\Models\User;
use Date;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class PinjamComponent extends Component
{
    use WithPagination, WithoutUrlPagination;
    protected $paginationTheme = 'bootstrap';
    public $cari, $id, $user, $buku, $tgl_pinjam, $tgl_kembali;
    public function render()
    {
        if ($this->cari) {
            $data['member'] = User::Where('nama', 'like', '%' . $this->cari . '%')
                ->orWhere('email', 'like', '%' . $this->cari . '%')
                ->get();
            $data['buku'] = Buku::Where('judul', 'like', '%' . $this->cari . '%')
                ->orWhere('penulis', 'like', '%' . $this->cari . '%')
                ->orWhere('penerbit', 'like', '%' . $this->cari . '%')
                ->orWhere('tahun', 'like', '%' . $this->cari . '%')
                ->orWhere('isbn', 'like', '%' . $this->cari . '%')
                ->get();
            $data['pinjam'] = Pinjam::Where('tanggal_pinjam', 'like', '%' . $this->cari . '%')
                ->orWhere('tanggal_kembali', 'like', '%' . $this->cari . '%')
                ->paginate(10);
        }
        $data['member'] = User::where('jenis', 'member')->get();
        $data['book'] = Buku::all();
        $data['pinjam'] = Pinjam::paginate(10);
        $layout['title'] = 'Pinajam Buku';
        return view('livewire.pinjam-component', $data)->layoutData($layout);
    }

    public function store()
    {
        $this->validate([
            'user' => 'required',
            'buku' => 'required',
        ], [
            'user.required' => 'Member tidak boleh kosong',
            'buku.required' => 'Buku tidak boleh kosong',
        ]);
        $this-> tgl_pinjam = date('Y-m-d');
        $this-> tgl_kembali = date('Y-m-d', strtotime($this-> tgl_pinjam . ' + 7 days'));
        Pinjam::create([
            'user_id' => $this->user,
            'buku_id' => $this->buku,
            'tanggal_pinjam' => $this-> tgl_pinjam,
            'tanggal_kembali' => $this-> tgl_kembali,
            'status' => 'pinjam',
        ]);
        $this ->reset();
        session()->flash('success', 'Pinjam berhasil ditambahkan');
        return redirect()->route('pinjam');
    }

    public function edit($id)
    {
        $pinjam = Pinjam::find($id);
        $this->id = $pinjam->id;
        $this->user = $pinjam->user_id;
        $this->buku = $pinjam->buku_id;
        $this-> tgl_pinjam = $pinjam->tanggal_pinjam;
        $this-> tgl_kembali = $pinjam->tanggal_kembali;
    }

    public function update()
    {
        $pinjam = Pinjam::find($this->id);
        $pinjam->update([
            'user_id' => $this->user,
            'buku_id' => $this->buku,
            'tanggal_pinjam' => $this-> tgl_pinjam,
            'tanggal_kembali' => $this-> tgl_kembali,
            'status' => 'pinjam',
        ]);
        $this->reset();
        session()->flash('success', 'Pinjam berhasil diubah');
        return redirect()->route('pinjam');
    }

    public function confirm($id)
    {
        $this->id = $id;
    }

    public function destroy()
    {
        $pinjam = Pinjam::find($this->id);
        $pinjam->delete();
        $this->reset();
        session()->flash('success', 'Pinjam berhasil dihapus');
        return redirect()->route('pinjam');
    }
}
