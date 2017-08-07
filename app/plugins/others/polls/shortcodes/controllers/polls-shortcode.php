<?php
defined ( 'PF_VERSION' ) or header ( 'Location:404.html' );
class Polls_Shortcode extends Pf_Shortcode_Controller{
    public function __construct(){
        parent::__construct();
        $this->load_model('answers');
        $this->load_model('ip');
        $this->load_model('polls');
    }
    public function display($atts, $content = null,$tag) {
        $allow_to_vote = Pf::setting()->get_element_value('pf_polls', 'allow_to_vote');
        $atts['display_vote'] =  Pf::setting()->get_element_value('pf_polls', 'display_number_vote');
        $atts['display_percentage '] =  Pf::setting()->get_element_value('pf_polls', 'display_percentage');
        $ip = get_client_ip();
        $polls_id = !empty($atts['id']) ? $atts['id'] : '';
        $check = $this->ip_model->check_ipquestion($ip,$polls_id);
        if($check == TRUE){
            if(!empty($polls_id)){
                $atts['template'] = 'display';
                $atts['polls'] = $this->polls_model->get_polls($polls_id);
                $atts['answers'] = $this->answers_model->get_list_answers($polls_id);
                if(!empty($atts['polls'])){
                    $atts['status'] = $atts['polls']['polls_status'];
                    $atts['question'] = $atts['polls']['polls_question'];
                    $atts['list_answers'] =  $atts['answers'];
                    $atts['page'] = $_GET['pf_page_url'];
                    if(!empty ( $_POST ) && $_POST ['type'] == 'polls' ){
                        if($allow_to_vote == 3 && !is_login()){
                            $atts['warning'] = __('Only Members can vote','polls');
                        }else if($allow_to_vote == 2 && is_login()){
                            $atts['warning'] = __('Only Guests can vote','polls');
                        }else{
                            if(!empty($_POST['vote'])){
                                $aid = $_POST['vote'];
                                $number_vote = count($aid);
                                if($number_vote > $atts['polls']['polls_multiple'] ){
                                    $atts['warning'] = __('The maximum number of votes is','polls').$atts['polls']['polls_multiple'] .' !';
                                }else{
                                    $data = array();
                                    $get_answers = array();
                                    foreach ($aid as $value){
                                        $get_answers = $this->answers_model->get_id_answers($value);
                                        $vote = $get_answers['pollsa_vote'] + 1;
                                        $data['pollsa_vote'] = $vote;
                                        $this->answers_model->update($data,$where = 'id='.$get_answers['id'].'');
                                        $this->ip_model->insert(array("pollsip_qid" => $polls_id ,"pollsip_aid" => $get_answers['id'],"pollsip_ip"=>$ip));
                                    }
                                    $atts['polls'] = $this->polls_model->get_polls($polls_id);
                                    $atts['answers'] = $this->answers_model->get_list_answers($polls_id);
                                    $atts['list_answers'] =  $atts['answers'];
                                    $atts['total_vote'] = 0;
                                    foreach ($atts['list_answers'] as $item){
                                        $atts['total_vote'] =  $atts['total_vote'] + $item['pollsa_vote'];
                                    }
                                    $this->polls_model->update(array('polls_totalvote' =>  $atts['total_vote']),$where = 'id='.$polls_id.'');
                                    $atts['template'] = 'logs';
                                    unset($_POST['vote']);
                                }
                            }else{
                                $atts['warning'] = __('Please vote','polls');
                            }
                        }
                    }
                }else{
                    $atts['status'] = 0;
                }
            }
            $this->view->atts = $atts;
            $this->view->render();
        }else if($check == FALSE){
            $atts['polls'] = $this->polls_model->get_polls($polls_id);
            $atts['answers'] = $this->answers_model->get_list_answers($polls_id);
            $atts['question'] = $atts['polls']['polls_question'];
            $atts['list_answers'] =  $atts['answers'];
            $atts['status'] = $atts['polls']['polls_status'];
            $atts['total_vote'] = 0;
            foreach ($atts['list_answers'] as $key => $item){
                 $atts['total_vote'] =  $atts['total_vote'] + $item['pollsa_vote'];
            }
            $atts['template'] = 'logs';
            $this->view->atts = $atts;
            $this->view->render();
        }
    }
}