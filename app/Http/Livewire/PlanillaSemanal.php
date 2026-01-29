<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Week;
use App\Models\User;
use App\Models\Areas;
use App\Models\WorkAssignment;
use Carbon\Carbon;

class PlanillaSemanal extends Component
{
    public $weekId;
    public $startDate;
    public $endDate;

    public $days = [];
    public $areas = [];

    public $busqueda = '';
    public $areaseleccionada = '';
    public $rolseleccionado = '';
    public $estadoUser = 'Activo';

    public $selectedDate;

    protected $listeners = [
        'assignUserToDay' => 'assignUserToDay',
        'assignment-updated' => '$refresh',
    ];

    /* -------------------------------
     *   MOUNT
     * ------------------------------*/
    public function mount($weekId = null)
    {
        if ($weekId) {
            $week = Week::findOrFail($weekId);
        } else {

            $today = Carbon::today();

            $monday = $today->copy()->startOfWeek(Carbon::MONDAY);
            $sunday = $monday->copy()->endOfWeek(Carbon::SUNDAY);

            $week = Week::firstOrCreate(
                [
                    'start_date' => $monday->toDateString(),
                    'end_date'   => $sunday->toDateString(),
                ],
                [
                    'name' => 'Semana ' . $monday->format('W') . ' - ' . $monday->format('Y'),
                ]
            );
        }

        $this->loadWeek($week);
    }

    /* -------------------------------
     *   Cargar semana
     * ------------------------------*/
    public function loadWeek($week)
    {
        $this->weekId = $week->id;
        $this->startDate = $week->start_date;
        $this->endDate = $week->end_date;

        $this->buildDays();
        $this->areas = Areas::where('area', 'NOT ILIKE', '%ALMACEN%')
            ->orderBy('area')
            ->get();
    }

    /* -------------------------------
     *   Construir días lunes → domingo
     * ------------------------------*/
    public function buildDays()
    {
        $this->days = [];

        $date = Carbon::parse($this->startDate)->copy();

        for ($i = 0; $i < 7; $i++) {
            $this->days[] = [
                'label' => $date->translatedFormat('l d/m'),
                'date'  => $date->toDateString(),
            ];
            $date->addDay();
        }
    }

    /* -------------------------------
     *   Usuarios disponibles
     * ------------------------------*/
    public function getUsersProperty()
    {
        $query = User::query()
            ->where('name', 'ilike', '%' . $this->busqueda . '%')
            ->where('rol', 'ilike', '%' . $this->rolseleccionado . '%')
            ->where('rol', '!=', 'TARJETAS')
            ->where('rol', '!=', 'mbq')
            ->where('rol', '!=', 'Cliente');

        if ($this->estadoUser !== 'todos') {
            $query->where('estado', $this->estadoUser);
        }

        // Usuarios NO asignados esa semana
        $query->whereNotIn('id', function ($q) {
            $q->select('user_id')
                ->from('work_assignments')
                ->where('week_id', $this->weekId);
        });

        return $query
            ->orderByRaw("CASE WHEN path IS NULL OR path = '' THEN 1 ELSE 0 END")
            ->orderBy('name')
            ->get();
    }

    /* -------------------------------
     *   Matriz de asignaciones
     * ------------------------------*/
    public function getAssignmentsMatrixProperty()
    {
        $assignments = WorkAssignment::with('user')
            ->where('week_id', $this->weekId)
            ->orderBy('work_date')
            ->get();

        $matrix = [];

        foreach ($assignments as $asig) {

            $area  = $asig->area_id;
            $date  = Carbon::parse($asig->work_date)->toDateString();
            $shift = $asig->shift ?? 'am';

            $matrix[$area][$date][$shift][] = $asig;
        }

        return $matrix;
    }
    public function deleteAssignment($id)
    {
        WorkAssignment::where('id', $id)->delete();
        $this->emit('assignment-updated');
    }

    /* -------------------------------
     *   Asignar usuario (Drag & Drop)
     * ------------------------------*/
    public function assignUserToDay($payload)
    {

        $userId = $payload['userId'] ?? null;
        $areaId = $payload['areaId'] ?? null;
        $date   = $payload['date'] ?? null;
        $algo  = $payload['algo'];

        if (!$userId || !$areaId || !$date) return;

        $workDate = Carbon::parse($date);
        $start = Carbon::parse($this->startDate);
        $end   = Carbon::parse($this->endDate);

        if (!$workDate->between($start, $end)) return;
        $assignment = WorkAssignment::firstOrNew([
            'week_id'   => $this->weekId,
            'user_id'   => $userId,
            'work_date' => $workDate->toDateString(),
            'shift'     => $algo,
        ]);

        $assignment->area_id = $areaId;
        $assignment->save();
        $algomas = WorkAssignment::find($assignment->id);
        $algomas->shift = $algo;
        $algomas->save();
        $this->emit('assignment-updated');
    }

    /* -------------------------------
     *   Eliminar asignación
     * ------------------------------*/
    public function removeAssignment($assignmentId)
    {
        WorkAssignment::where('id', $assignmentId)
            ->where('week_id', $this->weekId)
            ->delete();

        $this->emit('assignment-updated');
    }

    /* -------------------------------
     *   Semana anterior
     * ------------------------------*/
    public function goToPreviousWeek()
    {
        $start = Carbon::parse($this->startDate)->subWeek();
        $end   = $start->copy()->endOfWeek(Carbon::SUNDAY);

        $week = Week::firstOrCreate(
            ['start_date' => $start->toDateString(), 'end_date' => $end->toDateString()],
            ['name' => 'Semana ' . $start->format('W') . ' - ' . $start->format('Y')]
        );

        $this->loadWeek($week);
    }

    /* -------------------------------
     *   Semana siguiente
     * ------------------------------*/
    public function goToNextWeek()
    {
        $start = Carbon::parse($this->startDate)->addWeek();
        $end   = $start->copy()->endOfWeek(Carbon::SUNDAY);

        $week = Week::firstOrCreate(
            ['start_date' => $start->toDateString(), 'end_date' => $end->toDateString()],
            ['name' => 'Semana ' . $start->format('W') . ' - ' . $start->format('Y')]
        );

        $this->loadWeek($week);
    }

    /* -------------------------------
     *   Cargar semana por DatePicker
     * ------------------------------*/
    public function loadWeekFromDate()
    {
        if (!$this->selectedDate) return;

        $dt = Carbon::parse($this->selectedDate);
        $monday = $dt->copy()->startOfWeek(Carbon::MONDAY);
        $sunday = $monday->copy()->endOfWeek(Carbon::SUNDAY);

        $week = Week::firstOrCreate(
            ['start_date' => $monday->toDateString(), 'end_date' => $sunday->toDateString()],
            ['name' => 'Semana ' . $monday->format('W') . ' - ' . $monday->format('Y')]
        );

        $this->loadWeek($week);
    }

    /* -------------------------------
     *   Render
     * ------------------------------*/
    public function render()
    {
        return view('livewire.planilla-semanal', [
            'users'             => $this->users,
            'assignmentsMatrix' => $this->assignmentsMatrix,
        ]);
    }
}
