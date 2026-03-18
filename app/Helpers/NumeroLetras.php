<?php

namespace App\Helpers;

class NumeroLetras
{
    private static array $unidades = [
        '', 'UN', 'DOS', 'TRES', 'CUATRO', 'CINCO', 'SEIS', 'SIETE', 'OCHO', 'NUEVE',
        'DIEZ', 'ONCE', 'DOCE', 'TRECE', 'CATORCE', 'QUINCE', 'DIECISÉIS',
        'DIECISIETE', 'DIECIOCHO', 'DIECINUEVE',
    ];

    private static array $decenas = [
        '', 'DIEZ', 'VEINTE', 'TREINTA', 'CUARENTA', 'CINCUENTA',
        'SESENTA', 'SETENTA', 'OCHENTA', 'NOVENTA',
    ];

    private static array $centenas = [
        '', 'CIENTO', 'DOSCIENTOS', 'TRESCIENTOS', 'CUATROCIENTOS', 'QUINIENTOS',
        'SEISCIENTOS', 'SETECIENTOS', 'OCHOCIENTOS', 'NOVECIENTOS',
    ];

    public static function convertir(float $numero): string
    {
        if ($numero < 0) {
            return 'MENOS ' . self::convertir(abs($numero));
        }

        $entero = (int) floor($numero);
        $centavos = (int) round(($numero - $entero) * 100);

        $letras = self::convertirEntero($entero);

        if ($centavos > 0) {
            $letras .= ' CON ' . str_pad($centavos, 2, '0', STR_PAD_LEFT) . '/100';
        } else {
            $letras .= ' CON 00/100';
        }

        return $letras;
    }

    private static function convertirEntero(int $n): string
    {
        if ($n === 0) return 'CERO';
        if ($n === 100) return 'CIEN';
        if ($n === 1000) return 'MIL';

        $resultado = '';

        if ($n >= 1000000) {
            $millones = (int) ($n / 1000000);
            $resultado .= ($millones === 1 ? 'UN MILLÓN' : self::convertirEntero($millones) . ' MILLONES');
            $n %= 1000000;
            if ($n > 0) $resultado .= ' ';
        }

        if ($n >= 1000) {
            $miles = (int) ($n / 1000);
            $resultado .= ($miles === 1 ? 'MIL' : self::convertirEntero($miles) . ' MIL');
            $n %= 1000;
            if ($n > 0) $resultado .= ' ';
        }

        if ($n >= 100) {
            $resultado .= self::$centenas[(int) ($n / 100)];
            $n %= 100;
            if ($n > 0) $resultado .= ' ';
        }

        if ($n >= 20) {
            $decena = (int) ($n / 10);
            $unidad = $n % 10;
            $resultado .= self::$decenas[$decena];
            if ($unidad > 0) $resultado .= ' Y ' . self::$unidades[$unidad];
        } elseif ($n > 0) {
            $resultado .= self::$unidades[$n];
        }

        return trim($resultado);
    }
}
