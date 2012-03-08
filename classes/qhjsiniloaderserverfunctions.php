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
    public static function configload( )
    {
        $ini = eZINI::instance( 'qhjsiniloader.ini' );
        $qhJSINILoaderConfig = array( );

        /*
         * Loading INI settings
         */
        $availableSets = $ini->variable( 'QHJSINILoaderSettings', 'AvailableSets' );
        foreach( $availableSets as $availableSet )
        {
            // eZLog::write( 'Processing set: ' . $availableSet );
            $availableSetParameters = array( );

            $availableINIConfigs = $ini->variable( $availableSet, 'AvailableINIConfigs' );
            foreach( $availableINIConfigs as $availableINIConfig )
            {
                // eZLog::write( 'availableINIConfig: '.$availableINIConfig );
                list( $iniFile, $iniBloc, $blocVariables ) = explode( '/', $availableINIConfig );

                $availableINI = eZINI::instance( $iniFile );

                if( !empty( $iniBloc ) )
                {
                    if( !empty( $blocVariables ) )
                    {
                        $availableVariables = explode( ',', $blocVariables );
                        foreach( $availableVariables as $availableVariable )
                        {
                            $availableSetParameters[$iniFile][$availableVariable] = $availableINI->variable( $iniBloc, $availableVariable );
                            // eZLog::write( "INI Variable {$iniBloc}/{$availableVariable}: ". var_export($availableSetParameters[$availableVariable], true) );
                        }
                    }
                    else
                    // Loads all variables from a bloc
                    {
                        $availableSetParameters[$iniFile] = $availableINI->group( $iniBloc );
                        // eZLog::write( "Bloc {$iniBloc}: ". var_export($availableSetParameters, true) );
                    }
                }
                else
                // Loads the entire INI file
                {
                    $availableBlocs = $availableINI->groups();
                    // eZLog::write('***** '.var_export($availableBlocs, true));
                    foreach( $availableBlocs as $blocName => $blocVariables )
                    {
                        $availableSetParameters[$iniFile][$blocName] = $availableINI->group( $blocName );
                        // eZLog::write( "entire bloc {$availableBloc}: ". var_export($availableSetParameters[$blocName], true));
                    }
                    // eZLog::write('entire file: '. var_export($availableSetParameters, true));
                }
            }

            $qhJSINILoaderConfig['loaded_configs'] = $availableSetParameters;

            $usei18n = $ini->variable( $availableSet, 'Usei18n' );
            if( $usei18n == 'enabled' )
            {
                $tpl = eZTemplate::factory( );
                $i18nJSON = str_replace( "\n", '', $tpl->fetch( 'design:qhjsiniloader/' . $availableSet . '_i18n.tpl' ) );
                $qhJSINILoaderConfig[$availableSet]['i18n'] = json_decode( $i18nJSON );
            }

            // eZLog::write('result: '. var_export($qhJSINILoaderConfig, true));
        }

        /*
         * Encode and return in a result in JSON format
         */
        return json_encode( $qhJSINILoaderConfig );
    }

}
?>