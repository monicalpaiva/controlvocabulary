<script>
	$(function() {ldelim}
		$('#VocabularyRequiredSettings').pkpHandler('$.pkp.controllers.form.AjaxFormHandler');
	{rdelim});
</script>

<form
  class="pkp_form"
  id="VocabularyRequiredSettings"
  method="POST"
  action="{url router=$smarty.const.ROUTE_COMPONENT op="manage" category="generic" plugin=$pluginName verb="settings" save=true}"
>
  <!-- Always add the csrf token to secure your form -->
	{csrf}

  {fbvFormArea}
		{fbvFormSection}
			{fbvElement
        type="text"
        id="LinkPrincipal"
        value=$LinkPrincipal
        label="plugins.generic.VocabularyRequired.LinkPrincipal"
      }
		{/fbvFormSection}
  {/fbvFormArea}
	{fbvFormButtons submitText="common.save"}
</form>