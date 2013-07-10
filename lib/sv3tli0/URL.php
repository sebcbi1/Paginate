<?php
namespace sv3tli0;
/**
 * @author sv3tli0 <sfetliooo@gmail.com>
 * @copyright Copyright (c) 2013 sv3tli0
 * @license http://github.com/sv3tli0
 */

class URL
{
	protected $baseUrl;
	protected $scheme = FALSE;
	protected $host = FALSE;
	protected $port = FALSE;
	protected $user = FALSE;
	protected $pass = FALSE;
	protected $path = FALSE;
	protected $query = FALSE;
	protected $fragment = FALSE;

	# segments are each key after slash / in the path
	protected $segments;
	# params - each key=>value from the url query string (equals to $_GET for current page)
	protected $params;

	public function __construct($url)
	{
		$this->init($url);
	}	

	private function init($url)
	{
		$this->baseUrl = $url;
		if($this->validate($url)){
			$this->parseUrl($url);
		} else{
			$this->parsePartUrl($url);
		}
		$this->setSegments($this->path);
		$this->setParams($this->query);
	}

	private function parseUrl($url)
	{
		$parse = parse_url($url);
		foreach ($parse as $key => $value) {
			$this->$key = $value;
		}
	}

	/**
	*	Parsing not full URLs if its not a valid path, its not our problem
	*/
	private function parsePartUrl($url)
	{
		$path = explode("?", $url, 2);
		$this->path = $path[0];

		if(isset($path[1])){
			$query = explode("#", $path[1], 2);
			$this->query = $query[0];
			if(isset($query[1])){
				$this->hash = $query[1];
			}
		}
	}

	public function fetchRebuildedUrl()
	{
		return $this->buildUrl();
	}
 	
 	/**
 	 *	Updating elements
	 */
	public function updateElements($data, $key)
	{
		if(method_exists($this, "update".$key)){
 			$mth = "update" . $key;
 			$this->$mth($data);
		}
	}

	protected function updateSegments($data)
	{
		$this->segments = $data;
		$this->path = $this->generatePath();
	}

	protected function updateParams($data)
	{
		$this->params = $data;
		$this->query = $this->generateQuery();
	}


	private function generatePath()
	{
		return $this->segments ? implode("/", $this->segments) : "";
	}

	private function generateQuery()
	{
		foreach ($this->params as $key => $value) {
			$data[] = $key."=".$value;
		}
		return $this->params ? implode("&", $data) : "";
	}

	public function getElements($key)
	{
		if($key == 'segments'){
			return $this->segments;
		} elseif($key == 'params'){
			return $this->params;
		}
	}

	private function buildUrl() { 
		$scheme   = $this->scheme ? $this->scheme . '://' : ''; 
		$host     = $this->host ? $this->host : ''; 
		$port     = $this->port ? ':' . $this->port : ''; 
		$user     = $this->user ? $this->user : ''; 
		$pass     = $this->pass ? ':' . $this->pass  : ''; 
		$pass     = ($user || $pass) ? "$pass@" : ''; 
		$path     = $this->path ? $this->path : ''; 
		$query    = $this->query ? '?' . $this->query : ''; 
		$fragment = $this->fragment ? '#' . $this->fragment : ''; 
		return "$scheme$user$pass$host$port$path$query$fragment"; 
	} 

	private function setSegments($path)
	{
		$this->segments = explode("/", "/".ltrim($path));
	}

	private function setParams($query)
	{
		parse_str($query, $this->params);
	}

	private function validate($url)
	{
		return filter_var($url, FILTER_VALIDATE_URL) ? TRUE : FALSE;
	}

}
