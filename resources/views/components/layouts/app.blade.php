<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="dracula">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'StaffHubPro' }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen font-sans antialiased">
    <x-mary-nav sticky full-width>

        <x-slot:brand>
            {{-- Drawer toggle for "main-drawer" --}}
            <label for="main-drawer" class="lg:hidden mr-3">
                <x-mary-icon name="o-bars-3" class="cursor-pointer" />
            </label>
            {{-- Brand --}}
            <div class="p-5 pt-3 flex items-center space-x-2">
                <x-heroicon-s-square-3-stack-3d class="fill-current flex-shrink-0 w-8 h-8 text-purple-400" />
                <span
                    class="font-bold text-3xl mr-3 bg-gradient-to-r from-purple-500 to-pink-300 bg-clip-text text-transparent whitespace-nowrap hover:bg-gradient-to-l from-pink-500 to-purple-300">
                    StaffHub Pro
                </span>
            </div>
        </x-slot:brand>

        {{-- Right side actions --}}
        <x-slot:actions>
            <x-mary-button label="Messages" icon="o-envelope" link="###" class="btn-ghost btn-sm" />
            <x-mary-button label="Notifications" icon="o-bell" link="###" class="btn-ghost btn-sm" />
        </x-slot:actions>
    </x-mary-nav>
    <x-mary-main with-nav full-width>

        {{-- This is a sidebar that works also as a drawer on small screens --}}
        {{-- Notice the `main-drawer` reference here --}}
        <x-slot:sidebar drawer="main-drawer">

            {{-- Activates the menu item when a route matches the `link` property --}}
            <x-mary-menu activate-by-route
                active-bg-color="bg-gradient-to-r from-purple-500 to-pink-300 hover:bg-gradient-to-l from-pink-500 to-purple-300">
                @if ($user = auth()->user())
                    <x-mary-list-item :item="$user" sub-value="username" no-separator no-hover
                        class="!-mx-2 mt-2 mb-5 border-y border-y-fuchsia-200">
                        <x-slot:actions>
                            <x-mary-dropdown>
                                <x-slot:trigger>
                                    <x-mary-button icon="o-cog-8-tooth" class="btn-circle btn-ghost btn-sm" />
                                </x-slot:trigger>
                                <x-mary-menu-item title="Log Out" icon="o-power" wire:click="logout" />
                                <x-mary-menu-item title="Profile" icon="o-user-plus" link="profile" />
                            </x-mary-dropdown>
                        </x-slot:actions>
                    </x-mary-list-item>
                @endif
                <x-mary-menu-item title="Department" icon="o-circle-stack" link="department" />
                <x-mary-menu-item title="Position" icon="o-chart-bar" link="position" />
                <x-mary-menu-item title="Employee" icon="o-users" link="employee" />
                <x-mary-menu-item title="Work Schedule" icon="o-clock" link="workSchedule" />
                <x-mary-menu-item title="Project" icon="o-briefcase" link="project" />
                <x-mary-menu-item title="Leave and Absence" icon="o-calendar-days" link="leaveAndAbsence" />
            </x-mary-menu>
        </x-slot:sidebar>

        {{-- The `$slot` goes here --}}
        <x-slot:content>
            {{ $slot }}
        </x-slot:content>
        {{-- Footer area --}}
        {{-- <x-slot:footer>
            <hr />
            <div class="p-6">
                Footer
            </div>
        </x-slot:footer> --}}
    </x-mary-main>
    {{--  TOAST area --}}
    <x-mary-toast />
</body>

</html>
