<?php foreach ($configs as $buildNameSpace => $buildPara): ?>
<font color="red"><?php echo $buildPara['title'] ?></font><br />
<?php echo $buildPara['description'] ?><br />
<form action="<?php echo nbMvcWidget::url('form/submit')?>" method="post">
<input type="hidden" name="buildNameSpace" value="<?php echo $buildNameSpace ?>" />

<table>
<?php foreach ($buildPara['fromPara'] as $key => $parameter): ?>
<tr>
<td><?php echo $parameter['title'] ?></td>
<td><?php echo htmlspecialchars_decode($parameter['content']) ?></td>
</tr>
<?php endforeach; ?>
</table>

<input type="submit" />
</form>
<hr></hr>
<?php endforeach; ?>
