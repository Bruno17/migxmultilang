<?php
$get_tvs_fromformtabs = $modx->getOption('get_tvs_fromformtabs', $scriptProperties, 1);
$get_tvs_fromtemplate = $modx->getOption('get_tvs_fromtemplate', $scriptProperties, 1);

$template = $modx->getOption('template', $scriptProperties, $modx->resource->get('template'));

$packageName = 'migxmultilang';
$packagepath = $modx->getOption('core_path') . 'components/' . $packageName . '/';
$modelpath = $packagepath . 'model/';
$modx->addPackage($packageName, $modelpath);
$classname = 'mmlFormtabsTemplate';
$ftClassname = 'mmlFormtabs';
$tvs = array();

if (!empty($get_tvs_fromformtabs)) {
    if ($object = $modx->getObject($classname, array('templateid' => $template))) {
        $object_id = $object->get('formtabsid');
        $ftObject = $modx->getObject($ftClassname, $object_id);
    } elseif ($ftObject = $modx->getObject($ftClassname, array('default' => '1'))) {
        $object_id = $ftObject->get('id');
    }

    if ($ftObject) {
        $formtabs = $modx->fromJson($ftObject->get('formtabs'));
        foreach ($formtabs as $tab) {
            $fields = $tab['fields'];
            if (!is_array($fields)) {
                $fields = $modx->fromJson($fields);
            }
            if (is_array($fields)) {
                foreach ($fields as $field) {
                    $tvs[$field['field']] = $field['field'];
                }
            }
        }
    }
}

//get tvs from template
if (!empty($get_tvs_fromtemplate)) {
    if ($tObject = $modx->getObject('modTemplate', $template)) {
        if ($collection = $tObject->getTemplateVars()) {
            foreach ($collection as $object) {
                $tvs[$object->get('name')] = $object->get('name');
            }
        }
    }
}
return implode(',', $tvs);