<?php
if (!defined('ABSPATH')) exit;

/**
 * Cria uma cobrança na API Asaas
 *
 * @param array $cliente — ['nome', 'email', 'telefone']
 * @param string $valor
 * @param string $metodo
 * @param string $chave_api
 * @return array ['success' => bool, 'url' => string, 'erro' => string]
 */
function fpp_criar_cobranca($cliente, $valor, $metodo, $chave_api) {
    if (empty($chave_api)) {
        return ['erro' => 'Chave da API não configurada.'];
    }

    $telefone_limpo = preg_replace('/\D/', '', $cliente['telefone']);

    $data = [
        'billingType' => $metodo ?: 'PIX',
        'value'       => $valor ?: '10.00',
        'dueDate'     => date('Y-m-d'),
        'description' => 'Pagamento gerado via formulário',
        'customer'    => [
            'name'  => $cliente['nome'],
            'email' => $cliente['email'],
            'phone' => $telefone_limpo,
        ],
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
        return ['erro' => 'Erro cURL: ' . $erro_curl];
    }

    curl_close($ch);
    $resposta_decodificada = json_decode($resposta, true);

    if (isset($resposta_decodificada['errors'])) {
        return ['erro' => $resposta_decodificada['errors'][0]['description']];
    }

    if (isset($resposta_decodificada['error'])) {
        return ['erro' => $resposta_decodificada['error']];
    }

    return [
        'success' => true,
        'url'     => $resposta_decodificada['invoiceUrl'] ?? '',
        'id'      => $resposta_decodificada['id'] ?? null,
    ];
}
