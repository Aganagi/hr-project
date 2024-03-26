<?php

namespace App\Livewire;

use App\Livewire\Forms\PositionForm;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;
use App\Models\Position as Positions;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Department;

class Position extends Component
{
    use Toast;
    use WithPagination;
    #[Url( as: "q")]
    public string $search = "";
    public bool $addModal = false;
    public bool $editModal = false;
    public bool $deleteModal = false;
    public PositionForm $form;
    public array $sortBy = ['column' => 'department_id', 'direction' => 'asc'];
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
        $this->success('Position created succesfuly');
    }
    public function edit(Positions $positions)
    {
        $this->form->fillForm(record: $positions);
        $this->editModal = true;
    }
    public function update()
    {
        $this->form->update();
        $this->editModal = false;
        $this->success('Position updated succesfuly');
    }
    public function delete(Positions $position): void
    {
        if (!$position->exists()) {
            return;
        }
        $this->deleteModal = false;
        if ($position->delete()) {
            $this->success("Position deleted successfully", position: 'toast-top');
        } else {
            $this->warning("Failed to delete position. There are employees associated with this department", position: 'toast-top', timeout: '5000');
        }
    }
    public function headers(): array
    {
        return [
            ['key' => 'index', 'label' => '#'],
            ['key' => 'department_id', 'label' => 'Department', 'class' => 'w-64'],
            ['key' => 'position', 'label' => 'Position', 'class' => 'w-64'],
            ['key' => 'description', 'label' => 'Description', 'class' => 'w-64'],
            ['key' => 'salary', 'label' => 'Salary', 'class' => 'w-64']
        ];
    }
    public function positions(): LengthAwarePaginator
    {
        $query = Positions::query()
            ->with('department')
            ->where(['user_id' => auth()->user()->id])
            ->orderBy($this->sortBy['column'], $this->sortBy['direction'])
            ->when($this->search, function ($query) {
                return $query->where(function ($query) {
                    $query->whereAny(['department_id', 'position', 'description', 'salary'], 'like', '%' . $this->search . '%');
                });
            });

        return $query->paginate(5, ['*'], 'page');
    }
    #[Title('Position')]
    public function render()
    {
        $departments = Department::get();

        return view('livewire.position', [
            'departments' => $departments,
            'positions' => $this->positions(),
            'headers' => $this->headers(),
        ]);
    }
}
