<?php
namespace Anax\MVC;

/**
 * Model for Users.
 *
 */
class CDatabaseModel implements \Anax\DI\IInjectionAware
{
    use \Anax\DI\TInjectable;

    public function getSource()
    {
        return strtolower(implode('', array_slice(explode('\\', get_class($this)), -1)));
    }
	 /**
	 * Find and return specific.
	 *
	 * @return this
	 */
	public function getPopTags()
	{
		$this->db->select("tag.name, COUNT(*) cnt")
                 ->from("`tagHandler` INNER JOIN tag ON tag.id=tagHandler.tagId GROUP BY tag.name ORDER BY COUNT(*) DESC LIMIT 5");
     
        $this->db->execute();
        return $this->db->fetchAll();	
	}
	public function getProfile($acronym)
	{
	    $this->db->select()
	             ->from($this->getSource())
	             ->where("acronym = ?");
	    $this->db->execute([$acronym]);
	    $res[0] = $this->db->fetchAll();

        $this->db->select("question.title, question.id, question.created, user.acronym")
                 ->from("questionHandler INNER JOIN user ON user.id=questionHandler.userId INNER JOIN question ON question.id=questionHandler.questionId WHERE user.acronym=?");
     
        $this->db->execute([$acronym]);
        $res[1] = $this->db->fetchAll();

        $this->db->select("comment.questionId, comment.comment, user.acronym")
                 ->from("commentHandler INNER JOIN user ON user.id=commentHandler.userId INNER JOIN comment ON comment.id=commentHandler.commentId WHERE user.acronym=?");
     
        $this->db->execute([$acronym]);
        $res[2] = $this->db->fetchAll();

        $this->db->select("comment.questionId, response.comment, response.user")
                 ->from("responseHandler INNER JOIN response ON response.id=responseHandler.responseId INNER JOIN comment ON comment.id=responseHandler.commentId WHERE response.user=?");
     
        $this->db->execute([$acronym]);
        $res[3] = $this->db->fetchAll();

        return $res;
	}

	public function find($id)
	{
	    $this->db->select()
	             ->from($this->getSource())
	             ->where("id = ?");
	    $this->db->execute([$id]);
	    return $this->db->fetchInto($this);
	}



	public function auth($user) 
	{
		$this->db->select()
	             ->from($this->getSource())
	             ->where("acronym = ?");
	    $this->db->execute([$user['acronym']]);
		return $this->db->fetchInto($this);
	}
	public function findAll()
    {
        $this->db->select()
                 ->from($this->getSource());
     
        $this->db->execute();
        $this->db->setFetchModeClass(__CLASS__);
        return $this->db->fetchAll();
    }
    public function findAllQuestions()
    {
        $this->db->select("question.title, question.content, question.id, user.acronym, question.created")
                 ->from("questionHandler INNER JOIN user ON user.id=questionHandler.userId INNER JOIN question ON question.id=questionHandler.questionId");
     
        $this->db->execute();
        $res[0] = $this->db->fetchAll();

        $this->db->select("tag.name, tagHandler.questionId")
                 ->from("tagHandler INNER JOIN tag ON tag.id=tagHandler.tagId INNER JOIN question ON question.id=tagHandler.questionId");
     
        $this->db->execute();
        $res[1] = $this->db->fetchAll();
        return $res;

    }
    public function findQuestionsByTag($tag)
    {
        $this->db->select("tagHandler.questionId, tag.name")
                 ->from("`tagHandler` INNER JOIN tag ON tag.id=tagHandler.tagId INNER JOIN question ON question.id=tagHandler.questionId WHERE tag.name=?");
     
        $this->db->execute([$tag]);
        $res = $this->db->fetchAll();

        $qId = [];
        foreach ($res as $key => $value) {
        	$qId[$key] = $value->questionId;
        }
        $this->db->select("question.title, question.content, question.id, user.acronym, question.created")
                 ->from("questionHandler INNER JOIN user ON user.id=questionHandler.userId INNER JOIN question ON question.id=questionHandler.questionId WHERE question.id IN (?)");

        $this->db->execute([$qId]);
        $res = $this->db->fetchAll();
        /*$this->db->select("tag.name, tagHandler.questionId")
                 ->from("tagHandler INNER JOIN tag ON tag.id=tagHandler.tagId INNER JOIN question ON question.id=tagHandler.questionId");
     
        $this->db->execute();
        $res[1] = $this->db->fetchAll();*/
        return $res;

    }
    /*
	 * Find question by id
	 */
    public function findQuestion($id)
    {
        $this->db->select("question.title, question.content, question.id, user.acronym, user.profileImg, user.created")
                 ->from("questionHandler INNER JOIN user ON user.id=questionHandler.userId INNER JOIN question ON question.id=questionHandler.questionId WHERE question.id=" . $id);
     
        $this->db->execute();
        $res[0] = $this->db->fetchAll();

        $this->db->select("tag.name, tagHandler.questionId")
                 ->from("tagHandler INNER JOIN tag ON tag.id=tagHandler.tagId INNER JOIN question ON question.id=tagHandler.questionId WHERE question.id=" . $id);
     
        $this->db->execute();
        $res[1] = $this->db->fetchAll();
        return $res;

    }
    /*
     * Find comments by id
     */
    public function findComments($questionId)
    {
        $this->db->select("user.acronym, user.profileImg, comment.comment, comment.questionId, comment.id, comment.responseId")
                 ->from("commentHandler INNER JOIN user ON user.id=commentHandler.userId INNER JOIN comment ON comment.id=commentHandler.commentId WHERE comment.questionId=" . $questionId);
     
        $this->db->execute();
        $res = $this->db->fetchAll();
        return $res;

    }
    public function findResponses()
    {
        $this->db->select("response.comment, response.user, response.created, comment.id")
                 ->from("`responseHandler` INNER JOIN response ON response.id=responseHandler.responseId INNER JOIN comment ON comment.id=responseHandler.commentId");
     
        $this->db->execute();
        $res = $this->db->fetchAll();
        return $res;

    }

	public function search($searchTerm = null)
    {
        $this->db->select()
	             ->from($this->getSource())
	             ->where("name LIKE '%".$searchTerm."%'");
	    $this->db->execute();
	    $this->db->setFetchMode(\PDO::FETCH_ASSOC);
        return $this->db->fetchAll();
    }

    public function getProperties()
    {
        $properties = get_object_vars($this);
	    unset($properties['di']);
	    unset($properties['db']);        
	    return $properties;
    }
    public function getValue($section, $id)
    {
        $this->db->select($section)
                 ->from($this->getSource())
                 ->where("id = ?");
     
        $this->db->execute([$id]);
        $value = $this->db->fetchInto($this);
        return $value->$section;
    }
    public function getComments() {
        $this->db->select()->from($this->getSource());
        $this->db->execute();
        $this->db->setFetchMode(\PDO::FETCH_ASSOC);
        $all = $this->db->fetchAll();
        return $all;
        
        }
	/**
	 * Save current object/row.
	 *
	 * @param array $values key/values to save or empty to use object properties.
	 *
	 * @return boolean true or false if saving went okey.
	 */

	public function getTags()
	{
		$this->db->select('name')
	        ->from('tag');
	             //->where("acronym = ?");
	    $this->db->execute();
	    return $this->db->fetchAll();
	}
	public function save($values = [], $table = null , $acronym = NULL, $tags = [])
	{

		if ($table === 'questionHandler')
		{
			// Insert question and get questionId
			$keys   = array_keys($values);
	    	$values = array_values($values);
			$this->db->insert(
		        'question',
	        	$keys
		    );
		    $this->db->execute($values);
		    $questionId = $this->db->lastInsertId();

		    // Collecting userId from $acronym
			$this->db->select()
	             ->from('user WHERE acronym =' . '"' . $acronym . '"');
	             //->where("acronym = ?");
	    	$this->db->execute();
	    	$res = $this->db->fetchAll();
	    	$userId = $res[0]->id;

	    	// Insert userId and questionId into questionHandler
	    	$this->db->insert(
	    		'questionHandler',
		        ['userId', 'questionId']
		    );
		    $this->db->execute([$userId, $questionId]);

		    // Tags handling
		    foreach ($tags as $key => $value) {
		    	$this->db->select()
		             ->from('tag WHERE name =' . '"' . $value . '"');
		        $this->db->execute();
		    	$res = $this->db->fetchAll();

				$this->db->insert(
		    		'tagHandler',
			        ['tagId', 'questionId']
		    	);
		        $this->db->execute([$res[0]->id, $questionId]);
			}
			


/*		    $this->db->insert(
		        'INTO questionHandler (questionId, userId) VALUES (1, 2)'
		    );

		    $this->db->execute();*/
		}
		else if ($table === "comment") {
			// Insert question and get questionId
			$keys   = array_keys($values);
	    	$values = array_values($values);
			$this->db->insert(
		        'comment',
	        	$keys
		    );
		    $this->db->execute($values);
		    $commentId = $this->db->lastInsertId();

		    // Collecting userId from $acronym
			$this->db->select()
	             ->from('user WHERE acronym =' . '"' . $acronym . '"');
	             //->where("acronym = ?");
	    	$this->db->execute();
	    	$res = $this->db->fetchAll();
	    	$userId = $res[0]->id;

	    	// Insert userId and questionId into questionHandler
	    	$this->db->insert(
	    		'commentHandler',
		        ['userId', 'commentId']
		    );
		    $this->db->execute([$userId, $commentId]);
		}
		else if ($table === "respond") {
			// Insert question and get questionId
			$keys   = array_keys($values);
	    	$values = array_values($values);
			$this->db->insert(
		        'response',
	        	$keys
		    );
		    $this->db->execute($values);
		    $responseId = $this->db->lastInsertId();

	    	$this->db->insert(
	    		'responseHandler',
		        ['responseId', 'commentId']
		    );
		    $this->db->execute([$responseId, $tags[0]]);
		}
		else 
		{
			$this->setProperties($values);
		    $values = $this->getProperties();

		    if (isset($values['id'])) {
		        return $this->update($values);
		    } else {
		        return $this->create($values);
		    }
		}
	    
	}
	
	/**
	 * Set object properties.
	 *
	 * @param array $properties with properties to set.
	 *
	 * @return void
	 */
	public function setProperties($properties)
	{
	    // Update object with incoming values, if any
	    if (!empty($properties)) {
	        foreach ($properties as $key => $val) {
	            $this->$key = $val;
	        }
	    }
	}
	/**
	 * Create new row.
	 *
	 * @param array $values key/values to save.
	 *
	 * @return boolean true or false if saving went okey.
	 */
	public function create($values)
	{
	    $keys   = array_keys($values);
	    $values = array_values($values);

	    $this->db->insert(
	        $this->getSource(),
	        $keys
	    );

	    $res = $this->db->execute($values);

	    $this->id = $this->db->lastInsertId();

	    return $res;
	}
	/**
	 * Update row.
	 *
	 * @param array $values key/values to save.
	 *
	 * @return boolean true or false if saving went okey.
	 */
	public function updateProfile($values, $acronym)
	{
	    $keys   = array_keys($values);
	    $values = array_values($values);

	    // Its update, remove id and use as where-clause
	    /*unset($keys[1]);
	    $values[] = $this->id;*/

	    $this->db->update(
	        'user',
	        $keys,
	        "acronym = " . "'" . $acronym . "'"
	    );

	    return $this->db->execute($values);
	    //return $keys[1];
	}
	/**
	 * Delete row.
	 *
	 * @param integer $id to delete.
	 *
	 * @return boolean true or false if deleting went okey.
	 */
	public function delete($id)
	{
	    $this->db->delete(
	        $this->getSource(),
	        'id = ?'
	    );

	    return $this->db->execute([$id]);
	}
	/**
	 * Build a select-query.
	 *
	 * @param string $columns which columns to select.
	 *
	 * @return $this
	 */
	public function query($columns = '*')
	{
	    $this->db->select($columns)
	             ->from($this->getSource());

	    return $this;
	}
	/**
	 * Build the where part.
	 *
	 * @param string $condition for building the where part of the query.
	 *
	 * @return $this
	 */
	public function where($condition)
	{
	    $this->db->where($condition);

	    return $this;
	}
	/**
	 * Build the where part.
	 *
	 * @param string $condition for building the where part of the query.
	 *
	 * @return $this
	 */
	public function andWhere($condition)
	{
	    $this->db->andWhere($condition);

	    return $this;
	}
	/**
	 * Execute the query built.
	 *
	 * @param string $query custom query.
	 *
	 * @return $this
	 */
	public function execute($params = [])
	{
	    $this->db->execute($this->db->getSQL(), $params);
	    $this->db->setFetchModeClass(__CLASS__);



	}
}