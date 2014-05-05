## Laravel PHP Framework

# Mac OS X php

Mac OS X php does not have mcrypt extesion. To ease life, download and install php 5.5:
- curl -s http://php-osx.liip.ch/install.sh | bash -s 5.5
add php to the path:
- if using bash:
  in the end of ~/.profile add this line:
  export PATH=/usr/local/php5/bin:$PATH
- if using zsh, in the end of ~/.zshrc add this line:
  export PATH=/usr/local/php5/bin:$PATH

# Create mysql DB
Create a mysql UTF-8 db and user, assign all the privileges to that user.
Write the host, user, pass and dbname in app/config/database.php, in connections => mysql

# install php/js dependencies

- curl -sS https://getcomposer.org/installer | php
- php composer.phar install
- php artisan migrate
- brew install npm
- npm install -g bower
- bower install

# running a server
Either you need to install this to a vhost in apache, or just run:
- php artisan serve

# cordova

install cordova:
- sudo npm install -g cordova
- sudo npm install -g ios-sim

Build project with:
- cd cordova
- cordova build ios

Build for android:
first set adb directory in cordova/local.properties and cordova/CordovaLib/local.properties

- cd cordova
- cordova build android

Run with:
- cordova emulate ios
- cordova emulate android
