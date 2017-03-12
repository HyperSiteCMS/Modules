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
            'github' => array(
                'id' => array(
                    'type' => 'INT',
                    'length' => 11,
                    'primary' => true,
                    'auto_increment' => true
                ),
                'commit_sha' => array(
                    'type' => 'VARCHAR',
                    'length' => 255,
                    'allow_null' => false
                ),
                'commit_time' => array(
                    'type' => 'INT',
                    'length' => 15,
                    'allow_null' => false,
                ),
                'commit_author' => array(
                    'type' => 'VARCHAR',
                    'length' => 255,
                    'allow_null' => false
                )
            ),
        )
    ),
    1 => array(
        'insert' => array(
            'table' => 'settings',
            'entries' => array(
                'setting_name' => 'github_user',
                'setting_value' => ''
            )
        )
    ),
    2 => array(
        'insert' => array(
            'table' => 'settings',
            'entries' => array(
                'setting_name' => 'github_repo',
                'setting_value' => ''
            )
        )
    ),
    3 => array(
        'insert' => array(
            'table' => 'navigation',
            'entries' => array(
                'url' => 'github_updates',
                'title' => 'CMS Updates'
            )
        )
    )
);
