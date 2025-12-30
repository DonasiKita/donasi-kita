@extends('admin.layouts.app')

@section('title', 'Edit Kampanye: ' . $campaign->title)

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3 mb-0">Edit Kampanye: {{ $campaign->title }}</h1>
                <a href="{{ route('admin.campaigns.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i> Kembali
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.campaigns.update', $campaign->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="title" class="form-label">Judul Kampanye *</label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror"
                                           id="title" name="title" value="{{ old('title', $campaign->title) }}" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">Deskripsi *</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror"
                                              id="description" name="description" rows="6" required>{{ old('description', $campaign->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="target_amount" class="form-label">Target Donasi (Rp) *</label>
                                            <input type="number" class="form-control @error('target_amount') is-invalid @enderror"
                                                   id="target_amount" name="target_amount"
                                                   value="{{ old('target_amount', $campaign->target_amount) }}" min="1000" required>
                                            @error('target_amount')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="current_amount" class="form-label">Terkumpul (Rp)</label>
                                            <input type="number" class="form-control @error('current_amount') is-invalid @enderror"
                                                   id="current_amount" name="current_amount"
                                                   value="{{ old('current_amount', $campaign->current_amount) }}" min="0">
                                            @error('current_amount')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="image" class="form-label">Gambar Kampanye</label>
                                    <div class="border rounded p-3 text-center mb-3"
                                         style="background: #f8f9fa; min-height: 200px;"
                                         id="imagePreview">
                                        @if($campaign->image_url)
                                            <img src="{{ $campaign->image_url }}"
                                                 class="img-fluid rounded"
                                                 style="max-height: 200px; object-fit: cover;">
                                        @else
                                            <i class="fas fa-image fa-3x text-muted mb-3"></i>
                                            <p class="text-muted mb-0">Belum ada gambar</p>
                                        @endif
                                    </div>
                                    <input type="file" class="form-control @error('image') is-invalid @enderror"
                                           id="image" name="image" accept="image/*">
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    @if($campaign->image_url)
                                        <div class="form-check mt-2">
                                            <input class="form-check-input" type="checkbox"
                                                   id="remove_image" name="remove_image" value="1">
                                            <label class="form-check-label" for="remove_image">
                                                Hapus gambar
                                            </label>
                                        </div>
                                    @endif
                                </div>

                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox"
                                               id="is_active" name="is_active" value="1"
                                               {{ old('is_active', $campaign->is_active) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            Aktifkan kampanye
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.campaigns.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Image preview
    document.getElementById('image').addEventListener('change', function(e) {
        const preview = document.getElementById('imagePreview');
        const file = e.target.files[0];

        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.innerHTML = `
                    <img src="${e.target.result}"
                         class="img-fluid rounded"
                         style="max-height: 200px; object-fit: cover;">
                `;
            }
            reader.readAsDataURL(file);
        }
    });

    // Format amount inputs
    const amountInputs = ['target_amount', 'current_amount'];
    amountInputs.forEach(id => {
        document.getElementById(id)?.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            e.target.value = value;
        });
    });
</script>
@endsection
