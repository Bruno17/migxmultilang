<?php
/*
does only work after this commits/pulls: 
https://github.com/bezumkin/pdoTools/commit/1f4ae742a27c69b5e299d7f1ffa75b2cd367c616
https://github.com/bezumkin/pdoTools/pull/37
*/

$field = $modx->getOption('field',$scriptProperties,'');

$scriptProperties['field'] = 'mml.' . $field;
$scriptProperties['default'] = $modx->getOption('default',$scriptProperties, $field);

return $modx->runSnippet('pdoField',$scriptProperties);