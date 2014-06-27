<?php
/**
 * snippets transport file for migxMultiLang extra
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
/* @var xPDOObject[] $snippets */


$snippets = array();

$snippets[1] = $modx->newObject('modSnippet');
$snippets[1]->fromArray(array(
    'id' => '1',
    'property_preprocess' => '',
    'name' => 'mmlField',
    'description' => '',
    'properties' => '',
), '', true, true);
$snippets[1]->setContent(file_get_contents($sources['source_core'] . '/elements/snippets/mmlfield.snippet.php'));

$snippets[2] = $modx->newObject('modSnippet');
$snippets[2]->fromArray(array(
    'id' => '2',
    'property_preprocess' => '',
    'name' => 'mml_LangLinks',
    'description' => '',
    'properties' => '',
), '', true, true);
$snippets[2]->setContent(file_get_contents($sources['source_core'] . '/elements/snippets/mml_langlinks.snippet.php'));

$snippets[3] = $modx->newObject('modSnippet');
$snippets[3]->fromArray(array(
    'id' => '3',
    'property_preprocess' => '',
    'name' => 'mmlTranslatePdoToolsRow',
    'description' => '',
    'properties' => array(),
), '', true, true);
$snippets[3]->setContent(file_get_contents($sources['source_core'] . '/elements/snippets/mmltranslatepdotoolsrow.snippet.php'));

$snippets[4] = $modx->newObject('modSnippet');
$snippets[4]->fromArray(array(
    'id' => '4',
    'property_preprocess' => '',
    'name' => 'mmlCache',
    'description' => '<b>1.1.0-pl</b> A generic caching snippet for caching the output of any MODx Element using a configurable cache handler.',
), '', true, true);
$snippets[4]->setContent(file_get_contents($sources['source_core'] . '/elements/snippets/mmlcache.snippet.php'));


$properties = include $sources['data'].'properties/properties.mmlcache.snippet.php';
$snippets[4]->setProperties($properties);
unset($properties);

$snippets[5] = $modx->newObject('modSnippet');
$snippets[5]->fromArray(array(
    'id' => '5',
    'property_preprocess' => '',
    'name' => 'mmlGetTemplateTVs',
    'description' => '',
    'properties' => '',
), '', true, true);
$snippets[5]->setContent(file_get_contents($sources['source_core'] . '/elements/snippets/mmlgettemplatetvs.snippet.php'));

$snippets[6] = $modx->newObject('modSnippet');
$snippets[6]->fromArray(array(
    'id' => '6',
    'property_preprocess' => '',
    'name' => 'mmlLangLinks',
    'description' => '',
    'properties' => '',
), '', true, true);
$snippets[6]->setContent(file_get_contents($sources['source_core'] . '/elements/snippets/mmllanglinks.snippet.php'));

return $snippets;
