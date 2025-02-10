@extends('layouts.layout')

@section('title', 'Página de Inicio')

@section('content')
<script src="https://sdk.mercadopago.com/js/v2"></script>
 cheockout

 <div class="d-flex">
    <!-- Contenedor del botón -->
    <div id="wallet_container"></div>
</div>

 <script>
    const mp = new MercadoPago('APP_USR-ed24d67b-50b9-48d6-9fc2-959a5c0157f2', {
          locale: 'es-CO'
      });

      // Crea un componente de billetera de MercadoPago en el contenedor con id "wallet_container"
      mp.bricks().create("wallet", "wallet_container", {
          initialization: {
              preferenceId: '<?php echo $preference->id; ?>',
              redirectMode: 'self'
          },
          customization: {
              texts: {
                  action: "pay",
                  valueProp: 'security_safety',
              },
          },
      });      

  </script>

   
@endsection
