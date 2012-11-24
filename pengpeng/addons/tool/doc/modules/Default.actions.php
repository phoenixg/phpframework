<?php

class DefaultActions extends nbAction {

  public function indexAction() {
    $finder = new nbFinder();
    $para['fileRegx'] = '/^(doc)\w*\..*\.xml/';
    $files = $finder->execute($para);

    $this->xmls = array();
    foreach ($files as $file) {
      $xml = simplexml_load_file($file);
      $this->xmls[] = $xml;
      $this->idss[]=$xml->head->id;
      $this->files[]=$file;
      $string=$xml->head->id;

      $pos = strpos($string, '-');
      if(!$pos){
        $this->parent[]=$xml;
        $this->parentids[]=$xml->head->id;
      }else{
        $this->son[]=$xml;
      }
    }
//    echo "<pre>";
    $request = nbRequest::getInstance();
    $id = $request->getGet('id','Index');
    foreach ($files as $file) {
      $xml = simplexml_load_file($file);
    if ($xml->head->id == $id) {
      $doc = new Doc;
      $this->display = $doc->execute($xml);
      $this->xmlurl=$file;

    }
    }

    function objectToArray(&$object) {
      $object = (array) $object;
      foreach ($object as $key => $value) {
        if (is_object($value)) {
          objectToArray($value);
          $object[$key] = $value;
        }
        if (is_array($value)) {
          objectToArray($value);
          $object[$key] = $value;
        }
      }
    }
  }

}