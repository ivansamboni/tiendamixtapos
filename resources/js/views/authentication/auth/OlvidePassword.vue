<template>
    <v-row class="bg-containerBg position-relative" no-gutters>
      
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
                           Recuperar contraseña
                        </h2> 
                      
                      <v-form @submit.prevent="resetLink()" class="mt-7 loginForm">
                        <div class="text-center">
                          <v-progress-circular
                            v-show="spinnerload"
                            color="primary"
                            indeterminate
                          ></v-progress-circular>
                        </div>
                        <v-alert
                          v-show="errormsg"
                          color="error"
                          text="Algo salió mal, puede que el correo esté mal escrito o no exista"
                        ></v-alert>
                        <v-alert
                          v-show="succesmsg"
                          color="success"
                          text="Se envió un enlace de restablecimiento de contraseña a tu correo revisa tu bandeja de entrada o en correos no deseados puedes cerrar esta pagina."
                        ></v-alert>
                        <br />
                        <div class="mb-6">
                          <v-text-field
                            v-model="user.email"
                            required
                            hide-details="auto"
                            variant="underlined"
                            color="info"
                            label="Correo electrónico"
                          ></v-text-field>
                        </div>
                        <div>
                          <v-btn class="mt-7" type="submit" block color="success"
                            ><i class="bi bi-key"></i> Enviar enlace de restablecimiento</v-btn
                          >
                        </div>
                      </v-form>
                      <br />
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
  import { ref } from 'vue'
  import axios from 'axios'
  
  const url = import.meta.env.VITE_APP_API_URL;
  const user = ref({
    email: ''
  })
  const spinnerload = ref(false)
  const errormsg = ref(false)
  const succesmsg = ref(false)
  
  const resetLink = async () => {
    try {
      errormsg.value = false
      spinnerload.value = true
      const res = await axios.post(url + 'api/forgot-password', user.value)
      spinnerload.value = false
      succesmsg.value = true
      user.value.email = ''
    } catch (err) {
      spinnerload.value = false
      succesmsg.value = false
      errormsg.value = true
      console.log(err)
    }
  }
  </script>
  <style lang="scss">
  .loginBox {
    max-width: 475px;
    margin: 0 auto;
  }
  </style>
  