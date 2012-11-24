<form action="/tm-filter-password.php/default/submit" method="post">
访问密码：<input type="text" name="password" />
<input type="hidden" name="url" value="<?php echo $url ?>" />
<input type="submit" value="提交" /> Your Ip Address: <?php echo TMUtil::getClientIp() ?>
</form>