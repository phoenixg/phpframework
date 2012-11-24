<?php
class TMQzoneService
{
  public static function sendQzoneGuajian($actid, $qq, $option = array())
  {
    $fields = self::setOptions($option,'hang_annex');
    return self::sendQzoneService($actid,$qq,$fields);
  }

  public static function sendQzonBlog($actid, $qq, $option = array())
  {
    $fields = self::setOptions($option,'post_blog');
    return self::sendQzoneService($actid,$qq,$fields);
  }

  public static function sendQzonFeeds($actid, $qq, $image)
  {
    $option['albumlife[0][t]'] = $image;
    return self::sendQzoneService($actid, $qq, $option);
  }

  public static function setOptions($option,$type)
  {
    if(count($option) == 0)
    {
      $fields = array(
        $type => 1
      );
    }
    else
    {
      $fields = $option;
      if(!array_key_exists($type,$option))
      {
        $fields[$type] = 1;
      }
    }
    return $fields;
  }

  public static function sendQzoneService($actid, $qq, $option)
  {
    if ($_SERVER['SERVER_TYPE'] == "dev" || $_SERVER['SERVER_TYPE'] == "localhost")
    {
      $service = new TMService;
      $service->insertWithTime(array('FQQ' => $qq, 'FType' => $actid), 'Fake_Tbl_Qzonegood');
      return true;
    }

    $ips = array('10.128.33.43', '10.128.33.44');
    $ip = $ips[rand(0, count($ips) - 1)];

    $curl =  new TMCurl("http://".$ip."/wapi.php/main/user/?act_id=".$actid."&unicode");

    $fields = $option;
    $fields["ref"] = 'hdgp';
    $fields["appkey"] = 'fdfb4d662582b832140953470b6d6574';
    $fields["qq"] = $qq;
    $fields['uin']= $qq; //用户的QQ号码
    $fields['md5']= md5('mudi5n' . $qq . '#$ui982~)=c+^%3*');

    $fields_string = TMUtil::handleParameter($fields);
    $cookie_string = TMUtil::handleParameter($_COOKIE, ";");

    $option = array(
      CURLOPT_HEADER =>0,
      CURLOPT_RETURNTRANSFER=>1,
      CURLOPT_COOKIE => $cookie_string,
      CURLOPT_POST => count($fields),
      CURLOPT_POSTFIELDS => $fields_string,
      CURLOPT_HTTPHEADER => array('Host: act.qzone.qq.com')
    );

    $curl->setOptions($option);
    try
    {
      $result = $curl->execute();
    }
    catch(TMException $te)
    {
      return false;
    }

    $result_array = json_decode($result,true);

    if ($result_array['ret'] == 4502)
    {
      throw new nbMessageException('您还没有开通Qzone！');
    }

    if(array_key_exists('msg', $result_array))
    {
      new nbLog($qq." message: ".$result_array["msg"].$result);
      return false;
    }
    else
    {
      new nbLog($qq." message: ".$result);
      return true;
    }
  }

}
?>
