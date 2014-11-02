<form action="<?= BASE_PATH ?>/items/add" method="post">
<input type="text" value="I have to..." onclick="this.value=''" name="todo"> 
<input type="submit" value="add">
</form>
<br/><br/>
<? $number = 0?>
 
<? foreach ($todo as $todoitem):?>
    <a class="big" href="<?= BASE_PATH ?>/items/view/<?= $todoitem['Item']['id']?>/<?= strtolower(str_replace(" ","-",$todoitem['Item']['item_name']))?>">
    <span class="item">
    <?= ++$number?>
    <?= $todoitem['Item']['item_name']?>
    </span>
    </a><br/>
<? endforeach?>