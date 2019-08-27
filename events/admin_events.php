<?php

/*
 * @package         HyperSite CMS
 * @file            admin_events.php
 * @file_desc       Events module for HyperSite CMS
 * @author          Ryan Morrison
 * @website         http://www.github.com/HyperSiteCMS/HyperSite
 * @copyright       (c) 2019 HyperSite CMS
 * @license         http://opensource.org/licenses/gpl-license.php GNU Public License
 */

/* Check if we are in CMS otherwise exit code. */
if (!defined('IN_HSCMS')) {
    exit;
}
/* Check if in ACP */
if (!defined('IN_ACP')) {
    exit;
}

/* Main Code Here */
$template_file = "../../../modules/{$act}/styles/{$config->config['site_theme']}/template/admin/";
switch ($i) {
    case 'add-event':
        if (!isset($_POST['submit_event'])) {
            $template_file .= "addevent.html";
        } else {
            $dbQuery = array(
                'event_title' => $db->clean(request_var('event_title', null)),
                'event_text' => $db->clean(htmlentities(request_var('event_text', null))),
                'event_time' => $db->clean(request_var('event_time', null)) . ':00'
            );
            $query = $db->build_query('insert', EVENTS_TABLE, $dbQuery);
            $result = $db->query($query);
            if (!$result) {
                $template->assign_var('MESSAGE', 'Error: Event failed to save');
            } else {
                $template->assign_var('MESSAGE', 'Success! Event saved.');
            }
            $template_file = "admin/message.html";
        }
        break;
    case 'edit-event':
        if (!isset($_POST['submit_event'])) {
            $template_file .= "editevent.html";
            $query = "SELECT * FROM " . EVENTS_TABLE . " WHERE id={$p}";
            $event = $db->fetchrow($db->query($query));
            $template->assign_vars(array(
                'EVENT_STATUS' => $event['event_status'],
                'EVENT_TITLE' => $event['event_title'],
                'EVENT_TEXT' => $event['event_text'],
                'EVENT_TIME' => $event['event_time'],
                'EVENT_ID' => $p
            ));
        } else {
            $dbQuery = array(
                'event_title' => $db->clean(request_var('event_title', null)),
                'event_text' => $db->clean(htmlentities(request_var('event_text', null))),
                'event_time' => $db->clean(request_var('event_time', null)),
                'event_status' => $db->clean(request_var('event_status', null))
            );
            $where = array('id' => $db->clean($p));
            $query = $db->build_query('update', EVENTS_TABLE, $dbQuery, $where);
            $result = $db->query($query);
            if (!$result) {
                $template->assign_var('MESSAGE', 'Error: Event failed to save');
            } else {
                $template->assign_var('MESSAGE', 'Success! Event saved.');
            }
            $template_file = "admin/message.html";
        }
        break;
    default:
        $template_file .= "main.html";
        $PendingEvents = $db->fetchall($db->query("SELECT * FROM " . EVENTS_TABLE . " WHERE event_status=0 ORDER BY event_time ASC"));
        $CompletedEvents = $db->fetchall($db->query("SELECT * FROM " . EVENTS_TABLE . " WHERE event_status=1 ORDER BY event_time ASC"));
        $CancelledEvents = $db->fetchall($db->query("SELECT * FROM " . EVENTS_TABLE . " WHERE event_status=2 ORDER BY event_time ASC"));
        foreach ($PendingEvents as $A) {
            $Date = date_create($A['event_time']);
            $template->assign_block_vars('pendingevents', array(
                'TITLE' => $A['event_title'],
                'ID' => $A['id'],
                'TIME' => date_format($Date, 'd F Y') . '@' . date_format($Date, 'H:i:s')
            ));
        }
        foreach ($CompletedEvents as $B) {
            $Date = date_create($B['event_time']);
            $template->assign_block_vars('completedevents', array(
                'TITLE' => $B['event_title'],
                'ID' => $B['id'],
                'TIME' => date_format($Date, 'd F Y') . '@' . date_format($Date, 'H:i:s')
            ));
        }
        foreach ($CancelledEvents as $C) {
            $Date = date_create($C['event_time']);
            $template->assign_block_vars('cancelledevents', array(
                'TITLE' => $C['event_title'],
                'ID' => $C['id'],
                'TIME' => date_format($Date, 'd F Y') . '@' . date_format($Date, 'H:i:s')
            ));
        }
}