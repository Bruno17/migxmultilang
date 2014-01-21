if (is_array($row)) {
    $outrow = array();
    foreach ($row as $key => $value) {
        if (substr($key, 0, 4) == 'mml.' && !empty($value)) {
            $tvname = substr($key, 4);
            $outrow[$tvname] = $value;
            $row[$tvname] = $value;
        }
    }
    $resourcefields=array('pagetitle','longtitle','introtext','description','menutitle','content');
    //copy translated values to resourcefields, if not empty
    $tvPrefix = $modx->getOption('tvPrefix',$pdoTools->config,'');
    $translateResourcefields = $modx->getOption('translateResourcefields',$pdoTools->config,'1');
    if (!empty($translateResourcefields)){
       $resourcefields = explode(',',$modx->getOption('resourcefields',$pdoTools->config,'pagetitle,longtitle,introtext,description,menutitle,content'));
    } 
     
    foreach ($resourcefields as $field){
        if (!empty($row[$tvPrefix.'mml_' . $field])){
            $outrow[$field] = $row[$tvPrefix.'mml_' . $field];
        }
    }
    
    $cultureKey = $modx->getOption('cultureKey');
    $main_lang = $modx->getOption('original_cultureKey');
    $base_url = $modx->getOption('base_url');
    
    if ($cultureKey != $main_lang) {

       if ($row['link'] == $base_url){
           $outrow['link'] = '';
       }
       
    }
}

return $modx->toJson($outrow);