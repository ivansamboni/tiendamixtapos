<template>

  <div v-show="cardwelcome">
    <Welcome />
  </div>

  <v-row class="bg-containerBg position-relative" no-gutters v-if="cardregistro">
    <!---Login Part-->
    <v-col cols="10" lg="8" class="d-flex align-center">
      <v-container>
        <div class="d-flex align-center justify-center" style="min-height: calc(100vh - 148px)">
          <v-row justify="center">
            <v-col cols="12" md="7">
              <v-card elevation="0" class="registerBox">
                <v-card elevation="24">
                  <v-card-text class="pa-sm-20 pa-8">
                    <v-form @submit.prevent="register()" class="mt-1 loginForm">
                      <v-container>
                        <h3 class="text-primary text-center">Registro de Usuario</h3>
                        <br />
                        <v-alert color="error" v-show="errormsg">
                          <p>
                            {{ errortext }}
                          </p>
                        </v-alert>
                        <div class="text-center">
                          <v-progress-circular v-show="spinnerload" color="primary" indeterminate></v-progress-circular>
                        </div>
                        <br />
                        <v-row>
                          <v-col cols="12" md="4">
                            <v-select v-model="txtregdata.tipoidentificacion" :rules="nombreRules"
                              label="Tipo de Identificación" variant="underlined" color="info" :items="[
                                'CÉDULA DE CIUDADANÍA',
                                'CÉDULA DE EXTRANJERÍA',
                                'TARJETA DE IDENTIDAD',
                                'REGISTRO CIVIL',
                                'PASAPORTE',
                                'NÚMERO DE IDENTIFICACIÓN DE EXTRANJERO'
                              ]"></v-select>
                          </v-col>
                          <v-col cols="12" md="4">
                            <v-text-field v-model="txtregdata.numidentificacion" :rules="nombreRules" required
                              hide-details="auto" variant="underlined" color="info"
                              label="Numero de Identificación"></v-text-field>
                            <v-text-field v-show="numnit" v-model="txtregdata.numidentificacion" :rules="nombreRules"
                              required hide-details="auto" variant="underlined" color="info" label="NIT"></v-text-field>
                          </v-col>
                        </v-row>
                        <v-row>
                          <v-col cols="12" md="4">
                            <v-text-field v-model="txtregdata.nombres" :rules="nombreRules" required hide-details="auto"
                              variant="underlined" color="info" label="Nombres"
                              @input="txtregdata.nombres = $event.target.value.toUpperCase()"></v-text-field>

                          </v-col>
                          <v-col cols="12" md="4">
                            <v-text-field v-model="txtregdata.apellidos" hide-details="auto" variant="underlined"
                              color="info" label="Apellidos"
                              @input="txtregdata.apellidos = $event.target.value.toUpperCase()"></v-text-field>
                          </v-col>

                        </v-row>
                        <v-row>
                          <v-col cols="12" md="4">
                            <v-text-field v-model="txtregdata.telefono" :rules="telefonoRules" required
                              hide-details="auto" variant="underlined" color="info" label="Teléfono"
                              @input="txtregdata.telefono = $event.target.value.replace(/\D/g, '')"></v-text-field>
                          </v-col>
                          <v-col cols="12" md="4">
                            <v-text-field v-model="txtregdata.email" :rules="emailRules" required hide-details="auto"
                              variant="underlined" color="info" label="Correo Electrónico"></v-text-field>
                          </v-col>
                        </v-row>
                        <v-row>
                          <v-col cols="12" md="8">
                            <v-text-field v-model="txtregdata.direccion" :rules="nombreRules" required
                              hide-details="auto" variant="underlined" color="info"
                              label="Dirección domicilio"></v-text-field>
                          </v-col>
                        </v-row>
                        <v-row>
                          <v-col cols="12" md="4">
                            <v-text-field v-model="txtregdata.password" :rules="passwordRules" required
                              :type="txtregdata.Showpassword ? 'text' : 'password'" hide-details="auto"
                              variant="underlined" color="info" label="Contraseña"></v-text-field>
                          </v-col>
                          <v-col cols="12" md="4">
                            <v-text-field v-model="txtregdata.password_confirmation" :rules="confirmpasswordRules"
                              required :type="txtregdata.Showpassword ? 'text' : 'password'" hide-details="auto"
                              variant="underlined" color="info" label="Confirmar Contraseña"></v-text-field>
                          </v-col>
                        </v-row>
                        <br />
                        <br />
                        <br />
                        <v-btn type="submit" color="success"><i class="bi bi-person-plus-fill"></i> Crear
                          cuenta</v-btn><br />
                        <div class="text-end">
                          <router-link to="/auth/login" class="text-primary text-decoration-none">Volver al login
                          </router-link>
                        </div>
                      </v-container>
                    </v-form>
                  </v-card-text>
                </v-card>
              </v-card>
            </v-col>
          </v-row>
        </div>
      </v-container>
    </v-col>
  </v-row>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';
import Welcome from '@/components/Welcome.vue';

const url = import.meta.env.VITE_APP_API_URL;

const txtregdata = ref({
  tipoidentificacion: '',
  numidentificacion: null,
  nombres: '',
  apellidos: '',
  telefono: '',
  email: '',
  password: '',
  password_confirmation: '',
  Showpassword: false
});
const nombreRules = ref([(v) => !!v || 'El campo es requerido']);
const telefonoRules = ref([(v) => !!v || 'El campo es requerido']);
const emailRules = ref([(v) => !!v || 'El campo es requerido', (v) => /.+@.+\..+/.test(v) || 'E-mail debe ser valido']);
const passwordRules = ref([
  (v) => !!v || 'Contraseña es requerida',
  (v) => (v && v.length <= 12) || 'La contraseña debe tener 8 o mas caracteres',
  (v) => (v && v.length >= 8) || 'La contraseña debe tener 8 o mas caracteres'
]);
const confirmpasswordRules = ref([
  (v) => !!v || 'Confirme la contraseña ',
  (v) => v === txtregdata.value.password || 'La contraseña no coincide  '
]);


const spinnerload = ref(false);
const cardregistro = ref(true);
const cardwelcome = ref(false);
const errormsg = ref(false);
const errortext = ref('');


const register = async () => {
  errormsg.value = false;
  spinnerload.value = true;
  try {
    const res = await axios.post(url + 'api/register', txtregdata.value);
    spinnerload.value = false;
    cardregistro.value = false;
    cardwelcome.value = true;
    errormsg.value = false;
  } catch (error) {
    spinnerload.value = false;
    errormsg.value = true;
    errortext.value = error.response.data.message;
  }
};
</script>
<style lang="scss">
.registerBox {
  max-width: 1000px;
  margin: 0 auto;
}
</style>