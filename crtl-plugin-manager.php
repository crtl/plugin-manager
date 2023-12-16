<?php
/**
 * Plugin Name: Crtl Plugin Manager
 * Description: Plugin manages plugins through a json file which can be committed to version control
 * Version: 1.0.0
 * Author: Marvin Petker
 */
//activate_plugin('path/to/plugin/file.php');
//deactivate_plugins('path/to/plugin/file.php');
//if (is_plugin_active('path/to/plugin/file.php')) {
//    // Plugin is activated
//} else {
//    // Plugin is not activated
//}
//$active_plugins = get_option('active_plugins');
//
//$plugin_slug = 'plugin-slug';
//$install_status = install_plugin_install_status($plugin_slug);
//
//if ($install_status['status'] !== 'installed') {
//    // Plugin is not installed, you can install it here
//    wp_install_plugin($plugin_slug);
//}
//$plugin_slug = 'plugin-slug';
//wp_update_plugin($plugin_slug);

if( !function_exists('get_plugin_data') ){
    require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}

require_once __DIR__ . "/vendor/autoload.php";

$plugin = \Crtl\PluginManager\Plugin::init();


function crtl_init_plugin() {

}
add_action('plugins_loaded', 'crtl_init_plugin');



/**
 * Returns current plugin config or null if none exists
 * @return mixed|string[]|null
 * @throws Exception
 */
function crtl_get_plugin_config() {
    static $config;

    if (!$config) $config = crtl_load_config(get_template_directory());

    $installedPlugins = get_plugins();



    $plugins = get_option("active_plugins");

    $data = [];

    foreach ($config as $plugin_name => $plugin_version) {
        $installed = isset($installedPlugins[$plugin_name]);
        $data[$plugin_name] = [
            "installed" => $installed,
            "version" => $installed ? $installedPlugins[$plugin_name]["Version"] : null,
            "required_version" => $plugin_version,
            "requires_sync" => !$installed || $plugin_version !== $installedPlugins[$plugin_name]["Version"],
            "plugin_data" => $installedPlugins[$plugin_name] ?? null,
        ];
    }

    return $data;

}

/**
 * Attempts to load plugin configuration from theme directory
 * @param string $dir Path to theme to load config from
 * @return array<string, string> An array of plugins mapped to version
 * @throws Exception When wp-plugins.json contains invalid json
 */
function crtl_load_config(string $dir): ?array {
    $file = sprintf("%s/wp-plugins.json", rtrim($dir, "/"));

    if (!is_readable($file)) return null;

    $content = json_decode(file_get_contents($file), true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception("Unable to pare wp-plugins.json file: " . json_last_error_msg(), json_last_error());
    }

    return $content;
}

function crtl_get_config_path() {
    return get_template_directory() . "/wp-plugins.json";
}

