<div class="questions">
	<h3>Latest 5 questions</h3>
	<?php $k = 0; for($i = sizeof($questions)-1; $i >= 0; $i--) {
		if ($k < 5)
		{
			?>
			<a href="<?=$this->url->create('question/view/' . $questions[$i]->id )?>"><?=$questions[$i]->title?></a>
			<table>
				<tr>
				    <td><?=$questions[$i]->created?></td>
				</tr>
			</table>
		<?php 

		}
		$k++;
		
	} 
	?>
</div>

<div class="tags">
	<h3>Populair tags</h3>
	<?php
	foreach ($tags as $key => $value) {
		?><a href="<?=$this->url->create('question/view/' . $tags[$key]->name)?>"><?=$tags[$key]->name?>(<?=$tags[$key]->cnt?>)</a> <?

	}



	?>


</div>



