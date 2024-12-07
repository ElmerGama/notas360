<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Períodos</title>
    <style>
        /* Configurar los márgenes para el tamaño carta */
        body {
            font-family: Arial, sans-serif;
            margin: 2cm;
            padding: 0;
        }

        header {
            text-align: center;
            margin-bottom: 20px;
        }

        /* Logo y título de la institución */
        .logo {
            display: inline-block;
            vertical-align: middle;
        }

        .title {
            display: inline-block;
            font-size: 1.5em;
            font-weight: bold;
            vertical-align: middle;
            margin-left: 10px;
        }

        /* Tabla para los períodos */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid black;
        }

        th, td {
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #ff9b04;
        }

        footer {
            position: fixed;
            bottom: 1cm;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 0.8em;
            color: #ff9b04;
        }
    </style>
</head>
<body>
    <header>
        <img src="{{ $logo }}" alt="Logo de la Institución" class="logo" width="100">
        <div class="title">Unidad Educativa Guadalupana</div>
    </header>

    <h2 style="text-align: center;">Reporte de Períodos</h2>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Gestión</th>
                <th>Nombre del Período</th>
                <th>Estado</th>
                <th>Creado en</th>
                <th>Actualizado en</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($periodos as $periodo)
                <tr>
                    <td>{{ $periodo->id }}</td>
                    <td>{{ $periodo->gestion->nombre_gestion }}</td>
                    <td>{{ $periodo->nombre_periodo }}</td>
                    <td>{{ $periodo->estado == 0 ? 'Abierto' : 'Cerrado' }}</td>
                    <td>{{ $periodo->created_at }}</td>
                    <td>{{ $periodo->updated_at }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <footer>
        Reporte generado el {{ \Carbon\Carbon::now()->format('d-m-Y') }}
    </footer>
</body>
</html>
