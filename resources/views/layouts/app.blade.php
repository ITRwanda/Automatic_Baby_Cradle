<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>IoT Baby Monitor</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-light">
    <div class="d-flex">
        {{-- Sidebar only for authenticated users --}}
        @auth
            <div class="sidebar d-flex flex-column p-3">
                <a href="{{ route('admin.dashboard') }}" class="brand mb-4 text-decoration-none">
                    IoT Baby Monitor
                </a>
                <ul class="nav nav-pills flex-column mb-auto">
                    {{-- Role-based links --}}
                    @if(auth()->user()->role && auth()->user()->role->name === 'admin')
                        <li>
                            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Dashboard</a>
                        </li>
                        <li>
                            <a href="{{ route('admin.families') }}" class="nav-link {{ request()->routeIs('admin.families') ? 'active' : '' }}">Families</a>
                        </li>
                        <li>
                            <a href="{{ route('admin.devices') }}" class="nav-link {{ request()->routeIs('admin.devices') ? 'active' : '' }}">Devices</a>
                        </li>
                        <li>
                            <a href="{{ route('admin.deviceReports') }}" class="nav-link {{ request()->routeIs('admin.deviceReports') ? 'active' : '' }}">Device Reports</a>
                        </li>
                        <li>
                            <a href="{{ route('admin.familyReports') }}" class="nav-link {{ request()->routeIs('admin.familyReports') ? 'active' : '' }}">Family Reports</a>
                        </li>

                        <li>
                            <a href="{{ route('admin.megaReports') }}" class="nav-link {{ request()->routeIs('admin.megaReports') ? 'active' : '' }}">Mega / Incident Report</a>
                        </li>

                        <li class="mt-3 px-1">
                            <div class="text-white-50 small mb-2">Quick actions</div>
                            <div class="d-grid gap-2">
                                <a href="{{ route('admin.devices') }}" class="btn btn-light btn-sm text-dark shadow-sm">Register device</a>
                                <a href="{{ route('admin.families') }}" class="btn btn-light btn-sm text-dark shadow-sm">Create family</a>
                                <a href="{{ route('admin.reports') }}" class="btn btn-light btn-sm text-dark shadow-sm">Assign devices</a>
                                <a href="{{ route('admin.megaReports') }}" class="btn btn-light btn-sm text-dark shadow-sm">Mega report</a>

                            </div>
                        </li>

                    @elseif(auth()->user()->role && auth()->user()->role->name === 'family_parent')
                        <li><a href="{{ route('family.dashboard') }}" class="nav-link {{ request()->routeIs('family.dashboard') ? 'active' : '' }}">Dashboard</a></li>
                        <li><a href="{{ route('family.addMember') }}" class="nav-link">Members</a></li>
                        <li><a href="{{ route('family.assignRole') }}" class="nav-link">Roles</a></li>
                        <li><a href="{{ route('family.reports') }}" class="nav-link {{ request()->routeIs('family.reports') ? 'active' : '' }}">Reports</a></li>
                    @elseif(auth()->user()->role && auth()->user()->role->name === 'family_member')
                        <li><a href="{{ route('member.dashboard') }}" class="nav-link {{ request()->routeIs('member.dashboard') ? 'active' : '' }}">Dashboard</a></li>
                        <li><a href="{{ route('member.reports') }}" class="nav-link {{ request()->routeIs('member.reports') ? 'active' : '' }}">Reports</a></li>
                        <li><a href="{{ route('member.notifications') }}" class="nav-link {{ request()->routeIs('member.notifications') ? 'active' : '' }}">Notifications</a></li>
                    @endif

                    <li><a href="{{ route('profile.settings') }}" class="nav-link {{ request()->routeIs('profile.settings') ? 'active' : '' }}">Profile</a></li>
                    <li>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="nav-link w-100 text-start bg-transparent border-0 text-danger">Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        @endauth

        {{-- Main content always visible --}}
        <div class="flex-grow-1 p-4">
            {{-- Global flash messages (so they show even after modal actions) --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>
    {{-- <footer class="bg-dark text-white text-center py-3 mt-auto">
        <small>&copy; {{ date('Y') }} IoT Baby Monitor — All rights reserved.</small>
    </footer>  --}}
</body>
</html>
