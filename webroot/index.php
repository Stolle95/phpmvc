<?php
require __DIR__.'/config_with_app.php';
$di->setShared('db', function() {
    $db = new \Mos\Database\CDatabaseBasic();
    $db->setOptions(require ANAX_APP_PATH . 'config/database_mysql.php');
    $db->connect();
    return $db;
});

$di->set('UserController', function() use ($di) {
    $controller = new \Anax\Users\UserController();
    $controller->setDI($di);
    return $controller;
});

$app->url->setUrlType(\Anax\Url\CUrl::URL_CLEAN);
$app->theme->configure(ANAX_APP_PATH . 'config/theme_me.php');
$app->theme->configure(ANAX_APP_PATH . 'config/navbar.php');

$app->router->add('', function() use ($app)
{
    $app->dispatcher->forward([
        'controller' => 'question',
        'action'     => 'home',
    ]); 
});

$app->router->add('about', function() use ($app)
{
    // Prepare the page content
    $app->theme->setVariable('title', "Hello World me-sida")
               ->setVariable('main', "
        <h1>About Partoy</h1>
        <p>Partoy provides a question community where developers help out each other. Partoy uses Anax MVC as the structure and currently hosted at student.bth.se, create an account and ask your question on contribute by answering other questions.

        Developer: Filip Brännlund Stål, filip.brannlundstal@gmail.com. Feel free to contact me.</p>
    ");
});
/*
	Redovisnings sida
*/
$app->router->add('redovisning', function() use ($app) {
    $app->theme->setTitle("Redovisning");

    $content = $app->fileContent->get('redovisning.md');
    $content = $app->textFilter->doFilter($content, 'shortcode, markdown');

    $byline = $app->fileContent->get('byline.md');
    $byline = $app->textFilter->doFilter($byline, 'shortcode, markdown');

    $app->views->add('me/page', [
        'content' => $content,
        'byline' => $byline,
    ]);

});
/*
	Source sida
*/
$app->router->add('source', function() use ($app)
{
 	$app->theme->addStylesheet('css/source.css');
    $app->theme->setTitle("Källkod");

    $source = new \Mos\Source\CSource([
        'secure_dir' => '..',
        'base_dir' => '..',
        'add_ignore' => ['.htaccess'],
    ]);

    $app->views->add('me/source', [
        'content' => $source->View(),
    ]);
});

// comment controller
$di->set('QuestionController', function() use ($di) {
    $controller = new \Anax\Question\QuestionController();
    $controller->setDI($di);
    return $controller;
});

$app = new \Anax\Kernel\CAnax($di);

//Comment php
$app->router->add('comment/php', function() use ($app) {

    $app->theme->setTitle("Vad tycker du om php?");

    $app->views->add('comment/index');

    $app->dispatcher->forward([
        'controller' => 'comment',
        'action'     => 'view',
				'params'     => ['php'], #Om jag kommenterar bort denna rad så visas rätt kommentaren, annars visas ingenting
    ]);

    $app->views->add('comment/form', [
        'mail'      => null,
        'web'       => null,
        'name'      => null,
        'content'   => null,
        'output'    => null,
				'pagekey'   => 'php',
    ]);

});

//Comment javascript
$app->router->add('comment/js', function() use ($app) {

    $app->theme->setTitle("Vad tycker du om javascript?");
    $app->views->add('comment/index');

    $app->dispatcher->forward([
        'controller' => 'comment',
        'action'     => 'view',
				'params'     => ['js'],
    ]);

    $app->views->add('comment/form', [
        'mail'      => null,
        'web'       => null,
        'name'      => null,
        'content'   => null,
        'output'    => null,
				'pagekey'   => 'js',
    ]);

});

$app->router->add('setup', function() use ($app) {

    //$app->db->setVerbose();

    $app->db->dropTableIfExists('user')->execute();

    $app->db->createTable(
        'user',
        [
            'id' => ['integer', 'primary key', 'not null', 'auto_increment'],
            'acronym' => ['varchar(20)', 'unique', 'not null'],
            'email' => ['varchar(80)'],
            'name' => ['varchar(80)'],
            'password' => ['varchar(255)'],
            'created' => ['datetime'],
            'updated' => ['datetime'],
            'deleted' => ['datetime'],
            'active' => ['datetime'],
        ]
    )->execute();
		$app->db->insert(
        'user',
        ['acronym', 'email', 'name', 'password', 'created', 'active']
    );

    $now = gmdate('Y-m-d H:i:s');

    $app->db->execute([
        'admin',
        'admin@dbwebb.se',
        'Administrator',
        password_hash('admin', PASSWORD_DEFAULT),
        $now,
        $now
    ]);

    $app->db->execute([
        'doe',
        'doe@dbwebb.se',
        'John/Jane Doe',
        password_hash('doe', PASSWORD_DEFAULT),
        $now,
        $now
    ]);
});

$app->router->add('setupComment', function() use ($app) {

    //$app->db->setVerbose();

    $app->db->dropTableIfExists('comment')->execute();

    $app->db->createTable(
        'comment',
        [
            'id' => ['integer', 'primary key', 'not null', 'auto_increment'],
            'name' => ['varchar(80)'],
            'comment' => ['text'],
            'created' => ['datetime'],
        ]
    )->execute();
        $app->db->insert(
        'comment',
        ['name', 'comment', 'created']
    );
});
// Router for viewing and editing users
$app->router->add('user', function () use ($app) {
    $app->theme->setTitle("Visa alla användare");
    $app->dispatcher->forward([
        'controller' => 'user',
        'action'     => 'list',
    ]);

});


// Router for comment with database attached
$app->router->add('question', function () use ($app) {
    $app->dispatcher->forward([
        'controller' => 'question',
        'action'     => 'view',
    ]); 
 });
$app->router->handle();
$app->theme->render();
