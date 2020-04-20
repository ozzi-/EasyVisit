<?php
/**
 * EasyVisit
 * @author   zgheb.com
 * @license  See EULA_READ_ME.txt
 */
  $translation = array();

  function translation_get($key){
    global $translation;
    return isset($translation[$key]) ? $translation[$key] : 'missing translation for '.filter_var($key,FILTER_SANITIZE_ENCODED);
  }
  include("translations/".LANGUAGE);
?>
