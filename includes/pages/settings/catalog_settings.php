<?php
if (!defined('ABSPATH')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit();
}

function Pomar_core_catalog_settings_page() {
    $categories_icons = glob(EP_PLUGIN_DIR . 'assets/img/catalog/categories/*.svg');
    $icons = glob(EP_PLUGIN_DIR . 'assets/img/catalog/icons/*.svg');

    if (isset($_GET['delete_icon']) && isset($_GET['type'])) {
        $icon_name = sanitize_text_field($_GET['delete_icon']);
        $icon_type = sanitize_text_field($_GET['type']);
        $delete_dir = ($icon_type === 'categories') ? EP_PLUGIN_DIR . 'assets/img/catalog/categories/' : EP_PLUGIN_DIR . 'assets/img/catalog/icons/';
        $delete_path = $delete_dir . $icon_name;

        if (file_exists($delete_path)) {
            unlink($delete_path);
            echo '<div class="notice notice-success is-dismissible"><p>Icono eliminado exitosamente.</p></div>';
        } else {
            echo '<div class="notice notice-error is-dismissible"><p>Error al eliminar el icono.</p></div>';
        }

        wp_redirect(admin_url('admin.php?page=el-pomar-catalog-settings'));
        exit();
    }

    ?>
    <div>
        <p>Carga, elimina o descarga los iconos de categorías o beneficios del portafolio de productos El Pomar.</p>

        <h3>Iconos de Categorías</h3>
        <div class="icon-grid">
            <?php foreach ($categories_icons as $icon) : ?>
                <div class="icon-item">
                    <img src="<?php echo plugin_dir_url(__FILE__) . '../../../assets/img/catalog/categories/' . basename($icon); ?>" alt="<?php echo basename($icon); ?>" style="background:black;">
                    <p><?php echo basename($icon); ?></p>
                    <div class="icon-actions">
                        <a href="<?php echo plugin_dir_url(__FILE__) . '../../../assets/img/catalog/categories/' . basename($icon); ?>" download>Descargar</a>
                        <a href="?page=el-pomar-catalog-settings&delete_icon=<?php echo urlencode(basename($icon)); ?>&type=categories" class="delete-icon">Eliminar</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <h3>Iconos Beneficios</h3>
        <div class="icon-grid">
            <?php foreach ($icons as $icon) : ?>
                <div class="icon-item">
                    <img src="<?php echo plugin_dir_url(__FILE__) . '../../../assets/img/catalog/icons/' . basename($icon); ?>" alt="<?php echo basename($icon); ?>" style="filter:invert(1);">
                    <p><?php echo basename($icon); ?></p>
                    <div class="icon-actions">
                        <a href="<?php echo plugin_dir_url(__FILE__) . '../../../assets/img/catalog/icons/' . basename($icon); ?>" download>Descargar</a>
                        <a href="?page=el-pomar-catalog-settings&delete_icon=<?php echo urlencode(basename($icon)); ?>&type=icons" class="delete-icon">Eliminar</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <h3>Agregar Nuevo Icono</h3>
        <form id="upload-icon-form" method="post" enctype="multipart/form-data">
            <label for="icon-type">Tipo de Icono:</label>
            <select name="icon-type" id="icon-type">
                <option value="categories">Categorías</option>
                <option value="icons">Beneficios</option>
            </select>
            <br><br>
            <label for="icon-file">Seleccionar Icono (SVG):</label>
            <input type="file" name="icon-file" id="icon-file" accept=".svg">
            <br><br>
            <input type="submit" name="upload-icon" value="Subir Icono">
        </form>
    </div>

    <?php
    if (isset($_POST['upload-icon'])) {
        $icon_type = sanitize_text_field($_POST['icon-type']);
        $upload_dir = ($icon_type === 'categories') ? EP_PLUGIN_DIR . 'assets/img/catalog/categories/' : EP_PLUGIN_DIR . 'assets/img/catalog/icons/';
        $upload_url = ($icon_type === 'categories') ? plugin_dir_url(__FILE__) . '../../assets/img/catalog/categories/' : plugin_dir_url(__FILE__) . '../../assets/img/catalog/icons/';

        if (!empty($_FILES['icon-file']['name'])) {
            $uploaded_file = $_FILES['icon-file'];
            $uploaded_file_name = basename($uploaded_file['name']);
            $uploaded_file_path = $upload_dir . $uploaded_file_name;

            if (move_uploaded_file($uploaded_file['tmp_name'], $uploaded_file_path)) {
                echo '<div class="notice notice-success is-dismissible"><p>Icono subido exitosamente: <a href="' . esc_url($upload_url . $uploaded_file_name) . '" target="_blank">' . esc_html($uploaded_file_name) . '</a></p></div>';
            } else {
                echo '<div class="notice notice-error is-dismissible"><p>Error al subir el icono.</p></div>';
            }
        } else {
            echo '<div class="notice notice-error is-dismissible"><p>Por favor, selecciona un archivo SVG para subir.</p></div>';
        }
    }
}