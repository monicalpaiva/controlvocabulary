<?php

/**
 * @file controlvocabularyPlugin.inc.php
 *
 * Copyright (c) 2013-2021 Simon Fraser University Library
 * Copyright (c) 2003-2021 John Willinsky
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class controlvocabularyPlugin
 * @ingroup plugins_generic_controlvocabulary
 * @brief controlvocabulary plugin class
 * 
 */
namespace APP\plugins\generic\controlvocabulary;

use APP\core\Application;
use PKP\plugins\HookRegistry;
use PKP\plugins\GenericPlugin;
use PKP\submission\PKPSubmission;

import('lib.pkp.classes.plugins.GenericPlugin');

class controlvocabularyPlugin extends GenericPlugin {
	public function register($category, $path, $mainContextId = NULL) {

		// Register the plugin even when it is not enabled
		$success = parent::register($category, $path);

		if ($success && $this->getEnabled()) {
			// Do something when the plugin is enabled
		}

		return $success;
	}

	/**
	 * Name for this plugin
	 * Description for this plugin
	 */

    function getDisplayName(){
        return __('plugins.generic.controlvocabulary.displayName');
    }

    function getDescription(){
        return __('plugins.generic.controlvocabulary.description');
    }

    function register($category, $path, $mainContextId = NULL) {
		$success = parent::register($category, $path);
		if ($success && $this->getEnabled()) {
				HookRegistry::register('Template::Workflow', array($this, 'addCustomAutosuggest'));
				HookRegistry::register('TemplateManager::display',array($this, 'hideNativeAutosuggest'));
        }
		return $success;
	}

	/**
	 * Ação para o plugin
	 */
	public function getActions($request, $actionArgs) {

		// Get the existing actions
			$actions = parent::getActions($request, $actionArgs);
			if (!$this->getEnabled()) {
				return $actions;
			}
	
		// Create a LinkAction that will call the plugin's
		// `manage` method with the `settings` verb.
			$router = $request->getRouter();
			import('lib.pkp.classes.linkAction.request.AjaxModal');
			$linkAction = new LinkAction(
				'settings',
				new AjaxModal(
					$router->url(
						$request,
						null,
						null,
						'manage',
						null,
						array(
							'verb' => 'settings',
							'plugin' => $this->getName(),
							'category' => 'generic'
						)
					),
					$this->getDisplayName()
				),
				__('manager.plugins.settings'),
				null
			);
	
		// Add the LinkAction to the existing actions.
		// Make it the first action to be consistent with
		// other plugins.
			array_unshift($actions, $linkAction);
	
			return $actions;
		}

		public function manage($args, $request) {
			switch ($request->getUserVar('verb')) {
	
			// Return a JSON response containing the
			// settings form
			case 'settings':
			$templateMgr = TemplateManager::getManager($request);
			$settingsForm = $templateMgr->fetch($this->getTemplateResource('settings.tpl'));
			return new JSONMessage(true, $settingsForm);
			}
			return parent::manage($args, $request);
		}

		
		
}
?>