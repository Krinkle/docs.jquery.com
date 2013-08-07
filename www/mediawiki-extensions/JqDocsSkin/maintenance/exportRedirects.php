<?php
/**
 * Export all redirects from the wiki into a map from page title to url.
 *
 * Example:
 *
 *     # Export interwiki redirects into NGINX config, preferring HTTPS where possible
 *     $ php exportRedirects.php --wiki alphawiki --expand-https --format=nginx --external
 *
 * @author Timo Tijhof, 2013
 */

$IP = getenv( 'MW_INSTALL_PATH' );
if ( $IP === false ) {
	$IP = __DIR__  . '/../../..';
}
require_once $IP . '/maintenance/Maintenance.php';

class ExportRedirectsScript extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->mDescription = 'Export redirects into plain text map from title to url';
		$this->addOption( 'expand-https', 'Expand protocol-relative urls (if any) to HTTPS instead of HTTP', false );
		$this->addOption( 'external', 'Only include redirects with external targets', false );
		$this->addOption( 'verbose', 'Be verbose in output (include non-redirects with target "null")', false );
		$this->addOption( 'format', 'One of "plain", "json", "apache" or "nginx". The latter two imply --interwiki.' );
	}

	public function execute() {
		$db = wfGetDB( DB_MASTER );
		$res = $db->select( 'page', '*', '', __METHOD__ );
		if ( !$res ) {
			return;
		}
		foreach ( $res as $row ) {
			$title = Title::newFromRow( $row );
			if ( !$title->isRedirect() ) {
				$this->putNonredirect( $title, 'Title is not a redirect' );
				continue;
			}
			$page = WikiPage::factory( $title );
			$revision = $page->getRevision();
			if ( !$revision ) {
				$this->putNonredirect( $title, 'Page has no revisions' );
				continue;
			}
			$content = $revision->getContent();
			if ( !$content ) {
				$this->putNonredirect( $title, 'Revision has no content' );
				continue;
			}
			$redirectTarget = $content->getUltimateRedirectTarget();
			$this->putRedirect( $title, $redirectTarget );
		}
	}

	/**
	 * @param Title $title
	 * @param Title|string $target
	 */
	protected function putRedirect( Title $title, $target ) {
		// Don't show non-external titles if we're show this if we're not filtering for interwiki redirects only
		if ( is_string( $target ) || $target->isExternal() || !$this->getOption( 'external', false ) ) {
			$this->formatPut( $title, $target );
		}
	}

	/**
	 * @param Title $title
	 * @param |string $reason
	 */
	protected function putNonredirect( Title $title, $reason ) {
		if ( $this->getOption( 'verbose', false ) ) {
			$this->formatPut( $title, null, $reason );
		}
	}

	/**
	 * @param Title $from
	 * @param Title|string|null $to
	 * @param string [$comment]
	 */
	protected function formatPut( Title $from, $to, $comment = null ) {
		if ( $to !== null && !is_string( $to ) ) {
			$to = $to->getFullURL( '', false,
				$this->getOption( 'expand-https', false ) ? PROTO_HTTPS : PROTO_HTTP
			);
		}
		switch ( $this->getOption( 'format', 'plain' ) ) {
			case 'plain':
				if ( $to === null ) {
					$to = 'null';
				}
				$line = "* [[$from]] | $to ";
				if ( $comment !== null ) {
					$line .= "# $comment";
				}
				break;
			case 'json':
				$line = FormatJson::encode( array(
					// {string} from Full page name
					'from' => "$from",
					// {null|string} to Full url
					'to' => $to,
				) );
				break;
			case 'nginx':
				if ( $to === null ) {
					$line = "# [[$from]]: $comment";
				} else {
					// rewrite ^{from_path} {to_full_url} permanent;
					$line = 'rewrite ^' . preg_quote( $from->getLocalURL() ) . '$ ' . $to . ' permanent;';
				}
				break;
			case 'apache':
				if ( $to === null ) {
					$line = "# [[$from]]: $comment";
				} else {
					// rewrite ^{from_path} {to_full_url} permanent;
					$line = 'Redirect 301 ' . $from->getLocalURL() . ' ' . $to;
				}
				break;
			default:
				$line = '# Unknown format';
				break;
		}

		$this->output( "$line\n" );
	}
}

$maintClass = 'ExportRedirectsScript';
require_once RUN_MAINTENANCE_IF_MAIN;
