<?php
defined('PF_VERSION') OR header('Location:404.html');
/*
 * * 
 * @package  Vitubo
 * @author  Vitubo Team 
 * @copyright Vitubo Team
 * @link  http://www.vitubo.com
 * @since  Version 1.0
 * @filesource
 *
 */
$setting = Pf::setting();
$setting->add_title('pf_polls', __('Polls settings','polls'));
$answer_ordering = Pf::setting()->get_element_value('pf_polls', 'answer_order');
$setting->add_element(__('Answer Ordering', 'Polls'), "<div class='col-md-2'>" . form_dropdown('answer_order',array(1=>'Ascending Alphabetical Order',2=>'Descending Alphabetical Order',3 =>'Ascending Votes Cast Order',4 => 'Descending Votes Cast Order'),'' ) ." &nbsp; </div>", 'pf_polls');
$allow_to_vote = Pf::setting()->get_element_value('pf_polls', 'allow_to_vote');
$setting->add_element(__('Allow To Vote', 'Polls'), "<div class='col-md-2'>" . form_dropdown('allow_to_vote',array(1=>'Everyone',2=>'Guest only',3=>'Member only'),'' ) ." &nbsp; </div>", 'pf_polls');
$allow_to_vote = Pf::setting()->get_element_value('pf_polls', 'display_number_vote');
$setting->add_element(__('Display number of vote', 'Polls'), "<div class='col-md-2'>" . form_dropdown('display_number_vote',array(1=> __('Yes','polls'),2=>__('No','polls')),'' ) ." &nbsp; </div>", 'pf_polls');
$allow_to_vote = Pf::setting()->get_element_value('pf_polls', 'display_percentage');
$setting->add_element(__('Display percentage of vote', 'Polls'), "<div class='col-md-2'>" . form_dropdown('display_percentage',array(1=>__('Yes','polls'),2=>__('No','polls')),'' ) ." &nbsp; </div>", 'pf_polls');


