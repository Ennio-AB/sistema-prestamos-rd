<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Contrato de Préstamo #{{ $loan->id }}</title>
    <style>
        @page {
            size: letter portrait;
            margin: 15mm 18mm 15mm 18mm;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: Arial, sans-serif;
            font-size: 9.5pt;
            color: #111;
            line-height: 1.4;
            width: 100%;
        }
        /* Vista previa en pantalla: simula hoja letter */
        @media screen {
            html { background: #e5e7eb; min-height: 100vh; padding: 20px 0; }
            body {
                width: 216mm;
                min-height: 279mm;
                max-height: 279mm;
                overflow: hidden;
                margin: 0 auto;
                background: white;
                padding: 15mm 18mm;
                box-shadow: 0 4px 24px rgba(0,0,0,0.15);
            }
        }
        h1 { text-align: center; font-size: 13pt; text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 2px; }
        .subtitle { text-align: center; font-size: 8.5pt; color: #555; margin-bottom: 10px; }
        .section { margin-bottom: 9px; }
        .section h2 { font-size: 8.5pt; text-transform: uppercase; border-bottom: 1px solid #999; padding-bottom: 2px; margin-bottom: 6px; color: #333; letter-spacing: 0.5px; }
        .grid { display: grid; grid-template-columns: 1fr 1fr 1fr 1fr; gap: 4px 12px; }
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 4px 12px; }
        .field label { font-size: 7.5pt; color: #666; display: block; }
        .field span { font-weight: bold; font-size: 9pt; }
        .clausulas { text-align: justify; }
        .clausulas p { margin-bottom: 5px; font-size: 9pt; line-height: 1.35; }
        .firmas { display: grid; grid-template-columns: 1fr 1fr; gap: 40px; margin-top: 20px; }
        .firma-box { text-align: center; }
        .firma-line { border-top: 1px solid #333; padding-top: 4px; margin-top: 30px; font-size: 9pt; }
        .no-print { background: #1e3a8a; padding: 8px 16px; text-align: center; margin-bottom: 16px; display: flex; align-items: center; justify-content: center; gap: 16px; }
        @media print {
            .no-print { display: none !important; }
            html, body { background: white; box-shadow: none; padding: 0; margin: 0; width: auto; max-height: none; }
        }
    </style>
</head>
<body>

<div class="no-print">
    <button onclick="window.print()" style="background:white;color:#1e3a8a;border:none;padding:7px 20px;border-radius:5px;cursor:pointer;font-size:12px;font-weight:bold;">
        🖨 Imprimir / PDF
    </button>
    <a href="{{ route('loans.show', $loan) }}" style="color:#93c5fd;text-decoration:none;font-size:12px;">← Volver</a>
</div>

<h1>Contrato de Préstamo</h1>
<p class="subtitle">República Dominicana · Contrato #{{ $loan->id }} · {{ now()->format('d/m/Y') }}</p>

<div class="section">
    <h2>Datos del Prestatario</h2>
    <div class="grid">
        <div class="field" style="grid-column:span 2"><label>Nombre completo</label><span>{{ $loan->client->nombre }}</span></div>
        <div class="field"><label>Cédula de Identidad</label><span>{{ $loan->client->cedula_formateada }}</span></div>
        <div class="field"><label>Teléfono</label><span>{{ $loan->client->telefono ?? 'N/A' }}</span></div>
        <div class="field" style="grid-column:span 4"><label>Dirección</label><span>{{ $loan->client->direccion ?? 'N/A' }}</span></div>
    </div>
</div>

<div class="section">
    <h2>Condiciones del Préstamo</h2>
    <div class="grid">
        <div class="field"><label>Monto prestado</label><span>RD${{ number_format($loan->monto, 2) }}</span></div>
        <div class="field"><label>Tasa de interés</label><span>{{ $loan->interes }}% {{ $loan->tipo_interes }}</span></div>
        <div class="field"><label>Plazo</label><span>{{ $loan->plazo }} cuotas {{ $loan->frecuencia }}s</span></div>
        <div class="field"><label>Fecha de inicio</label><span>{{ $loan->fecha_inicio->format('d/m/Y') }}</span></div>
        <div class="field"><label>Cuota estimada</label><span>RD${{ number_format($loan->calcularCuota(), 2) }}</span></div>
        <div class="field"><label>Total a pagar</label><span>RD${{ number_format($loan->total_a_pagar, 2) }}</span></div>
        <div class="field"><label>Mora por atraso</label><span>{{ $loan->mora_porcentaje }}%</span></div>
        <div class="field"><label>Estado</label><span>{{ ucfirst($loan->estado) }}</span></div>
    </div>
</div>

<div class="section clausulas">
    <h2>Cláusulas y Condiciones</h2>
    <p><strong>PRIMERO:</strong> El prestatario declara haber recibido la suma de
    <strong>RD${{ number_format($loan->monto, 2) }}</strong>
    ({{ \App\Helpers\NumeroLetras::convertir($loan->monto) }} PESOS DOMINICANOS)
    en calidad de préstamo, comprometiéndose a devolver dicha suma junto con los intereses pactados.</p>

    <p><strong>SEGUNDO:</strong> El préstamo devengará un interés del <strong>{{ $loan->interes }}%
    {{ $loan->tipo_interes }}</strong>, calculado sobre el saldo insoluto. El pago se realizará en
    <strong>{{ $loan->plazo }} cuotas {{ $loan->frecuencia }}s</strong> de
    <strong>RD${{ number_format($loan->calcularCuota(), 2) }}</strong> cada una, con vencimiento a partir
    del {{ $loan->fecha_inicio->format('d/m/Y') }}.</p>

    <p><strong>TERCERO:</strong> En caso de atraso en el pago de cualquier cuota, se aplicará un recargo
    de <strong>{{ $loan->mora_porcentaje }}%</strong> sobre el monto de la cuota vencida por cada período
    de atraso.</p>

    <p><strong>CUARTO:</strong> El incumplimiento de <strong>dos (2) cuotas consecutivas</strong> dará
    derecho al acreedor a declarar vencida la totalidad de la deuda y proceder a las acciones legales
    correspondientes conforme a las leyes de la República Dominicana.</p>

    <p><strong>QUINTO:</strong> Las partes acuerdan que cualquier controversia derivada de este contrato
    será dirimida en los tribunales competentes de la República Dominicana.</p>
</div>

<div class="firmas">
    <div class="firma-box">
        <div class="firma-line">
            <strong>EL PRESTAMISTA</strong>
        </div>
    </div>
    <div class="firma-box">
        <div class="firma-line">
            <strong>{{ strtoupper($loan->client->nombre) }}</strong><br>
            <small>Cédula: {{ $loan->client->cedula_formateada }}</small>
        </div>
    </div>
</div>

</body>
</html>
