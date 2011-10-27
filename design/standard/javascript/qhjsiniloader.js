var QHJSINILoader = new function() {
    this.initialized = false;
    this.config = {};

    this.init = function( loader_name, callback ) {
        if( this.initialized ) {
            callback( this.config[loader_name] );
        } else {
            // Load the config from qhautosave.ini via ezjscore in JSON format
            $.ez( 'qhjsiniloader::configload', '', function( ezp_data ) {

                // If any error
                if ( ezp_data.error_text ) {
                    console.log( 'ezjscore ajax error in qhjsiniloader::configload' );
                } else {
                    this.initialized = true;
                    this.config = jQuery.parseJSON( ezp_data.content );
                    callback( this.config[loader_name] );
                }
            });        
        }
    }
}
