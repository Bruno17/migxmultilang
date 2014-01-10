<?php

$action = $this->modx->getOption('action', $_REQUEST, '');
$main_lang = $this->modx->getOption('main_lang', $_REQUEST, '');

if ($action == 'mgr/migxdb/fields') {

    $packageName = 'migxmultilang';

    $packagepath = $this->modx->getOption('core_path') . 'components/' . $packageName . '/';
    $modelpath = $packagepath . 'model/';
    if (is_dir($modelpath)) {
        $this->modx->addPackage($packageName, $modelpath, $prefix);
    }
    $classname = 'mmlLang';
    $formtabs = array();
    if ($collection = $this->modx->getCollection($classname)) {
        foreach ($collection as $object) {
            $tabfields = array();
            if ($object->get('lang_key') != $main_lang) {
                $newfield = array();
                $newfield['field'] = 'mml_checkbox_' . $object->get('lang_key');
                $newfield['inputTVtype'] = 'checkbox';
                $newfield['inputOptionValues'] = 'published==published||To translate==totranslate';
                $tabfields[] = $newfield;
            }

            $newfield = array();
            $newfield['field'] = 'mml_field_' . $object->get('lang_key');
            $newfield['inputTVtype'] = 'textarea';
            $tabfields[] = $newfield;

            $tab = array();
            $tab['caption'] = $object->get('language');
            $tab['fields'] = $tabfields;
            $formtabs[] = $tab;
        }
    }

    $this->customconfigs['tabs'] = $formtabs;


}
