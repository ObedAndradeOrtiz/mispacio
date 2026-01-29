<div>
    @php
    $cantidad = DB::table('mensajes')
        ->where('receptor', Auth::user()->id)
        ->where('estado', 'A')
        ->count();
@endphp
<label>{{ $cantidad}}</label>
</div>
