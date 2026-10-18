
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'COFIHO V2')</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #f3f5f9;
            color: #1e293b;
        }

        .sidebar {
            width: 250px;
            height: 100vh;
            background: #172337;
            color: white;
            position: fixed;
            top: 0;
            left: 0;
            overflow-y: auto;
        }

        .brand {
            padding: 28px 22px;
            font-size: 24px;
            font-weight: 700;
            border-bottom: 1px solid #334155;
        }

        .brand span {
            color: #38bdf8;
        }

        .menu {
            padding: 20px 12px;
        }

        .menu a {
            display: block;
            padding: 13px 16px;
            margin-bottom: 5px;
            color: #cbd5e1;
            text-decoration: none;
            border-radius: 8px;
            font-size: 14px;
            transition: 0.2s;
        }

        .menu a:hover,
        .menu a.active {
            background: #2563eb;
            color: white;
        }

        .main {
            margin-left: 250px;
            min-height: 100vh;
        }

        .topbar {
            background: white;
            padding: 22px 30px;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .topbar h1 {
            font-size: 21px;
            font-weight: 600;
        }

        .topbar .system {
            color: #64748b;
            font-size: 13px;
        }

        .content {
            padding: 30px;
        }

        .card {
            background: white;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 3px 12px rgba(0,0,0,0.05);
            margin-bottom: 20px;
        }

        .card h2 {
            font-size: 19px;
            margin-bottom: 18px;
        }

        .table-responsive {
            width: 100%;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }

        th {
            background: #f1f5f9;
            color: #334155;
            text-align: left;
            padding: 13px;
        }

        td {
            padding: 13px;
            border-bottom: 1px solid #e2e8f0;
        }

        tr:hover td {
            background: #f8fafc;
        }

        .btn {
            display: inline-block;
            background: #2563eb;
            color: white;
            padding: 10px 16px;
            border: none;
            border-radius: 7px;
            text-decoration: none;
            cursor: pointer;
        }

        .btn:hover {
            background: #1d4ed8;
        }

        @media (max-width: 768px) {
            .sidebar {
                position: relative;
                width: 100%;
                height: auto;
            }

            .menu {
                display: flex;
                flex-wrap: wrap;
                gap: 5px;
            }

            .main {
                margin-left: 0;
            }

            .topbar, .content {
                padding: 18px;
            }
        }
    </style>

    @stack('styles')
</head>

<body>

    <aside class="sidebar">
        <div class="brand">
            COFIHO <span>V2</span>
        </div>

        <nav class="menu">
            <a href="{{ route('vehiculos.index') }}"
               class="{{ request()->routeIs('vehiculos.*') ? 'active' : '' }}">
                Vehículos
            </a>

            <a href="{{ route('inventario.index') }}"
               class="{{ request()->routeIs('inventario.*') ? 'active' : '' }}">
                Inventario
            </a>

            <a href="{{ route('clientes.index') }}"
               class="{{ request()->routeIs('clientes.*') ? 'active' : '' }}">
                Clientes
            </a>

            <a href="{{ route('usuarios.index') }}"
               class="{{ request()->routeIs('usuarios.*') ? 'active' : '' }}">
                Usuarios
            </a>

            <a href="{{ route('reservas.index') }}"
               class="{{ request()->routeIs('reservas.*') ? 'active' : '' }}">
                Reservas
            </a>

            <a href="{{ route('ventas.index') }}"
               class="{{ request()->routeIs('ventas.*') ? 'active' : '' }}">
                Ventas
            </a>

            <a href="{{ route('finanzas.index') }}"
               class="{{ request()->routeIs('finanzas.*') ? 'active' : '' }}">
                Finanzas
            </a>

            <a href="{{ route('inspecciones.index') }}"
               class="{{ request()->routeIs('inspecciones.*') ? 'active' : '' }}">
                Inspecciones
            </a>

            <a href="{{ route('reportes.index') }}"
               class="{{ request()->routeIs('reportes.*') ? 'active' : '' }}">
                Reportes
            </a>
        </nav>
    </aside>

    <div class="main">

        <header class="topbar">
            <h1>@yield('page-title', 'Panel de administración')</h1>
            <span class="system">Sistema de Gestión COFIHO</span>
        </header>

        <main class="content">
            @yield('content')
        </main>

    </div>

    @stack('scripts')
</body>
</html>
