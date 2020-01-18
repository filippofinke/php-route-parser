<?php

use FilippoFinke\RouteParser;

require __DIR__ . '/../vendor/autoload.php';


$tests = array(
    array("/users/filippofinke","/users/{username}"),
    array("/post/1/10", "/post/{post_id}/{comment_id}"),
    array("/articles", "/articles"),
    array("/users","/usersss"),
    array("/home", "/{path:[A-Za-z]+}"),
    array("/home/as", "/{path:[A-Za-z]+}/asd"),
    array("/","/{name}")

);

$start = microtime(true);


foreach($tests as $test) {
    $url = $test[0];
    $pattern = $test[1];
    echo $url." ".$pattern.PHP_EOL;
    $parser = new RouteParser($pattern);
    var_dump($parser->parse($url));
}

echo microtime(true) - $start.PHP_EOL;