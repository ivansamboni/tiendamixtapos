<?php

namespace App\Constants;

class CodesDianConstants
{
    // Forma de pago
    public const TIPO_NOTA = [
        '20' => 'Referencia a factura electrónica',
        '22' => 'Sin referencia a factura',
    ];
  
    public const MOTIVOS_NOTA = [
        '1' => 'Devolución parcial de bienes o servicios',
        '2' => 'Anulación de factura electrónica',
        '3' => 'Rebaja o descuento total o parcial',
        '4' => 'Ajuste de precio',       
        '5' => 'Otros',     
    ];
}

  
