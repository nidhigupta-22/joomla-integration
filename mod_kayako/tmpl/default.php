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

$input = JFactory::getApplication()->input;
$id    = $input->get('id', null);

if ($id == 'fetch') {
    require JModuleHelper::getLayoutPath('mod_kayako', $params->get('layout', 'fetchTicket'));
} else if ($id == 'create') {
    require JModuleHelper::getLayoutPath('mod_kayako', $params->get('layout', 'createTicket'));
} else if ($id != null) {
    require JModuleHelper::getLayoutPath('mod_kayako', $params->get('layout', 'editTicket'));
} else if ($id == null) {
    echo '<div>
            <ul>
                <li>
                    <a href="index.php?id=fetch">View Tickets</a>
                </li>
                <li>
                    <a href="index.php?id=create">Create Ticket</a>
                </li>
            </ul>
        </div>';
}