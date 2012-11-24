<?php nbHeadHelper::useJs('/nb/js/jquery.js'); ?>
<style>
#sfWebDebug
{
  padding: 0;
  margin: 0;
  font-family: Arial, sans-serif;
  font-size: 12px;
  color: #333;
  text-align: left;
  line-height: 12px;
}

#sfWebDebug a, #sfWebDebug a:hover
{
  text-decoration: none;
  border: none;
  background-color: transparent;
  color: #000;
}

#sfWebDebug img
{
  float: none;
  margin: 0;
  border: 0;
  display: inline;
}

#sfWebDebugBar
{
  position: absolute;
  margin: 0;
  padding: 1px 0;
  right: 0px;
  top: 0px;
  opacity: 0.80;
  filter: alpha(opacity:80);
  z-index: 10000;
  white-space: nowrap;
  background-color: #ddd;
}

#sfWebDebugBar[id]
{
  position: fixed;
}

#sfWebDebugBar img
{
  vertical-align: middle;
}

#sfWebDebugBar .sfWebDebugMenu
{
  padding: 5px;
  padding-left: 0;
  display: inline;
  margin: 0;
}

#sfWebDebugBar .sfWebDebugMenu li
{
  display: inline;
  list-style: none;
  margin: 0;
  padding: 0 6px;
}

#sfWebDebugBar .sfWebDebugMenu li.last
{
  margin: 0;
  padding: 0;
  border: 0;
}

#sfWebDebugDatabaseDetails li
{
  margin: 0;
  margin-left: 30px;
  padding: 5px 0;
}

#sfWebDebugShortMessages li
{
  margin-bottom: 10px;
  padding: 5px;
  background-color: #ddd;
}

#sfWebDebugShortMessages li
{
  list-style: none;
}

#sfWebDebugDetails
{
  margin-right: 7px;
}

#sfWebDebug pre
{
  line-height: 1.3;
  margin-bottom: 10px;
}

#sfWebDebug h1
{
  font-size: 16px;
  font-weight: bold;
  margin: 20px 0;
  padding: 0;
  border: 0px;
  background-color: #eee;
}

#sfWebDebug h2
{
  font-size: 14px;
  font-weight: bold;
  margin: 10px 0;
  padding: 0;
  border: 0px;
  background: none;
}

#sfWebDebug h3
{
  font-size: 12px;
  font-weight: bold;
  margin: 10px 0;
  padding: 0;
  border: 0px;
  background: none;
}

#sfWebDebug .sfWebDebugTop
{
  position: absolute;
  left: 0px;
  top: 0px;
  width: 98%;
  padding: 0 1%;
  margin: 0;
  z-index: 9999;
  background-color: #efefef;
  border-bottom: 1px solid #aaa;
  display:none;
}

#sfWebDebugLog
{
  margin: 0;
  padding: 3px;
  font-size: 11px;
}

#sfWebDebugLogMenu
{
  margin-bottom: 5px;
}

#sfWebDebugLogMenu li
{
  display: inline;
  list-style: none;
  margin: 0;
  padding: 0 5px;
  border-right: 1px solid #aaa;
}

#sfWebDebugConfigSummary
{
  display: inline;
  padding: 5px;
  background-color: #ddd;
  border: 1px solid #aaa;
  margin: 20px 0;
}

#sfWebDebugConfigSummary li
{
  list-style: none;
  display: inline;
  margin: 0;
  padding: 0 5px;
}

#sfWebDebugConfigSummary li.last
{
  border: 0;
}

.sfWebDebugInfo, .sfWebDebugInfo td
{
  background-color: #ddd;
}

.sfWebDebugWarning, .sfWebDebugWarning td
{
  background-color: orange !important;
}

.sfWebDebugError, .sfWebDebugError td
{
  background-color: #f99 !important;
}

.sfWebDebugLogNumber
{
  width: 1%;
}

.sfWebDebugLogType
{
  width: 1%;
  white-space: nowrap;
}

.sfWebDebugLogType, #sfWebDebug .sfWebDebugLogType a
{
  color: darkgreen;
}

#sfWebDebug .sfWebDebugLogType a:hover
{
  text-decoration: underline;
}

.sfWebDebugLogInfo
{
  color: blue;
}

.ison
{
  color: #3f3;
  margin-right: 5px;
}

.isoff
{
  color: #f33;
  margin-right: 5px;
  text-decoration: line-through;
}

.sfWebDebugLogs
{
  padding: 0;
  margin: 0;
  border: 1px solid #999;
  font-family: Arial;
  font-size: 11px;
}

.sfWebDebugLogs tr
{
  padding: 0;
  margin: 0;
  border: 0;
}

.sfWebDebugLogs td
{
  margin: 0;
  border: 0;
  padding: 1px 3px;
  vertical-align: top;
}

.sfWebDebugLogs th
{
  margin: 0;
  border: 0;
  padding: 3px 5px;
  vertical-align: top;
  background-color: #999;
  color: #eee;
  white-space: nowrap;
}

.sfWebDebugDebugInfo
{
  color: #999;
  font-size: 11px;
  margin: 5px 0 5px 10px;
  padding: 2px 0 2px 5px;
  border-left: 1px solid #aaa;
  line-height: 1.25em;
}

.sfWebDebugDebugInfo .sfWebDebugLogInfo,
.sfWebDebugDebugInfo a.sfWebDebugFileLink
{
  color: #333 !important;
}

.sfWebDebugCache
{
  padding: 0;
  margin: 0;
  font-family: Arial;
  position: absolute;
  overflow: hidden;
  z-index: 995;
  font-size: 9px;
  padding: 2px;
  filter:alpha(opacity=85);
  -moz-opacity:0.85;
  opacity: 0.85;
}

#sfWebDebugSymfonyVersion
{
  margin-left: 0;
  padding: 1px 4px;
  background-color: #666;
  color: #fff;
}

#sfWebDebugviewDetails ul
{
  padding-left: 2em;
  margin: .5em 0;
  list-style: none;
}

#sfWebDebugviewDetails li
{
  margin-bottom: .5em;
}

#sfWebDebug .sfWebDebugDataType,
#sfWebDebug .sfWebDebugDataType a
{
  color: #666;
  font-style: italic;
}

#sfWebDebug .sfWebDebugDataType a:hover
{
  text-decoration: underline;
}

#sfWebDebugDatabaseLogs
{
  margin-bottom: 10px;
}

#sfWebDebugDatabaseLogs ol
{
  margin: 0;
  padding: 0;
  margin-left: 20px;
  list-style: number;
}

#sfWebDebugDatabaseLogs li
{
  padding: 6px;
}

#sfWebDebugDatabaseLogs li:nth-child(odd)
{
  background-color: #CCC;
}

.sfWebDebugDatabaseQuery
{
  margin-bottom: .5em;
  margin-top: 0;
}

.sfWebDebugDatabaseLogInfo
{
  color: #666;
  font-size: 11px;
}

.sfWebDebugDatabaseQuery .sfWebDebugLogInfo
{
  color: #909;
  font-weight: bold;
}

.sfWebDebugHighlight
{
  background: #FFC;
}
</style>
<script type="text/javascript">
toggleTab = function(id)
{
  $('.sfWebDebugTop').hide();
  $('#sfWebDebug' + id + 'Details').show();
  return false;
}

</script>
<div id="sfWebDebug">
    <div id="sfWebDebugBar">
          <ul class="sfWebDebugMenu" id="sfWebDebugDetails">
<?php foreach ($debugInfo as $name => $value): ?>
<li class="sfWebDebugInfo"><a onclick="toggleTab('<?php echo $name ?>');" href="javascript:void(0)" title="View Layer"> <?php echo $name ?></a></li>
<?php endforeach; ?>
          </ul>
        </div>
<?php foreach ($debugInfo as $name => $value): ?>
<div style="" class="sfWebDebugTop" id="sfWebDebug<?php echo $name ?>Details">
<?php echo $value['content'] ?>
</div>
<?php endforeach; ?>
</div>