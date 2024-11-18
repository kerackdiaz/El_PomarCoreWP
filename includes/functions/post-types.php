<?php
if (!defined('ABSPATH')) {
    exit;
}

function Pomar_core_register_post_types() {
    // Registrar tipo de post para El Pomar
    register_post_type('el_pomar', array(
        'labels' => array(
            'name' => __('El Pomar'),
            'singular_name' => __('El Pomar')
        ),
        'public' => true,
        'has_archive' => true,
        'supports' => array('title', 'thumbnail', 'editor'),
        'taxonomies' => array('el_pomar_category'),
        'show_in_menu' => false, 
    ));

    // Registrar tipo de post para Mulai
    register_post_type('mulai', array(
        'labels' => array(
            'name' => __('Mulai'),
            'singular_name' => __('Mulai')
        ),
        'public' => true,
        'has_archive' => true,
        'supports' => array('title', 'thumbnail', 'editor'),
        'taxonomies' => array('mulai_category'),
        'show_in_menu' => false, 
    ));

    // Registrar tipo de post para Levelma
    register_post_type('levelma', array(
        'labels' => array(
            'name' => __('Levelma'),
            'singular_name' => __('Levelma')
        ),
        'public' => true,
        'has_archive' => true,
        'supports' => array('title', 'thumbnail', 'editor'),
        'taxonomies' => array('levelma_category'),
        'show_in_menu' => false, 
    ));
}
add_action('init', 'Pomar_core_register_post_types');

// Agregar campos personalizados
function Pomar_core_add_meta_boxes() {
    add_meta_box(
        'el_pomar_meta_box',
        'Detalles de El Pomar',
        'Pomar_core_render_meta_box',
        'el_pomar',
        'normal',
        'high'
    );

    add_meta_box(
        'mulai_meta_box',
        'Detalles de Mulai',
        'Pomar_core_render_meta_box',
        'mulai',
        'normal',
        'high'
    );

    add_meta_box(
        'levelma_meta_box',
        'Detalles de Levelma',
        'Pomar_core_render_meta_box',
        'levelma',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'Pomar_core_add_meta_boxes');

function Pomar_core_render_meta_box($post) {
    // Campos personalizados
    $benefits = get_post_meta($post->ID, '_benefits', true);
    $url_tienda = get_post_meta($post->ID, '_url_tienda', true);

    // Obtener lista de iconos SVG
    $icons = glob(plugin_dir_path(__FILE__) . '../../assets/img/icons/*.svg');

    ?>
    <label for="url_tienda">URL Tienda:</label>
    <input type="url" id="url_tienda" name="url_tienda" value="<?php echo esc_attr($url_tienda); ?>" size="25" />
    <div id="benefits-wrapper" style="margin: 15px 0;">
    <label for="benefits" style="font-weight: 600; line-height: 30px;">Beneficios:</label>
       <?php
    if (!empty($benefits)) {
        foreach ($benefits as $index => $benefit) {
            ?>
            <div class="benefit-item">
                <select name="benefits[icon][]" class="benefit-icon-select" style="width: max-content; height:40px">
                    <?php foreach ($icons as $icon) {
                        $icon_name = basename($icon);
                        ?>
                        <option value="<?php echo esc_attr($icon_name); ?>" <?php selected($benefit['icon'], $icon_name); ?>>
                            <?php echo esc_html($icon_name); ?>
                        </option>
                    <?php } ?>
                </select>
                <img src="<?php echo plugin_dir_url(__FILE__) . '../../assets/img/icons/' . esc_attr($benefit['icon']); ?>" alt="<?php echo esc_attr($benefit['icon']); ?>" class="benefit-icon-preview" style="width: 50px; height: 50px; vertical-align: middle; filter: invert();" />
                <input type="text" name="benefits[text][]" value="<?php echo esc_attr($benefit['text']); ?>" style="width: 60%;" placeholder="Beneficio / copy" />
                <button type="button" class="remove-benefit">Eliminar</button>
            </div>
            <?php
        }
    }
    ?>
    </div>
    <button type="button" id="add-benefit">Agregar Beneficio</button>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function updateIconPreview(selectElement) {
                var icon = selectElement.value;
                var img = selectElement.nextElementSibling;
                img.src = '<?php echo plugin_dir_url(__FILE__) . '../../assets/img/icons/'; ?>' + icon;
            }

            document.querySelectorAll('.benefit-icon-select').forEach(function(selectElement) {
                selectElement.addEventListener('change', function() {
                    updateIconPreview(selectElement);
                });
                updateIconPreview(selectElement);
            });

            document.getElementById('add-benefit').addEventListener('click', function() {
                var wrapper = document.getElementById('benefits-wrapper');
                var newBenefit = document.createElement('div');
                newBenefit.classList.add('benefit-item');
                newBenefit.innerHTML = `
                    <select name="benefits[icon][]" class="benefit-icon-select" style="width: max-content; height:40px">
                    <?php foreach ($icons as $icon) {
                        $icon_name = basename($icon);
                        ?>
                        <option value="<?php echo esc_attr($icon_name); ?>" <?php selected($benefit['icon'], $icon_name); ?>>
                            <?php echo esc_html($icon_name); ?>
                        </option>
                    <?php } ?>
                </select>
                    <img src="<?php echo plugin_dir_url(__FILE__) . '../../assets/img/icons/' . basename($icons[0]); ?>" alt="<?php echo basename($icons[0]); ?>" class="benefit-icon-preview" style="width: 40px; height: 40px; vertical-align: middle; filter:invert()" />
                    <input type="text" name="benefits[text][]" value="" style="width: 75%;" placeholder="Beneficio / copy" />
                    <button type="button" class="remove-benefit">Eliminar</button>
                `;
                wrapper.appendChild(newBenefit);

                var selectElement = newBenefit.querySelector('.benefit-icon-select');
                selectElement.addEventListener('change', function() {
                    updateIconPreview(selectElement);
                });
            });

            document.getElementById('benefits-wrapper').addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-benefit')) {
                    e.target.parentElement.remove();
                }
            });
        });
    </script>
    <?php
}

function Pomar_core_save_post($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    if (isset($_POST['url_tienda'])) {
        update_post_meta($post_id, '_url_tienda', sanitize_text_field($_POST['url_tienda']));
    }

    if (isset($_POST['benefits'])) {
        $benefits = array();
        foreach ($_POST['benefits']['icon'] as $index => $icon) {
            $benefits[] = array(
                'icon' => sanitize_text_field($icon),
                'text' => sanitize_text_field($_POST['benefits']['text'][$index])
            );
        }
        update_post_meta($post_id, '_benefits', $benefits);
    }
}
add_action('save_post', 'Pomar_core_save_post');


function Pomar_core_register_taxonomies() {
    // Registrar taxonomía para El Pomar
    register_taxonomy('el_pomar_category', 'el_pomar', array(
        'labels' => array(
            'name' => __('Categorías El Pomar'),
            'singular_name' => __('Categoría El Pomar')
        ),
        'hierarchical' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'el-pomar-category'),
    ));

    // Registrar taxonomía para Mulai
    register_taxonomy('mulai_category', 'mulai', array(
        'labels' => array(
            'name' => __('Categorías Mulai'),
            'singular_name' => __('Categoría Mulai')
        ),
        'hierarchical' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'mulai-category'),
    ));

    // Registrar taxonomía para Levelma
    register_taxonomy('levelma_category', 'levelma', array(
        'labels' => array(
            'name' => __('Categorías Levelma'),
            'singular_name' => __('Categoría Levelma')
        ),
        'hierarchical' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'levelma-category'),
    ));
}
add_action('init', 'Pomar_core_register_taxonomies');


// Añadir campos personalizados al formulario de añadir nueva categoría
function add_category_custom_fields($taxonomy) {
    $icons = glob(plugin_dir_path(__FILE__) . '../../assets/img/iconsCat/*.svg');
    ?>
    <div class="form-field term-group">
        <label for="category-icon">Icono de la Categoría</label>
        <select name="category-icon" id="category-icon" class="category-icon-select">
            <?php foreach ($icons as $icon) {
                $icon_filename = basename($icon); ?>
                <option value="<?php echo esc_attr($icon_filename); ?>">
                    <?php echo esc_html($icon_filename); ?>
                </option>
            <?php } ?>
        </select>
        <img src="" alt="" class="category-icon-preview" style="width: 40px; height: 40px; vertical-align: middle; background:black;" />
        <p class="description">Selecciona un icono SVG para la categoría</p>
    </div>
    <div class="form-field term-group">
        <label for="category-order">Orden de la Categoría</label>
        <input type="number" name="category-order" id="category-order" value="0" min="0" />
        <p class="description">Define el orden en que se mostrarán las categorías</p>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function updateIconPreview() {
                var icon = document.getElementById('category-icon').value;
                var img = document.querySelector('.category-icon-preview');
                if (icon) {
                    img.src = '<?php echo plugin_dir_url(__FILE__) . '../../assets/img/iconsCat/'; ?>' + icon;
                } else {
                    img.src = '';
                }
            }
            document.getElementById('category-icon').addEventListener('change', updateIconPreview);
            updateIconPreview();
        });
    </script>
    <?php
}
add_action('el_pomar_category_add_form_fields', 'add_category_custom_fields');
add_action('mulai_category_add_form_fields', 'add_category_custom_fields');
add_action('levelma_category_add_form_fields', 'add_category_custom_fields');

// Añadir campos personalizados al formulario de edición de categoría
function edit_category_custom_fields($term, $taxonomy) {
    $category_icon  = get_term_meta($term->term_id, 'category-icon', true);
    $category_order = get_term_meta($term->term_id, 'category-order', true);
    $icons = glob(plugin_dir_path(__FILE__) . '../../assets/img/iconsCat/*.svg');
    ?>
    <tr class="form-field term-group-wrap">
        <th scope="row"><label for="category-icon">Icono de la Categoría</label></th>
        <td>
            <select name="category-icon" id="category-icon" class="category-icon-select">
                <option value="">Seleccionar Icono</option>
                <?php foreach ($icons as $icon) {
                    $icon_filename = basename($icon);
                    ?>
                    <option value="<?php echo esc_attr($icon_filename); ?>" <?php selected($icon_filename, $category_icon); ?>>
                        <?php echo esc_html($icon_filename); ?>
                    </option>
                <?php } ?>
            </select>
            <img src="<?php if ($category_icon) { echo plugin_dir_url(__FILE__) . '../../assets/img/iconsCat/' . esc_attr($category_icon); } ?>" alt="" class="category-icon-preview" style="width: 40px; height: 40px; vertical-align: middle; background:black;" />
            <p class="description">Selecciona un icono SVG para la categoría</p>
        </td>
    </tr>
    <tr class="form-field">
        <th scope="row"><label for="category-order">Orden de la Categoría</label></th>
        <td>
            <input type="number" name="category-order" id="category-order" value="<?php echo esc_attr($category_order); ?>" min="0" />
            <p class="description">Define el orden en que se mostrarán las categorías</p>
        </td>
    </tr>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function updateIconPreview() {
                var icon = document.getElementById('category-icon').value;
                var img = document.querySelector('.category-icon-preview');
                if (icon) {
                    img.src = '<?php echo plugin_dir_url(__FILE__) . '../../assets/img/iconsCat/'; ?>' + icon;
                } else {
                    img.src = '';
                }
            }
            document.getElementById('category-icon').addEventListener('change', updateIconPreview);
            updateIconPreview();
        });
    </script>
    <?php
}
add_action('el_pomar_category_edit_form_fields', 'edit_category_custom_fields', 10, 2);
add_action('mulai_category_edit_form_fields', 'edit_category_custom_fields', 10, 2);
add_action('levelma_category_edit_form_fields', 'edit_category_custom_fields', 10, 2);

// Guardar campos personalizados de la categoría
function save_category_custom_fields($term_id) {
    if (isset($_POST['category-icon'])) {
        update_term_meta($term_id, 'category-icon', sanitize_text_field($_POST['category-icon']));
    }
    if (isset($_POST['category-order'])) {
        update_term_meta($term_id, 'category-order', intval($_POST['category-order']));
    }
}
add_action('created_el_pomar_category', 'save_category_custom_fields', 10, 2);
add_action('edited_el_pomar_category', 'save_category_custom_fields', 10, 2);
add_action('created_mulai_category', 'save_category_custom_fields', 10, 2);
add_action('edited_mulai_category', 'save_category_custom_fields', 10, 2);
add_action('created_levelma_category', 'save_category_custom_fields', 10, 2);
add_action('edited_levelma_category', 'save_category_custom_fields', 10, 2);

// Personalizar el pie de página en el administrador
function custom_admin_footer() {
    $screen = get_current_screen();
    $post_types = array('el_pomar', 'mulai', 'levelma');

    if (in_array($screen->post_type, $post_types)) {
        echo '<script>
            document.addEventListener("DOMContentLoaded", function() {
                const wpFooter = document.getElementById("wpfooter");
                if (wpFooter) {
                    const customFooter = \'<div class="footer" style="text-align: center; display: flex; flex-direction: row; align-items: center; align-content: center; flex-wrap: wrap; gap: 10px;">\'
                        + \'<hr style="width: 100%;">\'
                        + \'<p>Desarrollado por <a href="https://github.com/kerackdiaz" target="_blank" rel="nofollow">Kerack Diaz</a></p>\'
                        + \'<p>&copy; 2024 <a href="https://inclup.com/" target="_blank" rel="nofollow">Inclup S.A.S</a>. Todos los derechos reservados.</p>\'
                        + \'</div>\';
                    wpFooter.insertAdjacentHTML("afterbegin", customFooter);
                }
            });
        </script>';
    }
}
add_action('admin_footer', 'custom_admin_footer');