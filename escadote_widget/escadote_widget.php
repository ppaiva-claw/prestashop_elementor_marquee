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

require_once __DIR__ . '/classes/Widget/EscadoteAnnouncementBar.php';

class Escadote_Widget extends Module
{
    public function __construct()
    {
        $this->name = 'escadote_widget';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Escadote';
        $this->need_instance = 0;
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->trans('Escadote Widget', [], 'Modules.EscadoteWidget.Admin');
        $this->description = $this->trans(
            'Adds a Creative Elements announcement bar widget with marquee effect.',
            [],
            'Modules.EscadoteWidget.Admin'
        );

        $this->ps_versions_compliancy = [
            'min' => '1.7.8.0',
            'max' => _PS_VERSION_,
        ];
    }

    public function install()
    {
        return parent::install()
            && $this->registerHook('actionCreativeElementsInit')
            && $this->registerHook('actionFrontControllerSetMedia');
    }

    public function uninstall()
    {
        return parent::uninstall();
    }

    public function hookActionCreativeElementsInit(array $params)
    {
        if (!class_exists('CE\\Widget_Base')) {
            return;
        }

        $widgetsManager = null;

        if (isset($params['widgets_manager'])) {
            $widgetsManager = $params['widgets_manager'];
        } elseif (isset($params['manager'])) {
            $widgetsManager = $params['manager'];
        }

        if (!$widgetsManager) {
            return;
        }

        $categoryArgs = [
            'title' => 'Escadote',
            'icon' => 'fa fa-bullhorn',
        ];

        if (method_exists($widgetsManager, 'add_category')) {
            $widgetsManager->add_category('escadote', $categoryArgs);
        } elseif (method_exists($widgetsManager, 'addCategory')) {
            $widgetsManager->addCategory('escadote', $categoryArgs);
        }

        $widget = new EscadoteAnnouncementBar();

        if (method_exists($widgetsManager, 'register_widget_type')) {
            $widgetsManager->register_widget_type($widget);
        } elseif (method_exists($widgetsManager, 'register')) {
            $widgetsManager->register($widget);
        }
    }

    public function hookActionFrontControllerSetMedia()
    {
        $this->context->controller->registerStylesheet(
            'module-escadote-widget-marquee',
            'modules/' . $this->name . '/views/css/announcement-bar.css',
            [
                'media' => 'all',
                'priority' => 150,
            ]
        );
    }
}
