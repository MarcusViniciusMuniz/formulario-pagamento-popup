<?php
// Impede acesso direto
if (!defined('ABSPATH')) {
    exit;
}

/**
 * 🟢 REGISTRA A ROTA DO WEBHOOK
 * Usamos REST API do WordPress para que a Asaas envie POST para: /wp-json/fpp/v1/webhook/
 */
add_action('rest_api_init', function () {
    register_rest_route('fpp/v1', '/webhook/', [
        'methods'             => 'POST',
        'callback'            => 'fpp_tratar_webhook_asaas',
        'permission_callback' => '__return_true', // ⚠️ Permitido público (Asaas precisa acessar externamente)
    ]);
});

/**
 * Função que trata o Webhook recebido da Asaas
 *
 * @param WP_REST_Request $request
 * @return WP_REST_Response
 */
function fpp_tratar_webhook_asaas(WP_REST_Request $request) {
    $dados = $request->get_json_params();

    // ✅ LOG OPCIONAL: Ativar se quiser rastrear no debug.log
    // error_log('[FPP] Webhook recebido: ' . json_encode($dados));

    if (!isset($dados['event']) || !isset($dados['payment']['id'])) {
        error_log('[FPP] Webhook incompleto recebido.');
        return new WP_REST_Response(['status' => 'error', 'message' => 'Dados incompletos.'], 400);
    }

    $evento       = sanitize_text_field($dados['event']);
    $id_pagamento = sanitize_text_field($dados['payment']['id']);

    if ($evento === 'PAYMENT_RECEIVED') {
        global $wpdb;
        $tabela = $wpdb->prefix . 'fpp_clientes';

        $updated = $wpdb->update(
            $tabela,
            ['status_pagamento' => 'pago'],
            ['id_cobranca' => $id_pagamento],
            ['%s'],
            ['%s']
        );

        if ($updated !== false) {
            error_log('[FPP] Status atualizado para pago: ' . $id_pagamento);
            return new WP_REST_Response(['status' => 'ok', 'message' => 'Status atualizado.'], 200);
        } else {
            error_log('[FPP] Nenhuma linha afetada para ID: ' . $id_pagamento);
            return new WP_REST_Response(['status' => 'warning', 'message' => 'Nenhuma linha afetada.'], 200);
        }
    }

    // ✅ Outros eventos apenas registrados (opcional)
    error_log('[FPP] Evento ignorado: ' . $evento);
    return new WP_REST_Response(['status' => 'ignored', 'message' => 'Evento não tratado.'], 200);
}
