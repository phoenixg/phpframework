<?php

/*
 *  @Id:autoload.class.php 2010-10-19 aomm $
 */

class nbInitAutoload {

 static protected
    $registered = false,
    $instance   = null;

  protected
    $baseDir = null;

  protected function __construct() {
    $this->baseDir = realpath(dirname(__FILE__) . '/..'); //构造函数--当前目录
  }

//getInstance
  static public function getInstance() {
    if (!isset(self::$instance)) {
      self::$instance = new self();
    }

    return self::$instance;
  }

//register autoload
  static public function register() {
    if (self::$registered) {
      return;
    }
    //ini_set
    ini_set('unserialize_callback_func', 'spl_autoload_call');////如果解串行器发现有未定义类要被实例化,则设置spl_autoload_call函数加载请求类.
    if (false === spl_autoload_register(array(self::getInstance(), 'autoload')))//自动载入类，类似于__autoload
    {
     throw new Exception('wrong');
    }
    self::$registered = true;
  }

//unregister autoload
  static public function unregister() {
    spl_autoload_unregister(array(self::getInstance(), 'autoload'));
    self::$registered = false;
  }

//rewrite autoload
  public function autoload($class) {//处理自动载入类
    if ($path = $this->getClassPath($class)) {
      require $path;
      return true;
    }

    return false;
  }

  //getClassPath
  public function getClassPath($class) {
    $class = strtolower($class);

    if (!isset($this->classes[$class])) {//$classes数组中没有这个类返回空
      return null;
    }

    return $this->baseDir . '/../' . $this->classes[$class];//返回当前需要的类的绝对路径
  }

  //getBaseDir
  public function getBaseDir() {//当前目录--构造函数已加载
    return $this->baseDir;
  }

  //rewrite $classes
  public function make() {
    self::register();
    $libDir = str_replace(DIRECTORY_SEPARATOR, '/', realpath(dirname(__FILE__) . DIRECTORY_SEPARATOR . '../..'));
    require_once $libDir . '/finder/lib/nbFinder.class.php';
    include_once(FRAMEWORK_ROOT.'/helper/nbConfigHelper.class.php');
    $finder = new nbFinder();
    $para['root'] = FRAMEWORK_ROOT;
    $para['fileRegx'] = '/\.class\.php$/';
    $files = $finder->execute($para);
    sort($files, SORT_STRING);
    $classes = '';
    foreach ($files as $file) {
      $file = str_replace(DIRECTORY_SEPARATOR, '/', $file);
      $class = basename($file, false === strpos($file, '.class.php') ? '.php' : '.class.php');

      $contents = file_get_contents($file);
      if (false !== stripos($contents, 'class ' . $class) || false !== stripos($contents, 'interface ' . $class)) {
        $classes .= sprintf("    '%s' => '%s',\n", strtolower($class), substr(str_replace($libDir, '', $file), 1));
      }
    }
     $content = preg_replace('/protected \$classes = array *\(.*?\);/s', sprintf("protected \$classes = array(\n%s  );", $classes), file_get_contents(__FILE__));
    file_put_contents(__FILE__, $content);
  }

    // Don't edit this property by hand.
    // To update it, use sfCoreAutoload::make()
   protected $classes = array(
    'nbautoconfig' => 'autoconfig/lib/nbAutoconfig.class.php',
    'nbautoconfighelper' => 'autoconfig/lib/nbAutoconfigHelper.class.php',
    'nbautoload' => 'autoload/lib/nbAutoload.class.php',
    'nbautoloadhelper' => 'autoload/lib/nbAutoloadHelper.class.php',
    'nbinitautoload' => 'autoload/lib/nbInitAutoload.class.php',
    'nbcoreconfig' => 'coreConfig/lib/nbCoreConfig.class.php',
    'nbdata' => 'data/nbData.class.php',
    'nb404exception' => 'exception/lib/nb404Exception.class.php',
    'nbaddonexception' => 'exception/lib/nbAddonException.class.php',
    'nbcoreexception' => 'exception/lib/nbCoreException.class.php',
    'nbexception' => 'exception/lib/nbException.class.php',
    'nbgotoexception' => 'exception/lib/nbGotoException.class.php',
    'nbmessageexception' => 'exception/lib/nbMessageException.class.php',
    'nbfactory' => 'factory/nbFactory.class.php',
    'nbfinder' => 'finder/lib/nbFinder.class.php',
    'nbform' => 'form/lib/nbForm.class.php',
    'nbformwidget' => 'form/lib/nbFormWidget.class.php',
    'nbgenerate' => 'generate/nbGenerate.class.php',
    'nbapphelper' => 'helper/nbAppHelper.class.php',
    'nbarrayhelper' => 'helper/nbArrayHelper.class.php',
    'nbconfighelper' => 'helper/nbConfigHelper.class.php',
    'nbdatahelper' => 'helper/nbDataHelper.class.php',
    'nbhelper' => 'helper/nbHelper.class.php',
    'nbtoolhelper' => 'helper/nbToolHelper.class.php',
    'nbframework' => 'init/lib/nbFramework.class.php',
    'nblog' => 'log/lib/nbLog.class.php',
    'nbloghelper' => 'log/lib/nbLogHelper.class.php',
    'nbaction' => 'mvc/action/nbAction.class.php',
    'nbcontroller' => 'mvc/controller/nbController.class.php',
    'nbexecutionfilter' => 'mvc/filter/nbExecutionFilter.class.php',
    'nbfilter' => 'mvc/filter/nbFilter.class.php',
    'nbfilterchain' => 'mvc/filter/nbFilterChain.class.php',
    'nbrenderingfilter' => 'mvc/filter/nbRenderingFilter.class.php',
    'nbheadhelper' => 'mvc/helper/nbHeadHelper.class.php',
    'nbmvchelper' => 'mvc/helper/nbMvcHelper.class.php',
    'nbquery' => 'mvc/model/nbQuery.class.php',
    'nbquerybuilder' => 'mvc/model/nbQueryBuilder.class.php',
    'nbqueryexecuter' => 'mvc/model/nbQueryExecuter.class.php',
    'nbqueryexplain' => 'mvc/model/nbQueryExplain.class.php',
    'nbrequest' => 'mvc/request/nbRequest.class.php',
    'nbresponse' => 'mvc/response/nbResponse.class.php',
    'nbmysqladapter' => 'mvc/service/nbMysqlAdapter.class.php',
    'nbmysqlresult' => 'mvc/service/nbMysqlResult.class.php',
    'nbservice' => 'mvc/service/nbService.class.php',
    'nbvalidate' => 'mvc/validate/nbValidate.class.php',
    'nbmvcwidget' => 'mvc/widget/nbMvcWidget.class.php',
    'nbpagerhelper' => 'pager/nbPagerHelper.class.php',
    'nbrouter' => 'router/lib/nbRouter.class.php',
    'basetable' => 'table/lib/BaseTable.class.php',
    'table' => 'table/lib/Table.class.php',
    'tablehelper' => 'table/lib/TableHelper.class.php',
    'nbtablewidget' => 'table/lib/nbTableWidget.class.php',
    'showactions' => 'table/modules/show/actions/ShowActions.class.php',
    'nbunittest' => 'test/nbUnitTest.class.php',
    'converttool' => 'tool/ConvertTool.class.php',
    'coretool' => 'tool/CoreTool.class.php',
    'nbuserdao' => 'user/lib/nbUserDao.class.php',
    'nbuserhelper' => 'user/lib/nbUserHelper.class.php',
    'nbuserprivilegetablewidget' => 'user/lib/nbUserPrivilegeTableWidget.class.php',
    'nbuserroleformwidget' => 'user/lib/nbUserRoleFormWidget.class.php',
    'nbuserroletablewidget' => 'user/lib/nbUserRoleTableWidget.class.php',
    'nbuserservice' => 'user/lib/nbUserService.class.php',
    'nbappformwidget' => 'widget/nbAppFormWidget.class.php',
    'nbhtmlwidget' => 'widget/nbHtmlWidget.class.php',
    'nbwidget' => 'widget/nbWidget.class.php',
  );



}

?>
