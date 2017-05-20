<?php
/**
 * This is a Anax pagecontroller.
 *
 */
// Include the essential settings.
require __DIR__.'/config.php';


// Create services and inject into the app.
$di  = new \Anax\DI\CDIFactoryDefault();

$di->set('CommentController', function() use ($di) {
    $controller = new Phpmvc\Comment\CommentController();
    $controller->setDI($di);
    return $controller;
});

$app = new \Anax\Kernel\CAnax($di);




// Home route
$app->router->add('', function() use ($app) {

    $app->theme->setTitle("Gästbok");
    $app->views->add('comment/index');

    $app->dispatcher->forward([
        'controller' => 'comment',
        'action'     => 'view',
    ]);

    $app->views->add('comment/form', [
        'mail'      => null,
        'web'       => null,
        'type'      => 'home',
        'name'      => null,
        'content'   => null,
        'output'    => null,
    ]);

});


// Edit comment route
$app->router->add('edit', function() use ($app) {

    // Make sure that a ID is available
    if (!isset($_GET['id'])) {
        die("No ID specified");
    }

    $commentId = (int)$_GET['id'];

    $app->theme->setTitle("Redigera inlägg");

    $app->dispatcher->forward([
        'controller' => 'comment',
        'action'     => 'editComment',
        'params'     => array($commentId),
    ]);

});

// Delete a comment route
$app->router->add('delete', function() use ($app) {

    // Make sure that a ID is available
    if (!isset($_GET['id'])) {
        die("No ID specified");
    }

    $commentId = (int)$_GET['id'];

    $app->dispatcher->forward([
        'controller' => 'comment',
        'action'     => 'removeComment',
        'params'     => array($commentId),
    ]);
});
// Check for matching routes and dispatch to controller/handler of route
$app->router->handle();

// Render the page
$app->theme->render();
