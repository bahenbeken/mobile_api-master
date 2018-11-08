<?php

	/** Force to display error */
	ini_set("display_errors", "on");

	/** Configurations */
	$user = "root";
	$pass = "p@ssw0rd";
	$host = "localhost";
	$database_ret = "sanitasRet";
	$database_dist = "sanitasDist";
    $timezone = "+07:00";

    /** Initialize PDO as $db */
    
	$db['ret'] = new PDO('mysql:host='. $host .';dbname='. $database_ret .';charset=utf8', $user, $pass, 
        [PDO::MYSQL_ATTR_INIT_COMMAND =>"SET time_zone = '".$timezone."'"]);
        
	$db['dist'] = new PDO('mysql:host='. $host .';dbname='. $database_dist .';charset=utf8', $user, $pass, 
        [PDO::MYSQL_ATTR_INIT_COMMAND =>"SET time_zone = '".$timezone."'"]);

	/** Set default fetch as FETCH_ASSOC to use column name as index */
	$db['ret']->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
	$db['dist']->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    
    // $db['ret']->exec("SET time_zone = '{$timezone}'");
    // $db['dist']->exec("SET time_zone = '{$timezone}'");
?>
