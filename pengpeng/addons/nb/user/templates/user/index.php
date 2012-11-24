<?php echo nbWidget::linkTo('用户添加', 'user/add') ?><br />
<?php nbWidget::includeComponent('@nb-table', array('name' => 'csUserTable', 'builder' => $builder)); ?>