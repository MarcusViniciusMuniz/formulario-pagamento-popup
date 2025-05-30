<?php
if (!defined('ABSPATH')) exit;

class FPP_Widget_Formulario extends \Elementor\Widget_Base {

    public function get_name() {
        return 'fpp_formulario_popup';
    }

    public function get_title() {
        return __('FPP Formulário Popup', 'formulario-pagamento-popup');
    }

    public function get_icon() {
        return 'eicon-popup';
    }

    public function get_categories() {
        return ['general'];
    }

    protected function _register_controls() {

        /**
         * 🔹 Aba: Conteúdo
         */
        $this->start_controls_section('content_section', [
            'label' => __('Conteúdo', 'formulario-pagamento-popup'),
            'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
        ]);

        $this->add_control('popup_title', [
            'label' => __('Título do Formulário', 'formulario-pagamento-popup'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => __('Preencha seus dados', 'formulario-pagamento-popup'),
        ]);

        $this->add_control('placeholder_nome', [
            'label' => __('Placeholder Nome', 'formulario-pagamento-popup'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => __('Seu nome', 'formulario-pagamento-popup'),
        ]);

        $this->add_control('placeholder_email', [
            'label' => __('Placeholder E-mail', 'formulario-pagamento-popup'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => __('Seu e-mail', 'formulario-pagamento-popup'),
        ]);

        $this->add_control('placeholder_telefone', [
            'label' => __('Placeholder Telefone', 'formulario-pagamento-popup'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => __('Seu telefone', 'formulario-pagamento-popup'),
        ]);

        $this->add_control('submit_button_text', [
            'label' => __('Texto do Botão de Envio', 'formulario-pagamento-popup'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => __('Finalizar Compra', 'formulario-pagamento-popup'),
        ]);

        $this->add_control('button_text', [
            'label' => __('Texto do Botão (Abrir Popup)', 'formulario-pagamento-popup'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => __('Abrir Formulário de Pagamento', 'formulario-pagamento-popup'),
        ]);

        $this->end_controls_section();

        /**
         * 🔹 Aba: Estilo do Popup
         */
        $this->start_controls_section('popup_style_section', [
            'label' => __('Estilo do Popup', 'formulario-pagamento-popup'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('popup_background_color', [
            'label' => __('Cor de Fundo', 'formulario-pagamento-popup'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .fpp-popup' => 'background-color: {{VALUE}};',
            ],
        ]);

        $this->add_control('popup_background_image', [
            'label' => __('Imagem de Fundo', 'formulario-pagamento-popup'),
            'type' => \Elementor\Controls_Manager::MEDIA,
            'selectors' => [
                '{{WRAPPER}} .fpp-popup' => 'background-image: url({{URL}}); background-size: cover; background-position: center;',
            ],
        ]);

        $this->add_control('popup_border_radius', [
            'label' => __('Borda Arredondada', 'formulario-pagamento-popup'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'range' => ['px' => ['min' => 0, 'max' => 50]],
            'selectors' => [
                '{{WRAPPER}} .fpp-popup' => 'border-radius: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_control('popup_box_shadow', [
            'label' => __('Sombra', 'formulario-pagamento-popup'),
            'type' => \Elementor\Controls_Manager::BOX_SHADOW,
            'selectors' => [
                '{{WRAPPER}} .fpp-popup' => '{{VALUE}}',
            ],
        ]);

        $this->add_responsive_control('popup_padding', [
            'label' => __('Padding Interno', 'formulario-pagamento-popup'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'selectors' => [
                '{{WRAPPER}} .fpp-popup' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->add_responsive_control('popup_max_width', [
            'label' => __('Largura Máxima (px)', 'formulario-pagamento-popup'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'range' => ['px' => ['min' => 200, 'max' => 1000]],
            'selectors' => [
                '{{WRAPPER}} .fpp-popup' => 'max-width: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_responsive_control('popup_max_height', [
            'label' => __('Altura Máxima (px)', 'formulario-pagamento-popup'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'range' => ['px' => ['min' => 200, 'max' => 1000]],
            'selectors' => [
                '{{WRAPPER}} .fpp-popup' => 'max-height: {{SIZE}}{{UNIT}}; overflow: auto;',
            ],
        ]);

        $this->end_controls_section();

        /**
         * 🔹 Aba: Estilo do Título
         */
        $this->start_controls_section('title_style_section', [
            'label' => __('Estilo do Título', 'formulario-pagamento-popup'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('title_color', [
            'label' => __('Cor do Título', 'formulario-pagamento-popup'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .fpp-popup h2' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .fpp-popup h2',
            ]
        );

        $this->end_controls_section();

        /**
         * 🔹 Aba: Estilo do Botão (Abrir Popup)
         */
        $this->start_controls_section('button_style_section', [
            'label' => __('Estilo do Botão', 'formulario-pagamento-popup'),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('button_color', [
            'label' => __('Cor do Botão', 'formulario-pagamento-popup'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .fpp-abrir-popup' => 'background-color: {{VALUE}}; color: #fff;',
            ],
        ]);

        $this->add_control('button_border_radius', [
            'label' => __('Borda Arredondada', 'formulario-pagamento-popup'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'range' => ['px' => ['min' => 0, 'max' => 50]],
            'selectors' => [
                '{{WRAPPER}} .fpp-abrir-popup' => 'border-radius: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'button_typography',
                'selector' => '{{WRAPPER}} .fpp-abrir-popup',
            ]
        );

        $this->end_controls_section();
    }

    public function render() {
        $settings = $this->get_settings_for_display();

        echo '<button class="fpp-abrir-popup">' . esc_html($settings['button_text']) . '</button>';

        ?>
        <div class="fpp-popup-overlay" style="display: none;">
            <div class="fpp-popup">
                <h2><?php echo esc_html($settings['popup_title']); ?></h2>
                <form id="fpp-form">
                    <?php wp_nonce_field('fpp_nonce', 'nonce'); ?>
                    <input type="text" id="fpp-nome" name="nome" placeholder="<?php echo esc_attr($settings['placeholder_nome']); ?>" required>
                    <input type="email" id="fpp-email" name="email" placeholder="<?php echo esc_attr($settings['placeholder_email']); ?>" required>
                    <input type="tel" id="fpp-telefone" name="telefone" placeholder="<?php echo esc_attr($settings['placeholder_telefone']); ?>" required>
                    <button type="submit"><?php echo esc_html($settings['submit_button_text']); ?></button>
                    <p id="fpp-mensagem" style="margin-top:10px;"></p>
                </form>
            </div>
        </div>
        <?php
    }
}
