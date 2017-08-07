<?php defined('PF_VERSION') OR header('Location:404.html'); ?>
<?php if(!empty($this->atts['template']) && $this->atts['template'] === 'display'){?>
<?php if($this->atts['status'] == 1){?>
    <div class="sign-form">
      <div class="sign-inner">
        <h4><?php echo $this->atts['question']?></h4>
        <hr>
         <form id="login-form" role="form" method="post" action="<?php echo public_url($this->atts['page']);?>">
           <?php if(!empty($this->atts['warning'])){?> <label><?php echo $this->atts['warning'];?></label><?php }?>
            <input type="hidden" name="type" value="polls"  />
            <input type="hidden" class="polls-multiple" value="<?php echo $this->atts['polls']['polls_multiple']?>"></input>
             <?php foreach ($this->atts['list_answers'] as $item){?>
              <div class="input-group">
                <?php if($this->atts['polls']['polls_multiple'] == 1){?>
                    <input type="radio" name="vote[0]"  value="<?php echo $item['id']?>" /> <?php echo $item['pollsa_answers']?> 
                <?php }else{?>
                    <input type="checkbox" name="vote[]"  value="<?php echo $item['id']?>" /> <?php echo $item['pollsa_answers']?>
                <?php }?>
              </div>
              <?php }?>
              <br>
              <button type="submit" class="btn btn-theme-primary"><?php echo __("Vote","polls"); ?></button>
        </form>
      </div>
    </div>
<?php }else { ?>
    <div class="sign-form">
         <div class="sign-inner">
             <h4><?php echo __("Sorry, there are no polls available at the moment.","polls"); ?></h4>
         </div>
    </div>
<?php }?>
<?php }else if(!empty($this->atts['template']) && $this->atts['template'] === 'logs'){?>
     <?php if($this->atts['status'] == 1){?>
     <div class="sign-form">
          <div class="sign-inner">
             <h4><?php echo $this->atts['question']?></h4>
             <hr>
             <table width="100%" cellpadding="0" cellspacing="0" border="0">
                <tbody>
                    <?php foreach ($this->atts['list_answers'] as $item){?>
                    <tr>
                        <td class="pollAnswer" width="40%"><p><?php echo $item['pollsa_answers']?></p></td>
                        <td>
                            <div style="float:left;width:40%;">
                                <div style="width:<?php echo number_format($item['pollsa_vote']*100/$this->atts['total_vote'],2);?>%;" class="pollAnswerBar"></div>
                            </div>
                            <div style="float:left;margin-left:20px;"><?php if($this->atts['display_percentage '] == 1){echo number_format($item['pollsa_vote']*100/$this->atts['total_vote'],2).' %  '; }else{echo '';}?><?php if($this->atts['display_vote'] == 1){echo $item['pollsa_vote'].'&nbsp'.__('votes','polls');}else{echo '';}?></div>
                        </td>
                    </tr>
                    <?php }?>
                    <td class="pollAnswer"><b><?php echo __("Total Votes","polls"); ?>:</b></td>
                    <td><b><?php echo $this->atts['total_vote'];?> <?php echo __("votes","polls"); ?></b></td>
                </tbody>
			</table>
          </div>
     </div>
     <?php }else { ?>
       <div class="sign-form">
             <div class="sign-inner">
                 <h4><?php echo __("Sorry, there are no polls available at the moment.","polls"); ?></h4>
             </div>
        </div>
     <?php }?>
<?php }?>
<?php public_js(site_url().RELATIVE_PATH.'/admin/themes/default/assets/admin-lte/js/plugins/iCheck/icheck.min.js',true);?>
<?php public_js(site_url().RELATIVE_PATH.'/app/plugins/others/polls/shortcodes/assets/polls.js',true);?>
<?php public_css(site_url().RELATIVE_PATH.'/app/plugins/others/polls/shortcodes/assets/polls.css',true);?>