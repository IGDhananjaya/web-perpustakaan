<?php

namespace App\Livewire;

use App\Models\Buku;
use App\Models\Kategori;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class BukuComponent extends Component
{
    use WithPagination, WithoutUrlPagination;
    protected $paginationTheme = 'bootstrap';
    public $cari, $id, $judul, $penulis, $penerbit, $tahun, $kategori, $isbn, $jumlah;
    public function render()
    {
        if ($this->cari) {
            $data['buku'] = Buku::Where('judul', 'like', '%' . $this->cari . '%')
                ->orWhere('penulis', 'like', '%' . $this->cari . '%')
                ->orWhere('penerbit', 'like', '%' . $this->cari . '%')
                ->orWhere('tahun', 'like', '%' . $this->cari . '%')
                ->orWhere('isbn', 'like', '%' . $this->cari . '%')
                ->paginate(10);
        } else {
            $data['buku'] = Buku::paginate(10);
        }
        $data['buku'] = Buku::paginate(10);
        $data['category'] = Kategori::all();
        $layout['title'] = 'Kelola Buku';
        return view('livewire.buku-component', $data)->layoutData($layout);
    }

    public function store()
    {
        $this->validate([
            'judul' => 'required',
            'penulis' => 'required',
            'penerbit' => 'required',
            'tahun' => 'required',
            'isbn' => 'required',
            'kategori' => 'required',
            'jumlah' => 'required',
        ], [
            'judul.required' => 'Judul tidak boleh kosong',
            'penulis.required' => 'Penulis tidak boleh kosong',
            'penerbit.required' => 'Penerbit tidak boleh kosong',
            'tahun.required' => 'Tahun tidak boleh kosong',
            'isbn.required' => 'ISBN tidak boleh kosong',
            'kategori.required' => 'Kategori tidak boleh kosong',
            'jumlah.required' => 'Jumlah tidak boleh kosong',
        ]);
        Buku::create([
            'judul' => $this->judul,
            'penulis' => $this->penulis,
            'penerbit' => $this->penerbit,
            'tahun' => $this->tahun,
            'isbn' => $this->isbn,
            'kategori_id' => $this->kategori,
            'jumlah' => $this->jumlah
        ]); 
        $this->reset();
        session()->flash('success', 'Buku berhasil ditambahkan');
        return redirect()->route('buku');
    }

    public function edit($id)
    {
        $buku = Buku::find($id);
        $this->id = $buku->id;
        $this->judul = $buku->judul;
        $this->penulis = $buku->penulis;
        $this->penerbit = $buku->penerbit;
        $this->tahun = $buku->tahun;
        $this->isbn = $buku->isbn;
        $this->kategori = $buku->kategori->id;
        $this->jumlah = $buku->jumlah;
    }

    public function update()
    {
        $buku = Buku::find($this->id);
        $buku->update([
            'judul' => $this->judul,
            'penulis' => $this->penulis,
            'penerbit' => $this->penerbit,
            'tahun' => $this->tahun,
            'isbn' => $this->isbn,
            'kategori_id' => $this->kategori,
            'jumlah' => $this->jumlah
        ]);
        $this->reset();
        session()->flash('success', 'Buku berhasil diubah');
        return redirect()->route('buku');
    }

    public function confirm($id)
    {
        $this->id=$id;
    }

    public function destroy()
    {
        $buku = Buku::find($this->id);
        $buku->delete();
        $this->reset();
        session()->flash('success', 'Buku berhasil dihapus');
        return redirect()->route('buku');
    }
}
