<?php
// Impede acesso direto
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Cria a tabela fpp_clientes no banco de dados ao ativar o plugin.
 * Usamos dbDelta para garantir criação/atualização segura.
 */
function fpp_criar_tabela_clientes() {
    global $wpdb;

    $tabela = $wpdb->prefix . 'fpp_clientes';
    $charset_collate = $wpdb->get_charset_collate();

    // 🟡 CAMPOS MELHORADOS:
    $sql = "CREATE TABLE IF NOT EXISTS $tabela (
        id INT(11) NOT NULL AUTO_INCREMENT,
        nome VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL,
        telefone VARCHAR(20) NOT NULL,
        id_cobranca VARCHAR(255),
        status_pagamento VARCHAR(50) DEFAULT 'pendente',
        data_envio DATETIME NOT NULL,
        log_erro TEXT NULL, -- 🟢 NOVO: campo opcional para logs de erro
        PRIMARY KEY (id),
        INDEX (email),
        INDEX (id_cobranca)
    ) $charset_collate;";

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';

    try {
        dbDelta($sql);
    } catch (Exception $e) {
        error_log('[FPP Plugin] Erro ao criar tabela: ' . $e->getMessage());
    }
}

/**
 * Insere um novo cliente na tabela fpp_clientes.
 * 
 * @param string $nome
 * @param string $telefone
 * @param string $email
 * @param string $id_cobranca
 * @param string $status_pagamento
 * @param string|null $log_erro
 */
function fpp_inserir_cliente($nome, $telefone, $email, $id_cobranca = null, $status_pagamento = 'pendente', $log_erro = null) {
    global $wpdb;

    $tabela = $wpdb->prefix . 'fpp_clientes';

    $wpdb->insert($tabela, [
        'nome'             => sanitize_text_field($nome),
        'email'            => sanitize_email($email),
        'telefone'         => sanitize_text_field($telefone),
        'id_cobranca'      => sanitize_text_field($id_cobranca),
        'status_pagamento' => sanitize_text_field($status_pagamento),
        'data_envio'       => current_time('mysql'),
        'log_erro'         => $log_erro ? sanitize_textarea_field($log_erro) : null,
    ]);
}
