<style>
.nbTable {float:left;display:inline;}
.nbTable th{font:13px "黑体"; color:#fff;line-height:37px;background-color:#9999CC;text-align:center;}
.nbTable th a{color:#fff;}
.nbTable .on{font: bold 14px "宋体";color:#55f;}
.nbTable td{border-bottom:1px solid #D7D7D7;font-size:13px; color:#000; padding:8px;}
.nbTable td a:link,#nbTable td a:visited{font-size:13px; color:#000;}
.nbTable td a:hover,#nbTable td a:active{font-size:13px; color:#95291e;text-decoration: underline;}
.tableSingleRow{background-color: rgb(239, 239, 239);}
.tablePageRow{text-align:right;}
</style>
<script>
function tablePage(page)
{
  if(page == '')
  {
      page = 1
  }
  if (location.href.match(/page=/))
  {
    newLocation = location.href.replace(/page=(.*)$/, 'page=' + page);
  }
  else if (location.href.match(/\?/))
  {
    newLocation = location.href + '&page=' + page;
  }
  else
  {
    newLocation = location.href + '?page=' + page;
  }
  location.href = newLocation;
}

</script>
<table class="nbTable">
 <thead>
    <tr>
    <?php foreach ($tableTitle as $title) :?>
      <?php echo $title ?>
    <?php endforeach?>
    </tr>
  </thead>

  <tbody>
    <?php foreach ($tableBody as $key => $row) :?>
    <tr<?php echo $key%2 ? '' : ' class="tableSingleRow"' ?>>
    <?php foreach ($row as $cell) :?>
      <?php echo $cell ?>
    <?php endforeach?>
    </tr>
    <?php endforeach?>
  </tbody>
  <tfoot>
    <tr>
      <td class="tablePageRow" colspan="<?php echo count($tableTitle) ?>">
      <?php foreach ($pagerInfo['pageArea'] as $pageNum) :?>
        <a href="javascript:tablePage(<?php echo $pageNum; ?>)"><?php echo $pageNum; ?></a>
      <?php endforeach?>
      </td>
    </tr>
  </tfoot>
</table>
