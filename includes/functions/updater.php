<?php

add_filter('pre_set_site_transient_update_plugins', 'check_for_plugin_update');
add_filter('plugins_api', 'plugin_api_call', 10, 3);

function check_for_plugin_update($transient) {
    if (empty($transient->checked)) {
        return $transient;
    }

    $remote_version = get_remote_plugin_version();

    if ($remote_version && version_compare(EP_VERSION, $remote_version, '<')) {
        $plugin_data = get_plugin_data(EP_FILE);
        $slug = plugin_basename(EP_FILE);

        $transient->response[$slug] = (object) [
            'slug' => $slug,
            'new_version' => $remote_version,
            'url' => 'https://github.com/kerackdiaz/El_PomarCoreWP',
            'package' => 'https://github.com/kerackdiaz/El_PomarCoreWP/archive/refs/tags/V' . $remote_version . '.zip',
        ];
    }

    return $transient;
}

function get_remote_plugin_version() {
    $remote_info = wp_remote_get('https://raw.githubusercontent.com/kerackdiaz/El_PomarCoreWP/main/README.md');
    if (is_wp_error($remote_info) || wp_remote_retrieve_response_code($remote_info) != 200) {
        return false;
    }

    $remote_info = wp_remote_retrieve_body($remote_info);
    if (preg_match('/^Stable tag:\s*(\d+\.\d+\.\d+)/m', $remote_info, $matches)) {
        return $matches[1];
    }

    return false;
}

function plugin_api_call($default, $action, $args) {
    if ($action != 'plugin_information') {
        return false;
    }

    if ($args->slug != plugin_basename(EP_FILE)) {
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
        'version' => get_remote_plugin_version(),
        'author' => 'Inclup',
        'homepage' => 'https://github.com/kerackdiaz/El_PomarCoreWP',
        'sections' => [
            'description' => 'Este plugin es exclusivo para la administración y venta de productos de Leches El Pomar.',
        ],
        'download_link' => 'https://github.com/kerackdiaz/El_PomarCoreWP/archive/refs/tags/V' . get_remote_plugin_version() . '.zip',
    ];

    return (object) $plugin_info;
}