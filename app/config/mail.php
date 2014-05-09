<?php

return array(

    'driver' => 'smtp',

    'host' => 'smtp.gmail.com',

    'port' => 587,

    'from' => array('address' => 'spotmyfriends@zipleen.com', 'name' => 'Spot My Friends'),

    'encryption' => 'tls',

    'username' => 'spotmyfriends',

    'password' => 'password',

    'sendmail' => '/usr/sbin/sendmail -bs',

    'pretend' => false,

);