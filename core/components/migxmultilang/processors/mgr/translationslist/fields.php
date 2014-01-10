<?php

$config = $modx->migx->customconfigs;
$prefix = isset($config['prefix']) && !empty($config['prefix']) ? $config['prefix'] : null;
$object_id = 'new';

if (isset($config['use_custom_prefix']) && !empty($config['use_custom_prefix'])) {
    $prefix = isset($config['prefix']) ? $config['prefix'] : '';
}
$packageName = $config['packageName'];
$sender = 'default/fields';

$packagepath = $modx->getOption('core_path') . 'components/' . $packageName . '/';
$modelpath = $packagepath . 'model/';
if (is_dir($modelpath)) {
    $modx->addPackage($packageName, $modelpath, $prefix);
}
$classname = $config['classname'];

$joinalias = isset($config['join_alias']) ? $config['join_alias'] : '';

$joins = isset($config['joins']) && !empty($config['joins']) ? $modx->fromJson($config['joins']) : false;

if (!empty($joinalias)) {
    if ($fkMeta = $modx->getFKDefinition($classname, $joinalias)) {
        $joinclass = $fkMeta['class'];
    } else {
        $joinalias = '';
    }
}

if ($this->modx->lexicon) {
    $this->modx->lexicon->load($packageName . ':default');
}

if (empty($scriptProperties['object_id']) || $scriptProperties['object_id'] == 'new') {
    if ($object = $modx->newObject($classname)) {
        $object->set('object_id', 'new');
    }

} else {
    $c = $modx->newQuery($classname, $scriptProperties['object_id']);
    $pk = $modx->getPK($classname);
    $c->select('
        `' . $classname . '`.*,
    	`' . $classname . '`.`' . $pk . '` AS `object_id`
    ');
    if (!empty($joinalias)) {
        $c->leftjoin($joinclass, $joinalias);
        $c->select($modx->getSelectColumns($joinclass, $joinalias, 'Joined_'));
    }

    if ($joins) {
    $modx->migx->prepareJoins($classname, $joins, $c);
    }

    if ($object = $modx->getObject($classname, $c)) {
        $object_id = $object->get('id');
    }
}

$_SESSION['migxWorkingObjectid'] = $object_id;

$record = array();
if ($object) {
    $record = $object->toArray();
    
    $tmplvarid = $object->get('tmplvarid');
    $resource_id = $object->get('contentid');

    //get cultureKey - system-setting
    if ($setting = $modx->getObject('modSystemSetting', array('key' => 'cultureKey'))) {
        $cultureKey = $setting->get('value');
    }

    //get cultureKey - context-setting
    if ($resource = $modx->getObject('modResource', $resource_id)) {
        $context = $modx->newObject('modContext');
        $context->_fields['key'] = $resource->get('context_key');
        if ($context->prepare()) {
            $cultureKey = isset($context->config['cultureKey']) ? $context->config['cultureKey'] : $cultureKey;
        }
    }

    $main_lang = $cultureKey;
    $_REQUEST['main_lang'] = $cultureKey;

    $classname = 'mmlLang';
    $formtabs = array();
    if ($collection = $this->modx->getCollection($classname)) {
        foreach ($collection as $object) {
            $lang_key = $object->get('lang_key');
            if ($main_lang == $lang_key) {
                $tv_classname = 'modTemplateVarResource';
                $where = array('contentid' => $resource_id);
            } else {
                $tv_classname = 'mmlTemplateVarResource';
                $where = array('langid' => $object->get('id'), 'contentid' => $resource_id);
            }

            $c = $modx->newQuery($tv_classname);
            $c->select($modx->getSelectColumns($tv_classname, $tv_classname, ''));
            $c->where($where);
            $c->where(array('tmplvarid' => $tmplvarid));
            if ($tvObject = $modx->getObject($tv_classname, $c)) {
                $record['mml_field_' . $lang_key] = $tvObject->get('value');
                $cb_values = array();
                if ($tvObject->get('published') == '1') {
                    $cb_values[] = 'published';
                }
                if ($tvObject->get('totranslate') == '1') {
                    $cb_values[] = 'totranslate';
                }
                $record['mml_checkbox_' . $lang_key] = implode('||', $cb_values);
            }
        }
    }
}


foreach ($record as $field => $fieldvalue) {
    if (!empty($fieldvalue) && is_array($fieldvalue)) {
        foreach ($fieldvalue as $key => $value) {
            $record[$field . '.' . $key] = $value;
        }
    }
}
