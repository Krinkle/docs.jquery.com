<?php

/**
 * MediaWiki extension providing the JqDocs skin
 * for docs.jquery.com
 */

$wgValidSkinNames['jqdocs'] = 'JqDocs';

$wgAutoloadClasses['SkinJqDocs'] = __DIR__ . '/JqDocsSkin.class.php';


// This is loaded for all pages and doesn't depend on javascript
// (loaded through <link>)
$wgResourceModules['skins.jqdocs.layout'] = array(
	'localBasePath' => __DIR__,
	'remoteExtPath' => 'JqDocsSkin',

	'styles' => 'modules/skins.jqdocs.layout.css',

);

// Dynamic scripts (and dependencies) are loaded
// asynchronous through AJAX
$wgResourceModules['skins.jqdocs.enhanced'] = array(
	'localBasePath' => __DIR__,
	'remoteExtPath' => 'JqDocsSkin',

	'scripts' => 'modules/skins.jqdocs.enhanced.js',
	'dependencies' => array(
		'jquery.ui.tabs',
	),
);
