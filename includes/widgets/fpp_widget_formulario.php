<?php
if (!defined('ABSPATH')) exit;

class FPP_Widget_Formulario extends \Elementor\Widget_Base {

    public function get_name() {
        return 'fpp_formulario_popup';
    }

    public function get_title() {
        return __('Formulário Pagamento Popup', 'formulario-pagamento-popup');
    }

    public function get_icon() {
        return 'eicon-popup';
    }

    public function get_categories() {
        return ['general'];
    }

    protected function _register_controls() {
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Conteúdo', 'formulario-pagamento-popup'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'button_text',
            [
                'label' => __('Texto do Botão', 'formulario-pagamento-popup'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Abrir Formulário', 'formulario-pagamento-popup'),
            ]
        );

        $this->add_control(
            'popup_title',
            [
                'label' => __('Título do Popup', 'formulario-pagamento-popup'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Preencha seus dados', 'formulario-pagamento-popup'),
            ]
        );

        $this->add_control(
            'placeholder_nome',
            [
                'label' => __('Placeholder Nome', 'formulario-pagamento-popup'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Seu nome', 'formulario-pagamento-popup'),
            ]
        );

        $this->add_control(
            'placeholder_email',
            [
                'label' => __('Placeholder Email', 'formulario-pagamento-popup'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Seu e-mail', 'formulario-pagamento-popup'),
            ]
        );

        $this->add_control(
            'placeholder_telefone',
            [
                'label' => __('Placeholder Telefone', 'formulario-pagamento-popup'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Seu telefone', 'formulario-pagamento-popup'),
            ]
        );

        $this->add_control(
            'button_submit_text',
            [
                'label' => __('Texto do Botão Enviar', 'formulario-pagamento-popup'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Finalizar Compra', 'formulario-pagamento-popup'),
            ]
        );

        $this->end_controls_section();
    }

    public function render() {
        $settings = $this->get_settings_for_display();

        echo '<button class="fpp-abrir-popup">' . esc_html($settings['button_text']) . '</button>';

        echo '<div class="fpp-popup-overlay" style="display:none;">
            <div class="fpp-popup">
                <h2>' . esc_html($settings['popup_title']) . '</h2>
                <form id="fpp-form">
                    <input type="hidden" name="nonce" value="' . wp_create_nonce('fpp_nonce') . '">
                    <input type="text" name="nome" placeholder="' . esc_attr($settings['placeholder_nome']) . '" required>
                    <input type="email" name="email" placeholder="' . esc_attr($settings['placeholder_email']) . '" required>
                    <input type="tel" name="telefone" placeholder="' . esc_attr($settings['placeholder_telefone']) . '" required>
                    <button type="submit">' . esc_html($settings['button_submit_text']) . '</button>
                    <p id="fpp-mensagem" style="margin-top:10px;"></p>
                </form>
            </div>
        </div>';
    }
}
