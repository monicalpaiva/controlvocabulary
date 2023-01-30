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

}

?>