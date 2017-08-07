<?php
defined ( 'PF_VERSION' ) or header ( 'Location:404.html' );
/**
 *
 * @package Vitubo
 * @author Vitubo Team
 * @copyright Vitubo Team
 * @link http://www.vitubo.com
 * @since Version 1.0
 * @filesource
 *
 */
class Category_Widget extends Pf_Widget {
    public $name = 'Post Category';
    public $version = '1.0';
    public $description = 'This is description';
    public function __construct($properties, $setting) {
        parent::__construct ( $properties, $setting );
    }
    public function setting() {
        $this->view->render ();
    }
    public function index() {
        if (! empty ( $this->setting ['widget-key-category'] )) {
            require_once ABSPATH . '/lib/common/plugin/helpers/helper.php';
            require_once ABSPATH . '/lib/common/plugin/utiles/pf-plugin-singleton.php';
            $data = Pf_Plugin_Singleton::categories ();
            if (empty ( $data )) {
                return;
            }
            $nodes = array ();
            $list = array ();
            foreach ( $data as &$node ) {
                $node->children = array ();
                $id = $node->id;
                $parent_id = $node->category_parent;
                $nodes [$id] = & $node;
                if (array_key_exists ( $parent_id, $nodes )) {
                    $nodes [$parent_id]->children [] = & $node;
                } else {
                    $list [] = & $node;
                }
            }
            $this->view->category_list = $list;
            $this->view->render();
        }
    }
}
