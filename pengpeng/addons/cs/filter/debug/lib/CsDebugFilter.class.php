<?php
class CsDebugFilter extends nbFilter
{
  public function execute($filterChain)
  {
    $filterChain->execute();

    $replace = "\n".nbWidget::getComponent('cs-filter-debug/csFilterDebug/index')."\n</body>";
    nbResponse::getInstance()->content = str_replace("</body>", $replace, nbResponse::getInstance()->content);
  }
}