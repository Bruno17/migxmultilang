$corePath = $modx->getOption('mml.core_path',null,$modx->getOption('core_path').'components/migxmultilang/');
$assetsUrl = $modx->getOption('mml.assets_url', null, $modx->getOption('assets_url') . 'components/migxmultilang/');
switch ($modx->event->name) {
    case 'OnTVInputRenderList':
        $modx->event->output($corePath.'elements/tv/input/');
        break;
    case 'OnTVInputPropertiesList':
        $modx->event->output($corePath.'elements/tv/inputoptions/');
        break;

        case 'OnDocFormPrerender':
        $modx->controller->addCss($assetsUrl.'css/mgr.css');
        break; 
}
return;