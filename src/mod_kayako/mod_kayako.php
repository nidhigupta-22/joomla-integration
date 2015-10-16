<?php
/**
 * @copyright      2001-2015 Kayako
 * @license        https://www.freebsd.org/copyright/freebsd-license.html
 * @link           https://github.com/kayako/joomla-integration
 */

defined('_JEXEC') or die;

$input = new JInput();

if ($input->get('add', null)) {
	require_once(dirname(__FILE__) . "/helper.php");
	$status = JTickets::createTickets();
	if ($status) {
		printf("The ticket was created and its ID is: %s\n", $status);
	} else {
		echo "Some fields are missing or invalid";
	}
} else if ($input->get('update', null)) {
	require_once(dirname(__FILE__) . "/helper.php");
	$update_status = JTickets::editTicket();
	header('location:' . $_SERVER['HTTP_REFERER']);
} else if ($input->get('reply', null)) {
	require_once(dirname(__FILE__) . "/helper.php");
	$update_status = JTickets::postReply();
	header('location:' . $_SERVER['HTTP_REFERER']);
} else {
	$user = &JFactory::getUser();
	if ($user->id != 0) {
		require_once __DIR__ . '/helper.php';
		require JModuleHelper::getLayoutPath('mod_kayako', $params->get('layout', 'default'));
	}
}

