<?php foreach ($questions as $id => $question) :?>

<div class="question">
    <div class="profile">
        <a href="<?=$this->url->create('user/profile/' . $question->acronym)?>"><p class="center"><?=$question->acronym?></p></a>
        <img class="profileImg" src="http://www.student.bth.se/~fist14/phpmvc1/kmom10/webroot/img/<?=$question->profileImg?>.png" width="80" height="80">
    </div>
    <div class="content">
        <p><?=$question->content?></p>
        
        <i>tags:</i>
        <?php foreach ($tags as $id => $tag) :
        if ($tag->questionId === $question->id)
        {
            ?><a href="<?=$this->url->create('question/view/' . $tag->name)?>"><?=$tag->name?> </a><?

        }
        endforeach; ?>
    </div>

<hr>
</div>
<?php endforeach; ?>
<p>Comments:</p>
<hr>
<?php for($i = sizeof($comments)-1; $i >= 0; $i--) { ?>
<div class="comment">
    <div class="profile">
                <a href="<?=$this->url->create('user/profile/' . $comments[$i]->acronym)?>"><p class="center"><?=$comments[$i]->acronym?></p></a>
        <img class="profileImg" src="http://www.student.bth.se/~fist14/phpmvc1/kmom10/webroot/img/<?=$comments[$i]->profileImg?>.png" width="80" height="80">
</cite>
    </div>
    <div class="content">
       <p><?=$comments[$i]->comment?></p>
    </div>
    <hr>


</div>
<?php if (sizeof($responds) > 0) { ?>
    <div class="response">

        <?php for($r = 0; $r < sizeof($responds); $r++) { 
                if ($responds[$r]->id == $comments[$i]->id)
                {
            ?>
                    <div class="indivResponse">
                        <a href="<?=$this->url->create('user/profile/' . $responds[$r]->user)?>"><p ><?=$responds[$r]->user?></p></a>
                        <p><?=$responds[$r]->comment?></p>                 
                    </div>

        <?php
                }
        }
        ?>
    </div>
<?php
    }
if ($isLogged)
{


?>
<hr>
<div class='comment-form'>
    <form method=post>
        <label>Respond to <?=$comments[$i]->acronym?>'s comment: </label><input type="textarea" name="respond" width="500"><input type='submit' name='doRespond' value='Respond' onClick="this.form.action = '<?=$this->url->create('question/respond/' . $comments[$i]->id)?>'"/>
    </form>
</div>
<br><br>
<?php
} 
}
echo $content;
?>