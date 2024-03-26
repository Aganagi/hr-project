<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;
use App\Models\Department;
use Illuminate\Support\Facades\Auth;
class DepartmentForm extends Form
{
    public ?Department $record;

    #[Validate('required|string')]
    public string $department = '';

    #[Validate('required|string')]
    public string $description = '';

    public function fillForm(Department $record)
    {
        $this->record = $record;
        $this->department = $record->department;
        $this->description = $record->description;
    }
    public function store()
    {
        $this->validate();
        $user = Auth::user();
        $department = new Department([
            'department' => $this->department,
            'description' => $this->description,
        ]);
        $user->departments()->save($department);
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
