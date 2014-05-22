<?php
/**
 * Created by JetBrains PhpStorm.
 * User: rahulbhattacharya
 * Date: 10/16/12
 * Time: 11:42 AM
 * To change this template use File | Settings | File Templates.
 */

defined('_JEXEC') or die;
?>

<div class="<?php echo $params->get('moduleclass_sfx'); ?>"
    <?php  printf("The ticket was created and its ID is: %s\n", $ticket_mask_id); ?>
</div>