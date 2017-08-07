<?php defined('PF_VERSION') OR header('Location:404.html');?>
<?php
/*
 * * 
 * @package  Vitubo
 * @author  Vitubo Team 
 * @copyright Vitubo Team
 * @link  http://www.vitubo.com
 * @since  Version 1.0
 * @filesource
 *
 */

/**
 * Description of backup
 *
 * @author Admin
 */
class Pf_Backup
{

    //put your code here
    protected $plczip;
    protected $host;
    protected $user;
    protected $pass;
    protected $name;
    protected $char;
    protected $return = '';
    protected $tables = '*';
    protected $link;
    protected $dateformat;
    protected $config_backup;
    
    public function __construct()
    {   
        $this->config_backup    =   get_configuration('backup_dir');
        $this->dir  = (!empty($this->config_backup)&& is_dir($this->config_backup))?$this->config_backup:ABSPATH.'/tmp/';
        $this->host = DB_HOST;
        $this->user = DB_USER;
        $this->pass = DB_PASSWORD;
        $this->name = DB_NAME;
        $this->char = DB_CHARSET;
        $this->link = Pf::database()->get_link();
        $this->dateformat   = get_configuration('long_date');
    }
    public function list_all() {
        $return ='';
        chdir($this->dir);
        $list = glob('*.zip');
        foreach ($list as $file) {
            $detail = $this->file_detail($file);
            $return.="<tr id='" . $file . "' style='height: 39px;'>
	<td style='width: 217px;'>" . $file . "</td>
	<td style='width: 272px;'>" . date($this->dateformat, $detail['date']) . "</td>
	<td style='width: 272px;'>" . $detail['size'] . "</td>
	<td style='width: 240px;'>
		<a class='btn btn-success btn-xs' onclick=\"javascript:ajax_action('restore', '" . $file . "');\" >
			<i class='fa fa-pencil-square-o'></i> " . __('Restore', 'configuration') . "
		</a> 
		<a class='btn btn-info btn-xs' href='" . admin_url('&sub_page=backup&action=download&id=' . $file) . "' >
			<i class='fa fa-download'></i> " . __('Download', 'configuration') . "
		</a>
		<a class='btn btn-danger btn-xs' onclick=\"javascript:ajax_action('delete', '" . $file . "')\" >
			<i class='fa fa-trash-o'></i> " . __('Delete', 'configuration') . "
		</a>
	</td>
</tr>";
            
        }
        return $return;
    }
    public function list_backup()
    {
        chdir($this->dir);
        $list = glob('*.zip');
        foreach ($list as $file) {
            $detail = $this->file_detail($file);
            ?>
            <tr id='<?php echo $file; ?>' style="height: 39px;">
                <td style="width: 217px;"><?php echo $file; ?></td>
                <td style="width: 272px;"><?php echo date($this->dateformat, $detail['date']); ?></td>
                <td style="width: 272px;"><?php echo $detail['size']; ?></td>
                <td style="width: 240px;">
                    <a class="btn btn-success btn-xs" onclick="javascript:ajax_action('restore', '<?php echo $file; ?>');" >
                        <i class="fa fa-pencil-square-o"></i> <?php echo __('Restore', 'configuration'); ?>
                    </a> 
                    <a class="btn btn-info btn-xs" href="<?php echo admin_url('&sub_page=backup&action=download&id=' . $file); ?>" >
                        <i class="fa fa-download"></i> <?php echo __('Download', 'configuration'); ?>
                    </a>
                    <a class="btn btn-danger btn-xs" onclick="javascript:ajax_action('delete', '<?php echo $file; ?>');" >
                        <i class="fa fa-trash-o"></i> <?php echo __('Delete', 'configuration'); ?>
                    </a>
                </td>
            </tr>

        <?php
        }
    }
    public function ajax_list() {
        $return =   "<table class='bootstrap-table'>
	<thead>
		 <tr>
			<th data-fixed='left' style='width:300px'>".__('File Name', 'configuration')."</th>
			<th>".__('Created Date', 'configuration')."</th>
			<th>".__('File Size (kB)', 'configuration')."</th>
			<th data-fixed='right' style='width: 24%; text-align: center;'>". __('Actions', 'configuration')."</th>
		</tr>
	</thead>
        <tbody id='list-backup'>";
        $return .=  $this->list_all();
        $return .=  "</tbody>
                                </table>";
        die($return);
    }
    public function file_detail($filename)
    {
        $file = $this->dir . $filename;
        if (!is_file($filename)) {
            $msg = "File is missing";
        } else {
            $file_d['size'] = filesize($file);
            $file_d['date'] = filectime($file);
        }
        return $file_d;
    }

    public function backup()
    {
        if ($this->tables == '*') {
            $this->tables = array();
            $result = mysqli_query($this->link,'SHOW TABLES');
            while ($row = mysqli_fetch_row($result)) {
                $this->tables[] = $row[0];
            }
        } else {
            $this->tables = is_array($this->tables) ? $this->tables : explode(',', $this->tables);
        }

        //cycle through
        foreach ($this->tables as $table) {
            $result = mysqli_query($this->link,'SELECT * FROM ' . $table);
            $num_fields = mysqli_num_fields($result);

            $this->return.= 'DROP TABLE IF EXISTS ' . $table . ';';
            $row2 = mysqli_fetch_row(mysqli_query($this->link,'SHOW CREATE TABLE ' . $table));
            $this->return.= "\n\n" . $row2[1] . ";\n\n";

            for ($i = 0; $i < $num_fields; $i++) {
                while ($row = mysqli_fetch_row($result)) {
                    $this->return.= 'INSERT INTO ' . $table . ' VALUES(';
                    for ($j = 0; $j < $num_fields; $j++) {
                        $row[$j] = addslashes($row[$j]);
                        $row[$j] = preg_replace("#\n#", "\\n", $row[$j]);
                        if (isset($row[$j])) {
                            $this->return.= '"' . $row[$j] . '"';
                        } else {
                            $this->return.= '""';
                        }
                        if ($j < ($num_fields - 1)) {
                            $this->return.= ',';
                        }
                    }
                    $this->return.= ");\n";
                }
            }
            $this->return.="\n\n\n";
        }

        //save file
        $time = date('Y-m-d_H-i-s', time());
        chdir($this->dir);
        $file   =   fopen('data.sql',"x+");
        fwrite($file, $this->return);
        $this->plczip   =   new PclZip($this->dir . '/db-backup-' . $time . '.zip');
        $v_list = $this->plczip->add('data.sql');
        fclose($file);
        unlink('data.sql');
        if (is_file($this->dir . '/db-backup-' . $time . '.zip')) {
            return __('Backup successfully','configuration');
        } else {
            return 'unsuccessfull';
        }
    }

    public function restore($filename,$after='')
    {        
        if (is_file($this->dir .$filename)) {
            $content = '';
            $files = $this->dir . $filename;
            $this->plczip   =   new PclZip($files);
            $this->plczip->extract(PCLZIP_OPT_PATH,$this->dir);
            $file = $this->dir . 'data.sql';
            if (file_exists($file))
                $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            else {
                    echo "<script>notif('".__('Your file is not in Vitubo backup format.','configuration')."')</script>";
                exit;
            }
            $buffer = '';

            foreach ($lines as $line) {
                if (substr(ltrim($line), 0, 2) == '--' || $line[0] == '#')
                    continue;
                if (($line = trim($line)) == '') {
                    continue;
                } else if ($line[strlen($line) - 1] != ";") {
                    $buffer .= $line;
                    continue;
                } else
                if ($buffer) {

                    $line = $buffer . $line;
                    $buffer = '';
                }
                $result = @mysqli_query($this->link,$line) or die(mysqli_error() . $line);
                if (!$result) {

                    $this->msg = mysqli_error();
                    die;
                    return $this->msg;
                    break;
                }
            }
        }
        else 
            $this->msg  =   'unsuccessfully';
        if (empty($this->msg)) {
            unlink($file);
            if($after=='delete'){unlink($this->dir .$filename);}
            $this->msg = __('Restore successfully', 'configuration');
        }
        die( $this->msg );
    }

    public function download($file)
    {
        $filename = $this->dir . $file;
        $fp = fopen($filename, "rb");

        header("Content-type: " . filetype($filename));
        header("Content-length: " . filesize($filename));
        header("Content-disposition: attachment;filename = " . $file . "");
        die(fpassthru($fp));
        fclose($fp);
    }
    private function findexts($filename) {
        $filename = strtolower($filename);
        $exts = explode(".", $filename);
        $n = count($exts) - 1;
        $exts = $exts[$n];
        return $exts;
    }
    function remove_allFile($dir) {
        if ($handle = opendir("$dir")) {
            while (false !== ($item = readdir($handle))) {
                if ($item != "." && $item != "..") {
                    if (is_dir("$dir/$item")) {
                        remove_directory("$dir/$item");
                    } else {
                        unlink("$dir/$item");
                    }
                }
            }
            closedir($handle);
        }
    }

    public function upload($file) {
        if(!is_dir($this->dir.'tmp'))
            mkdir ($this->dir.'tmp',0777);
        if ($file['error'] > 0)
            die('errorcode1');
        else {
            if ($this->findexts($file['name']) != 'zip') {
                unlink($file['tmp_name']);
                die('errorcode2');
            } else {
                $this->plczip = new PclZip($file['tmp_name']);
                $this->plczip->extract(PCLZIP_OPT_PATH, $this->dir.'tmp');
                $filetmp = $this->dir . 'tmp/data.sql';
                if (is_file($filetmp)) {
                    unlink($this->dir . 'tmp/data.sql');
                    move_uploaded_file($file["tmp_name"], $this->dir . "/" . $file["name"]);
                    die($file['name']);
                } else {
                    $this->remove_allFile($this->dir . 'tmp');
                    die('errorcode3');
                    exit;
                }
            }
        }
    }

    public function check_htaccess() {
        if(is_dir($this->dir)){
            if(!file_exists($this->dir."/.htaccess")){
                $hta    =   fopen($this->dir.'.htaccess', 'w');
                fwrite($hta, "Order deny,allow
    Deny from all");
                fclose($hta);
            }
            if(!file_exists($this->dir."/index.html")){
                $ind    =   fopen($this->dir.'index.html', 'w');
                fwrite($ind, "");
                fclose($ind);
            }
        }
    }
}
