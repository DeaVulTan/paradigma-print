<?php defined('PF_VERSION') OR header('Location:404.html'); ?>
<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo __('System information', 'configuration'); ?>
                </h3>
            </div>
            <div class="panel-body">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#php_info" data-toggle="tab"><?php echo __('PHP Information', 'configuration'); ?></a></li>
                        <li><a href="#sever_info" data-toggle="tab"><?php echo __('Server Information', 'configuration'); ?></a></li>
                    </ul>
                    
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div class="tab-pane active" id="php_info">
                            <table class="table table-bordered table-configuration">
                                <col class="col-md-4" />
                                <tr>
                                    <th><label for="site_name" class="control-label"><?php echo __('Current PHP Version ', 'configuration'); ?></label></th>
                                    <td><?php echo phpversion();?></td>
                                </tr>
                                <tr>
                                    <th><label for="site_name" class="control-label"><?php echo __('GD version ', 'configuration'); ?></label></th>
                                    <td><?php echo "{$phpinfo['gd']['GD Version']}"; ?></td>
                                </tr>
                                <tr>
                                    <th><label for="site_name" class="control-label"><?php echo __('PHP Effective Memory Limit ', 'configuration'); ?></label></th>
                                    <td><?php echo "{$phpinfo['Core']['memory_limit'][0]}"; ?></td>
                                </tr>
                                <tr>
                                    <th><label for="site_name" class="control-label"><?php echo __('Maximum Execution Time ', 'configuration'); ?></label></th>
                                    <td><?php echo  "{$phpinfo['Core']['max_execution_time'][0]}"; ?></td>
                                </tr>
                                <tr>
                                    <th><label for="site_name" class="control-label"><?php echo __('PHP output_buffering ', 'configuration'); ?></label></th>
                                    <td><?php echo  "{$phpinfo['Core']['output_buffering'][0]}"; ?></td>
                                </tr>
                                <tr>
                                    <th><label for="site_name" class="control-label"><?php echo __('PHP Safe Mode ', 'configuration'); ?></label></th>
                                    <td><?php  echo  " {$phpinfo['Core']['sql.safe_mode'][0]}"; ?></td>
                                </tr>
                                <tr>
                                    <th><label for="site_name" class="control-label"><?php echo __('File uploads ', 'configuration'); ?></label></th>
                                    <td><?php echo  "{$phpinfo['Core']['file_uploads'][0]}"; ?></td>
                                </tr>
                                <tr>
                                    <th><label for="site_name" class="control-label"><?php echo __('Maximum Post Size ', 'configuration'); ?></label></th>
                                    <td><?php echo ini_get('post_max_size'); ?></td>
                                </tr>
                                <tr>
                                    <th><label for="site_name" class="control-label"><?php echo __('Maximum Upload Size', 'configuration'); ?></label></th>
                                    <td><?php echo ini_get('upload_max_filesize');?></td>
                                </tr>
                                <tr>
                                    <th><label for="site_name" class="control-label"><?php echo __('Session Save Path ', 'configuration'); ?></label></th>
                                    <td><?php echo   ini_get('session.save_path'); ?></td>
                                </tr>
                                <tr>
                                    <th><label for="site_name" class="control-label"><?php echo __('Sessions are allowed to use Cookies', 'configuration'); ?></label></th>
                                    <td><?php echo  "{$phpinfo['session']['session.use_cookies'][0]}"; ?></td>
                                </tr>
                                <tr>
                                    <th><label for="site_name" class="control-label"><?php echo __('Checking for the XMLReader class ', 'configuration'); ?></label></th>
                                    <td><?php  echo  "{$phpinfo['xmlreader']['XMLReader']}"; ?></td>
                                </tr>
                                <tr>
                                    <th><label for="site_name" class="control-label"><?php echo __('PHP register_gobals ', 'configuration'); ?></label></th>
                                    <td><?php echo  "{$phpinfo['Core']['register_argc_argv'][0]}"; ?></td>
                                </tr>
                                <tr>
                                    <th><label for="site_name" class="control-label"><?php echo __('PHP Open Basedir ', 'configuration'); ?></label></th>
                                    <td><?php echo  "{$phpinfo['Core']['open_basedir'][0]}"; ?></td>
                                </tr>
                                <tr>
                                    <th><label for="site_name" class="control-label"><?php echo __('Curl version ', 'configuration'); ?></label></th>
                                    <td><?php echo "{$phpinfo['curl']['cURL Information']}"; ?></td>
                                </tr>
                             </table>
                        </div>
                        <div class="tab-pane" id="sever_info">
                             <table class="table table-bordered table-configuration">
                                <col class="col-md-4" />
                                <tr>
                                    <th><label for="site_name" class="control-label"><?php echo __('Server API ', 'configuration'); ?></label></th>
                                    <td><?php echo php_sapi_name();?></td>
                                </tr>
                                <tr>
                                    <th><label for="site_name" class="control-label"><?php echo __('Server Database Version ', 'configuration'); ?></label></th>
                                    <td><?php  echo get_mysqlinfo(); ?></td>
                                </tr>
                                <tr>
                                    <th><label for="site_name" class="control-label"><?php echo __('Server software', 'configuration'); ?></label></th>
                                    <td><?php echo  $_SERVER['SERVER_SOFTWARE']; ?></td>
                                </tr>
                                <tr>
                                    <th><label for="site_name" class="control-label"><?php echo __('Server IP', 'configuration'); ?></label></th>
                                    <td><?php echo  $_SERVER['SERVER_ADDR']; ?></td>
                                </tr>
                                <tr>
                                    <th><label for="site_name" class="control-label"><?php echo __('Server doccument root', 'configuration'); ?></label></th>
                                    <td><?php echo  $_SERVER['DOCUMENT_ROOT']; ?></td>
                                </tr>
                                <tr>
                                    <th><label for="site_name" class="control-label"><?php echo __('Server Operating System ', 'configuration'); ?></label></th>
                                    <td><?php echo php_uname(); ?></td>
                                </tr>
                             </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>