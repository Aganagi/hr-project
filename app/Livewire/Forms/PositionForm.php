<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;
use App\Models\Position;
use Illuminate\Support\Facades\Auth;

class PositionForm extends Form
{
    #[Validate('required', as: 'department')]
    public $department_id = '';

    #[Validate('required|string')]
    public string $position = '';

    #[Validate('required|string')]
    public string $description = '';

    #[Validate('required|numeric')]
    public $salary = '';
    public ?Position $record;
    public function fillForm(Position $record)
    {
        $this->record = $record;
        $this->department_id = $record->department_id;
        $this->position = $record->position;
        $this->description = $record->description;
        $this->salary = $record->salary;
    }
    public function store()
    {
        $this->validate();
        $user = Auth::user();
        $position = new Position([
            'department_id' => $this->department_id,
            'position' => $this->position,
            'description' => $this->description,
            'salary' => $this->salary
        ]);
        $user->positions()->save($position);
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
