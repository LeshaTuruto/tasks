<?php
require 'rb.php';;

R::setup( 'mysql:host=eu-cdbr-west-03.cleardb.net;dbname=heroku_b6f63681ed16789',
    'b909c45ce9494e', '91ad69db' ); //for both mysql or mariaDB
session_start();