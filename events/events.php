<?php

/*
 * @package         HyperSite CMS
 * @file            events.php
 * @file_desc       Events module for HyperSite CMS
 * @version         1.0.0
 * @author          Ryan Morrison
 * @website         http://www.github.com/HyperSiteCMS/HyperSite
 * @copyright       (c) 2019 HyperSite CMS
 * @license         http://opensource.org/licenses/gpl-license.php GNU Public License
 */

/* Check if we are in CMS otherwise exit code. */
if (!defined('IN_HSCMS')) {
    exit;
}
$template_file = "../../../modules/{$mode}/styles/{$config->config['site_theme']}/template/";
/* Insert Main Code Here */
switch ($act) {
    case 'view':
        $template->assign_var('PAGE_TITLE', 'View Event');
        $query = "SELECT * FROM " . EVENTS_TABLE . " WHERE id={$i}";
        $event = $db->fetchrow($db->query($query));
        if ($event['event_status'] == 0) {
            $status = "<font style='color: #000000; background-color: #FFFF00;'><strong>Pending</strong></font>";
        } else if ($event['event_status'] == 1) {
            $status = "<font style='color: #FFFFFF; background-color: #009933;'><strong>Complete</strong></font>";
        } else {
            $status = "<font style='color: #FFFFFF; background-color: #FF0000;'><strong>Cancelled</strong></font>";
        }
        $EventTime = date_create($event['event_time']);
        $template->assign_vars(array(
            'EVENT_STATUS' => $status,
            'EVENT_TITLE' => $event['event_title'],
            'EVENT_TEXT' => html_entity_decode($event['event_text']),
            'EVENT_TIME' => date_format($EventTime, 'd F Y') . '@' . date_format($EventTime, 'H:i:s'),
        ));
        $template_file .= "view-event.html";
        break;
    case null:
        $template->assign_var('PAGE_TITLE', 'Events');
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
        $template_file .= "index.html";
        break;
}