<?php
require __DIR__.'/config_with_app.php';
$app->url->setUrlType(\Anax\Url\CUrl::URL_CLEAN);
$app->theme->configure(ANAX_APP_PATH . 'config/navbar.php');
$app->theme->configure(ANAX_APP_PATH . 'config/pink-grid.php');



$app->router->add('', function() use ($app) {
		$content = $app->fileContent->get('fontAwesome.md');
		$sidebar = $app->fileContent->get('fontSidebar.md');
    $app->theme->addStylesheet('css/pink-grid/regions_demo.css');
    $app->theme->setTitle("Regioner");

    $app->views->addString('<h1>Hello, welcome to my customize responsive theme!</h1>', 'flash')
               ->addString('<h2>Features</h2><ul><li>FontAwesome</li><li>Regions</li></ul>', 'featured-1')
               ->addString('<h2>News</h2><p>New Yorks population have just readed 1 billion.</p>', 'featured-2')
               ->addString('<h2>Contact</h2><p>Contact me at me@theBest.com</p>', 'featured-3')
               ->addString('<h3>To do list</h3><ul><li>Get a life</li><li>Eat kebab</li><li>Code </li>', 'main')
               ->addString('<img src="https://s-media-cache-ak0.pinimg.com/736x/51/aa/c8/51aac8e5328790194fc4220dfd88c1f7.jpg" height="150" width="150">', 'sidebar')
               ->addString('<h4>This is sarcasm</h4>', 'footer-col-1')
               ->addString('<h4>This is sarcasm</h4>', 'footer-col-2')
               ->addString('<h4>This is sarcasm</h4>', 'footer-col-3')
               ->addString('<i class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i>', 'footer-col-4');



});



$app->router->handle();
$app->theme->render();
