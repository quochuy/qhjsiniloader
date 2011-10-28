<?php
//
// Definition of qhJSINILoaderServerFunctions class
//
// Created on: <18-Oct-2011 00:00:00>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: QH AutoSave
// SOFTWARE RELEASE: 1.0
// COPYRIGHT NOTICE: Copyright (C) 2011-2012 NGUYEN DINH Quoc-Huy
// SOFTWARE LICENSE: GNU General Public License v2.0
// NOTICE: >
//   This program is free software; you can redistribute it and/or
//   modify it under the terms of version 2.0  of the GNU General
//   Public License as published by the Free Software Foundation.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of version 2.0 of the GNU General
//   Public License along with this program; if not, write to the Free
//   Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
//   MA 02110-1301, USA.
//
//
// ## END COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
//

/*
 * ezjscServerFunctions for qhjsiniloader
 */
class qhJSINILoaderServerFunctions extends ezjscServerFunctions
{
    /**
     * Load INI configuration for the autosave feature
     *
     * @return JSON 
     */
    public static function configload()
    {
        $ini = eZINI::instance( 'qhjsiniloader.ini' );
        $qhJSINILoaderConfig = array();

	    /*
	     * Loading INI settings
	     */
        $availableLoaders = $ini->variable( 'QHJSINILoaderSettings', 'AvailableLoaders' );
        foreach( $availableLoaders as $loader )
        {
            $loaderParameters = $ini->variable( $loader, 'Parameters' );
            $useLocales = $ini->variable( $loader, 'UseLocales' );

            if( $useLocales == 'enabled' )
            {
                $tpl = eZTemplate::factory();
                $loaderParameters['locales'] = $tpl->fetch( 'design:qhjsiniloader/'. $loader .'_locales.tpl' );
                eZLog::write( 'design:qhjsiniloader/'. $loader .'_locales.tpl' );
            }

            $qhJSINILoaderConfig[$loader] = $loaderParameters;
        }

	    /*
	     * Encode and return in a result in JSON format
	     */
        return json_encode( $qhJSINILoaderConfig );
    }

}

?>
