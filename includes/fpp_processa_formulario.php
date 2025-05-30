<?php
// Impede acesso direto
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Processa o formulário via chamada Ajax.
 * Valida, cria cobrança, salva no banco e retorna resposta.
 */
function fpp_processa_formulario_via_ajax() {
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'fpp_nonce')) {
        wp_send_json_error(__('Acesso não autorizado (nonce inválido).', 'formulario-pagamento-popup'));
    }

    $nome     = sanitize_text_field($_POST['nome'] ?? '');
    $email    = sanitize_email($_POST['email'] ?? '');
    $telefone = sanitize_text_field($_POST['telefone'] ?? '');

    if (empty($nome) || empty($email) || empty($telefone)) {
        wp_send_json_error(__('Por favor, preencha todos os campos.', 'formulario-pagamento-popup'));
    }

    // 🔧 Carrega configurações
    $chave_api      = get_option('fpp_asaas_api_key');
    $valor_padrao   = get_option('fpp_valor_cobranca_padrao');
    $metodo_pag     = get_option('fpp_metodo_pagamento_padrao');
    $metodo_envio   = get_option('fpp_send_method');
    $email_destino  = get_option('fpp_email_destination');
    $webhook_url    = get_option('fpp_webhook_url');

    require_once plugin_dir_path(__FILE__) . 'asaas-api.php';

    $cliente = [
        'nome'     => $nome,
        'email'    => $email,
        'telefone' => $telefone
    ];
    $resultado = fpp_criar_cobranca($cliente, $valor_padrao, $metodo_pag, $chave_api);

    $log_erro = null;
    if (isset($resultado['erro'])) {
        $log_erro = $resultado['erro'];
        error_log('[FPP Plugin] Erro na cobrança: ' . $log_erro);
    }

    $id_cobranca    = $resultado['id'] ?? null;
    $link_pagamento = $resultado['url'] ?? null;

    // 🔧 Salva no banco mesmo em caso de erro (para não perder lead)
    require_once plugin_dir_path(__FILE__) . 'fpp_clientes.php';
    fpp_inserir_cliente($nome, $telefone, $email, $id_cobranca, $log_erro ? 'erro' : 'pendente', $log_erro);

    // 🔧 Se configurado para email
    if ($metodo_envio === 'email' && is_email($email_destino)) {
        $assunto = __('Novo cliente do formulário de pagamento', 'formulario-pagamento-popup');
        $mensagem = "Nome: $nome\nEmail: $email\nTelefone: $telefone\nID Cobrança: $id_cobranca\nStatus: " . ($log_erro ? 'Erro' : 'Pendente');
        wp_mail($email_destino, $assunto, $mensagem);
    }

    // 🔧 Se configurado para webhook
    if ($metodo_envio === 'webhook' && filter_var($webhook_url, FILTER_VALIDATE_URL)) {
        wp_remote_post($webhook_url, [
            'method'  => 'POST',
            'headers' => ['Content-Type' => 'application/json'],
            'body'    => json_encode([
                'nome'        => $nome,
                'email'       => $email,
                'telefone'    => $telefone,
                'id_cobranca' => $id_cobranca,
                'status'      => $log_erro ? 'erro' : 'pendente',
            ]),
        ]);
    }

    // 🔧 Retorna para o frontend
    if ($log_erro) {
        wp_send_json_error(__('Erro ao criar cobrança: ', 'formulario-pagamento-popup') . esc_html($log_erro));
    }

    if (!empty($link_pagamento)) {
        wp_send_json_success(['url' => $link_pagamento]);
    } else {
        wp_send_json_error(__('Erro: link de pagamento não gerado.', 'formulario-pagamento-popup'));
    }
}
