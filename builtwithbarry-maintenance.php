<?php
/**
 * Plugin Name: Maintenance Mode
 * Plugin URI: https://www.builtwithbarry.com
 * Description: A plugin to force display of a custom 50X error page when enabled.
 * Version: 1.0.0
 * Author: Barry Smith
 * Author URI: https://www.builtwithbarry.com
 * GitHub Plugin URI: https://github.com/barry-smithjr/maintenance-mode
 * GitHub Branch: main
 * Plugin Name: Force 50X Error Page
 * Plugin URI: https://www.example.com
 * Description: A plugin to force display of a custom 50X error page when enabled.
 * Version: 1.0.0
 * Author: Barry Smith
 * Author URI: https://www.builtwithbarry.com
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Force_50X_Error {
    private static $instance = null;

    public static function get_instance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct() {
        add_action('init', array($this, 'check_maintenance_mode'));
        add_action('admin_menu', array($this, 'add_admin_page'));
        add_action('admin_init', array($this, 'register_settings'));
    }

    public function check_maintenance_mode() {
        if (get_option('force_50x_enabled', false)) {
            status_header(503);
            header('Location: /50x.html');
            exit;
        }
    }

    public function add_admin_page() {
        add_options_page('Force 50X Error', 'Force 50X Error', 'manage_options', 'force-50x-error', array($this, 'admin_page_content'));
    }

    public function register_settings() {
        register_setting('force_50x_settings', 'force_50x_enabled');
    }

    public function admin_page_content() {
        ?>
        <div class="wrap">
            <h1>Force 50X Error Page</h1>
            <form method="post" action="options.php">
                <?php settings_fields('force_50x_settings'); ?>
                <?php do_settings_sections('force_50x_settings'); ?>
                <table class="form-table">
                    <tr>
                        <th scope="row">Enable 50X Error Page</th>
                        <td>
                            <input type="checkbox" name="force_50x_enabled" value="1" <?php checked(1, get_option('force_50x_enabled', 0)); ?> />
                        </td>
                    </tr>
                </table>
                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }
}

Force_50X_Error::get_instance();
