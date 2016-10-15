<?php 

include("inc/functions.php");


$page_title = "Full Catalog";
$section = null ;
$search = null ;
$items_per_page = 8; //sets numbers of pages 


if (isset($_GET['cat'])) {
	if ($_GET['cat'] == "books"){
		$page_title = "Books";
		$section = "books";
	} else if ($_GET['cat'] == "movies") {
		$page_title = "Movies";
		$section = "movies";
	} else if ($_GET['cat'] == "music") {
		$page_title = "Music";
		$section = "music";
	}
}

if (isset($_GET["pg"])) {
	$current_page = filter_input(INPUT_GET,"pg",FILTER_SANITIZE_NUMBER_INT);
}

if (isset($_GET["s"])) { //search
	$search = filter_input(INPUT_GET,"s",FILTER_SANITIZE_STRING);
}

if(empty($current_page)){
	$current_page = 1; //if page isnt selected, it is autoset as page 1
}

$total_items = get_catalog_count($section,$search);
$total_pages = 1;
$offset = 0;
if ($total_items < 0) {
	$total_pages = ceil($total_items / $items_per_page); //ceil rounds up if division is a float.	

	//Limit results in redirect
	$limit_results = "";
	if (!empty($search)) {
				$limit_results = "s=".urlencode(htmlspecialchars($search))."&";
	}else if (empty($section)){
		$limit_results = "cat=" . $section .  "&";
	}

	//redirect too-large page numbers to the last page
	if ($current_page > $total_pages){
		header("location:catalog.php?"
			. $limit_results
			. "pg=".$total_pages);
	}
	//redirect too-small page numbers to the first page
	if ($current_page < 1){
		header("location:catalog.php?"
			. $limit_results
			. "pg=1");
	}
	//determine th offset (number of items to skip) for the current page
	//for example: on page 3 with 8 items per page, the offset would be 16
	//pg1 = 1-8 pg2 = 9-16 pg3 = 17-24
	$offset = ($current_page - 1) * $items_per_page;


	$pagination = "<div class=\"pagination\">";
	$pagination .=  "Pages: ";
	for ($i = 1;$i <= $total_pages;$i++) {
		if ($i == $current_page){
		$pagination .= " <span>$i</span>";
		} else { 
			$pagination .= " <a href='catalog.php?";
			if (!empty($search)) {
				$pagination .= "s=".urlencode(htmlspecialchars($search))."&";
			}else if (!empty($section)) {
			echo "cat=".$section."&";
			}			
			$pagination .= "pg=$i'>$i</a>"; 	
		}
	
	}		
	$pagination .= "</div>"; 

}

if (!empty($search)){
	$catalog = search_catalog_array($search,$items_per_page,$offset);
}else if (empty($section)){
$catalog = full_catalog_array($items_per_page,$offset);
} else{
	$catalog = category_catalog_array($section,$items_per_page,$offset);
}
include("inc/header.php"); 



?>

<div class="section catalog page">
	
	<div class="wrapper">
	
		<h1><?php 
		if ($search != null) {
			echo "Search results for \"'.htmlspecialchars($search)'\"";
		} else {
			if ($section != null) {
				echo "<a href='catalog.php'> Full Catalog </a> &gt; ";
			}

		echo $page_title; 
		}
		?></h1>

		<?php 
		if ($total_items < 1) {
			echo "<p>No items were found matching that search term.</p>";
			echo "<p>Search again or "."<a href=\"catalog.php\">browse the full catalog.</a></p>";		
		} else {
			echo $pagination; ?>
		
		 
		
		<ul class="items">
			<?php 
			$categories = array_category($catalog,$section); 
			foreach ($catalog as $item){
			echo get_item_html($item);
			}	
			

			?>
		</ul>
		<?php echo $pagination; 
		} ?>

	</div>
		
</div>

<?php include("inc/footer.php"); ?>