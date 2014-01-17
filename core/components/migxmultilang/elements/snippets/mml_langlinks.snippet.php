//[[!mml_LangLinks]]

$outputSeparator = $modx->getOption('outputSeparator', $scriptProperties, '');
$tpl = $modx->getOption('tpl', $scriptProperties, 'mml_LinkTpl');

$wrapper = '<ul class="mml_links">[[+output]]</ul>';
$wrapper = $modx->getOption('wrapper', $scriptProperties, $wrapper);

$cultureKey = $modx->getOption('cultureKey');
$site_url = $modx->getOption('site_url');
$furls = $modx->getOption('friendly_urls');

$packageName = 'migxmultilang';

$packagepath = $modx->getOption('core_path') . 'components/' . $packageName . '/';
$modelpath = $packagepath . 'model/';
if (is_dir($modelpath)) {
    $modx->addPackage($packageName, $modelpath, $prefix);
}
$classname = 'mmlLang';

$c = $modx->newQuery($classname);
$output = array();

if ($collection = $modx->getCollection($classname, $c)) {

    if ($furls == '1') {
        $url = $modx->makeUrl($modx->resource->get('id'));
        $base_url = $modx->getOption('base_url');
        $url = $modx->makeUrl($modx->resource->get('id'));
        if ($url == $base_url) {
            $url = '';
        }
        $site_url = str_replace('/' . $cultureKey . '/', '', $site_url);
    }


    foreach ($collection as $object) {
        $lang_key = $object->get('lang_key');
        $class = ($lang_key == $cultureKey) ? 'class="active"' : '';
        $row = $object->toArray();

        if ($furls == '1') {
            $lang_key = !empty($lang_key) ? $lang_key . '/' : '';
            $row['link'] = rtrim($site_url, '/') . '/' . $lang_key . $url;
        } else {
            $row['link'] = $modx->makeUrl($modx->resource->get('id'), null, 'cultureKey=' . $lang_key);
        }


        $row['class'] = $class;
        $output[] = $modx->getChunk($tpl, $row);
    }
}

$output = implode($outputSeparator, $output);
$output = str_replace('[[+output]]', $output, $wrapper);

return $output;