<?php
namespace sv3tli0;
/**
 * @author sv3tli0 <sfetliooo@gmail.com>
 * @copyright Copyright (c) 2013 sv3tli0
 * @license http://github.com/sv3tli0
 */

class Layout
{
	private $layout;
	private $data;
	private $engine;
	private $object;

	public function __construct($layout = FALSE, $data = [], $engine = FALSE, $engineObject = FALSE)
	{
		if(!$layout){
			$layout = dirname(dirname(__DIR__)) . '/layouts/' .($engine ? "bootstrap_$engine.php" : "bootstrap.php");
		}

		$this->layout = $layout;
		$this->data = $data;
		$this->engine = $engine;
		$this->object = $engineObject;
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
		if(!realpath($this->layout)){
			throw new Exception("Wrong layout path: <{$this->layout}>", 1);
		}

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
