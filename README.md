# C4985-GPS-webserver

A java TCP webserver website that listens and plots current location data from
android devices on Google Maps.

In order to use this website you will need to set up a Linux Server with
the LAMP (Linux, Apache, MySQL, PHP) model installed along with the JDK for
compiling the networking code.

Configure your firewall to allow an open port for communication.

Place these files into the default Apache Linux location /var/www/html/

You will need to set up the database for the user login.  Please read function.php
and modify the database values for your MySQL installation.

Compile tcps.java:
javac -Xlint tcps.java

Run the Client server:
java tcps [portNumber]
