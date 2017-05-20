<?php
namespace Anax\Users;

/**
 * A controller for users and admin related events.
 *
 */
class UserController implements \Anax\DI\IInjectionAware
{
    use \Anax\DI\TInjectable;
	public function initialize()
	{
	    $this->users = new \Anax\Users\User();
	    $this->users->setDI($this->di);
	    $this->form = new \Mos\HTMLForm\CForm();
	}
	public function logoutAction() 
	{
		$url = $this->url->create('');
		if (!$this->session->get('authenticated', []))
	    {
			$this->response->redirect($url);
	    	return false;
	    }
	    $this->session->set('authenticated', false);
	    $this->response->redirect($url);

	}
	public function profileAction($acronym = NULL)
	{
				$prof = '';
		    	if ($acronym !== NULL)
		    	{
		    		$profile = $this->users->getProfile($acronym);
		    		$this->theme->setTitle($acronym . " - profile");		    		
		    	}
		    	else if (isset($this->session->get('authenticated', [])[1]))
		    	{
		    		$profile = $this->users->getProfile($this->session->get('authenticated', [])[1]);
					$this->theme->setTitle($this->session->get('authenticated', [])[1] . " - profile");
		    	}
		    	else 
		    	{
			    	$this->response->redirect($this->url->create('user/login'));
                    return true;
		    	}

		    	if ($this->session->get('authenticated', [])[1] === $acronym or isset($this->session->get('authenticated', [])[1]) && $acronym === NULL)
			    {
			    	$form = $this->form->create([], [
						'profileImg' => array(
						    'type' => 'select',
						    'label' => 'Change profile image:',
						    'options' => array(
						      'default' => 'Select a profile image...',
						      'chef-icon' => 'chef-icon',
						      'cop-icon' => 'cop-icon',
						      'cowboy-icon' => 'cowboy-icon',
						      'dice-faces' => 'dice-faces',
						      'doctor-icon' => 'doctor-icon',
						      'gentleman-icon' => 'gentleman-icon',
						      'miss-crown-icon' => 'miss-crown-icon',
						      'miss-grey-hat-icon' => 'miss-grey-hat-icon',
						      'miss-purple-hat-icon' => 'miss-purple-hat-icon',

						    ),
						    'validation' => array('not_empty', 'not_equal' => 'default')
						  ),
                        'submit' => [
                            'type'      => 'submit',
                            'value'         => 'Change img',
                            'callback'  => function () {
                                $this->users->updateProfile([
                                    'profileImg' => $this->form->Value('profileImg')
                                    ], $this->session->get('authenticated', [])[1]
                                );
                                $this->response->redirect($this->url->create('user/profile/' . $this->session->get('authenticated', [])[1]));
                                return true;
                            }
                        ],
                       ]);
                		$form->check();
                		$prof = $form->getHTML();
			    }

		$this->views->add('users/profile', [
		        'profile' => $profile[0],
		        'questions' => $profile[1],
		        'comments' => $profile[2],
		        'responses' => $profile[3],
		        'profileImg' => $prof,
		    ]);
		
		
	}
	public function loginAction() 
	{
		if ($this->session->get('authenticated', []))
		{
			$url = $this->url->create('user/list');
			$this->response->redirect($url);
			return true;
		}
		$form = $this->form->create([], [
	     'acronym' => [
	         'type'        => 'text',
	         'label'       => 'Akronym',
	         'required'    => true,
	         'validation'  => ['not_empty'],
	     ],
	     'password' => [
	         'type'        => 'password',
	         'label'       => 'Lösenord',
	         'required'    => true,
	         'validation'  => ['not_empty'],
	     ],
	     'submit' => [
	         'type'      => 'submit',
	         'callback'  => function () {
			    $auth = $this->users->auth([
			         'password' => $this->form->Value('password'),
			         'acronym' => $this->form->Value('acronym'),
			     ]);
			    if (password_verify($this->form->Value('password'), $auth->getProperties()['password']) == 1)
			    {
			    	$this->session->set('authenticated', [true, $this->form->Value('acronym')]);
			    	$url = $this->url->create('user/profile');
				     $this->response->redirect($url);
				     return true;
			    }
			}
	    ],
	    ]);
	    $form->check();  
	    $this->views->add('default/page', [
	        'content' => $form->getHTML(),
	        'title' => "Logga in"
	    ]);
	}
	public function addAction() 
	{
	    $this->theme->setTitle("Create account");
	    /**
	     * Creating submition form to add user
	     */
	    $form = $this->form->create([], [

	     'acronym' => [
	         'type'        => 'text',
	         'label'       => 'Akronym',
	         'required'    => true,
	         'validation'  => ['not_empty'],
	     ],
	     'password' => [
	         'type'        => 'password',
	         'label'       => 'Lösenord',
	         'required'    => true,
	         'validation'  => ['not_empty'],
	     ],
	     'submit' => [
	         'type'      => 'submit',
	         'callback'  => function () {
	     /**
	      * Storing date in seconds to know when account was created
	      */
	     $now = gmdate('Y-m-d H:i:s');
	     $this->users->save([
	         'password' => password_hash($this->form->Value('password'), PASSWORD_DEFAULT),
	         'acronym' => $this->form->Value('acronym'),
	         'created' => $now,
	         'profileImg' => 'chef-icon',
	     ], 123, 123, [1234]);
	     /**
	      * Redirect to list page to see all users
	      */
	     $url = $this->url->create('user/profile');
	     $this->response->redirect($url);
	     return true;
	     }
	     ],
	    ]);
	    $form->check();  
	    $this->views->add('default/page', [
	        'content' => $form->getHTML(),
	        'title' => "Create account"
	    ]);
	}
    /**
	 * List all users.
	 *
	 * @return void
	 */
	public function listAction($searchTerm = null)
	{
	    if (!$this->session->get('authenticated', []))
	    {
	    	$url = $this->url->create('user/login');
			$this->response->redirect($url);
	    	return false;
	    }
	    
	    $form = $this->form->create([], 
	    	[
		     'search' => [
		         'type'        => 'text',
		         'label'       => 'Sök användare',
		         'required'    => true,
		         'validation'  => ['not_empty'],
		     ],
		     'submit' => [
		         'type'      => 'submit',
		         'callback'  => function () {
		     	 $url = $this->url->create('user/list/' . $this->form->Value('search'));
				 $this->response->redirect($url);
		    	 return false;
		     	}
		     ],
	    	]);
	    /** 
	     * Display menu
	     */
	    $this->views->add('users/menu', []);
	    
	    /**
	     * Display form
	     */
	    $form->check(); 
	    $this->views->add('default/page', [
	        'content' => $form->getHTML(),
	        'title' => ""
	    ]);

	    /**
	     * Display search result
	     */
	    $all = $this->users->findAll();
	    $title = 'Visar alla användare';
	    if (isset($searchTerm))
	    {
	    	$title = 'Sök resultat på: ' . $searchTerm;
	    	$all = $this->users->search($searchTerm);
	    }
	    $this->theme->setTitle("List all users");
	    $this->views->add('users/list-all', [
	        'users' => $all,
	        'title' => $title,
	    ]);
	}
	public function indexAction() 
    {
    	if (!$this->session->get('authenticated', []))
	    {
	    	$url = $this->url->create('user/login');
			$this->response->redirect($url);
	    	return false;
	    }
        $this->theme->setTitle("Användare");        
        $all = $this->users->generateUsers();
        
    	$this->views->add('users/welcome', [
	       'users' => $all,
	       'title' => "Användare",
	       ]);
    }
	
	/**
	 * List user with id.
	 *
	 * @param int $id of user to display
	 *
	 * @return void
	 */
	public function idAction($id = null)
	{
		if (!$this->session->get('authenticated', []))
	    {
	    	$url = $this->url->create('user/login');
			$this->response->redirect($url);
	    	return false;
	    }
	    $this->users = new \Anax\Users\User();
	    $this->users->setDI($this->di);

	    $user = $this->users->find($id);

	    $this->theme->setTitle("View user with id");
	    $this->views->add('users/view', [
	    	'title' => 'Visar info',
	        'user' => $user
	    ]);
	}
	/**
	 * Delete user.
	 *
	 * @param integer $id of user to delete.
	 *
	 * @return void
	 */
	public function deleteAction($id = null)
	{
		if (!$this->session->get('authenticated', []))
	    {
	    	$url = $this->url->create('user/login');
			$this->response->redirect($url);
	    	return false;
	    }
	    if (!isset($id)) {
	        die("Missing id");
	    }
	    $res = $this->users->delete($id);

	    $url = $this->url->create('user/list');
	    $this->response->redirect($url);
	}
	public function updateAction($id) {
		if (!$this->session->get('authenticated', []))
	    {
	    	$url = $this->url->create('user/login');
			$this->response->redirect($url);
	    	return false;
	    }
        $this->theme->setTitle("Ändra Användare");
        $form = $this->form->create([], [
	        'id' => [
	            'type'        => 'hidden',
	            'value'       => "{$this->users->getValue('id', $id)}",
	        ],
	        'name' => [
	            'type'        => 'text',
	            'label'       => 'Namn',
	            'required'    => true,
	            'validation'  => ['not_empty'],
	            'value'       => "{$this->users->getValue('name', $id)}",
	        ],
	        'password' => [
	            'type'        => 'password',
	            'label'       => 'Lösenord',
	            'required'    => true,
	            'validation'  => ['not_empty'],
	            'value'       => "{$this->users->getValue('password', $id)}",
	        ],
	        'acronym' => [
	            'type'        => 'text',
	            'label'       => 'Akronym',
	            'required'    => true,
	            'validation'  => ['not_empty'],
	            'value'       => "{$this->users->getValue('acronym', $id)}",
	        ],
	        'email' => [
	            'type'        => 'text',
	            'label'       => 'Email',
	            'required'    => true,
	            'validation'  => ['not_empty', 'email_adress'],
	            'value'       => "{$this->users->getValue('email', $id)}",
	        ],
	        'submit' => [
	            'type'      => 'submit',
	            'callback'  => function () {
	            /**
			      * Storing date in seconds to know when account was created
			    */
	            $now = gmdate('Y-m-d H:i:s');
	            
	        $this->users->save([
	            'id'            => $this->form->Value('id'),
	            'name' => $this->form->Value('name'),
	            'password' => password_hash($this->form->Value('password'), PASSWORD_DEFAULT),
	            'acronym' => $this->form->Value('acronym'),
	            'email' => $this->form->Value('email'),
	            'updated' => $now,
	    ]);
	    /**
	     * Redirect to user by id page to see changes
	     */
        $url = $this->url->create('user/list');
        $this->response->redirect($url);
        return true;
        
        }
        ],        
        ]);
        $form->check();
        $this->views->add('default/page', [
           'title' => "Ändra användare",
           'content' => $form->getHTML() 
        ]);
    }	
	/**
	 * Delete (soft) user.
	 *
	 * @param integer $id of user to delete.
	 *
	 * @return void
	 */
	public function softDeleteAction($id = null)
	{
		if (!$this->session->get('authenticated', []))
	    {
	    	$url = $this->url->create('user/login');
			$this->response->redirect($url);
	    	return false;
	    }
	    if (!isset($id)) {
	        die("Missing id");
	    }

	    $now = gmdate('Y-m-d H:i:s');

	    $user = $this->users->find($id);

	    $user->deleted = $now;
	    $user->save();
	    $all = $this->users->findAll();
	    $this->theme->setTitle("View soft deleted users");
	    $this->views->add('users/bin', [
	        'users' => $all
	    ]);
	}
	/**
	 * Undelete (soft) user.
	 *
	 * @param integer $id of user to undelete.
	 *
	 * @return void
	 */
	public function unSoftDeleteAction($id = null)
	{
		if (!$this->session->get('authenticated', []))
	    {
	    	$url = $this->url->create('user/login');
			$this->response->redirect($url);
	    	return false;
	    }
	    if (!isset($id)) {
	        die("Missing id");
	    }
	    $user = $this->users->find($id);
	    $user->deleted = NULL;
	    $user->save();
	    $all = $this->users->findAll();
	    $this->theme->setTitle("View soft deleted users");
	    $this->views->add('users/bin', [
	        'users' => $all
	    ]);
	}
	public function binAction()
	{	
		if (!$this->session->get('authenticated', []))
	    {
	    	$url = $this->url->create('user/login');
			$this->response->redirect($url);
	    	return false;
	    }
		$all = $this->users->findAll();
	    $this->theme->setTitle("View soft deleted users");
	    $this->views->add('users/bin', [
	        'users' => $all
	    ]);
	}
	public function avaliableAction()
	{	
		if (!$this->session->get('authenticated', []))
	    {
	    	$url = $this->url->create('user/login');
			$this->response->redirect($url);
	    	return false;
	    }
		$all = $this->users->findAll();
	    $this->theme->setTitle("View all avaliable users");
	    $this->views->add('users/list-avaliable', [
	        'users' => $all
	    ]);
	}

	/**
	 * List all active and not deleted users.
	 *
	 * @return void
	 */
	public function activeAction($id = null)
	{
		if (!$this->session->get('authenticated', []))
	    {
	    	$url = $this->url->create('user/login');
			$this->response->redirect($url);
	    	return false;
	    }
		if (!isset($id)) {
	        die("Missing id");
	    }
	    $now = gmdate('Y-m-d H:i:s');
	    $user = $this->users->find($id);
	    $user->active = $now;
	    $user->save();
	    $all = $this->users->findAll();
	    $this->theme->setTitle("View inactive users");
	    $this->views->add('users/active', [
	        'users' => $all,
	        'active' => true,
	        'title' => 'Aktiva användare'
	    ]);
	}
	public function inActiveAction($id = null)
	{
		if (!$this->session->get('authenticated', []))
	    {
	    	$url = $this->url->create('user/login');
			$this->response->redirect($url);
	    	return false;
	    }
		if (!isset($id)) {
	        die("Missing id");
	    }
	    $user = $this->users->find($id);
	    $user->active = NULL;
	    $user->save();
	    $all = $this->users->findAll();
	    $this->theme->setTitle("View inactive users");
	    $this->views->add('users/active', [
	        'users' => $all,
	        'active' => false,
	        'title' => 'Inaktiva användare'
	    ]);
	}
}