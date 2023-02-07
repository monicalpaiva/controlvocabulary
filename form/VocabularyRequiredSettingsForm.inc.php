<?php
 
import('lib.pkp.classes.form.Form');

class VocabularyRequiredSettingsForm extends Form{
    /** @var VocabularyRequiredPlugin  */
  public $plugin;

  public function __construct($plugin) {

  // Defina o modelo de configurações e armazene uma cópia do objeto de plug-in
      parent::__construct($plugin->getTemplateResource('settings.tpl'));
  $this->plugin = $plugin;

  // Sempre adicione validação POST e CSRF para proteger seu formulário.
      $this->addCheck(new FormValidatorPost($this));
      $this->addCheck(new FormValidatorCSRF($this));
  }

/**
 * Carregar configurações já salvas no banco de dados
 *
 * As configurações são armazenadas por contexto, de modo que cada revista possa ter configurações diferentes.
 */
  public function initData() {
  $contextId = Application::get()->getRequest()->getContext()->getId();
  $this->setData('LinkPrincipal', $this->plugin->getSetting($contextId, 'LinkPrincipal'));
  parent::initData();
  }

/**
 * 
 */
  public function readInputData() {
      $this->readUserVars(['LinkPrincipal']);
  parent::readInputData();
}

/**
 * Carregar dados que foram enviados com o formulário
 *
 * Os dados atribuídos ao formulário usando $this->setData() 
 * durante os métodos initData() ou readInputData() serão passados ​​para o modelo.
 */
  public function fetch($request, $template = null, $display = false) {

  // Passe o nome do plug-in para o modelo para que possa ser 
  // usado na URL para a qual o formulário é enviado
  $templateMgr = TemplateManager::getManager($request);
  $templateMgr->assign('pluginName', $this->plugin->getName());

  return parent::fetch($request, $template, $display);
}

  /**
   * Salve as configurações
   */
  public function execute() {
  $contextId = Application::get()->getRequest()->getContext()->getId();
      $this->plugin->updateSetting($contextId, 'LinkPrincipal', $this->getData('LinkPrincipal'));

  // Informe ao usuário que o salvamento foi bem-sucedido.
      import('classes.notification.NotificationManager');
      $notificationMgr = new NotificationManager();
      $notificationMgr->createTrivialNotification(
    Application::get()->getRequest()->getUser()->getId(),
    NOTIFICATION_TYPE_SUCCESS,
    ['contents' => __('common.changesSaved')]
  );

      return parent::execute();
  }

}
