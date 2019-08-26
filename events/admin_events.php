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
        $template_file .= "main.html";
        break;
    case 'del-event':
        $teamplte_file .= "main.html";
        break;
    case 'edit-event':
        break;
    default:
        $template_file .= "main.html";
        break;
}