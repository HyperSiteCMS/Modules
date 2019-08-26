<?php

/*
 * @package         HyperSite CMS
 * @file            install_mod.php
 * @file_desc       Installation file for Events Module
 * @author          Ryan Morrison
 * @website         http://www.github.com/HyperSiteCMS/HyperSite
 * @copyright       (c) 2019 HyperSite CMS
 * @license         http://opensource.org/licenses/gpl-license.php GNU Public License
 */

/* Check if we are in CMS otherwise exit code. */
if (!defined('IN_HSCMS')) {
    exit;
}

/* Main Code here */
/*
 * This is where you would put any relevant code for installing a module. This code is called whenever you "load" a module, so if you unload
 * a module it will reverse any action. Ie- if you create a database table on module load, it will delete said table on unload and all data
 * in the table will be lost. Please back up the database (or ask your web-host to do so) before you unload modules.
 */
$install_actions = array(
    0 => array(
        'create' => array(
            'events' => array(
                'id' => array(
                    'type' => 'INT',
                    'length' => 11,
                    'primary' => true,
                    'auto_increment' => true
                ),
                'event_title' => array(
                    'type' => 'VARCHAR',
                    'length' => 255,
                    'allow_null' => false
                ),
                'event_time' => array(
                    'type' => 'DATETIME',
                    'length' => 6,
                    'allow_null' => true
                ),
                'event_status' => array(
                    'type' => 'INT',
                    'length' => 1,
                    'allow_null' => false,
                    'default' => 0
                )
            ),
        ),
    ),
    //This is the navigation bar item. If you don't want to display in the navigation bar, remove this.
    1 => array(
        'insert' => array(
            'table' => 'navigation',
            'entries' => array(
                'url' => './events/',
                'title' => 'Events'
            )
        )
    )
);
//This is where you can add stuff that doesn't need to be reversed when uninstalling. Requires a bit more technical SQL knowledge

