<template>  
  <v-col cols="12" class="align-center">
      <v-alert color="success" theme="dark" prominent>
        <h2 class="text-h4 mb-6 pa-7 text-light">
          <i class="bi bi-check-circle"></i>¡ Te has registrado con éxito !
        </h2>
      </v-alert>
      <br />
  
      <br />
  
      <p class="mb-4 text-medium-emphasis text-body-6">
        ¡Bienvenido a <strong>Centro De Conciliación Asojuridica Chía!</strong> Nos complace confirmar
        que su registro se ha completado exitosamente. Hemos enviado un mensaje de confirmación a su
        dirección de <i class="bi bi-envelope"></i> correo electrónico. Este correo contiene detalles
        importantes sobre su registro y cómo puede aprovechar al máximo nuestros servicios. Le
        recomendamos revisar su bandeja de entrada y, si es necesario, la carpeta de spam, para
        asegurarse de recibir toda la información relevante sin contratiempos. Si tiene alguna
        pregunta o necesita asistencia adicional, no dude en contactarnos. Estamos aquí para ayudarle
        y guiarle en todo lo que necesite. Gracias por confiar en
        <strong>Centro De Conciliación Asojuridica Chía.</strong>  
        <br />
      </p>
  
      <v-divider class="mb-4"></v-divider>
  
      <div class="text-center">
        <v-btn to="/auth/login" class="text-none" color="info" variant="flat" width="200" rounded>
          Iniciar sesión
        </v-btn>
      </div>   
    </v-col>
  </template>
  <script setup>
  </script>
  