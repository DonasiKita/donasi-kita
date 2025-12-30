@extends('admin.layouts.app')

@section('title', 'Kelola Kampanye')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3 mb-0">Kelola Kampanye</h1>
                <a href="{{ route('admin.campaigns.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i> Tambah Kampanye
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th width="50">#</th>
                                    <th>Judul</th>
                                    <th>Target</th>
                                    <th>Terkumpul</th>
                                    <th>Progress</th>
                                    <th>Status</th>
                                    <th width="150">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($campaigns as $campaign)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($campaign->image_url)
                                                <img src="{{ $campaign->image_url }}"
                                                     alt="{{ $campaign->title }}"
                                                     class="rounded me-2"
                                                     style="width: 50px; height: 50px; object-fit: cover;">
                                            @endif
                                            <div>
                                                <strong>{{ $campaign->title }}</strong><br>
                                                <small class="text-muted">{{ $campaign->created_at->format('d M Y') }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $campaign->formatted_target }}</td>
                                    <td>{{ $campaign->formatted_current }}</td>
                                    <td>
                                        <div class="progress" style="height: 8px;">
                                            <div class="progress-bar bg-success"
                                                 role="progressbar"
                                                 style="width: {{ $campaign->progress_percentage }}%">
                                            </div>
                                        </div>
                                        <small class="text-muted">{{ number_format($campaign->progress_percentage, 1) }}%</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $campaign->is_active ? 'success' : 'secondary' }}">
                                            {{ $campaign->is_active ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.campaigns.show', $campaign->id) }}"
                                           class="btn btn-sm btn-info" title="Lihat">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.campaigns.edit', $campaign->id) }}"
                                           class="btn btn-sm btn-warning" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.campaigns.destroy', $campaign->id) }}"
                                              method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Yakin ingin menghapus?')" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">Belum ada kampanye</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center mt-4">
                        {{ $campaigns->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
