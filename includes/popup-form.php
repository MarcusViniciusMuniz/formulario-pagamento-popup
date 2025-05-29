<?php
if (!defined('ABSPATH')) exit;

function fpp_render_popup_form() {
    ?>
    <div class="fpp-popup-overlay" style="display: none;">
        <div class="fpp-popup">
            <h2><?php _e('Preencha seus dados', 'formulario-pagamento-popup'); ?></h2>
            <form id="fpp-form">
                <?php wp_nonce_field('fpp_nonce', 'nonce'); ?>
                <input type="text" name="nome" placeholder="<?php _e('Seu nome', 'formulario-pagamento-popup'); ?>" required>
                <input type="email" name="email" placeholder="<?php _e('Seu e-mail', 'formulario-pagamento-popup'); ?>" required>
                <input type="tel" name="telefone" placeholder="<?php _e('Seu telefone', 'formulario-pagamento-popup'); ?>" required>
                <button type="submit"><?php _e('Finalizar Compra', 'formulario-pagamento-popup'); ?></button>
                <p id="fpp-mensagem"></p>
            </form>
        </div>
    </div>
    <?php
}
