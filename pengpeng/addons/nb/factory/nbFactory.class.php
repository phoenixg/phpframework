<?php
class nbFactory
{
  private static $instance;

  /**
   * @param bool $newInstance
   * @return CoreFactory
   */
  public static function getInstance($newInstance = false)
  {
    if ($newInstance || self::$instance == null)
    {
      self::$instance = new self();
    }

    return self::$instance;
  }

  public $factory = array();

  public function getFactory($name)
  {
    if (isset($this->factory[$name]))
    {
      return $this->factory[$name];
    }
    else
    {
      return false;
    }
  }

  public function setFactory($name, $value, $replace = false)
  {
    if ($replace)
    {
      $this->factory[$name] = $value;
    }
    else
    {
      if (isset($this->factory[$name]))
      {
        echo "facetory $name exist!";
      }
      else
      {
        $this->factory[$name] = $value;
      }
    }
  }
}