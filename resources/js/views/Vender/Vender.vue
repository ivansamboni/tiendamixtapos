<template>
    <v-row class="bg-containerBg position-relative" no-gutters>

        <v-card max-width="auto" class="text-center pa-4">
            <v-form @submit.prevent="searchCodigo()" class="mt-1 loginForm">
                <v-row>
                    <v-col cols="12" md="2">
                        <v-text-field ref="codigoBarrasInput" v-model="txtregdata.codigo_barras" hide-details="auto"
                            variant="underlined" color="info" label="Codigo"></v-text-field>
                    </v-col>
                    <v-col cols="12" md="2">
                        <v-combobox v-model="selectedId" :items="productResults" item-title="nombre" item-value="id"
                            variant="underlined" v-model:search="nomBuscar" label="Buscar"
                            hide-details="auto"></v-combobox>
                    </v-col>

                    <v-col cols="12" md="2">
                        <v-btn color="success" type="submit">Agregar</v-btn>
                    </v-col>
                </v-row>
            </v-form>
            <br>
            <v-col cols="12" md="10">
                               <h3>{{ txtregdata.nombre }}, Precio {{ parseFloat(txtregdata.precio).toLocaleString('es-ES') }},Stock: {{ txtregdata.stock }}</h3>
            </v-col>
        </v-card>

        <v-col cols="12" md="6">

            <v-table>
                <thead>
                    <tr>
                        <th class="text-left">
                            Name
                        </th>
                        <th class="text-left">
                            Cantidad
                        </th>
                        <th class="text-left">
                            +/-
                        </th>
                        <th class="text-left">
                            Precio
                        </th>
                        <th class="text-left">
                            Eliminar
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="item in itemsVenta" :key="item.id">
                        <td>{{ item.nombre }}</td>
                        <td>{{ item.cantidad }}</td>
                        <td><v-btn color="dark" @click="addcantItem(item.id)" density="compact" icon="mdi mdi-plus"
                                title="aumentar"></v-btn>
                            <v-btn color="dark" @click="restcantItem(item.id)" density="compact" icon="mdi mdi-minus"
                                title="restar"></v-btn>
                        </td>
                        <td>{{ parseFloat(item.precio).toLocaleString('es-ES') }}</td>
                        <td><v-btn color="dark" @click="deleteItem(item.id)" density="comfortable"
                                icon="mdi mdi-delete-forever" title="Eliminar"></v-btn></td>
                    </tr>
                </tbody>
            </v-table>

        </v-col>
        <v-col cols="12" md="6">
            <v-card class="mx-auto" color="primary" max-width="340" title="Total a Pagar:">
                <h2 class="text-center"><span class="mdi mdi-currency-usd"></span> {{
                    parseFloat(total).toLocaleString('es-ES') }}</h2>
                <template v-slot:actions>
                    <v-btn append-icon="mdi-chevron-right" color="red-lighten-2" text="Finalizar Venta"
                        variant="outlined" block></v-btn>
                </template>
            </v-card>
        </v-col>
    </v-row>


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
import { ref, onMounted, computed, watch } from 'vue'
import axiosInst from '@/components/axiosins'
import { useAuthStore } from '@/stores/auth';
const url = import.meta.env.VITE_APP_API_URL
const authStore = useAuthStore();
const txtregdata = ref({
    cajero_id: authStore.user.user.id,
    id: '',
    nombre: '',
    precio: '',
    cantidad: 1,
    stock: '',
    codigo_barras: '',
    img1: '',
})
const nomBuscar = ref('')
const selectedId = ref('')
const codigoBarrasInput = ref(null);
const productResults = ref([])
const itemsVenta = ref([])

const total = computed(() => {
    return itemsVenta.value.reduce((acumulado, producto) => {
        return acumulado + (producto.precio * producto.cantidad);

    }, 0);

});


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
        const res = await axiosInst.get('http://127.0.0.1:8000/api/searchcodigoproducto/' + txtregdata.value.codigo_barras);

        const productoExistente = itemsVenta.value.find(item => item.id === res.data.id || item.codigo_barras === txtregdata.value.codigo_barras);
        const stockdisponible = res.data.stock;
        if(stockdisponible===0){
            alert('Producto agotado')
        }
        if (productoExistente) {
            productoExistente.cantidad += txtregdata.value.cantidad;
        } else {
            txtregdata.value.id = res.data.id;
            txtregdata.value.nombre = res.data.nombre;
            txtregdata.value.precio = res.data.precio;
            txtregdata.value.stock = res.data.stock;
            itemsVenta.value.push({ ...txtregdata.value });
        }

        txtregdata.value.cantidad = 1;
        txtregdata.value.codigo_barras = '';
        selectedId.value = ''
        productResults.value = ''
        codigoBarrasInput.value.focus();
    } catch (error) {
        alert('codigo no encontrado')
        console.error(error);
    }
};

const deleteItem = (id) => {
    const index = itemsVenta.value.findIndex(item => item.id === id);

    if (index !== -1) {
        itemsVenta.value.splice(index, 1);
    }
    codigoBarrasInput.value.focus();
};

const addcantItem = (id) => {
    const index = itemsVenta.value.findIndex(item => item.id === id);
    if (index !== -1) {
        itemsVenta.value[index].cantidad++;
    }
    codigoBarrasInput.value.focus();
};
const restcantItem = (id) => {
    const index = itemsVenta.value.findIndex(item => item.id === id);
    if (index !== -1) {
        if (itemsVenta.value[index].cantidad > 1) {
            itemsVenta.value[index].cantidad--;
        }
    }
    codigoBarrasInput.value.focus();
};

onMounted(() => {
    codigoBarrasInput.value.focus();
})

watch(nomBuscar, () => {
    if (nomBuscar.value.length >= 4) {
        searchProducto();
    }
});

watch(selectedId, (newVal) => {
    if (newVal) {
        txtregdata.value.codigo_barras = newVal.id;
    }
});


</script>
<style lang="scss">
.registerBox {
    max-width: 1000px;
    margin: 0 auto;
}
</style>