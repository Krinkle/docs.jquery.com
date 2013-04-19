<?php
/**
 * Further documentation for configuration settings:
 * https://www.mediawiki.org/wiki/Manual:Configuration_settings
 */

// Protect against web entry
if ( !defined( 'MEDIAWIKI' ) ) {
	exit;
}

/************************************************************************
 * @section Basic settings
 *
 * @{
 */

## Database settings

$wgDBtype = 'mysql';
$wgDBserver = 'localhost';
#$wgDBname = false; // Set later
$wgDBprefix = '';
$wgDBTableOptions = 'ENGINE=InnoDB, DEFAULT CHARSET=binary';

## General settings

$wgSitename = 'jQuery Wiki';
$wgMetaNamespace = 'Project';
$wgLanguageCode = 'en';
$wgDefaultSkin = 'jqdocs';
$wgFavicon = 'http://static.jquery.com/favicon.ico';

## Web server

#$wgServer = false; // Set later
$wgScriptPath = '/mw';
$wgScriptExtension = '.php';

// Action paths, like "/edit/Page_name"
$actions = array(
	'view', 'edit', 'watch', 'unwatch', 'delete','revert', 'rollback',
	'protect', 'unprotect', 'markpatrolled', 'render', 'submit', 'history', 'purge'
);
foreach ( $actions as $action ) {
	$wgActionPaths[$action] = "/$action/$1";
}


// Override view, use "/Page_name" for regular page views.
$wgArticlePath = $wgActionPaths['view'] = "/$1";

// To enable image uploads, make sure the 'images' directory is writable
$wgEnableUploads = false;

// InstantCommons allows wiki to use images from http://commons.wikimedia.org
$wgUseInstantCommons = false;

// We installed MediaWiki from Git
// Extensions are seperate clones in a higher directory, fix paths:
$jqgExtPath = dirname( realpath( $IP ) ) . '/mediawiki-extensions';
$wgExtensionAssetsPath = '/mw-extensions';


/**@}*/

/************************************************************************
 * @section MediaWiki extensions
 *
 *
 * @{
 */

require_once( "$jqgExtPath/DynamicPageList/DynamicPageList.php" );

require_once( "$jqgExtPath/Interwiki/Interwiki.php" );

require_once( "$jqgExtPath/JqDocsSkin/JqDocsSkin.php" );

require_once( "$jqgExtPath/ParserFunctions/ParserFunctions.php" );


/**@}*/

/************************************************************************
 * @section Environment specific settings
 *
 *
 * @{
 */

$jqgIsStage = false;

if ( $wgCommandLineMode ) {
	if ( defined( 'MW_DB' ) ) {
		$jqgIsStage = MW_DB !== 'jqdocs_live';
	} else {
		// Can't guess server from the CLI
		die( 'Command-line mode must specify which --wiki should be acted on.' . "\n" );
	}
} elseif ( WebRequest::detectServer() === 'http://stage.docs.jquery.com' ) {
	$jqgIsStage = true;
}


if ( $jqgIsStage ) {

	// Stage settings:

	$wgDBname = 'jqdocs_stage';
	$wgServer = 'http://stage.docs.jquery.com';

	$wgReadOnly = '<div class="usermessage">This is the staging wiki. It runs on a fixed snapshot of the live wiki. Please make edits on the live wiki instead.</div>';

	error_reporting( -1 );

	$wgShowExceptionDetails = true;

} else {

	// Production settings:

	$wgDBname = 'jqdocs_live';
	$wgServer = 'http://docs.jquery.com';

	error_reporting( 0 );
}


/**@}*/

/************************************************************************
 * @section Additional settings
 *
 *
 * @{
 */

/**
 * Make interwiki redirects to trusted sites use 301 (default behavior is 302).
 *
 * @param Title &$title
 * @param WebRequest &$request
 * @param bool &$ignoreRedirect
 * @param bool|string|Title &$target
 * @param Article &$article
 */
function efInterwikiRedirectPage301( &$title, &$request, &$ignoreRedirect, &$target, &$article ) {
	global $wgDisableHardRedirects;
	if ( !$ignoreRedirect && !$target && $article->isRedirect() ) {
		$maybeUrl = $article->followRedirect();
		if ( is_string( $maybeUrl ) && !$wgDisableHardRedirects ) {
			$article->getContext()->getOutput()->redirect( $maybeUrl, 301 );
			// We've handled it, so the rest of the code should no longer act on this redirect
			$ignoreRedirect = true;
		}
	}

	return true;
}
$wgHooks['InitializeArticleMaybeRedirect'][] = 'efInterwikiRedirectPage301';

## Subpages

// Enable in the main namespace
$wgNamespacesWithSubpages[NS_MAIN] = true;


## Caching and optimization

// Optimizations
$wgUseGzip = true;
$wgEnableSidebarCache = true;
$wgCompressRevisions = true;
$wgHitcounterUpdateFreq = 10;

// Set up parser cache
$wgEnableParserCache = true;

// Set up file cache (at "/var/www/<domain>/mw-cache", sibling of public_html)
$wgShowIPinHeader = false;
$wgCacheDirectory = dirname( $IP ) . '/mw-cache';

// Set up wiki page cache
$wgFileCacheDirectory = "{$wgCacheDirectory}/html";

// Set up Memcached
#$wgMemCachedServers = array( '127.0.0.1:11211' );
$wgMemCachedServers = array();

// Now that its all set up, use it
$wgMainCacheType = CACHE_ACCEL;
$wgMessageCacheType = CACHE_ACCEL;
$wgParserCacheType = CACHE_ACCEL;
$wgUseFileCache = true;


## Set user permissions

// Rename legacy group 'sysop' to 'wiki-admin'
$wgGroupPermissions['wiki-admin'] = $wgGroupPermissions['sysop'];

// Merge sysop, checkuser, developer and bureaucrat into wiki-admin
unset( $wgGroupPermissions['sysop'] );
unset( $wgGroupPermissions['checkuser'] );
unset( $wgGroupPermissions['developer'] );
unset( $wgGroupPermissions['bureaucrat'] );

$wgGroupPermissions['wiki-admin']['userrights'] = true;
$wgGroupPermissions['wiki-admin']['editinterface'] = true;

// Account creation: Disable for readers, users; Enable for wiki-admins
$wgGroupPermissions['*']['createaccount'] =
$wgGroupPermissions['user']['createaccount'] = false;

$wgGroupPermissions['wiki-admin']['createaccount'] = true;

// Editing: Disable for readers; Enable for users
$wgGroupPermissions['*']['edit'] =
$wgGroupPermissions['*']['writeapi'] =
$wgGroupPermissions['*']['createpage'] =
$wgGroupPermissions['*']['createtalk'] =
$wgGroupPermissions['*']['minoredit'] = false;

$wgGroupPermissions['user']['edit'] =
$wgGroupPermissions['user']['writeapi'] =
$wgGroupPermissions['user']['createtalk'] =
$wgGroupPermissions['user']['createpage'] =
$wgGroupPermissions['user']['minoredit'] = true;

// Uploads: Disable for users; Enable for wiki-admins
$wgGroupPermissions['user']['upload'] =
$wgGroupPermissions['user']['reupload'] =
$wgGroupPermissions['user']['reupload-shared'] = false;

$wgGroupPermissions['wiki-admin']['upload'] =
$wgGroupPermissions['wiki-admin']['reupload'] =
$wgGroupPermissions['wiki-admin']['reupload-shared'] = true;

// Enable delete-revision feature
$wgGroupPermissions['wiki-admin']['deleterevision'] = true;

// Extension:Interwiki
$wgGroupPermissions['wiki-admin']['interwiki'] = true;


## Hide old user preferences

// personal
$wgHiddenPrefs[] = 'realname';
$wgHiddenPrefs[] = 'nickname';
$wgHiddenPrefs[] = 'fancysig';
// rendering
$wgHiddenPrefs[] = 'imagesize';
$wgHiddenPrefs[] = 'underline';
$wgHiddenPrefs[] = 'highlightbroken';
$wgHiddenPrefs[] = 'showjumplinks';
$wgHiddenPrefs[] = 'numberheadings';
$wgHiddenPrefs[] = 'vector-noexperiments';
// editing
$wgHiddenPrefs[] = 'previewontop';
$wgHiddenPrefs[] = 'editsection';
$wgHiddenPrefs[] = 'editsectiononrightclick';
$wgHiddenPrefs[] = 'editondblclick';
$wgHiddenPrefs[] = 'showtoolbar';
$wgHiddenPrefs[] = 'minordefault';
$wgHiddenPrefs[] = 'externaleditor';
$wgHiddenPrefs[] = 'externaldiff';
// rc
$wgHiddenPrefs[] = 'usenewrc';
// searchoptions
$wgHiddenPrefs[] = 'vector-simplesearch';
// misc
$wgHiddenPrefs[] = 'diffonly';
$wgHiddenPrefs[] = 'norollbackdiff';


## Set default user preferences

// personal
$wgDefaultUserOptions['ccmeonemails'] = 1;
// searchoptions
$wgDefaultUserOptions['searchlimit'] = 25;
// watchlist
$wgDefaultUserOptions['watchlistdays'] = 0;


## Misc

// Allow raw HTML inside wiki markup through <html> tags
// Disabled by default, but enabled for jqdocs for backwards compatibility.
$wgRawHtml = true;
$wgWellFormedXml = false;

// Don't show links to talk pages to readers
$wgDisableAnonTalk = true;


/************************************************************************
 * @section Includes (must be last)
 *
 * @{
 */

require_once( __DIR__ . '/PrivateSettings.php' );


/**@}*/
