<script setup lang="ts">
import { ref } from 'vue';
// icons
import { EyeInvisibleOutlined, EyeOutlined } from '@ant-design/icons-vue';
import { useAuthStore } from '@/stores/auth';
import { Form } from 'vee-validate';


const checkbox = ref(false);
const valid = ref(false);
const show1 = ref(false);
const password = ref('');
const email = ref('');
const passwordRules = ref([
  (v: string) => !!v || 'Introduce una contraseña',
  (v: string) => (v && v.length <= 10) || 'La contraseña debe tener 8 o mas caracteres '
]);
const emailRules = ref([(v: string) => !!v || 'Introduce una dirección de correo', (v: string) => /.+@.+\..+/.test(v) || 'Correo electrónico   debe ser valido']);

/* eslint-disable @typescript-eslint/no-explicit-any */
function validate(values: any, { setErrors }: any) {
  const authStore = useAuthStore();
  return authStore.login(email.value, password.value).catch((error) => setErrors({ apiError: error }));
}
</script>

<template>
  
    <h2 class="text-primary text-center">Iniciar sesión</h2>
    <br>    
 
  <Form @submit="validate" class="mt-7 loginForm" v-slot="{ errors, isSubmitting }">
    <div class="mb-6">
     
      <v-text-field
        aria-label="email address"
        label="Correo electrónico"
        v-model="email"
        :rules="emailRules"
        class="mt-2"
        required
        hide-details="auto"
        variant="underlined"
        color="primary"
      ></v-text-field>
    </div>
    <div>
      
      <v-text-field
        aria-label="password"
        label="Contraseña"
        v-model="password"
        :rules="passwordRules"
        required        
        variant="underlined"
        color="primary"
        hide-details="auto"
        :type="show1 ? 'text' : 'password'"
        class="pwdInput mt-2"
      >
        <template v-slot:append>
          <v-btn color="secondary" icon rounded variant="text">
            <EyeInvisibleOutlined :style="{ color: 'rgb(var(--v-theme-secondary))' }" v-if="show1 == false" @click="show1 = !show1" />
            <EyeOutlined :style="{ color: 'rgb(var(--v-theme-secondary))' }" v-if="show1 == true" @click="show1 = !show1" />
          </v-btn>
        </template>
      </v-text-field>
    </div>
    <br>

    <div class="d-flex align-center mt-4 mb-7 mb-sm-0">
      <router-link to="/auth/register" class="text-primary text-decoration-none">¡Registrate!</router-link>
      <div class="ml-auto">
        <router-link to="/auth/olvidepassword" class="text-primary link-hover">¿Olvidaste tu contraseña?</router-link>
      </div>
    
    </div>
    <v-btn color="success" :loading="isSubmitting" block class="mt-5" variant="flat" size="large" :disabled="valid" type="submit">
      Iniciar sesión</v-btn
    >
    <div v-if="errors.apiError" class="mt-2">
      <h3 class="text-error">El correo o la contraseña son incorrectos</h3>
    </div>
  </Form>
</template>
<style lang="scss">
.loginForm {
  .v-text-field .v-field--active input {
    font-weight: 500;
  }
}
</style>
