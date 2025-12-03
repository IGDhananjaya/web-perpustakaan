<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class MemberComponent extends Component
{
    use WithPagination, WithoutUrlPagination;
    protected $paginationTheme = 'bootstrap';
    public $nama, $alamat, $telepon, $email, $password, $cari, $id;
    public function render()
    {
        if ($this->cari) {
            $data['member'] = User::Where('nama', 'like', '%' . $this->cari . '%')
                ->orWhere('email', 'like', '%' . $this->cari . '%')
                ->paginate(10);
        } else {
            $data['member'] = User::where('jenis', 'member')->paginate(10);
        }
        $layout['title'] = 'Kelola Member';
        return view('livewire.member-component', $data)->layoutData($layout);
    }

    public function store()
    {
        $this->validate([
            'nama' => 'required',
            'alamat' => 'required',
            'email' => 'required|email',
            'telepon' => 'required',
        ], [
            'nama.required' => 'Nama tidak boleh kosong',
            'email.required' => 'Email tidak boleh kosong',
            'email.email' => 'Email tidak valid',
            'telepon.required' => 'Telepon tidak boleh kosong',
            'alamat.required' => 'Alamat tidak boleh kosong',
        ]);
        User::create([
            'nama' => $this->nama,
            'email' => $this->email,
            'alamat' => $this->alamat,
            'telepon' => $this->telepon,
            'jenis' => 'member'
        ]);
        session()->flash('success', 'Member berhasil ditambahkan');
        return redirect()->route('member');
    }

    public function edit($id)
    {
        $member = User::find($id);
        $this->nama = $member->nama;
        $this->alamat = $member->alamat;
        $this->telepon = $member->telepon;
        $this->email = $member->email;
        $this->id = $member->id;
    }

    public function update()
    {
        $member = User::find($this->id);
        $member->update([
            'nama' => $this->nama,
            'alamat' => $this->alamat,
            'telepon' => $this->telepon,
            'email' => $this->email,
        ]);
        session()->flash('success', 'Member berhasil diubah');
        return redirect()->route('member');
    }

    public function confirm($id)
    {
        $this->id = $id;
    }

    public function destroy()
    {
        $member = User::find($this->id);
        $member->delete();
        session()->flash('success', 'Member berhasil dihapus');
        return redirect()->route('member');
    }
}
