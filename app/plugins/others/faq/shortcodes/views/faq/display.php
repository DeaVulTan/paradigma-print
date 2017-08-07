<?php   defined('PF_VERSION') OR header('Location:404.html'); 
        public_css(RELATIVE_PATH . "/app/plugins/others/faq/shortcodes/assets/public.css", TRUE);
        $faqs = $this->atts['faqss'];
        $list_question = $this->atts['list_question'];
        
        if (!empty($faqs)):
?>
<?php $id_faq = uniqid();?>
<div  id="<?php echo $id_faq; ?>" data-url="<?php echo public_url('', true); ?>" ></div>
<div class="container">
        <div class="row">
          <div class="col-sm-4 pfFAQquestion">
            <!-- Search -->
            <form role="form" action="javascript:void(0);">
              <div class="well">
                <div class="row faq-ques">
                  <div class="col-sm-12">
                    <div class="input-group">
                      <?php echo form_input(array('type' => 'text','name' => 'question','class' => 'form-control key-search-question')); ?>
                      <span class="input-group-btn">
                        <button class="btn btn-theme-primary search-question" type="button"><i class="fa fa-search"></i></button>
                      </span>
                    </div><!-- /input-group -->
                  </div><!-- /.col-lg-6 -->
                </div><!-- /.row -->
              </div>
            </form>
            <!-- question -->
            <div class="panel-group" id="help-nav">
              <?php
                $i = 0;
                foreach($faqs as $id=>$faq):
                $i++;
              ?>
              <div class="panel panel-default faq-ques">
                  <div class="panel-heading list-ques" name="<?php echo $this->atts['faqid']?>">
                      <a data-toggle="collapse" data-parent="#help-nav" href="#help-nav-<?php echo $id?>" class="<?php if($i != 1){echo 'collapsed';}?> view_question" id="<?php echo $id;?>">
                            <?php 
                                if(isset($faq['title'])){
                                    echo e($faq['title']);
                                }
                            ?>
                      </a>
                  </div>
               </div>
                <?php
                endforeach;
            ?>
            </div>
          </div>    
          <!-- answer -->      
          <div id="detailQuestion" class="col-sm-6 pfFAQQuestion">
            <h3 class="headline"><span><?php echo $this->atts['title_qes'];?></span></h3>
            <div class="list-question">
                <ol class="list_all_quetion">
                        <?php
                          foreach($list_question as $key2 =>  $value2):
                      ?>
                        <li><a href="javascript:void(0);" name="<?php echo $key2;  ?>" class="view_answer">
                            <?php echo $value2 ;  ?>
                        </a></li>
                      <?php 
                        endforeach; 
                      ?>
                </ol>
            </div>
          </div>
          <div class="col-xs-7 pagination-load">
                <?php echo $this->atts['pagination_question'];?>
         </div>
         <div class="col-xs-7 pagination-question">
         </div>
         <div class="col-xs-7 pagination-search">
         </div>
        </div> <!-- / .row -->
        <?php
    endif;
?>
</div>
<script type="text/javascript">
    $(document).ready(function(){
    	var baseURL = window.location.href;
    	//view question
        $(".view_question").on('click',function(){
            id = $(this).attr("id");
            $.post(baseURL + '/faq_code:list_question_faq/ajax:1', {id: id}, function(result) {
        if (result.length > 0) {
            var data = JSON.parse(result);
            $(".headline").html(data['title']);
			$(".pagination-load").hide();
			$(".pagination-search").hide();
			$(".pagination-question").show();
			$(".list_all_quetion").html(data['list_question']);
			$(".pagination-question").html(data['pagination-div']);
			$(".pagination-load-question").html(data['pagination-question']);
            }
        });
    });
    //pagination question
    $(document).on('click','.pagination-load-question ul a',function(e){
        id = $(".pagination-load-question").attr('name');
        var href = $(this).attr('href');
        var result = href.split('page-faq:');
        page = !isNaN(result[1]) ? result[1] : 1;
        e.preventDefault();
        $.post(baseURL +'/faq_code:load_question_pagination/ajax:1', {id: id,page: page}, function(result){
          if (result.length > 0) {
            var data = JSON.parse(result);
            $(".list_all_quetion").html(data['list_question']);
            $(".pagination-load-question").html(data['pagination_question']);
            $("#act_script").html(data['jquery']);
        }
      });
    });
    //view answer
    $(document).on('click','.view_answer',function(){
        id = $(this).attr("name"); 
        $.post(baseURL + '/faq_code:list_answer_faq/ajax:1', {id: id}, function(result){
            if (result.length > 0) {
                var data = JSON.parse(result);
                $(".headline").html(data['title']);
                $(".list_all_quetion").html(data['answer']);
                $(".pagination-load").hide();
                $(".pagination-question").hide();
                $(".pagination-search").hide();
            }
        });	
    });
    //pagination question first
    $(".pagination-load").on('click','a',function(e){ 
        var href = $(this).attr('href');
        var result = href.split('page-faq:');
        page = !isNaN(result[1]) ? result[1] : 1;
        e.preventDefault();
        $.post(baseURL + '/faq_code:load_pagination/ajax:1', {page: page}, function(result){
          if (result.length > 0) {
            var data = JSON.parse(result);
            $(".list_all_quetion").html(data['list_question']);
            $(".pagination-load").html(data['pagination_question']);
            $("#act_script").html(data['jquery']);
        }
      });
    });
    //search 
    $(".search-question").on('click',function(){
    	key = $(".key-search-question").val();
    	if(key == ''){
    	    return false;
    	}
    	else{
            key =  $('.key-search-question').val();
            id = $('.panel-heading').attr('name');
            $.post(baseURL + '/faq_code:load_list_question_search/ajax:1', {id : id , key : key}, function(result){
                if(result.length > 0){
                    var data = JSON.parse(result);
                    $(".list_all_quetion").html(data['question']);
                    $(".pagination-load").hide();
                    $(".pagination-question").hide();
                    $(".pagination-search").show();
                    $(".pagination-search").html(data['pagination_question']);
                    $(".headline").html(data['title']);
                }
            });
       }
    });
    //pagination search
    $(document).on('click','.pagination-search ul a',function(e){
         var href = $(this).attr('href');
         var result = href.split('page-faq:');
         page = !isNaN(result[1]) ? result[1] : 1;
         e.preventDefault();
         $.post(baseURL +'/faq_code:pagination_search/ajax:1', {page: page}, function(result){
        	if(result.length > 0){
            	var data = JSON.parse(result);
            	$(".list_all_quetion").html(data['list_question']);
                $(".pagination-search").html(data['pagination_question']);
        	}	
         });
    });
});
</script>