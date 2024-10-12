<template>
    <!--modalfotoprevia-->
    <div class="text-center pa-4">
        <v-dialog v-model="dialog2" width="auto">
            <v-card max-width="600">
                <img :src="url + 'archivos/folder_img_product/' + imgview" width="500">

                <template v-slot:actions>
                    <v-btn class="ms-auto" text="Cerrar" @click="dialog2 = false"></v-btn>
                </template>
            </v-card>
        </v-dialog>
    </div>
    <!--modalregistroedicionproductos-->
    <div class="text-center pa-1">

        <v-dialog v-model="dialogEdit">
            <v-card max-width="auto">
                <v-form class="mt-5 loginForm">
                    <v-container>
                        <h3 class="text-primary text-center">Registro de Productos</h3>
                        <br />
                        <br />
                        <v-row>
                            <v-col cols="12" md="3">
                                <v-text-field v-model="txtregdata.nombre" :rules="nombreRules" required
                                    hide-details="auto" variant="underlined" color="info"
                                    label="Nombre del Producto"></v-text-field>
                            </v-col>

                            <v-col cols="12" md="3">
                                <v-autocomplete v-model="txtregdata.marca_id" :items="marcasList" item-title="nombre"
                                    item-value="id" variant="underlined" color="info" label="Marca">
                                    <template v-slot:item="{ props }">
                                        <v-list-item v-bind="props"></v-list-item>
                                    </template>
                                </v-autocomplete>
                            </v-col>
                            <v-col cols="12" md="3">
                                <v-autocomplete v-model="txtregdata.categoria_id" :items="categoriasList"
                                    item-title="nombre" item-value="id" variant="underlined" color="info"
                                    label="Categoria">
                                    <template v-slot:item="{ props }">
                                        <v-list-item v-bind="props"></v-list-item>
                                    </template>
                                </v-autocomplete>
                            </v-col>
                            <v-col cols="12" md="3">
                                <v-text-field v-model="txtregdata.precio" required :rules="nombreRules" type="text"
                                    hide-details="auto" variant="underlined" color="info" label="Precio"
                                    @input="formatPrecio()" :type="''"></v-text-field>
                                {{ precio }}
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col cols="12" md="3">
                                <v-text-field v-model="txtregdata.stock" required :rules="nombreRules"
                                    hide-details="auto" variant="underlined" color="info" label="Stock"></v-text-field>
                            </v-col>
                            <v-col cols="12" md="3">
                                <v-autocomplete v-model="txtregdata.proveedor_id" :items="proveedoresList"
                                    item-title="nombres" item-value="id" variant="underlined" color="info"
                                    label="Proveedor">
                                    <template v-slot:item="{ props }">
                                        <v-list-item v-bind="props"></v-list-item>
                                    </template>
                                </v-autocomplete>
                            </v-col>
                            <v-col cols="12" md="3">
                                <v-text-field v-model="txtregdata.codigo_barras" hide-details="auto"
                                    variant="underlined" color="info" label="Codigo de Barras"></v-text-field>
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col cols="12" md="3">
                                <img :src="url + 'archivos/folder_img_product/' + txtregdata.img1 || img1" width="80"
                                    height="80" name="img">
                                <v-file-input v-model="txtregdata.img1" prepend-icon="mdi-camera"></v-file-input>
                            </v-col>
                            <v-col cols="12" md="3">
                                <img :src="url + 'archivos/folder_img_product/' + txtregdata.img2" width="80"
                                    height="80">
                                <v-file-input v-model="txtregdata.img2" prepend-icon="mdi-camera"></v-file-input>
                            </v-col>
                            <v-col cols="12" md="3">
                                <img :src="url + 'archivos/folder_img_product/' + txtregdata.img3" width="80"
                                    height="80">
                                <v-file-input v-model="txtregdata.img3" prepend-icon="mdi-camera"></v-file-input>
                            </v-col>
                            <v-col cols="12" md="3">
                                <img :src="url + 'archivos/folder_img_product/' + txtregdata.img4" width="80"
                                    height="80">
                                <v-file-input v-model="txtregdata.img4" prepend-icon="mdi-camera"></v-file-input>
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col cols="12" md="12">

                                <v-textarea v-model="txtregdata.descripcion" label="Descripción" row-height="30"
                                    rows="12" variant="outlined" auto-grow shaped></v-textarea>
                            </v-col>
                        </v-row>

                    </v-container>
                    <v-card-actions>
                        <v-btn class="ms-auto" text="Cerrar" @click="dialogEdit = false"></v-btn>
                        <v-btn @click="register()" v-if="editando" color="success"><i
                                class="bi bi-person-plus-fill"></i>
                            Agregar</v-btn>
                        <v-btn v-if="!editando" color="success" @click="updateProducto">Actualizar</v-btn>
                    </v-card-actions>
                </v-form>
            </v-card>
        </v-dialog>
    </div>

    <v-card max-width="auto" class="text-center pa-4">
        <v-form @submit.prevent="searchCodigo()" class="mt-1 loginForm">
            <v-row>
                <v-col cols="12" md="2">
                    <v-text-field ref="codigoBarrasInput" v-model="txtregdata.codigo_barras" hide-details="auto"
                        variant="underlined" color="info" label="Codigo"></v-text-field>
                </v-col>
                <v-col cols="12" md="2">
                    <v-combobox v-model="selectedId" :items="productResults" item-title="nombre" item-value="id"
                        variant="underlined" v-model:search="nomBuscar" label="Buscar" hide-details="auto"></v-combobox>
                </v-col>

                <v-col cols="12" md="2">
                    <v-btn color="success" type="submit">Buscar</v-btn>
                </v-col>
                <v-col cols="12" md="2">
                    <v-btn @click="nuevoProducto" icon="mdi mdi-plus" density="default" title="Nuevo producto">
                    </v-btn>
                </v-col>
            </v-row>
        </v-form>
        <br>
    </v-card>

    <v-table>
        <thead>
            <tr>
                <th class="text-left">
                    Detalle
                </th>
                <th class="text-left">
                    Producto
                </th>
                <th class="text-left">
                    codigo
                </th>

                <th class="text-left">
                    Marca
                </th>
                <th class="text-left">
                    Categoria
                </th>
                <th class="text-left">
                    Precio
                </th>
                <th class="text-left">
                    Stock
                </th>
                <th class="text-left">
                    Acciones
                </th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="pro in productosList.data" :key="pro.id">

                <td><a class="clickable-element" @click="dialog2 = true, imgview = pro.img1"><img
                            :src="pro.img1 ? url + 'archivos/folder_img_product/' + pro.img1 : url + 'archivos/folder_img_product/sinimagen.jpg'"
                            width="80" height="80" /></a></td>
                <td>{{ pro.nombre }}</td>
                <td>{{ pro.codigo_barras }}</td>
                <td>{{ pro.marca?.nombre || '' }}</td>
                <td>{{ pro.categoria?.nombre || '' }}</td>
                <td>{{ parseFloat(pro.precio).toLocaleString('es-ES') }}</td>
                <td>{{ pro.stock }}</td>
                <!--<td>{{ pro.proveedor?.nombres || '' }}</td>-->
                <td>
                    <v-btn color="dark" v-bind="activatorProps" density="comfortable" icon="mdi mdi-square-edit-outline"
                        title="Editar" @click="selecProducto(pro.id), dialogEdit = true"></v-btn>
                    <v-btn color="dark" @click="deleteProducto(pro.id)" density="comfortable"
                        icon="mdi mdi-delete-forever" title="Eliminar"></v-btn>
                </td>
            </tr>
        </tbody>
    </v-table><br>
    <div class="text-center">
        <v-btn @click="getProductos(productosList.prev_page_url)" :disabled="!productosList.prev_page_url"
            icon="mdi mdi-chevron-left" density="comfortable">
        </v-btn>
        <span>&nbsp;Página {{ productosList.current_page }} / {{ productosList.last_page }}&nbsp;</span>
        <v-btn @click="getProductos(productosList.next_page_url)" :disabled="!productosList.next_page_url"
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
import { ref, onMounted, watch } from 'vue'
import axiosInst from '@/components/axiosins'
const url = import.meta.env.VITE_APP_API_URL

const txtregdata = ref({
    id: '',
    nombre: '',
    descripcion: '',
    marca_id: '',
    categoria_id: '',
    precio: '',
    stock: '',
    proveedor_id: '',
    codigo_barras: '',
    img1: '',
    img2: '',
    img3: '',
    img4: '',
})
const nomBuscar = ref('')
const selectedId = ref('')
const codigoBarrasInput = ref(null);
const productResults = ref([])
const marcasList = ref([])
const proveedoresList = ref([])
const categoriasList = ref([])
const productosList = ref({
    current_page: 1,
    data: [],
    last_page: 1,
    next_page_url: null,
    prev_page_url: null,
})

const nombreRules = ref([(v) => !!v || 'El campo es requerido'])
const dialog2 = ref(false)
const imgview = ref('')
const snackbarReg = ref(false)
const snackbarUpd = ref(false)
const timeout = 4000
const dialogEdit = ref(false)
const editando = ref(false);
const regerrormsg = ref('')
const precio = ref('')
const regsuccessmsg = ref('')
const upderrormsg = ref('')
const updsuccessmsg = ref('')


const nuevoProducto = () => {
    editando.value = true;
    dialogEdit.value = true
    for (const key in txtregdata.value) {
        txtregdata.value[key] = ''
    }
    precio.value = ''
}

const formatPrecio = () => {
    let fprecio = txtregdata.value.precio.replace(/\D/g, '');
    if (fprecio) {
        fprecio = parseFloat(fprecio).toLocaleString('es-ES');
    }
    //precio.value = fprecio;
    txtregdata.value.precio = txtregdata.value.precio.replace(/\D/g, '')
};

const register = async () => {
    const formData = new FormData();
    formData.append('nombre', txtregdata.value.nombre);
    formData.append('descripcion', txtregdata.value.descripcion);
    formData.append('marca_id', txtregdata.value.marca_id);
    formData.append('categoria_id', txtregdata.value.categoria_id);
    formData.append('precio', txtregdata.value.precio);
    formData.append('stock', txtregdata.value.stock);
    formData.append('proveedor_id', txtregdata.value.proveedor_id);
    formData.append('codigo_barras', txtregdata.value.codigo_barras);
    if (txtregdata.value.img1) formData.append('img1', txtregdata.value.img1);
    if (txtregdata.value.img2) formData.append('img2', txtregdata.value.img2);
    if (txtregdata.value.img3) formData.append('img3', txtregdata.value.img3);
    if (txtregdata.value.img4) formData.append('img4', txtregdata.value.img4);
    try {
        const res = await axiosInst.post(url + 'api/productos', formData, {
            headers: { 'Content-Type': 'multipart/form-data' }
        })
        regsuccessmsg.value = 'Registro éxitoso'
        regerrormsg.value = ''
        for (const key in txtregdata.value) {
            txtregdata.value[key] = '';
        }
        dialogEdit.value = false
        getProductos()
    } catch (error) {
        regsuccessmsg.value = ''
        regerrormsg.value = error.response.data.message

    }
    snackbarReg.value = true
    editando.value = true;
}

const getProductos = async (urls = url + 'api/productos?page=1') => {
    try {
        const res = await axiosInst.get(urls)
        productosList.value = res.data
    } catch (error) {

    }
};

const dataMarcas = async () => {
    const res = await axiosInst.get(url + "api/marcaslist")
    marcasList.value = res.data
}
const dataProveedores = async () => {
    const res = await axiosInst.get(url + "api/proveedoreslist")
    proveedoresList.value = res.data
}
const dataCategorias = async () => {
    const res = await axiosInst.get(url + "api/categoriaslist")
    categoriasList.value = res.data
}

const selecProducto = async (id) => {
    try {
        const res = await axiosInst.get(url + 'api/productos/' + id)
        txtregdata.value = res.data

    } catch (err) {
        alert(err)
    }

    editando.value = false;
}

const updateProducto = async () => {
    const formData = new FormData();
    formData.append('id', txtregdata.value.id)
    formData.append('img1', txtregdata.value.img1)
    formData.append('img2', txtregdata.value.img2)
    formData.append('img3', txtregdata.value.img3)
    formData.append('img4', txtregdata.value.img4)

    try {

        const res = await axiosInst.put(url + 'api/productos/' + txtregdata.value.id, txtregdata.value)

        const resimg = await axiosInst.post(url + 'api/productos-loadimg', formData, {
            headers: { 'Content-Type': 'multipart/form-data' }
        })
        regsuccessmsg.value = 'Se actualizó con éxito'
        regerrormsg.value = ''
        for (const key in txtregdata.value) {
            txtregdata.value[key] = '';
        }
        dialogEdit.value = false
        getProductos()
    } catch (error) {
        regsuccessmsg.value = ''
        regerrormsg.value = error.response.data.message
    }
    snackbarReg.value = true
};

const deleteProducto = async (id) => {
    let confirmac = confirm('Eliminar este producto?')
    if (confirmac) {
        const res = await axiosInst.delete(url + 'api/productos/' + id)
        getProductos()
    }
}

const searchProducto = async () => {

    try {
        const res = await axiosInst.get('http://127.0.0.1:8000/api/searchnomproducto/' + nomBuscar.value)
        productResults.value = res.data.map(producto => ({
            id: producto.id,
            nombre: producto.nombre
        }));

    } catch (error) {
    }
}

const searchCodigo = async () => {
    try {
        const res = await axiosInst.get('http://127.0.0.1:8000/api/searchCodigoPaginate/' + txtregdata.value.codigo_barras);
        productosList.value = res.data
        txtregdata.value.codigo_barras = '';
        selectedId.value = ''
        productResults.value = ''
        codigoBarrasInput.value.focus();
    } catch (error) {
        alert('codigo no encontrado')
        console.error(error);
    }
};

onMounted(() => {
    dataCategorias(), getProductos(), dataProveedores(), dataMarcas()
})

watch(nomBuscar, () => {
    if (nomBuscar.value.length >= 4) {
        searchProducto();
    }
});

watch(selectedId, (newVal) => {
    if (newVal) {
        txtregdata.value.codigo_barras = newVal.id;
        if (txtregdata.value.codigo_barras >= 1) {
            searchCodigo();

        }
    }

});

</script>
<style lang="scss">
.registerBox {
    max-width: 1000px;
    margin: 0 auto;
}

.clickable-element {
    cursor: pointer;
}
</style>