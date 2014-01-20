<?php

$langloaded = $this->modx->getOption('langloaded', $_REQUEST, false);

if (!$langloaded) {
    $_REQUEST['langloaded'] = true;
    $this->modx->lexicon->load('migxmultilang:default');
    $this->loadLang('mml');
}

if (!empty($_REQUEST['tempParams']) && $_REQUEST['tempParams'] == 'raw') {
    $tabs = '
    [
    {"caption":"Formtabs", "fields": [
        {"field":"formtabs","caption":"Formtabs","inputTVtype":"textarea"}
    ]}
    ]
    ';
    $this->customconfigs['tabs'] = $this->modx->fromJson($tabs);

}
