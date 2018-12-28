<?php

global $where_clause;
global $join_clause;
global $select_statement;
global $cb_rank;
global $cb_occupation;
global $cb_birthplace;
global $cb_residence;
global $cb_state;
global $cb_battle;
global $cb_casuality;

// set checkbox variables
function set_checkbox_variables($dsp_rank, $dsp_occupation, $dsp_birthplace, $dsp_residence, $dsp_state, $dsp_battle, $dsp_casuality){
    
    global $cb_rank;
    global $cb_occupation;
    global $cb_birthplace;
    global $cb_residence;
    global $cb_state;
    global $cb_battle;
    global $cb_casuality;
    
    
    $cb_rank = $dsp_rank;
    $cb_occupation = $dsp_occupation;
    $cb_birthplace = $dsp_birthplace;
    $cb_residence = $dsp_residence;
    $cb_state = $dsp_state;
    $cb_battle = $dsp_battle;
    $cb_casuality = $dsp_casuality;
    
}//end set_checkbox_variables()

// process selections made by the user
function process_dropdown_list_selections($rank_selection_array, $occupation_selection_array,
    $birthplace_selection_array, $residence_selection_array, $battle_selection_array, $casuality_selection_array){
    
        // define variables
        global $where_clause;
        global $join_clause;
        global $cb_rank;
        global $cb_occupation;
        global $cb_birthplace;
        global $cb_residence;
        global $cb_battle;
        global $cb_casuality;
        
        
        // define query JOIN statements
        $rank_selection_join_clause = " LEFT JOIN rank r ON s.rank_id = r.id";
        $occupation_selection_join_clause = " LEFT JOIN occupation o ON s.occupation_id = o.id";
        $birthplace_selection_join_clause = " LEFT JOIN location birth ON s.birthplace_id = birth.id";
        $residence_selection_join_clause = " LEFT JOIN location res ON s.residence_id = res.id";
        $battle_selection_join_clause = " LEFT JOIN battle_list bl ON  bc.battle_list_id = bl.id";
        $casuality_selection_join_clause = " LEFT JOIN casuality_status cs ON bc.casuality_status_id = cs.id";
        $battle_casualties_selection_join_clause = " LEFT JOIN battle_casualties bc ON s.id = bc.soldier_id";
        
        
        // define query WHERE clause values
        $rank_where_clause_value = "r.id";
        $occupation_where_clause_value = "o.id";
        $birthplace_where_clause_value = "birth.id";
        $residence_where_clause_value = "res.id";
        $battle_where_clause_value = "bl.id";
        $casuality_where_clause_value = "bc.casuality_status_id";
        
        
        // define the $where_clause_array, used to build the WHERE clause for the construct_query() function
        $where_clause_array = array();
        
        /* the process_list_selections() function creates the JOIN clause needed to display the relevant 
         * column in the report and adds it to the global $join_clause variable for use in the construct_query() function 
         *
         * the process_list_selections() function also constructs the WHERE clause values 
         * and puts them in the $where_clause_array for use in the build_where_clause() function
         */
        array_push($where_clause_array, process_list_selections($rank_selection_array, 
            $rank_selection_join_clause, $rank_where_clause_value, $cb_rank));
        
        array_push($where_clause_array, process_list_selections($occupation_selection_array,
            $occupation_selection_join_clause, $occupation_where_clause_value, $cb_occupation));
        
        array_push($where_clause_array, process_list_selections($birthplace_selection_array,
            $birthplace_selection_join_clause, $birthplace_where_clause_value, $cb_birthplace));
        
        array_push($where_clause_array, process_list_selections($residence_selection_array,
            $residence_selection_join_clause, $residence_where_clause_value, $cb_residence));
        
        if($cb_battle == "TRUE" or $cb_casuality == "TRUE"){
            $join_clause .= $battle_casualties_selection_join_clause;
        }
        
        if($cb_battle == "TRUE"){
            array_push($where_clause_array, process_list_selections($battle_selection_array,
                $battle_selection_join_clause, $battle_where_clause_value, $cb_battle));
        }
        
        if($cb_casuality == "TRUE"){
            array_push($where_clause_array, process_list_selections($casuality_selection_array,
                $casuality_selection_join_clause, $casuality_where_clause_value, $cb_casuality));
        }
        
        
        // buid the SELECT statement to be used in the construct_query() function
        build_select_statement();
        
        /* build the WHERE clause based on the values added to the $where_clause_array and
         * place the constructed WHERE clause in the global $where_clause variable for use in the construct_query() function
         */
        $where_clause = build_where_clause($where_clause_array);
        
        // build the search query based on the users selections
        $constructed_query = construct_query();
        
        return $constructed_query;
        
}//end process_dropdown_list_selections()

//build JOIN and WHERE clauses based on dropdown list selections, add JOIN clause to global $join_clause variable and return WHERE clause
function process_list_selections($relevant_array, $relevant_join_clause, $relevant_where_clause_value, $checkbox_value){
    
    global $join_clause;
    $where_clause_values = "FALSE";
    $selection_count = 0;
    
    //build JOIN and WHERE clauses
    foreach ($relevant_array as $selected_value){
        $selection_count++;
        /* if the user selected None and the display in report check box is checked 
         * create a join to the relevant table and concatenate it to the global join clause
         * exit this loop
         * None always overrides any additional selections made*/
        if($selected_value === 'none'){
            if($checkbox_value == "TRUE"){
                $join_clause .= $relevant_join_clause;
            }
            break;
        }
        /* if the user selected Select All, create a join to the relevant table and concatenate it to the global join clause
         * exit this loop
         * Select All always overrides multiple selections*/
        elseif($selected_value === '0'){
            $join_clause .= $relevant_join_clause;
            break;
        }
        //else create WHERE clause and JOIN clause to process user selections
        else{
            //if it's the first selection create a JOIN and WHERE clause for the relevant table
            if($selection_count == 1){
                $join_clause .= $relevant_join_clause;
                // EXAMPLE - "$relevant_where_clause_value  =  $selected_value" equates to [r.id = 3]
                $where_clause_values =  "$relevant_where_clause_value  =  $selected_value";
            }
            //if there are additional selections, concatenate them to the WHERE clause using the OR keyword
            //EXAMPLE - " OR  $relevant_where_clause_value  =  $selected_value" equates to [OR r.id = 3]
            else{
                $where_clause_values .= " OR  $relevant_where_clause_value  =  $selected_value";
            }//end inner else
        }// end outer else
    }//end foreach()
    
    //if $where_clause_values variable is FALSE as initiated above return FALSE for use in the build_where_clause() function
    //else return $where_clause_values encased in parentheses
    if($where_clause_values === "FALSE"){
        return "FALSE";
    }
    else{
        return "(" . $where_clause_values . ")";
    }
}//end process_list_selections()


// if the display in report checkbox is checked add that table's value to the select clause so it displays in the result
function build_select_statement(){
    
    global $select_statement;
    global $cb_rank;
    global $cb_occupation;
    global $cb_birthplace;
    global $cb_residence;
    global $cb_battle;
    global $cb_casuality;
    
    
    // define select statement, additional values will be added if Display in report checkbox is checked
    $select_statement = "SELECT s.last_name, s.first_name, s.middle_name";
    
    
    //add additional selections to the SELECT statement if the Display in report checkbox is checked (TRUE)
    if($cb_rank == "TRUE"){
        $select_statement = $select_statement  . ", r.rank";
    }
    
    if($cb_occupation == "TRUE"){
        $select_statement = $select_statement  . ", o.occupation";
    }
    
    if($cb_birthplace == "TRUE"){
        $select_statement = $select_statement  . ", birth.place as birthplace";
    }
    
    if($cb_residence == "TRUE"){
        $select_statement = $select_statement  . ", res.place as residence";
    }
    
    if($cb_battle == "TRUE"){
        $select_statement = $select_statement  . ", bl.battle_name as battle";
    }
    
    if($cb_casuality == "TRUE"){
        $select_statement = $select_statement  . ", cs.status as casuality";
    }
    
    
}//end build_select_statement()

function build_where_clause($where_clause_array){
    
    // define variables
    $complete_where_clause = " WHERE ";
    $constructed_where_clause = "";
    
    //define a value to count the foreach{} loop iterations to build the WHERE clause
    $where_clause_array_value_count = 0;
    
    // use $where_clause_array values to construxt where clause
    foreach($where_clause_array as $value){
        /* if $where_clause_array value is not FALSE add 1 to the count and append value to $constructed_where_clause
         * if count is greater than 1 add keyword AND between additional values
         * and append it to the $constructed_where_clause 
         */
        if($value !== "FALSE"){
            ++$where_clause_array_value_count;
            if($where_clause_array_value_count > 1){
                $constructed_where_clause .= " AND " . $value;
            }
            else{
                $constructed_where_clause = $value;
            }
        }//end outer if()
    }//end foreach
    
    //build the completed where clause for use in the query
    $complete_where_clause .= $constructed_where_clause;
    
    //if the WHERE clause has no values return an empty string which removes it from the query
    if($complete_where_clause === " WHERE "){
        $complete_where_clause = "";
    }
    
    return $complete_where_clause;
    
}//end build_where_clause()

function construct_query(){
    
    // define variables
    global $select_statement;
    $from_statement = " FROM soldier s";
    global $join_clause;
    global $where_clause;
    $order_by_clause = " ORDER BY s.last_name, s.first_name, s.middle_name";
    $constructed_query;
    
    $constructed_query = $select_statement . $from_statement . $join_clause . $where_clause . $order_by_clause;
    
    // display complete query in solder_search_result.php for testing purposes    
    //echo "<br><strong>see -  construct_query() in web_root/includes/functions.php</strong><br>constructed query:<br> $constructed_query";
    
    return $constructed_query;
}//end construct_query()

?>