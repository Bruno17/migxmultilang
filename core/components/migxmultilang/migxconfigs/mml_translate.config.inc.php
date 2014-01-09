<?php

$action = $this->modx->getOption('action', $_REQUEST, '');

if ($action == 'mgr/migxdb/fields') {
    $tabs = $this->modx->getOption('formtabs', $this->customconfigs, '');
    $tabs = $this->modx->fromJson($tabs);
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
                        $newfield['inputOptionValues'] = 'published==published||To translate==totranslate';
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
