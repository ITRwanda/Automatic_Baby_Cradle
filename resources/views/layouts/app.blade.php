<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>IoT Baby Monitor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-light">

    @auth
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="#">IoT Baby Monitor</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" 
                    data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" 
                    aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    @if(auth()->user()->role === 'admin')
                        <li class="nav-item"><a class="nav-link" href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('admin.families') }}">Families</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('admin.devices') }}">Devices</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('admin.reports') }}">Reports</a></li>
                    @elseif(auth()->user()->role === 'family_parent')
                        <li class="nav-item"><a class="nav-link" href="{{ route('family.dashboard') }}">Dashboard</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('family.members') }}">Members</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('family.roles') }}">Roles</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('family.reports') }}">Reports</a></li>
                    @elseif(auth()->user()->role === 'family_member')
                        <li class="nav-item"><a class="nav-link" href="{{ route('member.dashboard') }}">Dashboard</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('member.reports') }}">Reports</a></li>
                    @endif
                    <li class="nav-item"><a class="nav-link" href="{{ route('profile.settings') }}">Profile</a></li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('logout') }}" 
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                           Logout</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
                            @csrf
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    @endauth

    <!-- Main Content -->
    <main class="container py-4">
        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
