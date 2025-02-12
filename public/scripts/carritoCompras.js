window.onload = function () {
    loadDataStorage();
    updateTotal();
};



//carga los datos del navegador del local storage

function loadDataStorage() {
    const spamcantidad = document.getElementById('spamcantidad');
    const carrito = JSON.parse(localStorage.getItem('carrito'));

    const formulario = document.getElementById('carritoForm');

    carrito.forEach(item => {
        // Crear un nuevo input oculto para cada producto
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'productos[][id]';
        input.value = item.id;

        // Crear el contenedor del producto
        const contenedor = document.createElement('div');
        contenedor.className = 'product-group producto-seleccionado d-flex align-items-center gap-3 mb-2';
        contenedor.id = `productcar-${input.value}`;
        // Crear la imagen del producto
        const img = document.createElement('img');
        img.src = item.img;
        img.alt = item.nombre;
        img.style.height = '50px';
        img.style.objectFit = 'cover';

        // Crear el título (nombre del producto)
        const titulo = document.createElement('small');
        titulo.textContent = item.nombre;

        // Crear el precio
        const costo = document.createElement('small');
        costo.textContent = `$${parseFloat(item.precio).toLocaleString('es-ES')}`;
        costo.className = 'text-success';

        const quitar = document.createElement('button');
        quitar.textContent = 'x';
        quitar.className = 'btn btn-dark btn-sm';
        quitar.type = 'button';
        const idpro = input.value;
        quitar.addEventListener('click', () => {
            removeItem(idpro); // Llama a la función y pasa el parámetro
        });

        // Agregar los elementos al contenedor
        contenedor.appendChild(img);
        contenedor.appendChild(titulo);
        contenedor.appendChild(costo);
        contenedor.appendChild(input);
        contenedor.appendChild(quitar);
        // Agregar el input y el contenedor al formulario
       
        formulario.appendChild(contenedor);
    });

    // Mostrar la cantidad de productos seleccionados
   
    spamcantidad.innerHTML = carrito.length;

}

//elimina los items del carrito de compras de la pagina
function removeItem(idp) {
    // Eliminar el producto del DOM

    const productCar = document.getElementById(`productcar-${idp}`);  
    // Verificar y eliminar cada elemento por separado
    if (productCar) productCar.remove();
  
    // Eliminar el producto del localStorage
    let productos = JSON.parse(localStorage.getItem('carrito')) || [];
    productos = productos.filter(producto => producto.id !== idp.toString()); // Filtrar el producto por ID
    localStorage.setItem('carrito', JSON.stringify(productos)); // Guardar el carrito actualizado
    spamcantidad.innerHTML = productos.length;   
}

//elimina los items del carrito de compras
function removeProduct(productId,idp) {
    // Eliminar el producto del DOM
    const productGroup = document.getElementById(`product-${productId}`);
    const productCar = document.getElementById(`productcar-${idp}`);
    
    // Verificar y eliminar cada elemento por separado
    if (productGroup) productGroup.remove();
    if (productCar) productCar.remove();

    // Eliminar el producto del localStorage
    let productos = JSON.parse(localStorage.getItem('carrito')) || [];
    productos = productos.filter(producto => producto.id !== idp.toString()); // Filtrar el producto por ID
    localStorage.setItem('carrito', JSON.stringify(productos)); // Guardar el carrito actualizado

    // Actualizar la cantidad en el contador
   
    spamcantidad.innerHTML = --productos.length;    
    // Actualizar el total
    updateTotal();
}

// agrega productos al carrito
function capturarDatos(button) {

    const id = button.getAttribute('data-id');
    const nombre = button.getAttribute('data-nombre');
    const precio = button.getAttribute('data-precio');
    const img1 = button.getAttribute('data-img1');
    const imgSrc = button.getAttribute('data-img1');
    const formulario = document.getElementById('carritoForm');

    // Verificar si ya existe el producto en el localStorage
    let productos = JSON.parse(localStorage.getItem('carrito')) || [];

    // Comprobar si el producto ya está en el carrito
    const existe = productos.find(p => p.id === id);

    if (!existe) {
        // Crear un nuevo objeto para el producto
        const nuevoProducto = { id, nombre, precio, img: imgSrc };

        // Agregar el nuevo producto al array de productos
        productos.push(nuevoProducto);

        // Guardar el array actualizado en localStorage
        localStorage.setItem('carrito', JSON.stringify(productos));

        // Crear los elementos visuales en el DOM
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'productos[][id]';
        input.value = id;

        const contenedor = document.createElement('div');
        contenedor.className = 'product-group producto-seleccionado d-flex align-items-center gap-3 mb-2';
        contenedor.id = `productcar-${input.value}`;

        const img = document.createElement('img');
        img.src = imgSrc;
        img.alt = nombre;
        img.style.height = '50px';
        img.style.objectFit = 'cover';

        const titulo = document.createElement('small');
        titulo.textContent = nombre;

        const costo = document.createElement('small');
        costo.textContent = `$${parseFloat(precio).toLocaleString('es-ES')}`;
        costo.className = 'text-success';

        const quitar = document.createElement('button');
        quitar.textContent = 'x';
        quitar.className = 'btn btn-dark btn-sm';
        quitar.type = 'button';
        const idpro = input.value;
        quitar.addEventListener('click', () => {
            removeItem(idpro); // Llama a la función y pasa el parámetro
        });

        // Incrementar cantidad
        let cantidadActual = parseInt(spamcantidad.innerHTML) || 0;
        spamcantidad.innerHTML = ++cantidadActual;

        // Añadir los elementos al contenedor
        contenedor.appendChild(img);
        contenedor.appendChild(titulo);
        contenedor.appendChild(costo);
        contenedor.appendChild(quitar);

        // Agregar los elementos al formulario
        formulario.appendChild(input);
        formulario.appendChild(contenedor);
    }
    updateTotal();
}


function updateTotal() {
    let total = 0;
    const prices = document.querySelectorAll('input[name$="[precio]"]');
    prices.forEach(priceInput => {
        const cantidadInput = priceInput.closest('.row').querySelector('input[name$="[cantidad]"]');
        const precio = parseFloat(priceInput.value);
        const cantidad = parseInt(cantidadInput.value) || 0;
        total += precio * cantidad;
    });
    document.getElementById('total').textContent = `$${parseFloat(total).toLocaleString('es-ES')}`;
}

//elimina todos los productos del carrito
function vaciarcarro() {
    localStorage.removeItem('carrito');
    location.reload()
}


function changeImage(src) {
    document.getElementById('mainImage').src = src;
}

function ampliarImage(src) {
    document.getElementById('modalImage').src = src;
}