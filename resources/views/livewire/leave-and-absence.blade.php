<div>
    <!-- HEADER -->
    <x-mary-header title="Leave & Absence" separator progress-indicator class="text-fuchsia-400">
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
        @if ($absences->count() > 0)
            <x-mary-table :headers="$headers" :rows="$absences" :sort-by="$sortBy" with-pagination>
                @scope('cell_index', $absences)
                    {{ $this->loop->index + 1 }}
                @endscope
                @scope('cell_employee_id', $absences)
                    {{ optional($absences->employee)->fname }} {{ optional($absences->employee)->lname }}
                @endscope
                @scope('cell_start_date', $absences)
                    {{ \Carbon\Carbon::parse($absences->start_date)->format('m-d-Y') }}
                @endscope
                @scope('cell_end_date', $absences)
                    {{ \Carbon\Carbon::parse($absences->end_date)->format('m-d-Y') }}
                @endscope
                @scope('actions', $absences)
                    <div class="flex space-x-2">
                        <x-mary-button icon="o-pencil-square" wire:click="edit({{ $absences['id'] }})" spinner
                            class="btn-ghost btn-sm text-purple-500" />
                        <x-mary-button icon="o-trash" @click="$wire.deleteModal = true"
                            class="btn-ghost btn-sm text-red-500" />
                        <!--Delete Modal-->
                        <x-mary-modal wire:model="deleteModal" title="Are you sure?" class="backdrop-blur text-justify">
                            <span
                                class="text-xl bg-gradient-to-r from-purple-500 to-pink-300 bg-clip-text text-transparent">You
                                want
                                to delete this leave and absences?</span>
                            <x-slot:actions>
                                <x-mary-button label="Cancel" @click="$wire.deleteModal = false" />
                                <x-mary-button label="Confirm" class="btn-primary"
                                    wire:click="delete({{ $absences['id'] }})" spinner />
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
        <x-mary-modal wire:model="addModal" title="Create Leave and Absences" separator persistent
            class="backdrop-blur">
            <x-mary-form wire:submit="save">
                <x-mary-select label="Employee" :options="$employee" option-value="id" option-label="fullName"
                    placeholder="Select employee" placeholder-value="0" wire:model="form.employee_id" />
                <x-mary-datepicker label="Start date" wire:model="form.start_date" icon="o-calendar" />
                <x-mary-datepicker label="End date" wire:model="form.end_date" icon="o-calendar" />
                <x-mary-textarea label="Reason" wire:model="form.reason" rows="3" />
                <x-slot:actions>
                    <x-mary-button label="Cancel" @click="$wire.closeModal" />
                    <x-mary-button label="Save" class="btn-primary" type="submit" spinner="save" />
                </x-slot:actions>
            </x-mary-form>
            </x-modal>

            <!--Edit Modal-->
            <x-mary-modal wire:model="editModal" title="Edit Leave and Absences" separator persistent
                class="backdrop-blur">
                <x-mary-form wire:submit="update">
                    <x-mary-select label="Employee" :options="$employee" option-value="id" option-label="fullName"
                        placeholder="Select employee" placeholder-value="0" wire:model="form.employee_id" />
                    <x-mary-datepicker label="Start date" wire:model="form.start_date" icon="o-calendar" />
                    <x-mary-datepicker label="End date" wire:model="form.end_date" icon="o-calendar" />
                    <x-mary-textarea label="Reason" wire:model="form.reason" rows="3" />
                    <x-slot:actions>
                        <x-mary-button label="Cancel" @click="$wire.closeEditModal" />
                        <x-mary-button label="Update" class="btn-primary" type="submit" spinner="save" />
                    </x-slot:actions>
                </x-mary-form>
                </x-modal>
</div>
