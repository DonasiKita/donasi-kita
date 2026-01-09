@extends('admin.layouts.app')

@section('title', 'Kelola Kampanye')

@section('content')
    <div class="container-fluid">

        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Daftar Kampanye</h1>
            <a href="{{ route('admin.campaigns.create') }}" class="btn btn-primary shadow-sm">
                <i class="fas fa-plus fa-sm text-white-50 me-2"></i> Tambah Kampanye Baru
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Data Semua Kampanye</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle" id="dataTable" width="100%"
                        cellspacing="0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 5%">No</th>
                                <th style="width: 10%">Gambar</th>
                                <th style="width: 25%">Judul Kampanye</th>
                                <th style="width: 15%">Target</th>
                                <th style="width: 20%">Terkumpul</th>
                                <th style="width: 10%">Status</th>
                                <th style="width: 15%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($campaigns as $key => $campaign)
                                <tr>
                                    <td class="text-center">{{ $campaigns->firstItem() + $key }}</td>
                                    <td>
                                        <img src="{{ $campaign->image_url }}" alt="Img" class="img-thumbnail"
                                            style="width: 80px; height: 50px; object-fit: cover;">
                                    </td>
                                    <td>
                                        <span class="fw-bold d-block">{{ Str::limit($campaign->title, 40) }}</span>
                                        <small class="text-muted">
                                            <i class="fas fa-calendar-alt me-1"></i>
                                            {{ $campaign->deadline ? $campaign->deadline->format('d M Y') : 'Tanpa Batas' }}
                                        </small>
                                    </td>
                                    <td>Rp {{ number_format($campaign->target_amount, 0, ',', '.') }}</td>
                                    <td>
                                        <div class="d-flex justify-content-between small mb-1">
                                            <span>Rp {{ number_format($campaign->current_amount, 0, ',', '.') }}</span>
                                            <span class="fw-bold">{{ $campaign->progress_percentage }}%</span>
                                        </div>
                                        <div class="progress" style="height: 6px;">
                                            <div class="progress-bar bg-success" role="progressbar"
                                                style="width: {{ $campaign->progress_percentage }}%"></div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        @if ($campaign->is_active)
                                            <span class="badge bg-success">Aktif</span>
                                        @else
                                            <span class="badge bg-secondary">Non-Aktif</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">

                                            {{-- TOMBOL BARU: DONASI --}}
                                            {{-- Mengarah ke form donasi publik --}}
                                            <a href="{{ route('donation.create', ['campaign_id' => $campaign->id]) }}"
                                                class="btn btn-success btn-sm" title="Donasi" target="_blank">
                                                <i class="fas fa-hand-holding-heart"></i>
                                            </a>

                                            {{-- Tombol Edit --}}
                                            <a href="{{ route('admin.campaigns.edit', $campaign->id) }}"
                                                class="btn btn-warning btn-sm" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            {{-- Tombol Delete --}}
                                            <form action="{{ route('admin.campaigns.destroy', $campaign->id) }}"
                                                method="POST" class="d-inline"
                                                onsubmit="return confirm('Yakin ingin menghapus kampanye ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <div class="text-muted">Belum ada data kampanye.</div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3 d-flex justify-content-end">
                    {{ $campaigns->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
@endsection
