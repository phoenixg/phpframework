<div id="<?php echo $table->getTableId(); ?>" class="tableHeader csTable">
<?php if ($table->needSearchFunction) : // && $table->getSearch()?>
  <?php
  $className = ConvertTool::toPascal($table->getTableId()).'SearchForm';
  
  $class = new $className();
  $class->display();
  ?>
  <?php //new Form($table->getTableId().'SearchForm'); ?>
  


  <?php //echo input_hidden_tag('table', $table->getTableId()); ?>

  <?php //foreach ($sf_params->get($table->getTableId()) as $key => $value) : ?>
    <?php //echo input_hidden_tag("{$table->getTableId()}[$key]", $value); ?>
  <?php // endforeach; ?>


  <?php //include_form($table->getTableId().'Search/'.$table->getTableId().'SearchForm'); ?>
  <?php //foreach ($table->getSearch() as $searchKey => $searchValue): ?>
    <?php //echo $table->display_search($table, $searchKey) ?>
  <?php //endforeach; ?>
  <?php //echo submit_tag(__('Filter')) ?>
  
<?php endif; ?>


<div id="crossBarTop">
<?php // temp colse to top pagination, need a condition in table class later ?>
<?php //if ($table->needPagination && $table->getContent()) : ?>
  <?php //include_partial('global/pagination', array('pageInfo' => $table->getPageInfo(), 'table' => $table)) ?>
<?php //endif; ?>
</div>

<div style="clear:both">&nbsp;</div>
<table>
  <thead>
    <tr>
      <?php foreach ($table->getColumn() as $columnKey => $column): ?>
        <?php //echo isset($table->column[$columnKey]['columnWidth']) ? 'width=' . $table->column[$columnKey]['columnWidth'] : '' ?>
        <?php echo TableHelper::displayHeader($table, $columnKey); ?>
      <?php endforeach; ?>
    </tr>
  </thead>

  <tbody>




    <?php if ($table->getContent()) : ?>
      <?php foreach ($table->getContent() as $rowKey => $row): ?>
      <tr>
        <?php foreach ($table->getColumn() as $columnKey => $column): ?>
          <?php echo TableHelper::displayCell($table, $columnKey, $row); ?>
        <?php endforeach; ?>
      </tr>
      <?php endforeach; ?>
    <?php else: ?>
      <?php //HtmlHelper::includePartial('global/emptyRow', array('colspan' => $table->totalColumn)); ?>
    <?php endif; ?>

  </tbody>

  <tfoot>
    <tr>
      <td colspan="<?php echo $table->totalColumn ?>" style="margin:0;padding:0;border:0"></td>
    </tr>
  </tfoot>
</table>

<div id="crossBarBottom">

<?php if ($table->needExportContent) : ?>
<div style="float:left;">
  <?php echo $table->linkTo(__('Export'), array('export' => 1, 'page' => '', 'limit' => 100000, 'table' => $table->getTableId()), array('style' => 'text-decoration:underline;color: #8FAD00;')) ?>
</div>
<?php endif; ?>

<?php if ($table->needPagination && $table->getContent()) : ?>
  <?php HtmlHelper::includePartial('table/global/pagination', array('pageInfo' => $table->getPagerInfo(), 'table' => $table)) ?>
<?php endif; ?>
</div>

<script>
if ('undefined' != typeof callbackFunction)
{
  callbackFunction();
}

tablePage = function(dom)
{
  $.ajax({
    type: "GET",
    url: dom,
    success: function(msg){
      $('#<?php echo $table->getTableId() ?>').replaceWith(msg);
      // here need to check whether tb_init can be used
      //tb_init($("#<?php echo $table->getTableId() ?>").find('.thickbox'));
    }
  });
}
</script>

</div>