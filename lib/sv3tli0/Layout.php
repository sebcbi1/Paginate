<?php
namespace sv3tli0;
/**
 * @author sv3tli0 <sfetliooo@gmail.com>
 * @copyright Copyright (c) 2013 sv3tli0
 * @license http://github.com/sv3tli0
 */

class Layout
{
	private $path;
	private $layout;
	private $data;
	private $engine;
	private $object;

	public function __construct($path = FALSE, $layout = FALSE, $data = [], $engine = FALSE, $object = FALSE)
	{
		$this->path = $path?: dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'layouts';
		if(!realpath($this->path)){
			throw new Exception("Layouts path is not correct!", 1);
		}

		$layout = $layout ?: ($engine ? "bootstrap_$engine.php" : "bootstrap.php");

		if(realpath($this->path . DIRECTORY_SEPARATOR . $layout)){
			$this->layout = $this->path . DIRECTORY_SEPARATOR . $layout;
		} else{
			throw new Exception("Incorrect layout path!", 1);
		}
		
		$this->data = $data;
		$this->engine = $engine;
		$this->object = $object;
	}

	private function renderLayout()
	{
		switch ($this->engine) {
			case 'smarty':
				return $this->renderSmartyLayout();
				break;
			
			default:
				return $this->renderPhpLayout();
				# code...
				break;
		}
	}

	private function renderPhpLayout()
	{
		$data = $this->data;
		ob_start();
		include_once($this->layout);
		$myvar = ob_get_clean();
		return $myvar;
	}

	private function renderSmartyLayout()
	{
		$smarty = $this->object;
  		$smarty->assign("data", $this->data);
		return $smarty->fetch($this->layout);
	}

	public function getHTML()
	{
		return $this->renderLayout();
	}
		
}	
