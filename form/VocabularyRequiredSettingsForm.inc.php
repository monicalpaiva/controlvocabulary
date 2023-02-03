<?php
/**
*
*
*
*
*
*/
 
import('lib.pkp.classes.form.Form');

class VocabularyRequiredSettingsForm extends Form{
    public $contextId;
    public $plugin;

    public function __construct($plugin, $contextId)
    {
        $this->contextId = $contextId;
        $this->plugin = $plugin;
        parent::__construct($plugin->getTemplateResource("settingsForm.tpl"));
        $this->addCheck(new FormValidatorCSRF($this));
    }

}
