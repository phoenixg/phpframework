<?php
class Doc
{
  public function execute($xml)
  {
    $html = '';

    foreach ($xml->body->children() as $child)
    {
      $tagName = $child->getName();
      $title = $child->attributes();

      if (in_array($tagName, array('h3', 'p')))//数组中设定需要用到的标签.
      {
        $html .= '<'.$tagName.'>'.$child.'</'.$tagName.'>';//这些标签转换程html格式.比如h3-><h3>内容</h3>.
      }
      else if ($tagName == 'pre')//不同类型或不同试用方法的标签
      {
        $html .= '<'.$tagName.'>'.htmlspecialchars($child).'</'.$tagName.'>';
      }
      else if ($tagName == 'php')//不同类型或不同试用方法的标签
      {
        $html .= "<pre class='brush:php;collapse:true'>".$child."</pre>";//实现php的语法高亮.
      }
      else if ($tagName == 'js')//不同类型或不同试用方法的标签
      {
        $html .= "<pre class='brush: js;wrap-lines:true;'>".$child."</pre>";//实现js的语法高亮.
      }
      else if ($tagName == 'note')
      {
        $html .= "<div id= note ><span>".$child."</span></div>";//要点预览.

      }
      else if ($tagName == 'a')
      {
         $html .= "<a href=".$title.">".$child."</a>";//超链接标签
      }
      else if ($tagName == 'table')
      {
        foreach ($child as $table)
        {
          $tagtr = $table->getName();
          if ($tagtr=='tr')
          {
            foreach ($table as $td)
            {
             $tagtd = $td->getName();
             $table .= '<'.$tagtd.'>'.$td.'</'.$tagtd.'>';
            }
            $child .= '<'.$tagtr.'>'.$table.'</'.$tagtr.'>';
          }
        }
         $html .= "<table border='1' style='background:#E2F2FC;width:600px;'>".$child."</table>";
      }
      else if ($tagName == 'list')
      {
        foreach ($child as $list)
        {
          $taglist = $list->getName();
          if ($taglist=='list')
          {
            foreach ($list as $li)
            {
              $tagli = $li->getName();
              $list .= '<'.$tagli.'>'.$li.'</'.$tagli.'>';
            }
          }
          $child .= '<'.$taglist.'>'.$list.'</'.$taglist.'>';
        }
        $html .= "<ul>".$child."</ul>";
      }

    }

    return $html;
  }
}
