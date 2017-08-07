<?php
defined('PF_VERSION') OR header('Location:404.html');
class Social_Shortcode extends Pf_Shortcode_Controller{
    public function tweets($atts, $content = null) {
        if (!is_dir(ABSPATH . '/tmp/cache/tweets/')) {
            mkdir(ABSPATH . '/tmp/cache/tweets/');
            chmod(ABSPATH . '/tmp/cache/tweets/', 0777);
        }
        $screen_name = !empty($atts['twitter']) ? $atts['twitter'] : 'vitubocms';
        $num_result = !empty($atts['num']) ? $atts['num'] : 3;
        $once = "<div class='blog-twitter-inner'>
                                    <i class='icon-twitter'></i>
                                    <a href='https://twitter.com/{{tweet_author}}'>@{{tweet_author}}</a>
                                    {{content}}
                                    <span>{{time}}</span>
                            </div>";
        require_once ABSPATH . '/lib/tweet/TweetPHP.php';
        $TweetPHP = new TweetPHP(array('twitter_screen_name' => $screen_name, 'cache_file' => ABSPATH . '/tmp/cache/tweets/twitter.txt', 'cache_file_raw' => ABSPATH . '/tmp/cache/tweets/twitter-array.txt'));
        $tweet_arrays = $TweetPHP->get_tweet_array();
        if ($tweet_arrays != array('error')) {
            $contents = '';
            for ($i = 0; $i < $num_result; $i++) {
                if (!empty($tweet_arrays[$i])) {
                    $text = $TweetPHP->autolink($tweet_arrays[$i]['text']);
                    $tweet_author = $tweet_arrays[$i]['user']['screen_name'];
                    $time = $this->relative_time(time() - strtotime($tweet_arrays[$i]['created_at']));
                    $once_tweet = $once;
                    $once_tweet = str_replace('{{tweet_author}}', $tweet_author, $once_tweet);
                    $once_tweet = str_replace('{{content}}', $text, $once_tweet);
                    $once_tweet = str_replace('{{time}}', $time, $once_tweet);
                    $contents .= $once_tweet;
                } else
                    break;
            }
            $result = "<div class='blog-twitter'>$contents</div>";
        } else
            $result = __('There is no tweet yet or wrong twitter name', 'core');
        return $result;
    }
    
    private function relative_time($secs) {
        $second = 1;
        $minute = 60;
        $hour = 60 * 60;
        $day = 60 * 60 * 24;
        $week = 60 * 60 * 24 * 7;
        $month = 60 * 60 * 24 * 30;
        $year = 60 * 60 * 24 * 365;
        if ($secs <= 0) {
            $output = "now";
        } elseif ($secs > $second && $secs < $minute) {
            $output = round($secs / $second) . " second";
        } elseif ($secs >= $minute && $secs < $hour) {
            $output = round($secs / $minute) . " minute";
        } elseif ($secs >= $hour && $secs < $day) {
            $output = round($secs / $hour) . " hour";
        } elseif ($secs >= $day && $secs < $week) {
            $output = round($secs / $day) . " day";
        } elseif ($secs >= $week && $secs < $month) {
            $output = round($secs / $week) . " week";
        } elseif ($secs >= $month && $secs < $year) {
            $output = round($secs / $month) . " month";
        } elseif ($secs >= $year && $secs < $year * 10) {
            $output = round($secs / $year) . " year";
        } else {
            $output = " more than a decade ago";
        }
    
        if ($output <> "now") {
            $output = (substr($output, 0, 2) <> "1 ") ? $output . "s" : $output;
        }
        return $output;
    }
    
    
    public function icon($atts, $content = null, $class) {
        $url = !empty($atts['url']) ? $atts['url'] : '#';
        $css = !empty($atts['class']) ? $atts['class'] : '';
        
        $this->view->url = $url;
        $this->view->css = $css;
        $this->view->class = $class;
        $this->view->render();
    }
}