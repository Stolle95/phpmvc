<div class='comment-form'>
    <form method=post>
        <input type=hidden name="redirect" value="<?=$this->url->create('comment/'.$pagekey)?>">
        <input type=hidden name="pagekey" value="<?=$pagekey?>">

        <fieldset>
        <legend>Redigera kommentar</legend>
        <p><label>Kommentar:<br/><textarea class="comment-content" name='content'><?=$comment['content']?></textarea></label></p>
        <p><label>Namn:<br/><input type='text' name='name' value='<?=$comment['name']?>'/></label></p>
        <p><label>Hemsida:<br/><input type='text' name='web' value='<?=$comment['web']?>'/></label></p>
        <p><label>Email:<br/><input type='text' name='mail' value='<?=$comment['mail']?>'/></label></p>
        <p class=buttons>
            <input type='submit' name='doEdit' value='Spara' onClick="this.form.action = '<?=$this->url->create('comment/edit/' . $id . '/' . $pagekey)?>'"/>
            <input type='reset' value='Rensa'/>
        </p>
         <!--  <output><?=$output?></output> -->
        </fieldset>
    </form>
</div>
