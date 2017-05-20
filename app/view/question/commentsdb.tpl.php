<h2>Kommentarer</h2>
<?php foreach ($comments as $id => $comment) : ?>
<div class="kommentarer">
    <p><b><?=$comment['name']?></b> säger:</p>
    <p><?=$comment['comment']?></p>
    <p><i>Skapad:</i> <?=$comment['created']?></p>
    <hr>
</div>
<?php endforeach; ?>