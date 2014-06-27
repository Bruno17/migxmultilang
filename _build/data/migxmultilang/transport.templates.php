<?php
/**
 * templates transport file for migxMultiLang extra
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
/* @var xPDOObject[] $templates */


$templates = array();

$templates[1] = $modx->newObject('modTemplate');
$templates[1]->fromArray(array(
    'id' => '1',
    'property_preprocess' => '',
    'templatename' => 'mml_baseTemplate',
    'description' => '',
    'icon' => '',
    'template_type' => '0',
    'properties' => array(),
), '', true, true);
$templates[1]->setContent(file_get_contents($sources['source_core'] . '/elements/templates/mml_basetemplate.template.html'));

return $templates;
