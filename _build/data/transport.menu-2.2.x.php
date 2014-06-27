<?php
/**
 * menus transport file for migxMultiLang extra
 *
 * Copyright 2014 by Bruno Perner b.perner@gmx.de
 * Created on 01-10-2014
 *
 * @package migxmultilang
 * @subpackage build
 */

if (! function_exists('stripPhpTags')) {
    function stripPhpTags($filename) {
        $o = file_get_contents($filename);
        $o = str_replace('<' . '?' . 'php', '', $o);
        $o = str_replace('?>', '', $o);
        $o = trim($o);
        return $o;
    }
}
/* @var $modx modX */
/* @var $sources array */
/* @var xPDOObject[] $menus */

$action = $modx->newObject('modAction');
$action->fromArray( array (
  'id' => 1,
  'namespace' => 'migx',
  'controller' => 'index',
  'haslayout' => '1',
  'lang_topics' => 'example:default',
  'assets' => '',
), '', true, true);

$menus[1] = $modx->newObject('modMenu');
$menus[1]->fromArray( array (
  'text' => 'migxMultiLang',
  'parent' => 'components',
  'description' => '',
  'icon' => '',
  'menuindex' => '',
  'params' => '&configs=mml_languages:migxmultilang||mml_translationslist:migxmultilang||mml_formtabs:migxmultilang',
  'handler' => '',
  'permissions' => '',
), '', true, true);
$menus[1]->addOne($action);

return $menus;
