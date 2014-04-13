/**
 * @author Bruno Perner
 * @copyright 2014
 */

/**
 *  LangRouter
 * ==========
 *
 * This plugin is meant to be used with Babel extra for MODX Revolution. It
 * takes care of switching contexts, which hold translations, depending on URL
 * requested by client. LangRouter works with so called subfolder based setup,
 * in which many languages are served under a single domain but are
 * differentiated by a virtual subfolder indicating the language, eg.
 * mydomain.com/pl/.
 *
 * The routing work as follows:
 * - if URI contains cultureKey, which is defined in Babel configuration, then
 * the matching context is served
 * - if URI doesn't contain cultureKey (or one not defined in Babel
 * configuration) AND at least one of the client's accepted languages is
 * defined in Babel configuration, then the matching context is served
 * - otherwise the default context is served
 *
 * LangRouter works out-of-the-box and doesn't require any changes to URL
 * rewrite rules in the webserver configuration. All routing is handled
 * internally by MODX. This greatly simplifies the setup and provides
 * portability. LangRouter was tested with Apache and Lighttpd.
 *
 * Setup:
 * 1. Prepare your contexts as you normally would for Babel.
 * 2. For each context set `base_url` to `/`.
 * 3. For each context set `site_url` to
 * `{server_protocol}://{http_host}{base_url}{cultureKey}/`
 * 4. Add new system setting `babel.contextDefault` and set it to the default
 * context, which should be served when no language is specified in
 * request, eg. `pl`.
 * 5. Include static files from the assets folder with
 * `[[++assets_url]]path/to/static_file`.
 * 6. In template header use `<base href="[[++site_url]]" />`.
 * 7. Use default URL generation scheme in MODX (ie. relative).
 *
 * This code is shared AS IS. Use at your own risk.
 */

if ($modx->context->get('key') != "mgr") {

    /*
    * Debugs request handling
    */
    if (!function_exists('logRequest')) {
        function logRequest($message = 'Request')
        {
            global $modx;
            $modx->log(modX::LOG_LEVEL_ERROR, $message . ':' . "\n REQUEST_URI: " . $_SERVER['REQUEST_URI'] . "\n REDIRECT_URI: " . $_SERVER['REDIRECT_URI'] . "\n QUERY_STRING: " . $_SERVER['QUERY_STRING'] . "\n q: " . $_REQUEST['q'] . "\n Context: " . $modx->context->get('key') . "\n Site start: " . $modx->context->getOption('site_start'));
        }
    }


    /*
    * Dumps variables to MODX log
    */
    if (!function_exists('dump')) {
        function dump($var)
        {
            ob_start();
            var_dump($var);
            return ob_get_clean();
        }
    }


    /*
    * Detects client language preferences and returns associative array sorted
    * by importance (q factor)
    */
    if (!function_exists('clientLangDetect')) {
        function clientLangDetect()
        {
            $langs = array();

            if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
                # break up string into pieces (languages and q factors)
                preg_match_all('/([a-z]{1,8}(-[a-z]{1,8})?)\s*(;\s*q\s*=\s*(1|0\.[0-9]+))?/i', $_SERVER['HTTP_ACCEPT_LANGUAGE'], $lang_parse);

                if (count($lang_parse[1])) {
                    # create a list like "en" => 0.8
                    $langs = array_combine($lang_parse[1], $lang_parse[4]);

                    # set default to 1 for any without q factor
                    foreach ($langs as $lang => $val) {
                        if ($val === '')
                            $langs[$lang] = 1;
                    }

                    # sort list based on value
                    arsort($langs, SORT_NUMERIC);
                    return $langs;
                }
            }
        }
    }
    $furls = $modx->getOption('friendly_urls');
    switch ($modx->event->name) {

        case 'OnInitCulture':


            if (isset($_REQUEST['cultureKey'])) {

            } elseif ($furls != '1' && isset($_SESSION['cultureKey'])) {

                $_REQUEST['cultureKey'] = $_SESSION['cultureKey'];

            } else {
                #logRequest('Unhandled request');

                # Get languages and their cultureKeys

                $languages = array();
                $packageName = 'migxmultilang';

                $packagepath = $modx->getOption('core_path') . 'components/' . $packageName . '/';
                $modelpath = $packagepath . 'model/';
                if (is_dir($modelpath)) {
                    $modx->addPackage($packageName, $modelpath, $prefix);
                }
                $classname = 'mmlLang';

                $c = $modx->newQuery($classname);

                if ($collection = $modx->getCollection($classname, $c)) {
                    foreach ($collection as $object) {
                        $lang_key = $object->get('lang_key');
                        $row = $object->toArray();
                        $languages[$lang_key] = $row;
                    }
                }

                # Determine language from request
                $reqCultureKeyIdx = strpos($_REQUEST['q'], '/');
                $reqCultureKey = substr($_REQUEST['q'], 0, $reqCultureKeyIdx);

                # Serve the proper context and language
                if (array_key_exists(strtolower($reqCultureKey), array_change_key_case($languages))) {
                    # Remove cultureKey from request

                    $_REQUEST['q'] = substr($_REQUEST['q'], $reqCultureKeyIdx + 1);
                    $_REQUEST['cultureKey'] = $reqCultureKey;

                    if ($_REQUEST['q']) {

                    } else {
                        // $_REQUEST['q'] shouldn't be empty
                        $_REQUEST['q'] = '_mml_home';
                    }

                    # logRequest('Culture key found in URI');
                } else {
                    $clientCultureKey = array_flip(array_intersect_key(clientLangDetect(), $languages));
                    if ($clientCultureKey) {
                        $_REQUEST['cultureKey'] = current($clientCultureKey);
                    } else {
                        $_REQUEST['cultureKey'] = trim($modx->getOption('cultureKey'));
                    }
                }
            }

            $_SESSION['mml_settings'] = array();
            if (!empty($_REQUEST['cultureKey'])) {
                $language = $languages[$_REQUEST['cultureKey']];
                $_SESSION['cultureKey'] = $_REQUEST['cultureKey'];
                $_SESSION['mml_settings']['cultureKey'] = $_REQUEST['cultureKey'];
                $_SESSION['mml_settings']['lang_dir'] = $language['lang_dir'];
            }

            /*
            $modx->setOption('original_cultureKey', $modx->getOption('cultureKey'));
            $modx->setOption('original_site_url', $modx->getOption('site_url'));
            if (!empty($_SESSION['cultureKey'])) {
            $modx->setOption('cultureKey', $_SESSION['cultureKey']);
            if ($furls == '1') {
            $modx->setOption('site_url', $modx->getOption('site_url') . $_SESSION['cultureKey'] . '/');
            }
            }
            */

            break;

        case 'OnLoadWebDocument':

            $do_translate = $modx->getOption('mml.do_translate', null, 1);

            if (!empty($do_translate)) {
                if (isset($_SESSION['mml_settings']) && !empty($_SESSION['mml_settings']['cultureKey'])) {
                    $mml_settings = $_SESSION['mml_settings'];
                    $mml_settings['original_cultureKey'] = $modx->getOption('cultureKey');
                    $mml_settings['original_site_url'] = $modx->getOption('site_url');
                    if ($furls == '1') {
                        $mml_settings['site_url'] = $modx->getOption('site_url') . $_SESSION['mml_settings']['cultureKey'] . '/';
                    }
                    $modx->setPlaceholders($mml_settings, '+');
                    foreach ($mml_settings as $key => $value) {
                        $modx->setOption($key, $value);
                    }

                }
            }

            break;

    }
}