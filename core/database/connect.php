<?php
$connect_error = 'sorry we are experiencing connection issue';
mysql_connect('localhost', 'root', 'root');// or die($connection->error);
mysql_select_db('lr') or die($connect_error);
