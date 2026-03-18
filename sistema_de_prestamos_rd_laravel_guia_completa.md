# 📊 Sistema de Préstamos (República Dominicana) - Laravel

## 🧠 Descripción
Este proyecto consiste en una aplicación web administrativa para la gestión de préstamos en República Dominicana, desarrollada con PHP + Laravel.

Incluye:
- Gestión de clientes
- Creación de préstamos
- Cálculo de intereses
- Control de pagos
- Generación de contratos en PDF
- Seguimiento legal

---

## ⚖️ Consideraciones legales (RD)

- Evitar tasas de interés excesivas (usura)
- Definir claramente:
  - Interés (mensual o anual)
  - Mora (% por atraso)
- El contrato debe incluir:
  - Datos del cliente
  - Monto
  - Intereses
  - Forma de pago
  - Penalidades

---

## 🗄️ Base de Datos

### Tabla: clients
```sql
id
nombre
cedula
telefono
direccion
created_at
```

### Tabla: loans
```sql
id
client_id
usuario_id
monto
interes
tipo_interes (mensual/anual)
plazo
frecuencia (diario, semanal, mensual)
fecha_inicio
mora_porcentaje
estado (activo, atrasado, legal, cerrado)
created_at
```

### Tabla: installments
```sql
id
loan_id
numero_cuota
fecha_vencimiento
monto
mora
pagado
```

### Tabla: payments
```sql
id
loan_id
monto
fecha_pago
metodo
```

### Tabla: legal_actions
```sql
id
loan_id
tipo
descripcion
fecha
```

---

## ⚙️ Instalación del Proyecto

```bash
composer create-project laravel/laravel prestamos
cd prestamos
php artisan serve
```

---

## 📦 Modelos

```bash
php artisan make:model Client -m
php artisan make:model Loan -m
php artisan make:model Payment -m
php artisan make:model Installment -m
php artisan make:model LegalAction -m
```

---

## 🔗 Relaciones (Eloquent)

```php
// Client.php
public function loans() {
    return $this->hasMany(Loan::class);
}

// Loan.php
public function client() {
    return $this->belongsTo(Client::class);
}

public function payments() {
    return $this->hasMany(Payment::class);
}

public function installments() {
    return $this->hasMany(Installment::class);
}
```

---

## 🧮 Cálculo de Intereses

### Interés simple

```php
$interes = $monto * ($tasa / 100) * $tiempo;
$total = $monto + $interes;
$cuota = $total / $numCuotas;
```

---

## ⏰ Mora

```php
if ($cuota_vencida) {
    $mora = $cuota * ($loan->mora_porcentaje / 100);
}
```

---

## 📅 Generación de Cuotas

```php
for ($i = 1; $i <= $plazo; $i++) {
    Installment::create([
        'loan_id' => $loan->id,
        'numero_cuota' => $i,
        'fecha_vencimiento' => now()->addMonths($i),
        'monto' => $cuota,
        'pagado' => 0
    ]);
}
```

---

## 📄 Contrato (Blade)

```blade
<h2>CONTRATO DE PRÉSTAMO</h2>

<p>Cliente: {{ $loan->client->nombre }}</p>
<p>Cédula: {{ $loan->client->cedula }}</p>

<p>Monto: RD${{ $loan->monto }}</p>
<p>Interés: {{ $loan->interes }}%</p>
<p>Plazo: {{ $loan->plazo }} meses</p>

<p>
El cliente se compromete a pagar el monto recibido más intereses.
En caso de mora, se aplicará un recargo de {{ $loan->mora_porcentaje }}%.
</p>

<p>Firma: ____________________</p>
```

---

## 🧾 Generar PDF

```bash
composer require barryvdh/laravel-dompdf
```

```php
use Barryvdh\DomPDF\Facade\Pdf;

public function contrato($id) {
    $loan = Loan::with('client')->findOrFail($id);
    $pdf = Pdf::loadView('contrato', compact('loan'));
    return $pdf->stream('contrato.pdf');
}
```

---

## 📊 Dashboard

Debe mostrar:
- Total prestado
- Total cobrado
- Ganancia (intereses)
- Préstamos activos
- Morosos

---

## ⚖️ Módulo Legal

Estados del préstamo:
- Activo
- Atrasado
- En cobranza
- Legal
- Cerrado

Historial:
- Notificaciones
- Visitas
- Acciones legales

---

## 🔐 Roles

- Admin
- Cobrador

---

## 🚀 Mejoras futuras

- Notificaciones WhatsApp
- Firma digital
- Reportes Excel
- Scoring de clientes
- API REST

---

## ✅ Conclusión

Este sistema cubre los procesos reales de préstamos en RD:
- Control financiero
- Seguimiento legal
- Automatización de cálculos
- Generación de contratos

Listo para escalar a un sistema profesional.

