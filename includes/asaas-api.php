<?php
// Impede acesso direto ao arquivo
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Cria uma cobrança na API Asaas.
 *
 * @param array  $cliente       ['nome', 'email', 'telefone']
 * @param string $valor         Valor da cobrança (ex: "10.00")
 * @param string $metodo        Método de pagamento ("PIX", "BOLETO", "CREDIT_CARD")
 * @param string $chave_api     Chave da API Asaas
 * @return array                Retorna ['success' => true, 'url' => ..., 'id' => ...] OU ['erro' => ...]
 */
function fpp_criar_cobranca($cliente, $valor, $metodo, $chave_api) {
    if (empty($chave_api)) {
        return ['erro' => 'Chave da API não configurada.'];
    }

    if (empty($cliente['nome']) || empty($cliente['email']) || empty($cliente['telefone'])) {
        return ['erro' => 'Dados do cliente incompletos.'];
    }

    $metodo_pagamento = $metodo ?: 'PIX';
    $valor_cobranca = $valor ?: '10.00';
    $telefone_limpo = preg_replace('/\D/', '', $cliente['telefone']); // apenas números

    $data = [
        'billingType' => $metodo_pagamento,
        'value'       => $valor_cobranca,
        'dueDate'     => date('Y-m-d'),
        'description' => 'Pagamento via formulário FPP',
        'customer'    => [
            'name'  => $cliente['nome'],
            'email' => $cliente['email'],
            'phone' => $telefone_limpo
        ]
    ];

    $url = 'https://www.asaas.com/api/v3/payments';

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'access_token: ' . $chave_api
    ]);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    $resposta = curl_exec($ch);

    if (curl_errno($ch)) {
        $erro_curl = curl_error($ch);
        curl_close($ch);
        error_log('[FPP Plugin] Erro cURL: ' . $erro_curl); // 🟢 log no debug.log
        return ['erro' => 'Erro cURL: ' . $erro_curl];
    }

    curl_close($ch);
    $resposta_decodificada = json_decode($resposta, true);

    if (isset($resposta_decodificada['errors'])) {
        $descricao_erro = $resposta_decodificada['errors'][0]['description'];
        error_log('[FPP Plugin] Erro API Asaas: ' . $descricao_erro);
        return ['erro' => $descricao_erro];
    }

    if (isset($resposta_decodificada['error'])) {
        $descricao_erro = $resposta_decodificada['error'];
        error_log('[FPP Plugin] Erro API Asaas: ' . $descricao_erro);
        return ['erro' => $descricao_erro];
    }

    if (empty($resposta_decodificada['invoiceUrl'])) {
        error_log('[FPP Plugin] Erro: link de pagamento não retornado.');
        return ['erro' => 'Erro: link de pagamento não gerado.'];
    }

    return [
        'success' => true,
        'url'     => $resposta_decodificada['invoiceUrl'],
        'id'      => $resposta_decodificada['id'] ?? null
    ];
}
