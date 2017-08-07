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
class Pf_Rating_Shortcode extends Pf_Plugin_Shortcode_Controller
{

    protected $attrs;

    public function __construct($attrs)
    {
        parent::__construct();
        $this->view->set_path('/app/plugins/others/rating/public/views');
        $this->model = new Pf_Rating_Model;
        $this->attrs = $attrs;
    }

    private function getAuthor()
    {
        if ((int) get_configuration('permission', 'pf_rating') === 0 || is_login()) {
            return current_user('user-id');
        }
        return getRealIpAddr();
    }

    private function checkPermissionRate()
    {
        if(!empty($this->attrs['read_only']) && $this->attrs['read_only'] == 'true'){
            return false;
        }
        if ((int) get_configuration('permission', 'pf_rating') === 0) {
            return is_login() ? true : false;
        }
        return true;
    }

    private function checkRate($key)
    {
        $author = $this->getAuthor();
        if (empty($author)) {
            return false;
        }
        $total = $this->model->conditions("rating_key = ? and rating_author = ?")
                ->param(array($key, $this->getAuthor()))
                ->count();
        return $total > 0 ? true : false;
    }

    private function getRating($key)
    {
        return $this->model->select('ifnull( round( sum( `rating_rate` ) / count( `id` ) , 2 ) , 0 ) avg, count( `id` ) total')
                        ->conditions('WHERE rating_key = ?')->param(array($key))->get(2);
    }

    private function getShowRating($key, $data = null)
    {
        if (empty($data)) {
            $rating = $this->getRating($key);
        } else {
            $rating = $data;
        }
        if (empty($rating)) {
            return '';
        }
        return "{$rating['avg']} / {$rating['total']} " . (($rating['total'] == 1) ? __('vote', 'pf_rating') : __('votes', 'pf_rating'));
    }

    public function rating_main()
    {
        if (!empty($this->attrs['key']) && get_configuration('enable', 'pf_rating')) {
            $key = $this->attrs['key'];
            $token =  Pf_Plugin_CSRF::token('rating_' . $key);
            $rating = $this->getRating($key);
            $this->data['key'] = $key;
            $this->data['ratings'] = $rating;
            $this->data['showRating'] = $this->getShowRating($key, $rating);
            $this->data['token'] = $token;
            $this->data['permission'] = $this->checkPermissionRate();
        }
        return $this->view->render('main', $this->data);
    }

    public function rating_add()
    {
        // Kiem tra xem REQUEST co phai la ajax khong va co day du du lieu khong
        if (!is_ajax() || !$this->input->has_post(array('key', 'rate', 'token'))) {
            return json_encode(array('code' => 0, 'msg' => __('You unauthorized access', 'pf_rating')));
        }
        $key = $this->input->post('key');
        if (!$this->checkPermissionRate()) {
            return json_encode(array('code' => 0, 'msg' => __('You can not rate', 'pf_rating')));
        }
        if (!Pf_Plugin_CSRF::is_valid($this->input->post('token'), 'rating_' . $key)) {
            return json_encode(array('code' => 0, 'msg' => __('Your session has ended', 'pf_rating'), 'token' => Pf_Plugin_CSRF::token('rating_' . $key)));
        }
        $data = array(
            'rating_author' => $this->getAuthor(),
            'rating_rate' => $this->input->post('rate'),
            'rating_key' => $key
        );
        if (!$this->checkRate($key)) {
            $result = $this->model->set_timestamps(false)->insert($data, false);
        } else {
            $result = $this->model->set_timestamps(false)->conditions('rating_key = ? and rating_author = ?')
                            ->param(array($key, $this->getAuthor()))->update($data, false);
        }
        if ($result) {
            $rating = $this->getRating($key);
            return json_encode(array('code' => 1, 'msg' => __('Thank you for rating', 'pf_rating'), 'token' => Pf_Plugin_CSRF::token('rating_' . $key), 'rating' => $rating, 'showRating' => $this->getShowRating($key, $rating)));
        }
        return __('There is an error', 'pf_rating');
    }

}
