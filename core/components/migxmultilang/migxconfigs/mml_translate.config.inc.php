<?php

$action = $this->modx->getOption('action', $_REQUEST, '');
$langloaded = $this->modx->getOption('langloaded', $_REQUEST, false);

$packageName = 'migxmultilang';
$packagepath = $this->modx->getOption('core_path') . 'components/' . $packageName . '/';
$modelpath = $packagepath . 'model/';
$this->modx->addPackage($packageName, $modelpath);

if (!$langloaded) {
    $_REQUEST['langloaded'] = true;
    $this->modx->lexicon->load('migxmultilang:default');
    $this->loadLang('mml');
}

if ($action == 'mgr/migxdb/getList' || $action == 'mgr/migxdb/fields') {

    $resource_id = $this->modx->getOption('resource_id', $_REQUEST, 0);

    //get cultureKey - system-setting
    if ($setting = $this->modx->getObject('modSystemSetting', array('key' => 'cultureKey'))) {
        $cultureKey = $setting->get('value');
    }

    //get cultureKey - context-setting
    if ($resource = $this->modx->getObject('modResource', $resource_id)) {
        $template = $resource->get('template');
        $context = $this->modx->newObject('modContext');
        $context->_fields['key'] = $resource->get('context_key');
        if ($context->prepare()) {
            $cultureKey = isset($context->config['cultureKey']) ? $context->config['cultureKey'] : $cultureKey;
        }
    }

    $main_lang = $cultureKey;
    $properties = array();
    //try to get formtab for current resource-template
    if ($ftt_object = $this->modx->getObject('mmlFormtabsTemplate', array('templateid' => $template))) {
        if ($object = $ftt_object->getOne('Formtabs')) {
            $properties = $object->get('properties');
        }
    }

    if (count($properties) < 1) {
        //try to get default formtab
        if ($object = $this->modx->getObject('mmlFormtabs', array('default' => '1'))) {
            $properties = $object->get('properties');
        }
    }

    if (isset($properties['hide_defaultlang']) && !empty($properties['hide_defaultlang'])) {
        $this->customconfigs['getlistwhere'] = '{"lang_key:!=":"' . $main_lang . '"}';
    }


}

if ($action == 'mgr/migxdb/fields') {
    $tabs = $this->modx->getOption('tabs', $this->customconfigs, '');
    //$tabs = $this->modx->fromJson($tabs);

    if (false == $this->modx->getOption('is_mainlang', $_REQUEST, false)) {
        $formtabs = array();
        foreach ($tabs as $tab) {
            $tabfields = array();
            if ($fields = $this->modx->getOption('fields', $tab, false)) {
                $fields = !is_array($fields) ? $this->modx->fromJson($fields) : $fields;
                if (is_array($fields)) {
                    foreach ($fields as $field) {
                        $tabfields[] = $field;
                        $newfield = array();
                        $newfield['field'] = 'mml_checkbox_' . $field['field'];
                        $newfield['inputTVtype'] = 'mml_checkboxes';
                        $newfield['inputOptionValues'] = $this->modx->lexicon('mml.published') . '==published||' . $this->modx->lexicon('mml.to_translate') . '==totranslate';
                        $tabfields[] = $newfield;
                    }
                }
            }
            $tab['fields'] = $tabfields;
            $formtabs[] = $tab;
        }

        $this->customconfigs['tabs'] = $formtabs;
    } else {
        if (isset($properties['hide_defaultlang_fields']) && !empty($properties['hide_defaultlang_fields'])) {
            $hide_fields = explode(',', $properties['hide_defaultlang_fields']);
            $formtabs = array();
            foreach ($tabs as $tab) {
                $tabfields = array();
                if ($fields = $this->modx->getOption('fields', $tab, false)) {
                    $fields = !is_array($fields) ? $this->modx->fromJson($fields) : $fields;
                    if (is_array($fields)) {
                        foreach ($fields as $field) {
                            if (!in_array($field['field'],$hide_fields)){
                                $tabfields[] = $field;
                            }
                        }
                    }
                }
                $tab['fields'] = $tabfields;
                $formtabs[] = $tab;
            }

            $this->customconfigs['tabs'] = $formtabs;

        }
    }
}
