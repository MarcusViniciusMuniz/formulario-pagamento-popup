<?php
// Arquivo: includes/popup-form.php

// Impede acesso direto
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Renderiza o pop-up do formulário
 * Este arquivo é chamado diretamente pelo widget Elementor.
 */
?>

<div class="fpp-popup-overlay" style="display: none;">
    <div class="fpp-popup">
        <h2><?php _e('Preencha seus dados', 'formulario-pagamento-popup'); ?></h2>
        <form id="fpp-form">
            <?php wp_nonce_field('fpp_nonce', 'nonce'); ?>
            <input type="text" id="fpp-nome" name="nome" placeholder="<?php esc_attr_e('Seu nome', 'formulario-pagamento-popup'); ?>" required>
            <input type="email" id="fpp-email" name="email" placeholder="<?php esc_attr_e('Seu e-mail', 'formulario-pagamento-popup'); ?>" required>
            <input type="tel" id="fpp-telefone" name="telefone" placeholder="<?php esc_attr_e('Seu telefone', 'formulario-pagamento-popup'); ?>" required>
            <button type="submit"><?php _e('Finalizar Compra', 'formulario-pagamento-popup'); ?></button>
            <p id="fpp-mensagem" style="margin-top:10px;"></p>
        </form>
    </div>
</div>
