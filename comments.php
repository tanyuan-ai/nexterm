<?php
if(post_password_required())return;
?>
<div id="comments" class="comments-area">
<?php if(have_comments()):?>
<h2 class="comments-title"><?php $n=get_comments_number();printf(_n('%s comment','%s comments',$n,'nexterm'),number_format_i18n($n));?></h2>
<ol class="comment-list">
<?php wp_list_comments(array('style'=>'ol','short_ping'=>true,'avatar_size'=>40));?>
</ol>
<?php the_comments_pagination(array('prev_text'=>'&larr;','next_text'=>'&rarr;'));?>
<?php endif;
if(!comments_open() && get_comments_number()):?>
<p class="no-comments"><?php esc_html_e('Comments are closed.','nexterm');?></p>
<?php endif;
comment_form();
?>
</div>
