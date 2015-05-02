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
        <script type="text/javascript" src="{$jquery_file}"></script>
        <script type="text/javascript" src="{$js_file}"></script>

        {literal}
            <style>
             ul.menu-link {list-style:none;height:20px;background-color:#8bc954;height:25px;padding:5px 28px 0px 20px;display: inline-block;border-radius:4px;}
             ul.menu-link a {color:#FFF;text-decoration:none}
             ul.menu-link a:hover {text-decoration: underline;}
             ul.menu-link li {display: inline-block;margin-left:8px;text-transform:capitalize;}
             .link-block {display:none;}
             
             .mce-window-head {height:15px;}
            </style>
        {/literal}
    </head>
    <body>
	<ul class="menu-link">
		<li><a class="show-block-link" rel="cms_content" href="#">{l s='cms content' mod='eicmslinks'}</a></li>
		<li><a class="show-block-link" rel="category_content" href="#">{l s='category content' mod='eicmslinks'}</a></li>
		<!--<li><a class="show-block-link" rel="product_content" href="#">{l s='product content' mod='eicmslinks'}</a></li>-->
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