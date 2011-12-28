<?php

// Include required files
include_once (dirname (dirname (__FILE__)) . '/config/bootstrap.php');
App::LoadClass ('User');
App::LoadClass ('Comment');
App::LoadClass ('Pagination');
App::LoadClass ('Video');


// Establish page variables, objects, arrays, etc
View::InitView ('comments');
Plugin::Trigger ('comments.start');
View::$vars->logged_in = User::LoginCheck();
if (View::$vars->logged_in)  View::$vars->user = new User (View::$vars->logged_in);
$records_per_page = 9;



// Verify a comment type and id was given
if (empty ($_GET['vid']) || !is_numeric ($_GET['vid']) || !Video::Exist (array ('video_id' => $_GET['vid']))) {
    App::Throw404();
}


// Retrieve Video
View::$vars->video = new Video ($_GET['vid']);
View::$vars->meta->title = Functions::Replace (View::$vars->meta->title, array ('video' => View::$vars->video->title));
$url = '/videos/' . View::$vars->video->video_id . '/comments';


// Retrieve comments count
$query = "SELECT comment_id FROM " . DB_PREFIX . "comments WHERE video_id = " . View::$vars->video->video_id . " ORDER BY comment_id DESC";
$result_count = $db->Query ($query);
View::$vars->total_comments = $db->Count ($result_count);


// Initialize pagination
View::$vars->pagination = new Pagination ($url, View::$vars->total_comments, $records_per_page);
$start_record = View::$vars->pagination->GetStartRecord();


// Retrieve limited results
$query .= " LIMIT $start_record, $records_per_page";
$result = $db->Query ($query);
View::$vars->comment_list = array();
while ($row = $db->FetchObj ($result)) {
    View::$vars->comment_list[] = $row->comment_id;
}


// Output page
Plugin::Trigger ('comments.before_render');
View::Render ('comments.tpl');

?>