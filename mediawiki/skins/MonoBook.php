<?php
/**
 * MonoBook nouveau
 *
 * Translated from gwicke's previous TAL template version to remove
 * dependency on PHPTAL.
 *
 * @todo document
 * @package MediaWiki
 * @subpackage Skins
 */

if( !defined( 'MEDIAWIKI' ) )
	die( -1 );

/** */
require_once('includes/SkinTemplate.php');

/**
 * Inherit main code from SkinTemplate, set the CSS and template filter.
 * @todo document
 * @package MediaWiki
 * @subpackage Skins
 */
class SkinMonoBook extends SkinTemplate {
	/** Using monobook. */
	function initPage( &$out ) {
		SkinTemplate::initPage( $out );
		$this->skinname  = 'monobook';
		$this->stylename = 'monobook';
		$this->template  = 'MonoBookTemplate';
	}
}

/**
 * @todo document
 * @package MediaWiki
 * @subpackage Skins
 */
class MonoBookTemplate extends QuickTemplate {
	/**
	 * Template filter callback for MonoBook skin.
	 * Takes an associative array of data set from a SkinTemplate-based
	 * class, and a wrapper for MediaWiki's localization database, and
	 * outputs a formatted page.
	 *
	 * @access private
	 */
	function execute() {
		// Suppress warnings to prevent notices about missing indexes in $this->data
		wfSuppressWarnings();

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<?php $this->html('headlinks') ?>
		<title><?php $this->text('pagetitle') ?></title>
		<link rel="stylesheet" href="http://static.jquery.com/files/rocker/css/reset.css" type="text/css" />
		<link rel="stylesheet" href="http://static.jquery.com/files/rocker/css/screen.css" type="text/css" />
		<style type="text/css" media="screen,projection">/*<![CDATA[*/ @import "http://docs.jquery.com/skins/monobook/main.css?9"; /*]]>*/</style>

		<link rel="stylesheet" type="text/css" href="http://code.jquery.com/ui/1.8.23/themes/ui-lightness/jquery-ui.css" />

		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js"></script>
		<script src="http://code.jquery.com/ui/1.8.23/jquery-ui.min.js"></script>

		<script>$(function(){
			$("div[id=examples]").each(function(){
				$(this).siblings("div:first").find("div.desc").after(
					$(this).find("div.example:has(#demo):first").remove().clone());

				if ( !$("div.example", this).length )
					$(this).remove();
			});

			$("div.entry")
				.children("p").remove().end()
				.find("ul:first li").each(function(){
					if ( !$(this).parent().siblings( $("a",this).attr("href") ).length )
						$(this).remove();
				}).end()
				.not(".ui")
				.tabs();

			$("div.args > br").remove();

			$("div.example")
				.children("p").remove().end()
				.tabs();

			var keywords = "String,Number,Object,Options,Array,Function,Callback,Selector,Event,Element,Integer,Float,Boolean".split(",");

			$("td.default span, td.type").each(function(){
				var html = this.innerHTML;
				jQuery.each(keywords, function(i,key){
					html = html.replace(new RegExp(key, "ig"), "<a href='/Types#" + key + "'>" + key + "</a>");
				});
				this.innerHTML = html;
			});

			$("div[id=source]").each(function(){
				var source = $("pre", this).html()
					.replace(/<\/?a.*?>/ig, "")
					.replace(/<\/?strong.*?>/ig, "")
					.replace(/&lt;/g, "<").replace(/&gt;/g, ">").replace(/&amp;/g, "&");

				//var items = [];
				//source = source.replace(/\s*<(link|script).*?>\s\*/g, function(m){
					//items.push(m);
					//return "";
				//}).replace("</head>", items.join("") + "</head>")
				//*/

				var iframe = document.createElement("iframe");
				iframe.src = "/index-blank.html";
				iframe.width = "100%";
				iframe.height = $(this).prev().attr("rel") || "125";
				iframe.style.border = "none";
				$(this).prev().append(iframe);


				$(window).load(function(){
					var doc = iframe.contentDocument || iframe.contentWindow.document;
					  source = source
					          .replace(/<script>([^<])/g,"<script>window.onload = (function(){\ntry{$1")
					          .replace(/([^>])<\/sc/g,  '$1\n}catch(e){}});</sc');

					    source = source
					            .replace("</head>", "<style>html,body{border:0; margin:0; padding:0;}</style></head>");

					doc.open();
					doc.write( source );
					doc.close();
				});

			});
		});</script>
		<script type="text/javascript" src="http://static.jquery.com/files/rocker/scripts/custom.js"></script>
		<link rel="shortcut icon" href="http://static.jquery.com/favicon.ico" type="image/x-icon"/>
		<style type="text/css" media="screen,projection">/*<![CDATA[*/ @import "<?php $this->text('stylepath') ?>/<?php $this->text('stylename') ?>/main.css?9"; /*]]>*/</style>
		<link rel="stylesheet" type="text/css" <?php if(empty($this->data['printable']) ) { ?>media="print"<?php } ?> href="<?php $this->text('stylepath') ?>/common/commonPrint.css" />
		<!--[if lt IE 5.5000]><style type="text/css">@import "<?php $this->text('stylepath') ?>/<?php $this->text('stylename') ?>/IE50Fixes.css";</style><![endif]-->
		<!--[if IE 5.5000]><style type="text/css">@import "<?php $this->text('stylepath') ?>/<?php $this->text('stylename') ?>/IE55Fixes.css";</style><![endif]-->
		<!--[if IE 6]><style type="text/css">@import "<?php $this->text('stylepath') ?>/<?php $this->text('stylename') ?>/IE60Fixes.css";</style><![endif]-->
		<!--[if IE 7]><style type="text/css">@import "<?php $this->text('stylepath') ?>/<?php $this->text('stylename') ?>/IE70Fixes.css?1";</style><![endif]-->
		<!--[if lt IE 7]><script type="<?php $this->text('jsmimetype') ?>" src="<?php $this->text('stylepath') ?>/common/IEFixes.js"></script>
		<meta http-equiv="imagetoolbar" content="no" /><![endif]-->
		
		<!-- Head Scripts -->
		<?php $this->html('headscripts') ?>
	</head>
	<body id="jq-interior" <?php if($this->data['body_ondblclick']) { ?>ondblclick="<?php $this->text('body_ondblclick') ?>"<?php } ?>
<?php if($this->data['body_onload'    ]) { ?>onload="<?php     $this->text('body_onload')     ?>"<?php } ?>
 class="mediawiki <?php $this->text('nsclass') ?> <?php $this->text('dir') ?>">

	<div id="jq-siteContain">
			<div id="jq-header">
				<a id="jq-siteLogo" href="http://jquery.com/" title="jQuery Home"><img src="http://static.jquery.com/files/rocker/images/logo_jquery_215x53.gif" width="215" height="53" alt="jQuery: Write Less, Do More." /></a>

				<div id="jq-primaryNavigation">

					<ul>
<li class="jq-jquery jq-current"><a href="http://jquery.com/" title="jQuery Home">jQuery</a></li>
<li class="jq-plugins"><a href="http://plugins.jquery.com/" title="jQuery Plugins">Plugins</a></li>
<li class="jq-ui"><a href="http://jqueryui.com/" title="jQuery UI">UI</a></li>
<li class="jq-meetup"><a href="http://meetups.jquery.com/" title="jQuery Meetups">Meetups</a></li>
<li class="jq-forum"><a href="http://forum.jquery.com/" title="jQuery Forum">Forum</a></li>
<li class="jq-blog"><a href="http://blog.jquery.com/" title="jQuery Blog">Blog</a></li>
<li class="jq-about"><a href="http://jquery.org/about" title="About jQuery">About</a></li>
<li class="jq-donate"><a href="http://jquery.org/donate" title="Donate to jQuery">Donate</a></li>
					</ul>
				</div><!-- /#primaryNavigation -->

				<div id="jq-secondaryNavigation">
					<ul>
						<li class="jq-download jq-first"><a href="http://docs.jquery.com/Downloading_jQuery">Download</a></li>
						<li class="jq-documentation jq-current"><a href="http://docs.jquery.com/">Documentation</a></li>
						<li class="jq-tutorials"><a href="http://docs.jquery.com/Tutorials">Tutorials</a></li>

						<li class="jq-bugTracker"><a href="http://dev.jquery.com/">Bug Tracker</a></li>
						<li class="jq-discussion jq-last"><a href="http://docs.jquery.com/Discussion">Discussion</a></li>
					</ul>
				</div><!-- /#secondaryNavigation -->

				

				<h1>Documentation</h1>

				<form id="jq-primarySearchForm" action="/Special:Search">

					<div>
						<input type="hidden" value="1" name="ns0"/>
						<label for="primarySearch">Search <span class="jq-jquery">jQuery</span></label>
						<input type="text" value="" accesskey="f" title="Search jQuery" name="search" id="jq-primarySearch"/>
						<button type="submit" name="go" id="jq-searchGoButton"><span>Go</span></button>
					</div>

				</form>

			</div><!-- /#header -->

			<div id="jq-content" class="jq-clearfix">

				<div id="jq-interiorNavigation">
					<div class='jq-portlet' id='jq-p-Getting-Started'>
						<h5>Getting Started</h5>
						<div class='jq-pBody'>

							<ul>

								<li id="jq-n-Main-Page"><a href="/Main_Page">Main Page</a></li>
								<li id="jq-n-Downloading-jQuery"><a href="/Downloading_jQuery">Downloading jQuery</a></li>
				
								<li id="jq-n-How-jQuery-Works"><a href="/How_jQuery_Works">How jQuery Works</a></li>
								<li id="jq-n-FAQ"><a href="/Frequently_Asked_Questions">FAQ</a></li>
								<li id="jq-n-Tutorials"><a href="/Tutorials">Tutorials</a></li>

								<li id="jq-n-Using-jQuery-with-Other-Libraries"><a href="/Using_jQuery_with_Other_Libraries">Using jQuery with Other Libraries</a></li>

								<li id="jq-n-Variable-Types"><a href="/Types">Variable Types</a></li>
							</ul>
				
						</div>
					</div>
						<div class='jq-portlet' id='jq-p-API-Reference'>
						<h5>API Reference</h5>

						<div class='jq-pBody'>

							<ul>
								<li id="jq-n-jQuery-Core"><a href="/Core">jQuery Core</a></li>
								<li id="jq-n-Selectors"><a href="/Selectors">Selectors</a></li>
				
								<li id="jq-n-Attributes"><a href="/Attributes">Attributes</a></li>
								<li id="jq-n-Traversing"><a href="/Traversing">Traversing</a></li>
								<li id="jq-n-Manipulation"><a href="/Manipulation">Manipulation</a></li>

								<li id="jq-n-CSS"><a href="/CSS">CSS</a></li>
								<li id="jq-n-Events"><a href="/Events">Events</a></li>
								<li id="jq-n-Effects"><a href="/Effects">Effects</a></li>
				
								<li id="jq-n-Ajax"><a href="/Ajax">Ajax</a></li>
								<li id="jq-n-Utilities"><a href="/Utilities">Utilities</a></li>
								<li id="jq-n-jQuery-UI"><a href="/UI">jQuery UI</a></li>

							</ul>
						</div>
					</div>
						<div class='jq-portlet' id='jq-p-Plugins'>
				
						<h5>Plugins</h5>
						<div class='jq-pBody'>
							<ul>
								<li id="jq-n-Plugin-Repository"><a href="http://jquery.com/plugins/">Plugin Repository</a></li>

								<li id="jq-n-Authoring"><a href="/Plugins/Authoring">Authoring</a></li>
							</ul>
						</div>
				
					</div>
						<div class='jq-portlet' id='jq-p-Support'>
						<h5>Support</h5>
						<div class='jq-pBody'>

							<ul>

								<li id="jq-n-Mailing-List-and-Chat"><a href="/Discussion">Mailing List and Chat</a></li>
								<li id="jq-n-Submit-New-Bug"><a href="http://jquery.com/dev/bugs/new/">Submit New Bug</a></li>
								<li id="jq-n-Commercial-Support"><a href="/Commercial_Support">Commercial Support</a></li>
				
							</ul>
						</div>
					</div>
						<div class='jq-portlet' id='jq-p-About-jQuery'>

						<h5>About jQuery</h5>

						<div class='jq-pBody'>
							<ul>
								<li id="jq-n-Contributors"><a href="/Contributors">Contributors</a></li>
				
								<li id="jq-n-History-of-jQuery"><a href="/History_of_jQuery">History of jQuery</a></li>
								<li id="jq-n-Sites-Using-jQuery"><a href="/Sites_Using_jQuery">Sites Using jQuery</a></li>

								<li id="jq-n-Browser-Compatibility"><a href="/Browser_Compatibility">Browser Compatibility</a></li>
								<li id="jq-n-License"><a href="/Licensing">Licensing</a></li>

								<li id="jq-n-Donate"><a href="/Donate">Donate</a></li>
							</ul>
				
						</div>
					</div>
						
	<div class="jq-portlet" id="p-tb">
		<h5><?php $this->msg('toolbox') ?></h5>
		<div class="jq-pBody">
			<ul>
<?php
		if($this->data['notspecialpage']) { ?>
				<li id="t-whatlinkshere"><a href="<?php
				echo htmlspecialchars($this->data['nav_urls']['whatlinkshere']['href'])
				?>"><?php $this->msg('whatlinkshere') ?></a></li>
<?php
			if( $this->data['nav_urls']['recentchangeslinked'] ) { ?>
				<li id="t-recentchangeslinked"><a href="<?php
				echo htmlspecialchars($this->data['nav_urls']['recentchangeslinked']['href'])
				?>"><?php $this->msg('recentchangeslinked') ?></a></li>
<?php 		}
		}
		if(isset($this->data['nav_urls']['trackbacklink'])) { ?>
			<li id="t-trackbacklink"><a href="<?php
				echo htmlspecialchars($this->data['nav_urls']['trackbacklink']['href'])
				?>"><?php $this->msg('trackbacklink') ?></a></li>
<?php 	}
		if($this->data['feeds']) { ?>
			<li id="feedlinks"><?php foreach($this->data['feeds'] as $key => $feed) {
					?><span id="feed-<?php echo htmlspecialchars($key) ?>"><a href="<?php
					echo htmlspecialchars($feed['href']) ?>"><?php echo htmlspecialchars($feed['text'])?></a>&nbsp;</span>
					<?php } ?></li><?php
		}

		foreach( array('contributions', 'blockip', 'emailuser', 'upload', 'specialpages') as $special ) {

			if($this->data['nav_urls'][$special]) {
				?><li id="t-<?php echo $special ?>"><a href="<?php echo htmlspecialchars($this->data['nav_urls'][$special]['href'])
				?>"><?php $this->msg($special) ?></a></li>
<?php		}
		}

		if(!empty($this->data['nav_urls']['print']['href'])) { ?>
				<li id="t-print"><a href="<?php echo htmlspecialchars($this->data['nav_urls']['print']['href'])
				?>"><?php $this->msg('printableversion') ?></a></li><?php
		}

		if(!empty($this->data['nav_urls']['permalink']['href'])) { ?>
				<li id="t-permalink"><a href="<?php echo htmlspecialchars($this->data['nav_urls']['permalink']['href'])
				?>"><?php $this->msg('permalink') ?></a></li><?php
		} elseif ($this->data['nav_urls']['permalink']['href'] === '') { ?>
				<li id="t-ispermalink"><?php $this->msg('permalink') ?></li><?php
		}

		wfRunHooks( 'MonoBookTemplateToolboxEnd', array( &$this ) );
?>
			</ul>
		</div>
	</div>

	<div id="jq-p-cactions" class="jq-portlet">
		<h5><?php $this->msg('views') ?></h5>
		<div class="jq-pBody">
		<ul>
<?php			foreach($this->data['content_actions'] as $key => $tab) { ?>
				 <li id="jq-ca-<?php echo htmlspecialchars($key) ?>"<?php
				 	if($tab['class']) { ?> class="<?php echo htmlspecialchars($tab['class']) ?>"<?php }
				 ?>><a href="<?php echo htmlspecialchars($tab['href']) ?>"><?php
				 echo htmlspecialchars($tab['text']) ?></a></li>
<?php			 } ?>
		</ul>
		</div>
	</div>
	<div class="jq-portlet" id="jq-p-personal">
		<h5><?php $this->msg('personaltools') ?></h5>
		<div class="jq-pBody">
			<ul>
<?php 			foreach($this->data['personal_urls'] as $key => $item) { ?>
				<li id="jq-pt-<?php echo htmlspecialchars($key) ?>"<?php
					if ($item['active']) { ?> class="active"<?php } ?>><a href="<?php
				echo htmlspecialchars($item['href']) ?>"<?php
				if(!empty($item['class'])) { ?> class="<?php
				echo htmlspecialchars($item['class']) ?>"<?php } ?>><?php
				echo htmlspecialchars($item['text']) ?></a></li>
<?php			} ?>
			</ul>
		</div>
	</div>

					
				</div><!-- /#interiorNavigation -->





				<div id="jq-primaryContent">
		<div id="column-content">
	<div id="docs-content">
		<a name="top" id="top"></a>
		<?php if($this->data['sitenotice']) { ?><div id="siteNotice"><?php $this->html('sitenotice') ?></div><?php } ?>
		<!--<div style="background:#efefff; border:#9f9fff 1px solid; padding: 10px;"><strong>UPDATE:</strong> The jQuery API documentation has been completely rewritten and moved to <a href="http://api.jquery.com/">api.jquery.com</a>. More details <a href="http://jquery14.com/pre-release-1/new-jquery-api-site">can be found on the jQuery blog</a>.</div>-->
		<h1 class="firstHeading"><?php $this->data['displaytitle']!=""?$this->html('title'):$this->text('title') ?></h1>
		<div id="bodyContent">
			<h3 id="siteSub"><?php $this->msg('tagline') ?></h3>
			<div id="contentSub"><?php $this->html('subtitle') ?></div>
			<?php if($this->data['undelete']) { ?><div id="contentSub2"><?php     $this->html('undelete') ?></div><?php } ?>
			<?php if($this->data['newtalk'] ) { ?><div class="usermessage"><?php $this->html('newtalk')  ?></div><?php } ?>
			<?php if($this->data['showjumplinks']) { ?><div id="jump-to-nav"><?php $this->msg('jumpto') ?> <a href="#column-one"><?php $this->msg('jumptonavigation') ?></a>, <a href="#searchInput"><?php $this->msg('jumptosearch') ?></a></div><?php } ?>
			<!-- start content -->
			<?php $this->html('bodytext') ?>
			<?php if($this->data['catlinks']) { ?><div id="catlinks"><?php       $this->html('catlinks') ?></div><?php } ?>
			<!-- end content -->
			<div class="visualClear"></div>
		</div>
	</div>
		</div>

				</div><!-- /#primaryContent -->

				
				
				
			</div><!-- /#content -->


			<div id="jq-footer" class="jq-clearfix">

				<div id="jq-credits">
				<p id="jq-copyright">&copy; 2010 <a href="http://jquery.org/">The jQuery Project</a></p>
				<p id="jq-hosting">Sponsored by <a href="http://mediatemple.net" class="jq-mediaTemple">Media Temple</a> and <a href="http://jquery.org/sponsors">others</a>.</p>
				</div>

				<div id="jq-footerNavigation">
					<ul>
						<li class="jq-download jq-first"><a href="http://docs.jquery.com/Downloading_jQuery">Download</a></li>
						<li class="jq-documentation jq-current"><a href="http://docs.jquery.com/">Documentation</a></li>
						<li class="jq-tutorials"><a href="http://docs.jquery.com/Tutorials">Tutorials</a></li>
						<li class="jq-bugTracker"><a href="http://dev.jquery.com/">Bug Tracker</a></li>
						<li class="jq-discussion jq-last"><a href="http://docs.jquery.com/Discussion">Discussion</a></li>
					</ul>
				</div><!-- /#secondaryNavigation -->
				
			</div><!-- /#footer -->


	</div><!-- /#siteContain -->

<script type="text/javascript">
var _gaq = _gaq || []; _gaq.push(['_setAccount', 'UA-1076265-1']); _gaq.push(['_trackPageview']); _gaq.push(['_setDomainName', '.jquery.com']);
(function() {var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
(document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(ga);})();
</script>
	</body>
</html>

<?php
	wfRestoreWarnings();
	} // end of execute() method
} // end of class
?>
