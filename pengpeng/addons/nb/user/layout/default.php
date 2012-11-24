<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<script type="text/javascript" src="/tm/js/jquery.js"></script>
</head>
<body>
 <?php // echo nbWidget::linkTo('用户管理', 'user/index') ?>
 <?php // echo nbWidget::linkTo('用户组（角色）管理', 'userRole/index') ?>
 <?php echo nbWidget::linkTo('用户权限管理', 'userPrivilege/index') ?>
<br />
<?php echo $contents ?>
</body>
</html>