<template>
    <v-row class="bg-containerBg position-relative" no-gutters>

        <div class="text-center pa-4">
            <v-dialog width="600" v-model="dialogEdit">
                <v-card max-width="600">
                    <v-card-text class="pa-sm-10 pa-6">
                        <v-form @submit.prevent="registrar()" class="mt-1 loginForm">
                            <v-row>
                                <v-col cols="12" md="6">
                                    <v-text-field v-model="txtregdata.nombre" :rules="nombreRules" required
                                        hide-details="auto" variant="underlined" color="info" label="Nombre de Marca"
                                        @input="txtregdata.nombre = $event.target.value.toUpperCase()"></v-text-field>
                                </v-col>
                                <v-col cols="12" md="6">
                                    <v-text-field v-model="txtregdata.descripcion" required hide-details="auto"
                                        variant="underlined" color="info" label="Descripción"></v-text-field>
                                </v-col>
                            </v-row>

                            <v-card-actions>
                                <v-btn class="ms-auto" text="Cerrar" @click="dialogEdit = false"></v-btn>
                                <v-btn v-if="!editando" color="success" @click="actualizar">Actualizar</v-btn>
                                <v-btn v-if="editando" color="success" type="submit">Crear</v-btn>
                            </v-card-actions>
                        </v-form>
                    </v-card-text>
                </v-card>
            </v-dialog>
        </div>
    </v-row>
    <v-btn @click="nuevaMarca" icon="mdi mdi-plus" density="compact"></v-btn>
    <v-table>
        <thead>
            <tr>
                <th class="text-left">
                    Marca
                </th>
                <th class="text-left">
                    Descripción
                </th>
                <th class="text-left">
                    Acciones
                </th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="mac in marcasList.data" :key="mac.id">
                <td>{{ mac.nombre }}</td>
                <td>{{ mac.descripcion }} </td>
                <td>
                    <v-btn color="dark" @click="selecMarca(mac.id), dialogEdit = true" density="comfortable"
                        icon="mdi mdi-square-edit-outline" title="Editar"></v-btn>
                    <v-btn color="dark" @click="deleteMarca(mac.id)" density="comfortable" icon="mdi mdi-delete-forever"
                        title="Eliminar"></v-btn>
                </td>
            </tr>
        </tbody>
    </v-table>
    <br>
    <div class="text-center">
        <v-btn @click="getMarcas(marcasList.prev_page_url)" :disabled="!marcasList.prev_page_url"
            icon="mdi mdi-chevron-left" density="comfortable">
        </v-btn>
        <span>&nbsp;Página {{ marcasList.current_page }} / {{ marcasList.last_page }}&nbsp;</span>
        <v-btn @click="getMarcas(marcasList.next_page_url)" :disabled="!marcasList.next_page_url"
            icon="mdi mdi-chevron-right" density="comfortable">
        </v-btn>
    </div>

    <v-snackbar v-model="snackbarReg" :timeout="timeout">
        <h3 v-if="regerrormsg" class="text-error">{{ regerrormsg }}</h3>
        <h3 v-if="regsuccessmsg" class="text-success">{{ regsuccessmsg }}</h3>
        <template v-slot:actions>
            <v-btn color="blue" variant="text" @click="snackbarReg = false">
                Cerrar
            </v-btn>
        </template>
    </v-snackbar>
    <v-snackbar v-model="snackbarUpd" :timeout="timeout">
        <h3 v-if="upderrormsg" class="text-error">{{ upderrormsg }}</h3>
        <h3 v-if="updsuccessmsg" class="text-success">{{ updsuccessmsg }}</h3>
        <template v-slot:actions>
            <v-btn color="blue" variant="text" @click="snackbarUpd = false">
                Cerrar
            </v-btn>
        </template>
    </v-snackbar>

</template>

<script setup>
import { ref, onMounted } from 'vue'
import axiosInst from '@/components/axiosins'
import useRegister from '@/composables/useRegister'
import useUpdate from '@/composables/useUpdate'

const url = import.meta.env.VITE_APP_API_URL
const { register, regsuccessmsg, regerrormsg } = useRegister();
const { update, upderrormsg, updsuccessmsg } = useUpdate();
const txtregdata = ref({ id: '', nombre: '', descripcion: '', })
const marcasList = ref({
    current_page: 1,
    data: [],
    last_page: 1,
    next_page_url: null,
    prev_page_url: null,
})
const snackbarReg = ref(false)
const snackbarUpd = ref(false)
const timeout = 4000
const dialogEdit = ref(false)
const nombreRules = ref([(v) => !!v || 'El campo es requerido'])
const editando = ref(false);

const nuevaMarca = () => {
    editando.value = true;
    dialogEdit.value = true
    txtregdata.value.nombre = ''
    txtregdata.value.descripcion = ''
}

const registrar = async () => {
    await register(url + 'api/marcas', txtregdata.value, getMarcas);
    dialogEdit.value = false
    snackbarReg.value = true
    txtregdata.value.nombre = ''
    txtregdata.value.descripcion = ''
    editando.value = true;
}

const actualizar = async () => {
    await update(url + 'api/marcas/' + txtregdata.value.id, txtregdata.value, getMarcas);
    dialogEdit.value = false
    snackbarUpd.value = true
    txtregdata.value.nombre = ''
    txtregdata.value.descripcion = ''
    editando.value = false;
}

const selecMarca = async (id) => {
    editando.value = false;
    try {
        const res = await axiosInst.get(url + 'api/marcas/' + id)
        txtregdata.value = res.data
        console.log(res.data)
    } catch (err) {
        alert(err)
    }
}

const getMarcas = async (urls = url + 'api/marcas?page=1') => {
    try {
        const res = await axiosInst.get(urls)
        marcasList.value = res.data
    } catch (error) {

    }
};

const deleteMarca = async (id) => {
    let confirmac = confirm('Eliminar esta Marca?');
    if (confirmac) {
        const res = await axiosInst.delete(url + 'api/marcas/' + id);
        getMarcas()
    }
}


onMounted(() => {
    getMarcas()
})
</script>
<style lang="scss">
.registerBox {
    max-width: 1000px;
    margin: 0 auto;
}
</style>