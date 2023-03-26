<?php

class controlvocabularySettingsForm extends Form
{
    /** @var int */
    public $_journalId;

    /** @var object */
    public $_plugin;

    public function __construct($plugin, $journalId)
    {
        $this->_journalId = $journalId;
        $this->_plugin = $plugin;

        parent::__construct($plugin->getTemplateResource('settingsForm.tpl'));

        $this->addCheck(new \PKP\form\validation\FormValidator($this, 'controlVocabularyAPI', 'required', 'plugins.generic.controlvocabulary.manager.settings.controlvocabularySiteIdRequired'));

        $this->addCheck(new \PKP\form\validation\FormValidatorPost($this));
        $this->addCheck(new \PKP\form\validation\FormValidatorCSRF($this));
    }

    public function initData()
    {
        $this->_data = [
            'controlVocabularyAPI' => $this->_plugin->getSetting($this->_journalId, 'controlVocabularyAPI'),
        ];
    }

    public function readInputData()
    {
        $this->readUserVars(['controlVocabularyAPI']);
    }

    public function fetch($request, $template = null, $display = false)
    {
        $templateMgr = TemplateManager::getManager($request);
        $templateMgr->assign('pluginName', $this->_plugin->getName());
        return parent::fetch($request, $template, $display);
    }

    public function execute(...$functionArgs)
    {
        $this->_plugin->updateSetting($this->_journalId, 'controlVocabularyAPI', trim($this->getData('controlVocabularyAPI'), "\"\';"), 'string');
        parent::execute(...$functionArgs);
    }
}
