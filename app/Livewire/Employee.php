<?php

namespace App\Livewire;

use App\Livewire\Forms\EmployeeForm;
use App\Models\Position;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use App\Models\Employee as Employees;
use Livewire\WithPagination;
use Mary\Traits\Toast;
use Illuminate\Pagination\LengthAwarePaginator;

class Employee extends Component
{
    use Toast;
    use WithPagination;
    #[Url( as: "q")]
    public string $search = '';
    public bool $addModal = false;
    public bool $editModal = false;
    public bool $deleteModal = false;
    public EmployeeForm $form;
    public array $sortBy = ['column' => 'position_id', 'direction' => 'asc'];
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
        $this->success('Employee created succesfuly');
    }
    public function edit(Employees $employee)
    {
        $this->form->fillForm(record: $employee);
        $this->editModal = true;
    }
    public function update()
    {
        $this->form->update();
        $this->editModal = false;
        $this->success('Employee updated succesfuly');
    }

    public function delete(Employees $id): void
    {
        $id->delete();
        $this->deleteModal = false;
        $this->success("Employee deleted succesfuly", position: 'toast-top');
    }
    public function headers(): array
    {
        return [
            ['key' => 'index', 'label' => '#'],
            ['key' => 'position_id', 'label' => 'Department and Position', 'class' => 'w-64'],
            ['key' => 'fname', 'label' => 'Name', 'class' => 'w-64'],
            ['key' => 'email', 'label' => 'E-mail', 'class' => 'w-64'],
            ['key' => 'phone', 'label' => 'Phone', 'class' => 'w-64'],
            ['key' => 'hireDate', 'label' => 'Hire Date', 'class' => 'w-64']
        ];
    }
    public function employees(): LengthAwarePaginator
    {
        $query = Employees::query()
            ->with('position')
            ->where('user_id', auth()->user()->id)
            ->orderBy($this->sortBy['column'], $this->sortBy['direction'])
            ->when($this->search, function ($query) {
                return $query->where(function ($query) {
                    $query->whereAny(['position_id', 'fname', 'lname', 'email', 'phone', 'hireDate'], 'like', '%' . $this->search . '%');
                });
            });

        return $query->paginate(5, ['*'], 'page');
    }
    #[Title('Employee')]
    public function render()
    {
        $positions = Position::with('department')->get();

        return view('livewire.employee', [
            'employees' => $this->employees(),
            'positions' => $positions,
            'headers' => $this->headers()
        ]);
    }
}
