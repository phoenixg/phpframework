<?php
class TMFileDao extends TMDao
{
  public function updateVoteCount($fileId, $count)
  {
    $this->query('UPDATE Tbl_File set FVoteCount = FVoteCount + '.$count.' WHERE FFileId = '.$fileId);
  }

  public function getFqqById($fileId)
  {
    return $this->selectOne('FQQ', 'Tbl_File', array('FFileId' => $fileId));
  }

  public function getLatest($number, $page)
  {
    $start = ($page - 1) * $number;

    return $this->select('*', 'Tbl_File', array('FEnable|o' =>2,'FEnable' => 4), array($start, $number), array('orderby' => 'FFileId DESC'));
  }

  public function getHotest($number, $page)
  {
    $start = ($page - 1) * $number;

    return $this->select('*', 'Tbl_File', array('FEnable|o' =>2,'FEnable' => 4), array($start, $number), array('orderby' => 'FVoteCount DESC'));
  }

  public function getByQQ($qq, $number, $page)
  {
    $start = ($page - 1) * $number;

    return $this->select('*', 'Tbl_File', array('FQQ' => $qq,'1|n' =>'1 AND (FEnable = 2 or FEnable = 4)'), array($start, $number), array('orderby' => 'FFileId DESC'));
  }

}

?>
