<?php

$langloaded = $this->modx->getOption('langloaded', $_REQUEST, false);

if (!$langloaded) {
    $_REQUEST['langloaded'] = true;
    $this->modx->lexicon->load('migxmultilang:default');
    $this->loadLang('mml');
}