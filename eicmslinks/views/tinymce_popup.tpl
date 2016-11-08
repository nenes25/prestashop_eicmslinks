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
            var admin_dir = '{$admin_dir}';
            var ajax_page = '{$ajax_page}'
        </script>
        <script type="text/javascript" src="{$jquery_file}"></script>
        <script type="text/javascript" src="{$js_file}"></script>
        <link rel="stylesheet" href="{$css_file}">
    </head>
    <body id="eicmslinks_body">
        <div class="input-group">
            <label for="eicmslinks_textlink">{l s='Text link :' mod='eicmslinks'}</label>
            <div id="eicmslinks_textlink_wrapper"><input type="text" id="eicmslinks_textlink" onkeyup="textlinkKeyUp()"/><span>{l s='This field is required !' mod='eicmslinks'}</span></div>
        </div>
        <ul class="menu-link">
            <li><a class="show-block-link" rel="cms_content" href="#">{l s='cms content' mod='eicmslinks'}</a></li>
            <li><a class="show-block-link" rel="category_content" href="#">{l s='category content' mod='eicmslinks'}</a></li>
            <li><a class="show-block-link" rel="product_content" href="#">{l s='product content' mod='eicmslinks'}</a></li>
            <li><a class="show-block-link" rel="widgets" href="#">{l s='widgets' mod='eicmslinks'}</a></li>
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
        <div id="widgets" class="link-block">
            <ul>
                {foreach from=$widgets_list item=widget}
                    <li>
                        <a href="#" onclick="addWidget('{$widget.name}');">{$widget.name}</a>
                        {if sizeof($widget.params)}
                            <div class="hint">
                                {l s='Allowed options' mod='eicmslinks'} :
                                {foreach from=$widget.params item=param}
                                    {$param},
                                {/foreach}
                            </div>
                        {/if}
                    </li>
                {/foreach}
            </ul>
        </div>

        <div class="mceActionPanel">
            <div>
                <input type="button" id="cancel" name="cancel" value="{l s='Cancel' mod='eicmslinks'}" class="mce-close" onclick="closePopup();" />
            </div>
        </div>       
        <script>
            // mise à jour du text du lien si text sélectionné
            var iframe = window.parent.document.querySelectorAll("iframe");
            var $textlink = document.getElementById("eicmslinks_textlink");
            var $textlinkWrapper = document.getElementById("eicmslinks_textlink_wrapper");
            var param = null;
            var url = null;

            // on cherche sur tous les iframes
            for (var i = 0; i < iframe.length; i++) {

                url = iframe[i].contentWindow.location.search;
                param = url.split('eicmslinks_sel=')[1] ? url.split('eicmslinks_sel=')[1] : null;

                // On change la valeur du INPUT uniquement si on a le bon paramètre
                if (param !== null) {
                    $textlink.value = decodeURIComponent(param);
                }
            }

            function textlinkKeyUp() {
                $textlink.value.length === 0 ? showError() : hideError();
            }

            function showError() {
                $textlinkWrapper.className = "error";
            }
            function hideError() {
                $textlinkWrapper.className = "";
            }
            
            textlinkKeyUp();

            /**
             * Ajout du lien d'ans l'éditeur
             * @param string url
             * @param string texte
             * @returns void
             */
            function addLink(url) {
                texte = $textlink.value;
                if (texte.length === 0 || texte === null) {
                    showError();
                    return false;
                } else {
                    hideError();
                }


            {if $ps_version == '16'}
                parent.tinymce.activeEditor.execCommand('mceInsertContent', false, '<a href="' + url + '">' + texte + '</b>');
                top.tinymce.activeEditor.windowManager.close();
            {else}
                tinyMCE.execCommand('mceInsertContent', false, '<a href="' + url + '">' + texte + '</b>');
                tinyMCEPopup.close();
            {/if}
            }

            /**
             * Ajout d'un widget dans l'editeur
             * @param string name
             * @returns void
             */
            function addWidget(name) {
            {if $ps_version == '16'}
                parent.tinymce.activeEditor.execCommand('mceInsertContent', false, '{ldelim}{ldelim}widget name="Widget' + name + '"{rdelim}{rdelim}');
                top.tinymce.activeEditor.windowManager.close();
            {else}
                tinyMCE.execCommand('mceInsertContent', false, '{ldelim}{ldelim}widget name="Widget' + name + '"{rdelim}{rdelim}');
                tinyMCEPopup.close();
            {/if}
            }

            /**
             * Fermeture de la popup
             * @returns void
             */
            function closePopup() {
            {if $ps_version == '16'}
                top.tinymce.activeEditor.windowManager.close();
            {else}
                tinyMCEPopup.close();
            {/if}
            }
        </script>    

    </body>
</html>