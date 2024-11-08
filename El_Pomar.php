<?php
/**
* Plugin name: El Pomar
* Plugin URL: https://pomar.com.co/
* Description: Crea y gestiona los productos disponibles dentro del catalogo de Leches El Pomar.
* Version: 1.0.1
* Author: Inclup
* Author URI: http://inclup.com/
* License: GPLv2
* License URL: https://www.gnu.org/licenses/gpl-2.0.html
* Text Domain: El Pomar
* Wordpress tested up to: 6.6.2
* @package El Pomar
*/

if (!defined('ABSPATH')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit();
}

if (!defined('EP_VERSION')) {
    define('EP_VERSION', '1.0.1');
}

if (!defined('EP_FILE')) {
    define('EP_FILE', __FILE__);
}

/** Define las constantes antes de usarlas */
define('EP_PLUGIN_URL', plugin_dir_url(__FILE__));
define('EP_PLUGIN_DIR', plugin_dir_path(__FILE__));

if (!function_exists('is_plugin_active')) {
    include_once ABSPATH . 'wp-admin/includes/plugin.php';
}

// Include the menu file
include_once EP_PLUGIN_DIR . 'includes/menu.php';

// Include creation of post types
include_once EP_PLUGIN_DIR . 'includes/functions/post-types.php';

// Include the admin page file
include_once EP_PLUGIN_DIR . 'includes/pages/admin_page.php';

// Include dynamic content by shortcode
include_once EP_PLUGIN_DIR . 'includes/functions/front_content.php';

// Include updater
include_once EP_PLUGIN_DIR . 'includes/functions/updater.php';

class ElPomar {
    public function __construct() {
        add_action('admin_enqueue_scripts', array($this, 'EP_admin_enqueue_styles'));
        add_action('wp_enqueue_scripts', array($this, 'EP_enqueue_frontend_styles'));
        add_filter('plugin_action_links_' . plugin_basename(__FILE__), array($this, 'plugin_action_links'));
    }

    // Enlace de Accion para el plugin
    public function plugin_action_links($links) {
        $update_link = '<a href="' . esc_url(add_query_arg('lc_check_update', '1', admin_url('plugins.php'))) . '">' . __('Check for Updates', 'El Pomar') . '</a>';
        array_unshift($links, $update_link);
        return $links;
    }

    // Encolar el archivo CSS para el backend
    public function EP_admin_enqueue_styles() {
        wp_enqueue_style('EP-admin-styles', plugin_dir_url(__FILE__) . 'assets/css/admin-style.css');
    }

    // Encolar el archivo CSS y JS para el frontend
    public function EP_enqueue_frontend_styles() {
        wp_enqueue_style('EP-frontend-styles', plugin_dir_url(__FILE__) . 'assets/css/frontend-style.css');
        wp_enqueue_script('EP-frontend-scripts', plugin_dir_url(__FILE__) . 'assets/js/products-frontend.js', array('jquery'), null, true);
        wp_localize_script('EP-frontend-scripts', 'pomar_core', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'plugin_url' => plugin_dir_url(__FILE__)
        ));
    }

    // Registrar el hook de activación
    public static function activate() {
        flush_rewrite_rules();
    }

    // Registrar el hook de desactivación
    public static function deactivate() {
        flush_rewrite_rules();
    }
}

register_activation_hook(__FILE__, array('ElPomar', 'activate'));
register_deactivation_hook(__FILE__, array('ElPomar', 'deactivate'));

new ElPomar();

// Agregar la función para comprobar actualizaciones
add_action('admin_init', 'el_pomar_check_update');

function el_pomar_check_update() {
    if (isset($_GET['ep_check_update']) && $_GET['ep_check_update'] == '1') {
        $update_plugins = get_site_transient('update_plugins');
        $update_plugins = check_for_plugin_update($update_plugins);
        set_site_transient('update_plugins', $update_plugins);
        wp_update_plugins(); // Forzar la actualización de plugins
        add_action('admin_notices', 'el_pomar_update_notice');
    }
}

function el_pomar_update_notice() {
    ?>
    <div class="notice notice-success is-dismissible">
        <p><?php esc_html_e('Update check completed. If an update is available, it will be installed shortly.', 'el-pomar'); ?></p>
    </div>
    <?php
}