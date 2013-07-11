<div class="pagination">
	<ul>
	<?php foreach ($data as $key => $value): ?>
	<? if($value["separator"]):?>
	<li><span>..</span></li>
	<? else:?>
	<li<?=$value["disabled"]?' class="disabled"': ($value["current"]? ' class="active"':"" )?>>
		<a href="<?=$value["url"]?>"><?=$value["name"]?></a>
	</li>
	<? endif;?>
	<?php endforeach; ?>
	</ul>
</div>
