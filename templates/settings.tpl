<form>
	{csrf}

	<div id="description">{translate key="plugins.generic.controlvocabulary.manager.settings.description"}</div>

	{fbvFormArea id="webFeedSettingsFormArea"}
		{fbvElement type="text" id="controlVocabularyAPI" value=$controlVocabularyAPI label="plugins.generic.controlvocabulary.LinkPrincipal"}
	{/fbvFormArea}

	{fbvFormButtons}

	<p><span class="formRequired">{translate key="common.requiredField"}</span></p>
</form>