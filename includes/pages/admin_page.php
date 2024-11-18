<?php
if (!defined('ABSPATH')) {
    exit;
}

function Pomar_core_documentation_page() {
    ?>
    <div class="wrap" style="display: flex; flex-direction: column; min-height: 95dvh;">
        <div class="content" style="flex: 1;">
            <h1>Documentación de Productos El Pomar</h1>
            <p>Bienvenido a la documentación del plugin Productos El Pomar. Aquí encontrarás toda la información necesaria para utilizar el plugin.</p>
            
            <h2>El Pomar</h2>
            <p>En esta sección puedes gestionar los productos de El Pomar. Puedes agregar, editar y eliminar productos, así como asignarles categorías.</p>
            
            <h2>Mulai</h2>
            <p>En esta sección puedes gestionar los productos de Mulai. Puedes agregar, editar y eliminar productos, así como asignarles categorías.</p>
            
            <h2>Levelma</h2>
            <p>En esta sección puedes gestionar los productos de Levelma. Puedes agregar, editar y eliminar productos, así como asignarles categorías.</p>
            
            <h2>Categorías</h2>
            <p>En cada sección de productos, puedes gestionar las categorías correspondientes. Puedes agregar, editar y eliminar categorías según sea necesario. Además, puedes asignar un icono SVG a cada categoría.</p>
            
            <h2>Beneficios</h2>
            <p>Al agregar o editar un producto, puedes asignar beneficios. Cada beneficio puede tener un icono y un texto descriptivo. Puedes agregar múltiples beneficios según sea necesario.</p>
            
            <h2>URL Tienda</h2>
            <p>Al agregar o editar un producto, puedes asignar una URL de tienda donde los usuarios pueden comprar el producto.</p>
            
            <h2>Shortcode</h2>
            <p>El plugin proporciona un shortcode que puedes usar para mostrar los productos en el frontend. El contenido se divide en tres tabs: "El Pomar", "Mulai" y "Levelma".</p>
            <p>Para usar el shortcode, simplemente agrega <code>[pomar_core]</code> en cualquier página o entrada donde quieras mostrar los productos.</p>
            <p>El contenido se mostrará de la siguiente manera:</p>
            <ul>
                <li>Izquierda: Categorías de productos en un acordeón. Dentro del acordeón, el nombre del post que corresponde a la categoría.</li>
                <li>Centro: Imagen destacada del producto seleccionado (carga por defecto el primer producto de la lista) y un botón con el link de la tienda.</li>
                <li>Derecha: Lista de beneficios del producto seleccionado.</li>
            </ul>
        </div>
        <div class="footer" style="text-align: center;display: flex;flex-direction: row;align-items: center;align-content: center;flex-wrap: wrap; gap:10px;">
                <hr style="width: 100%;">
                <p>Desarrollado por <a href="https://github.com/kerackdiaz" target="_blank" rel="nofollow"> Kerack Diaz </a> </p>
                <p>&copy; 2024 <a href="https://inclup.com/" target="_blank" rel="nofollow">Inclup S.A.S</a>. Todos los derechos reservados.</p>
            </div>
    </div>
    <?php
}