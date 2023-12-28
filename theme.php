<?php
/*+*************************************************************************************
 * The contents of this file are subject to the VTECRM License Agreement
 * ("licenza.txt"); You may not use this file except in compliance with the License
 * The Original Code is: VTECRM
 * The Initial Developer of the Original Code is VTECRM LTD.
 * Portions created by VTECRM LTD are Copyright (C) VTECRM LTD.
 * All Rights Reserved.
 ***************************************************************************************/

// crmv@119414
// crmv@227309

require_once('include/BaseClasses.php');

class ThemeConfig extends OptionableClass {
	
	protected $options = array(
		'handle_contestual_buttons' => true, // Use this flag if the theme uses contextual buttons (theme without header buttons) // crmv@190519
		'lateral_left_menu' => true,
		'lateral_right_menu' => false,
		'enable_html5_doctype' => true,
		'enable_viewport_meta' => true,
		'old_style_buttons' => false,
		'login_get_logo_mode' => 'login',
	);
	
	protected $excludedMenuModules = ['Home', 'Talks', 'OCRBCard', 'OCRReceipt', 'OCRPdf']; // crmv@232399 crmv@232524

	private $_cacheQueryParams = [];

	public function setThemeVars(&$smarty, $options = []) {
		$mainMenu = $this->getMainMenu($_REQUEST);
		$smarty->assign("MAIN_MENU", $mainMenu);
	}

	protected function getMainMenu($route) {
		$mainMenu = [];
		
		$mainMenu[] = $this->getHomeMenu($route);
		$mainMenu[] = $this->getMenuDivider();
		
		// crmv@233288
		if (!$this->isSettingsPage($route)) {
			$mainMenu = array_merge($mainMenu, $this->getFavoriteMenu($route)); // crmv@232399
			$mainMenu[] = $this->getMenuDivider();
		}
		// crmv@233288e
		
		// qlikiframe - start
		// Aggiunta voce Analisi Custom nel menu
		$mainMenu[] = $this->getAnalisiCustomMenu($route);
		$mainMenu[] = $this->getMenuDivider();
		// qlikiframe - end

		$mainMenu[] = $this->getModulesMenu($route);
		$mainMenu[] = $this->getMenuDivider();
		

		$mainMenu = array_merge($mainMenu, $this->getSettingsMenu($route));
		
		$this->findMenuSelectedItem($mainMenu, $route);
		
		return $mainMenu;
	}

	// qlikiframe - start
	// Menu custom per Analisi Qlik
	protected function getAnalisiCustomMenu() {

		// Inizializzo l'array e creo un elemento non visibile per creare la tendina
		$items = [];
		$items[] = [
			'id' => 'first-item',
			'text' => 'first-item',
			'type' => 'menu-item',
			'selected' => false,
			'visible' => false,
		];
		
		// Creo il menu effettivo
		$modulesMenu = [
			'id' => 'analisi_custom',
			'text' => 'Analisi Custom',
			'type' => 'menu-item',
			'submenu' => $items,
			'selected' => false,
			'visible' => true,
			'prefix' => [
				'type' => 'icon',
				'icon_style' => 'material',
				'is_module' => true,
				'icon_name' => 'icon-analisicustom',
				'module_first_letter' => 'A'
			]
		];
		
		return $modulesMenu;
	}
	// qlikiframe - end

	protected function findMenuSelectedItem(&$mainMenu, $route) {
		$itemSelected = false;
		
		if (isset($route['module']) && isset($route['action']) && isset($route['mode'])) {
			$itemSelected = $this->recursiveFindMenuSelectedItem($mainMenu, $route, function($menuItem, $itemQueryParams, $route) {
				$actionType = $menuItem['action']['type'] ?? '';
				if ($actionType === 'link') {
					$routeModule = $route['module'] ?? '';
					$routeAction = $route['action'] ?? '';
					$routeMode = $route['mode'] ?? '';
					return $routeModule === $itemQueryParams['module'] && $routeAction === $itemQueryParams['action'] && $routeMode === $itemQueryParams['mode'];
				}
				return false;
			});
		}
		
		if (!$itemSelected && isset($route['module']) && isset($route['action'])) {
			$itemSelected = $this->recursiveFindMenuSelectedItem($mainMenu, $route, function($menuItem, $itemQueryParams, $route) {
				$actionType = $menuItem['action']['type'] ?? '';
				if ($actionType === 'link') {
					$routeModule = $route['module'] ?? '';
					$routeAction = $route['action'] ?? '';
					return $routeModule === $itemQueryParams['module'] && $routeAction === $itemQueryParams['action'];
				}
				return false;
			});
		}
		
		if (!$itemSelected && isset($route['module'])) {
			$this->recursiveFindMenuSelectedItem($mainMenu, $route, function($menuItem, $itemQueryParams, $route) {
				$actionType = $menuItem['action']['type'] ?? '';
				if ($actionType === 'link') {
					$routeModule = $route['module'] ?? '';
					return $routeModule === $itemQueryParams['module'] && $itemQueryParams['action'] === 'index';
				}
				return false;
			});
		}
	}
	
	protected function recursiveFindMenuSelectedItem(&$mainMenu, $route, $checkFn, $level = 0) {
		$itemSelected = false;
		
		foreach ($mainMenu as &$menuItem) {
			if (isset($menuItem['submenu']) && !empty($menuItem['submenu'])) {
				$itemSelected = $this->recursiveFindMenuSelectedItem($menuItem['submenu'], $route, $checkFn, ($level+1));
				$menuItem['selected'] = $itemSelected;
				if ($itemSelected) break;
			} else {
				if (is_callable($checkFn)) {
					$link = $menuItem['action']['link_href'] ?? '';
					$itemQueryParams = $this->getQueryParams($link);
					$itemSelected = $checkFn($menuItem, $itemQueryParams, $route);
					if ($itemSelected) {
						$menuItem['selected'] = true;
						break;
					}
				}
			}
		}
		
		return $itemSelected;
	}
	
	protected function getQueryParams($url) {
		if (!isset($this->_cacheQueryParams[$url])) {
			$itemQueryParams = [];
			
			$urlQueryString = parse_url($url, PHP_URL_QUERY);
			if ($urlQueryString !== false) {
				parse_str($urlQueryString, $itemQueryParams);
			}
			
			$this->_cacheQueryParams[$url] = $itemQueryParams;
		}
		return $this->_cacheQueryParams[$url];
	}
	
	protected function getMenuDivider() {
		return [
			'type' => 'divider',
		];
	}
	
	protected function getHomeMenu($route) {
		return [
			'id' => 'home',
			'text' => getTranslatedString('Home'),
			'type' => 'menu-item',
			'selected' => false,
			'visible' => true,
			'prefix' => [
				'type' => 'icon',
				'icon_style' => 'material',
				'icon_name' => 'home'
			],
			'action' => [
				'type' => 'link',
				'link_href' => 'index.php?module=Home&action=index'
			],
		];
	}
	
	// crmv@232399
	protected function getFavoriteMenu($route) {
		$items = [];
		
		$menuModuleList = getMenuModuleList(false);
		$fastModuleList = $menuModuleList[0] ?? [];
		
		foreach ($fastModuleList as $module) {
			if (in_array($module['name'], $this->excludedMenuModules)) continue;
			
			$items[] = [
				'id' => '',
				'text' => $module['translabel'],
				'type' => 'menu-item',
				'selected' => false,
				'visible' => true,
				'action' => [
					'type' => 'link',
					'link_href' => $module['index_url']
				],
				'prefix' => [
					'type' => 'icon',
					'icon_style' => 'material',
					'is_module' => true,
					'icon_name' => 'icon-' . strtolower($module['name']),
					'module_first_letter' => strtoupper(substr($module['translabel'], 0, 1)),
				]
			];
		}
		
		return $items;
	}
	// crmv@232399e

	protected function getModulesMenu($route) {
		$areaManager = AreaManager::getInstance();
		$areaModuleList = $areaManager->getModuleList()[1] ?? []; // crmv@230048 crmv@232524

		$items = [];

		foreach ($areaModuleList as $area) {
			$modules = [];
			$modulesInfo = $area['info']['info'] ?? [];
			
			foreach ($modulesInfo as $module) {
				if (in_array($module['name'], $this->excludedMenuModules)) continue;

				$modules[] = [
					'id' => '',
					'text' => $module['translabel'],
					'type' => 'menu-item',
					'selected' => false,
					'visible' => true,
					'action' => [
						'type' => 'link',
						'link_href' => $module['index_url']
					]
				];
			}
			
			$items[] = [
				'id' => '',
				'text' => $area['info']['translabel'],
				'type' => 'menu-item',
				'submenu' => $modules,
				'selected' => false,
				'visible' => true,
			];
		}
		
		$modules = [];
		$menuModuleList = getMenuModuleList(true);
		$allModulesList = $menuModuleList[1] ?? [];
		
		foreach ($allModulesList as $module) {
			if (in_array($module['name'], $this->excludedMenuModules)) continue;

			$modules[] = [
				'id' => '',
				'text' => $module['translabel'],
				'type' => 'menu-item',
				'selected' => false,
				'visible' => true,
				'action' => [
					'type' => 'link',
					'link_href' => $module['index_url']
				]
			];
		}
		
		$items[] = [
			'id' => 'allmodules',
			'text' => getTranslatedString('LBL_ALL'),
			'type' => 'menu-item',
			'submenu' => $modules,
			'selected' => false,
			'visible' => true
		];
		
		$modulesMenu = [
			'id' => 'modules',
			'text' => getTranslatedString('LBL_MODULES'),
			'type' => 'menu-item',
			'submenu' => $items,
			'selected' => false,
			'visible' => true,
			'prefix' => [
				'type' => 'icon',
				'icon_style' => 'material',
				'icon_name' => 'view_module'
			]
		];
		
		return $modulesMenu;
	}

	protected function getSettingsMenu($route) {
		global $current_user;

		$items = [];
		
		$isSettingsPage = $this->isSettingsPage($route);
		
		if ($isSettingsPage) {
			SettingsUtils::resetMenuState();

			$blocks = SettingsUtils::getBlocks();
			$fields =  SettingsUtils::getFields();

			foreach ($blocks as $blockId => $block) {
				$blocklabel = $block['label'] ?? '';
				if ($block['label'] === 'LBL_MODULE_MANAGER') continue;

				$blockTransLabel = getTranslatedString($blocklabel, 'Settings');
				$blockImage = $block['image'] ?? '';
				$imageType = $block['image_type'] ?? '';

				$prefix = null;
				if ($imageType === 'icon') {
					$prefix = [
						'type' => 'icon',
						'icon_style' => 'material',
						'icon_name' => $blockImage,
					];
				}

				$subitems = [];

				$blockFields = $fields[$blockId] ?? [];
				foreach ($blockFields as $data) {
					$link = $data['link'] ?? '';
					if (empty($link)) continue;

					$blockFieldName = $data['name'] ?? '';
					$blockFieldTransName = getTranslatedString($blockFieldName, 'Settings');

					$subitems[] = [
						'id' => '',
						'text' => $blockFieldTransName,
						'type' => 'menu-item',
						'selected' => false,
						'visible' => true,
						'action' => [
							'type' => 'link',
							'link_href' => "{$link}&reset_session_menu=true",
						]
					];
				}


				$items[] = [
					'id' => '',
					'text' => $blockTransLabel,
					'type' => 'menu-item',
					'submenu' => $subitems,
					'selected' => false,
					'visible' => true,
					'expanded' => true,
					'prefix' => $prefix,
				];
			}

			return $items;
		}
		
		return [
			[
				'id' => 'settings',
				'text' => getTranslatedString('LBL_SETTINGS', 'Settings'),
				'type' => 'menu-item',
				'selected' => false,
				'visible' => !boolval(VteSession::get('MorphsuitZombie')) && boolval(is_admin($current_user)),
				'expanded' => !empty($items),
				'prefix' => [
					'type' => 'icon',
					'icon_style' => 'material',
					'icon_name' => 'settings'
				],
				'action' => [
					'type' => 'link',
					'link_href' => 'index.php?module=Administration&action=index&parenttab=Settings&reset_session_menu_tab=true'
				]
			]
		];
	}

	// crmv@231485
	function isSupportedAreaModule($module) {
		return true;
	}
	// crmv@231485e

	public function isSettingsPage($route) {
		$moduleName = vtlib_purify($route['module']);
		$parentTab = vtlib_purify($route['parenttab']);
		
		if (in_array($moduleName, ['Settings', 'Administration', 'com_vtiger_workflow']) || $parentTab === 'Settings') {
			return true;
		}
		
		return false;
	}
}
