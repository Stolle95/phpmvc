<div class='comment-form'>
    <form method=post>
        <input type=hidden name="redirect" value="<?=$this->url->create('comment/'.$pagekey)?>">
        <input type=hidden name="pagekey" value="<?=$pagekey?>">
        <h2>Kommentera</h2>
        <p><label>Namn:<br/><input type='text' name='name' value='<?=$name?>' placeholder="Namn"/></label></p>
        <p><label>Hemsida:<br/><input type='text' name='web' value='<?=$web?>' placeholder="http://"/></label></p>
        <p><label>Email:<br/><input type='text' name='mail' value='<?=$mail?>' placeholder="Email"/></label></p>
        <p><label>Kommentar:<br/><textarea class="comment-content" name='content' placeholder='Kommentar'><?=$content?></textarea></label></p>
        <p class=buttons>
            <input type='submit' name='doCreate' value='Kommentera' onClick="this.form.action = '<?=$this->url->create('comment/add')?>'"/>
            <input type='reset' value='Rensa'/>
        </p>
        <output><?=$output?></output>
    </form>
</div>
