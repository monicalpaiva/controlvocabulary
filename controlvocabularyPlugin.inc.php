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

import('lib.pkp.classes.plugins.GenericPlugin');

class controlvocabularyPlugin extends GenericPlugin {
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

		function hideNativeAutosuggest($hookName, $params) {
			$template =& $params[1];
			if ($template !== 'workflow/workflow.tpl') {
				return false;
			}
	
			$templateMgr =& $params[0];
			$templateMgr->addStylesheet(
				"ObjectsForReviewGridHandlerStyles",
				"div[id^='metadata-keywords-autosuggest'] .autosuggest__results-container{ position: absolute; left: -9999px; }",
				[
					"inline" => true,
					"contexts" => "backend",
				]
			);
	
			return false;
		}

		function addCustomAutosuggest($hookName, $params) {
			$output =& $params[2];
			$output .= $this->ysoAutosuggest('pt_BR','pt');
			$output .= $this->ysoAutosuggest('en_US','en');
			return false;
		}

		function ysoAutosuggest($localeField, $localeApi){
			return "
				<script>
					$( function() {
						$( '#metadata-keywords-control-" . $localeField . "' ).autocomplete({
							source: function( request, response ) {
								$.ajax( {
									url: '$controlVocabularyAPI" . $localeApi . "',
									dataType: 'json',
									data: {
										query: request.term + '*'
									},
									success:
										function( data ) {
											var output = data.results;
											response($.map(data.results, function(item) {
											return {
												label: item.prefLabel + ' [' + item.uri + ']',
												value: item.prefLabel + ' [' + item.uri + ']'
										}
									}));
								}
							} );
							},
							minLength: 2,
							autoFocus: true,
							select: function(){
								$( '#metadata-keywords-control-" . $localeField . "' ).focus().trigger({type: 'keypress', which: 50, keyCode: 50});
							}
						} );
					} );
				</script>";
		}
}
?>