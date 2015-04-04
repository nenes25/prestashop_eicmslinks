{* Override du template prestashop 1.5 *}
{extends file="helpers/form/form.tpl"}

{* rajout des champs tinymce *}
{if isset($tinymce) && $tinymce}
	<script type="text/javascript">

	var iso = '{$iso}';
	var pathCSS = '{$smarty.const._THEME_CSS_DIR_}';
	var ad = '{$ad}';

	$(document).ready(function(){
		{block name="autoload_tinyMCE"}
			tinySetup({
				plugins : 'eicmslinks',
				theme_advanced_buttons3 : 'eicmslinks',
			});
		{/block}
	});
	</script>
{/if}
{block name="input"}
	{if $input.name == "link_rewrite"}
		<script type="text/javascript">
		{if isset($PS_ALLOW_ACCENTED_CHARS_URL) && $PS_ALLOW_ACCENTED_CHARS_URL}
			var PS_ALLOW_ACCENTED_CHARS_URL = 1;
		{else}
			var PS_ALLOW_ACCENTED_CHARS_URL = 0;
		{/if}
		</script>
		{$smarty.block.parent}
	{else}
		{$smarty.block.parent}
	{/if}
{/block}
{block name="script"}
	$(document).ready(function() {
		if (btn_submit.length > 0)
		{
			//get reference on save and stay link
			btn_save_and_preview = $('span[class~="process-icon-save-and-preview"]').parent();

			//get reference on current save link label
			lbl_save = $('#desc-{$table}-save div');

			//submit the form
				if (btn_save_and_preview)
				{
					btn_save_and_preview.click(function() {
						//add hidden input to emulate submit button click when posting the form -> field name posted
						btn_submit.before('<input type="hidden" name="'+btn_submit.attr("name")+'AndPreview" value="1" />');
						$('#{$table}_form').submit();
					});
				}
		}
		$('#active_on').bind('click', function(){
			toggleDraftWarning(false);
		});
		$('#active_off').bind('click', function(){
			toggleDraftWarning(true);
		});		
	});
{/block}

{block name="leadin"}
	<div class="warn draft" style="{if $active}display:none{/if}">
		<p>
		<span style="float: left">
		{l s='Your CMS page will be saved as a draft'}
		</span>
		<br class="clear" />
		</p>
	</div>
{/block}

{block name="input"}
	{if $input.type == 'select_category'}
		<select name="{$input.name}">
			{$input.options.html}
		</select>
	{else}
		{$smarty.block.parent}
	{/if}
{/block}