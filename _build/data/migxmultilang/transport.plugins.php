<?php
/**
 * plugins transport file for migxMultiLang extra
 *
 * Copyright 2014 by Bruno Perner b.perner@gmx.de
 * Created on 04-15-2014
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
/* @var xPDOObject[] $plugins */


$plugins = array();

$plugins[1] = $modx->newObject('modPlugin');
$plugins[1]->fromArray(array(
    'id' => '1',
    'property_preprocess' => '',
    'name' => 'mml_initCulture',
    'description' => '',
    'properties' => array(),
    'disabled' => '',
), '', true, true);
$plugins[1]->setContent(file_get_contents($sources['source_core'] . '/elements/plugins/mml_initculture.plugin.php'));

$plugins[2] = $modx->newObject('modPlugin');
$plugins[2]->fromArray(array(
    'id' => '2',
    'property_preprocess' => '',
    'name' => 'mml_langRouter',
    'description' => '',
    'properties' => array(),
    'disabled' => '',
), '', true, true);
$plugins[2]->setContent(file_get_contents($sources['source_core'] . '/elements/plugins/mml_langrouter.plugin.php'));

$plugins[3] = $modx->newObject('modPlugin');
$plugins[3]->fromArray(array(
    'id' => '3',
    'property_preprocess' => '',
    'name' => 'mml_checkboxes',
    'description' => '',
    'properties' => array(),
    'disabled' => '',
), '', true, true);
$plugins[3]->setContent(file_get_contents($sources['source_core'] . '/elements/plugins/mml_checkboxes.plugin.php'));

$plugins[4] = $modx->newObject('modPlugin');
$plugins[4]->fromArray(array(
    'id' => '4',
    'property_preprocess' => '',
    'name' => 'mml_syncTranslations',
    'description' => '',
    'properties' => array(),
    'disabled' => '',
), '', true, true);
$plugins[4]->setContent(file_get_contents($sources['source_core'] . '/elements/plugins/mml_synctranslations.plugin.php'));

return $plugins;
