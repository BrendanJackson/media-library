<?php 
include("inc/data.php");
include("inc/connection.php");
include("inc/functions.php");

$page_title = "Personal Media Library"; // names page title
$section = null; //sets section to a standard of null

include("inc/header.php"); 
?>
		<div class="section catalog random">

			<div class="wrapper">

				<h2>May we suggest something?</h2>

								<ul class="items">
									<?php
									$random = random_catalog_array(); //function randomly chooses 4 items, name it $random 
									foreach($random as $item){ //for every item of full_catalog_array randomly chosen, set those as $id
									echo get_item_html($item); //takes arguments from full_catalog_array(), the total and the array['$id']in full_catalog_array()
 									}	
									?>								
								</ul>

			</div>

		</div>

<?php include("inc/footer.php"); 

?>