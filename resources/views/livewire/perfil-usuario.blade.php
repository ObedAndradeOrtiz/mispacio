<div>
    <form wire:submit.prevent="guardarFoto">
        <div class="mb-3">
            <label class="form-label fw-semibold">Foto de perfil</label>
            <input type="file" wire:model="foto" class="form-control">
            @error('foto')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        @if ($foto)
            <div class="mb-3">
                <p class="fw-semibold">Previsualización:</p>
                <img src="{{ $foto->temporaryUrl() }}" width="150" class="rounded">
            </div>
        @endif

        <button type="submit" class="btn btn-primary">Guardar Foto</button>
    </form>

    @if (session('mensaje'))
        <div class="mt-3 alert alert-success">{{ session('mensaje') }}</div>
    @endif

</div>
