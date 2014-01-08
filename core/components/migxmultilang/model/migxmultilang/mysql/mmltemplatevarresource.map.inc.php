<?php
$xpdo_meta_map['mmlTemplateVarResource']= array (
  'package' => 'migxmultilang',
  'version' => NULL,
  'table' => 'mml_tmplvar_contentvalues',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'langid' => 0,
    'tmplvarid' => 0,
    'contentid' => 0,
    'value' => NULL,
  ),
  'fieldMeta' => 
  array (
    'langid' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
      'index' => 'index',
    ),
    'tmplvarid' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
      'index' => 'index',
    ),
    'contentid' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
      'index' => 'index',
    ),
    'value' => 
    array (
      'dbtype' => 'mediumtext',
      'phptype' => 'string',
      'null' => false,
    ),
  ),
  'indexes' => 
  array (
    'tmplvarid' => 
    array (
      'alias' => 'tmplvarid',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'tmplvarid' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'contentid' => 
    array (
      'alias' => 'contentid',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'contentid' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'tv_cnt' => 
    array (
      'alias' => 'tv_cnt',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'tmplvarid' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
        'contentid' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
  ),
  'aggregates' => 
  array (
    'TemplateVar' => 
    array (
      'class' => 'modTemplateVar',
      'local' => 'tmplvarid',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
    'Resource' => 
    array (
      'class' => 'modResource',
      'local' => 'contentid',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
    'Language' => 
    array (
      'class' => 'mmlLang',
      'local' => 'langid',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ),
);
