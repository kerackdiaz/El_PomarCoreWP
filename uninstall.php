<?php 

if (!defined('ABSPATH')) {
    exit;
}

// Eliminar metadatos de posts
global $wpdb;
$wpdb->query("DELETE FROM $wpdb->postmeta WHERE meta_key LIKE '_ep_%'");

// Eliminar términos y taxonomías
$taxonomies = array('el_pomar_category', 'mulai_category', 'levelma_category');
foreach ($taxonomies as $taxonomy) {
    $terms = get_terms(array('taxonomy' => $taxonomy, 'hide_empty' => false));
    foreach ($terms as $term) {
        wp_delete_term($term->term_id, $taxonomy);
    }
}

// Eliminar tipos de post personalizados
$post_types = array('el_pomar', 'mulai', 'levelma');
foreach ($post_types as $post_type) {
    $posts = get_posts(array('post_type' => $post_type, 'numberposts' => -1));
    foreach ($posts as $post) {
        wp_delete_post($post->ID, true);
    }
}

// Eliminar transientes
delete_site_transient('update_plugins');