<?php

$modx = &$object->xpdo;
if ($object->xpdo) {
    switch ($options[xPDOTransport::PACKAGE_ACTION]) {
        case xPDOTransport::ACTION_INSTALL:
        case xPDOTransport::ACTION_UPGRADE:

            $menu_placement = $modx->getOption('menu_placement', $options, 'components');
            $modx->getVersionData();


            $menues = $modx->fromJson('[{"MIGX_id":"1","text":"migxMultiLang","parent":"","description":"","icon":"","menuindex":"","params":"&configs=mml_languages:migxmultilang||mml_translationslist:migxmultilang||mml_formtabs:migxmultilang","handler":"","permissions":"","action.id":"","action.namespace":"","action.controller":"","action.haslayout":"0","action.lang_topics":"","action.assets":""}]');

            if (is_array($menues) && count($menues) > 0) {
                $modx->log(modX::LOG_LEVEL_INFO, 'Prepare menu for MODX Revolution 2.3.x');

                foreach ($menues as $props) {
                    $text = !empty($props['text']) ? $props['text'] : '';
                    if ($object = $modx->getObject('modMenu', array('text' => $text))) {

                        if (version_compare($modx->version['full_version'], '2.3', '>=')) {
                            /*
                            $parent = $object->get('parent');
                            if (empty($parent)) {
                            $object->set('parent', 'topnav');
                            }
                            */
                            $object->set('parent', $menu_placement);
                            $object->set('action', 'index');
                            $object->set('namespace', !empty($props['action.namespace']) ? $props['action.namespace'] : 'migx');
                            $object->save();

                            if ($action = $object->getOne('Action')) {
                                //$action->remove();
                            }
                        } else {
                            $menu_placement = $menu_placement=='topnav' ? '' : $menu_placement;    
                            $object->set('parent', $menu_placement);
                            $object->save();
                        }
                    }
                }
            }


            break;
    }

}
return true;
