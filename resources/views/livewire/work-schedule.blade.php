<div>
    <!-- HEADER -->
    <x-mary-header title="Work Schedules" separator progress-indicator class="text-fuchsia-400">
        <x-slot:middle class="!justify-end text-white">
            <x-mary-input placeholder="Search..." wire:model.live.debounce.500ms="search" clearable
                icon="o-magnifying-glass" />
        </x-slot:middle>
        <x-slot:actions>
            <x-mary-button label="Add New" @click="$wire.addModal = true" responsive icon="o-plus" class="btn-primary" />
        </x-slot:actions>
    </x-mary-header>
    <!-- TABLE  -->
    <x-mary-card>
        @if ($workSchedules->count() > 0)
            <x-mary-table :headers="$headers" :rows="$workSchedules" :sort-by="$sortBy" with-pagination>
                @scope('cell_index', $positions)
                    {{ $this->loop->index + 1 }}
                @endscope
                @scope('cell_employee_id', $workSchedules)
                    {{ optional($workSchedules->employee)->fname }} {{ optional($workSchedules->employee)->lname }}
                @endscope
                @scope('cell_day_of_week', $workSchedules)
                    @php
                        $selectedDaysOfWeek = json_decode($workSchedules->day_of_week, true);
                        $dayCount = $selectedDaysOfWeek ? count($selectedDaysOfWeek) : 0;
                    @endphp
                    {{ $dayCount }}
                @endscope
                @scope('cell_start_time', $workSchedules)
                    {{ \Carbon\Carbon::parse($workSchedules->start_time)->format('H:i') }}
                @endscope
                @scope('cell_end_time', $workSchedules)
                    {{ \Carbon\Carbon::parse($workSchedules->end_time)->format('H:i') }}
                @endscope
                @scope('actions', $workSchedules)
                    <div class="flex space-x-2">
                        <x-mary-button icon="o-pencil-square" wire:click="edit({{ $workSchedules['id'] }})" spinner
                            class="btn-ghost btn-sm text-purple-500" />
                        <x-mary-button icon="o-trash" @click="$wire.deleteModal = true"
                            class="btn-ghost btn-sm text-red-500" />
                        <!--Delete Modal-->
                        <x-mary-modal wire:model="deleteModal" title="Are you sure?" class="backdrop-blur text-justify">
                            <span
                                class="text-xl bg-gradient-to-r from-purple-500 to-pink-300 bg-clip-text text-transparent">You
                                want to delete
                                this
                                work schedule</span>
                            <x-slot:actions>
                                <x-mary-button label="Cancel" @click="$wire.deleteModal = false" />
                                <x-mary-button label="Confirm" class="btn-primary"
                                    wire:click="delete({{ $workSchedules['id'] }})" />
                            </x-slot:actions>
                        </x-mary-modal>
                    </div>
                @endscope
                </x-table>
            @else
                <h1 class="text-center font-bold text-3xl">No record present in the database!</h1>
        @endif
    </x-card>
    <!--Create Modal-->
    <x-mary-modal wire:model="addModal" title="Create Work Schedule" separator persistent class="backdrop-blur">
        <x-mary-form wire:submit="save">
            <x-mary-select label="Employee" :options="$employees" option-value="id" option-label="fullName"
                placeholder="Select employee" placeholder-value="0" wire:model="form.employee_id" />
            <x-mary-select label="Work Days" wire:model="form.selectedDaysOfWeek" :options="$form->day_of_week" multiple />
            <x-mary-datetime label="Start time" wire:model="form.start_time" icon="o-calendar" type="time" />
            <x-mary-datetime label="End time" wire:model="form.end_time" icon="o-calendar" type="time" />
            <x-slot:actions>
                <x-mary-button label="Cancel" @click="$wire.closeModal" />
                <x-mary-button label="Save" class="btn-primary" type="submit" spinner="save" />
            </x-slot:actions>
        </x-mary-form>
    </x-modal>
    <!--Edit Modal-->
    <x-mary-modal wire:model="editModal" title="Edit Work Schedule" separator persistent class="backdrop-blur">
        <x-mary-form wire:submit="update">
            <x-mary-select label="Employee" :options="$employees" option-value="id" option-label="fullName"
                placeholder="Select employee" placeholder-value="0" wire:model="form.employee_id" />
            <x-mary-select label="Work Days" wire:model="form.selectedDaysOfWeek" :options="$form->day_of_week" multiple />
            <x-mary-datetime label="Start time" wire:model="form.start_time" icon="o-calendar" type="time" />
            <x-mary-datetime label="End time" wire:model="form.end_time" icon="o-calendar" type="time" />
            <x-slot:actions>
                <x-mary-button label="Cancel" @click="$wire.closeEditModal" />
                <x-mary-button label="Update" class="btn-primary" type="submit" spinner="save" />
            </x-slot:actions>
        </x-mary-form>
    </x-modal>
</div>
