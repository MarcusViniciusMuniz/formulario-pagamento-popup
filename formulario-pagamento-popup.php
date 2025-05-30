<?php
/**
 * Plugin Name: Formulário Pagamento Popup
 * Description: Exibe um formulário em pop-up, customizável no Elementor Free, que gera link de pagamento na Asaas.
 * Version: 1.1.0
 * Author: MarcusVSMuniz
 * Text Domain: formulario-pagamento-popup
 */

// Impede acesso direto
if (!defined('ABSPATH')) exit;

// Define constantes
define('FPP_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('FPP_PLUGIN_URL', plugin_dir_url(__FILE__));
define('FPP_VERSION', '1.1.0');

// Carrega traduções
function fpp_load_textdomain() {
    load_plugin_textdomain('formulario-pagamento-popup', false, dirname(plugin_basename(__FILE__)) . '/languages');
}
add_action('plugins_loaded', 'fpp_load_textdomain');

// Carrega arquivos do plugin
require_once FPP_PLUGIN_DIR . 'includes/asaas-api.php';
require_once FPP_PLUGIN_DIR . 'includes/fpp_clientes.php';
require_once FPP_PLUGIN_DIR . 'includes/settings-page.php';
require_once FPP_PLUGIN_DIR . 'includes/fpp_webhook_handler.php';

// Enfileira scripts e estilos só nas páginas necessárias
function fpp_maybe_enqueue_scripts() {
    if (is_singular() && class_exists('Elementor\Plugin')) {
        $has_widget = \Elementor\Plugin::$instance->documents->get(get_the_ID())->get_elements_data();
        $found = false;
        array_walk_recursive($has_widget, function($item) use (&$found) {
            if (isset($item['widgetType']) && $item['widgetType'] === 'fpp_formulario_popup') {
                $found = true;
            }
        });
        if ($found) {
            fpp_enqueue_scripts();
        }
    }
}
add_action('wp', 'fpp_maybe_enqueue_scripts');

function fpp_enqueue_scripts() {
    wp_enqueue_style('fpp-style', FPP_PLUGIN_URL . 'assets/css/style.css', array(), FPP_VERSION);
    wp_enqueue_script('fpp-script', FPP_PLUGIN_URL . 'assets/js/script.js', array('jquery'), FPP_VERSION, true);
    wp_localize_script('fpp-script', 'fpp_data', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('fpp_nonce')
    ));
}

// Hook de ativação para criar tabela
function fpp_plugin_activate() {
    try {
        require_once FPP_PLUGIN_DIR . 'includes/fpp_clientes.php';
        fpp_criar_tabela_clientes();
    } catch (Exception $e) {
        error_log('Erro na ativação do plugin: ' . $e->getMessage());
    }
}
register_activation_hook(__FILE__, 'fpp_plugin_activate');

// Handler do AJAX
add_action('wp_ajax_fpp_enviar_dados', 'fpp_ajax_processa_formulario');
add_action('wp_ajax_nopriv_fpp_enviar_dados', 'fpp_ajax_processa_formulario');

function fpp_ajax_processa_formulario() {
    require_once FPP_PLUGIN_DIR . 'includes/fpp_processa_formulario.php';
    fpp_processa_formulario_via_ajax();
}

// Registra o widget Elementor
function fpp_register_formulario_widget($widgets_manager) {
    require_once FPP_PLUGIN_DIR . 'includes/widgets/fpp_widget_formulario.php';
    $widgets_manager->register(new \FPP_Widget_Formulario());
}
add_action('elementor/widgets/register', 'fpp_register_formulario_widget');
