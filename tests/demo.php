<?php
namespace sv3tli0\Paginate;
use sv3tli0\Paginate;


$path = dirname(__DIR__) . '/lib/sv3tli0/';

require_once($path . "Paginate.php");
require_once($path . "Layout.php");
require_once($path . "URL.php");
require_once($path . "Exception.php");


$current_page = isset($_GET['page']) ?  $_GET['page'] : 2;

$counter = 1111;
$limit = 10;

$paginate_conf = [
	'displayedPages' => 7,
#	'current' => $current_page,
	'itemsPerPage' => $limit,
	'show_PrevNext' => TRUE,
#	'show_FirstLast' => TRUE,
	'FirstToLast' => FALSE
];
$pagination = new Paginate( $paginate_conf);
$pagination->setTotalItems($counter);#->setTotalItems($counter);#->renderHtml();
$pagination_html = $pagination->renderHtml();
echo $pagination_html;	
