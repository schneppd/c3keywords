<!-- C3Keywords module -->
<div id="c3keywords_block_left" class="block c3keywords_block">
	<h4 class="title_block">{l s='Mots clés' mod='c3keywords'}</h4>
	<p class="block_content">
{if $tags}
	{foreach from=$tags item=tag name=myLoop}
		<a href="{$tag.link|escape:'html'}" title="{l s='Plus d\'informations' mod='c3keywords'} {$tag.tag_name|escape:html:'UTF-8'}" class="{if $smarty.foreach.myLoop.last}last_item{elseif $smarty.foreach.myLoop.first}first_item{else}item{/if}">{$tag.tag_name|escape:html:'UTF-8'}</a>
	{/foreach}
{else}
	{l s='No keywords specified.' mod='c3keywords'}
{/if}
	</p>
</div>
<!-- /C3Keywords module -->

