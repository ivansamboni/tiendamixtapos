<script setup>
import { useCustomizerStore } from '../../../stores/customizer';
import { ref, onMounted } from 'vue';
import { useAuthStore } from '@/stores/auth';
// icons
import {
  MenuFoldOutlined, SearchOutlined, UserOutlined, LogoutOutlined,
  EditOutlined,
  ProfileOutlined,
} from '@ant-design/icons-vue';
import Pusher from 'pusher-js';

const authStore = useAuthStore();
const tab = ref(null);
const customizer = useCustomizerStore();
const mensajepush = ref([]);
const menu = ref(false);
const sticky = false;
const countNotific = ref('');

Pusher.logToConsole = false;

onMounted(() => {

    const pusher = new Pusher('27850b0749389f5d2d8c', {
      cluster: 'sa1'
    });
    
    var channel = pusher.subscribe('canaltest');
    channel.bind('App\\Events\\TestEvent', function (data) {     
      mensajepush.value.push(JSON.stringify(data.mensaje));
      countNotific.value = mensajepush.value.length;
      
    });  
});

</script>

<template>
  <v-app-bar elevation="2" height="60">
    <v-btn class="hidden-md-and-down text-secondary mr-3" color="darkText" icon rounded="sm" variant="text"
      @click.stop="customizer.SET_MINI_SIDEBAR(!customizer.mini_sidebar)" size="small">
      <MenuFoldOutlined :style="{ fontSize: '16px' }" />
    </v-btn>
    <v-btn class="hidden-lg-and-up text-secondary ms-3" color="darkText" icon rounded="sm" variant="text"
      @click.stop="customizer.SET_SIDEBAR_DRAWER" size="small">
      <MenuFoldOutlined :style="{ fontSize: '16px' }" />
    </v-btn>

    <!---/Search part -->

    <v-spacer />
    <!-- ---------------------------------------------- -->
    <!-- User Profile -->
    <!-- ---------------------------------------------- -->
    

  <div class="text-center">
    <v-menu
      v-model="menu"
      :close-on-content-click="false"
      location="end"
    >
      <template v-slot:activator="{ props }">
        <v-btn
          color="indigo"
          v-bind="props"
          @click="( countNotific='0')"
        >Notifications  ({{ countNotific }})
        </v-btn>
      </template>

      <v-card
    class="overflow-auto mx-auto"
    max-height="300"
    width="448"
  >   
  
    <v-alert v-for="(mensajepush, index) in mensajepush" :key="index"  :text="( mensajepush )" type="info" variant="outlined"></v-alert>
             

    <v-card-text class="bg-grey-lighten-4">
      <v-sheet
        class="mx-auto"
        height="auto"
      ></v-sheet>
    </v-card-text>
   
  </v-card>
    </v-menu>
  </div>

   

    <v-menu :close-on-content-click="true" offset="8, 0">
      <template v-slot:activator="{ props }">
        <v-btn class="profileBtn" variant="text" rounded="sm" v-bind="props">
          <div class="d-flex align-center">
           
            <v-avatar class="mr-sm-2 mr-0 py-2">
              <UserOutlined />
            </v-avatar>
            {{ authStore.user.user.nombres }},{{ authStore.user.user.apellidos ? authStore.user.user.apellidos : ''
            }}
          </div>
        </v-btn>
      </template>
      <v-sheet rounded="md" width="290">
        <v-list class="py-0" aria-label="profile list" aria-busy="false">
          <v-list-item router-link to="/editarperfil" color="primary" rounded="0" value="Edit profile">
            <template v-slot:prepend>
              <EditOutlined :style="{ fontSize: '14px' }" class="mr-4" />
            </template>
            <v-list-item-title class="text-subtitle-2">Editar Perfil</v-list-item-title>
          </v-list-item>


          <v-list-item @click="authStore.logout()" color="secondary" rounded="0">
            <template v-slot:prepend>
              <LogoutOutlined :style="{ fontSize: '14px' }" class="mr-4" />
            </template>

            <v-list-item-title class="text-subtitle-2"> Cerrar Sesión</v-list-item-title>
          </v-list-item>
        </v-list>
      </v-sheet>
    </v-menu>
  </v-app-bar>
</template>
