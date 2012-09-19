<VirtualHost *:80>
	ServerName docs.jquery.com
	DocumentRoot /var/www/docs.jquery.com/public_html
	ServerAlias docs.jquery.com

	ErrorLog /var/log/apache2/docs.jquery.com.error.log
	CustomLog /var/log/apache2/docs.jquery.com.access.log custom

	<Directory "/var/www/docs.jquery.com/public_html">
		Allow from all
		Options +Indexes +FollowSymLinks

		# For the list of redirects from pages that now live somewhere
		# else, see public_html/.htaccess

		# MediaWiki configuration
		# See also: https://www.mediawiki.org/wiki/Manual:Short_URL/Apache

		RewriteEngine On

		# Short url for wiki page from the root
		RewriteCond %{DOCUMENT_ROOT}%{REQUEST_URI} !-f
		RewriteCond %{DOCUMENT_ROOT}%{REQUEST_URI} !-d
		RewriteRule ^(.*)$ %{DOCUMENT_ROOT}/mw/index.php [L]

		## Redirect / to the Main Page
		RewriteRule ^/*$ %{DOCUMENT_ROOT}/mw/index.php [L]
	</Directory>
</VirtualHost>



<VirtualHost *:80>
	ServerName stage.docs.jquery.com
	DocumentRoot /var/www/stage.docs.jquery.com/public_html
	ServerAlias stage.docs.jquery.com

	ErrorLog /var/log/apache2/stage.docs.jquery.com.error.log
	CustomLog /var/log/apache2/stage.docs.jquery.com.access.log custom

	<Directory "/var/www/stage.docs.jquery.com/public_html">
		Allow from all
		Options +Indexes +FollowSymLinks

		# For the list of redirects from pages that now live somewhere
		# else, see public_html/.htaccess

		# MediaWiki configuration
		# See also: https://www.mediawiki.org/wiki/Manual:Short_URL/Apache

		RewriteEngine On

		# Short url for wiki page from the root
		RewriteCond %{DOCUMENT_ROOT}%{REQUEST_URI} !-f
		RewriteCond %{DOCUMENT_ROOT}%{REQUEST_URI} !-d
		RewriteRule ^(.*)$ %{DOCUMENT_ROOT}/mw/index.php [L]

		## Redirect / to the Main Page
		RewriteRule ^/*$ %{DOCUMENT_ROOT}/mw/index.php [L]
	</Directory>
</VirtualHost>
