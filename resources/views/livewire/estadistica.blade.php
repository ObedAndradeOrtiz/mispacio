<div>
    @if ($tipo == 'pagos')
        <x-panel-show :presionado="19" :areas="$areas" :sucursal="$sucursalName" :tipo="$tipo"></x-panel-show>
    @endif
    @if ($tipo == 'citas')
        <x-panel-show :presionado="109" :areas="$areas" :sucursal="$sucursalName" :tipo="$tipo"></x-panel-show>
    @endif

</div>
