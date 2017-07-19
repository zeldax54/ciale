/**
 * @license Copyright (c) 2003-2017, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
	config.extraPlugins = 'lineheight';
	config.line_height="0.01px;0.1px;0.2px;0.3px;0.4px;0.5px;1px;1.1px;1.2px;1.3px;1.4px;1.5px" ;
    	config.extraPlugins = 'font';
		
		config.contentsCss = '/cialesymf/web/bundles/gema/css/font.css';
        config.font_names = 'Fago-Bold/Fago-Bold;' + config.font_names;
        config.font_names = 'Fago-normal/Fago-normal;' + config.font_names;
};
