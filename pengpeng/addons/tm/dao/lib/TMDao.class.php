<?php
class TMDao extends CsDao
{
  public function setTable()
  {

  }

  /**
   * Set time in update or insert array
   *
   * @param array $field   the insert or update array
   * @param string $column  the presented time column name
   */
  public function setTimeForUpdateOrInsert(array &$field, $column = 'FTime')
  {
    $field[$column] = date ( 'Y-m-d H:i:s' );
  }

  /**
   * Set date in update or insert array
   *
   * @param array $field   the insert or update array
   * @param string $column  the presented date column name
   */
  public function setDateForUpdateOrInsert(array &$field, $column = 'FDate')
  {
    $field[$column] = date ('Y-m-d');
  }

  public function insertWithTime($table, array $insertArray, $delay=false)
  {
    $this->setDateForUpdateOrInsert($insertArray);
    $this->setTimeForUpdateOrInsert($insertArray);
    $this->insert ( $table, $insertArray, $delay );

    return $this->db->getInsertId();
  }
}