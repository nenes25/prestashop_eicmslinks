<!doctype html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
            <title>{l s='add link to prestashop cms page' mod='eicmslinks'}</title>
			{if $ps_version == 15}
			<script type="text/javascript" src="../../tiny_mce_popup.js"></script>
			{/if}
    </head>
    <body>
        <p>{l s='click on the element to add it to the page content' mod='eicmslinks'}</p>
        {* Category contents *}
        {$categories_html}	
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