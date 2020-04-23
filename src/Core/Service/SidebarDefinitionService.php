<?php

namespace WS\Core\Service;

use WS\Core\Library\Sidebar\SidebarDefinition;
use WS\Core\Library\Sidebar\SidebarDefinitionInterface;

class SidebarDefinitionService implements SidebarDefinitionInterface
{
    protected $settingService;

    public function __construct(SettingService $settingService)
    {
        $this->settingService = $settingService;
    }

    public function getSidebarDefinition(): array
    {
        // translation menu
        $translationsNode = new SidebarDefinition(
            'translations',
            'menu',
            [
                'route_name' => 'ws_translation_index'
            ],
            [
                'roles' => ['ROLE_WS_TRANSLATION'],
                'translation_domain' => 'ws_cms_translation',
                'icon' => 'fa-language',
                'collapsed_routes' => ['ws_translation_'],
                'order' => 5
            ]
        );

        // settings menu
        $settingsNode = new SidebarDefinition(
            'settings',
            'menu',
            null,
            [
                'container' => SidebarDefinition::SIDEBAR_CONTAINER,
                'translation_domain' => 'ws_cms_setting',
                'icon' => 'fa-cogs',
                'collapsed_routes' => ['ws_setting_'],
                'order' => 6
            ]
        );

        // settings section menu
        foreach ($this->settingService->getSections() as $settingSectionDefinition) {

            // sectionCode menu
            $settingsNode->addChild(new SidebarDefinition(
                $settingSectionDefinition->getCode(),
                $settingSectionDefinition->getName(),
                [
                    'route_name' => 'ws_setting_index',
                    'route_options' => ['section' => $settingSectionDefinition->getCode()]
                ],
                [
                    'roles' => [$settingSectionDefinition->getRole()],
                    'translation_domain' => $settingSectionDefinition->getTranslationDomain(),
                    'icon' => $settingSectionDefinition->getIcon(),
                    'collapsed_routes' => ['ws_setting_'],
                    'order' => $settingSectionDefinition->getOrder()
                ]
            ));
        }

        // administrator menu
        $administratorsNode = new SidebarDefinition(
            'administrators',
            'menu',
            [
                'route_name' => 'ws_administrator_index'
            ],
            [
                'roles' => ['ROLE_WS_ADMINISTRATOR'],
                'translation_domain' => 'ws_cms_administrator',
                'icon' => 'fa-user-shield',
                'collapsed_routes' => ['ws_administrator_'],
                'order' => 7
            ]
        );

        return [
            $translationsNode,
            $settingsNode,
            $administratorsNode
        ];
    }
}
