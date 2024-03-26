<?php

namespace App\Livewire\Forms;

use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Livewire\Form;

class ProjectForm extends Form
{
    #[Validate('required', as: 'employee')]
    public $employee_id = '';
    #[Validate('required|string|max:30', as: 'project name')]
    public string $project_name = '';
    #[Validate('required|string|max:35')]
    public string $description = '';
    #[Validate('required', as: 'start date')]
    public $start_date = '';
    #[Validate('required', as: 'due date')]
    public $due_date = '';
    public ?Project $record;
    public function fillForm(Project $record)
    {
        $this->record = $record;
        $this->employee_id = $record->employee_id;
        $this->project_name = $record->project_name;
        $this->description = $record->description;
        $this->start_date = $record->start_date;
        $this->due_date = $record->due_date;
    }
    public function store()
    {
        $this->validate();
        $user = Auth::user();
        $project = new Project(
            $this->all()
        );
        $user->projects()->save($project);
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
