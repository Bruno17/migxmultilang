$template = $modx->getOption('template', $scriptProperties, $modx->resource->get('template'));

$packageName = 'migxmultilang';
$packagepath = $modx->getOption('core_path') . 'components/' . $packageName . '/';
$modelpath = $packagepath . 'model/';
$modx->addPackage($packageName, $modelpath);
$classname = 'mmlFormtabsTemplate';
$ftClassname = 'mmlFormtabs';
$tvs = array();

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
                $tvs[] = $field['field'];
            }
        }
    }
}

return implode(',', $tvs);