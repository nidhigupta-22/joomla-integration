<?php
/**
 * @copyright      2001-2015 Kayako
 * @license        https://www.freebsd.org/copyright/freebsd-license.html
 * @link           https://github.com/kayako/joomla-integration
 */

defined('_JEXEC') or die;

require_once('modules/mod_kayako/includes/kayako-php-api/kyIncludes.php');
require_once('modules/mod_kayako/helper.php');

$email = JFactory::getUser()->email;
if (empty($email) || !isset($email)) {
	throw new kyException();
}

//Retrieve the get value
$input = JFactory::getApplication()->input;
//$edit_id = $input->get('id', null);
$edit_id = $_REQUEST['ticketid'];

JTickets::init();

$all_departments   = kyDepartment::getAll()->filterByModule(kyDepartment::MODULE_TICKETS)->filterByType(kyDepartment::TYPE_PUBLIC);
$department_titles = kyDepartment::getAll()->collectTitle();
$department_id     = kyDepartment::getAll()->collectId();

$priority_list = kyTicketPriority::getAll();
$status_list   = kyTicketStatus::getAll();
$ticket_type   = kyTicketType::getAll();

$ticket_details = kyTicket::get($edit_id);

$document = &JFactory::getDocument();
$document->addScript('/media/jui/js/jquery.min.js');
?>

<link rel="stylesheet" type="text/css" href="modules/mod_kayako/resources/css/style.css">

<script>
	jQuery(document).ready(function() {
		jQuery('#Reply').click(function() {
			jQuery('#div_reply').slideToggle("fast");
		});
	});

	function AddTicketFile(_namePrefix) {
		jQuery('#' + _namePrefix + 'attachmentcontainer').append('<div class="ticketattachmentitem"><div class="ticketattachmentitemdelete" onclick="javascript: jQuery(this).parent().remove();">&nbsp;</div><input name="' + _namePrefix + 'attachments[]" type="file" size="20" class="swifttextlarge swifttextfile" /></div>');
	}
	;
</script>

<div class="<?php echo $params->get('moduleclass_sfx'); ?>">
	<form method="POST" name="update_ticket">
		<h4><?php echo "[" . $ticket_details->getDisplayId() . "] " . $ticket_details->getSubject(); ?></h4>
		<input type="button" value="Reply" id="Reply" class="right">
		<input type="submit" value="Update Ticket" class="right">

		<div class="ticketgeneralinfocontainer">Created: <?php echo date('M d, Y h:i A', strtotime($ticket_details->getCreationTime())); ?>
			&nbsp;&nbsp;&nbsp;&nbsp;Updated: <?php echo date('M d, Y h:i A', strtotime($ticket_details->getLastActivity())); ?></div>
		<table id="ticket">
			<tr>
				<th>Department</th>
				<th>Owner</th>
				<th>Type</th>
				<th>Status</th>
				<th style="background-color: <?php echo kyTicketPriority::get($ticket_details->getPriorityId())->getBackgroundColor(); ?>;">Priority</th>
			</tr>
			<tr>
				<td><?php echo kyDepartment::get($ticket_details->getDepartmentId())->getTitle(); ?></td>
				<td><?php echo $ticket_details->getFullName(); ?></td>
				<td><?php echo kyTicketType::get($ticket_details->getTypeId())->getTitle(); ?></td>
				<td>
					<select name="status">
						<?php foreach ($status_list as $status): ?>
							<option
								value="<?php echo $status->id; ?>" <?php if ($status->id == $ticket_details->getStatusId()) echo "selected"; ?> ><?php echo $status->title; ?></option>
						<?php endforeach; ?>
					</select>
				</td>
				<td style="background-color: <?php echo kyTicketPriority::get($ticket_details->getPriorityId())->getBackgroundColor(); ?>;">
					<select name="priority">
						<?php foreach ($priority_list as $priority): ?>
							<option
								value="<?php echo $priority->id; ?>" <?php if ($priority->id == $ticket_details->getPriorityId()) echo "selected"; ?> ><?php echo $priority->title; ?></option>
						<?php endforeach; ?>
					</select>
				</td>
			</tr>
		</table>
		<div class="clear"></div>
		<table id="post">
			<?php
			foreach ($ticket_details->getPosts() as $posts):
				?>
				<tr>
					<td colspan="3"><?php echo $posts->getContents(); ?></td>
					<td>
						<?php
						foreach (kyTicketAttachment::getAll($ticket_details->getId()) as $key) {
							if ($posts->getId() == $key->getTicketPostId()) {
								echo "<a href='" . JURI::Root() . "tmp/" . $key->getFileName() . "'>" . $key->getFileName() . "</a>&nbsp;";
							} else {
								echo "&nbsp;";
							}
						}
						?>
					</td>
				</tr>
				<tr class="ticketgeneralinfocontainer" id="post_details">
					<td>Posted on</td>
					<td><?php echo date('M d, Y h:i A', strtotime($posts->getDateline())); ?></td>
					<td> By</td>
					<td><?php echo $posts->getFullName(); ?></td>
				</tr>
				<tr>
					<td colspan="4">&nbsp;</td>
				</tr>
			<?php
			endforeach;
			?>
		</table>
		<input type="hidden" name="id" value="<?php echo $ticket_details->getDisplayId(); ?>">
		<input type="hidden" name="update" value="1">
	</form>
	<div id="div_reply" style="display: none;" class="new_reply">
		<form name="post_reply" method="POST" enctype="multipart/form-data">
			<table class="new_reply">
				<tr>
					<td><textarea class="new_reply_textarea" name="content" placeholder="Enter your message here..."></textarea></td>
				</tr>
				<tr>
					<td>
						<a href="javascript:void(0);" onclick="this.blur();AddTicketFile('reply')">
							<img src="modules/mod_kayako/resources/images/icon_addplus.gif">&nbsp;Add File
						</a>

						<div id="replyattachmentcontainer"></div>
					</td>
				</tr>
				<tr>
					<td>
						<input type="submit" value="Post">
						<input type="hidden" name="id" value="<?php echo $ticket_details->getDisplayId(); ?>">
						<input type="hidden" name="reply" value="1">
					</td>
				</tr>
			</table>
		</form>
	</div>
</div>