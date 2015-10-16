<?php
/**
 * @copyright      2001-2015 Kayako
 * @license        https://www.freebsd.org/copyright/freebsd-license.html
 * @link           https://github.com/kayako/joomla-integration
 */

defined('_JEXEC') or die;

if ($_REQUEST['ticketid'] == 'fetch') {
	require JModuleHelper::getLayoutPath('mod_kayako', $params->get('layout', 'fetchTicket'));
} else if ($_REQUEST['ticketid'] == 'create') {
	require JModuleHelper::getLayoutPath('mod_kayako', $params->get('layout', 'createTicket'));
} else if ($_REQUEST['ticketid'] != null) {
	require JModuleHelper::getLayoutPath('mod_kayako', $params->get('layout', 'editTicket'));
} else if ($_REQUEST['ticketid'] == null) {
	echo '<div>
            <ul>
                <li>
                    <a href="index.php?ticketid=fetch">View Tickets</a>
                </li>
                <li>
                    <a href="index.php?ticketid=create">Create Ticket</a>
                </li>
            </ul>
        </div>';
}
