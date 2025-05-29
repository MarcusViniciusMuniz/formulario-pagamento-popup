<?php
/**
 * Plugin Name: Formulário Pagamento Popup
 * Description: Exibe um formulário em pop-up e gera link de pagamento via Asaas. Compatível com Elementor Free.
 * Version: 1.0.0
 * Author: MarcusVSMuniz
 * Text Domain: formulario-pagamento-popup
 */

if (!defined('ABSPATH')) exit;

// Define constantes
define('FPP_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('FPP_PLUGIN_URL', plugin_dir_url(__FILE__));
define('FPP_VERSION', '1.0.0');

// Carrega arquivos
require_once FPP_PLUGIN_DIR . 'includes/popup-form.php';

/**
 * Carrega textdomain para traduções
 */
function fpp_load_textdomain() {
    load_plugin_textdomain('formulario-pagamento-popup', false, dirname(plugin_basename(__FILE__)) . '/languages');
}
add_action('plugins_loaded', 'fpp_load_textdomain');

require_once FPP_PLUGIN_DIR . 'includes/asaas-api.php';
require_once FPP_PLUGIN_DIR . 'includes/settings-page.php';
