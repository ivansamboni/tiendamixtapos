<div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2 mb-2 d-flex">
    <div class="product-item men d-flex flex-column w-100 h-100">
        <div class="product flex-grow-1 d-flex flex-column">
            <div class="flex-grow-1 d-flex flex-column align-items-center"><br>
                <a href="{{ route('producto.productodetalle', ['id' => $pro['id'], 'slug' => $pro['slug']]) }}">
                    <img src="{{ asset('archivos/folder_img_product/' . ($pro['img1'] ?? 'sinimagen.jpg')) }}"
                        alt="{{ $pro['nombre'] }}" style="height: 100px; object-fit: cover;">
                </a>
            </div>

            <div class="product-body">
                <h3 class="product-name">
                    <a href="{{ route('producto.productodetalle', ['id' => $pro['id'], 'slug' => $pro['slug']]) }}">
                        {{ $pro['nombre'] }}
                    </a>
                </h3>
                <h4 class="product-price">${{ number_format($pro['precio']) }}</h4>
                <button class="myCar w-100 mt-auto" data-id="{{ $pro['id'] }}"
                    data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight"
                    data-nombre="{{ $pro['nombre'] }}" data-precio="{{ $pro['precio'] }}"
                    data-img1="{{ asset('archivos/folder_img_product/' . ($pro['img1'] ?? 'sinimagen.jpg')) }}"
                    onclick="capturarDatos(this)">
                    <i class="fa fa-shopping-cart"></i> Agregar
                </button>
            </div>
        </div>


    </div>
</div>
