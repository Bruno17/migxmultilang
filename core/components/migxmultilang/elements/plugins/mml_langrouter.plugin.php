<?php
if ($modx->context->get('key') != "mgr") {
    
    if ($id = $modx->findResource($_REQUEST['q'])) {
        $modx->sendForward($id);
    }

    # Serve site_start when no resource is requested
    if (empty($_REQUEST['q']) || $_REQUEST['q'] == '_mml_home') {
        $modx->sendForward($modx->getOption('site_start'));
    }

}