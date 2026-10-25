<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'COFIHO V2')</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f3f5f9; color: #1e293b; }
        .sidebar { width: 250px; height: 100vh; background: #172337; color: white; position: fixed; top: 0; left: 0; overflow-y: auto; display: flex; flex-direction: column; }
        .brand { padding: 28px 22px; font-size: 24px; font-weight: 700; border-bottom: 1px solid #334155; }
        .brand span { color: #38bdf8; }
        .menu { padding: 20px 12px; flex: 1; }
        .menu a { display: block; padding: 13px 16px; margin-bottom: 5px; color: #cbd5e1; text-decoration: none; border-radius: 8px; font-size: 14px; transition: 0.2s; }
        .menu a:hover, .menu a.active { background: #2563eb; color: white; }
        .sidebar-footer { padding: 16px 12px; border-top: 1px solid #334155; }
        .user-name { color: #fff; font-size: 14px; font-weight: 600; overflow-wrap: anywhere; }
        .user-role { color: #94a3b8; font-size: 12px; margin-top: 4px; }
        .logout-button { width: 100%; padding: 11px 14px; margin-top: 14px; border: 1px solid #475569; border-radius: 8px; background: transparent; color: #fff; cursor: pointer; text-align: left; font: inherit; font-size: 14px; }
        .logout-button:hover { background: #334155; }
        .main { margin-left: 250px; min-height: 100vh; }
        .topbar { background: white; padding: 22px 30px; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; gap: 12px; }
        .topbar h1 { font-size: 21px; font-weight: 600; }
        .topbar .system { color: #64748b; font-size: 13px; }
        .content { padding: 30px; }
        .card { background: white; border-radius: 12px; padding: 24px; box-shadow: 0 3px 12px rgba(0,0,0,0.05); margin-bottom: 20px; }
        .card h2 { font-size: 19px; margin-bottom: 18px; }
        .table-responsive { width: 100%; overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; font-size: 14px; }
        th { background: #f1f5f9; color: #334155; text-align: left; padding: 13px; }
        td { padding: 13px; border-bottom: 1px solid #e2e8f0; }
        tr:hover td { background: #f8fafc; }
        .btn { display: inline-block; background: #2563eb; color: white; padding: 10px 16px; border: none; border-radius: 7px; text-decoration: none; cursor: pointer; }
        .btn:hover { background: #1d4ed8; }
        
/* Formularios COFIHO */
.form-group {
    display: flex;
    flex-direction: column;
    gap: 7px;
    margin-bottom: 18px;
}

.form-group label {
    font-size: 14px;
    font-weight: 600;
    color: #334155;
}

.form-group input,
.form-group select,
.form-group textarea {
    display: block;
    width: 100%;
    max-width: 520px;
    padding: 11px 13px;
    border: 1px solid #cbd5e1;
    border-radius: 7px;
    background: #ffffff;
    color: #1e293b;
    font-family: inherit;
    font-size: 14px;
    transition: border-color 0.2s, box-shadow 0.2s;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    outline: none;
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
}

.form-group input::placeholder {
    color: #94a3b8;
}

.card h3 {
    font-size: 16px;
    color: #172337;
    margin: 24px 0 16px;
}

.card p {
    color: #64748b;
    line-height: 1.6;
    margin-bottom: 18px;
}

.card hr {
    border: 0;
    border-top: 1px solid #e2e8f0;
    margin: 24px 0;
}

.btn:disabled {
    opacity: 0.45;
    cursor: not-allowed;
}

        @media (max-width: 768px) {
            .sidebar { position: relative; width: 100%; height: auto; }
            .menu { display: flex; flex-wrap: wrap; gap: 5px; }
            .main { margin-left: 0; }
            .topbar, .content { padding: 18px; }
        }

/* ===== Componentes compartidos (mejoras visuales) ===== */
.badge { display:inline-block; padding:5px 10px; border-radius:20px; font-size:11px; font-weight:700; white-space:nowrap; }
.badge-ok { background:#dcfce7; color:#166534; }
.badge-warn { background:#fef3c7; color:#92400e; }
.badge-bad { background:#fee2e2; color:#991b1b; }
.badge-info { background:#dbeafe; color:#1d4ed8; }
.badge-neutral { background:#e2e8f0; color:#334155; }

.btn-sm { font-size:12px; padding:8px 11px; white-space:nowrap; }
.btn-secundario { background:#e2e8f0; color:#334155; }
.btn-secundario:hover { background:#cbd5e1; }
.btn-peligro { background:#dc2626; }
.btn-peligro:hover { background:#b91c1c; }
.acciones { display:flex; gap:6px; flex-wrap:wrap; align-items:center; }
.acciones form { margin:0; }

.filtros { display:flex; gap:12px; flex-wrap:wrap; align-items:flex-end; margin-bottom:16px; padding:14px 16px; background:#f8fafc; border:1px solid #e2e8f0; border-radius:10px; }
.filtros .filtro { display:flex; flex-direction:column; gap:5px; }
.filtros label { font-size:12px; font-weight:600; color:#475569; }
.filtros input, .filtros select { padding:9px 11px; border:1px solid #cbd5e1; border-radius:7px; font:inherit; font-size:13px; min-width:170px; background:#fff; }
.filtros input:focus, .filtros select:focus { outline:none; border-color:#2563eb; box-shadow:0 0 0 3px rgba(37,99,235,0.12); }
.filtros .conteo { margin-left:auto; font-size:12px; color:#64748b; }
.filtros .limpiar { padding:9px 13px; border:1px solid #cbd5e1; border-radius:7px; background:#fff; color:#334155; cursor:pointer; font:inherit; font-size:13px; }
.filtros .limpiar:hover { background:#f1f5f9; }

.stats { display:grid; grid-template-columns:repeat(auto-fit,minmax(180px,1fr)); gap:14px; margin-bottom:20px; }
.stat { padding:16px 18px; border-radius:10px; background:#f1f5f9; }
.stat .stat-valor { font-size:26px; font-weight:700; color:#172337; }
.stat .stat-etiqueta { font-size:12px; color:#64748b; margin-top:2px; }

.charts { display:grid; grid-template-columns:repeat(auto-fit,minmax(320px,1fr)); gap:20px; margin-bottom:20px; }
.chart-box { position:relative; height:300px; }

dialog.modal { border:none; border-radius:12px; padding:24px; max-width:460px; width:92%; box-shadow:0 20px 50px rgba(0,0,0,0.25); }
dialog.modal::backdrop { background:rgba(15,23,42,0.55); }
dialog.modal h3 { margin-top:0; }
dialog.modal .form-group input, dialog.modal .form-group select, dialog.modal .form-group textarea { max-width:100%; }
.modal-acciones { display:flex; gap:8px; justify-content:flex-end; margin-top:8px; }
    </style>
    @stack('styles')
</head>
<body>
    @php
        $usuarioActual = auth()->user();
        $rolActual = $usuarioActual?->rol?->nombre;
    @endphp

    <aside class="sidebar">
        <div class="brand">COFIHO <span>V2</span></div>

        <nav class="menu" aria-label="Menú principal">
            @if(in_array($rolActual, ['ADMINISTRADOR', 'VENDEDOR', 'INSPECTOR'], true))
                <a href="{{ route('vehiculos.index') }}" class="{{ request()->routeIs('vehiculos.*') ? 'active' : '' }}">Predial</a>
                <a href="{{ route('inventario.index') }}" class="{{ request()->routeIs('inventario.*') ? 'active' : '' }}">Inventario</a>
            @endif

            @if(in_array($rolActual, ['ADMINISTRADOR', 'VENDEDOR'], true))
                <a href="{{ route('clientes.index') }}" class="{{ request()->routeIs('clientes.*') ? 'active' : '' }}">Clientes</a>
            @endif

            @if($rolActual === 'ADMINISTRADOR')
                <a href="{{ route('usuarios.index') }}" class="{{ request()->routeIs('usuarios.*') ? 'active' : '' }}">Usuarios</a>
            @endif

            @if(in_array($rolActual, ['ADMINISTRADOR', 'VENDEDOR'], true))
                <a href="{{ route('reservas.index') }}" class="{{ request()->routeIs('reservas.*') ? 'active' : '' }}">Reservas</a>
                <a href="{{ route('ventas.index') }}" class="{{ request()->routeIs('ventas.*') ? 'active' : '' }}">Ventas</a>
            @endif

            @if($rolActual === 'ADMINISTRADOR')
                <a href="{{ route('finanzas.index') }}" class="{{ request()->routeIs('finanzas.*') ? 'active' : '' }}">Finanzas</a>
            @endif

            @if(in_array($rolActual, ['ADMINISTRADOR', 'INSPECTOR'], true))
                <a href="{{ route('inspecciones.index') }}" class="{{ request()->routeIs('inspecciones.*') ? 'active' : '' }}">Inspecciones</a>
            @endif

            @if($rolActual === 'ADMINISTRADOR')
                <a href="{{ route('reportes.index') }}" class="{{ request()->routeIs('reportes.*') ? 'active' : '' }}">Reportes</a>
            @endif
        </nav>

        @auth
            <div class="sidebar-footer">
                <div class="user-name">{{ $usuarioActual->nombre }}</div>
                <div class="user-role">{{ $rolActual ?? 'Sin rol asignado' }}</div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="logout-button" type="submit">Cerrar sesión</button>
                </form>
            </div>
        @endauth
    </aside>

    <div class="main">
        <header class="topbar">
            <h1>@yield('page-title', 'Panel de administración')</h1>
            <span class="system">Sistema de Gestión COFIHO</span>
        </header>
        
<main class="content">

    @if (session('success'))
        <div style="
            background: #dcfce7;
            color: #166534;
            padding: 14px 18px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid #86efac;
        ">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div style="
            background: #fee2e2;
            color: #991b1b;
            padding: 14px 18px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid #fca5a5;
        ">
            {{ session('error') }}
        </div>
    @endif

    @yield('content')

</main>

    </div>

    <script>
    /* Filtros automáticos para cualquier <table data-filtrable data-filtros="Estado,Rol"> */
    (function () {
        document.querySelectorAll('table[data-filtrable]').forEach(function (tabla) {
            var cuerpo = tabla.tBodies[0];
            if (!cuerpo) { return; }

            var filas = Array.prototype.filter.call(cuerpo.rows, function (fila) {
                return !(fila.cells.length === 1 && fila.cells[0].colSpan > 1);
            });
            if (filas.length === 0) { return; }

            var encabezados = Array.prototype.map.call(tabla.tHead.rows[0].cells, function (th) {
                return th.textContent.trim().toLowerCase();
            });

            var barra = document.createElement('div');
            barra.className = 'filtros';

            var buscar = document.createElement('div');
            buscar.className = 'filtro';
            buscar.innerHTML = '<label>Buscar</label><input type="search" placeholder="Escribe para filtrar...">';
            barra.appendChild(buscar);
            var inputBuscar = buscar.querySelector('input');

            var selects = [];
            (tabla.dataset.filtros || '').split(',').forEach(function (nombre) {
                nombre = nombre.trim();
                if (!nombre) { return; }
                var col = encabezados.indexOf(nombre.toLowerCase());
                if (col < 0) { return; }

                var valores = [];
                filas.forEach(function (fila) {
                    var v = fila.cells[col].textContent.trim();
                    if (v && valores.indexOf(v) < 0) { valores.push(v); }
                });
                valores.sort();

                var caja = document.createElement('div');
                caja.className = 'filtro';
                var etiqueta = document.createElement('label');
                etiqueta.textContent = nombre;
                var select = document.createElement('select');
                select.innerHTML = '<option value="">Todos</option>';
                valores.forEach(function (v) {
                    var op = document.createElement('option');
                    op.value = v;
                    op.textContent = v;
                    select.appendChild(op);
                });
                caja.appendChild(etiqueta);
                caja.appendChild(select);
                barra.appendChild(caja);
                selects.push({ col: col, el: select });
            });

            var limpiar = document.createElement('button');
            limpiar.type = 'button';
            limpiar.className = 'limpiar';
            limpiar.textContent = 'Limpiar';
            barra.appendChild(limpiar);

            var conteo = document.createElement('span');
            conteo.className = 'conteo';
            barra.appendChild(conteo);

            var contenedor = tabla.closest('.table-responsive') || tabla;
            contenedor.parentNode.insertBefore(barra, contenedor);

            function aplicar() {
                var texto = inputBuscar.value.trim().toLowerCase();
                var visibles = 0;
                filas.forEach(function (fila) {
                    var ok = !texto || fila.textContent.toLowerCase().indexOf(texto) >= 0;
                    selects.forEach(function (s) {
                        if (ok && s.el.value && fila.cells[s.col].textContent.trim() !== s.el.value) { ok = false; }
                    });
                    fila.style.display = ok ? '' : 'none';
                    if (ok) { visibles++; }
                });
                conteo.textContent = 'Mostrando ' + visibles + ' de ' + filas.length;
            }

            inputBuscar.addEventListener('input', aplicar);
            selects.forEach(function (s) { s.el.addEventListener('change', aplicar); });
            limpiar.addEventListener('click', function () {
                inputBuscar.value = '';
                selects.forEach(function (s) { s.el.value = ''; });
                aplicar();
            });
            aplicar();
        });
    })();
    </script>
    @stack('scripts')
</body>
</html>
