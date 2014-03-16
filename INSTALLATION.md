Installation
============

Operating System Requirements
-----------------------------

These installation instructions have been verified against a Ubuntu 12.04 LTS 64-bit.
While these instructions will probably work for any apt-get based Linux distribution,
it is best to stick with Ubuntu 12.04 LTS whenever possible.

Software Overview
-----------------

The web server running this application must have a MySQL server installed or else have
access to a MySQL server on another network (these instructions give steps to set up
the former). Apache 2.2 will be used as the web server, and PHP will be needed to run
the application. Git will also be needed for obvious reasons.

Installing the Application
--------------------------

Run the following commands to install most of the software dependencies required by
the application:

    # Adds the PPA to install that makes the proper version of PHP available to
    # the apt-get package manager. You'll have to hit enter to confirm adding the
    # PPA.
    sudo add-apt-repository ppa:ondrej/php5-oldstable

    # Update apt-get's available package list .
    sudo apt-get update

    # Install major project dependencies. Note that the installation of mysql-server
    # prompts the user for the root password of the MySQL server on the server. Pick
    # something secure and keep the password safe.
    sudo apt-get install php5 php5-curl php5-mcrypt php5-mysqlnd mysql-client mysql-server apache2 git curl

    # Now clone the repository in a directory that you will point the web server to
    # later.
    cd /var/www/ && sudo git clone https://github.com/CSC495-2014/TeamworkEnglewoodGit.git

    # Now we should install Composer so we can install the library dependencies for
    # the application.
    cd /tmp/
    curl -sS https://getcomposer.org/installer | php
    sudo mv composer.phar /usr/local/bin/composer

    # Use Composer to install the application's PHP library dependencies.
    cd /var/www/TeamworkEnglewoodGit/ && sudo composer install

    # Now we need to create a database and user for the application to use. Please
    # use a different username/password combination from below. The database name
    # used here is "TeamworkEnglewoodGit". Feel free to change it to anything if
    # you desire.
    mysql -u root -p
    mysql> CREATE DATABASE `TeamworkEnglewoodGit`;
    mysql> GRANT ALL ON `TeamworkEnglewoodGit`.* TO 'user'@'localhost' IDENTIFIED BY 'password';
    mysql> FLUSH PRIVILEGES;
    mysql> exit

    # The next step is to give the application the information about the database
    # we just created. Find the line section that looks like the section below and
    # replace the details with your own. You should only need to modify the values
    # for 'database', 'username', and 'password'.
    #
    # 'mysql' => array (
    #   'driver' => 'mysql',
    #   'host' => 'localhost',
    #   'database' => 'database'
    #   'username' => 'root'
    #   'password' => '',
    #   'charset' => 'utf8',
    #   'collation' => 'utf8_unicode_ci',
    #   'prefix' => '',
    # ),
    sudo nano app/config/database.php

    # Now to let the application set the database up the way it wants.
    sudo php artisan migrate

    # Before we point the web server to the application, we must give ownership of
    # the application files to the web server.
    sudo chown -R www-data:www-data /var/www/TeamworkEnglewoodGit/

    # Now to configure the web server. Run the following two commands to enable
    # modules required by the application.
    sudo a2enmod php5
    sudo a2enmod rewrite

    # ...and disable the default apache virtual host.
    sudo a2dissite default


There is now only one thing left to do, and that's to configure the virtual host for
the server so the server can provide the proper content to the desired domain name.
To do this, create a file called `TeamworkEnglewoodGit.conf` in
`/etc/apache2/sites-available/` and place the following contents inside, making sure
to replace `subdomain.teamworkenglewood.org` with the desired domain:

    <VirtualHost *:80>
        ServerName subdomain.teamworkenglewood.org
        DocumentRoot "/var/www/TeamworkEnglewoodGit/public"
        <Directory "/var/TeamworkEnglewoodGit/public">
            AllowOverride All
            Order Allow,Deny
            Allow from all
            # Uncomment the following line if running Apache 2.4 instead of 2.2.
            # Require all granted
        </Directory>
    </VirtualHost>

After you save and exit the file, run `sudo a2ensite TeamworkEnglewoodGit` followed by
`sudo service apache2 restart`. The application should now be accessible by the
domain specified in the `ServerName` directive above.
