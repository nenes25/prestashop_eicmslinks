
<!-- MODULE EicmsLinks Block products -->
<div id="eicmslink-products-{$type}" class="block products_block">
	<h4 class="title_block">{l s='Products' mod='eicmslinks'}</h4>
	<div class="block_content">
	{if $products !== false}
		<ul class="product_images clearfix">
		{foreach from=$products item='product' name='newProducts'}
			{if $smarty.foreach.newProducts.index}
				<li{if $smarty.foreach.newProducts.first} class="first"{/if}><a href="{$product.link|escape:'html'}" title="{$product.legend|escape:html:'UTF-8'}"><img src="{$link->getImageLink($product.link_rewrite, $product.id_image, 'medium_default')|escape:'html'}" alt="{$product.legend|escape:html:'UTF-8'}" /></a></li>
			{/if}
		{/foreach}
		</ul>
		<dl class="products">
		{foreach from=$products item=newproduct name=myLoop}
			<dt class="{if $smarty.foreach.myLoop.first}first_item{elseif $smarty.foreach.myLoop.last}last_item{else}item{/if}"><a href="{$newproduct.link|escape:'html'}" title="{$newproduct.name|escape:html:'UTF-8'}">{$newproduct.name|strip_tags|escape:html:'UTF-8'}</a></dt>
			{if $newproduct.description_short}<dd class="{if $smarty.foreach.myLoop.first}first_item{elseif $smarty.foreach.myLoop.last}last_item{else}item{/if}"><a href="{$newproduct.link|escape:'html'}">{$newproduct.description_short|strip_tags:'UTF-8'|truncate:75:'...'}</a><br /><a href="{$newproduct.link}" class="lnk_more">{l s='Read more' mod='eicmslinks'}</a></dd>{/if}
		{/foreach}
		</dl>
	{/if}
	</div>
</div>
<!-- /MODULE EicmsLinks Block products -->
