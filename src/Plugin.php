<?php
namespace Crtl\PluginManager;


use Crtl\PluginManager\Attribute\WPAction;

class Plugin extends PluginBase {

    #[WPAction("after_setup_theme")]
    public function after_setup_theme() {
        if (!is_admin()) return;

        try {
            $config = crtl_get_plugin_config();

            if ($config) return;

            $plugins = get_option("active_plugins");

            $data = [];
            foreach ($plugins as $plugin) {
                $info = get_plugin_data(ABSPATH . "wp-content/plugins/$plugin");
                $data[$plugin] = $info["Version"];
            }


            file_put_contents(crtl_get_config_path(), json_encode($data), JSON_PRETTY_PRINT);
        } catch (\Exception $ex) {
            wp_trigger_error("crtl-crtl_after_setup_theme", $ex->getMessage(), E_WARNING);
        }
    }

    #[WPAction("admin_menu")]
    public function admin_menu() {
        add_menu_page(
            __("Crtl Plugin Manager", "crtl-pm"),
            __("Plugin Manager", "crtl-pm"),
            "manage_options",
            "crtl-plugin-manager",
            [$this, "admin_pages_index"],
            "dashicons-admin-generic",
        );
    }


    /**
     * Renders HTML of admin index page
     * @return void
     */
    public function admin_pages_index() {
        include __DIR__ . "/../pages/admin-page.php";
    }


}