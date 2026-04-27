<?php
/**
 * 2007-2026 PrestaShop.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 */


if (!defined('_PS_VERSION_')) {
    exit;
}

class Escadote_Widget extends Module
{

    /**
     * Module Constructor
     *
     * @param string $name Module unique name
     * @param Context $context
     */
    public function __construct($name = null, Context $context = null)
    {
        $this->name = 'escadote_widget';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Escadote';
        $this->need_instance = 0;
        $this->bootstrap = true;        

        $this->displayName = $this->trans('Escadote Widget', [], 'Modules.EscadoteWidget.Admin');
        $this->description = $this->trans(
            'Adds a Creative Elements widgets.',
            [],
            'Modules.EscadoteWidget.Admin'
        );

        $this->ps_versions_compliancy = [
            'min' => '1.7.8.0',
            'max' => _PS_VERSION_,
        ];

        parent::__construct();
    }

    public function install()
    {
        return parent::install() && $this->registerHook('actionCreativeElementsInit');
    }

    public function uninstall()
    {
        return parent::uninstall();
    }

/**
     * Add module to Creative Elements' actions
     */
    public function hookActionCreativeElementsInit()
    {
        CE\add_action('elementor/elements/categories_registered', [$this, 'registerCategory']);

        CE\add_action('elementor/widgets/widgets_registered', [$this, 'registerWidget']);
    }

    /**
     * Register a custom widget category
     */
    public function registerCategory($elements_manager)
    {
        $elements_manager->addCategory('escadote', [
            'title' => $this->l('Escadote'),
        ]);
    }

    /**
     * Include and register a widget
     */
    public function registerWidget($widgets_manager)
    {
        include _PS_MODULE_DIR_ . $this->name . '/classes/widget_marquee.php';

        $widgets_manager->registerWidgetType(new EscadoteWidgets\WidgetMarquee());
    }
}
