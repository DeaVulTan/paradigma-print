<?php defined('PF_VERSION') OR header('Location:404.html');?>
<?php

/* 
 ** 
 * @package  Vitubo
 * @author  Vitubo Team 
 * @copyright Vitubo Team
 * @link  http://www.vitubo.com
 * @since  Version 1.0
 * @filesource
 *
 */
$ref= !empty($_GET['ref'])?urldecode($_GET['ref']):'user.php';
$userpage   =   public_url("user");
?>
<div class="form-box" id="login-box">
    <div class="header"><?php echo __('Sign In','user'); 
    echo "<p><h5>".$sitename." Administrator</h5></p>"; ?>
    </div>
        <div class="body bg-gray" style="float: left;width: 100%;">
            <div id="loading" style="display:none;"><img class='image-widthauto' src="<?php echo RELATIVE_PATH; ?>/app/themes/jupiter/img/Progressbar.gif" /></div>
            <div class="alert alert-danger alert-dismissable" id="notification" style="display:none;">
                <i class="fa fa-check"></i>
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <div id="content-alert"></div>
            </div>
            <div class="form-group">
                <input type="text" id="username" name="username" class="form-control" placeholder="<?php echo __('Username','user'); ?>"/>
            </div>
            <div class="form-group">
                <input type="password" id="password" name="password" class="form-control" placeholder="<?php echo __('Password','user'); ?>"/>
            </div>          
            <div class="form-group">
                <div class="col-md-6">
                    <input type="checkbox" id="remember" name="remember_me" value="1"/> <?php echo __('Remember me','user'); ?>
                </div>
                <div class="col-md-6">
                    <a href="<?php echo public_url('user/lostpassword/user_code:lostpassword/ajax:1'); ?>"><?php echo __('Lost your password?','user'); ?></a>
                </div>
            </div>
        </div>
        <div class="footer" style="  clear: left;">
            <button type="submit" name="submit" id="submit" class="btn bg-olive btn-block"><?php echo __('Sign In','user'); ?></button>  
        </div>
</div>
<script>
    $('#submit').click(function() { 
        login();
    });
    $('#login-box').keypress(function(event){
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if(keycode == '13'){
            login();
        }
    });
    function login(){
        $('#notification').hide();
        $('#content-alert').html('');
        var username    =   $('#username').val();
        var password    =   $('#password').val();
        if($('#remember').is(':checked')==true)
            remember    =   1;
        else
            remember    =   0;
        if(username.length>0 && password.length>0){
             $('#loading').show();
             $.ajax({
                    url: '<?php echo public_url('user/signin/user_code:signin/?ajax=1&ref='.$ref.''); ?>',
                    type: 'POST',
                    cache: false,
                    data: 'type=login&username='+username+'&password='+password+'&remember='+remember,
                    success: function(string){
                        $('#loading').hide();
                        if(string=='success'){
                           window.location.replace("<?php echo $ref; ?>");
                        }
                        else{
                            $('#notification').show();
                            $('#content-alert').html(string);
                        }
                    },
                    error: function (){
                        $('#notification').show();
                        $('#content-alert').html('<?php echo __('There is an error, please sign in again.', 'user'); ?>');
                    }
                });
            }
        else{
            $('#notification').show();
            if(username.length==0)
                $('#content-alert').html("<?php printf(__('Username needs more than %s characters', 'user'),1); ?><br/>");
            if(password.length==0)
                $('#content-alert').html($('#content-alert').html()+"<?php printf(__('Password needs more than %s characters', 'user'),1); ?>");
        }
    };
</script>