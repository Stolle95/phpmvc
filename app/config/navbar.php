<?php
/**
 * Config-file for navigation bar.
 *
 */
return [

    // Use for styling the menu
    'class' => 'navbar',

    // Here comes the menu strcture
    'items' => [

        // This is a menu item
        'home'  => [
            'text'  => 'Home',
            'url'   => $this->di->get('url')->create(''),
            'title' => 'Home route of current frontcontroller'
        ],

        /*// This is a menu item
        'test'  => [
            'text'  => 'Submenu',
            'url'   => $this->di->get('url')->create('submenu'),
            'title' => 'Submenu with url as internal route within this frontcontroller',

            // Here we add the submenu, with some menu items, as part of a existing menu item
            'submenu' => [

                'items' => [

                    // This is a menu item of the submenu
                    'item 0'  => [
                        'text'  => 'Item 0',
                        'url'   => $this->di->get('url')->create('submenu/item-0'),
                        'title' => 'Url as internal route within this frontcontroller'
                    ],

                    // This is a menu item of the submenu
                    'item 2'  => [
                        'text'  => '/humans.txt',
                        'url'   => $this->di->get('url')->asset('/humans.txt'),
                        'title' => 'Url to sitespecific asset',
                        'class' => 'italic'
                    ],

                    // This is a menu item of the submenu
                    'item 3'  => [
                        'text'  => 'humans.txt',
                        'url'   => $this->di->get('url')->asset('humans.txt'),
                        'title' => 'Url to asset relative to frontcontroller',
                    ],
                ],
            ],
        ],*/

       /* // This is a menu item
        'Redovisning' => [
            'text'  =>'Redovisning',
            'url'   => $this->di->get('url')->create('redovisning'),
            'title' => 'Läs mina redovisningar'
        ],

        // This is a menu item
        'Källkod' => [
            'text'  =>'Källkod',
            'url'   => $this->di->get('url')->create('source'),
            'title' => 'Källkod'
        ],
        'Mitt tema' => [
            'text'  =>'Mitt tema',
            'url'   => 'myTheme.php',
            'title' => 'Tema'
        ],*/
        'Profile' => [
            'text'  =>'Profile',
            'url'   => $this->di->get('url')->create('user/profile'),
            'title' => 'Your profile'
        ],
        'Questions' => [
            'text'  =>'Questions',
            'url'   => $this->di->get('url')->create('question/'),
            'title' => 'View all questions'
        ],
        'Login' => [
            'text'  =>'Login',
            'url'   => $this->di->get('url')->create('user/login'),
            'title' => 'Login'
        ],
        'Ask question' => [
            'text'  =>'Ask question',
            'url'   => $this->di->get('url')->create('question/ask'),
            'title' => 'Ask question'
        ],
        'Create account' => [
            'text'  =>'Create account',
            'url'   => $this->di->get('url')->create('user/add'),
            'title' => 'Ask question'
        ],
        'About' => [
            'text'  =>'About',
            'url'   => $this->di->get('url')->create('about'),
            'title' => 'About'
        ]
        

    ],



    /**
     * Callback tracing the current selected menu item base on scriptname
     *
     */
    'callback' => function ($url) {
        if ($url == $this->di->get('request')->getCurrentUrl(false)) {
            return true;
        }
    },



    /**
     * Callback to check if current page is a decendant of the menuitem, this check applies for those
     * menuitems that has the setting 'mark-if-parent' set to true.
     *
     */
    'is_parent' => function ($parent) {
        $route = $this->di->get('request')->getRoute();
        return !substr_compare($parent, $route, 0, strlen($parent));
    },



   /**
     * Callback to create the url, if needed, else comment out.
     *
     */
   /*
    'create_url' => function ($url) {
        return $this->di->get('url')->create($url);
    },
    */
];
