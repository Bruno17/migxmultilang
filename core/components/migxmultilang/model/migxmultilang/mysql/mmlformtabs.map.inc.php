<?php
$xpdo_meta_map['mmlFormtabs']= array (
  'package' => 'migxmultilang',
  'version' => NULL,
  'table' => 'mml_formtabs',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'name' => '',
    'formtabs' => NULL,
    'createdon' => NULL,
    'properties' => NULL,
    'default' => 0,
  ),
  'fieldMeta' => 
  array (
    'name' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '150',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
      'index' => 'index',
    ),
    'formtabs' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
      'null' => true,
    ),
    'createdon' => 
    array (
      'dbtype' => 'datetime',
      'phptype' => 'datetime',
      'null' => true,
    ),
    'properties' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'array',
      'null' => true,
    ),
    'default' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '1',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
      'index' => 'index',
    ),
  ),
  'composites' => 
  array (
    'Templates' => 
    array (
      'class' => 'mmlFormtabsTemplate',
      'local' => 'id',
      'foreign' => 'formtabsid',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
  ),
);
