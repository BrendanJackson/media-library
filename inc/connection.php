<?php

//THIS BLOCK CONNECTS TO THE DATABASE, IF IT CANNOT CONNECT IT 'CATCHES' THE ERROR AND SENDS USER A MESSAGE
try{
	//new(lets php know we're creating an object) 'PDO(driver == 'connects one thing to another') == class'
	$db = new PDO("mysql:host=localhost;dbname=mediaLibrary;port=3306","root","evanetra");
	//mysql format:$db = new PDO("mysql:host=localhost;dbname=DATABASE_NAME;port=3306","USERNAME","PASSWORD");
	//in The PDO arguments you list the database '(sqlite,mysql,etc)'.__DIR__.'/path' then the path to the database
	// .__DIR__. 'tells the pc to look in this directory for the database'
	$db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
} catch (Exception $e){
	echo "Unable to connect";
	echo $e->getMessage();
	exit;
}

//THIS BLOCK QUERIES THE DATABASE, IF IT CANNOT CONNECT IT 'CATCHES' THE ERROR AND SENDS USER A MESSAGE
/*
try{
	$results = $db->query("SELECT title, category FROM Media");
	echo "Retrieved Results";
} catch (Exception $e) {
	echo "Unable to retrieve results";
	exit;
}

$catalog = $results->fetchAll(PDO::FETCH_ASSOC);
*/