<?php

if (!defined('ABSPATH')) {
    exit;
}

add_filter('pre_set_site_transient_update_plugins', 'ep_check_for_plugin_update');
add_filter('plugins_api', 'ep_plugin_api_call', 10, 3);

function ep_check_for_plugin_update($ep_transient) {
    if (empty($ep_transient->checked)) {
        return $ep_transient;
    }

    $ep_remote_version = ep_get_remote_plugin_version();

    if ($ep_remote_version && version_compare(EP_VERSION, $ep_remote_version, '<')) {
        $ep_plugin_data = get_plugin_data(EP_FILE);
        $ep_slug = plugin_basename(EP_FILE);

        $ep_transient->response[$ep_slug] = (object) [
            'slug' => $ep_slug,
            'new_version' => $ep_remote_version,
            'url' => 'https://github.com/kerackdiaz/El_PomarCoreWP',
            'package' => 'https://github.com/kerackdiaz/El_PomarCoreWP/archive/refs/tags/V' . $ep_remote_version . '.zip',
        ];
    }

    return $ep_transient;
}

function ep_get_remote_plugin_version() {
    $ep_remote_info = wp_remote_get('https://raw.githubusercontent.com/kerackdiaz/El_PomarCoreWP/main/README.md');
    if (is_wp_error($ep_remote_info) || wp_remote_retrieve_response_code($ep_remote_info) != 200) {
        return false;
    }

    $ep_remote_info = wp_remote_retrieve_body($ep_remote_info);
    if (preg_match('/^Stable tag:\s*(\d+\.\d+\.\d+)/m', $ep_remote_info, $ep_matches)) {
        return $ep_matches[1];
    }

    return false;
}

function ep_plugin_api_call($ep_default, $ep_action, $ep_args) {
    if ($ep_action != 'plugin_information') {
        return false;
    }

    if ($ep_args->slug != plugin_basename(EP_FILE)) {
        return false;
    }

    $remote_info = wp_remote_get('https://raw.githubusercontent.com/kerackdiaz/El_PomarCoreWP/main/README.md');
    if (is_wp_error($remote_info) || wp_remote_retrieve_response_code($remote_info) != 200) {
        return false;
    }

    $remote_info = wp_remote_retrieve_body($remote_info);

    $plugin_info = [
        'name' => 'El Pomar Core',
        'slug' => plugin_basename(EP_FILE),
        'version' => ep_get_remote_plugin_version(),
        'author' => 'Inclup',
        'homepage' => 'https://github.com/kerackdiaz/El_PomarCoreWP',
        'sections' => [
            'description' => 'Este plugin es exclusivo para la administración y venta de productos de Leches El Pomar.',
        ],
        'download_link' => 'https://github.com/kerackdiaz/El_PomarCoreWP/archive/refs/tags/V' . ep_get_remote_plugin_version() . '.zip',
    ];

    return (object) $plugin_info;
}