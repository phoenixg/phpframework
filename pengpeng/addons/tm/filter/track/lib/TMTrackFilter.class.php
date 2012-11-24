<?php
/*
 *---------------------------------------------------------------------------
 *
 *                  T E N C E N T   P R O P R I E T A R Y
 *
 *     COPYRIGHT (c)  2009 BY  TENCENT  CORPORATION.  ALL RIGHTS
 *     RESERVED.   NO  PART  OF THIS PROGRAM  OR  PUBLICATION  MAY
 *     BE  REPRODUCED,   TRANSMITTED,   TRANSCRIBED,   STORED  IN  A
 *     RETRIEVAL SYSTEM, OR TRANSLATED INTO ANY LANGUAGE OR COMPUTER
 *     LANGUAGE IN ANY FORM OR BY ANY MEANS, ELECTRONIC, MECHANICAL,
 *     MAGNETIC,  OPTICAL,  CHEMICAL, MANUAL, OR OTHERWISE,  WITHOUT
 *     THE PRIOR WRITTEN PERMISSION OF :
 *
 *                        TENCENT  CORPORATION
 *
 *       Advertising Platform R&D Team, Advertising Platform & Products
 *       Tencent Ltd.
 *---------------------------------------------------------------------------
 */

/**
 * 流程链--添加页面流量监控
 *
 * LIB库内部调用
 * 开关：config/filter.yml - TMTrackFilter
 * 监控代码在 ROOT_PATH . 'templates/tack.php'
 * 如果需要增加第三方监控代码，比如google，只需要修改ROOT_PATH . 'templates/tack.php'往里面添加即可
 *
 * @package lib.filter
 * @author  ianzhang <ianzhang@tencent.com>
 * @version TMTrackFilter.class.php 2009-4-17 by ianzhang
 */
class TMTrackFilter extends nbFilter
{
	/**
	 * 执行添加监控代码逻辑
	 * 监控代码在 ROOT_PATH . 'templates/tack.php'
	 * @param TMFilterChain $filterChain
	 */
	public function execute($filterChain)
	{
		$filterChain->execute();

		$replace = "\n".nbMvcWidget::getPartial('tm-filter-track/default/track')."\n</body>";
    nbResponse::getInstance()->content = str_replace("</body>",$replace,nbResponse::getInstance()->content);
	}
}