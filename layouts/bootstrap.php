<?php 
$paginate = '<div class="pagination"><ul>';
if($data){
	foreach ($data as $key => $value):
		if($value["separator"]):
			$paginate .= '<li><span>..</span></li>';
		else:
			$paginate .= '<li'. ( $value["disabled"] ? ' class="disabled"': ( $value["current"] ? ' class="active"' : "" )) .'>';
			if($value["disabled"] || $value["current"]):
				$paginate .= '<span>'.$value["name"].'</span>';
			else:
				$paginate .= '<a href="'.$value["url"].'">'.$value["name"].'</a>';
			endif;
		$paginate .= '</li>';
		endif;
	endforeach;
}
$paginate .= '</ul></div>';
return $paginate;
