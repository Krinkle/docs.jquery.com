<?php
/**
 * JqDocs - Based on Vector.
 *
 * @author Krinkle, 2012
 */

if( !defined( 'MEDIAWIKI' ) ) {
	die( -1 );
}

/**
 * SkinTemplate class for JqDocs skin
 * @ingroup Skins
 */
class SkinJqDocs extends SkinTemplate {

	protected static $bodyClasses = array();

	var $skinname = 'jqdocs', $stylename = 'jqdocs',
		$template = 'JqDocsTemplate', $useHeadElement = true;

	/**
	 * Initializes output page and sets up skin-specific parameters
	 * @param $out OutputPage object to initialize
	 */
	public function initPage( OutputPage $out ) {
		global $wgLocalStylePath;

		parent::initPage( $out );

		// Important: Don't load jQuery, it ships with MediaWiki and is loaded by default

		// Important: Don't load jQuery UI, it ships with MediaWiki.
		// The depedendency from 'skins.jqdocs' on 'jquery.ui.tabs' will automatically
		// load it (and its dependencies, namely jquery.ui.core)

		// jQuery Rocker Theme
		$out->addStyle( 'http://static.jquery.com/files/rocker/css/reset.css' );
		$out->addStyle( 'http://static.jquery.com/files/rocker/css/screen.css' );
		$out->addScriptFile( 'http://static.jquery.com/files/rocker/scripts/custom.js' );

		// JqDocs MediaWiki Skin modules
		$out->addModuleStyles( 'skins.jqdocs.layout' );
		$out->addModules( 'skins.jqdocs.enhanced' );
	}

	/**
	 * Adds classes to the body element.
	 * 
	 * @param $out OutputPage object
	 * @param &$bodyAttrs Array of attributes that will be set on the body element
	 */
	function addToBodyAttributes( $out, &$bodyAttrs ) {
		parent::addToBodyAttributes( $out, $bodyAttrs );

		// jQuery Rocket Theme needs <body id="jq-interior">
		$bodyAttrs['id'] = 'jq-interior';
	}
}

/**
 * QuickTemplate class for JqDocs skin
 * @ingroup Skins
 */
class JqDocsTemplate extends BaseTemplate {

	/* Functions */

	/**
	 * Outputs the entire contents of the (X)HTML page
	 */
	public function execute() {

		// Build additional attributes for navigation urls
		$nav = $this->data['content_navigation'];

		$xmlID = '';
		foreach ( $nav as $section => $links ) {
			foreach ( $links as $key => $link ) {
				if ( $section == 'views' && !( isset( $link['primary'] ) && $link['primary'] ) ) {
					$link['class'] = rtrim( 'collapsible ' . $link['class'], ' ' );
				}

				$xmlID = isset( $link['id'] ) ? $link['id'] : 'ca-' . $xmlID;
				$nav[$section][$key]['attributes'] =
					' id="' . Sanitizer::escapeId( $xmlID ) . '"';
				if ( $link['class'] ) {
					$nav[$section][$key]['attributes'] .=
						' class="' . htmlspecialchars( $link['class'] ) . '"';
					unset( $nav[$section][$key]['class'] );
				}
				if ( isset( $link['tooltiponly'] ) && $link['tooltiponly'] ) {
					$nav[$section][$key]['key'] =
						Linker::tooltip( $xmlID );
				} else {
					$nav[$section][$key]['key'] =
						Xml::expandAttributes( Linker::tooltipAndAccesskeyAttribs( $xmlID ) );
				}
			}
		}
		$this->data['namespace_urls'] = $nav['namespaces'];
		$this->data['view_urls'] = $nav['views'];
		$this->data['action_urls'] = $nav['actions'];
		$this->data['variant_urls'] = $nav['variants'];

		// Reverse horizontally rendered navigation elements
		if ( $this->data['rtl'] ) {
			$this->data['view_urls'] =
				array_reverse( $this->data['view_urls'] );
			$this->data['namespace_urls'] =
				array_reverse( $this->data['namespace_urls'] );
			$this->data['personal_urls'] =
				array_reverse( $this->data['personal_urls'] );
		}
		// Output HTML Page
		$this->html( 'headelement' );
?>
<div id="jq-siteContain">
	<div id="jq-header">
		<a id="jq-siteLogo" href="http://jquery.com/" title="jQuery Home"><img src="http://static.jquery.com/files/rocker/images/logo_jquery_215x53.gif" width="215" height="53" alt="jQuery: Write Less, Do More."></a>

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
		</div><!-- /#jq-primaryNavigation -->

		<div id="jq-secondaryNavigation">
			<ul>
				<li class="jq-download jq-first"><a href="http://docs.jquery.com/Downloading_jQuery">Download</a></li>
				<li class="jq-documentation jq-current"><a href="http://docs.jquery.com/">Documentation</a></li>
				<li class="jq-tutorials"><a href="http://docs.jquery.com/Tutorials">Tutorials</a></li>
				<li class="jq-bugTracker"><a href="http://dev.jquery.com/">Bug Tracker</a></li>
				<li class="jq-discussion jq-last"><a href="http://docs.jquery.com/Discussion">Discussion</a></li>
			</ul>
		</div><!-- /#jq-secondaryNavigation -->

		<h1>Documentation</h1>

		<form id="jq-primarySearchForm" action="/Special:Search">
			<div>
				<input type="hidden" value="1" name="ns0">
				<label for="primarySearch">Search <span class="jq-jquery">jQuery</span></label>
				<input type="text" value="" accesskey="f" title="Search jQuery" name="search" id="jq-primarySearch">
				<button type="submit" id="jq-searchGoButton"><span>Go</span></button>
			</div>
		</form>
	</div><!-- /#jq-header -->

	<div id="jq-content" class="jq-clearfix">
		<div id="jq-interiorNavigation">
			<div id="mw-panel" class="noprint">
				<?php
					foreach( $this->getSidebar( array( 'htmlOnly' => true ) ) as $portletId => $portletData ) {
						echo Html::element( 'h5', array(), $portletData['header'] )
							. $portletData['content'];
					}
				?>
			</div><!-- /#mw-panel -->
			<?php
				/**
				 * PERSONAL
				 *
				 * NAMESPACES
				 * VARIANTS
				 *
				 * VIEWS
				 * ACTIONS
				 * SEARCH
				 *
				 * sidebar
				 *
				 */
				$this->renderNavigation( array( 'ACTIONS', 'PERSONAL' ) );
			?>
		</div><!-- /#interiorNavigation -->
		<div id="jq-primaryContent">
			<div id="column-content">
				<div id="docs-content" class="mw-body">
				<?php
					$this->getMwBodyContents();
				?>
				</div>
			</div>
		</div><!-- /#jq-primaryContent -->
	</div><!-- /#jq-content -->
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
		</div><!-- /#jq-footerNavigation -->
	</div><!-- /#jq-footer -->
</div><!-- /#jq-siteContain -->
<?php $this->printTrail(); ?>
</body>
</html>
<?php
	}

	private function getMwBodyContents() {
?>

<a id="top"></a>
<div id="mw-js-message" style="display:none;"<?php $this->html( 'userlangattributes' ) ?>></div>
<?php if ( $this->data['sitenotice'] ): ?>
<!-- sitenotice -->
<div id="siteNotice"><?php $this->html( 'sitenotice' ) ?></div>
<!-- /sitenotice -->
<?php endif; ?>
<!-- firstHeading -->
<h1 id="firstHeading" class="firstHeading"><span dir="auto"><?php $this->html( 'title' ) ?></span></h1>
<!-- /firstHeading -->
<!-- bodyContent -->
<div id="bodyContent">
	<?php if ( $this->data['isarticle'] ): ?>
	<!-- tagline -->
	<div id="siteSub"><?php $this->msg( 'tagline' ) ?></div>
	<!-- /tagline -->
	<?php endif; ?>
	<!-- subtitle -->
	<div id="contentSub"<?php $this->html( 'userlangattributes' ) ?>><?php $this->html( 'subtitle' ) ?></div>
	<!-- /subtitle -->
	<?php if ( $this->data['undelete'] ): ?>
	<!-- undelete -->
	<div id="contentSub2"><?php $this->html( 'undelete' ) ?></div>
	<!-- /undelete -->
	<?php endif; ?>
	<?php if( $this->data['newtalk'] ): ?>
	<!-- newtalk -->
	<div class="usermessage"><?php $this->html( 'newtalk' ) ?></div>
	<!-- /newtalk -->
	<?php endif; ?>
	<?php if ( $this->data['showjumplinks'] ): ?>
	<!-- jumpto -->
	<div id="jump-to-nav" class="mw-jump">
		<?php $this->msg( 'jumpto' ) ?>
		<a href="#mw-head"><?php $this->msg( 'jumptonavigation' ) ?></a><?php $this->msg( 'comma-separator' ) ?>
		<a href="#p-search"><?php $this->msg( 'jumptosearch' ) ?></a>
	</div>
	<!-- /jumpto -->
	<?php endif; ?>
	<!-- bodycontent -->
	<?php $this->html( 'bodycontent' ) ?>
	<!-- /bodycontent -->
	<?php if ( $this->data['printfooter'] ): ?>
	<!-- printfooter -->
	<div class="printfooter">
	<?php $this->html( 'printfooter' ); ?>
	</div>
	<!-- /printfooter -->
	<?php endif; ?>
	<?php if ( $this->data['catlinks'] ): ?>
	<!-- catlinks -->
	<?php $this->html( 'catlinks' ); ?>
	<!-- /catlinks -->
	<?php endif; ?>
	<?php if ( $this->data['dataAfterContent'] ): ?>
	<!-- dataAfterContent -->
	<?php $this->html( 'dataAfterContent' ); ?>
	<!-- /dataAfterContent -->
	<?php endif; ?>
	<div class="visualClear"></div>
	<!-- debughtml -->
	<?php $this->html( 'debughtml' ); ?>
	<!-- /debughtml -->
</div>
<!-- /bodyContent -->

<?php
	}

	/**
	 * Render one or more navigations elements by name, automatically reveresed
	 * when UI is in RTL mode
	 *
	 * @param $elements array
	 */
	protected function renderNavigation( $elements ) {

		// If only one element was given, wrap it in an array, allowing more
		// flexible arguments
		if ( !is_array( $elements ) ) {
			$elements = array( $elements );
		// If there's a series of elements, reverse them when in RTL mode
		} elseif ( $this->data['rtl'] ) {
			$elements = array_reverse( $elements );
		}
		// Render elements
		foreach ( $elements as $name => $element ) {
			echo "\n<!-- {$name} -->\n";
			switch ( $element ) {
				case 'NAMESPACES':
?>
<div class="<?php if ( count( $this->data['namespace_urls'] ) == 0 ) echo 'emptyPortlet'; ?>">
	<h5><?php $this->msg( 'namespaces' ) ?></h5>
	<ul<?php $this->html( 'userlangattributes' ) ?>>
		<?php foreach ( $this->data['namespace_urls'] as $link ): ?>
			<li <?php echo $link['attributes'] ?>><span><a href="<?php echo htmlspecialchars( $link['href'] ) ?>" <?php echo $link['key'] ?>><?php echo htmlspecialchars( $link['text'] ) ?></a></span></li>
		<?php endforeach; ?>
	</ul>
</div>
<?php
				break;
				case 'VARIANTS':
?>
<div class="<?php if ( count( $this->data['variant_urls'] ) == 0 ) echo 'emptyPortlet'; ?>">
	<h4>
	<?php foreach ( $this->data['variant_urls'] as $link ): ?>
		<?php if ( stripos( $link['attributes'], 'selected' ) !== false ): ?>
			<?php echo htmlspecialchars( $link['text'] ) ?>
		<?php endif; ?>
	<?php endforeach; ?>
	</h4>
	<h5><?php $this->msg( 'variants' ) ?></h5>
	<ul>
		<?php foreach ( $this->data['variant_urls'] as $link ): ?>
			<li<?php echo $link['attributes'] ?>><a href="<?php echo htmlspecialchars( $link['href'] ) ?>" lang="<?php echo htmlspecialchars( $link['lang'] ) ?>" hreflang="<?php echo htmlspecialchars( $link['hreflang'] ) ?>" <?php echo $link['key'] ?>><?php echo htmlspecialchars( $link['text'] ) ?></a></li>
		<?php endforeach; ?>
	</ul>
</div>
<?php
				break;
				case 'ACTIONS':
				$actions = array_merge( $this->data['view_urls'], $this->data['action_urls'] );
?>
<div <?php if ( count( $actions ) == 0 ) echo 'class="emptyPortlet"'; ?>>
	<h5><?php $this->msg( 'actions' ) ?></h5>
	<ul<?php $this->html( 'userlangattributes' ) ?>>
		<?php foreach ( $actions as $link ): ?>
			<li<?php echo $link['attributes'] ?>><a href="<?php echo htmlspecialchars( $link['href'] ) ?>" <?php echo $link['key'] ?>><?php echo htmlspecialchars( $link['text'] ) ?></a></li>
		<?php endforeach; ?>
	</ul>
</div>
<?php
				break;
				case 'PERSONAL':
?>
<div class="<?php if ( count( $this->data['personal_urls'] ) == 0 ) echo 'emptyPortlet'; ?>">
	<h5><?php $this->msg( 'personaltools' ) ?></h5>
	<ul<?php $this->html( 'userlangattributes' ) ?>>
<?php			foreach( $this->getPersonalTools() as $key => $item ) { ?>
		<?php echo $this->makeListItem( $key, $item ); ?>

<?php			} ?>
	</ul>
</div>
<?php
				break;
			}
			echo "\n<!-- /{$name} -->\n";
		}
	}
}
