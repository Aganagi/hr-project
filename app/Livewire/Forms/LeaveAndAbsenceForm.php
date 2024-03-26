<?php

namespace App\Livewire\Forms;

use App\Models\LeaveAndAbsence;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Livewire\Form;

class LeaveAndAbsenceForm extends Form
{
    #[Validate('required', as: 'employee')]
    public $employee_id = "";

    #[Validate("required", as: 'start date')]
    public string $start_date = "";

    #[Validate("required", as: "end date")]
    public string $end_date = "";

    #[Validate('required|string')]
    public string $reason = '';
    public ?LeaveAndAbsence $record;
    public function fillForm(LeaveAndAbsence $record)
    {
        $this->record = $record;
        $this->employee_id = $record->employee_id;
        $this->start_date = $record->start_date ? date('Y-m-d', strtotime($record->start_date)) : null;
        $this->end_date = $record->end_date ? date('Y-m-d', strtotime($record->end_date)) : null;
        $this->reason = $record->reason;
    }
    public function store()
    {
        $this->validate();
        $user = Auth::user();
        $absence = new LeaveAndAbsence([
            'employee_id' => $this->employee_id,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'reason' => $this->reason
        ]);
        $user->leaveAndAbsences()->save($absence);
        $this->reset();
    }

    public function update()
    {
        $this->validate();
        $this->record->update(
            $this->all()
        );
        $this->reset();
    }
}
