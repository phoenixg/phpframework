 <?php echo nbWidget::linkTo('单个添加', 'userRole/add') ?>
 <?php //echo nbWidget::linkTo('为用户批量添加组（角色）', 'userRole/addRoles') ?>
 <?php //echo nbWidget::linkTo('为组（角色）批量添加用户', 'userRole/addUsers') ?>
 <br />
<?php nbWidget::includeComponent('@nb-table', array('name' => 'csUserRoleTable', 'builder' => $builder)); ?>