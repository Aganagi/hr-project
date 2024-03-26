<div>
    <!-- HEADER -->
    <x-mary-header title="Positions" separator progress-indicator class="text-fuchsia-400">
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
        @if ($positions->count() > 0)
            <x-mary-table :headers="$headers" :rows="$positions" :sort-by="$sortBy" with-pagination>
                @scope('cell_index', $positions)
                    {{ $this->loop->index + 1 }}
                @endscope
                @scope('cell_department_id', $positions)
                    <x-mary-badge :value="optional($positions->department)->department" />
                @endscope
                @scope('cell_salary', $positions)
                    <x-mary-badge :value="number_format($positions->salary)" />
                @endscope
                @scope('actions', $positions)
                    <div class="flex space-x-2">
                        <x-mary-button icon="o-pencil-square" wire:click="edit({{ $positions['id'] }})" spinner
                            class="btn-ghost btn-sm text-purple-500" />
                        <x-mary-button icon="o-trash" @click="$wire.deleteModal = true"
                            class="btn-ghost btn-sm text-red-500" />
                        <!--Delete Modal-->
                        <x-mary-modal wire:model="deleteModal" title="Are you sure?"
                            class="backdrop-blur text-justify text-orange-500">
                            <span
                                class="text-xl font-bold bg-gradient-to-r from-purple-500 to-pink-300 bg-clip-text text-transparent">You
                                want to delete
                                this
                                position</span>
                            <x-slot:actions>
                                <x-mary-button label="Cancel" @click="$wire.deleteModal = false" />
                                <x-mary-button label="Confirm" class="btn-primary"
                                    wire:click="delete({{ $positions['id'] }})" />
                            </x-slot:actions>
                        </x-mary-modal>
                    </div>
                @endscope
            </x-mary-table>
        @else
            <h1 class="text-center font-bold text-3xl">No record present in the database!</h1>
        @endif
    </x-mary-card>
    <!--Create Modal-->
    <x-mary-modal wire:model="addModal" title="Create Position" separator persistent class="backdrop-blur">
        <x-mary-form wire:submit="save">
            <x-mary-select label="Department" option-value="id" option-label="department"
                placeholder="Select department" :options="$departments" wire:model="form.department_id" single />
            <x-mary-input label="Position" wire:model="form.position" />
            <x-mary-textarea label="Description" wire:model="form.description" rows="3" />
            <x-mary-input label="Salary" wire:model="form.salary" icon="o-currency-dollar" />
            <x-slot:actions>
                <x-mary-button label="Cancel" @click="$wire.closeModal" />
                <x-mary-button label="Save" class="btn-primary" type="submit" spinner="save" />
            </x-slot:actions>
        </x-mary-form>
    </x-mary-modal>

    <!--Edit Modal-->
    <x-mary-modal wire:model="editModal" title="Edit Position" separator persistent class="backdrop-blur">
        <x-mary-form wire:submit="update">
            <x-mary-select label="Department" option-value="id" option-label="department"
                placeholder="Select department" :options="$departments" wire:model="form.department_id" single />
            <x-mary-input label="Position" wire:model="form.position" />
            <x-mary-textarea label="Description" wire:model="form.description" rows="3" />
            <x-mary-input label="Salary" wire:model="form.salary" icon="o-currency-dollar" />
            <x-slot:actions>
                <x-mary-button label="Cancel" @click="$wire.closeEditModal" />
                <x-mary-button label="Update" class="btn-primary" type="submit" spinner="save" />
            </x-slot:actions>
        </x-mary-form>
        </x-modal>
</div>
