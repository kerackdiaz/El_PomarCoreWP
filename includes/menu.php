<?php
if (!defined('ABSPATH')) {
    exit;
}

if (!function_exists('Pomar_core_menu')) {
    function Pomar_core_menu() {
        add_menu_page(
            'Productos El Pomar',
            'Productos El Pomar',
            'manage_options',
            'productos-el-pomar',
            'Pomar_core_documentation_page',
            plugin_dir_url(__FILE__) . '../assets/img/el-pomar.svg',
            30
        );

        add_submenu_page(
            'productos-el-pomar',
            'El Pomar',
            'El Pomar',
            'manage_options',
            'edit.php?post_type=el_pomar'
        );

        add_submenu_page(
            'productos-el-pomar',
            'Categorías El Pomar',
            'Categorías El Pomar',
            'manage_options',
            'edit-tags.php?taxonomy=el_pomar_category&post_type=el_pomar'
        );

        add_submenu_page(
            'productos-el-pomar',
            'Mulai',
            'Mulai',
            'manage_options',
            'edit.php?post_type=mulai'
        );

        add_submenu_page(
            'productos-el-pomar',
            'Categorías Mulai',
            'Categorías Mulai',
            'manage_options',
            'edit-tags.php?taxonomy=mulai_category&post_type=mulai'
        );

        add_submenu_page(
            'productos-el-pomar',
            'Levelma',
            'Levelma',
            'manage_options',
            'edit.php?post_type=levelma'
        );

        add_submenu_page(
            'productos-el-pomar',
            'Categorías Levelma',
            'Categorías Levelma',
            'manage_options',
            'edit-tags.php?taxonomy=levelma_category&post_type=levelma'
        );
    }
    add_action('admin_menu', 'Pomar_core_menu');
}