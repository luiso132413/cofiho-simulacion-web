<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Detalle del vehículo | COFIHO V2</title>
    <style>
        * {
            box-sizing: border-box
        }

        body {
            margin: 0;
            background: #f4f7fb;
            color: #14233e;
            font-family: system-ui, -apple-system, Segoe UI, sans-serif
        }

        header {
            background: #0e1d36;
            color: white;
            padding: 22px max(24px, calc((100vw - 1050px)/2));
            font-weight: 800
        }

        header a {
            color: white;
            text-decoration: none
        }

        main {
            max-width: 1050px;
            margin: 40px auto;
            padding: 0 20px
        }

        .back {
            color: #2563eb;
            text-decoration: none
        }

        .panel {
            display: grid;
            grid-template-columns: 1fr 1fr;
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 20px;
            overflow: hidden;
            margin-top: 22px
        }

        .visual {
            background: linear-gradient(145deg, #dce8f9, #adc9ef);
            min-height: 360px;
            display: grid;
            place-items: center;
            color: #5379af
        }

        .visual img { width: 100%; height: 100%; min-height: 360px; object-fit: cover; display: block; }
        .visual svg {
            width: 170px;
            height: 170px
        }

        .details {
            padding: 38px
        }

        .eyebrow {
            color: #2563eb;
            font-size: 12px;
            font-weight: 800;
            letter-spacing: .15em
        }

        .details h1 {
            font-size: 32px;
            margin: 12px 0
        }

        .price {
            font-size: 32px;
            font-weight: 850;
            color: #173f80;
            margin: 22px 0
        }

        dl {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px
        }

        dt {
            color: #64748b;
            font-size: 12px
        }

        dd {
            margin: 5px 0;
            font-weight: 750
        }

        .notice {
            margin-top: 22px;
            color: #64748b;
            font-size: 13px;
            line-height: 1.6
        }

        @media(max-width:750px) {
            .panel {
                grid-template-columns: 1fr
            }

            .visual {
                min-height: 240px
            }

            .details {
                padding: 25px
            }
        }
    </style>
</head>

<body>
    <header><a href="{{ route('catalogo.index') }}">COFIHO V2 · CATÁLOGO</a></header>
    <main><a class="back" href="{{ route('catalogo.index') }}">← Volver al catálogo</a>
        <div class="panel">
            <div class="visual">
                @php
                    $imagen = asset('imagenes/catalogo/prueba1.jpg');
                    foreach (['jpg', 'jpeg', 'png', 'webp'] as $extension) {
                        $rutaStorage = 'vehiculos/' . $vehiculo->id_vehiculo . '.' . $extension;
                        $rutaAnterior = 'imagenes/catalogo/' . $vehiculo->id_vehiculo . '.' . $extension;
                        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($rutaStorage)) {
                            $imagen = asset('storage/' . $rutaStorage);
                            break;
                        }
                        if (file_exists(public_path($rutaAnterior))) {
                            $imagen = asset($rutaAnterior);
                            break;
                        }
                    }
                @endphp
                <img src="{{ $imagen }}" alt="{{ $vehiculo->modelo?->marca?->nombre ?? 'Marca' }} {{ $vehiculo->modelo?->nombre ?? 'Modelo' }}">
            </div>
            <section class="details">
                <div class="eyebrow">{{ $vehiculo->condicion }} · DISPONIBLE</div>
                <h1>{{ $vehiculo->modelo?->marca?->nombre ?? 'Marca' }} {{ $vehiculo->modelo?->nombre ?? 'Modelo' }}
                </h1>
                <div class="price">Q {{ number_format((float) $vehiculo->precio_lista, 2) }}</div>
                <dl>
                    <div>
                        <dt>Año</dt>
                        <dd>{{ $vehiculo->anio }}</dd>
                    </div>
                    <div>
                        <dt>Color</dt>
                        <dd>{{ $vehiculo->color }}</dd>
                    </div>
                    <div>
                        <dt>Tipo</dt>
                        <dd>{{ $vehiculo->tipoVehiculo?->nombre ?? 'No especificado' }}</dd>
                    </div>
                    <div>
                        <dt>Condición</dt>
                        <dd>{{ $vehiculo->condicion }}</dd>
                    </div>
                    <div>
                        <dt>Ubicación</dt>
                        <dd>{{ $inventarioActual?->predio?->nombre ?? 'Consultar' }}</dd>
                    </div>
                    <div>
                        <dt>Estado</dt>
                        <dd>Disponible</dd>
                    </div>
                </dl>
                <p class="notice">Esta es una página pública de consulta. La disponibilidad y el precio final deben
                    confirmarse con la concesionaria. No se realizan reservas ni pagos desde este sitio.</p>
            </section>
        </div>
    </main>
</body>

</html>
