<form action="../items/add" method="post">
<input type="text" value="I have to..." onclick="this.value=''" name="todo"> <input type="submit" value="add">
</form>
<br/><br/>
<?php $number = 0?>
 
<?php foreach ($todo as $todoitem):?>
    <a class="big" href="../items/view/<?= $todoitem['Item']['id']?>/<?= strtolower(str_replace(" ","-",$todoitem['Item']['item_name']))?>">
    <span class="item">
    <?= ++$number?>
    <?= $todoitem['Item']['item_name']?>
    </span>
    </a><br/>
<?php endforeach?>