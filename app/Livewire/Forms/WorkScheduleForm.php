<?php

namespace App\Livewire\Forms;

use App\Models\WorkSchedule;
use Livewire\Attributes\Validate;
use Livewire\Form;
use Illuminate\Support\Facades\Auth;

class WorkScheduleForm extends Form
{
    #[Validate('required', as: 'employee')]
    public $employee_id = '';
    #[Validate('required', as: 'work days')]
    public $day_of_week = [
        ['id' => 0, 'name' => 'Monday'],
        ['id' => 1, 'name' => 'Tuesday'],
        ['id' => 2, 'name' => 'Wednesday'],
        ['id' => 3, 'name' => 'Thursday'],
        ['id' => 4, 'name' => 'Friday'],
        ['id' => 5, 'name' => 'Saturday'],
        ['id' => 6, 'name' => 'Sunday'],
    ];
    public $selectedDaysOfWeek = [];
    #[Validate('required', as: 'start time')]
    public $start_time = '';
    #[Validate('required', as: 'end time')]
    public $end_time = '';
    public ?WorkSchedule $record;
    public function fillForm(WorkSchedule $record)
    {
        $this->record = $record;
        $this->employee_id = $record->employee_id;
        $this->selectedDaysOfWeek = json_decode($record->day_of_week, true);
        $this->start_time = $record->start_time;
        $this->end_time = $record->end_time;
    }
    public function store()
    {
        $this->validate();
        $user = Auth::user();
        $workSchedule = new WorkSchedule([
            'employee_id' => $this->employee_id,
            'day_of_week' => json_encode($this->selectedDaysOfWeek),
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
        ]);
        $user->workschedules()->save($workSchedule);
        $this->reset();
    }

    public function update()
    {
        $this->validate();

        $this->record->update([
            'employee_id' => $this->employee_id,
            'day_of_week' => json_encode($this->selectedDaysOfWeek),
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
        ]);

        $this->reset();
    }
}
