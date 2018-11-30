<?php

global $where_clause;
global $join_clause;
global $select_statement;
global $constructed_query;
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
    $birthplace_selection_array, $residence_selection_array, $state_selection_array,
    $battle_selection_array, $casuality_selection_array){
        
        global $where_clause;
        global $cb_rank;
        global $cb_occupation;
        global $cb_birthplace;
        global $cb_residence;
        
        // define query JOIN statements
        $rank_selection_join_clause = " LEFT JOIN rank r ON s.rank_id = r.id";
        $occupation_selection_join_clause = " LEFT JOIN occupation o ON s.occupation_id = o.id";
        $birthplace_selection_join_clause = " LEFT JOIN location birth ON s.birthplace_id = birth.id";
        $residence_selection_join_clause = " LEFT JOIN location res ON s.residence_id = res.id";
        
        
        // define query WHERE clause ON values
        $rank_where_clause_on_parameter = "r.id";
        $occupation_where_clause_on_parameter = "o.id";
        $birthplace_where_clause_on_parameter = "birth.id";
        $residence_where_clause_on_parameter = "res.id";
        
        
        // define the $where_clause_array, used to build the WHERE clause for use in the construct_query() function
        $where_clause_array = array();
        
        /* the process_list_selections() function creates the JOIN clause needed to display that particular 
         * column in the report and adds it to the global $join_clause variable for use in the construct_query() function 
         */
        // the process_list_selections() function constructs the where clause values and puts them in the $where_clause_array
        array_push($where_clause_array, process_list_selections($rank_selection_array, 
            $rank_selection_join_clause, $rank_where_clause_on_parameter, $cb_rank));
        array_push($where_clause_array, process_list_selections($occupation_selection_array,
            $occupation_selection_join_clause, $occupation_where_clause_on_parameter, $cb_occupation));
        array_push($where_clause_array, process_list_selections($birthplace_selection_array,
            $birthplace_selection_join_clause, $birthplace_where_clause_on_parameter, $cb_birthplace));
        array_push($where_clause_array, process_list_selections($residence_selection_array,
            $residence_selection_join_clause, $residence_where_clause_on_parameter, $cb_residence));
        
        
        // build the where clause based on values added to the $where_clause_array
        // place constructed WHERE clause in global $where_clause variable for use in the construct_query() function
        $where_clause = build_where_clause($where_clause_array);
        
        // buid the select statement to be used in the construct_query() function
        build_select_statement();
        
}//end determine_query_parameters()

//build JOIN and WHERE clauses based on dropdown list selections, add JOIN clause to global $join_clause variable and return WHERE clause
function process_list_selections($selection_array, $selection_join_clause, $where_clause_on_value, $checkbox_value){
    
    global $join_clause;
    $selection_where_clause = "FALSE";
    $selection_count = 0;
    
    //build JOIN and WHERE clauses
    foreach ($selection_array as $selection_value){
        $selection_count++;
        /* if user selected None and the display in report check box is checked 
         * create a join to the selected table and exit this loop
         * None always overrides any additional selections made*/
        if($selection_value === 'none'){
            if($checkbox_value == "TRUE"){
                $join_clause = $join_clause . $selection_join_clause;
            }
            break;
        }
        /* if user selected Select All, join the selected table, 
         * create an empty WHERE clause and exit this loop
         * Select All always overrides multiple selections*/
        elseif($selection_value === '0'){
            $join_clause = $join_clause . $selection_join_clause;
            //$rank_where_clause = "FALSE";
            break;
        }
        //else create WHERE clause and JOIN clause to process user selections
        else{
            //if it's the first selection create a JOIN and WHERE clause for the selected table
            if($selection_count == 1){
                $join_clause = $join_clause . $selection_join_clause;
                // "$where_clause_on_value  =  $selection_value" equates to (example r.id = 3)
                $selection_where_clause =  "$where_clause_on_value  =  $selection_value";
            }
            //if there are additional selections, add them to the WHERE clause using the OR keyword
            //example - " OR  $where_clause_on_value  =  $selection_value" equates to (OR r.id = 3)
            else{
                $selection_where_clause = $selection_where_clause . " OR  $where_clause_on_value  =  $selection_value";
            }//end inner else
        }// end outer else
    }//end foreach()
    
    //if where clause value is FALSE return $selection_where_clause defined above (FALSE)
    //else return where clause values encased in parentheses
    if($selection_where_clause === "FALSE"){
        return $selection_where_clause;
    }
    else{
        return "(" . $selection_where_clause . ")";
    }
}//end process_list_selections()


// if the display in report checkbox is checked add that table's value to the select clause so it displays in the result
function build_select_statement(){
    
    global $select_statement;
    global $cb_rank;
    global $cb_occupation;
    global $cb_birthplace;
    global $cb_residence;
    
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
    
}//end build_select_statement()

function build_where_clause($where_clause_array){
    
    $where_clause = " WHERE ";
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
                $constructed_where_clause = $constructed_where_clause . " AND " . $value;
            }
            else{
                $constructed_where_clause = $value;
            }
        }//end outer if()
    }//end foreach
    
    //build the completed where clause for use in the query
    $where_clause = $where_clause . $constructed_where_clause;
    
    //if where clause has no values remove it from the query
    if($where_clause === " WHERE "){
        $where_clause = "";
    }
    
    return $where_clause;
    
}//end build_where_clause()

function construct_query(){
    global $where_clause;
    global $join_clause;
    global $select_statement;
    global $constructed_query;
    $order_by_clause = " ORDER BY s.last_name, s.first_name, s.middle_name";
    $constructed_query = $select_statement . " FROM soldier s" . $join_clause . $where_clause . $order_by_clause;
    
    // display complete query in solder_search_result.php for testing purposes    
    echo "<br><strong>see -  construct_query() in web_root/includes/functions.php</strong><br>constructed query:<br> $constructed_query";
    
    return $constructed_query;
}//end construct_query()

?>