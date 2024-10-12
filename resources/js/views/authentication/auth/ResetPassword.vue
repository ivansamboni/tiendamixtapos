<template>
    <v-row class="bg-containerBg position-relative" no-gutters>
      <v-col cols="12">
        <div class="pt-6 pl-6">
          <!---Logo-->
        </div>
      </v-col>
      <!---Login Part-->
      <v-col cols="12" lg="12" class="d-flex align-center">
        <v-container>
          <div class="d-flex align-center justify-center" style="min-height: calc(100vh - 148px)">
            <v-row justify="center">
              <v-col cols="12" md="12">
                <v-card elevation="0" class="loginBox">
                  <v-card elevation="24">
                    <v-card-text class="pa-sm-10 pa-6">
                     
                        <h2 class="text-primary text-center">
                           Restablecer contraseña
                        </h2>
                     
                      <v-alert
                        v-show="errormsg"
                        color="error"
                        text="Algo salió mal, puede que el correo esté mal escrito o no exista la contraseña deben coincidir y tener mínimo 8 caracteres o el enlace de restablecimiento caducó"
                      ></v-alert
                      ><br />
                      <v-form @submit.prevent="resetPassword()">
                        <div class="mb-6">
                          <v-text-field
                            v-model="user.email"
                            :rules="emailRules"
                            required
                            hide-details="auto"
                            variant="underlined"
                            color="info"
                            label="Correo electrónico"
                          ></v-text-field>
                        </div>
                        <div>
                          <v-text-field
                            v-model="user.password"
                            :rules="passwordRules"
                            required
                            hide-details="auto"
                            variant="underlined"
                            color="info"
                            label="Nueva contraseña"
                            :type="Showpassword ? 'text' : 'password'"
                          ></v-text-field>
                          <br />
                          <v-text-field
                            v-model="user.password_confirmation"
                            :rules="confirmpasswordRules"
                            required
                            hide-details="auto"
                            variant="underlined"
                            color="info"
                            label="Confirmar contraseña"
                            :type="Showpassword ? 'text' : 'password'"
                          ></v-text-field>
                          <br />
  
                          <v-btn class="mt-7" type="submit" block color="success"
                            ><i class="bi bi-key"></i> Restablecer contraseña</v-btn
                          >
                        </div> </v-form
                      ><br />
                      <div class="text-center">
                        <router-link to="/auth/login" class="text-primary text-decoration-none"
                          >Regresar al login</router-link
                        >
                      </div>
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
  import axios from 'axios'
  import { ref } from 'vue'
  import { useRoute, useRouter } from 'vue-router'
  const router = useRouter()
  const route = useRoute()
  const url = import.meta.env.VITE_APP_API_URL;
  
  const user = ref({
    email: '',
    password: '',
    password_confirmation: '',
    token: route.params.token
  })
  const emailRules = ref([
    (v) => !!v || 'El campo es requerido',
    (v) => /.+@.+\..+/.test(v) || 'E-mail debe ser valido'
  ])
  const passwordRules = ref([
    (v) => !!v || 'Contraseña es requerida',
    (v) => (v && v.length <= 12) || 'La contraseña debe tener 8 o mas caracteres  ',
    (v) => (v && v.length >= 8) || 'La contraseña debe tener 8 o mas caracteres  '
  ])
  const confirmpasswordRules = ref([
    (v) => !!v || 'Confirme la contraseña ',
    (v) => v === user.value.password || 'La contraseña no coincide  '
  ])
  const Showpassword = ref(false)
  const errormsg = ref(false)
  const succesmsg = ref(false)
  const spinnerload = ref(false)
  
  const resetPassword = async () => {
    spinnerload.value = true
    errormsg.value = false
    try {
      const res = await axios.post(url + 'api/reset-password', user.value)
      spinnerload.value = false
      succesmsg.value = true
      alert('Se cambión la contraseña con éxito usted será redirigido al inicio de sesión')
      router.push('/')
      console.log(res)
    } catch (error) {
      console.log(error)
      spinnerload.value = false
      errormsg.value = true
    }
  }
  </script>
  <style lang="scss">
  .loginBox {
    max-width: 475px;
    margin: 0 auto;
  }
  </style>