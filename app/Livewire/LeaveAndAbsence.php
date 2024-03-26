<?php

namespace App\Livewire;

use App\Livewire\Forms\LeaveAndAbsenceForm;
use App\Models\Employee;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Url;
use Livewire\Component;
use App\Models\LeaveAndAbsence as AbsenceModel;
use Livewire\WithPagination;
use Mary\Traits\Toast;

class LeaveAndAbsence extends Component
{
    use Toast;
    use WithPagination;
    #[Url( as: "q")]
    public string $search = '';
    public LeaveAndAbsenceForm $form;
    public bool $addModal = false;
    public bool $editModal = false;
    public bool $deleteModal = false;
    public $sortBy = ['column' => 'employee_id', 'direction' => 'asc'];
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
        $this->success('Absence aded succesfuly');
    }
    public function edit(AbsenceModel $absecnce)
    {
        $this->form->fillForm(record: $absecnce);
        $this->editModal = true;
    }
    public function update()
    {
        $this->form->update();
        $this->editModal = false;
        $this->success('Absence updated succesfuly');
    }
    public function delete(AbsenceModel $absenceModel): void
    {
        $absenceModel->delete();
        $this->deleteModal = false;
        $this->success('Absence deleted succesfuly', position: 'toast-top');
    }
    public function headers(): array
    {
        return [
            ['key' => 'index', 'label' => '#'],
            ['key' => 'employee_id', 'label' => 'Employee', 'class' => 'w-64'],
            ['key' => 'start_date', 'label' => 'Start date', 'class' => 'w-64'],
            ['key' => 'end_date', 'label' => 'End date', 'class' => 'w-64'],
            ['key' => 'reason', 'label' => 'Reason', 'class' => 'w-64'],
        ];
    }
    public function absences(): LengthAwarePaginator
    {
        $query = AbsenceModel::query()
            ->with('employee')
            ->where(['user_id' => auth()->user()->id])
            ->orderBy($this->sortBy['column'], $this->sortBy['direction'])
            ->when($this->search, function ($query) {
                return $query->where(function ($query) {
                    $query->whereAny(['employee_id', 'reason'], 'like', '%' . $this->search . '%');
                });
            });
        return $query->paginate(5, ['*'], 'page');
    }
    public function render()
    {
        $employee = Employee::get();

        return view('livewire.leave-and-absence', [
            'employee' => $employee,
            'headers' => $this->headers(),
            'absences' => $this->absences()
        ]);
    }
}
