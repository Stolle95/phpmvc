<?php
namespace Anax\Comment;

/**
 * To attach comments-flow to a page or some content.
 *
 */
class CommentController implements \Anax\DI\IInjectionAware
{
    use \Anax\DI\TInjectable;
    public function initialize()
    {
        $this->form = new \Mos\HTMLForm\CForm();
        $this->comment = new \Anax\Comment\Comment();
        $this->comment->setDI($this->di);
    }
    /**
     * View all comments.
     *
     * @return void
     */
    public function viewAction($pagekey = null)
    {
        $comments = new \Phpmvc\Comment\CommentsInSession();
        $comments->setDI($this->di);
        $all = $comments->findAll($pagekey);

        $this->views->add('comment/comments', [
            'comments' => $all,
        ]);
    }



    /**
     * Add a comment.
     *
     * @return void
     */
    public function addAction()
    {
        $isPosted = $this->request->getPost('doCreate');
        if (!$isPosted) {
            $this->response->redirect($this->request->getPost('redirect'));
        }
        $pagekey = $this->request->getPost('pagekey');
        $comment = [
            'content'   => $this->request->getPost('content'),
            'web'      => $this->request->getPost('web'),
            'mail'      => $this->request->getPost('mail'),
            'name'      => $this->request->getPost('name'),
            'timestamp' => date('l jS \of F Y h:i:s A'),
            'pagekey'   => $this->request->getPost('pagekey'),
        ];

        $comments = new \Phpmvc\Comment\CommentsInSession();
        $comments->setDI($this->di);

        $comments->add($comment, $pagekey);

        $this->response->redirect($this->request->getPost('redirect'));
    }
    /**
     * Show form to edit comment
     *
     *
     *
     */
    public function editViewAction($id, $pagekey)
    {
        $comments = new \Phpmvc\Comment\CommentsInSession();
        $comments->setDI($this->di);

        $this->theme->setTitle("Redigera kommentar");
        #$this->theme->addStylesheet('css/form.css');
        #$this->theme->addStylesheet('css/comment.css');

        $comment = $comments->findOne($id, $pagekey);
        $this->views->add('comment/edit', [
            'comment'  => $comment,
            'id'       => $id,
            'pagekey'  => $pagekey,
        ]);

    }

    /**
     * Edit a comment
     *
     *
     */
    public function editAction($id, $pagekey)
    {
        $isPosted = $this->request->getPost('doEdit');
        if (!$isPosted) {
            $this->response->redirect($this->request->getPost('redirect'));
        }
        $content = $this->textFilter->doFilter($this->request->getPost('content'), "nl2br");
        $comment = [
            'content'   => $content,
            'name'      => $this->request->getPost('name'),
            'web'       => $this->request->getPost('web'),
            'mail'      => $this->request->getPost('mail'),
            'timestamp' => date('l jS \of F Y h:i:s A'),
            'pagekey'   => $pagekey,
        ];
        $comments = new \Phpmvc\Comment\CommentsInSession();
        $comments->setDI($this->di);
        $comments->edit($comment, $id, $pagekey);
        $this->response->redirect($this->request->getPost('redirect'));

    }
    /**
     * Remove comment.
     *
     * @return void
     */
    public function removeAction($id, $pagekey)
    {

        $comments = new \Phpmvc\Comment\CommentsInSession();
        $comments->setDI($this->di);
        $comments->delete($id, $pagekey);
        $this->response->redirect($this->request->getPost('redirect'));
    }
    /**
     * Remove all comments.
     *
     * @return void
     */
    public function removeAllAction()
    {
        $isPosted = $this->request->getPost('doRemoveAll');

        if (!$isPosted) {
            $this->response->redirect($this->request->getPost('redirect'));
        }
        $comments = new \Phpmvc\Comment\CommentsInSession();
        $comments->setDI($this->di);
        $comments->deleteAll();
        $this->response->redirect($this->request->getPost('redirect'));
    }

    public function indexAction() {
        $this->theme->setTitle("Posta en kommentar");
        $form = $this->form->create([], [
                'name' => [
                    'type'        => 'text',
                    'label'       => 'Namn',
                    'required'    => true,
                    'validation'  => ['not_empty'],
                ],
                'comment' => [
                    'type'        => 'textarea',
                    'label'       => 'Kommentar',
                    'required'    => true,
                    'validation'  => ['not_empty'],
                ],
                'submit' => [
                    'type'      => 'submit',
                    'value'         => 'Posta kommentar',
                    'callback'  => function () {
                        $now = gmdate('Y-m-d H:i:s');
                        $this->comment->save([
                            'name' => $this->form->Value('name'),
                            'comment' => $this->form->Value('comment'),
                            'created' => $now,
                        ]);
                        $this->response->redirect($this->url->create('comment'));
                        return true;
                    }
                ],
               ]);
        $form->check();  
        $this->views->add('default/page', [
            'content' => $form->getHTML(),
            'title' => "Posta en kommentar"
        ]);
        $all = $this->comment->getComments();        
        $this->views->add('comment/commentsdb', [
            'comments' => $all,
        ]);
    }
}
