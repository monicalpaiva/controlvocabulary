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
 *
 */

import('lib.pkp.classes.plugins.GenericPlugin');

class controlvocabularyPlugin extends GenericPlugin {

    function getName(){
        return __('plugins.generic.controlvocabulary.Name')
    }

    function getDisplayName(){
        return __('plugins.generic.controlvocabulary.displayName');
    }

    function getDescription(){
        return __('plugins.generic.controlvocabulary.description');
    }

    /**
	 * Add custom js for backend and frontend
	 */
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

    /**
	 * Add YSO autoSuggest scripts for keywords
	 * @param $hookName string
	 * @param $params array
	 */
	function addCustomAutosuggest($hookName, $params) {
		$output =& $params[2];
		$output .= $this->ysoAutosuggest('en_US','en');
		$output .= $this->ysoAutosuggest('pt_BR','pt');
		return false;
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
	 * Get YSO autosuggest api script for selected locale
	 * @param $localeField string
	 * @param $localeApi string
	 * @return $string
	 */
	function ysoAutosuggest($localeField, $localeApi){
		return "
			<script>
				$( function() {
					$( '#metadata-keywords-control-" . $localeField . "' ).autocomplete({
						source: function( request, response ) {
							$.ajax( {
								url: 'https://api.finto.fi/rest/v1/search?vocab=yso&lang=" . $localeApi . "',
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