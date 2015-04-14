<!doctype html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
            <title>{l s='add link to prestashop cms page' mod='eicmslinks'}</title>
			{if $ps_version == 15}
			<script type="text/javascript" src="../../tiny_mce_popup.js"></script>
			{/if}
			<script type="text/javascript">
				var js_token = '{$js_token}';
			</script>
			{* Jquery a inclure en dynamique en fonction de la version de prestashop *}
			<script type="text/javascript" src="../../../jquery/jquery-1.11.0.min.js"></script>
			<script type="text/javascript" src="../../../../modules/eicmslinks/views/tinymce_popup.js"></script>
			{* Styles Admin : récupérer dynamiquement le nom de l'admin directory *}
			<link rel="stylesheet" type="text/css" href="/prestashop/prestashop_1-6-0-14/admin-dev/themes/default/css/admin-theme.css" />
			
			{literal}
				<style>
				 ul.menu-link {list-style:none;}
				 ul.menu-link li {float:left;margin-left:5px;}
				 .link-block {display:none;}
				</style>
			{/literal}
    </head>
    <body>
	<ul class="menu-link">
		<li><a class="show-block-link" rel="cms_content" href="#">{l s='cms content' mod='eicmslinks'}</a></li>
		<li><a class="show-block-link" rel="category_content" href="#">{l s='category content' mod='eicmslinks'}</a></li>
		<li><a class="show-block-link" rel="product_content" href="#">{l s='product content' mod='eicmslinks'}</a></li>
	</ul>
	<br style="clear:both;" />
        <p>{l s='click on the element to add it to the page content' mod='eicmslinks'}</p>
		{* Cms contents *}
		<div id="cms_content" class="link-block" style="display:block;">	
			{$cms_categories_html}	
		</div>
		{* Categories (product) content *}
		<div id="category_content" class="link-block">
			<!-- Content dynamicaly loaded -->
			Contenu categories
		</div>
		{* Product content *}
		<div id="product_content" class="link-block">
			<!-- Content dynamicaly loaded -->
			Contenu produits
		</div>
		
        <div class="mceActionPanel">
            <center>
                <div>
                    <input type="button" id="cancel" name="cancel" value="{l s='Cancel' mod='eicmslinks'}" class="mce-close" onclick="closePopup();" />
                </div>
            </center>
        </div>       
        <script>
            /**
             * Ajout du lien d'ans l'éditeur
             * @param string url
             * @param string texte
             * @returns void
             */
            function addLink(url, texte) {
                {if $ps_version == '16'}
                    parent.tinymce.activeEditor.execCommand('mceInsertContent', false, '<a href="' + url + '">' + texte + '</b>');
                    top.tinymce.activeEditor.windowManager.close();
                {else}
                    tinyMCE.execCommand('mceInsertContent',false,'<a href="'+url+'">'+texte+'</b>');
                    tinyMCEPopup.close();	
                {/if}
            }
            /**
             * Fermeture de la popup
             * @returns void
             */
            function closePopup(){
                {if $ps_version == '16'}
                    top.tinymce.activeEditor.windowManager.close();
                {else}
                    tinyMCEPopup.close();	
                {/if}
            }
        </script>    

    </body>
</html>