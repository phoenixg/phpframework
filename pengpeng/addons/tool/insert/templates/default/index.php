<div id="out">
    <ul>
        <li>执行SQL</li>
        <li>导入</li>
    </ul>
</div>
<div id="form">
    <form action="" method="post">
        <!--输入你想要执行的SQL条数:<input name="number" type="text" id="input">条<br>-->
        <div id="tx1">输入你要执行的SQL语句(<span class="red">多条语句请用<span class="blue">英文</span>状态下的";"分割</span>)</div>
        <div id="tx2"><textarea  name="content" rows="10" cols="50"></textarea></div>
        <div id="tx1">执行SQL语句<input type="text"name="num"/>次，<input name="3" type="submit" value="提交" id="btn"></div>
    </form>
</div>
<?php if (isset($resultArr)): ?>
<?php foreach ($resultArr as $value): ?>
        <div id="result">
            <div id="sql">SQL语句：<?php echo $value[1]; ?>,共影响了<?php echo $value[2]; ?>条记录.
        <?php if ($value[3] > 0): ?>
               这条SQL语句共执行了<?php echo $value[3];?>遍。
        <?php endif; ?>
        </div>    
        <div id="selectResult"><!--结果如下:<br/>-->
        <?php //foreach($value[0] as $valueResult):?>
        <?php //print_r($valueResult);?>
        <?php //endforeach;?>
        </div>
    </div>
<?php endforeach; ?>
<?php endif; ?>
