<?php

namespace App\Constants;

class PaymentConstants
{
    // Forma de pago
    public const FORMAS_PAGO = [
        '1' => 'Contado',
        '2' => 'Crédito',
    ];

    // Métodos de pago
    public const METODOS_PAGO = [
        '10' => 'Efectivo',
        '47' => 'Transferencia',
        '49' => 'Tarjeta Débito',
        '48' => 'Tarjeta Crédito',       
        '42' => 'Consignación',
        'ZZZ' => 'Otro',
    ];
}
