<?php
$xpdo_meta_map['mmlResource']= array (
  'package' => 'migxmultilang',
  'version' => NULL,
  'table' => 'site_content',
  'extends' => 'modResource',
  'fields' => 
  array (
  ),
  'fieldMeta' => 
  array (
  ),
  'composites' => 
  array (
    'TranslatedTemplateVarResources' => 
    array (
      'class' => 'mmlTemplateVarResource',
      'local' => 'id',
      'foreign' => 'contentid',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
  ),
);
