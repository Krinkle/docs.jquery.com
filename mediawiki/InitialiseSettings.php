<?php


// counters cause LOCK TABLES inside ignore_user_abort(true) and runs on *every article request* by default
$wgDisableCounters = true;

# If PHP's memory limit is very low, some operations may fail.
# ini_set( 'memory_limit', '20M' );

if ( $wgCommandLineMode ) {
	if ( isset( $_SERVER ) && array_key_exists( 'REQUEST_METHOD', $_SERVER ) ) {
		die( "This script must be run from the command line\n" );
	}
} elseif ( empty( $wgNoOutputBuffer ) ) {
	## Compress output if the browser supports it
	if( !ini_get( 'zlib.output_compression' ) ) @ob_start( 'ob_gzhandler' );
}

$wgSitename         = "jQuery JavaScript Library";

$wgScriptPath       = "";
$wgScript           = "$wgScriptPath/index.php";
$wgRedirectScript   = "$wgScriptPath/redirect.php";

// Implicit group for all visitors
$wgGroupPermissions['*'    ]['createaccount']   = false;
$wgGroupPermissions['*'    ]['read']            = true;
$wgGroupPermissions['*'    ]['edit']            = false;
$wgGroupPermissions['*'    ]['createpage']      = false;
$wgGroupPermissions['*'    ]['createtalk']      = false;

// Implicit group for all logged-in accounts
$wgGroupPermissions['user' ]['move']            = false;
$wgGroupPermissions['user' ]['read']            = true;
$wgGroupPermissions['user' ]['edit']            = false;
$wgGroupPermissions['user' ]['createpage']      = false;
$wgGroupPermissions['user' ]['createtalk']      = false;
$wgGroupPermissions['user' ]['upload']          = false;
$wgGroupPermissions['user' ]['reupload']        = false;
$wgGroupPermissions['user' ]['reupload-shared'] = false;
$wgGroupPermissions['user' ]['minoredit']       = false;

// Must be a user for at least a day
$wgAutoConfirmAge = 3600*24;

// Implicit group for accounts that pass $wgAutoConfirmAge
$wgGroupPermissions['autoconfirmed']['autoconfirmed'] = false;
$wgGroupPermissions['autoconfirmed']['edit'] = false;
$wgGroupPermissions['autoconfirmed']['minoredit'] = false;

// Implicit group for accounts with confirmed email addresses
// This has little use when email address confirmation is off
$wgGroupPermissions['emailconfirmed']['emailconfirmed'] = true;

$wgGroupPermissions['sysop' ]['move']            = true;
$wgGroupPermissions['sysop' ]['read']            = true;
$wgGroupPermissions['sysop' ]['edit']            = true;
$wgGroupPermissions['sysop' ]['createpage']      = true;
$wgGroupPermissions['sysop' ]['createtalk']      = true;
$wgGroupPermissions['sysop' ]['upload']          = true;
$wgGroupPermissions['sysop' ]['reupload']        = true;
$wgGroupPermissions['sysop' ]['reupload-shared'] = true;
$wgGroupPermissions['sysop' ]['minoredit']       = true;

## For more information on customizing the URLs please see:
## http://meta.wikimedia.org/wiki/Eliminating_index.php_from_the_url
## If using PHP as a CGI module, the ?title= style usually must be used.
$wgArticlePath      = "/$1";
# $wgArticlePath      = "$wgScript?title=$1";

$actions = array('edit', 'watch', 'unwatch', 'delete','revert', 'rollback', 'protect',
  'unprotect','info','markpatrolled','validate','render','deletetrackback','print',
  'dublincore','creativecommons','credits','submit','viewsource','history','raw',
  'purge');

foreach ($actions as $a) {
  $wgActionPaths[$a] = "$wgScriptPath/action/$a/$1";
}

$wgStylePath        = "$wgScriptPath/skins";
$wgStyleDirectory   = "$IP/skins";
$wgLogo             = "/images/hat.gif";

$wgUploadPath       = "$wgScriptPath/images";
$wgUploadDirectory  = "$IP/images";

$wgEnableEmail      = true;
$wgEnableUserEmail  = true;


## For a detailed description of the following switches see
## http://meta.wikimedia.org/Enotif and http://meta.wikimedia.org/Eauthent
## There are many more options for fine tuning available see
## /includes/DefaultSettings.php
## UPO means: this is also a user preference option
$wgEnotifUserTalk = true; # UPO
$wgEnotifWatchlist = true; # UPO
$wgEmailAuthentication = true;


## Shared memory settings
/*
$wgMainCacheType = CACHE_MEMCACHED;
$wgParserCacheType = CACHE_MEMCACHED;
$wgMessageCacheType = CACHE_MEMCACHED;
$wgMemCachedServers = array('127.0.0.1:11211');
$wgSessionsInMemcached = false;
*/
$wgMainCacheType = CACHE_ACCEL;
//$wgMemCachedServers = array();

## To enable image uploads, make sure the 'images' directory
## is writable, then set this to true:
$wgEnableUploads       = true;
$wgUseImageResize      = false;
# $wgUseImageMagick = true;
# $wgImageMagickConvertCommand = "/usr/bin/convert";

## If you want to use image uploads under safe mode,
## create the directories images/archive, images/thumb and
## images/temp, and make them all writable. Then uncomment
## this, if it's not already uncommented:
# $wgHashedUploadDirectory = false;

## If you have the appropriate support software installed
## you can enable inline LaTeX equations:
$wgUseTeX           = false;
$wgMathPath         = "{$wgUploadPath}/math";
$wgMathDirectory    = "{$wgUploadDirectory}/math";
$wgTmpDirectory     = "{$wgUploadDirectory}/tmp";

$wgLocalInterwiki   = $wgSitename;

$wgLanguageCode = "en";

## Default skin: you can change the default skin. Use the internal symbolic
## names, ie 'standard', 'nostalgia', 'cologneblue', 'monobook':
$wgDefaultSkin = 'monobook';

## For attaching licensing metadata to pages, and displaying an
## appropriate copyright notice / icon. GNU Free Documentation
## License and Creative Commons licenses are supported so far.
# $wgEnableCreativeCommonsRdf = true;
$wgRightsPage = ""; # Set to the title of a wiki page that describes your license/copyright
$wgRightsUrl = "";
$wgRightsText = "";
$wgRightsIcon = "";
# $wgRightsCode = ""; # Not yet used

$wgDiff3 = "";
$wgExternalDiffEngine = 'wikidiff2';

# When you make changes to this configuration file, this will make
# sure that cached pages are cleared.
$configdate = gmdate( 'YmdHis', @filemtime( __FILE__ ) );
$wgCacheEpoch = max( $wgCacheEpoch, $configdate );

$wgUseFileCache = true;
$wgFileCacheDirectory = "/home/jquery/cache/mediawiki";
$wgShowIPinHeader = false;
$wgUseGzip = false;

$wgCachePages = true;

$wgDisableAnonTalk = true;

$wgAntiLockFlags = ALF_NO_LINK_LOCK | ALF_NO_BLOCK_LOCK;
$wgMiserMode = true;
$wgUseDumbLinkUpdate = true;

$wgRawHtml = true;

$wgFileExtensions = array( 'png', 'gif', 'jpg', 'jpeg', 'ogg', 'zip');

$wgNoFollowLinks = false;

require_once("extensions/ParserFunctions.php");
require_once("extensions/DynamicPageList2.php");
require_once("extensions/wiki2xml/extension.php");
