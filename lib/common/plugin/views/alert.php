<?php
defined('PF_VERSION') OR header('Location:404.html');
if (isset($error)):
    ?>
    <div class="notification hidden" data-type="error">
        <p class="content">
            <i class="fa fa-times"></i> <?php echo $error; ?>
        </p>
    </div>
    <?php
else:
    $alertType = array('danger', 'success', 'info');
    $icons = array('danger' => 'fa-times', 'success' => 'fa-check', 'info' => 'fa-check');
    foreach ($alertType as $type):
        if ($this->session->has_flash($type)):
            $tmp = $type === 'danger' ? 'error' : $type;
            ?>
            <div class="notification hidden" data-type="<?php echo $type; ?>">
                <p class="content">
                    <i class="fa <?php echo $icons[$type]; ?>"></i> <?php echo $this->session->flash_data($type); ?>
                </p>
            </div>
            <?php
            break;
        endif;
    endforeach;
endif;