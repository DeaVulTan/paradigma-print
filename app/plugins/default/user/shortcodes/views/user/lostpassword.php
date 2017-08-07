<html class="bg-blue">
    <head>
        <meta charset="UTF-8">
        <title><?php echo __('Lost your password?','user');?></title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <!-- bootstrap 3.0.2 -->
        <link href="<?php echo site_url().RELATIVE_PATH;?>/media/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <!-- font Awesome -->
        <link href="<?php echo site_url().RELATIVE_PATH;?>/media/assets/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <!-- Theme style -->
        <link href="<?php echo site_url().RELATIVE_PATH;?>/admin/themes/default/assets/admin-lte/css/AdminLTE.css" rel="stylesheet" type="text/css" />
                <!-- jQuery 2.0.2 -->
        <script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
        <!-- Bootstrap -->
        <script src="<?php echo site_url().RELATIVE_PATH;?>/media/assets/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->
    </head>
    <body class="bg-blue">
        <div class="form-box" id="login-box">
            <div class="header"><?php echo __('Lost your password?','user'); 
            echo ('<p><h5>'); echo __('Please enter your email and we will send you a link to reset password.','user'); echo ('</h5></p>'); ?>
            </div>
                <div class="body bg-gray" style="float: left;width: 100%;">
                    <div id="loading" style="display:none;"><img class='image-widthauto' src="<?php echo RELATIVE_PATH; ?>/app/themes/jupiter/img/Progressbar.gif" /></div>
                    <div class="alert alert-danger alert-dismissable" id="notification" style="display:none;">
                        <i class="fa fa-check"></i>
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <div id="content-alert"></div>
                    </div>
                    <div class="form-group" id="username-lost">
                        <input type="text" id="username" name="username" class="form-control" placeholder="<?php echo __('Email','user'); ?>"/>
                    </div>
                    <div class="form-group">
                        <div class="col-md-6">
                            <a href="<?php echo public_url('admin/user.php?page=login'); ?>"><?php echo __('Sign In','user'); ?></a>
                        </div>
                        <div class="col-md-6">
                            <a href="<?php echo public_url(); ?>">← <?php echo __('Back to homepage','user'); ?></a>
                        </div>
                    </div>
                </div>
                <div class="footer" style="  clear: left;">
                    <button type="submit" name="submit" id="submit" class="btn bg-olive btn-block"><?php echo __('Send','user'); ?></button>  
                </div>
        </div>
        <script>
            $('#submit').click(function() { 
            	$('#notification').hide();
                $('#content-alert').html('');
                var username    =   $('#username').val();
                if(username.length>0){
                     $('#loading').show();
                     $.post('<?php echo public_url('user/recover/user_code:recover/ajax:1'); ?>',{recover_email:$('#username').val()},function(obj){
                    	 $('#loading').hide();
                         var alert = 'alert-success';
                         if (obj.error == 1){
                             alert = 'alert-danger';
                         }
                         $('#notification').addClass(alert);
                         if (obj.error == 1){
                        	 $('#notification').show();
                             $('#content-alert').html(obj.message);
                         }
                         if (obj.error == 0){
                        	 $('#notification').show();
                        	 $('#username-lost, .footer').hide();
                        	 $('#notification').removeClass('alert-danger');
                             $('#content-alert').html(obj.message);
                             
                         }
                         
                     },'json');
                }else{
                    $('#notification').show();
                    if(username.length==0)
                        $('#content-alert').html("<?php printf(__('Your email does not exist', 'user'),1); ?><br/>");
                }
            });
        </script>
    </body>
</html>