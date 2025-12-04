<?php

namespace App\Livewire;

use App\Models\Pengembalian;
use App\Models\Pinjam;
use DateTime;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class KembaliComponent extends Component
{
    use WithPagination, WithoutUrlPagination;
    protected $paginationTheme = 'bootstrap';
    public $judul, $member, $tgl_kembali, $id, $tgl_pinjam, $today, $selisih, $status, $lama;
    public function render()
    {
        $layout['title'] = 'Pengembalian Buku';
        $data['pinjam'] = Pinjam::where('status', 'pinjam')->paginate(10);
        $data['pengembalian'] = Pengembalian::paginate(10);
        return view('livewire.kembali-component', $data)->layoutData($layout);
    }

    public function pilih($id)
    {
        $pinjam = Pinjam::find($id);

        // PENTING: Simpan ID agar bisa dipakai di function store()
        $this->id = $id;

        $this->judul = $pinjam->buku->judul;
        $this->member = $pinjam->user->nama;
        $this->tgl_kembali = $pinjam->tgl_kembali;

        $kembali = new DateTime($this->tgl_kembali); // Tanggal jatuh tempo
        $today = new DateTime(); // Tanggal hari ini (pengembalian)

        $selisih = $today->diff($kembali);

        // Cek status keterlambatan
        // invert = 1 artinya $today lebih besar dari $kembali (Telat)
        if ($selisih->invert == 1) {
            $this->status = true;
            $this->lama = $selisih->d; // Ambil jumlah hari telat
        } else {
            $this->status = false;
            $this->lama = 0; // <--- UBAH DISINI: Jika tepat waktu/lebih cepat, anggap 0 hari
        }
    }

    public function store()
    {
        if ($this->status == true) {
            $denda = $this->lama * 1000;
        } else {
            $denda = 0;
        }
        $pinjam = Pinjam::find($this->id);
        Pengembalian::create([
            'pinjam_id' => $this->id,
            'tgl_kembali' => date('Y-m-d'),
            'denda' => $denda
        ]);
        $pinjam->update([
            'status' => 'kembali',
        ]);
        $this->reset();
        session()->flash('success', 'Pengembalian Berhasil');
        return redirect()->route('kembali');
    }
}
