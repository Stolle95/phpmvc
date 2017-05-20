<img src="http://www.student.bth.se/~fist14/phpmvc1/kmom10/webroot/img/<?=$profile[0]->profileImg?>.png" width="80" height="80">
<h1><?=$profile[0]->acronym;?></h1>
<div class="profileSettings">
	<p>User created: <?=$profile[0]->created;?></p>
</div>

<div class="questions">
	<h2>Asked questions: (<?php echo sizeof($questions); ?>)</h2>
	<?php foreach ($questions as $id => $question) :?>
		<p><a href="<?=$this->url->create('question/view/' . $question->id )?>"><?=$question->title?></a></p>
	<?php endforeach; ?>
</div>

<div class="comments">
	<h2>Comments: (<?php echo sizeof($comments); ?>)</h2>
	<?php foreach ($comments as $id => $comment) :?>
		<p><a href="<?=$this->url->create('question/view/' . $comment->questionId )?>"><?=$comment->comment?></a></p>
	<?php endforeach; ?>
</div>

<div class="responses">
	<h2>Responds to comments: (<?php echo sizeof($responses); ?>)</h2>
	<?php foreach ($responses as $id => $response) :?>
		<p><a href="<?=$this->url->create('question/view/' . $response->questionId )?>"><?=$response->comment?></a></p>
	<?php endforeach; ?>
</div>

<p><a href="<?=$this->url->create('user/logout/')?>">Logout</a></p>

<?php echo $profileImg; ?>

