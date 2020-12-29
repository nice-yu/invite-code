<?php

require "./vendor/autoload.php";

$app = new \TimAutumnWind\InvitaionCode(6);

echo($app->decode(1123));

echo "<hr/>";

echo($app->encode($app->decode(1123)));



