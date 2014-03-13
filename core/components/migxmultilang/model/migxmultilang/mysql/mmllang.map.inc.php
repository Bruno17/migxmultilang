<?php
$xpdo_meta_map['mmlLang']= array (
  'package' => 'migxmultilang',
  'version' => NULL,
  'table' => 'mml_languages',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'language' => '',
    'lang_key' => '',
    'createdon' => NULL,
    'active' => 0,
    'properties' => NULL,
    'pos' => 0,
  ),
  'fieldMeta' => 
  array (
    'language' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '50',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
      'index' => 'index',
    ),
    'lang_key' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '2',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
      'index' => 'index',
    ),
    'createdon' => 
    array (
      'dbtype' => 'datetime',
      'phptype' => 'datetime',
      'null' => true,
    ),
    'active' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '1',
      'phptype' => 'integer',
      'attributes' => 'unsigned',
      'null' => false,
      'default' => 0,
    ),
    'properties' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'array',
      'null' => true,
    ),
    'pos' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
      'index' => 'index',
    ),
  ),
  'composites' => 
  array (
    'TranslatedTemplateVarResources' => 
    array (
      'class' => 'mmlTemplateVarResource',
      'local' => 'id',
      'foreign' => 'langid',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
  ),
);
