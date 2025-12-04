<?php

namespace App\Livewire;

use App\Models\Buku;
use App\Models\Pinjam;
use App\Models\User;
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
        $pinjamQuery = Pinjam::query();

        if ($this->cari) {
            $pinjamQuery->where(function ($q) {
                $term = '%' . $this->cari . '%';

                // 1. Cari berdasarkan Tanggal (di tabel pinjam sendiri)
                $q->where('tgl_pinjam', 'like', $term)
                    ->orWhere('tgl_kembali', 'like', $term)

                    // 2. Cari berdasarkan Nama Member (Relasi 'user')
                    ->orWhereHas('user', function ($subQuery) use ($term) {
                        $subQuery->where('nama', 'like', $term);
                    })

                    // 3. Cari berdasarkan Judul Buku (Relasi 'buku')
                    ->orWhereHas('buku', function ($subQuery) use ($term) {
                        $subQuery->where('judul', 'like', $term);
                    });
            });
        }

        $data['member'] = User::where('jenis', 'member')->get();
        $data['book'] = Buku::all();
        // Gunakan query yang sudah difilter
        $data['pinjam'] = $pinjamQuery->latest()->paginate(10);

        $layout['title'] = 'Pinjam Buku';
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

        // Logic Tanggal Otomatis
        $this->tgl_pinjam = date('Y-m-d');
        $this->tgl_kembali = date('Y-m-d', strtotime('+7 days'));

        Pinjam::create([
            'user_id' => $this->user,
            'buku_id' => $this->buku,
            // PERBAIKAN DISINI: Gunakan 'tgl_' bukan 'tanggal_'
            'tgl_pinjam' => $this->tgl_pinjam,
            'tgl_kembali' => $this->tgl_kembali,
            'status' => 'pinjam',
        ]);

        $this->reset();
        session()->flash('success', 'Pinjam berhasil ditambahkan');
        return redirect()->route('pinjam');
    }

    public function edit($id)
    {
        $pinjam = Pinjam::find($id);
        $this->id = $pinjam->id;
        $this->user = $pinjam->user_id;
        $this->buku = $pinjam->buku_id;
        // PERBAIKAN: Ambil dari kolom yang benar
        $this->tgl_pinjam = $pinjam->tgl_pinjam;
        $this->tgl_kembali = $pinjam->tgl_kembali;
    }

    public function update()
    {
        $pinjam = Pinjam::find($this->id);

        $pinjam->update([
            'user_id' => $this->user,
            'buku_id' => $this->buku,
            // PERBAIKAN DISINI JUGA
            'tgl_pinjam' => $this->tgl_pinjam,
            'tgl_kembali' => $this->tgl_kembali,
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
        if ($pinjam) {
            $pinjam->delete();
            session()->flash('success', 'Pinjam berhasil dihapus');
        }
        $this->reset();
        return redirect()->route('pinjam');
    }
}