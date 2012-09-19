<VirtualHost *:80>
ServerName docs.jquery.com
DocumentRoot /var/www/docs.jquery.com/public_html
ServerAlias docs.jquery.com
	
	ErrorLog /var/log/apache2/docs.jquery.com.error.log
	CustomLog /var/log/apache2/docs.jquery.com.access.log custom
	
<Directory "/var/www/docs.jquery.com/public_html">
allow from all
Options +Indexes

RewriteEngine On

RewriteRule ^Ajax$ http://api.jquery.com/category/ajax/ [R=301,L]

RewriteCond %{REQUEST_FILENAME} -f [OR]
RewriteCond %{REQUEST_FILENAME} -d
RewriteRule ^.*$ - [S=51]

RewriteRule ^action/([a-z]*)/(.*)$ /index.php?action=$1&title=$2 [L,QSA]

RewriteRule ^(.*)$ /index.php?title=$1 [L,QSA]
</Directory>
</VirtualHost>
