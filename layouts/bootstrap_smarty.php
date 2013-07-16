<div class="pagination">
	<ul>
	{foreach $data as $key=>$value}
	{if $value.separator}
	<li><span>..</span></li>
	{else}
	<li{if $value.disabled eq TRUE} class="disabled"{elseif $value.current eq TRUE} class="active"{/if}>
		{if $value.current || $value.disabled}
			<span>{$value.name}</span>
		{else}
			<a href="{$value.url}">{$value.name}</a>
		{/if}
	</li> 
	{/if}
	{/foreach}
	</ul>
</div>