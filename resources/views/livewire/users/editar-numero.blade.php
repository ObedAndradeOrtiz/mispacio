<div>
    @if ($numero == 3)
        <div style="background-color: red">
            <input type="number" wire:model.debounce.500ms="numero"style="width: 55px;" />
        </div>
    @else
        <input type="number" wire:model.debounce.500ms="numero" style="width: 55px;" />
    @endif

</div>
