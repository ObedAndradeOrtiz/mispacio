<div>
    @if ($abono<200)
    <div class="form-group">
        <label class="form-label" for="">Cuota 1:</label>
        <input type="number" class="form-control" id="exampleInputDisabled1" wire:model.defer="cuota1" value="{{$abono}}">
    </div>
    @endif
    @if ($abono>=200 && $abono<=1000)
    <div class="form-group">
        <label class="form-label" for="">Cuota 1:</label>
        <input type="number" class="form-control" id="exampleInputDisabled1" wire:model.defer="cuota1" value="{{$abono/2}}">
    </div>
    <div class="form-group">
        <label class="form-label" for="">Cuota 2:</label>
        <input type="number" class="form-control" id="exampleInputDisabled1" wire:model.defer="cuota2" value="{{$abono/2}}">
    </div>
    @endif
</div>
