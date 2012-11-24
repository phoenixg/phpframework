<form<?php echo $attributeString ?>>
<?php foreach ($fields as $key => $field) : ?>
<?php if (!$field['needBox']): ?>
<?php echo $field['content'] ?>
<?php endif;?>
<?php endforeach; ?>

<table>
<?php foreach ($fields as $key => $field) : ?>
<?php if ($field['needBox']): ?>
<?php if(isset($field['error'])) : ?>
  <tr>
    <td colspan="2"><?php echo $field['error'] ?></td>
  </tr>
<?php endif; ?>
  <tr>
    <th><?php echo $field['title'] ?></th>
    <td><?php echo $field['content'] ?><label><?php echo isset($field['help']) ? $field['help'] : '' ?></label></td>
  </tr>
<?php endif;?>
<?php endforeach; ?>
</table>
</form>