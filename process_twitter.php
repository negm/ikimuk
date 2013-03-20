<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
function latestTweet()
{
require_once $_SERVER["DOCUMENT_ROOT"].('/inc/codebird.php');
Codebird::setConsumerKey('siuqD954HVxelmypLKWnA', 'to0MbPCCxiGMHqT65ttkhpVc1ybj0eAhtGOgzWNrWpE'); // static, see 'Using multiple Codebird instances'
$cb = Codebird::getInstance();
$cb->setToken('947973235-4Yu2YnqZ4FT6pc4OsxE3r1v3esnAgU34tUyFwo9y', 'RW0e1RmX2CtLGliB4cqT9LMs7kaQgNu2eSnLsCz5eM');
$reply = (array) $cb->statuses_userTimeline(array("count"=>1));
if (count($reply)>0)
{
$text = $reply[0]->text;

//var_dump($reply[3]->entities);
//var_dump($text);
foreach ($reply[0]->entities->hashtags as $entity)
{
    $text = str_replace("#".$entity->text, "<a target='_blank' href='https://twitter.com/search?q=%23".$entity->text."&src=hash'>#".$entity->text."</a>", $text);
}
foreach ($reply[0]->entities->urls as $entity)
{
    $text = str_replace($entity->url, "<a target='_blank' href='$entity->url'>".$entity->url."'</a>", $text);
}
foreach ($reply[0]->entities->user_mentions as $entity)
{
    $text = str_replace("@".$entity->screen_name, "<a target='_blank' href='https://twitter.com/".$entity->screen_name."'>@".$entity->screen_name."</a>", $text);
}
return($text);
}
else return "Follow us on Twitter";
}
?>
