<?php

namespace App\Livewire;

use App\Livewire\Forms\DepartmentForm;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;
use App\Models\Department as Departments;

class Department extends Component
{
    use Toast;
    use WithPagination;
    #[Url( as: "q")]
    public string $search = "";
    public bool $addModal = false;
    public bool $editModal = false;
    public bool $deleteModal = false;
    public DepartmentForm $form;
    public array $sortBy = ['column' => 'department', 'direction' => 'asc'];
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
        $this->success('Department created succesfuly');
    }
    public function edit(Departments $departments)
    {
        $this->form->fillForm(record: $departments);
        $this->editModal = true;
    }
    public function delete(Departments $departments): void
    {
        if (!$departments->exists()) {
            return;
        }
        $this->deleteModal = false;
        if ($departments->delete()) {
            $this->success("Department deleted successfully", position: 'toast-top');
        } else {
            $this->warning("Failed to delete department. There are positions associated with this department", position: 'toast-top', timeout: '5000');
        }
    }
    public function headers(): array
    {
        return [
            ['key' => 'index', 'label' => '#', 'class' => 'w-20'],
            ['key' => 'department', 'label' => 'Department', 'class' => 'w-64'],
            ['key' => 'description', 'label' => 'Description'],
        ];
    }
    public function update()
    {
        $this->form->update();
        $this->editModal = false;
        $this->success('Department updated succesfuly');
    }
    public function departments(): LengthAwarePaginator
    {
        $query = Departments::query()
            ->where(['user_id' => auth()->user()->id])
            ->orderBy($this->sortBy['column'], $this->sortBy['direction'])
            ->when($this->search, function ($query) {
                return $query->where(function ($query) {
                    $query->whereAny(['department', 'description'], 'like', '%' . $this->search . '%');
                });
            });

        return $query->paginate(5, ['*'], 'page');
    }
    #[Title('Department')]
    public function render()
    {
        return view('livewire.department', [
            'departments' => $this->departments(),
            'headers' => $this->headers(),
        ]);
    }
}
