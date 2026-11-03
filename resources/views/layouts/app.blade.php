<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', "Ali's Hospital") - Hospital Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --hosp-navy: #0f172a;
            --hosp-blue: #1e3a8a;
            --hosp-blue-light: #2563eb;
            --hosp-red: #dc2626;
            --hosp-red-dark: #b91c1c;
            --hosp-bg: #f8fafc;
        }
        body {
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
            background-color: var(--hosp-bg);
            color: #1e293b;
        }
        .sidebar {
            background: linear-gradient(180deg, var(--hosp-navy) 0%, var(--hosp-blue) 100%);
            min-height: 100vh;
            color: #fff;
            box-shadow: 4px 0 10px rgba(0,0,0,0.05);
        }
        .sidebar-brand {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .sidebar .nav-link {
            color: #cbd5e1;
            padding: 0.75rem 1.25rem;
            border-radius: 0.5rem;
            margin: 0.25rem 0.75rem;
            display: flex;
            align-items: center;
            font-weight: 500;
            transition: all 0.2s;
        }
        .sidebar .nav-link i {
            font-size: 1.15rem;
            margin-right: 0.75rem;
            width: 24px;
        }
        .sidebar .nav-link:hover {
            color: #fff;
            background-color: rgba(255,255,255,0.1);
        }
        .sidebar .nav-link.active {
            color: #fff;
            background-color: var(--hosp-blue-light);
            box-shadow: 0 4px 12px rgba(37,99,235,0.3);
        }
        .topbar {
            background-color: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            padding: 0.85rem 1.75rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.02);
        }
        .badge-emergency {
            background-color: var(--hosp-red);
            color: white;
            padding: 0.35rem 0.75rem;
            border-radius: 50rem;
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.5px;
        }
        .card-stat {
            border: none;
            border-radius: 0.75rem;
            box-shadow: 0 2px 6px rgba(0,0,0,0.04);
            border-left: 5px solid var(--hosp-blue-light);
            background: #fff;
        }
        .card-stat.red-accent {
            border-left-color: var(--hosp-red);
        }
        .card-table {
            border: none;
            border-radius: 0.75rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            overflow: hidden;
            background: #fff;
        }
        .card-header-navy {
            background: var(--hosp-blue);
            color: #fff;
            padding: 0.9rem 1.25rem;
            font-weight: 600;
        }
        .btn-hospital-primary {
            background-color: var(--hosp-blue);
            border-color: var(--hosp-blue);
            color: #fff;
            font-weight: 500;
        }
        .btn-hospital-primary:hover {
            background-color: var(--hosp-navy);
            color: #fff;
        }
        .btn-hospital-red {
            background-color: var(--hosp-red);
            border-color: var(--hosp-red);
            color: #fff;
            font-weight: 500;
        }
        .btn-hospital-red:hover {
            background-color: var(--hosp-red-dark);
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="container-fluid p-0">
        <div class="row g-0">
            <!-- Sidebar -->
            <div class="col-lg-2 col-md-3 sidebar d-flex flex-column">
                <div class="sidebar-brand">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-hospital fs-3 text-danger me-2"></i>
                        <div>
                            <h5 class="text-white mb-0 fw-bold">Ali's Hospital</h5>
                            <small class="text-secondary" style="font-size: 0.7rem;">Lucknow • Secondary Care</small>
                        </div>
                    </div>
                </div>

                <div class="p-2 flex-grow-1">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                                <i class="bi bi-speedometer2"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('patients.*') ? 'active' : '' }}" href="{{ route('patients.index') }}">
                                <i class="bi bi-people"></i> Patients
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('appointments.*') ? 'active' : '' }}" href="{{ route('appointments.index') }}">
                                <i class="bi bi-calendar-check"></i> Appointments
                            </a>
                        </li>
                        @if(Auth::user()->isAdmin() || Auth::user()->isStaff())
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('doctors.*') ? 'active' : '' }}" href="{{ route('doctors.index') }}">
                                <i class="bi bi-person-badge"></i> Doctors
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('departments.*') ? 'active' : '' }}" href="{{ route('departments.index') }}">
                                <i class="bi bi-building"></i> Departments
                            </a>
                        </li>
                        @endif
                    </ul>
                </div>

                <div class="p-3 border-top border-secondary">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="mb-0 text-white small fw-bold">{{ Auth::user()->name }}</p>
                            <span class="badge bg-danger text-uppercase" style="font-size: 0.65rem;">{{ Auth::user()->role }}</span>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-light" title="Sign Out">
                                <i class="bi bi-box-arrow-right"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Content Area -->
            <div class="col-lg-10 col-md-9 d-flex flex-column" style="min-height: 100vh;">
                <!-- Topbar -->
                <div class="topbar d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <span class="badge-emergency me-3"><i class="bi bi-lightning-charge-fill me-1"></i>24/7 CASUALTY DESK</span>
                        <span class="text-muted small d-none d-md-inline">
                            <i class="bi bi-geo-alt-fill text-danger me-1"></i>Campbell Road, Mozzam Nagar, Lucknow 226003
                        </span>
                    </div>

                    <div class="d-flex align-items-center">
                        <span class="badge bg-light text-dark border me-3 py-2 px-3">
                            <i class="bi bi-clock me-1 text-primary"></i> {{ now()->format('D, d M Y') }}
                        </span>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle me-1 text-primary"></i> {{ Auth::user()->name }}
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow">
                                <li class="dropdown-header">{{ Auth::user()->email }}</li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button class="dropdown-item text-danger" type="submit">
                                            <i class="bi bi-box-arrow-right me-1"></i> Sign Out
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Main Body -->
                <div class="p-4 flex-grow-1">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @yield('content')
                </div>

                <!-- Footer -->
                <footer class="bg-white border-top py-3 px-4 text-center text-muted small">
                    Ali's Hospital &copy; {{ date('Y') }} • Campbell Road, Lucknow 226003 • Local XAMPP Platform
                </footer>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>