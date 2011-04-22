<?php

### Created on March 23, 2009
### Created by Miguel A. Hurtado
### This script searches for videos


// Include required files
include ('../config/bootstrap.php');
App::LoadClass ('User');
App::LoadClass ('Video');
App::LoadClass ('Rating');
App::LoadClass ('Pagination');
View::InitView();


// Establish page variables, objects, arrays, etc
View::$vars->logged_in = User::LoginCheck();
if (View::$vars->logged_in) View::$vars->user = new User (View::$vars->logged_in);
View::$vars->page_title = 'Techie Videos - Search Videos - ';
$keyword = NULL;
View::$vars->cleaned = NULL;
$url = array (HOST . '/search');
$records_per_page = 9;



// Verify a keyword was given
if (isset ($_POST['submitted_search'])) {
    View::$vars->cleaned = htmlspecialchars ($_POST['keyword']);
} elseif (isset ($_GET['keyword'])) {
    View::$vars->cleaned = htmlspecialchars ($_GET['keyword']);
}

$url[] =  '?keyword=' . View::$vars->cleaned;
View::$vars->page_title .= "'" . View::$vars->cleaned . "'";
$keyword = $db->Escape (View::$vars->cleaned);


// Retrieve total count
$query = "SELECT video_id FROM videos WHERE status = 6 AND MATCH(title, tags, description) AGAINST('$keyword')";
$result_count = $db->Query ($query);
$total = $db->Count ($result_count);

// Initialize pagination
View::$vars->pagination = new Pagination ($url, $total, $records_per_page);
$start_record = View::$vars->pagination->GetStartRecord();

// Retrieve limited results
$query .= " LIMIT $start_record, $records_per_page";
View::$vars->result = $db->Query ($query);


// Output Page
View::Render ('search.tpl');

?>