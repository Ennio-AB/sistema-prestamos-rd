<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Contrato de Préstamo #{{ $loan->id }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 12pt; color: #111; padding: 40px; line-height: 1.6; }
        h1 { text-align: center; font-size: 16pt; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 4px; }
        .subtitle { text-align: center; font-size: 10pt; color: #555; margin-bottom: 30px; }
        .section { margin-bottom: 20px; }
        .section h2 { font-size: 11pt; text-transform: uppercase; border-bottom: 1px solid #ccc; padding-bottom: 4px; margin-bottom: 10px; color: #333; }
        .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 8px 30px; }
        .field label { font-size: 9pt; color: #555; display: block; }
        .field span { font-weight: bold; }
        .clausulas { text-align: justify; }
        .clausulas p { margin-bottom: 10px; }
        .firmas { display: grid; grid-template-columns: 1fr 1fr; gap: 60px; margin-top: 60px; }
        .firma-box { text-align: center; }
        .firma-line { border-top: 1px solid #333; padding-top: 6px; margin-top: 40px; font-size: 10pt; }
        @media print { body { padding: 20px; } .no-print { display: none; } }
    </style>
</head>
<body>

<div class="no-print" style="background:#f0f0f0;padding:10px;text-align:center;margin-bottom:20px;">
    <button onclick="window.print()" style="background:#1d4ed8;color:white;border:none;padding:10px 24px;border-radius:6px;cursor:pointer;font-size:13px;">
        Imprimir / Guardar PDF
    </button>
    <a href="{{ route('loans.show', $loan) }}" style="margin-left:16px;color:#555;text-decoration:none;">← Volver</a>
</div>

<h1>Contrato de Préstamo</h1>
<p class="subtitle">República Dominicana · Contrato #{{ $loan->id }} · {{ now()->format('d/m/Y') }}</p>

<div class="section">
    <h2>Datos del Prestatario</h2>
    <div class="grid">
        <div class="field"><label>Nombre completo</label><span>{{ $loan->client->nombre }}</span></div>
        <div class="field"><label>Cédula de Identidad</label><span>{{ $loan->client->cedula_formateada }}</span></div>
        <div class="field"><label>Teléfono</label><span>{{ $loan->client->telefono ?? 'N/A' }}</span></div>
        <div class="field"><label>Dirección</label><span>{{ $loan->client->direccion ?? 'N/A' }}</span></div>
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
        <div class="field"><label>Mora por atraso</label><span>{{ $loan->mora_porcentaje }}% sobre cuota vencida</span></div>
    </div>
</div>

<div class="section clausulas">
    <h2>Cláusulas y Condiciones</h2>
    <p><strong>PRIMERO:</strong> El prestatario declara haber recibido la suma de
    <strong>RD${{ number_format($loan->monto, 2) }}</strong> ({{ strtoupper(num2words($loan->monto) ?? number_format($loan->monto, 2)) }} PESOS DOMINICANOS)
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
