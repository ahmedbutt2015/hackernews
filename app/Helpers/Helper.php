<?php // Code within app\Helpers\Helper.php

namespace App\Helpers;
use Blade;
use Feed;
class Helper
{
    public static function reply($comments)
    {
        foreach($comments as $comment){
            $a = "";
            if(isset($comment->vote)){
                $a = $comment->vote;
            }
            echo '<div class="comment commentReply" data-id=" '.$comment->id.'" vote="'.$a.'">';
                echo'<div class="vote">';
                    echo '<div class="upvote glyphicon glyphicon-chevron-up"></div>';
                    echo '<div class="downvote glyphicon glyphicon-chevron-down"></div>';
                echo '</div>';
                echo '<div class="content-body">';
                    echo '<div class="content-body_down">';
                    echo '<p>	<span class="point">'.$comment->point.'</span> points by ';
                    echo '<span class="content-body_down-username">'.$comment->username.'</span>';
                    echo ' '.Feed::time_elapsed_string($comment->created_at).'  | <span class="content-body_down-comments">';
                    if (isset($comment->reply))echo $comment->reply->count(); else echo 0;
                    echo'</span> replies </p></div> <div class="content-body_up"> <div class="content-body_up-title">';
                    echo '<p> '. $comment->comment .'</p><p class="reply">reply</p></div></div>';
                echo'</div>';
            if(isset($comment->reply)){
                self::reply($comment->reply);
            }
            echo'</div>';
        }
        
    }
}