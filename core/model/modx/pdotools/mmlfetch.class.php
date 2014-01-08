<?php
require MODX_CORE_PATH . 'components/pdotools/model/pdotools/pdofetch.class.php';

class mmlFetch extends pdoFetch {

    /**
     * @param modX $modx
     * @param array $config
     */
    public function __construct(modX & $modx, $config = array()) {

        return parent::__construct($modx, $config);
    }

    /**
     * Add selection of template variables to query
     */
    public function addTVs() {
        $time = microtime(true);

        $lang_id = 0;
        $cultureKey = $this->modx->getOption('cultureKey', $this->config, $this->modx->getOption('cultureKey'));
        $main_lang = $this->modx->getOption('mml.main_lang');

        if ($cultureKey != $main_lang && $langObject = $this->modx->getObject('mmlLang', array('lang_key' => $cultureKey))) {
           $lang_id = $langObject->get('id');
        }

        $includeTVs = $this->config['includeTVs'];
        $tvPrefix = $this->config['tvPrefix'];

        if (!empty($this->config['includeTVList']) && (empty($includeTVs) || is_numeric($includeTVs))) {
            $this->config['includeTVs'] = $includeTVs = $this->config['includeTVList'];
        }
        if (!empty($this->config['sortbyTV'])) {
            $includeTVs .= empty($includeTVs) ? $this->config['sortbyTV'] : ',' . $this->config['sortbyTV'];
        }

        if (!empty($includeTVs)) {
            $subclass = preg_grep('/^' . $this->config['class'] . '/i', $this->modx->classMap['modResource']);
            if (!preg_match('/^modResource$/i', $this->config['class']) && !count($subclass)) {
                $this->modx->log(modX::LOG_LEVEL_ERROR, '[pdoTools] Instantiated a derived class "' . $this->config['class'] . '" that is not a subclass of the "modResource", so tvs not joining.');
            } else {
                $tvs = array_map('trim', explode(',', $includeTVs));
                $tvs = array_unique($tvs);
                if (!empty($tvs)) {
                    $q = $this->modx->newQuery('modTemplateVar', array('name:IN' => $tvs));
                    $q->select('id,name,type,default_text');
                    $tstart = microtime(true);
                    if ($q->prepare() && $q->stmt->execute()) {
                        $this->modx->queryTime += microtime(true) - $tstart;
                        $this->modx->executedQueries++;
                        $tvs = array();
                        while ($tv = $q->stmt->fetch(PDO::FETCH_ASSOC)) {
                            $name = strtolower($tv['name']);
                            $alias = 'TV' . $name;
                            $this->config['tvsJoin'][$name] = array(
                                'class' => 'modTemplateVarResource',
                                'alias' => $alias,
                                'on' => '`TV' . $name . '`.`contentid` = `' . $this->config['class'] . '`.`id` AND `TV' . $name . '`.`tmplvarid` = ' . $tv['id'],
                                'tv' => $tv);
                            $this->config['tvsSelect'][$alias] = array('`' . $tvPrefix . $tv['name'] . '`' => 'IFNULL(`' . $alias . '`.`value`, ' . $this->modx->quote($tv['default_text']) . ')');

                            if (!empty($lang_id)) {
                                //add Translations
                                $name = 'MML' . strtolower($tv['name']);
                                $alias = 'TV' . $name;
                                $this->config['tvsJoin'][$name] = array(
                                    'class' => 'mmlTemplateVarResource',
                                    'alias' => $alias,
                                    'on' => '`TV' . $name . '`.`contentid` = `' . $this->config['class'] . '`.`id` AND `TV' . $name . '`.`tmplvarid` = ' . $tv['id'] . ' AND `TV' . $name . '`.`langid` = ' . $lang_id,
                                    'tv' => $tv);
                                $this->config['tvsSelect'][$alias] = array('`mml.' . $tvPrefix . $tv['name'] . '`' => 'IFNULL(`' . $alias . '`.`value`, ' . $this->modx->quote($tv['default_text']) . ')');
                            }


                            $tvs[] = $tv['name'];
                        }

                        $this->addTime('Included list of tvs: <b>' . implode(', ', $tvs) . '</b>', microtime(true) - $time);
                    }
                }
            }
        }
    }

}