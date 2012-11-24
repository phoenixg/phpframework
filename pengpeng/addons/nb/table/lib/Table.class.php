<?php


/**
 * base class used to generate table
 *
 */
abstract class Table
{
  public $pagerInfo = array('rowsTotal' => 0, 'rowsPerPage' => 0, 'atPage' => 1, 'displayPageAreaNum' => 0);

  /**
   * whether need to do pagination for a table
   *
   * @var boolen
   */
  public $needPagination = true;

  /**
   * whether need to add the 'export' click to let use export all the content display
   *
   * @var boolen
   */
  public $needExportContent = false;


  /**
   * whether need to add the title sort function
   *
   * @var boolen
   */
  public $titleSortColumn = array();

  public $needSearchFunction = false;


  protected $tableId = '';


  /**
   * Total row position, top or bottom
   *
   * @var String
   */
  private $totalRowPosition = 'top';

  public $exportName = '';

  // the parameter need to append after the URL query, like filter.
  public $bindParameter = array('sort');

  public $columnCroup = array();

  /**
   * init user defined option
   *
   * @return void
   */
  abstract protected function initOption();

  /**
   * load user defined column
   *
   * @return array
   */
  abstract protected function initColumn();

  /**
   * load user defined SQL
   *
   * @return Select
   */
  abstract protected function initData();

  /**
   * construct function (努力让这个这个方法只有4行（执行其他四个方法）)
   *
   * @param sfAction $action
   * @return void
   */
  public function __construct()
  {
    $this->loadConfigFile();

    $this->initOption();

    $this->content = $this->initData();


    $this->column = $this->initColumn();
    $this->totalColumn = count($this->column);

    if ($this->needSearchFunction && $this->getSearch())
    {
      foreach ($this->getSearch() as $searchKey => $searchValue)
      {
        if (Request::getInstance()->getGet("{$this->getTableId()}SearchForm[$searchKey]"))
        {
          $this->sql['conditions'][$searchKey] = preg_replace('/%value%/', Request::getInstance()->getGet("{$this->getTableId()}SearchForm[$searchKey]"), $this->search[$searchKey]['condition']);
        }
      }
    }

    if (Request::getInstance()->getGet('export'))
    {
      $this->doExport();
    }

    if ($this->needSearchFunction)
    {
      //$this->action->getResponse()->addJavascript('ajaxSubmit');
    }
//
//    if (Request::getInstance()->isAjax())
//    {
//      AjaxTool::display();
//      echo array('code' => 0, 'message' => )
//      echo $this;
//    }
  }

  private function loadConfigFile()
  {
    $this->pagerInfo['rowsPerPage'] = ConfigTool::getAddonConfig('table/rowsPerPage');
    $this->pagerInfo['displayPageAreaNum'] = ConfigTool::getAddonConfig('table/displayPageAreaNum');
  }

  /**
   * used to init default option, contain system default option can't be init by property and user defined option
   *
   * @return void
   */
  protected function initDefaultOption()
  {
    $this->atPage = $this->getAtPage();

    if ($this->needSearchFunction)
    {
      $this->search = $this->initSearch();
    }
  }



  /**
   * get the column user defined
   *
   * @return array
   */
  public function getColumn()
  {
    return $this->column;
  }

  /**
   * get content in the table
   *
   * @return array
   */
  public function getContent()
  {
    return $this->content;
  }

  public function getSearch()
  {
    return $this->search;
  }


  /**
   * get the table name, used to set a unique name for a table
   *
   * @return string
   */
  public function getTableId()
  {
    return $this->tableId ? $this->tableId : ConvertTool::toCamel(get_class($this));
  }

  /**
   * Do expoet CSV function
   *
   * @return void
   */
  public function doExport()
  {
    $exportName = $this->exportName ? $this->exportName : 'report.csv';

    $response = CsResponse::getInstance();

    $response->setHttpHeader('Content-type', 'application/octet-stream');
    $response->setHttpHeader('Content-Disposition', "attachment; filename=$exportName");
    $response->setHttpHeader('Pragma', 'public');
    $response->setHttpHeader("Cache-Control", 'must-revalidate, post-check=0, pre-check=0');
    $response->setHttpHeader("Cache-Control", 'private', false);
    $response->setHttpHeader("Content-Transfer-Encoding", 'binary');

    $response->sendHttpHeaders();

    $columnTemp = $contentTemp = array();

    //sfLoader::loadHelpers(array('Partial', 'Cache', 'Text', 'Date', 'Form', 'I18N', 'ec', 'Javascript', 'Asset', 'Url', 'Tag'));

    $culumns = $this->getColumn();

    foreach ($this->getColumn() as $columnKey => $column)
    {
      if ($columnKey != 'action')
      {
        $columnTemp[] = '"' .strip_tags(display_header($this, $columnKey)). '"';
      }
    }
    echo implode(',', $columnTemp) . "\n";

    foreach ($this->getContent() as $rowKey => $row)
    {
      $contentTemp = array();
      foreach ($this->getColumn() as $columnKey => $column)
      {
        if ($columnKey != 'action')
        {
          $contentTemp[] = '"' .strip_tags(display_cell($this, $columnKey, $row)). '"';
        }
      }
      echo implode(',', $contentTemp) . "\n";
    }

    exit;
  }

  /**
   * default dataModify, do nothing
   *
   * @param array $resultArray
   * @return array
   */
  public function dataModify($resultArray)
  {
    return $resultArray;
  }

  /**
   * display the table content, used by ajax mode
   *
   * @return void
   */
//  public function __toString()
//  {
//    HtmlHelper::includePartial('table/global/table', array('table' => $this));
//    exit;
//  }

  /**
   * check whether need to display the title sort.
   *
   * @param string $culumn
   * @return boolen
   */
  public function displayTitleSort($culumn)
  {
    if (in_array($culumn, $this->getTitleSortColumn()))
    {
      $return = true;
    }
    else
    {
      $return = false;
    }

    return $return;
  }

  /**
   * get current sort way
   *
   * @param string $culumn
   * @return string
   */
  public function getCurrentSortWay($culumn)
  {
    $sortWay = 'NOTSORTING';

    $criteria = $this->select->getCriteria();

    if (isset($criteria['order'][$culumn]))
    {
      $sortWay = strtoupper($criteria['order'][$culumn]);
    }

    $sort = $this->getTableParameter('sort');

    if (isset($sort[$culumn]))
    {
      $sortWay = $sort[$culumn];
    }

    return $sortWay;
  }

  /**
   * get title sort column
   *
   * @return array
   */
  public function getTitleSortColumn()
  {
    return $this->titleSortColumn;
  }

  /**
   * set title sort column
   *
   * @param array $titleSortColumn
   * @return void
   */
  public function setTitleSortColumn($titleSortColumn)
  {
    foreach ($titleSortColumn as $column => $way)
    {
      $this->titleSortColumn[$column] = $way;
    }
  }

  /**
   * get page filter(user 'filter') and table filter(use tbale id), the table class
   * would change page filter to table filter
   *
   * @return array
   */
  public function getFilter()
  {
    $pageFilter = $tableFilter['filter'] = array();
    if (Request::getInstance()->getGet("filter"))
    {
      $pageFilter = Request::getInstance()->getGet("filter");
    }

    if (Request::getInstance()->getGet($this->getTableId()))
    {
      $tableParameter = $this->getTableParameter();

      if (isset($tableParameter['filter']))
      {
        $tableFilter['filter'] = $tableParameter['filter'];
      }
    }

    return array_merge_recursive($pageFilter, $tableFilter['filter']);
  }

  /**
   * return table's parameter, and index if $key was given
   *
   * @param string $key
   * @return array
   */
  public function getTableParameter($key = '')
  {
    $return = Request::getInstance()->getGet($this->getTableId());

    if ($key && isset($return[$key]))
    {
      $return = $return[$key];
    }
    else if ($key)
    {
      $return = array();
    }

    return $return;
  }

  /**
   * get all the SQL defined by table class
   *
   * @return array
   */
  public function getSQLFromOption()
  {
    if ($this->rowsPerPage)
    {
      $this->sql['limit'] = array($this->rowsPerPage * ($this->atPage - 1), $this->rowsPerPage);
    }

    return $this->sql;
  }

  public function isFirstColumn($columnKey)
  {
    $keys = array_keys($this->column);
    if ($keys[0] == $columnKey)
    {
      return true;
    }
    else
    {
      return false;
    }
  }

  public function isLastColumn($columnKey)
  {
    $keys = array_keys($this->column);
    if ($keys[count($this->column) - 1] == $columnKey)
    {
      return true;
    }
    else
    {
      return false;
    }
  }

  public function getPagerInfo()
  {
    return PagerTool::getPagerInfo($this->pagerInfo['rowsTotal'], $this->pagerInfo['rowsPerPage'], $this->pagerInfo['atPage'], $this->pagerInfo['displayPageAreaNum']);
  }
}
