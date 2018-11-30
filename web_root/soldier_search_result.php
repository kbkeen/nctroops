<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <!-- the head section -->
    <head>
        <title>Soldier Search Results</title>
        
        
        <link rel="stylesheet" type="text/css"
              href="css/nctroops_stylesheet.css" />
              
    </head>

    <!-- the body section -->
    <body>
<div id="page">

<?php 

require('../require/nctroops_database_connect.php');
require('../require/db_queries.php');
include('includes/functions.php');
//Display the header section
include('templates/header.php');


// define variables
$rank_selection_array = $_POST['rank_id'];
$occupation_selection_array = $_POST['occupation_id'];
$birthplace_selection_array = $_POST['birthplace_id'];
$residence_selection_array = $_POST['residence_id'];
$state_selection_array = $_POST['state_id'];
$battle_selection_array = $_POST['battle_id'];
$casuality_selection_array = $_POST['casuality_id'];
$dsp_rank;
$dsp_occupation;
$dsp_birthplace;
$dsp_residence;
$dsp_state;
$dsp_battle;
$dsp_casuality;
$no_values_selected = FALSE;
$none_count = 0;
$no_checkbox_checked = FALSE;
$checkbox_count = 0;
// use this array to determine if any check boxes are checked
$cb_values_array = array();

// put all dropdown selections in  - $drop_down_values_array
$drop_down_values_array = array($rank_selection_array, $occupation_selection_array, $birthplace_selection_array, $residence_selection_array, 
    $state_selection_array, $battle_selection_array, $casuality_selection_array);



//handle display column checkboxes, if checked dsp variable = TRUE
//add all check box values to array - $cb_values_array
if(isset($_POST['dsp_rank'])){
	$dsp_rank = $_POST['dsp_rank'];
	array_push($cb_values_array, $dsp_rank);
}else{
	$dsp_rank = "FALSE";
	array_push($cb_values_array, $dsp_rank);
}
if(isset($_POST['dsp_occupation'])){
	$dsp_occupation = $_POST['dsp_occupation'];
	array_push($cb_values_array, $dsp_occupation);
}else{
	$dsp_occupation = "FALSE";
	array_push($cb_values_array, $dsp_occupation);
}
if(isset($_POST['dsp_birthplace'])){
    $dsp_birthplace = $_POST['dsp_birthplace'];
    array_push($cb_values_array, $dsp_birthplace);
}else{
    $dsp_birthplace = "FALSE";
    array_push($cb_values_array, $dsp_birthplace);
}
if(isset($_POST['dsp_residence'])){
    $dsp_residence = $_POST['dsp_residence'];
    array_push($cb_values_array, $dsp_residence);
}else{
    $dsp_residence = "FALSE";
    array_push($cb_values_array, $dsp_residence);
}
if(isset($_POST['dsp_state'])){
	$dsp_state = $_POST['dsp_state'];
	array_push($cb_values_array, $dsp_state);
}else{
	$dsp_state = "FALSE";
	array_push($cb_values_array, $dsp_state);
}
if(isset($_POST['dsp_battle'])){
	$dsp_battle = $_POST['dsp_battle'];
	array_push($cb_values_array, $dsp_battle);
}else{
	$dsp_battle = "FALSE";
	array_push($cb_values_array, $dsp_battle);
}
if(isset($_POST['dsp_casuality'])){
	$dsp_casuality = $_POST['dsp_casuality'];
	array_push($cb_values_array, $dsp_casuality);
}else{
	$dsp_casuality = "FALSE";
	array_push($cb_values_array, $dsp_casuality);
}


//loop thru all drop down values and count the number of none values
foreach ($drop_down_values_array as $selection){
    foreach($selection as $value){
        if($value === "none"){
            $none_count++;
        }
    }
}

//if all drop down boxes have a None value selected set $no_values_selected variable to TRUE
if($none_count == count($drop_down_values_array)){
    $no_values_selected = TRUE;
}

//count number of checkbox False values
foreach($cb_values_array as $value){
    if($value === "FALSE"){
        $checkbox_count++;
    }
}

//if no checkboxs are checked set $no_checkbox_checked variable to TRUE
if($checkbox_count == count($cb_values_array)){
    $no_checkbox_checked = TRUE;
}

//if all checkboxes are not ckecked and all dropdown selections are None, Stop and reload the page
if($no_checkbox_checked == "TRUE" && $no_values_selected == "TRUE"){
    header("Location: http://localhost/nctroops/web_root/soldier_search.php");
}

// START PROCESSING THE USER SELECTIONS BELOW

// if any chechboxes are checked or any dropdown values othe than None are selected start processing the results
// see web_root/includes/functions.php
set_checkbox_variables($dsp_rank, $dsp_occupation, $dsp_birthplace, $dsp_residence, $dsp_state, $dsp_battle, $dsp_casuality);

// see web_root/includes/functions.php
process_dropdown_list_selections($rank_selection_array, $occupation_selection_array, 
    $birthplace_selection_array, $residence_selection_array, $state_selection_array, 
    $battle_selection_array, $casuality_selection_array);

// if display in report checkbox is checked add that column of values to the result
// see web_root/includes/functions.php
//process_display_checkboxes();

// build the search query based on the users selections
// see web_root/includes/functions.php
$constructed_query = construct_query();

// execute query and return result for output display
// see require/db_queries.php
$soldiers = get_soldiers($constructed_query);
?>

<!-- Return link to the search page -->
<p><a href="soldier_search.php">New Search</a></p>

<!-- Display the number of soldiers found during the search -->
<p><?php if(count($soldiers) == 1){
            echo "This search found <strong>" . count($soldiers) . "</strong> soldier."; 
        }
        else{
            echo "This search found <strong>" . count($soldiers) . "</strong> soldiers.";
        }
?></p>

 <!--Display the results of the search -->
 	<table>
		<tr>
    	 	<th>Name</th>
    	 	<!--  if a Display in Report checkbox is checked add a table column heading for the checked type --> 
    	 	<?php 
			if($dsp_rank == "TRUE"){
			    echo "<th>Rank</th>";
			}
			if($dsp_occupation == "TRUE"){
			    echo "<th>Occupation</th>";
			}
			if($dsp_birthplace == "TRUE"){
			    echo "<th>Birthplace</th>";
			}
			if($dsp_residence == "TRUE"){
			    echo "<th>Residence</th>";
			}
			
			?>			
        </tr>
        
	<?php foreach ($soldiers as $soldier) : ?>
	
		<tr>
			<!--  always add the combined full name to each table result -->
			<td><?php echo $soldier['last_name'] . ', ' . $soldier['first_name'] . ' ' . $soldier['middle_name']?></td>
			
			<!--  if a Display in Report checkbox is checked create a column with the associated value -->
			<?php 
			if($dsp_rank == "TRUE"){
			    echo "<td>" .  $soldier['rank'] . "</td>";
			}
			
			if($dsp_occupation == "TRUE"){
			    echo "<td>" .  $soldier['occupation'] . "</td>";
			}
			
			if($dsp_birthplace == "TRUE"){
			    echo "<td>" .  $soldier['birthplace'] . "</td>";
			}
			
			if($dsp_residence == "TRUE"){
			    echo "<td>" .  $soldier['residence'] . "</td>";
			}
			
			?>			
			
		</tr>
	<?php endforeach; ?>
	</table>
 
<?php
//Display the footer section
include('templates/footer.php');
?>
</div>
 </body>
</html>
