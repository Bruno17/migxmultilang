<?php
/**
 * chunks transport file for migxMultiLang extra
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
/* @var xPDOObject[] $chunks */


$chunks = array();

$chunks[1] = $modx->newObject('modChunk');
$chunks[1]->fromArray(array(
    'id' => '1',
    'property_preprocess' => '',
    'name' => 'mml_LinkTpl',
    'description' => '',
    'properties' => '',
), '', true, true);
$chunks[1]->setContent(file_get_contents($sources['source_core'] . '/elements/chunks/mml_linktpl.chunk.html'));

$chunks[2] = $modx->newObject('modChunk');
$chunks[2]->fromArray(array(
    'id' => '2',
    'property_preprocess' => '',
    'name' => 'mml_MenuRowTpl',
    'description' => '',
    'properties' => '',
), '', true, true);
$chunks[2]->setContent(file_get_contents($sources['source_core'] . '/elements/chunks/mml_menurowtpl.chunk.html'));

$chunks[3] = $modx->newObject('modChunk');
$chunks[3]->fromArray(array(
    'id' => '3',
    'property_preprocess' => '',
    'name' => 'mml_resourceTemplate',
    'description' => '',
    'properties' => '',
), '', true, true);
$chunks[3]->setContent(file_get_contents($sources['source_core'] . '/elements/chunks/mml_resourcetemplate.chunk.html'));

return $chunks;
