<?php

/**
 * MIGXdb
 *
 * Copyright 2012 by Bruno Perner <b.perner@gmx.de>
 *
 * This file is part of MIGXdb, for editing custom-tables in MODx Revolution CMP.
 *
 * MIGXdb is free software; you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * MIGXdb is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * MIGXdb; if not, write to the Free Software Foundation, Inc., 59 Temple Place,
 * Suite 330, Boston, MA 02111-1307 USA 
 *
 * @package migx
 */
/**
 * Update and Create-processor for migxdb
 *
 * @package migx
 * @subpackage processors
 */
//if (!$modx->hasPermission('quip.thread_view')) return $modx->error->failure($modx->lexicon('access_denied'));

//return $modx->error->failure('huhu');

if (empty($scriptProperties['object_id'])) {
    $updateerror = true;
    $errormsg = $modx->lexicon('quip.thread_err_ns');
    return;
}

$config = $modx->migx->customconfigs;
$prefix = isset($config['prefix']) && !empty($config['prefix']) ? $config['prefix'] : null;
$errormsg = '';

if (isset($config['use_custom_prefix']) && !empty($config['use_custom_prefix'])) {
    $prefix = isset($config['prefix']) ? $config['prefix'] : '';
}
$packageName = $config['packageName'];

$packagepath = $modx->getOption('core_path') . 'components/' . $packageName . '/';
$modelpath = $packagepath . 'model/';
$is_container = $modx->getOption('is_container', $config, false);
if (is_dir($modelpath)) {
    $modx->addPackage($packageName, $modelpath, $prefix);
}
$classname = $config['classname'];

if ($modx->lexicon) {
    $modx->lexicon->load($packageName . ':default');
}

$co_id = $modx->getOption('co_id', $scriptProperties, '');

if (isset($scriptProperties['data'])) {
    $scriptProperties = array_merge($scriptProperties, $modx->fromJson($scriptProperties['data']));
}

$object_id = $modx->getOption('object_id', $scriptProperties, 0);
$resource_id = $modx->getOption('resource_id', $scriptProperties, false);
$resource_id = !empty($co_id) ? $co_id : $resource_id;
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
$postvalues = array();
$arraydelimiters = $modx->getOption('arraydelimiters', $config, array());
$arrayenclosings = $modx->getOption('arrayenclosings', $config, array());
$default_arraydelimiter = $modx->getOption('arraydelimiter', $config, '||');
$default_arrayenclosing = $modx->getOption('arrayenclosing', $config, '');
$validation_errors = array();

if ($object = $modx->getObject($classname, $object_id)) {

    foreach ($scriptProperties as $field => $value) {
        $fieldid++;
        /* handles checkboxes & multiple selects elements */
        if (is_array($value)) {
            $featureInsert = array();
            while (list($featureValue, $featureItem) = each($value)) {
                $featureInsert[count($featureInsert)] = $featureItem;
            }
            $arraydelimiter = $modx->getOption($field, $arraydelimiters, $default_arraydelimiter);
            $arrayenclosing = $modx->getOption($field, $arrayenclosings, $default_arrayenclosing);
            $value = $arrayenclosing . implode($arraydelimiter, $featureInsert) . $arrayenclosing;
        }

        $field = explode('.', $field);

        if (count($field) > 1) {
            //extended field (json-array)
            $postvalues[$field[0]][$field[1]] = $value;
        } else {
            $postvalues[$field[0]] = $value;
        }

    }

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
            $c->where($where);
            $c->where(array('tmplvarid' => $tmplvarid));
            if ($tvObject = $modx->getObject($tv_classname, $c)) {

                $value = $modx->getOption('mml_field_' . $lang_key, $postvalues, '');
                $cb_values = $modx->getOption('mml_checkbox_' . $lang_key, $postvalues, '');
                $cb_values = explode('||', $cb_values);


                $tvObject->set('value', $value);
                $tvObject->set('published', in_array('published', $cb_values) ? 1 : 0);
                $tvObject->set('totranslate', in_array('totranslate', $cb_values) ? 1 : 0);
                $tvObject->save();
            }
        }
    }
}


//clear cache for all contexts
$collection = $modx->getCollection('modContext');
foreach ($collection as $context) {
    $contexts[] = $context->get('key');
}
$modx->cacheManager->refresh(array(
    'db' => array(),
    'auto_publish' => array('contexts' => $contexts),
    'context_settings' => array('contexts' => $contexts),
    'resource' => array('contexts' => $contexts),
    ));

?>
