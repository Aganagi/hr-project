<div>
    <!-- HEADER -->
    <x-mary-header title="Projects" separator progress-indicator class="text-fuchsia-400">
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
        @if ($projects->count() > 0)
            <x-mary-table :headers="$headers" :rows="$projects" :sort-by="$sortBy" with-pagination>
                @scope('cell_index', $projects)
                    {{ $this->loop->index + 1 }}
                @endscope
                @scope('cell_employee_id', $projects)
                    {{ optional($projects->employee)->fname }} {{ optional($projects->employee)->lname }}
                @endscope
                @scope('cell_start_date', $projects)
                    {{ \Carbon\Carbon::parse($projects->start_date)->format('m-d-Y') }}
                @endscope
                @scope('cell_due_date', $projects)
                    {{ \Carbon\Carbon::parse($projects->due_date)->format('m-d-Y') }}
                @endscope
                @scope('cell_status', $projects)
                    @php
                        $start = \Carbon\Carbon::parse($projects->start_date);
                        $end = \Carbon\Carbon::parse($projects->due_date);
                        $diff = $start->diff($end);

                        if ($diff->days > 0 || $diff->h > 0 || $diff->i > 0 || $diff->s > 0) {
                            $result = $diff->format('%d days %h hours %i minutes %s seconds');
                        } else {
                            $result = $diff->format('%h hours %i minutes %s seconds');
                        }
                    @endphp
                    <span class="countDown" data-end='{{ $end->format('m-d-Y / H:i:s') }}'></span>
                @endscope
                @scope('actions', $projects)
                    <div class="flex space-x-2">
                        <x-mary-button icon="o-pencil-square" wire:click="edit({{ $projects['id'] }})" spinner
                            class="btn-ghost btn-sm text-purple-500" />
                        <x-mary-button icon="o-trash" @click="$wire.deleteModal = true"
                            class="btn-ghost btn-sm text-red-500" />
                        <!--Delete Modal-->
                        <x-mary-modal wire:model="deleteModal" title="Are you sure?" class="backdrop-blur text-justify">
                            <span
                                class="text-xl bg-gradient-to-r from-purple-500 to-pink-300 bg-clip-text text-transparent">You
                                want to delete
                                this
                                project</span>
                            <x-slot:actions>
                                <x-mary-button label="Cancel" @click="$wire.deleteModal = false" />
                                <x-mary-button label="Confirm" class="btn-primary"
                                    wire:click="delete({{ $projects['id'] }})" />
                            </x-slot:actions>
                        </x-mary-modal>
                        <!--End Delete Modal-->
                    </div>
                @endscope
                </x-table>
            @else
                <h1 class="text-center font-bold text-3xl">No record present in the database!</h1>
        @endif
        </x-card>
        <!--Create Modal-->
        <x-mary-modal wire:model="addModal" title="Create Project" separator persistent class="backdrop-blur">
            <x-mary-form wire:submit="save">
                <x-mary-select label="Employee" option-value="id" option-label="fullName" placeholder="Select employee"
                    :options="$employees" wire:model="form.employee_id" />
                <x-mary-input label="Project Name" wire:model="form.project_name" />
                <x-mary-textarea label="Description" wire:model="form.description" rows="3" />
                <x-mary-datepicker label="Start Date" wire:model="form.start_date" icon="o-calendar" />
                <x-mary-datepicker label="Due Date" wire:model="form.due_date" icon="o-calendar" />
                <x-slot:actions>
                    <x-mary-button label="Cancel" @click="$wire.closeModal" />
                    <x-mary-button label="Save" class="btn-primary" type="submit" spinner="save" />
                </x-slot:actions>
            </x-mary-form>
            </x-modal>
            <!--Edit Modal-->
            <x-mary-modal wire:model="editModal" title="Edit Project" separator persistent class="backdrop-blur">
                <x-mary-form wire:submit="update">
                    <x-mary-select label="Employee" option-value="id" option-label="fullName"
                        placeholder="Select employee" :options="$employees" wire:model="form.employee_id" />
                    <x-mary-input label="Project Name" wire:model="form.project_name" />
                    <x-mary-textarea label="Description" wire:model="form.description" rows="3" />
                    <x-mary-datepicker label="Start Date" wire:model="form.start_date" icon="o-calendar" />
                    <x-mary-datepicker label="Due Date" wire:model="form.due_date" icon="o-calendar" />
                    <x-slot:actions>
                        <x-mary-button label="Cancel" @click="$wire.closeEditModal" />
                        <x-mary-button label="Update" class="btn-primary" type="submit" spinner="save" />
                    </x-slot:actions>
                </x-mary-form>
                </x-modal>
                <script>
                    function updateCountDown() {
                        let countDownElements = document.querySelectorAll('.countDown');
                        countDownElements.forEach(element => {
                            let endTime = new Date(element.getAttribute('data-end')).getTime();
                            let now = new Date().getTime();
                            let timeDifference = endTime - now;
                            if (timeDifference <= 0) {
                                element.textContent = "Completed";
                            } else {
                                element.textContent = "In Progress";
                            }
                        });
                    }
                    setInterval(updateCountDown, 1000);
                    updateCountDown();
                </script>
</div>
