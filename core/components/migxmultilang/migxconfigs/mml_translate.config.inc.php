<?php

$action = $this->modx->getOption('action', $_REQUEST, '');
$langloaded = $this->modx->getOption('langloaded', $_REQUEST, false);

if (!$langloaded) {
    $_REQUEST['langloaded'] = true;
    $this->modx->lexicon->load('migxmultilang:default');
    $this->loadLang('mml');
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
    }
}
