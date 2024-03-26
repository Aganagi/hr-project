<?php

namespace App\Livewire;

use App\Livewire\Forms\ProjectForm;
use App\Models\Employee;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use App\Models\Project as Projects;
use Livewire\WithPagination;
use Mary\Traits\Toast;
use Illuminate\Pagination\LengthAwarePaginator;

class Project extends Component
{
    use Toast;
    use WithPagination;
    #[Url( as: "q")]
    public string $search = "";
    public bool $addModal = false;
    public bool $editModal = false;
    public bool $deleteModal = false;
    public ProjectForm $form;
    public array $sortBy = ['column' => 'employee_id', 'direction' => 'asc'];
    public function closeModal()
    {
        $this->resetValidation();
        $this->form->reset();
        $this->addModal = false;
    }
    public function closeEditModal()
    {
        $this->resetValidation();
        $this->form->reset();
        $this->editModal = false;
    }
    public function save()
    {
        $this->form->store();
        $this->addModal = false;
        $this->success('Project created succesfuly');
    }
    public function edit(Projects $projects)
    {
        $this->form->fillForm(record: $projects);
        $this->editModal = true;
    }
    public function update()
    {
        $this->form->update();
        $this->editModal = false;
        $this->success('Project updated succesfuly');
    }
    public function delete(Projects $id): void
    {
        $id->delete();
        $this->deleteModal = false;
        $this->success("Project deleted succesfuly", position: 'toast-top');
    }
    public function headers(): array
    {
        return [
            ['key' => 'index', 'label' => '#'],
            ['key' => 'employee_id', 'label' => 'Employee', 'class' => 'w-64'],
            ['key' => 'project_name', 'label' => 'Project Name', 'class' => 'w-64'],
            ['key' => 'description', 'label' => 'Description', 'class' => 'w-64'],
            ['key' => 'start_date', 'label' => 'Start date', 'class' => 'w-64'],
            ['key' => 'due_date', 'label' => 'Due date', 'class' => 'w-64'],
            ['key' => 'status', 'label' => 'Status', 'class' => 'w-64']
        ];
    }
    public function projects(): LengthAwarePaginator
    {
        $query = Projects::query()
            ->with('employee')
            ->where(['user_id' => auth()->user()->id])
            ->orderBy($this->sortBy['column'], $this->sortBy['direction'])
            ->when($this->search, function ($query) {
                return $query->where(function ($query) {
                    $query->whereAny(['employee_id', 'project_name', 'description'], 'like', '%' . $this->search . '%');
                });
            });

        return $query->paginate(5, ['*'], 'page');
    }
    #[Title('Project')]
    public function render()
    {
        $employees = Employee::get();

        return view('livewire.project', [
            'employees' => $employees,
            'projects' => $this->projects(),
            'headers' => $this->headers()
        ]);
    }
}
