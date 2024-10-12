<template>
    <v-row class="bg-containerBg position-relative" no-gutters>

        <div class="text-center pa-4">
            <v-dialog width="600" v-model="dialogEdit">
                <v-card max-width="600">
                    <v-card-text class="pa-sm-10 pa-6">
                        <v-form @submit.prevent="registrar()" class="mt-1 loginForm">
                            <v-row>
                                <v-col cols="12" md="6">
                                    <v-select v-model="txtregdata.tipoidentificacion" :rules="nombreRules"
                                        label="Tipo de Identificación" variant="underlined" color="info" :items="[
                                            'NIT',
                                            'CÉDULA DE CIUDADANÍA',
                                            'CÉDULA DE EXTRANJERÍA',
                                            'NÚMERO DE IDENTIFICACIÓN DE EXTRANJERO',
                                            'OTRO'
                                        ]"></v-select>
                                </v-col>
                                <v-col cols="12" md="6">
                                    <v-text-field v-model="txtregdata.numidentificacion" required hide-details="auto"
                                        variant="underlined" color="info" label="No Identificación"></v-text-field>
                                </v-col>
                            </v-row>
                            <v-row>
                                <v-col cols="12" md="6">
                                    <v-text-field v-model="txtregdata.nombres" :rules="nombreRules" required
                                        hide-details="auto" variant="underlined" color="info" label="Nombres"
                                        @input="txtregdata.nombres = $event.target.value.toUpperCase()"></v-text-field>

                                </v-col>
                                <v-col cols="12" md="6">
                                    <v-text-field v-model="txtregdata.apellidos" hide-details="auto"
                                        variant="underlined" color="info" label="Apellidos"
                                        @input="txtregdata.apellidos = $event.target.value.toUpperCase()"></v-text-field>
                                </v-col>
                            </v-row>
                            <v-row>
                                <v-col cols="12" md="6">
                                    <v-text-field v-model="txtregdata.telefono" :rules="telefonoRules" required
                                        hide-details="auto" variant="underlined" color="info" label="Teléfono"
                                        @input="txtregdata.telefono = $event.target.value.replace(/\D/g, '')"></v-text-field>
                                </v-col>
                                <v-col cols="12" md="6">
                                    <v-text-field v-model="txtregdata.email" :rules="emailRules" required
                                        hide-details="auto" variant="underlined" color="info"
                                        label="Correo Electrónico"></v-text-field>
                                </v-col>
                            </v-row>
                            <v-row>
                                <v-col cols="12" md="6">
                                    <v-text-field v-model="txtregdata.ubicacion" 
                                        hide-details="auto" variant="underlined" color="info"
                                        label="Ubicación"></v-text-field>
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
    <v-btn @click="nuevaProveedor" icon="mdi mdi-plus" density="compact"></v-btn>
    <v-table>
        <thead>
            <tr>
                <th class="text-left">
                    Tipo de Identificación
                </th>
                <th class="text-left">
                    No Identificación
                </th>
                <th class="text-left">
                    Nombres
                </th>
                <th class="text-left">
                    Apellidos
                </th>
                <th class="text-left">
                    Telefono
                </th>
                <th class="text-left">
                    Correo
                </th>
                <th class="text-left">
                    Ubicación
                </th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="pro in proveedoresList.data" :key="pro.id">
                <td>{{ pro.tipoidentificacion }}</td>
                <td>{{ pro.numidentificacion }}</td>
                <td>{{ pro.nombres }}</td>
                <td>{{ pro.apellidos }}</td>
                <td>{{ pro.telefono }}</td>
                <td>{{ pro.email }}</td>
                <td>{{ pro.ubicacion }}</td>
                <td>
                    <v-btn color="dark" @click="selecProveedor(pro.id), dialogEdit = true" density="comfortable"
                        icon="mdi mdi-square-edit-outline" title="Editar"></v-btn>
                    <v-btn color="dark" @click="deleteProveedor(pro.id)" density="comfortable"
                        icon="mdi mdi-delete-forever" title="Eliminar"></v-btn>
                </td>
            </tr>
        </tbody>
    </v-table>
    <br>
    <div class="text-center">
        <v-btn @click="getProveedors(proveedoresList.prev_page_url)" :disabled="!proveedoresList.prev_page_url"
            icon="mdi mdi-chevron-left" density="comfortable">
        </v-btn>
        <span>&nbsp;Página {{ proveedoresList.current_page }} / {{ proveedoresList.last_page }}&nbsp;</span>
        <v-btn @click="getProveedors(proveedoresList.next_page_url)" :disabled="!proveedoresList.next_page_url"
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

const { register, regsuccessmsg, regerrormsg } = useRegister();
const { update, upderrormsg, updsuccessmsg } = useUpdate();
const url = import.meta.env.VITE_APP_API_URL

const txtregdata = ref({
    id: '', tipoidentificacion: '', numidentificacion: '', nombres: '', apellidos: '', telefono: '', email: '', ubicacion: '',
})
const proveedoresList = ref({
    current_page: 1,
    data: [],
    last_page: 1,
    next_page_url: null,
    prev_page_url: null,
});
const nombreRules = ref([(v) => !!v || 'El campo es requerido'])
const emailRules = ref([(v) => !!v || 'El campo es requerido', (v) => /.+@.+\..+/.test(v) || 'E-mail debe ser valido']);
const snackbarReg = ref(false)
const snackbarUpd = ref(false)
const timeout = 4000
const dialogEdit = ref(false)
const editando = ref(false);


const nuevaProveedor = () => {
    editando.value = true;
    dialogEdit.value = true
    for (const key in txtregdata.value) {
        txtregdata.value[key] = ''
    }
}

const registrar = async () => {
    await register(url + 'api/proveedores', txtregdata.value, getProveedores);
    dialogEdit.value = false
    snackbarReg.value = true
    editando.value = true;
    for (const key in txtregdata.value) {
        txtregdata.value[key] = ''
    }

}

const actualizar = async () => {
    await update(url + 'api/proveedores/' + txtregdata.value.id, txtregdata.value, getProveedores);
    dialogEdit.value = false
    snackbarUpd.value = true
    editando.value = false;
    for (const key in txtregdata.value) {
        txtregdata.value[key] = ''
    }

}

const selecProveedor = async (id) => {
    editando.value = false;
    try {
        const res = await axiosInst.get(url + 'api/proveedores/' + id)
        txtregdata.value = res.data
        console.log(res.data)
    } catch (err) {
        alert(err)
    }
}

const getProveedores = async (urls = url + 'api/proveedores?page=1') => {
    try {
        const res = await axiosInst.get(urls)
        proveedoresList.value = res.data
    } catch (error) {

    }
};

const deleteProveedor = async (id) => {
    let confirmac = confirm('Eliminar esta Proveedor?');
    if (confirmac) {
        const res = await axiosInst.delete(url + 'api/proveedores/' + id);
        getProveedores()
    }
}

onMounted(() => {
    getProveedores()
})
</script>
<style lang="scss">
.registerBox {
    max-width: 1000px;
    margin: 0 auto;
}
</style>