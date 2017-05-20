<h2>Questions</h2>
<?php for($i = sizeof($questions)-1; $i >= 0; $i--) {?>

<div class="kommentarer">
    <p><a href="<?=$this->url->create('question/view/' . $questions[$i]->questionId )?>"><?=$questions[$i]->title?></a><h3><?=$questions[$i]->title?></h3></p>
    <p><?=$questions[$i]->content?></p>
    <i><?=$questions[$i]->name?> </i>
    <hr>
</div>
<?php } ?>