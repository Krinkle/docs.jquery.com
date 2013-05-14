# Setup

## www

Symlink the `/var/www/docs.jquery.com` directory to the `www` directory in this repository. Then set up the following sub tree:

* mediawiki-core (`git clone https://gerrit.wikimedia.org/r/p/mediawiki/core.git`)
* mediawiki-extensions
 * ParserFunctions (`git clone https://gerrit.wikimedia.org/r/p/mediawiki/extensions/ParserFunctions.git`)
 * DynamicPageList (`svn co https://svn.wikimedia.org/svnroot/mediawiki/trunk/extensions/DynamicPageList`)
 * Interwiki (`git clone https://gerrit.wikimedia.org/r/p/mediawiki/extensions/Interwiki.git`)
* mw-cache (`chmod 777`)

Also create a `PrivateSettings.php` file in the `www` directory, and make sure it has the following (fill in the blanks):

```php
<?php

# Database settings
$wgDBuser = '***';
$wgDBpassword = '***';

# Site keys
$wgSecretKey = '***'; # Random 64-char hash
$wgUpgradeKey = '***'; # Random 16-char hash

```

If this is a new installation (no existing database) make sure to run the MediaWiki installer (follow instructions from the web interface). Either way, overwrite  `mediawiki-core/LocalSettings.php` with the following (adjust path if needed):

```php
<?php

require_once( dirname( __DIR__ ) . '/InitialiseSettings.php' );
```

## httpd
Add `httpd/docs.jquery.com` conf file to `/etc/apache2/sites-enabled`.
