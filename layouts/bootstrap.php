<div class="pagination">
	<ul>
	<?php foreach ($data as $key => $value): ?>
	<li<?=$value["disabled"]?' class="disabled"': ($value["current"]? ' class="active"':"" )?>><a href="<?=$value["url"]?>"><?=$value["name"]?></a></li> 
	<?php endforeach; ?>
	</ul>
</div>
