# prestashop_eicmslinks (FR )
The module does not work on version 1.7 of prestashop.<br />
All informations for installation here : http://www.h-hennes.fr/blog/prestashop-liens-dynamiques-dans-lediteur-tinymce/

# prestashop_eicmslinks
Add Cms Links tools in tinyMce Editor ( on CmsPage / Products and Products Categories )
It allows you to insert dynamics links to the cms pages / products categories / products.

In order to make it work after installation you need to configure the admin path
<p align="left">
<img src="https://github.com/SeyitDuman/prestashop_eicmslinks/blob/dev-v1.0/medias/eicmslinks-configuration.png?raw=true" alt="Ei cms links configuration">
</p> 

Screenshots :
--------
<p align="left">
	New editor button : <br />
	<img src="https://github.com/SeyitDuman/prestashop_eicmslinks/blob/dev-v1.0/medias/eicmslinks-button.png?raw=true" alt="Eicmslinks Button">
</p>

<p align="left">
	Insert link to cms Page : <br />
	<img src="https://github.com/SeyitDuman/prestashop_eicmslinks/blob/dev-v1.0/medias/eicms-links-page.png?raw=true" alt="Insert link to cms Page">
</p>

<p align="left">
	Insert link to product category : <br />
	<img src="https://github.com/SeyitDuman/prestashop_eicmslinks/blob/dev-v1.0/medias/eicms-links-category.png?raw=true" alt="Insert link to product category">
</p>

<p align="left">
	Insert link to product : <br />
	<img src="https://github.com/SeyitDuman/prestashop_eicmslinks/blob/dev-v1.0/medias/eicmslinks-product.png?raw=true" alt="Insert link to product">
</p>
<p align="left">
	Insert widget content : <br />
	<img src="https://github.com/SeyitDuman/prestashop_eicmslinks/blob/dev-v1.0/medias/eicmslinks-widget.png?raw=true" alt="Insert widget content">
</p>
Make working with your own Module :
--------

<p>Open this file : <i>prestashop\admin\themes\default\template\helpers\form\form.tpl</i></p>
<p>Go to line 895 or search "<i>{if isset($tinymce) && $tinymce}</i>" bloc and replace with :</p>

```javascript
{if isset($tinymce) && $tinymce}
    <script type="text/javascript">
        var iso = '{$iso|addslashes}';
        var pathCSS = '{$smarty.const._THEME_CSS_DIR_|addslashes}';
        var ad = '{$ad|addslashes}';

        $(document).ready(function(){
        {block name="autoload_tinyMCE"}
              tinySetup({
                 plugins : "colorpicker link image paste pagebreak table contextmenu filemanager table code media autoresize textcolor eicmslinks",
                toolbar2: "eicmslinks"                                
               });
        {/block}
        });
    </script>
{/if}
```
And put the following in your module before class definition:
```php
include_once(dirname(__FILE__) . '/../eicmslinks/eicmslinks.php');
```
Troubleshooting :
--------

<p>

<p>If you try to insert a link to widget, you can have the following error:</p>

<p>"<i>The description_short field (English (English)) is invalid.</i>"</p>

<p><strong>To solve it</strong> :<br />
In the class prestashop \classes\product.php line 249, replace "isCleanhtml" with "IsString" for description and description_short field.</p>
