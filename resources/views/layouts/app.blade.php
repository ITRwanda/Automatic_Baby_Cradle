<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>IoT Baby Monitor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', 'Segoe UI', sans-serif;
        }
        .sidebar {
            width: 240px;
            min-height: 100vh;
            background: linear-gradient(180deg, #006633 0%, #004d26 100%); /* deep green gradient */
            color: #fff;
        }
        .sidebar .nav-link {
            color: #dfe6e9;
            font-weight: 500;
            padding: 10px 15px;
            border-radius: 6px;
            margin-bottom: 4px;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background-color: #45B972; /* brand green highlight */
            color: #fff;
        }
        .sidebar .brand {
            font-size: 1.25rem;
            font-weight: 700;
            color: #fff;
        }
    </style>
</head>
<body class="bg-light">

@auth
<div class="d-flex">
    <!-- Sidebar -->
    <div class="sidebar d-flex flex-column p-3">
        <a href="{{ route('admin.dashboard') }}" class="brand mb-4 text-decoration-none">
            IoT Baby Monitor
        </a>

        <ul class="nav nav-pills flex-column mb-auto">
            @if(auth()->user()->role && auth()->user()->role->name === 'admin')
                <li><a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Dashboard</a></li>
                <li><a href="{{ route('admin.families') }}" class="nav-link {{ request()->routeIs('admin.families') ? 'active' : '' }}">Families</a></li>
                <li><a href="{{ route('admin.devices') }}" class="nav-link {{ request()->routeIs('admin.devices') ? 'active' : '' }}">Devices</a></li>
                <li><a href="{{ route('admin.reports') }}" class="nav-link {{ request()->routeIs('admin.reports') ? 'active' : '' }}">Reports</a></li>
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

    <!-- Main Content -->
    <div class="flex-grow-1 p-4">
        @yield('content')
    </div>
</div>
@endauth

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
