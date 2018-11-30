<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <!-- the head section -->
    <head>
        <title>Select Soldiers</title>
        
        
        <link rel="stylesheet" type="text/css"
              href="css/nctroops_stylesheet.css" />
              
    </head>

    <!-- the body section -->
    <body>
<div id="page">

<?php 
require('../require/nctroops_database_connect.php');
require('../require/db_queries.php');
include('templates/header.php');

//execute queries to populate drop down selection lists
$rank_selection = get_rank_selections();
$occupation_selection = get_occupation_selections ();
$birthplace_selection = get_location_selections ();
$residence_selection = get_location_selections ();
$battle_selection = get_battle_selections ();
$state_selection = get_state_selections ();
$casuality_selection = get_casuality_selections ();
?>

<!--Filter By section -->
	<form action="soldier_search_result.php" method="post">
	
	<p>Soldiers of Compamy G, 33rd North Carolina Infantry</p>
	
	<p><span style="font-weight:bold" >Filter By:</span></p>
	<table>
		<tr>
			<td>
				<table>
					<tr><td><strong>Rank</strong></td></tr>
					<tr><td><input type="checkbox" name="dsp_rank" value="TRUE" />Display in report</td></tr>
					<tr>				
						<td>        
							<select multiple name="rank_id[]" size = "3">
								<option value="none" selected="selected">None</option>
					            <option value="0">Select All</option>
					            <?php foreach ($rank_selection as $rank) : ?>
					            <option value="<?php echo $rank['id'] ?>"> <?php echo $rank['rank'] ?></option>
					            <?php endforeach; ?>
					        </select>
						</td>
					</tr>
				</table>
			</td>
			<td>
				<table>
					<tr><td><strong>Occupation</strong></td></tr>
					<tr><td><input type="checkbox" name="dsp_occupation" value="TRUE" /> Display in report</td></tr>
					<tr>				
						<td>
							 <select multiple name="occupation_id[]" size = "3">
							 	<option value="none" selected="selected">None</option>
					            <option value="0">Select All</option>
					            <?php foreach ($occupation_selection as $occupation) : ?>
					            <option value="<?php echo $occupation['id'] ?>"> <?php echo $occupation['occupation'] ?></option>
					            <?php endforeach; ?>
					        </select>				
						</td>
					</tr>
				</table>
			</td>
			<td>
				<table>
					<tr><td><strong>Birthplace</strong></td></tr>
					<tr><td><input type="checkbox" name="dsp_birthplace" value="TRUE" />Display in report</td></tr>
					<tr>				
						<td>
							 <select multiple name="birthplace_id[]" size = "3">
					            <option value="none" selected="selected">None</option>
					            <option value="0">Select All</option>
					            <?php foreach ($birthplace_selection as $birthplace) : ?>
					            <option value="<?php echo $birthplace['id'] ?>"> <?php echo $birthplace['place'] ?></option>
					            <?php endforeach; ?>
					        </select>				
						</td>
					</tr>
				</table>
			</td>
			<td>
				<table>
					<tr><td><strong>Residence</strong></td></tr>
					<tr><td><input type="checkbox" name="dsp_residence" value="TRUE" />Display in report</td></tr>
					<tr>				
						<td>
							 <select name="residence_id[]" size = "3">
					            <option value="none" selected="selected">None</option>
					            <option value="0">Select All</option>
					            <?php foreach ($residence_selection as $residence) : ?>
					            <option value="<?php echo $residence['id'] ?>"> <?php echo $residence['place'] ?></option>
					            <?php endforeach; ?>
					        </select>				
						</td>
					</tr>
				</table>
			</td>
			
		</tr>
		
		<tr>
			<td>
				<table>
					<tr><td><strong>Home State</strong></td></tr>
					<tr><td><input type="checkbox" name="dsp_state" value="TRUE" />Display in report</td></tr>
					<tr>				
						<td>
							 <select name="state_id[]" size = "3">
					            <option value="none" selected="selected">None</option>
					            <option value="0">Select All</option>
					            <?php foreach ($state_selection as $state) : ?>
					            <option value="<?php echo $state['id'] ?>"> <?php echo $state['full_name'] ?></option>
					            <?php endforeach; ?>
					        </select>				
						</td>
					</tr>
				</table>
			</td>
		
			<td>
				<table>
					<tr><td><strong>Battles</strong></td></tr>
					<tr><td><input type="checkbox" name="dsp_battle" value="TRUE" />Display in report</td></tr>
					<tr>				
						<td>
							 <select name="battle_id[]" size = "3">
					            <option value="none" selected="selected">None</option>
					            <option value="0">Select All</option>
					            <?php foreach ($battle_selection as $battle) : ?>
					            <option value="<?php echo $battle['id'] ?>"> <?php echo $battle['battle_name'] ?></option>
					            <?php endforeach; ?>
					        </select>				
						</td>
					</tr>
				</table>
			</td>
			<td>
				<table>
					<tr><td><strong>Casuality Status</strong></td></tr>
					<tr><td><input type="checkbox" name="dsp_casuality" value="TRUE" />Display in report</td></tr>
					<tr>				
						<td>
							 <select name="casuality_id[]" size = "3">
					            <option value="none" selected="selected">None</option>
					            <option value="0">Select All</option>
					            <?php foreach ($casuality_selection as $casuality) : ?>
					            <option value="<?php echo $casuality['id'] ?>"> <?php echo $casuality['status'] ?></option>
					            <?php endforeach; ?>
					        </select>				
						</td>
					</tr>
				</table>
			</td>
			
		</tr>
	</table>

        
    <p><input type="submit" value="Search"/></p>
    </form>


<?php 
include('templates/footer.php');
?>
</div>
 </body>
</html>