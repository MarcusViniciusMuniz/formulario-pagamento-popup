<?php
if (!defined('ABSPATH')) exit;

/**
 * Adiciona a página no menu admin
 */
function fpp_add_admin_menu() {
    add_menu_page(
        __('Formulário Pagamento', 'formulario-pagamento-popup'),
        __('Formulário Pagamento', 'formulario-pagamento-popup'),
        'manage_options',
        'fpp_configuracoes',
        'fpp_render_settings_page',
        'dashicons-money-alt',
        56
    );
}
add_action('admin_menu', 'fpp_add_admin_menu');

/**
 * Registra as opções
 */
function fpp_register_settings() {
    register_setting('fpp_settings_group', 'fpp_asaas_api_key');
    register_setting('fpp_settings_group', 'fpp_valor_cobranca_padrao');
    register_setting('fpp_settings_group', 'fpp_metodo_pagamento_padrao');
    register_setting('fpp_settings_group', 'fpp_send_method');
    register_setting('fpp_settings_group', 'fpp_email_destination');
    register_setting('fpp_settings_group', 'fpp_webhook_url');
}
add_action('admin_init', 'fpp_register_settings');

/**
 * Renderiza a página
 */
function fpp_render_settings_page() {
    ?>
    <div class="wrap">
        <h1><?php _e('Configurações do Formulário de Pagamento', 'formulario-pagamento-popup'); ?></h1>
        <form method="post" action="options.php">
            <?php settings_fields('fpp_settings_group'); ?>
            <?php do_settings_sections('fpp_settings_group'); ?>

            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><?php _e('Chave API Asaas', 'formulario-pagamento-popup'); ?></th>
                    <td><input type="text" name="fpp_asaas_api_key" value="<?php echo esc_attr(get_option('fpp_asaas_api_key')); ?>" style="width:400px;"></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php _e('Valor padrão cobrança (R$)', 'formulario-pagamento-popup'); ?></th>
                    <td><input type="text" name="fpp_valor_cobranca_padrao" value="<?php echo esc_attr(get_option('fpp_valor_cobranca_padrao')); ?>"></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php _e('Método de pagamento padrão', 'formulario-pagamento-popup'); ?></th>
                    <td>
                        <select name="fpp_metodo_pagamento_padrao">
                            <option value="PIX" <?php selected(get_option('fpp_metodo_pagamento_padrao'), 'PIX'); ?>>PIX</option>
                            <option value="BOLETO" <?php selected(get_option('fpp_metodo_pagamento_padrao'), 'BOLETO'); ?>>Boleto</option>
                            <option value="CREDIT_CARD" <?php selected(get_option('fpp_metodo_pagamento_padrao'), 'CREDIT_CARD'); ?>>Cartão de Crédito</option>
                        </select>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php _e('Como enviar os dados', 'formulario-pagamento-popup'); ?></th>
                    <td>
                        <select name="fpp_send_method">
                            <option value="email" <?php selected(get_option('fpp_send_method'), 'email'); ?>>Email</option>
                            <option value="webhook" <?php selected(get_option('fpp_send_method'), 'webhook'); ?>>Webhook</option>
                            <option value="database" <?php selected(get_option('fpp_send_method'), 'database'); ?>>Banco de Dados</option>
                        </select>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php _e('Email destino', 'formulario-pagamento-popup'); ?></th>
                    <td><input type="email" name="fpp_email_destination" value="<?php echo esc_attr(get_option('fpp_email_destination')); ?>" style="width:300px;"></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php _e('Webhook URL', 'formulario-pagamento-popup'); ?></th>
                    <td><input type="url" name="fpp_webhook_url" value="<?php echo esc_attr(get_option('fpp_webhook_url')); ?>" style="width:400px;"></td>
                </tr>
            </table>

            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}
