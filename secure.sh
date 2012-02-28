#! /bin/bash

owner="`id -nu`"

cat <<EOM

This application is designed to be an easy to use "certificate factory"
requiring minimum human intervention to administer.  It is intended for
use within a trusted INTRAnet for the creation and management of x.509
e-mail digital certificates by departmental managers.  IT IS NOT INTENDED
FOR USE OVER THE INTERNET.

This application stores private keys within a sub-directory, making them
potentially susceptible to compromise.  Extra care has been taken in the
design of this application to protect the security of your certificates,
on the condition that you INSTALL IT AS THE ROOT USER.  However, no
software is 100% secure.  

EOM

read -p "Enter the location of your PHPki password (i.e. /etc/phpkipasswd): " passwd_file

echo

if [ ! -f "$passwd_file" ] 
then
    echo "The file you specified does not yet exist."
    echo "Let's create it and add your first user."
    echo
    read -p "Enter a user id: " user_id

    echo "Creating the $user_id user account..."

    htpasswd -c -m "$passwd_file" "$user_id" || exit

    echo "Creating the administrator account..."
	echo "See the README file for more information about the"
	echo "'pkiadmin' user."
    htpasswd -m "$passwd_file" 'pkiadmin' || exit
fi

echo

if [ ! "${owner}_" = "root_" ] 
then
	cat <<EOM
YOU ARE NOT LOGGED ON AS ROOT!

If you choose to proceed anyway, and you plan to make this application
available over the Internet, you increase the risk of compromising the
security of your certifcates and your server.  

This script may not run correctly if you are not the ROOT user.

EOM
fi

echo -n "Enter the user ID your web server runs as [apache]: " ; read x
echo
echo -n "Enter the group ID your web server runs as [apache]: " ; read z
echo
echo "Enter the IP or subnet address [192.168.0.0/16] which will be allowed access"
echo -n "to the user admin module in under ./admin: " ; read y

user=${x:-apache}
group=${z:-apache}
subnet=${y:-'192.168.0.0/16'}
subnet="${subnet} 127.0.0.1"

echo "Working..."

for i in ./include
do
	echo "deny from all" >$i/.htaccess
done 

cat <<EOS >> ./ca/.htaccess
AuthName "Restricted Area"
AuthType Basic
AuthUserFile "$passwd_file"
require valid-user
SSLRequireSSL

EOS

cat <<EOS > ./admin/.htaccess 
AuthName "Restricted Area"
AuthType Basic
AuthUserFile "$passwd_file"
require valid-user
SSLRequireSSL
Order Allow,Deny
Allow from $subnet

EOS

# Start with web server getting read-only access to everything.
# Directories have sticky bits set.
find .           -exec chown $owner:$group {} \;
find . ! -type d -exec chmod 640 {} \;
find .   -type d -exec chmod 3750 {} \;

echo "Done."
