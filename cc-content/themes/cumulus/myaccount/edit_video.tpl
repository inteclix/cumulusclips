<?php

View::SetLayout ('myaccount');
View::Header();

?>

<h1><?=Language::GetText('update_video_header')?></h1>

<?php if ($message): ?>
    <div id="message" class="<?=$message_type?>"><?=$message?></div>
<?php endif; ?>


<div class="block">

    <p><a href="<?=HOST?>/myaccount/myvideos/"><?=Language::GetText('back_to_videos')?></a></p><br />
    <form action="<?=HOST?>/myaccount/editvideo/<?=$video->video_id?>/" method="post">

        <div class="row<?=(isset ($errors['title'])) ? ' errors' : ''?>">
            <label><?=Language::GetText('title')?>:</label>
            <input class="text" type="text" name="title" value="<?=(!empty ($errors) && isset ($data['title'])) ? $data['title'] : $video->title?>" />
        </div>

        <div class="row<?=(isset ($errors['description'])) ? ' errors' : ''?>">
            <label><?=Language::GetText('description')?>:</label>
            <textarea class="text" name="description"><?=(!empty ($errors) && isset ($data['description'])) ? $data['description'] : $video->description?></textarea>
        </div>

        <div class="row<?=(isset ($errors['tags'])) ? ' errors' : ''?>">
            <label><?=Language::GetText('tags')?>:</label>
            <input class="text" type="text" name="tags" value="<?=(!empty ($errors) && isset ($data['tags'])) ? $data['tags'] : implode (', ', $video->tags)?>" /> <em>*<?=Language::GetText('comma_delimited')?></em>
        </div>

        <div class="row<?=(isset ($errors['cat_id'])) ? ' errors' : ''?>">
            <label><?=Language::GetText('category')?>:</label>
            <select class="dropdown" name="cat_id">
            <?php while ($cat = $db->FetchObj ($result_cat)): ?>
                <option value="<?=$cat->cat_id?>"<?=(isset ($data['cat_id']) && $data['cat_id'] == $cat->cat_id) || (!isset ($data['cat_id']) && $video->cat_id == $cat->cat_id) ? ' selected="selected"' : ''?>><?=$cat->cat_name?></option>
            <?php endwhile; ?>
            </select>
        </div>

        <div class="row-shift">
            <input type="hidden" name="submitted" value="TRUE" />
            <a class="button" href=""><span><?=Language::GetText('update_video_button')?></span></a>
        </div>
    </form>

</div>

<?php View::Footer(); ?>