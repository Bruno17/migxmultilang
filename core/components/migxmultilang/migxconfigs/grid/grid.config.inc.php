<?php

$action = $this->modx->getOption('action', $_POST, '');
$cfgs = $this->modx->getOption('configs', $_POST, '');

$gridactionbuttons['import_config']['text'] = "'[[%mml.import_example_config]]'";
$gridactionbuttons['import_config']['handler'] = 'this.import_config';
$gridactionbuttons['import_config']['scope'] = 'this';

$gridfunctions['this.import_config'] = "
import_config: function() {
            var package = 'migxmultilang';
            var url = this.config.url;
            var configs = this.config.configs;
            MODx.Ajax.request({
                url: url,
                params: {
                    action: 'mgr/migxdb/process',
                    processaction: 'importconfigs',
                    package: package,
                    configs: configs
                },
                listeners: {
                    'success': {
                        fn: this.refresh,
                        scope: this
                    }
                }
            });
}	
";


$gridfunctions['this.editRaw'] = "
    editRaw: function(btn,e) {
      this.loadWin(btn,e,'u','raw');
    }  
    ";


$gridcontextmenus['editraw']['code'] = "
        m.push({
            className : 'editraw',
            text: '[[%migx.edit_raw]]'
            ,handler: 'this.editRaw'
        });
    ";
$gridcontextmenus['editraw']['handler'] = 'this.editRaw';


$gridcontextmenus['createTVs']['code'] = "
        m.push({
            className : 'createtvs',
            text: '[[%mml.create_tvs]]'
            ,handler: 'this.createTVs'
        });
    ";
$gridcontextmenus['createTVs']['handler'] = 'this.createTVs';

$gridfunctions['this.createTVs'] = "
    createTVs: function() {
            var object_id = this.menu.record.id;
            var package = 'migxmultilang';
            var url = this.config.url;
            var configs = this.config.configs;
            MODx.Ajax.request({
                url: url,
                params: {
                    action: 'mgr/migxdb/process',
                    processaction: 'createtvs',
                    package: package,
                    configs: configs,
                    object_id: object_id
                },
                listeners: {
                    'success': {
                        fn: this.refresh,
                        scope: this
                    }
                }
            });
}	
";