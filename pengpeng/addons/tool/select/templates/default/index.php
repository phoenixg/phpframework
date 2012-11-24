<style>
body{font-size:12px; font-family:Arial, Helvetica, sans-serif;color:#AF2D00; background-color:#F2F2F2}
#input{width:120px;height:12px;margin-top:20px;}
#hight{margin-top:15px;}
#table{margin-top:30px;}
</style>
<form action="" method="POST">
SELECT:<input name="mark" type="text"style="width:300px;height:12px;margin-top:10px">
FROM<input name="tblname" type="text" style="width:300px;height:12px;margin-top:10px">
WHERE<input name="condition" type="text" style="width:300px;height:12px;margin-top:10px">
GROUP BY<input name="group" type="text" style="width:100px;height:12px;margin-top:10px">
ORDER BY<input name="order" type="text" style="width:100px;height:12px;margin-top:10px">
LIMIT<input name="limit" type="text" style="width:100px;height:12px;margin-top:10px">
<br>
<input type="submit" value="查询" id="hight" name = 'submit' onclick="appear();return false;"></form>
<?php if (!empty($chars)):?>
<?php
      $tStr = '<table id=table><tr>';
      foreach ($chars as $resule)
      {
        $tStr .= '<td>';
        $tStr .= $resule.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
        $tStr .= '</td>';
      }
      $tStr .= '</tr>';
      for ($i=0;$i<count($chars);$i++)
      {
        $tStr .= '<tr>';
        foreach ($array as $value)
        {
           $tStr .= '<tr>';
           for ($i=0;$i<count($chars);$i++)
           {
             $tStr .= '<td>' . $value[$chars[$i]] . '</td>';
           }
           $tStr .= '</tr>';
        }
        $tStr .= '</tr>';
      }
      $tStr .= '</table>';
      echo $tStr;
?>
<?php endif;?>