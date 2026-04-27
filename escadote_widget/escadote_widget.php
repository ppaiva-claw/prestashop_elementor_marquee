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
     * Hooks usados por diferentes versões do Creative Elements para registar widgets.
     *
     * @var string[]
     */
    private $creativeElementsWidgetHooks = [
        'actionCreativeElementsInit',
        'actionCreativeElementsRegisterWidgets',
        'actionCreativeElementsWidgetsRegistered',
    ];

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
        if (!parent::install()) {
            return false;
        }

        if (!$this->registerCreativeElementsHooks()) {
            return false;
        }

        return $this->registerHook('actionFrontControllerSetMedia');
    }

    public function uninstall()
    {
        return parent::uninstall();
    }

    public function hookActionCreativeElementsInit(array $params)
    {
        $this->registerCreativeElementsWidget($params);
    }

    public function hookActionCreativeElementsRegisterWidgets(array $params)
    {
        $this->registerCreativeElementsWidget($params);
    }

    public function hookActionCreativeElementsWidgetsRegistered(array $params)
    {
        $this->registerCreativeElementsWidget($params);
    }

    /**
     * Regista o widget no manager do Creative Elements.
     */
    private function registerCreativeElementsWidget(array $params)
    {
        if (!class_exists('CE\\Widget_Base')) {
            if (!class_exists('Elementor\\Widget_Base')) {
                return;
            }

            $aliases = [
                'Widget_Base',
                'Controls_Manager',
                'Group_Control_Typography',
                'Repeater',
                'Icons_Manager',
            ];

            foreach ($aliases as $alias) {
                $ceClass = 'CE\\' . $alias;
                $elementorClass = 'Elementor\\' . $alias;

                if (!class_exists($ceClass) && class_exists($elementorClass)) {
                    class_alias($elementorClass, $ceClass);
                }
            }
        }

        require_once __DIR__ . '/classes/Widget/EscadoteAnnouncementBar.php';

        $widgetsManager = $this->resolveWidgetsManager($params);

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
        } elseif (method_exists($widgetsManager, 'registerWidgetType')) {
            $widgetsManager->registerWidgetType($widget);
        }
    }

    /**
     * Regista hooks CE disponíveis nesta instalação.
     */
    private function registerCreativeElementsHooks()
    {
        $registered = false;

        foreach ($this->creativeElementsWidgetHooks as $hookName) {
            if ((int) Hook::getIdByName($hookName) <= 0) {
                continue;
            }

            $registered = $this->registerHook($hookName) || $registered;
        }

        return $registered;
    }

    /**
     * Resolve o widgets manager independentemente da chave usada no hook.
     *
     * @return object|null
     */
    private function resolveWidgetsManager(array $params)
    {
        $candidateKeys = [
            'widgets_manager',
            'manager',
            'widgetsManager',
        ];

        foreach ($candidateKeys as $key) {
            if (!isset($params[$key])) {
                continue;
            }

            $candidate = $params[$key];

            if (is_object($candidate) && (
                method_exists($candidate, 'register_widget_type')
                || method_exists($candidate, 'register')
                || method_exists($candidate, 'registerWidgetType')
            )) {
                return $candidate;
            }
        }

        return null;
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
