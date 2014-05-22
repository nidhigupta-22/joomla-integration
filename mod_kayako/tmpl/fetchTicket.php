<?php
/**
 * ###############################################
 *
 * Kayako Module
 * _______________________________________________
 *
 * @author        Rahul Bhattacharya
 *
 * @package       mod_kayako
 * @copyright    Copyright (c) 2001-2012, Kayako
 * @license      http://www.kayako.com/license
 * @link        http://www.kayako.com
 *
 * ###############################################
 */

defined('_JEXEC') or die;

require_once ('modules/mod_kayako/KayakoAPILibrary/kyIncludes.php');
require_once ('modules/mod_kayako/helper.php');

$email = JFactory::getUser()->email;
if(empty($email) || !isset($email))
{
    throw new kyException();
}

$input = JFactory::getApplication()->input;
$get_dep_id = $input->get('dep_type');

$ticket_object = new JTickets();
$ticket_object->fetchAllDepartment();
$ticket_object->view($get_dep_id);
?>

<link rel="stylesheet" type="text/css" href="modules/mod_kayako/theme/css/style.css">
<div class="<?php echo $params->get('moduleclass_sfx'); ?>">
<div>
    <form method="POST" name="department_form">
        <select name="dep_type" onchange="this.form.submit()">
            <option value="0">Select a department</option>
            <?php foreach($ticket_object->all_departments as $key=>$value)  { ?>
            <option value="<?php echo $key; ?>" <?php if($key == $get_dep_id) echo "selected"; ?>><?php echo $value; ?></option>
            <?php } ?>
        </select>
    </form>
</div>
    <table id="tickets">
        <tr>
            <th>Ticket Id</th>
            <th>Last Activity</th>
            <th>Last Replier</th>
            <th>Department</th>
            <th>Type</th>
            <th>Status</th>
            <th>Priority</th>
        </tr>
        <?php if(count($ticket_object->tickets) > 0)  { ?>
        <?php foreach($ticket_object->tickets as $list) { ?>
            <tr>
                <td colspan="7" style="text-align: left"><a id="ticket_link" href="index.php?id=<?php echo $list->getId(); ?>"><?php echo $list->getSubject(); ?></a></td>
            </tr>
            <tr>
                <td><?php echo $list->getDisplayId(); ?></td>
                <td><?php echo date('M d, Y h:i A', strtotime($list->getLastActivity())); ?></td>
                <td><?php echo $list->getLastReplier(); ?></td>
                <td><?php echo kyDepartment::get($list->getDepartmentId())->getTitle(); ?></td>
                <td><?php echo kyTicketType::get($list->getTypeId())->getTitle() ?></td>
                <td><?php echo kyTicketStatus::get($list->getStatusId())->getTitle() ?></td>
                <td style=" background-color: <?php echo kyTicketPriority::get($list->getPriorityId())->getBackgroundColor(); ?>;">
                    <span style="color: <?php echo kyTicketPriority::get($list->getPriorityId())->getForegroundColor(); ?>;">
                        <?php echo kyTicketPriority::get($list->getPriorityId())->getTitle();  ?>
                    </span>
                </td>
            </tr>
            <?php } ?>
        <?php } else { ?>
        <tr>
            <td colspan="7">No records...</td>
        </tr>
        <?php } ?>
    </table>
</div>