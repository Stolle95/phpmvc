<?php
namespace Anax\Fbslogin;

/**
 * A controller for users and admin related events.
 *
 */
class FbsloginController implements \Anax\DI\IInjectionAware
{
    use \Anax\DI\TInjectable;
	public function initialize()
	{
	    $this->login = new \Anax\Fbslogin\Fbslogin();
	    $this->login->setDI($this->di);
	    $this->form = new \Mos\HTMLForm\CForm();
	}
	public function indexAction() 
	{
		$this->views->add('fbslogin/form', [
	        'title' => "View all users",
	    ]);
	}

	public function verifyAction() 
	{
		$this->views->add('fbslogin/index', [
	        'title' => "View all users",
	    ]);
	}
}