<?php

$config = $modx->migx->customconfigs;
$prefix = $config['prefix'];
$packageName = $config['packageName'];
$packagepath = $modx->getOption('core_path') . 'components/' . $packageName . '/';
$modelpath = $packagepath . 'model/';
$modx->addPackage($packageName, $modelpath, $prefix);
$classname = $config['classname'];

$object_id = $modx->getOption('object_id', $scriptProperties, '');

if ($object = $modx->getObject($classname,$object_id)){
    $formtabs = $modx->fromJson($object->get('formtabs'));
    
    foreach ($formtabs as $tab){
        $fields = $tab['fields'];
        if (!is_array($fields)){
            $fields = $modx->fromJson($fields);
        }
        if (is_array($fields)){
            foreach ($fields as $field){
                if (!empty($field['field'])){
                    $name = $field['field'];
                    if ($tv = $modx->getObject('modTemplateVar',array('name'=>$name))){
                        
                    }else{
                        $tv = $modx->newObject('modTemplateVar');
                        $tv->set('name',$name);
                        $tv->save(); 
                    }
                }
            }
        }
        
    }
    
}


return $modx->error->success('');