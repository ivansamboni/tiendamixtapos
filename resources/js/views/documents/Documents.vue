<template>
  <h3>Mis Archivos</h3>
  <br />
  <v-card>
    <v-card-text>
      <v-form ref="form">
        <v-text-field v-model="namedoc" hide-details="auto" variant="outlined" color="info" label="Descripción"></v-text-field>
        <br />
        <v-file-input v-model="file" prepend-icon="mdi-paperclip"></v-file-input>
        <v-progress-linear v-if="isUploading" v-model="uploadProgress" height="10" color="primary" class="mb-4"></v-progress-linear>
        <h4 class="text-center text-primary" v-if="isUploading">{{ uploadProgress }} %</h4>
        <v-btn @click="loadFile" :disabled="!file || isUploading" color="success" class="text-end"
          ><v-icon>mdi-cloud-upload</v-icon>&nbsp;Subir</v-btn
        >
      </v-form>
      <br />
      <hr />
      <br />

      <v-item-group multiple>
        <v-col cols="12" v-for="doc in docsUser" :key="doc.id">
          <v-icon>mdi-file-document-outline</v-icon>
          <strong>{{ doc.namedoc }}</strong> {{ $dateForm(doc.created_at) }}             
          {{ doc.file_path }}
         <div>                  
          <a link class="text-primary link-hover ml-2" href="#" @click="downloadFile(doc.file_path)">Descargar</a>
          <v-btn @click="deleteFile(doc.id,doc.file_path)" class="ml-4" density="compact" color="error" icon="mdi-delete"></v-btn>
        </div>
        </v-col>
      </v-item-group>
    </v-card-text>
  </v-card>

  <br />
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axiosInst from '@/components/axiosins';

const url = import.meta.env.VITE_APP_API_URL;
const docsUser = ref([]);
const file = ref(null);
const namedoc = ref('');
const isUploading = ref(false);
const uploadProgress = ref(0);

const documents = async () => {
  const res = await axiosInst.get(url + 'api/document');
  docsUser.value = res.data;
};

const downloadFile = async (file_path) => {
  try {
    const response = await axiosInst.get(url + 'api/document/' + file_path);
    if (response.status === 200) {
      const { base64, filename } = response.data;

      // Crear un enlace de descarga
      const link = document.createElement('a');
      link.href = base64;
      link.download = filename;
      document.body.appendChild(link);
      link.click();
      document.body.removeChild(link);
    } else {
      alert('Error al descargar el archivo.');
    }
  } catch (error) {
    alert('Error al descargar el archivo.');
    console.error(error);
  }
};

const loadFile = async () => {
  if (!file.value) return;
  const formData = new FormData();
  formData.append('file', file.value);
  formData.append('namedoc', namedoc.value);
  isUploading.value = true;
  try {
    const res = await axiosInst.post(url + 'api/document', formData, {
      headers: {
        'Content-Type': 'multipart/form-data'
      },
      onUploadProgress: (progressEvent) => {
        uploadProgress.value = Math.round((progressEvent.loaded * 100) / progressEvent.total);
      }
    });
  } catch (error) {
    alert('No se pudo subir el archivo asegurese que no exceda el limite de 20mb');
  } finally {
    isUploading.value = false;
    uploadProgress.value = 0;
    documents();
    file.value = '';
    namedoc.value = '';
  }
};

const deleteFile = async (id,file_path) => {
  let confirmac = confirm('¿Eliminar este archivo? '+file_path);
  if (confirmac) {
    const res = await axiosInst.delete(url + 'api/document/' + id);
    documents();
  }
};

onMounted(() => {
  documents();
});
</script>
