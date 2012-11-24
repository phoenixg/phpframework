<?php  objectToArray($parentids);$parentids=array_reverse($parentids);$jsonparent=json_encode($parentids);?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<HTML>
  <head>
    <title>
      开发文档
    </title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <script type="text/javascript" src="tool/doc/js/scripts/shCore.js"></script>
    <script type="text/javascript" src="tool/doc/js/json_parse.js"></script>
    <script>var myjson=json_parse('<?php echo ($jsonparent);?>');</script>
    <script type="text/javascript" src="tool/doc/js/menu.js"></script>
    <script type="text/javascript" src="tool/doc/js/scripts/shBrushPhp.js"></script>
    <script type="text/javascript" src="tool/doc/js/scripts/shBrushXml.js"></script>
    <script type="text/javascript" src="tool/doc/js/scripts/shBrushJScript.js"></script>
    <link type="text/css" rel="stylesheet" href="tool/doc/css/shCore.css"/>
    <link type="text/css" rel="stylesheet" href="tool/doc/css/shCoreDefault.css"/>
    <link href="tool/doc/css/style.css" type="text/css" rel="stylesheet">
    <script type="text/javascript">
      SyntaxHighlighter.all();
    </script>
  </head>

  <body onload="mmenuURL()">

    <div id="contain">
      <div id="hd">
        <div class="hdbox">
          <a title="开发文档">
            <img alt="开发文档" src="tool/doc/images/api_logo.png">
          </a>
        </div>
<!--主导航开始-->
        <DIV class="topmenu">
        <DIV id=mainmenu_top>
            <UL>
     <?php $parent=array_reverse($parent);foreach ($parent as $list=>$xml) : ?>
          <LI><a id=mm<?php echo ($list+1);?> name="mainmenu[]" onmouseover=showM(this,<?php echo ($list+1);?>); onmouseout=OnMouseLeft(); href="tool-doc.php?id=<?php echo $xml->head->id ?>" target=_parent><?php echo $xml->head->title ?></a></LI>
     <?php endforeach; ?>
       </UL>
        </DIV>
        </DIV>
<!--子导航导航开始-->
  <DIV class="bottommenu">
    <DIV id=mainmenu_bottom>
        <DIV class=mainmenu_rbg>
            <?php objectToArray($son);$count=count($son);?>
            <?php foreach ($parent as $list=>$xml) : ?>
            <?php  $parentid=$xml->head->id;?>
            <UL class=hide id=mb<?php echo ($list+1);?>>
             <?php for($i=0;$i<$count;$i++){?>
             <?php $sonid=$son["$i"]['head']['id'];?>
             <?php  if(strpos("$sonid","$parentid")===0):?>
            <LI><A href="tool-doc.php?id=<?php echo $sonid;?>"><?php echo $son["$i"]['head']['title'];?></A> </LI>
             <?php endif; ?>
            <?php }; ?>
            </UL>
            <?php endforeach; ?>
        </DIV>
    </DIV>
     </DIV>
        </div>
       <div id="bd">
                  <div id='note'><span>当前XML文件地址:<?php echo $xmlurl;?></span></div>
      <?php echo $contents ?>
      <div id="side">
        <div class="topside">如何使用？</div>
        <!--topside-->
        <div class="sidebox">
          <div class="list02">
             <?php objectToArray($idss);?>
             <?php foreach ($idss as $value) : ?><?php foreach ($value as $v) : ?>
               <?php $ids[]=$v;?>
             <?php endforeach; ?><?php endforeach; ?>
            <?php $id = $request->getGet('id','Index');$key=array_search("$id", $ids);$xml=$xmls["$key"];?>
            <?php objectToArray($xml);$titles = $xml['body']['h3'];$count=count($titles);?>
            <?php for($i=0;$i<$count;$i++){?>
            <div>
              <h2><a href="#"><?php if(is_Array($titles)){echo ($i+1).".".$titles["$i"];}else{ echo ($i+1).".".$titles;};?></a></h2>
            </div>
            <?php }; ?>
          </div>
          <!--list02-->
        </div>
        <!--sidebox-->
        <div class="bottomside">
        </div>
      </div>
      <!--side-->
      <div class="clear">
       </div>
      <!--bd-->
      </div>
      <div id="fd">
        ©2010 项目开发文档
      </div>
    </div>
  </body>
</html>