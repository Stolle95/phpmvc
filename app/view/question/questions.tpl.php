<h2><?=$title?></h2>

<?php for($i = sizeof($questions)-1; $i >= 0; $i--) {?>
<a href="<?=$this->url->create('question/view/' . $questions[$i]->id )?>"><?=$questions[$i]->title?></a>
<table>
	<tr>
	    
	    <td>Started by: <?=$questions[$i]->acronym?></td>
	    <td><?=$questions[$i]->created?></td>

	    <td>
	    	<?php foreach ($tags as $id => $tag) :
		    if ($tag->questionId === $questions[$i]->id)
		    {

		    	?><a href="<?=$this->url->create('question/view/' . $tag->name)?>"><?=$tag->name?></a> <?
		    }
		    endforeach; ?>
	    </td>
	    
	</tr>
</table>
<?php } ?>
