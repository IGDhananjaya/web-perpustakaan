<div>
    <div class="card">
        <div class="card-header">
            Pengembalian Buku
        </div>
        <div class="card-body">
            @if (session()->has('success'))
                <div class="alert alert-success" role="alert">
                    {{ session('success') }}
                </div>
            @endif
            <input type="text" class="form-contol w-50" wire:model.live="cari" placeholder="Cari...">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">No.</th>
                            <th scope="col">Buku</th>
                            <th scope="col">Member</th>
                            <th scope="col">Tanggal Pinjam</th>
                            <th scope="col">Tanggal Kembali</th>
                            <th>Proses</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pinjam as $data)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td>{{ $data->buku->judul }}</td>
                                <td>{{ $data->user->nama }}</td>
                                <td>{{ $data->tgl_pinjam }}</td>
                                <td>{{ $data->tgl_kembali }}</td>
                                <td>
                                    <button type="button" wire:click="pilih({{ $data->id }})"
                                        class="btn btn-sm btn-success" data-toggle="modal" data-target="#pilih">
                                        Pilih
                                    </button>
                                    {{-- <a href="#" wire:click="edit({{ $data->id }})" class="btn btn-sm btn-info"
                                        data-toggle="modal" data-target="#editPage"> Ubah </a>
                                    <a href="#" wire:click="confirm({{ $data->id }})"
                                        class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deletePage">
                                        Hapus </a> --}}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $pinjam->links() }}
            </div>
            {{-- <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#addPage"> Tambah Member </a> --}}
        </div>
    </div>

    {{-- History --}}
    <div class="card">
        <div class="card-header">
            History Pengembalian Buku
        </div>
        <div class="card-body">
            @if (session()->has('success'))
                <div class="alert alert-success" role="alert">
                    {{ session('success') }}
                </div>
            @endif
            <input type="text" class="form-contol w-50" wire:model.live="cari" placeholder="Cari...">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">No.</th>
                            <th scope="col">ID Pinjam</th>
                            <th scope="col">Tanggal Kembali</th>
                            <th scope="col">Denda</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pengembalian as $data)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td>{{ $data->pinjam_id }}</td>
                                <td>{{ $data->tgl_kembali }}</td>

                                <td>Rp. {{ number_format($data->denda, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $pengembalian->links() }}
            </div>
        </div>
    </div>

    {{-- Pilih --}}
    <div wire:ignore.self class="modal fade" id="pilih" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Form Pengembalian</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            Judul Buku
                        </div>
                        <div class="col-md-8">
                            : {{ $judul }}
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            Member
                        </div>
                        <div class="col-md-8">
                            : {{ $member }}
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            Tanggal Kembali
                        </div>
                        <div class="col-md-8">
                            : {{ $tgl_kembali }}
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            Tanggal Hari Ini
                        </div>
                        <div class="col-md-8">
                            : {{ date('Y-m-d') }}
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            Denda
                        </div>
                        <div class="col-md-8">
                            : @if ($this->status == true)
                                ya
                            @else
                                tidak
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            Lama Terlambat
                        </div>
                        <div class="col-md-8">
                            : {{ $lama }} Hari
                            @if ($lama == 0)
                                <span class="badge badge-success">(Tepat Waktu)</span>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            Jumlah Denda
                        </div>
                        <div class="col-md-8">
                            : Rp.{{ $lama * 1000 }}
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" wire:click="store" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </div>
</div>
