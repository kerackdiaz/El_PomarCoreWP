document.addEventListener('DOMContentLoaded', function() {
    function activateTab(tabId) {
        document.querySelectorAll('.tab').forEach(function(link) {
            link.classList.remove('active');
        });

        document.querySelectorAll('.tab-content').forEach(function(content) {
            content.classList.remove('current');
        });

        var tabLink = document.querySelector('.tab[data-tab="' + tabId + '"]');
        var tabContent = document.getElementById(tabId);

        if (tabLink && tabContent) {
            tabLink.classList.add('active');
            tabContent.classList.add('current');

            // Cargar detalles del primer producto por defecto para la nueva tab activa
            var firstProductLink = tabContent.querySelector('.product-link');
            if (firstProductLink) {
                firstProductLink.click();
            }
        }
    }

    document.querySelectorAll('.tab').forEach(function(tabLink) {
        tabLink.addEventListener('click', function(e) {
            e.preventDefault();
            var tabId = this.getAttribute('data-tab');
            activateTab(tabId);
        });
    });

    document.querySelectorAll('.accordion-header').forEach(function(header) {
        header.addEventListener('click', function() {
            var content = this.nextElementSibling;
            var toggleIcon = this.querySelector('.toggle-icon');
            if (content.style.display === 'block') {
                content.style.display = 'none';
                toggleIcon.src = pomar_core.plugin_url + 'assets/img/plus.svg';
            } else {
                content.style.display = 'block';
                toggleIcon.src = pomar_core.plugin_url + 'assets/img/minus.svg';
            }
        });
    });

    function loadProductDetails(postId, tabId) {
        var request = new XMLHttpRequest();
        request.open('GET', pomar_core.ajax_url + '?action=load_product_details&post_id=' + postId, true);
        request.onload = function() {
            if (this.status >= 200 && this.status < 400) {
                var data = JSON.parse(this.response);
                var tabContent = document.getElementById(tabId);
                tabContent.querySelector('.product-image img').src = data.image;
                tabContent.querySelector('.buy-button').href = data.url;
                tabContent.querySelector('#product-benefits').innerHTML = data.benefits;
                tabContent.querySelector('#product-name').textContent = data.name; // Mostrar el nombre del post
            }
        };
        request.send();
    }

    document.querySelectorAll('.product-link').forEach(function(link) {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            var postId = this.getAttribute('data-post-id');
            var tabId = this.closest('.tab-content').id;
            loadProductDetails(postId, tabId);
        });
    });

    // Activar el tab basado en el hash de la URL
    var hash = window.location.hash;
    if (hash) {
        var tabId = hash.replace('#', 'tab-');
        activateTab(tabId);
    } else {
        // Cargar detalles del primer producto por defecto para la tab activa por defecto
        var firstProductLink = document.querySelector('#tab-El_Pomar .product-link.active');
        if (firstProductLink) {
            firstProductLink.click();
        }
    }
});