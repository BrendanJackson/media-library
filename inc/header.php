<html>
<head>
	<title><?php echo $page_title; ?></title>
	<link rel="stylesheet" href="style.css" type="text/css">
</head>
<body>

	<div class="header">

		<div class="wrapper">

			<h1 class="branding-title"><img src="img/branding-title.png" alt="brand"><a href="index.php">Personal Media Library</a></h1>

			<ul class="nav">
                <li class="books<?php if (section == "books"){echo " on";}?> "><a href="catalog.php?cat=books">Books</a></li>
                <li class="movies<?php if (section == "movies"){echo " on";}?> "><a href="catalog.php?cat=movies">Movies</a></li>
                <li class="music<?php if (section == "music"){echo " on";}?> "><a href="catalog.php?cat=music">Music</a></li>
                <li class="suggest<?php if (section == "books"){echo " on";}?> "><a href="suggest.php">Suggest</a></li>
            </ul>

		</div>

	</div>
	
	<div class="search">
	<form method="get" action="catalog.php">
		<label for="s">Search:</label>
		<input type="text" name="s" id="s">
		<input type="submit" name="" value="GO!">
	</form>	
	</div>

	<div id="content">
