var QHJSINILoader = {
    initialized: false,
    config: {},
    
    call: function(config_set, callback) {
        var configToReturn = {
            from_loader: QHJSINILoader.config[config_set],
            from_ini: QHJSINILoader.config['loaded_configs']
        };

        callback(configToReturn);
    },

    init: function(config_set, callback) {
        if( QHJSINILoader.initialized ) {
            QHJSINILoader.call(config_set, callback);
        } else {
            // Load the config from qhautosave.ini via ezjscore in JSON format
            $.ez( 'qhjsiniloader::configload', '', function( ezp_data ) {

                // If any error
                if (ezp_data.error_text) {
                    console.log( 'ezjscore ajax error in qhjsiniloader::configload' );
                } else {
                    QHJSINILoader.initialized = true;
                    QHJSINILoader.config = jQuery.parseJSON(ezp_data.content);
                }
                
                QHJSINILoader.call(config_set, callback);
            });

        }        
    }
}
