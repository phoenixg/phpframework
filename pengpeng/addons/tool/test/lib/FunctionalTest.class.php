<?php
class FunctionalTest
{
  /**
   *
   * @param string $url
   * @param string $query
   * @return bool
   */
  public function assign200($url, $query= '')
  {
    $url = HtmlHelper::url($url, $query );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
    curl_exec($ch);

    if (200 == curl_getinfo($ch, CURLINFO_HTTP_CODE))
    {
      echo '.';

      return true;
    }
    else
    {
      echo "F\n";
      var_dump($url)."\n";
    }
  }

}