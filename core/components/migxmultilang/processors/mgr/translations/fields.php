<?php

$resource_id = $modx->getOption('resource_id', $scriptProperties, 0);

//get cultureKey - system-setting
if ($setting = $modx->getObject('modSystemSetting',array('key'=>'cultureKey'))){
    $cultureKey = $setting->get('value');
}

//get cultureKey - context-setting
if ($resource = $modx->getObject('modResource',$resource_id)){
    $context= $modx->newObject('modContext');
    $context->_fields['key']= $resource->get('context_key');
    if ($context->prepare()){
        $cultureKey = isset($context->config['cultureKey']) ? $context->config['cultureKey'] : $cultureKey;    
    }    
}

$main_lang = $cultureKey;

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
if (is_dir($modelpath)){
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
    if ($object = $modx->newObject($classname)){
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
    if ($object = $modx->getObject($classname, $c)){
        $object_id = $object->get('id');
    }
}

$_SESSION['migxWorkingObjectid'] = $object_id;

//get translations for this language and this resource
$record = array();
if ($object && !empty($resource_id)){
    $lang_record = $object->toArray();
    foreach ($lang_record as $key=>$value){
        $record['Lang_'.$key] = $value;        
    }
    if ($main_lang == $lang_record['lang_key']){
        $tv_classname = 'modTemplateVarResource'; 
        $where = array('contentid'=>$resource_id);       
    }else{
        $tv_classname = 'mmlTemplateVarResource';
        $where = array('langid' => $object->get('id'),'contentid'=>$resource_id);
    }
    
    $joinclass = 'modTemplateVar';
    $jalias = 'TemplateVar';
    
    $c = $modx->newQuery($tv_classname);
    $c->select($modx->getSelectColumns($tv_classname, $tv_classname , ''));
    $c->leftjoin($joinclass, $jalias);
    $c->select($this->modx->getSelectColumns($joinclass, $jalias, $jalias . '_'));
    $c->where($where);
    //$c->prepare(); echo $c->toSql();
    if ($collection = $modx->getCollection($tv_classname,$c)){
        foreach ($collection as $object){
            $record[$object->get('TemplateVar_name')] = $object->get('value');
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
