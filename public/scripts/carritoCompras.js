


function capturarDatos(button) {
    const spamcantidad = document.getElementById('spamcantidad');
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
        contenedor.className = 'producto-seleccionado d-flex align-items-center gap-3 mb-2';

        const img = document.createElement('img');
        img.src = imgSrc;
        img.alt = nombre;
        img.style.height = '50px';
        img.style.objectFit = 'cover';

        const titulo = document.createElement('small');
        titulo.textContent = nombre;

        const costo = document.createElement('small');
        costo.textContent = `Precio: $${precio}`;
        costo.className = 'text-success';

        // Incrementar cantidad
        let cantidadActual = parseInt(spamcantidad.innerHTML) || 0;
        spamcantidad.innerHTML = ++cantidadActual;

        // Añadir los elementos al contenedor
        contenedor.appendChild(img);
        contenedor.appendChild(titulo);
        contenedor.appendChild(costo);

        // Agregar los elementos al formulario
        formulario.appendChild(input);
        formulario.appendChild(contenedor);
    } else {
        alert('Este producto ya está en el carrito.');
    }
}
window.onload = function () {
    // Obtener el carrito desde localStorage y asegurarse de que no sea null
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
        contenedor.className = 'producto-seleccionado d-flex align-items-center gap-3 mb-2';

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
        costo.textContent = `Precio: $${item.precio}`;
        costo.className = 'text-success';

        // Agregar los elementos al contenedor
        contenedor.appendChild(img);
        contenedor.appendChild(titulo);
        contenedor.appendChild(costo);

        // Agregar el input y el contenedor al formulario
        formulario.appendChild(input);
        formulario.appendChild(contenedor);
    });

    // Mostrar la cantidad de productos seleccionados
    const spamcantidad = document.getElementById('spamcantidad');
    spamcantidad.innerHTML = carrito.length;
};
// Opcional: Función para enviar los datos a un servidor o procesarlos
function vaciarcarro() {
    localStorage.removeItem('carrito');
    location.reload() 
}
