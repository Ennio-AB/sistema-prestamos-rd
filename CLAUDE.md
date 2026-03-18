# CLAUDE.md — Sistema de Préstamos RD (Laravel)

## Descripción del Proyecto
Aplicación web administrativa para gestión de préstamos en República Dominicana.
Stack: PHP + Laravel, MySQL, Blade, Bootstrap/Tailwind.

## Reglas de Desarrollo

### Stack tecnológico
- **Backend:** Laravel (PHP 8.x+)
- **Frontend:** Blade templates + Bootstrap 5 o Tailwind CSS
- **Base de datos:** MySQL (via Laragon)
- **PDF:** barryvdh/laravel-dompdf
- **Auth:** Laravel Breeze o Jetstream con roles (Admin, Cobrador)

### Convenciones de código
- Nombres de clases en **PascalCase** (ej: `LoanController`)
- Métodos y variables en **camelCase** (ej: `calcularInteres`)
- Columnas de BD en **snake_case** (ej: `fecha_vencimiento`)
- Rutas en **kebab-case** (ej: `/prestamos/crear`)
- Idioma del código fuente: **inglés** (variables, métodos, clases)
- Idioma de la UI y comentarios: **español**

### Estructura de modelos
- `Client` — Datos del prestatario
- `Loan` — Préstamo con interés, plazo, frecuencia, mora
- `Installment` — Cuotas generadas automáticamente
- `Payment` — Pagos registrados
- `LegalAction` — Historial de acciones legales

### Reglas de negocio
- El interés puede ser mensual o anual (campo `tipo_interes`)
- Frecuencias soportadas: diario, semanal, mensual
- Estados de préstamo: `activo`, `atrasado`, `en_cobranza`, `legal`, `cerrado`
- La mora se calcula automáticamente sobre cuotas vencidas
- Generar cuotas al crear el préstamo

### Migraciones
- Siempre usar `php artisan make:migration` — nunca editar BD directamente
- Orden de migración: clients → loans → installments → payments → legal_actions

### Seguridad
- Validar todas las entradas con Form Requests
- Usar políticas (Policies) para control de acceso por rol
- No exponer IDs secuenciales en URLs — usar UUIDs o hashids si es necesario
- Escapar siempre en Blade con `{{ }}`, usar `{!! !!}` solo cuando sea seguro

### Git
- Ramas: `main` (producción), `develop` (desarrollo), `feature/nombre-feature`
- Commits en español, descriptivos y atómicos
- No hacer commit de `.env`, `storage/`, `vendor/`, `node_modules/`

### Comandos frecuentes
```bash
php artisan migrate:fresh --seed   # Resetear BD con seeders
php artisan make:model X -mcrs     # Modelo + migración + controlador + resource + seeder
php artisan serve                  # Servidor de desarrollo
```

## Módulos del sistema
1. **Dashboard** — Resumen financiero (total prestado, cobrado, ganancia, morosos)
2. **Clientes** — CRUD completo
3. **Préstamos** — Crear, ver cuotas, registrar pagos, cambiar estado
4. **Pagos** — Historial y registro
5. **Legal** — Acciones legales y seguimiento
6. **Contratos** — Generación de PDF
7. **Usuarios/Roles** — Admin y Cobrador
