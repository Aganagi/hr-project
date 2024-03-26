<?php

namespace App\Livewire\Forms;

use App\Models\Employee;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Livewire\Form;

class EmployeeForm extends Form
{
    #[Validate('required', as: 'position')]
    public $position_id = '';

    #[Validate('required|string', as: 'first name')]
    public string $fname = '';

    #[Validate('required|string', as: 'last name')]
    public string $lname = '';

    #[Validate('required|email', onUpdate: false)]
    public string $email = '';

    #[Validate('required|numeric|regex:/^(\+\d{1,3}[- ]?)?\d{10}$/', as: 'phone number', onUpdate: false)]
    public string $phone = '';

    #[Validate('required|date', as: 'hire date')]
    public $hireDate = '';
    public ?Employee $record;
    public function fillForm(Employee $record)
    {
        $this->record = $record;
        $this->position_id = $record->position_id;
        $this->fname = $record->fname;
        $this->lname = $record->lname;
        $this->email = $record->email;
        $this->phone = $record->phone;
        $this->hireDate = $record->hireDate ? date('Y-m-d', strtotime($record->hireDate)) : null;
    }
    public function store()
    {
        $this->validate();
        $user = Auth::user();
        $employee = new Employee(
            $this->all()
        );
        $user->employees()->save($employee);
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
