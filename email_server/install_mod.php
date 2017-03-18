<?php
/*
 * @package         HyperSite CMS
 * @file            install_mod.php
 * @file_desc       Installation file for Github Updates
 * @author          Ryan Morrison
 * @website         http://www.hypersite.info
 * @copyright       (c) 2017 HyperSite CMS
 * @license         http://opensource.org/licenses/gpl-license.php GNU Public License
 */

 /* Check if we are in CMS otherwise exit code. */
/*if (!defined('IN_HSCMS'))
{
	exit;
}*/

/* Main Code here */
/*
 * This is where you would put any relevant code for installing a module. This code is called whenever you "load" a module, so if you unload
 * a module it will reverse any action. Ie- if you create a database table on module load, it will delete said table on unload and all data
 * in the table will be lost. Please back up the database (or ask your web-host to do so) before you unload modules.
 */
$install_actions = array(
    0 => array(
        'create' => array(
            'email_serv' => array(
                'email_id' => array(
                    'type' => 'INT',
                    'length' => 11,
                    'primary' => true,
                    'auto_increment' => true
                ),
                'email_from' => array(
                    'type' => 'VARCHAR',
                    'length' => 255,
                    'allow_null' => false
                ),
                'email_time' => array(
                    'type' => 'INT',
                    'length' => 15,
                    'allow_null' => false,
                ),
                'email_subject' => array(
                    'type' => 'VARCHAR',
                    'length' => 255,
                    'allow_null' => false
                ),
                'email_text' => array(
                    'type' => 'TEXT',
                    'allow_null' => true
                )
            ),
        )
    ),
    1 => array(
        'insert' => array(
            'table' => 'settings',
            'entries' => array(
                'setting_name' => 'imap_server',
                'setting_value' => ''
            )
        )
    ),
    2 => array(
        'insert' => array(
            'table' => 'settings',
            'entries' => array(
                'setting_name' => 'imap_port',
                'setting_value' => ''
            )
        )
    ),
    3 => array(
        'insert' => array(
            'table' => 'settings',
            'entries' => array(
                'setting_name' => 'smtp_server',
                'setting_value' => ''
            )
        )
    ),
    4 => array(
        'insert' => array(
            'table' => 'settings',
            'entries' => array(
                'setting_name' => 'smtp_port',
                'setting_value' => ''
            )
        )
    ),
    5 => array(
        'insert' => array(
            'table' => 'settings',
            'entries' => array(
                'setting_name' => 'from_email',
                'setting_value' => ''
            )
        )
    ),
    6 => array(
        'insert' => array(
            'table' => 'settings',
            'entries' => array(
                'setting_name' => 'email_username',
                'setting_value' => ''
            )
        )
    ),
    7 => array(
        'insert' => array(
            'table' => 'settings',
            'entries' => array(
                'setting_name' => 'email_password',
                'setting_value' => ''
            )
        )
    ),
);
