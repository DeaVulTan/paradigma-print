<?php defined('PF_VERSION') OR header('Location:404.html'); ?>
<?php

/**
 *
 * @package		Vitubo
 * @author		Vitubo Team 
 * @copyright   Vitubo Team
 * @link		http://www.vitubo.com
 * @since		Version 1.0
 * @filesource
 *
 */
function data_question_answer($questions, $answers) {
    $result = array();
    $count = count($questions);
    if ($count == count($answers)) {
        for ($i = 0; $i < $count; $i++) {
            $id = uniqid();
            $result["{$id}"] = array(
                'question' => $questions[$i],
                'answer' => $answers[$i]
            );
        }
    }
    return $result;
}


function clean_data($list){
    $result = array();
    foreach($list as $item){
        $result['answer'][] = $item['answer'];
        $result['question'][] = $item['question'];
    }
    return $result;
}