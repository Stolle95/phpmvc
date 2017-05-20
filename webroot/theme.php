<?php
require __DIR__.'/config_with_app.php';
$app->url->setUrlType(\Anax\Url\CUrl::URL_CLEAN);
$app->theme->configure(ANAX_APP_PATH . 'config/navbar.php');
$app->theme->configure(ANAX_APP_PATH . 'config/theme-grid.php');
$app->router->add('', function() use ($app)
{
	// Prepare the page content
	$app->theme->setVariable('title', "Hello World me-sida")
	           ->setVariable('main', "<h1>Nytt tema</h1>");
});

$app->router->add('regioner', function() use ($app) {

    $app->theme->addStylesheet('css/anax-grid/regions_demo.css');
    $app->theme->setTitle("Regioner");

    $app->views->addString('flash', 'flash')
               ->addString('featured-1', 'featured-1')
               ->addString('featured-2', 'featured-2')
               ->addString('featured-3', 'featured-3')
               ->addString('main', 'main')
               ->addString('sidebar', 'sidebar')
               ->addString('triptych-1', 'triptych-1')
               ->addString('triptych-2', 'triptych-2')
               ->addString('triptych-3', 'triptych-3')
               ->addString('footer-col-1', 'footer-col-1')
               ->addString('footer-col-2', 'footer-col-2')
               ->addString('footer-col-3', 'footer-col-3')
               ->addString('footer-col-4', 'footer-col-4');

});

$app->router->add('fontAwesome', function() use ($app) {
    $app->theme->setTitle('Font Awesome');

    $content = $app->fileContent->get('fontAwesome.md');
    $sidebar = $app->fileContent->get('fontSidebar.md');

    $app->views->addString($sidebar, 'sidebar');

    $app->views->add('theme/fontAwesome', [
        'content' => $content,
    ]);




}); 

$app->router->handle();
$app->theme->render();
