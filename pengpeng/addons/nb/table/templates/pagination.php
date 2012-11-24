<?php if (1 < $pageInfo['pageTotal']) : ?>
<div style="text-align:center;">
  <?php if ($pageInfo['pagePrevious']) : ?>
  <?php echo TableHelper::linkTo($table, __('上一页'), "[page]=".$pageInfo['pagePrevious']) ?>
  <?php endif; ?>

  <?php foreach ($pageInfo['pageArea'] as $key => $value) : ?>
    <?php if ($value == $pageInfo['pageAt']) : ?>
    <a class="on"><?php echo $value ?></a>
    <?php else : ?>
    <?php echo TableHelper::linkTo($table, $value, "[page]=".$value) ?>
    <?php endif; ?>
  <?php endforeach; ?>

  <?php if ($pageInfo['pageNext']) : ?>
    <?php echo TableHelper::linkTo($table, __('下一页'), "[page]=".$pageInfo['pageNext']) ?>
  <?php endif; ?>
</div>
<?php endif; ?>