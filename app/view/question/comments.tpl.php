<div class='comment-container'>

    <div class="form-title"><h2>Kommentarer</h2></div>

    <?php if (is_array($comments)) : ?>

        <?php foreach ($comments as $id => $comment) : ?>

            <div class='comment-field'>


              <a href="<?=$this->url->create('comment/editView/' . $id . '/' . $comment['pagekey'])?>">[ändra]</a>
              <a href="<?=$this->url->create('comment/remove/' . $id . '/' . $comment['pagekey'])?>">[Ta bort]</a>
                <div class='comment-info'>

                        <p><b>Namn:</b> <?= !empty($comment['name']) ? $comment['name'] : "Anonym"?> </p>
                        <p><b>Mejladress:</b> <?= !empty($comment['mail']) ? $comment['mail'] : "Anonym"?></p>
                        <p><b>Hemsida:</b> <?= !empty($comment['web']) ? $comment['web'] : "Anonym"?></p>

                    <div class='comment-small'>
                        <b>Postad: </b><?=$comment['timestamp']?>
                    </div>

                </div>
              </br>
                <b>Kommentar:</b>
                <div class='comment-text'><?=$comment['content']?></div>
                <hr>
            </div>
        <?php endforeach; ?>
        <?php else : ?>

        <div class ='form-title'>Här var det tomt, skriv något!</div>

        <?php endif; ?>
</div>
