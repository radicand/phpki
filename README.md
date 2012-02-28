NOTICE:
This application is designed to be an easy to use "certificate factory"
requiring minimum human intervention to administer.  It is intended for
use within a trusted INTRAnet for the creation and management of x.509
e-mail digital certificates by departmental managers.  IT IS NOT INTENDED
FOR USE OVER THE INTERNET.

This application stores private keys within a sub-directory, making them
potentially susceptible to compromise.  Extra care has been taken in the
design of this application to protect the security of your certificates,
on the condition that you INSTALL IT AS THE ROOT USER.  However, no
software is 100% secure, AND NO PROMISES OR GUARANTEES ARE MADE!


REQUIREMENTS:
PHPki was developed and tested on Mandrake and RedHat GNU/Linux systems.
It requires the Apache web server, PHP, and OpenSSL.  I do not as yet
know if it is sensitive to particular versions of those resources.

Your web server must be configured with "AllowOverride All" effective
in the directory where PHPki is installed.  If you don't know what this
means, then go Googling.

With PHP 5, make sure register_long_arrays in turn on in php.ini or .htaccess.
Otherwise, you may get an error similar to "method=post was not found on this se
rver.

INSTALLATION:
Make sure "AllowOverride All" is set in your Apache configuration file.
This is necessary because PHPki uses .htaccess files to implement HTTP
authentication and to enforce file access restrictions.  If you must 
change this Apache setting, don't forget to restart Apache.

Unpack the PHPki tarball onto your web server. For example:

	cp phpki.tar.gz /var/tmp
	cd /var/www/html
	tar -xzvf /var/tmp/phpki.tar.gz

To configure the certificate authority and create your root certificate,
point your browser to where you unpacked PHPki. For example:

	http://www.domain.com/phpki/

Enter all the requested information into the web form and click the Submit 
button.  If all goes well, you should see a page telling you that your
root certificate has been created. 

To access the PHPki public content menu, point your browser to your
PHPki installation (i.e. http://www.domain.com/phpki/).  To access the
PHPki certificate management menu, point your browser to the "ca"
directory under your PHPki installation (i.e. https://www.domain.com/phpki/ca/).
You must use SSL (https://) to access the PHPki certificate management
menu if you have secured the application using the secure.sh script.


SECURITY & USERS:
From a root user shell prompt, run the "secure.sh" shell script in this
directory to set more restrictive Unix file permissions, and to create 
the Apache .htaccess files which are necessary to force SSL access, HTTP 
authentication, and directory access restrictions.  If you don't do this
you will likely be extremely screwed!  Don't say you weren't warned.

The secure.sh script will attempt to create a file for your user list
and passwords.  If it fails in that attempt, you will have to use Apache's
htpasswd utility to manually create a "phpkipasswd" file in the location 
you specified during setup.

	htpasswd -cm /var/www/phpkipasswd username

Normal users may only manage the certificates they create.  Administrators
can manage all certificates.  The default administrator account is 
"pkiadmin".  The secure.sh script will attempt to add this user to your 
phpkipasswd file when it is first created.  Other users can be made 
administrators by carefully editing the $PHPki_admins assignment in 
config/config.php under your certificate store directory.

You may add additional users and change passwords using your browser after
you have successfully installed PHPki and created your phpkipasswd file with
at least one user. Point your browser to http://www.domain.com/phpki/admin/.  


UPGRADING:
Install and configure as if it were a first time installation (see above).
Be sure to specify the same root certificate password and user password file 
location you used with the previous version.

From the old installation, copy all certificates, crls, and user defaults
to the store directory specified during setup.

	rm -fr store_directory/CA
	cp -v --archive oldphpki/CA   store_directory
	cp -v --archive oldphpki/config/user-*.php  newphpki/ca/config

These upgrade instructions have not been thoroughly tested and may be 
incomplete.  Please be sure to fully backup your old PHPki installation before 
upgrading.


CONTACT:
Post all correspondence to the PHPki project page
http://sourceforge.net/projects/phpki/

---END OF FILE---
