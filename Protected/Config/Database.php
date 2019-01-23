<?php

return array(
    "mysql" => array(
        "read"  => array(
	        "host"=> "127.0.0.1",
	        "user"=>"root",
	        "password"=>"zhuan1234",
	        "database"=>"viet",
	        "prefix"=>"self_"
        ),
        "write" => array(
	        "host"=> "127.0.0.1",
	        "user"=>"root",
	        "password"=>"zhuan1234",
	        "database"=>"viet",
	        "prefix"=>"self_"
        ),
    ),
    "redis" => array(
        "read"  => array(),
        "write" => array(),
    ),
);