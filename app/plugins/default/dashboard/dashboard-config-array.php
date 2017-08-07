<?php defined('PF_VERSION') OR header('Location:404.html');?>
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
$setting = Pf::setting();

return array(
    'announcement'=>array(
        'action'=>array(
            'plugin_active'=>array(
                'name_plugin'=>'announcement'
            )
        )
    ),
    'comment'=>array(
        'table'=>''.DB_PREFIX.'comments',
        'action'=>array(
            'count'=>array(
                'approve'=>'comment_status',
                'label'=>__('Comments','dashboard'),
                'icon'=>'fa fa-comments',
                'url'=>admin_url('admin-page=comment', false),
                'columns_count'=>'id'
            ),
            'approve'=>array(
                'label'=>__('Latest Unpublished Comments','dashboard'),
                'url'=>admin_url('admin-page=comment&status=2', false),
                'token'=>'id',
                'active'=>$setting->get_element_value('pf_comment','enable') && $setting->get_element_value('pf_comment','approve_flag') ,
                'acl'=>array(1,2),
                'columns'=>array(
                    'status'=>'comment_status',
                    'label'=>'comment_author',
                    'id'=>'id',
                    'content'=>'comment_content',
                    'author'=>'comment_author',
                    'date_up'=>'comment_created_date'
                ),
            )
        )
    ),
    'users'=>array(
        'table'=> ''.DB_PREFIX.'users',
        'action'=>array(
            'count'=>array(
                'approve'=>'user_activation',
                'label'=>__('Users','dashboard'),
                'icon'=>'fa fa-user',
                'url'=>admin_url('admin-page=users', false),
                'backgorund_box'=>'bg-yellow',
                'columns_count'=>'id'
            ),
        ),
    ),
    'posts'=>array(
        'table'=> ''.DB_PREFIX.'posts',
        'action'=>array(
            'count'=>array(
                'approve'=>'post_status',
                'label'=>__('Posts','dashboard'),
                'icon'=>'fa fa-edit',
                'url'=>admin_url('admin-page=post&sub_page=post', false),
                'backgorund_box'=>'bg-green',
                'columns_count'=>'id'
            ),
            'approve'=>array(
                'label'=>__('Latest Unpublished Posts','dashboard'),
                'url'=>admin_url('admin-page=post&sub_page=post&status=2', false),
                'token'=>'id',
                'acl'=>array(1,2),
                'columns'=>array(
                    'status'=>'post_status',
                    'label'=>'post_title',
                    'id'=>'id',
                    'content'=>'post_title',
                    'author'=>'post_author',
                    'date_up'=>'post_created_date'
                ),
            )
        )
    ),
    'pages'=>array(
        'table'=> ''.DB_PREFIX.'pages',
        'action'=>array(
            'count'=>array(
                'approve'=>'page_status',
                'label'=>__('Pages','dashboard'),
                'icon'=>'fa fa-book',
                'url'=>admin_url('admin-page=page', false),
                'backgorund_box'=>'bg-red',
                'columns_count'=>'id'
            )
        ),
    ),
);