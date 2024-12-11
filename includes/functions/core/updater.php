<?php
if (!defined('ABSPATH')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit();
}

class El_Pomar_GitHub_Updater {
    private $file;
    private $plugin;
    private $basename;
    private $active;
    private $github_response;
    private $github_repo = 'El_PomarCoreWP'; 
    private $github_user = 'KerackDiaz'; 
    private $access_token = ''; 

    public function __construct($file) {
        $this->file = $file;
        add_action('admin_init', array($this, 'set_plugin_properties'));
        add_filter('pre_set_site_transient_update_plugins', array($this, 'check_update'));
        add_filter('plugins_api', array($this, 'plugin_popup'), 10, 3);
        add_filter('upgrader_post_install', array($this, 'after_install'), 10, 3);
    }

    public function set_plugin_properties() {
        $this->plugin = get_plugin_data($this->file);
        $this->basename = plugin_basename($this->file);
        $this->active = is_plugin_active($this->basename);
    }

    private function get_repository_info() {
        if (is_null($this->github_response)) {
            $request_uri = sprintf('https://api.github.com/repos/%s/%s/releases/latest', 
                $this->github_user, $this->github_repo);

            $args = array();
            if ($this->access_token) {
                $args['headers'] = array(
                    'Authorization' => "token {$this->access_token}"
                );
            }

            $response = wp_remote_get($request_uri, $args);

            if (is_wp_error($response)) {
                return false;
            }

            $response = json_decode(wp_remote_retrieve_body($response));
            
            if (is_object($response)) {
                $this->github_response = $response;
            }
        }
    }

    public function check_update($transient) {
        if (empty($transient->checked)) {
            return $transient;
        }

        $this->get_repository_info();

        if (!empty($this->github_response)) {
            $version = str_replace('v', '', $this->github_response->tag_name);

            if (version_compare($version, $this->plugin['Version'], '>')) {
                $plugin = array(
                    'url' => $this->plugin["PluginURI"],
                    'slug' => current(explode('/', $this->basename)),
                    'package' => $this->github_response->zipball_url,
                    'new_version' => $version
                );

                $transient->response[$this->basename] = (object) $plugin;
            }
        }

        return $transient;
    }

    public function plugin_popup($result, $action, $args) {
        if ($action !== 'plugin_information') {
            return false;
        }

        if (!isset($args->slug) || $args->slug != current(explode('/', $this->basename))) {
            return false;
        }

        $this->get_repository_info();

        if (!empty($this->github_response)) {
            $plugin = array(
                'name'              => $this->plugin["Name"],
                'slug'              => $this->basename,
                'version'           => str_replace('v', '', $this->github_response->tag_name),
                'author'            => $this->plugin["AuthorName"],
                'author_profile'    => $this->plugin["AuthorURI"],
                'last_updated'      => $this->github_response->published_at,
                'homepage'          => $this->plugin["PluginURI"],
                'short_description' => $this->plugin["Description"],
                'sections'          => array(
                    'Description'   => $this->plugin["Description"],
                    'Updates'       => $this->github_response->body,
                ),
                'download_link'     => $this->github_response->zipball_url
            );

            return (object) $plugin;
        }

        return $result;
    }

    public function after_install($response, $hook_extra, $result) {
        global $wp_filesystem;

        $install_directory = plugin_dir_path($this->file);
        $wp_filesystem->move($result['destination'], $install_directory);
        $result['destination'] = $install_directory;

        if ($this->active) {
            activate_plugin($this->basename);
        }

        return $result;
    }
}

// Inicializar el actualizador
$updater = new El_Pomar_GitHub_Updater(__FILE__);