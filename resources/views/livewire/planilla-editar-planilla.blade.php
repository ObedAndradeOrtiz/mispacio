<div>
    <style>
        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
            padding: 12px 16px;
            z-index: 1;
        }

        .dropdown-btn {
            background-color: transparent;
            border: none;
            cursor: pointer;
        }

        .show {
            display: block;
        }
    </style>
    <td style="max-width: 500px; overflow-wrap: break-word;">{{ $planillasueldousuario->nombre }}
    </td>
    <td style="max-width: 100px;"><input type="number" wire:model="planillasueldousuario.haberbasico" style="width: 100%;">
    </td>
    <td style="max-width: 100px;"><input type="number" wire:model="planillasueldousuario.sueldohora" style="width: 100%;">
    </td>
    <td style="max-width: 100px;"><input type="number" wire:model="planillasueldousuario.horasdias"
            style="width: 100%;"></td>
    <td style="max-width: 100px;"><input type="number" wire:model="planillasueldousuario.diastrabajados"
            style="width: 100%;"></td>
    <td style="max-width: 100px;"><input type="number" wire:model="planillasueldousuario.horasextras"
            style="width: 100%;"></td>
    <td style="max-width: 100px;"><input type="number" wire:model="planillasueldousuario.bonos" style="width: 100%;">
    </td>
    <td style="max-width: 100px;"><input type="number" wire:model="planillasueldousuario.anticipo"
            style="width: 100%;"></td>


    <td></td>
</div>
