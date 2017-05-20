<?php
namespace Anax\Question;

/**
 * To attach comments-flow to a page or some content.
 *
 */
class QuestionController implements \Anax\DI\IInjectionAware
{
    use \Anax\DI\TInjectable;
    public function initialize()
    {
        $this->form = new \Mos\HTMLForm\CForm();
        $this->question = new \Anax\Question\Question();
        $this->question->setDI($this->di);
    }

    public function respondAction($commentId)
    {
        $now = gmdate('Y-m-d H:i:s');
        $this->question->save([
            'comment' => $_POST['respond'],
            'created' => $now,
            'user'    => $this->session->get('authenticated', [])[1],
            ], 
            'respond',  
            $this->session->get('authenticated', [])[1],
            [$commentId]
        );
        $this->response->redirect($this->url->create('question/view/' . $this->session->get('questionId', [])));
        return true;
    }
    public function homeAction()
    {
        $this->theme->setTitle("Home - Partoy");
        $all = $this->question->findAllQuestions();
        $tags = $this->question->getPopTags();
        $users = $this->question->getPopUsers();
        $this->views->add('question/index', [
                'questions' => $all[0],
                'tags' => $tags,
                'users' => $users,
        ]); 
    }
    /**
     * View all comments.
     *
     * @return void
     */
    public function viewAction($id = null)
    {
        $this->theme->setTitle("Question - Partoy");

        $isLogged = true;
        if ($id === null)
        {
            $all = $this->question->findAllQuestions();
            $this->views->add('question/questions', [
                'title'     => 'Displaying all questions:',
                'questions' => $all[0],
                'tags' => $all[1],
            ]);           
        }
        else if (!is_numeric($id))
        {   

            $all = $this->question->findQuestionsByTag($id);
            $this->views->add('question/questions', [
                'title'     => 'Search term: ' . "'". $id . "'",
                'questions' => $all,
                'tags'      => [],
            ]);
        }
        else 
        {
            $all = $this->question->findQuestion($id);
            $comments = $this->question->findComments($id);
            $responses = $this->question->findResponses();
            $this->theme->setTitle("Question");
            $this->session->set('questionId', $id);

            if ($this->session->get('authenticated', []))
            {
                $form = $this->form->create([], [
                        'answer' => [
                            'type'        => 'textarea',
                            'label'       => 'Comment on this question',
                            'required'    => true,
                            'validation'  => ['not_empty'],
                        ],     
                        'submit' => [
                            'type'      => 'submit',
                            'value'         => 'Post answer',
                            'callback'  => function () {
                                $now = gmdate('Y-m-d H:i:s');
                                $this->question->save([
                                    'comment' => $this->form->Value('answer'),
                                    'created' => $now,
                                    'questionId' => $this->session->get('questionId', []),
                                ], 
                                'comment',  
                                $this->session->get('authenticated', [])[1]
                                );
                                $this->response->redirect($this->url->create('question/view/' . $this->session->get('questionId', [])));
                                return true;
                            }
                        ],
                       ]);
                $form->check(); 
                $content = $form->getHTML();
            }
            else 
            {
                $content = 'Please login to answer this question.';
                $isLogged = False;
            }
            foreach ($all[0] as $key => $value) {
                $all[0][$key]->content = $this->textFilter->doFilter($all[0][$key]->content, "markdown");
            }
            foreach ($comments as $key => $value) {
                $comments[$key]->comment = $this->textFilter->doFilter($comments[$key]->comment, "markdown");
            }
            foreach ($responses as $key => $value) {
                $responses[$key]->comment = $this->textFilter->doFilter($responses[$key]->comment, "markdown");
            }
            $this->views->add('question/question', [
                'title'     => $all[0][0]->title,
                'questions' => $all[0],
                'tags' => $all[1],
                'comments' => $comments,
                'content' => $content,
                'responds' => $responses,
                'isLogged' => $isLogged,
                            ]);
        }

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

    public function askAction() {
        if (!$this->session->get('authenticated', []))
        {
            $url = $this->url->create('user/login');
            $this->response->redirect($url);
            return false;
        }
        $tags = $this->question->getTags();
        $tagArr = array();
        foreach ($tags as $key => $value) {
            //print_r($tags[$key]->name);
            array_push($tagArr, $tags[$key]->name);
        }
        $this->theme->setTitle("Ask a question!");
        $form = $this->form->create([], [
                'title' => [
                    'type'        => 'text',
                    'label'       => 'Question title',
                    'required'    => true,
                    'validation'  => ['not_empty'],
                ],
                'content' => [
                    'type'        => 'textarea',
                    'label'       => 'Your question',
                    'required'    => true,
                    'validation'  => ['not_empty'],
                ],
                'tags' => array(
                  'type'        => 'checkbox-multiple',
                  'values'      => $tagArr,                
                  ),       
                'submit' => [
                    'type'      => 'submit',
                    'value'         => 'Post question',
                    'callback'  => function () {
                        $now = gmdate('Y-m-d H:i:s');
                        $this->question->save([
                            'title' => $this->form->Value('title'),
                            'content' => $this->form->Value('content'),
                            'created' => $now,
                        ], 'questionHandler', 
                        $this->session->get('authenticated', [])[1],
                        $this->form->Value('tags')
                        );
                        $this->response->redirect($this->url->create('question'));
                        return true;
                    }
                ],
               ]);
        $form->check(); 
        $this->views->add('default/page', [
            'content' => $form->getHTML(),
            'title' => "Posta en kommentar",
            'tags' => $tags
        ]);
        /*$all = $this->comment->getComments();        
        $this->views->add('comment/commentsdb', [
            'comments' => $all,
        ]);*/
    }
}
