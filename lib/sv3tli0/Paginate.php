<?php
namespace sv3tli0;
/**
 * @author sv3tli0 <sfetliooo@gmail.com>
 * @copyright Copyright (c) 2013 sv3tli0
 * @license http://github.com/sv3tli0
 */

class Paginate
{
	# Current page
	private $current;
	# Total pages
	private $totals;
	# total items must be set !
	private $totalItems = 0;

	# baseUrl for the links
	protected $baseUrl = FALSE;
	# display prev/next buttons
	protected $show_PrevNext = FALSE;
	# generate First and Last pages within all others with separators:
	# Something as: 1 2 3 4 .. 9 (where '..' is separator)
	protected $FirstToLast = FALSE;
	# display first/last buttons
	protected $show_FirstLast = FALSE;
	# items per page
	protected $itemsPerPage = 20;
	# methods are 2: query and segment
	# 1 (query)   - is if you use $_GET['param'] for catching page
	# 2 (segment) - is if you use url without GET's - /1/2/3/.. segments at url for catching page
	protected $method = 'query';
	# for method 1 you need param name
	protected $param = "page";
	# for method 2 you need segment number usually its 2-3 or 4 depening on target slash "domain/1segment/2segment/3segment/4...".
	protected $segment = 3;
	# displayed links (dosn't matter for First, Last, Prev or Next)
	protected $displayedPages = 7;
	# prepared string for build urls
	private $urlString = '';
	# PrevNext data
	protected $PrevNext = array("prevName" => "&laquo;", "nextName" => "&raquo;");
	# FirstLast data
	protected $FirstLast = array("firstName" => "First", "lastName" => "Last");
	# Pages
	protected $pages = array();

	public function __construct($param = array())
	{
		foreach ($param as $name => $value) {
			if (property_exists($this, $name)) {
				$this->$name = $value;
			}
		}
		$this->init();
  	}

 	/* Public methods */
	public function renderHtml($layout = FALSE, $engine = FALSE, $engineObject = FALSE)
	{
		$this->init();
		$this->setPages();
		$layout = new Layout($layout, $this->pages, $engine, $engineObject);
		return $layout->getHTML();
	}

 	/* GET METHODS */
	public function getOffset()
	{
		$this->init();
		return (int) ( ( ( !empty($this->current) ? $this->current : 1 ) - 1 )  * $this->itemsPerPage );
	}

	/**
	 * Returns number of items per page.
	 *
	 * @return integer
	 */
	public function getItemsPerPage()
	{
		return $this->itemsPerPage;
	}

	/**
	 * @see getItemsPerPage()
	 */
	public function getLimit()
	{
		return $this->getItemsPerPage();
	}

	public function getPages()
	{
		$this->init();
		$this->setPages();
		return $this->pages;
	}

 	/* SET METHODS */
 	public function setTotalItems($totalItems = 0)
 	{
 		$this->totalItems = (int)$totalItems;
 	}

	public function setCurrentPage($page)
	{
		$this->current = (int)$page > 1 ? (int)$page : 1;
	}

	public function setParam($param, $value)
	{
		if (property_exists($this, $param)) {
			$this->$param = $value;
		}
	}

	public function setParams(array $arr)
	{
		if(!empty($arr)){
			foreach ($arr as $key => $value) {
				$this->setParam($key, $value);
			}
		}
	}


	/* Private methods */
	private function init()
	{
		# prepare and set urlString for later building of link urls (depends on method)
		$this->setUrlString();

		# calculate total pages
		$this->calculateTotalPages();
	}

	private function calculateTotalPages()
	{
		$this->totals = (int) ceil($this->totalItems / $this->itemsPerPage);
	}


	private function setUrlString()
	{
		$url = $this->baseUrl ?: ltrim($_SERVER["REQUEST_URI"], '/');
		$urlCl = new URL($url);
		$method = $this->method == "segment" ? "segments" : "params";
		$param = $this->method == "segment" ? $this->segment : $this->param;

		$elements = $urlCl->getElements($method);
		$this->setCurrentPage(isset($this->current) ? $this->current : (isset($elements[$param])?$elements[$param]:1));
		$elements[$param] = "{{{PAGE}}}";

		$urlCl->updateElements($elements, $method);
		$this->urlString = $urlCl->fetchRebuildedUrl();
		unset($urlCl);
	}

	private function setPages()
	{
		# empty pages array if this method is called more than once!
		$this->pages = array();

		if($this->show_PrevNext != FALSE){
			$this->setSpecPage('prev');
		}
		if($this->show_FirstLast != FALSE && $this->FirstToLast == FALSE){
			$this->setSpecPage('first');
		}

		$this->generatePages();

		if($this->show_FirstLast != FALSE && $this->FirstToLast == FALSE){
			$this->setSpecPage('last');
		}
		if($this->show_PrevNext != FALSE){
			$this->setSpecPage('next');
		}
 	}

 	/**
 	 * Calculates and generate all pages except specials (first,last,next,prev);
 	 * @return [type] [description]
 	 */
	private function generatePages()
	{
		$dif = floor($this->displayedPages / 2);

		$first = ($this->current - $dif) > 1 ? $this->current - $dif : 1;
		$last = ($this->current + $dif) < $this->totals ? $this->current + $dif : $this->totals;
		if($first == 1 && $last < $this->totals){
			$last = (1 + $dif*2) < $this->totals ? (1 + $dif*2)  : $this->totals;
		} elseif($last == $this->totals && $first > 1){
			$first = ($last - $dif*2) > 1 ? ($last - $dif*2) : 1;
		}

		if($first > $last && $this->totals > 0) {
			throw new Exception("First page can't be more than last!", 1);
		}

		for ($i=(int)$first; $i <= (int)$last; $i++) {
			if($this->FirstToLast){
				if($i == $first){
					$this->setPage(1);
					if( $i - 1 >= 1 ){
						$this->setSeparator();
					}
					continue;
				}
				if($i == $last){
					if( $this->totals - 1 >= $i ){
						$this->setSeparator();
					}
					$this->setPage($this->totals);
					continue;
				}
			}
			$this->setPage($i);
		}
	}

 	/**
 	 * Generates all special pages (first,last,next,prev);
 	 * @return [type] [description]
 	 */
 	private function setSpecPage($type)
 	{
		if( $type == 'prev' ) {
			$val = $this->current > 1 ? ( $this->current <= $this->totals ? $this->current-1 : ($this->totals > 1 ? $this->totals-1 : FALSE ) ) : FALSE;
			$this->setPage($val, FALSE, $val==FALSE, $this->PrevNext["prevName"], $type);
		} elseif( $type == 'next' ) {
			$val = $this->current < $this->totals ? ( $this->current >= 1 ? $this->current + 1 : FALSE ): FALSE;
			$this->setPage($val, FALSE, $val==FALSE, $this->PrevNext["nextName"], $type);
		} elseif ( $type == 'first' ) {
			$val = $this->current > 1 ? 1 : FALSE;
  			$this->setPage($val, FALSE, $val==FALSE, (isset($this->FirstLast["firstName"]) ? $this->FirstLast["firstName"] : $val), $type);
		} elseif ( $type == 'last' ){
			$val = $this->current < $this->totals ? $this->totals : FALSE;
 			$this->setPage($val, FALSE, $val==FALSE, (isset($this->FirstLast["lastName"]) ? $this->FirstLast["lastName"] : $val), $type);
		}
 	}

 	private function setSeparator()
 	{
		$this->pages[] = $this->getPageData(FALSE, FALSE, FALSE, FALSE, TRUE);
 	}

	private function setPage($numb, $current = FALSE, $disabled = FALSE, $name = FALSE, $type = FALSE)
	{
		$this->pages[($type?:count($this->pages))] = $this->getPageData($numb, $current?:($this->current===$numb), $disabled, $name?:$numb);
	}

	private function getUrl($page = FALSE)
	{
		return $page ? str_replace("{{{PAGE}}}", $page, $this->urlString) : "#";
	}

	private function getPageData($page, $current = FALSE, $disabled = FALSE, $name = FALSE, $separator = FALSE)
	{
		return array("url" => $this->getUrl($page), "value"=>$page, "name"=>$name?:$page, "current"=>$current, "disabled"=>$disabled, "separator"=>$separator);
	}

}

