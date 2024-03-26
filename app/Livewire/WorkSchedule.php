<?php

namespace App\Livewire;

use App\Livewire\Forms\WorkScheduleForm;
use App\Models\Employee;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\WorkSchedule as WorkSchedules;

class WorkSchedule extends Component
{
    use Toast;
    use WithPagination;
    #[Url( as: "q")]
    public string $search = "";
    public bool $addModal = false;
    public bool $editModal = false;
    public bool $deleteModal = false;
    public WorkScheduleForm $form;
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
        $this->success('Work Shedule created succesfuly');
    }
    public function edit(WorkSchedules $workSchedules)
    {
        $this->form->fillForm(record: $workSchedules);
        $this->editModal = true;
    }
    public function delete(WorkSchedules $id): void
    {
        $id->delete();
        $this->deleteModal = false;
        $this->success("Work Schedule deleted succesfuly", position: 'toast-top');
    }
    public function headers(): array
    {
        return [
            ['key' => 'index', 'label' => '#'],
            ['key' => 'employee_id', 'label' => 'Employee', 'class' => 'w-64'],
            ['key' => 'day_of_week', 'label' => 'Work Days', 'class' => 'w-64'],
            ['key' => 'start_time', 'label' => 'Start time', 'class' => 'w-64'],
            ['key' => 'end_time', 'label' => 'End time', 'class' => 'w-64'],
        ];
    }
    public function update()
    {
        $this->form->update();
        $this->editModal = false;
        $this->success('Work Shedule updated succesfuly');
    }
    public function workSchedules(): LengthAwarePaginator
    {
        $query = workSchedules::query()
            ->with('employee')
            ->where(['user_id' => auth()->user()->id])
            ->orderBy($this->sortBy['column'], $this->sortBy['direction'])
            ->when($this->search, function ($query) {
                return $query->where(function ($query) {
                    $query->where('employee_id', 'like', '%' . $this->search . '%');
                });
            });

        return $query->paginate(5, ['*'], 'page');
    }
    public function render()
    {
        $employees = Employee::get();

        return view('livewire.work-schedule', [
            'employees' => $employees,
            'workSchedules' => $this->workSchedules(),
            'headers' => $this->headers(),
        ]);
    }
}
