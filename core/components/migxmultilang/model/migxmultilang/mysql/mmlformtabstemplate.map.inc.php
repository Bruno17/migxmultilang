<?php
$xpdo_meta_map['mmlFormtabsTemplate']= array (
  'package' => 'migxmultilang',
  'version' => NULL,
  'table' => 'mml_formtabstemplates',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'templateid' => 0,
    'formtabsid' => 0,
  ),
  'fieldMeta' => 
  array (
    'templateid' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
      'index' => 'index',
    ),
    'formtabsid' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
      'index' => 'index',
    ),
  ),
  'aggregates' => 
  array (
    'template' => 
    array (
      'class' => 'modTemplate',
      'local' => 'templateid',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
    'Formtabs' => 
    array (
      'class' => 'mmlFormtabs',
      'local' => 'formtabsid',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ),
);
