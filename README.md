# Setup

## www

Symlink the `/var/docs.jquery.com` directory to the `www` directory of this repository. Then set up the following sub tree:

* mediawiki-core (`git clone https://gerrit.wikimedia.org/r/p/mediawiki/core.git`)
* mediawiki-extensions
 * ParserFunctions (`git clone https://gerrit.wikimedia.org/r/p/mediawiki/extensions/ParserFunctions.git`)
 * DynamicPageList (`svn co https://svn.wikimedia.org/svnroot/mediawiki/trunk/extensions/DynamicPageList/`)
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

The first time mediawiki-core is cloned, make sure to run the installer (follow instructions from the web interface). Then overwrite  `mediawiki-core/LocalSettings.php` with this (adjust path if needed):

```php
<?php

require_once( '/home/jqadmin/docs.jquery.com/www/InitialiseSettings.php' );
```

## httpd
Add `httpd/docs.jquery.com` conf file to `/etc/apache2/sites-enabled`.
