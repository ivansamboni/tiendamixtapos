<template>
  <v-row class="bg-containerBg position-relative" no-gutters >
    <!---Login Part-->
    <v-col cols="12" lg="12" class="d-flex align-center">
      <v-container>
        <div class="d-flex align-center justify-center" style="min-height: calc(100vh - 148px)">
          <v-row justify="center">
            <v-col cols="12" md="9">
              <v-card elevation="0" class="registerBox">
                <v-card elevation="24">
                  <v-card-text class="pa-sm-10 pa-6">
                    <v-form @submit.prevent="update()" class="mt-1 loginForm">
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
                        <br />
                        <br />
                        <v-alert color="success" v-show="succesmsg">
                          <p>
                            Se actualizaron los datos
                          </p>
                        </v-alert>
                        <br />
                        <v-btn type="submit" color="success"><i class="bi bi-person-plus-fill"></i> Actualizar</v-btn><br />                        
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
import { ref, onMounted } from 'vue';
import { useAuthStore } from '@/stores/auth';
import axiosInst from '@/components/axiosins';
const authStore = useAuthStore();
const userlocal = authStore.user.user;
const url = import.meta.env.VITE_APP_API_URL;

const txtregdata = ref({
  tipoidentificacion: '',
  numidentificacion: null,
  nombres: '',
  apellidos: '',
  telefono: '',
  direccion: '',
  email: ''
});

const nombreRules = ref([(v) => !!v || 'El campo es requerido']);
const emailRules = ref([(v) => !!v || 'El campo es requerido', (v) => /.+@.+\..+/.test(v) || 'E-mail debe ser valido']);
const spinnerload = ref(false);
const errormsg = ref(false);
const succesmsg = ref(true);
const errortext = ref('');


const dataUser = async () => {
  const res = await axiosInst.get(url + "api/datauser");
  txtregdata.value = res.data;
  authStore.user.user = res.data;

};

const update = async () => {
  errormsg.value = false;
  spinnerload.value = true;
  succesmsg.value = false;

  try {
    const res = await axiosInst.put(url + 'api/updateprofile', txtregdata.value);
    spinnerload.value = false;
    errormsg.value = false;
    succesmsg.value = true;
    dataUser();

  } catch (error) {
    dataUser();
    succesmsg.value = false;
    spinnerload.value = false;
    errormsg.value = true;
    errortext.value = error.response.data.message;
  }
};

onMounted(() => {
  dataUser();
});
</script>
<style lang="scss">
.registerBox {
  max-width: 1000px;
  margin: 0 auto;
}
</style>