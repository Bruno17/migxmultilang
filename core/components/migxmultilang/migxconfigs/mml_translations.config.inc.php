<?php

$action = $this->modx->getOption('action', $_REQUEST, '');
$langloaded = $this->modx->getOption('langloaded', $_REQUEST, false);

if (!$langloaded) {
    $_REQUEST['langloaded'] = true;
    $this->modx->lexicon->load('migxmultilang:default');
    $this->loadLang('mml');
}

if ($action == 'mgr/migxdb/fields') {

    $resource_id = $this->modx->getOption('resource_id', $_REQUEST, 0);
    $template = 0;
    $formtabs = '';

    if ($resource = $this->modx->getObject('modResource', $resource_id)) {
        $template = $resource->get('template');
    }

    //try to get formtab for current resource-template
    if ($ftt_object = $this->modx->getObject('mmlFormtabsTemplate', array('templateid' => $template))) {
        if ($object = $ftt_object->getOne('Formtabs')) {
            $formtabs = $object->get('formtabs');
        }
    }

    if (empty($formtabs)) {
        //try to get default formtab
        if ($object = $this->modx->getObject('mmlFormtabs', array('default' => '1'))) {
            $formtabs = $object->get('formtabs');
        }
    }

    if (!empty($formtabs)){
        $this->customconfigs['tabs'] = $this->modx->fromJson($formtabs);
    }
    


}
