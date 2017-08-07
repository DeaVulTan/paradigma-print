<?php
defined ( 'PF_VERSION' ) or header ( 'Location:404.html' );
class Core_Plugin extends Pf_Plugin {
    public $name = 'Plugin Core';
    public $version = '1.0';
    public $author = 'Vitubo Team';
    public $description = 'This is the Plugin Core description';
    public function activate() {
		
    }
    public function deactivate() {
		
    }
    
    public function public_init(){
        $general_tags = array(
                'h1','h2','h3','h4','h5','h6','div','span','p','strong','em','abbr','a','address',
                'blockquote','footer','cite','ul','li','ol','dl','dt', 'img','iframe','small',
                'fullwidth'
        );
        
        foreach ( $general_tags as $v ) {
            $this->add_shortcode('html', $v, 'general');
        }
        
        /*foreach ( $general_tags as $v ) {
            $this->add_shortcode('bt', $v, 'general');
        }*/
        
        $general_tags = array(
                'fullwidth','button','button_group','icon','row','col','tabs','tab','accordion','collapse','br','img',
                'ribbon','pricing2','pricing_head2','pricing_content2','pricing_button2','open_div','close_div','container'
        );
        
        foreach ( $general_tags as $v ) {
            $this->add_shortcode('html', $v);
        }
        $this->add_shortcode('html', '_row','row');
        $this->add_shortcode('html', '__row','row');
        
        $this->add_shortcode('html', '_col','col');
        $this->add_shortcode('html', '__col','col');
        
        $this->add_shortcode('html', '_container','container');
        $this->add_shortcode('html', '__container','container');
        
        $this->add_shortcode('html', 'get');
        $this->add_shortcode('html', 'script');
        $this->add_shortcode('html', 'title');
        $this->add_shortcode('html', 'hr');
        $this->add_shortcode('html', 'comment');
        $this->add_shortcode('html', 'table');
        $this->add_shortcode('html', 'tr');
        $this->add_shortcode('html', 'td');
        $this->add_shortcode('html', 'th');
        $this->add_shortcode('html', 'i');
        $this->add_shortcode('html', 'source');
        $this->add_shortcode('html', 'code');
        
        $alerts = array('alert-success','alert-info','alert-warning','alert-danger');
        foreach ($alerts as $tag){
            $this->add_shortcode('html',$tag, 'alert');
        }
        $this->add_shortcode('php', 'url');
        $this->add_shortcode('php', 'datetime');
        $this->add_shortcode('php', 'comment');
        
        $this->add_shortcode('google', 'map');
        $this->add_shortcode('youtube', 'video');
        $this->add_shortcode('vimeo', 'video');
        $this->add_shortcode('soundcloud', 'audio');
        $this->add_shortcode('player', 'video');
        $this->add_shortcode('player', 'audio');
        $this->add_shortcode('livestream', 'video');
        
        $this->add_shortcode('social', 'tweets');
        $social_icons    =   array('rss','facebook','twitter','vimeo','googleplus','pintrest','linkedin','dropbox','picasa','spotify','jolicloud','wordpress','github','xing');
        foreach ($social_icons as $v){
            $this->add_shortcode('social',$v, 'icon');
        }
        
        $thumbs= array('normal','zoom');
        foreach ($thumbs as $v){
            $this->add_shortcode('thumb',$v, 'thumbnail');
        }
        
        $this->add_shortcode('pricings', 'option');
        $this->add_shortcode('pricings', 'inner');
        $this->add_shortcode('pricings', 'header');
        $this->add_shortcode('pricings', 'number');
        $this->add_shortcode('pricings', 'body');
        $this->add_shortcode('pricings', 'joint_inner');
        $this->add_shortcode('pricings', 'table_responsive');


        $this->add_shortcode('list','li', 'li');
        $this->add_shortcode('list','_li', 'li');
        $this->add_shortcode('list','__li', 'li');
        
        $this->add_shortcode('list','ol', 'ol');
        $this->add_shortcode('list','_ol', 'ol');
        $this->add_shortcode('list','__ol', 'ol');
        
        $this->add_shortcode('list','ul', 'ul');
        $this->add_shortcode('list','_ul', 'ul');
        $this->add_shortcode('list','__ul', 'ul');
        
        
        $breadcrumbs = array('page','post','post-category','gallery','portfolio','portfolio-category');
        foreach ($breadcrumbs as $tag){
            $this->add_shortcode('breadcrumb', $tag, 'show');
        }
        $this->add_shortcode('breadcrumb', 'remove');
        $this->add_shortcode('rmbreadcrumb', 'rm');
        $boxline_class  =   array('top','left','right','bottom');
        foreach ($boxline_class as $v){
            $this->add_shortcode('borderbox', $v, 'borderbox');
        }
        
        $this->add_shortcode('slider','jcarousel');
        $this->add_shortcode('slider','carousel');
        $this->add_shortcode('slider','img');
        
        
        $this->add_shortcode('style', 'colorbox');
        
        $this->add_shortcode('moza', 'slider');
        $this->add_shortcode('moza', 'slide');
        $this->add_shortcode('html', 'input');
        
    }
}