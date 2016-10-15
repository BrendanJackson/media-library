<?php
function get_catalog_count($category = null, $search = null){
	$category = strtolower($category);
	include("connection.php");

	try {
		$sql = "SELECT COUNT(media_id) FROM Media";
		if (!empty($search)){
			$result = $db->prepare(
				$sql
				. " WHERE title LIKE ?" 
			);
			$result->bindValue(1,"%".$search."%",PDO::PARAM_STR);
// Search block
		} else if (!empty($category)){
			$result = $db->prepare(
				$sql
				. " WHERE LOWER(category) = ?"
			);
			$result->bindParam(1,$category,PDO::PARAM_STR);
		} else {
		$result = $db->prepare($sql);
		} 
		$result->execute();
	} catch (Exception $e) {
		echo "bad query";
	}
$count = $result->fetchColumn(0);
return $count;
}

function full_catalog_array($limit = null, $offset = 0){
	include("connection.php"); //connects to database

	try{
		$sql = "
		SELECT media_id,title,category,img 
		FROM Media
		ORDER BY
		REPLACE(
			REPLACE(
				REPLACE(title,'The ',''),
				'An ',
				''
			),
			'A ',
			''
		)";//gets info from Media table, names it $results
		
		if(is_integer($limit)){
			$results = $db->prepare($sql . " LIMIT  ? OFFSET ?");
			$results->bindParam(1,$limit,PDO::PARAM_INT);
			$results->bindParam(2,$offset,PDO::PARAM_INT);
		} else {
			$results = $db->prepare($sql);
		}
			$results->execute();
		

	} catch (Exception $e) {
		echo "Unable to retrieve results";
		exit;
	}

	$catalog = $results->fetchAll(); //gets all data from $results as an array[] and saves it as $catalog
	return $catalog; //return ends function and returns $catalog as the result of the function
}

function category_catalog_array($category,$limit = null, $offset = 0){
	include("connection.php"); //connects to database
	$category = strtolower($category);
	try{
		
		$sql = "
		SELECT media_id,title,category,img 
		FROM Media
		WHERE LOWER(category) = ?
		ORDER BY
		REPLACE(
			REPLACE(
				REPLACE(title,'The ',''),
				'An ',
				''
			),
			'A ',
			''
		)";//gets info from Media table, names it $results
		if(is_integer($limit)){
			$results = $db->prepare($sql . " LIMIT  ? OFFSET ?");
			$results->bindParam(1,$category,PDO::PARAM_STR);
			$results->bindParam(2,$limit,PDO::PARAM_INT);
			$results->bindParam(3,$offset,PDO::PARAM_INT);
		} else {
			$results = $db ->prepare($sql);
			$results->bindParam(1,$category,PDO::PARAM_STR); //1=?
		}

		$results->execute();
	} catch (Exception $e) {
		echo "Unable to retrieve results";
		exit;
	}

	$catalog = $results->fetchAll(); //gets all data from $results as an array[] and saves it as $catalog
	return $catalog; //return ends function and returns $catalog as the result of the function
}

function search_catalog_array($search,$limit = null, $offset = 0){
	include("connection.php"); //connects to database
	
	try{
		
		$sql = "
		SELECT media_id,title,category,img 
		FROM Media
		WHERE title LIKE ? /*LIKE is not case sensitive!*/
		ORDER BY
		REPLACE(
			REPLACE(
				REPLACE(title,'The ',''),
				'An ',
				''
			),
			'A ',
			''
		)";//gets info from Media table, names it $results
		if(is_integer($limit)){
			$results = $db->prepare($sql . " LIMIT  ? OFFSET ?");
			$results->bindValue(1,'%'.$search.'%',PDO::PARAM_STR);
			$results->bindParam(2,$limit,PDO::PARAM_INT);
			$results->bindParam(3,$offset,PDO::PARAM_INT);
		} else {
			$results = $db ->prepare($sql);
			$results->bindValue(1,'%'.$search.'%',PDO::PARAM_STR); //1=?
		}

		$results->execute();
	} catch (Exception $e) {
		echo "Unable to retrieve results";
		exit;
	}

	$catalog = $results->fetchAll(); //gets all data from $results as an array[] and saves it as $catalog
	return $catalog; //return ends function and returns $catalog as the result of the function
}

function random_catalog_array(){
	include("connection.php"); //connects to database

	try{
		$results = $db->query("
		SELECT media_id,title,category,img 
		FROM Media
		ORDER BY RAND() 
		LIMIT 4");//gets info from Media table,only 4 results and at random.		
	} catch (Exception $e) {
		echo "Unable to retrieve results";
		exit;
	}

	$catalog = $results->fetchAll(); //gets all data from $results as an array[] and saves it as $catalog
	return $catalog; //return ends function and returns $catalog as the result of the function
}

function single_item_array($id){ //details pg
	include("connection.php"); //connects to database

	try{	//gets this info from database
		$results = $db->prepare(" 
			SELECT Media.media_id,title,category,img,format,year,genre,publisher,isbn 
			FROM Media
			JOIN Genres ON Media.genre_id = Genres.genre_id
			LEFT OUTER JOIN Books ON Media.media_id = Books.media_id
			WHERE Media.media_id = ? /* 1)creates PDO statement object;;optionally retrieves data from the Books table if data exists*/
		");
		$results->bindParam(1,$id,PDO::PARAM_INT);/*2)calls bindParam method on $results, binds $id # to the 1st "?" where media_id column is on the Media Table*/
		$results->execute();/*3)calls execution method on results, executes query, loads results into $results object*/
	} catch (Exception $e) {
		echo "Unable to retrieve results";
		exit;
	}

	$item = $results->fetch();/*4)retrieves item info for the product that matches the id,loads it into an $item variable*/
	if (empty($item)) return $item;
	try{	//gets this info from database
		$results = $db->prepare(" 
			SELECT fullname, role 
			FROM Media_People
			JOIN People ON Media_People.people_id = People.people_id
			WHERE Media_People.media_id = ? /* 1)creates PDO statement object;;optionally retrieves data from the Books table if data exists*/
		");
		$results->bindParam(1,$id,PDO::PARAM_INT);/*2)calls bindParam method on $results, binds $id # to the 1st "?" where media_id column is on the Media Table*/
		$results->execute();/*3)calls execution method on results, executes query, loads results into $results object*/
	} catch (Exception $e) {
		echo "Unable to retrieve results";
		exit;
	}
	while ($row = $results->fetch(PDO::FETCH_ASSOC)){
		$item[$row['role']][] = $row['fullname']; //https://teamtreehouse.com/library/integrating-php-with-databases/using-relational-tables/fetching-in-a-while-loop
	}
	return $item; /*returns $item back to the function call*/

}

function genre_array($category = null){
	$category = strtolower($category);
	include("connection.php");
	try{
			$sql = 
			"SELECT genre, category" 
			. " FROM Genres " // join Genres
			. " JOIN Genre_Categories " // with Genre_Categories
			. "  ON Genres.genre_id = Genre_Categories.genre_id "; // join Genres with Genre_Categories @ genre_id column
		if (!empty($category)){
			$results = $db->prepare($sql 
			. " WHERE LOWER(category) = ?"
			. " ORDER BY genre"); //prepare SQL QUERY ordered by genre, define as $results
			$results->bindParam(1,$category,PDO::PARAM_STR);
		} else {
			$results = $db->prepare($sql . " ORDER BY genre"); //prepare SQL QUERY ordered by genre, define as $results
		}
			$results->execute();
	} catch (Exception $e){ 
		echo "bad query!";
	}
	$genres =  array();
	while($row = $results->fetch(PDO::FETCH_ASSOC)){
		$genres[$row["category"]][] = $row["genre"];
	}
return $genres;
}

function get_item_html($item){
	$output = "<li><a href='details.php?id="
        		. $item['media_id'] . "'><img src='" 
				. $item["img"] . "' alt='" 
				. $item["title"] . "'/>" 
				. "<p>View Details</p>"
				. "</a></li>";
	return $output;
				
}


function array_category($catalog,$category) {
	/*if ($category == null){ //catalog.php without books,movies, or music
		return array_keys($catalog);
	}
	merged IF statements to OR*/
	$output = array();

	foreach ($catalog as $id => $item) {
		if ($category == null OR strtolower($category) == strtolower($item["category"]) ) {
			$sort = $item["title"];
			$sort = ltrim($sort, "The "); //sorts titles according to alphabetical order excluding "the "
			$sort = ltrim($sort, "A ");  //sorts titles according to alphabetical order excluding "a "
			$sort = ltrim($sort, "An ");  //sorts titles according to alphabetical order excluding "an "
			$output[$id] = $sort;
		}
	}
	
	asort($output);
	return array_keys($output);
}

