<?php

use CE\Controls_Manager;
use CE\Group_Control_Typography;
use CE\Repeater;
use CE\Widget_Base;

if (!defined('_PS_VERSION_')) {
    exit;
}

class EscadoteAnnouncementBar extends Widget_Base
{
    public function get_name()
    {
        return 'escadote_announcement_bar';
    }

    public function get_title()
    {
        return 'Escadote Announcement Bar';
    }

    public function get_icon()
    {
        return 'eicon-slider-push';
    }

    public function get_categories()
    {
        return ['escadote'];
    }

    protected function register_controls()
    {
        $this->start_controls_section(
            'section_content',
            [
                'label' => 'Conteúdo',
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();
        $repeater->add_control(
            'item_text',
            [
                'label' => 'Texto',
                'type' => Controls_Manager::TEXT,
                'default' => 'Envios grátis para compras acima de 50€',
                'label_block' => true,
            ]
        );
        $repeater->add_control(
            'item_icon',
            [
                'label' => 'Ícone',
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-bullhorn',
                    'library' => 'fa-solid',
                ],
            ]
        );

        $this->add_control(
            'items',
            [
                'label' => 'Itens',
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'item_text' => 'Promoções relâmpago todas as semanas',
                    ],
                    [
                        'item_text' => 'Portes grátis para Portugal Continental',
                    ],
                ],
                'title_field' => '{{{ item_text }}}',
            ]
        );

        $this->add_control(
            'direction',
            [
                'label' => 'Direção',
                'type' => Controls_Manager::SELECT,
                'default' => 'left',
                'options' => [
                    'left' => 'Direita para esquerda',
                    'right' => 'Esquerda para direita',
                ],
            ]
        );

        $this->add_control(
            'speed',
            [
                'label' => 'Velocidade',
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['s'],
                'range' => [
                    's' => [
                        'min' => 5,
                        'max' => 120,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 's',
                    'size' => 30,
                ],
            ]
        );

        $this->add_control(
            'pause_on_hover',
            [
                'label' => 'Pausar ao passar o rato',
                'type' => Controls_Manager::SWITCHER,
                'label_on' => 'Sim',
                'label_off' => 'Não',
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style',
            [
                'label' => 'Estilo',
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'background_color',
            [
                'label' => 'Cor de fundo',
                'type' => Controls_Manager::COLOR,
                'default' => '#111111',
                'selectors' => [
                    '{{WRAPPER}} .escadote-marquee' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'text_color',
            [
                'label' => 'Cor do texto',
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .escadote-marquee' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'text_typography',
                'label' => 'Tipografia',
                'selector' => '{{WRAPPER}} .escadote-marquee__item',
            ]
        );

        $this->add_responsive_control(
            'icon_size',
            [
                'label' => 'Tamanho do ícone',
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 60,
                    ],
                ],
                'default' => [
                    'size' => 16,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .escadote-marquee__item i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .escadote-marquee__item svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'item_gap',
            [
                'label' => 'Espaçamento entre itens',
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 8,
                        'max' => 120,
                    ],
                ],
                'default' => [
                    'size' => 40,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .escadote-marquee__track' => '--escadote-item-gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $items = !empty($settings['items']) ? $settings['items'] : [];

        if (empty($items)) {
            return;
        }

        $speed = isset($settings['speed']['size']) ? (int) $settings['speed']['size'] : 30;
        $direction = !empty($settings['direction']) && $settings['direction'] === 'right' ? 'reverse' : 'normal';
        $pauseClass = !empty($settings['pause_on_hover']) && $settings['pause_on_hover'] === 'yes'
            ? 'escadote-marquee--pause'
            : '';

        $this->add_render_attribute('wrapper', 'class', 'escadote-marquee ' . $pauseClass);
        $this->add_render_attribute('wrapper', 'style', '--escadote-duration: ' . max(5, $speed) . 's;');
        $this->add_render_attribute('wrapper', 'style', '--escadote-direction: ' . $direction . ';');

        echo '<div ' . $this->get_render_attribute_string('wrapper') . '>';
        echo '<div class="escadote-marquee__viewport">';
        echo '<div class="escadote-marquee__track">';

        for ($copy = 0; $copy < 2; ++$copy) {
            foreach ($items as $item) {
                echo '<span class="escadote-marquee__item">';
                if (!empty($item['item_icon']['value'])) {
                    \CE\Icons_Manager::render_icon($item['item_icon'], ['aria-hidden' => 'true']);
                }
                echo '<span class="escadote-marquee__text">' . esc_html($item['item_text']) . '</span>';
                echo '</span>';
            }
        }

        echo '</div>';
        echo '</div>';
        echo '</div>';
    }

    protected function _content_template()
    {
        ?>
        <#
        var items = settings.items || [];
        var speed = settings.speed && settings.speed.size ? settings.speed.size : 30;
        var direction = settings.direction === 'right' ? 'reverse' : 'normal';
        var pauseClass = settings.pause_on_hover === 'yes' ? 'escadote-marquee--pause' : '';
        #>
        <div class="escadote-marquee {{ pauseClass }}" style="--escadote-duration: {{ Math.max(5, speed) }}s; --escadote-direction: {{ direction }};">
            <div class="escadote-marquee__viewport">
                <div class="escadote-marquee__track">
                    <# for (var copy = 0; copy < 2; copy++) { #>
                        <# _.each(items, function(item) { #>
                            <span class="escadote-marquee__item">
                                <# if (item.item_icon && item.item_icon.value) { #>
                                    <i class="{{ item.item_icon.value }}" aria-hidden="true"></i>
                                <# } #>
                                <span class="escadote-marquee__text">{{{ item.item_text }}}</span>
                            </span>
                        <# }); #>
                    <# } #>
                </div>
            </div>
        </div>
        <?php
    }
}
