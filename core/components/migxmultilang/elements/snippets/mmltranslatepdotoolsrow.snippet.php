if (is_array($row)) {
    $outrow = array();
    foreach ($row as $key => $value) {
        if (substr($key, 0, 4) == 'mml.' && !empty($value)) {
            $tvname = substr($key, 4);
            $outrow[$tvname] = $value;
        }
    }

    $cultureKey = $modx->getOption('cultureKey');
    $main_lang = $modx->getOption('mml.main_lang');
    //$outrow['mml_lang_url'] = $modx->getOption('site_url');
    
    if ($cultureKey != $main_lang) {

       if ($row['link'] == '/'){
           $outrow['link'] = '';
       }
       
    }
    

}

return $modx->toJson($outrow);