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

$ticket_obj = new JTickets();
$ticket_obj->fetchAllDepartment();

?>
<link rel="stylesheet" type="text/css" href="modules/mod_kayako/resources/css/style.css">

<div class="<?php echo $params->get('moduleclass_sfx'); ?>">
	<form method="POST" name="ticketform" autocomplete="off">
		<fieldset>
			<legend class="small">Deparment</legend>
			<table class="create">
				<tr>
					<td>Select Department</td>
					<td>
						<select name="dep_type" required>
							<?php foreach ($ticket_obj->all_departments as $key => $value) { ?>
								<option value="<?php echo $key; ?>" <?php if ($key == $get_dep_id) echo "selected"; ?>><?php echo $value; ?></option>
							<?php } ?>
						</select>
					</td>
				</tr>
			</table>
			<legend class="small">General Information</legend>
			<table class="create">
				<tr>
					<td>Full Name</td>
					<td><input type="text" name="fname" id="fname" required/></td>
				</tr>
				<tr>
					<td>Email</td>
					<td><input type="email" name="mail" id="mail" value="<?php echo JFactory::getUser()->email; ?>" required/></td>
				</tr>
				<tr>
					<td>Select Priority</td>
					<td>
						<select name="priority">
							<?php foreach ($ticket_obj->priority_list as $priority): ?>
								<option value="<?php echo $priority->id; ?>"><?php echo $priority->title; ?></option>
							<?php endforeach; ?>
						</select>
					</td>
				</tr>
				<tr>
					<td>Select Status</td>
					<td>
						<select name="status">
							<?php foreach ($ticket_obj->status_list as $status): ?>
								<option value="<?php echo $status->id; ?>"><?php echo $status->title; ?></option>
							<?php endforeach; ?>
						</select>
					</td>
				</tr>
				<tr>
					<td>Select Type</td>
					<td>
						<select name="type">
							<?php foreach ($ticket_obj->ticket_type as $type): ?>
								<option value="<?php echo $type->id; ?>"><?php echo $type->title; ?></option>
							<?php endforeach; ?>
						</select>
					</td>
				</tr>
			</table>
			<legend class="small">Message Details</legend>
			<table class="create">
				<tr>
					<td>Subject</td>
					<td><input type="text" name="subject" id="subject" required/></td>
				</tr>
				<tr>
					<td>Message</td>
					<td><textarea name="message" cols="25" rows="10" required></textarea></td>
				</tr>
			</table>
			<input type="hidden" name="add" value="1">
			<input type="submit" value="Submit Ticket">
		</fieldset>
	</form>
</div>