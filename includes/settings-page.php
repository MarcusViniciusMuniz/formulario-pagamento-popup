<?php
// Impede acesso direto ao arquivo
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Adiciona a página de configurações do plugin ao menu do admin.
 */
function fpp_add_admin_menu() {
    add_menu_page(
        __('Configurações do Formulário', 'formulario-pagamento-popup'),
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
 * Registra as opções no banco.
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
 * Renderiza a página de configurações no admin.
 */
function fpp_render_settings_page() {
    ?>
    <div class="wrap">
        <h1><?php _e('Configurações do Formulário de Pagamento', 'formulario-pagamento-popup'); ?></h1>

        <?php if (isset($_GET['settings-updated']) && $_GET['settings-updated']): ?>
            <div class="updated notice">
                <p><?php _e('Configurações salvas com sucesso.', 'formulario-pagamento-popup'); ?></p>
            </div>
        <?php endif; ?>

        <form method="post" action="options.php">
            <?php settings_fields('fpp_settings_group'); ?>
            <?php do_settings_sections('fpp_settings_group'); ?>

            <table class="form-table">
                <!-- Chave da API Asaas -->
                <tr>
                    <th scope="row"><?php _e('Chave da API Asaas', 'formulario-pagamento-popup'); ?></th>
                    <td>
                        <input type="text" name="fpp_asaas_api_key" value="<?php echo esc_attr(get_option('fpp_asaas_api_key')); ?>" style="width: 400px;">
                        <p class="description"><?php _e('Copie e cole sua chave privada da conta Asaas aqui.', 'formulario-pagamento-popup'); ?></p>
                    </td>
                </tr>

                <!-- Valor padrão -->
                <tr>
                    <th scope="row"><?php _e('Valor padrão da cobrança (R$)', 'formulario-pagamento-popup'); ?></th>
                    <td>
                        <input type="text" name="fpp_valor_cobranca_padrao" value="<?php echo esc_attr(get_option('fpp_valor_cobranca_padrao')); ?>">
                        <p class="description"><?php _e('Valor a ser cobrado por padrão, se não especificado.', 'formulario-pagamento-popup'); ?></p>
                    </td>
                </tr>

                <!-- Método padrão -->
                <tr>
                    <th scope="row"><?php _e('Método de pagamento padrão', 'formulario-pagamento-popup'); ?></th>
                    <td>
                        <select name="fpp_metodo_pagamento_padrao">
                            <option value="PIX" <?php selected(get_option('fpp_metodo_pagamento_padrao'), 'PIX'); ?>><?php _e('Pix', 'formulario-pagamento-popup'); ?></option>
                            <option value="BOLETO" <?php selected(get_option('fpp_metodo_pagamento_padrao'), 'BOLETO'); ?>><?php _e('Boleto', 'formulario-pagamento-popup'); ?></option>
                            <option value="CREDIT_CARD" <?php selected(get_option('fpp_metodo_pagamento_padrao'), 'CREDIT_CARD'); ?>><?php _e('Cartão de Crédito', 'formulario-pagamento-popup'); ?></option>
                        </select>
                    </td>
                </tr>

                <!-- Forma de envio -->
                <tr>
                    <th scope="row"><?php _e('Como receber os dados do cliente', 'formulario-pagamento-popup'); ?></th>
                    <td>
                        <select name="fpp_send_method">
                            <option value="email" <?php selected(get_option('fpp_send_method'), 'email'); ?>><?php _e('Enviar por E-mail', 'formulario-pagamento-popup'); ?></option>
                            <option value="webhook" <?php selected(get_option('fpp_send_method'), 'webhook'); ?>><?php _e('Enviar via Webhook', 'formulario-pagamento-popup'); ?></option>
                            <option value="database" <?php selected(get_option('fpp_send_method'), 'database'); ?>><?php _e('Salvar no Banco de Dados', 'formulario-pagamento-popup'); ?></option>
                        </select>
                    </td>
                </tr>

                <!-- E-mail de destino -->
                <tr>
                    <th scope="row"><?php _e('E-mail para receber os dados', 'formulario-pagamento-popup'); ?></th>
                    <td>
                        <input type="email" name="fpp_email_destination" value="<?php echo esc_attr(get_option('fpp_email_destination')); ?>" style="width: 300px;">
                        <p class="description"><?php _e('Usado apenas se o método de envio for "Enviar por E-mail".', 'formulario-pagamento-popup'); ?></p>
                    </td>
                </tr>

                <!-- Webhook URL -->
                <tr>
                    <th scope="row"><?php _e('URL do Webhook', 'formulario-pagamento-popup'); ?></th>
                    <td>
                        <input type="url" name="fpp_webhook_url" value="<?php echo esc_attr(get_option('fpp_webhook_url')); ?>" style="width: 400px;">
                        <p class="description"><?php _e('Usado apenas se o método de envio for "Enviar via Webhook".', 'formulario-pagamento-popup'); ?></p>
                    </td>
                </tr>
            </table>

            <?php submit_button(__('Salvar Configurações', 'formulario-pagamento-popup')); ?>
        </form>
    </div>
    <?php
}
