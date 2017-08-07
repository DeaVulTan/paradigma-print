<?php
defined('PF_VERSION') OR header('Location:404.html');
class Pf_Paginator
{

    /**
     * set the number of items per page.
     *
     * @var numeric
     */
    private $num_per_page;

    /**
     * set get parameter for fetching the page number
     *
     * @var string
     */
    private $param_name;

    /**
     * sets the page number.
     *
     * @var numeric
     */
    private $page;

    /**
     * set the total number of records/items.
     *
     * @var numeric
     */
    private $total_rows = 0;

    /**
     *
     * pass values when class is istantiated
     *
     * @param numeric $num_per_page sets the number of items per page
     * @param numeric $param_name sets the instance for the GET parameter
     */
    public function __construct($total_rows, $num_per_page, $param_name, $page = null)
    {
        $this->param_name = $param_name;
        $this->num_per_page = $num_per_page;
        $this->total_rows = $total_rows;

        if ($page == null) {
            $this->page = (int) (!isset($_GET [$this->param_name]) ? 1 : $_GET [$this->param_name]);
            $this->page = ($this->page == 0 ? 1 : $this->page);
        } else {
            $this->page = ((int) $page > 1) ? $page : 1;
        }
    }

    public function page_lable()
    {
        $offset = ($this->page - 1) * $this->num_per_page + 1;
        $number_per_page = $offset + $this->num_per_page - 1;
        if ($number_per_page > $this->total_rows) {
            $number_per_page = $this->total_rows;
        }
        if($this->total_rows>1)
            return __('Displaying records ','includes') . $offset . ' - ' . $number_per_page . __(' of ','includes') . $this->total_rows;
        else
            return __('Displaying ','includes'). $this->total_rows . __(' records ','includes') ;
    }

    /**
     * page_links
     *
     * create the html links for navigating through the dataset
     *
     * @var sting $path optionally set the path for the link
     * @var sting $ext optionally pass in extra parameters to the GET
     * @return string returns the html menu
     */
    public function short_page_link()
    {
        
        $pagination = '';
        if($this->num_per_page == 0){
            return $pagination;
        }
        $totalpage = ceil($this->total_rows / $this->num_per_page);
        if ($totalpage >= 1) {
            $pagination = '<div class="pull-right">
                             <div class="form-inline" id="pagingControl">
                	            <div class="form-group">
                                    <label for="Page">' . __('Page: ', 'includes') . '</label>
                                    <select class="form-control" data-url="' . admin_url($this->param_name . '=') . '&'.$this->param_name.'="  onchange="window.location=$(this).data(\'url\')+$(this).val();">';
            for ($i = 1; $i <= $totalpage; $i++) {
                if ($this->page == $i) {
                    $pagination .= '<option value="' . $i . '" selected="selected">' . $i . '</option>';
                } else {
                    $pagination .= '<option value="' . $i . '">' . $i . '</option>';
                }
            }
            $pagination .= '         </select>
                                </div>
    	                     </div>
                          </div>';
        }
        return $pagination;
    }

    public function page_links($path = '?', $ext = '', $public = false)
    {
        $pagination = "";
        if($this->num_per_page == 0){
            return $pagination;
        }
        $adjacents = "2";
        $prev = $this->page - 1;
        $next = $this->page + 1;
        $lastpage = ceil($this->total_rows / $this->num_per_page);
        $lpm1 = $lastpage - 1;

        $param_name = $this->param_name . ($public === true ? ':' : '=');
        if (($lastpage == 1 && !$public) || $lastpage > 1) {
            $pagination .= "<ul class='pagination pull-right no-margin'>";
            if ($this->page > 1)
                $pagination .= "<li><a href='" . $path . $param_name . $prev . "$ext'><i class='fa fa-angle-double-left'></i> ".__("Previous","includes")."</a></li>";
            else
                $pagination .= "<li class='disabled'><span><i class='fa fa-angle-double-left'></i> ".__("Previous","includes")."</span></li>";

            if ($lastpage < 7 + ($adjacents * 2)) {
                for ($counter = 1; $counter <= $lastpage; $counter ++) {
                    if ($counter == $this->page)
                        $pagination .= "<li class='active'><span>$counter</span></li>";
                    else
                        $pagination .= "<li><a href='" . $path . $param_name . $counter . "$ext'>$counter</a></li>";
                }
            } elseif ($lastpage > 5 + ($adjacents * 2)) {
                if ($this->page < 1 + ($adjacents * 2)) {
                    for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter ++) {
                        if ($counter == $this->page)
                            $pagination .= "<li class='active'><span>$counter</span></li>";
                        else
                            $pagination .= "<li><a href='" . $path . $param_name . $counter . "$ext'>$counter</a></li>";
                    }
                    $pagination .= "<li class='disabled'><span>...</span></li>";
                    $pagination .= "<li><a href='" . $path . $param_name . $lpm1 . "$ext'>$lpm1</a></li>";
                    $pagination .= "<li><a href='" . $path . $param_name . $lastpage . "$ext'>$lastpage</a></li>";
                } elseif ($lastpage - ($adjacents * 2) > $this->page && $this->page > ($adjacents * 2)) {
                    $pagination .= "<li><a href='" . $path . $param_name . "1" . "$ext'>1</a></li>";
                    $pagination .= "<li><a href='" . $path . $param_name . "2" . "$ext'>2</a></li>";
                    $pagination .= "<li class='disabled'><span>...</span></li>";
                    for ($counter = $this->page - $adjacents; $counter <= $this->page + $adjacents; $counter ++) {
                        if ($counter == $this->page)
                            $pagination .= "<li class='active'><span>$counter</span></li>";
                        else
                            $pagination .= "<li><a href='" . $path . $param_name . $counter . "$ext'>$counter</a></li>";
                    }
                    $pagination .= "<li class='disabled'><span>...</span></li>";
                    $pagination .= "<li><a href='" . $path . $param_name . $lpm1 . "$ext'>$lpm1</a></li>";
                    $pagination .= "<li><a href='" . $path . $param_name . $lastpage . "$ext'>$lastpage</a></li>";
                } else {
                    $pagination .= "<li><a href='" . $path . $param_name . "1" . "$ext'>1</a></li>";
                    $pagination .= "<li><a href='" . $path . $param_name . "2" . "$ext'>2</a></li>";
                    $pagination .= "<li class='disabled'><span>...</span></li>";
                    for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter ++) {
                        if ($counter == $this->page)
                            $pagination .= "<li class='active'><span>$counter</span></li>";
                        else
                            $pagination .= "<li><a href='" . $path . $param_name . $counter . "$ext'>$counter</a></li>";
                    }
                }
            }

            if ($this->page < $counter - 1)
                $pagination .= "<li><a href='" . $path . $param_name . $next . "$ext'>".__("Next","includes")." <i class='fa fa-angle-double-right'></i></a></li>";
            else
                $pagination .= "<li class='disabled'><span>".__("Next","includes")." <i class='fa fa-angle-double-right'></i></span></li>";
            $pagination .= "</ul>\n";
        }

        return $pagination;
    }

}
