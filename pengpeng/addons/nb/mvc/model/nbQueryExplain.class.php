<?php
class nbQueryExplain
{
  public function explain($db, $sql)
  {
    $results = $db->query('EXPLAIN '.$sql);

    if ($results)
    {
      $info = $results->fetch_assoc();
      if ($info['Extra'] == 'Using filesort')
      {
        return "(<b style='color:red'>{$info['Extra']}</b>)";
      }
      else
      {
        return "";
      }
    }

  }
}