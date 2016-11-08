# prestashop_eicmslinks (FR )
Toutes les informations d'installation sont disponibles sur la page : http://www.h-hennes.fr/blog/prestashop-liens-dynamiques-dans-lediteur-tinymce/

# prestashop_eicmslinks
Add Cms Links tools in tinyMce Editor ( on CmsPage / Products and Products Categories )
It allows you to insert dynamics links to the cms pages / products categories / products.

In order to make it work after installation you need to configure the admin path
<p align="center">
	<img src="http://www.h-hennes.fr/blog/wp-content/uploads/2015/05/eicmslinks-configuration.jpg" alt="Ei cms links configuration" />
</p> 

Screenshots :
--------
<p align="center">
	New editor button : <br />
	<img src="http://www.h-hennes.fr/blog/wp-content/uploads/2015/05/eicmslinks-button.jpg" alt="New editor button" />
</p>

<p align="center">
	Insert link to cms Page : <br />
	<img src="http://www.h-hennes.fr/blog/wp-content/uploads/2015/05/eicms-links-page.jpg" alt="Insert link to cms Page" />
</p>

<p align="center">
	Insert link to product category : <br />
	<img src="http://www.h-hennes.fr/blog/wp-content/uploads/2015/05/eicms-links-category.jpg" alt="Insert link to product category" />
</p>

<p align="center">
	Insert link to product : <br />
	<img src="http://www.h-hennes.fr/blog/wp-content/uploads/2015/05/eicmslinks-product.jpg" alt="Insert link to product" />
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


Troubleshooting :
--------

<p>

<p>If you try to insert a link to widget, you can have the following error:</p>

<p>"<i>The description_short field (English (English)) is invalid.</i>"</p>

<p><strong>To solve it</strong> :<br />
In the class prestashop \classes\product.php line 249, replace "isCleanhtml" with "IsString" for description and description_short field.</p>
