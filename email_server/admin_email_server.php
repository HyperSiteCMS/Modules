<?php
/*
 * @package         HyperSite CMS
 * @file            admin_email_server.php
 * @file_desc       Allow admins to send email via SMTP server and retrieve via IMAP
 * @author          Ryan Morrison
 * @website         http://www.hypersite.info
 * @copyright       (c) 2017 HyperSite CMS
 * @license         http://opensource.org/licenses/gpl-license.php GNU Public License
 */

/* Check if we are in CMS otherwise exit code. */
if (!defined('IN_HSCMS'))
{
	exit;
}
/* Check if in ACP */
if (!defined('IN_ACP'))
{
    exit;
}
/* Main code Here */
define('EMAIL_TABLE', $config->mysql['table_prefix'] . 'email_serv');
if (!class_exists('hs_email'))
{
    include "{$root_path}modules/email_server/includes/classes/class.email.{$phpex}";
}
$template_file .= "modules/email_server/styles/{$config->config['site_theme']}/template/";
$do = request_var('do', null);
$email = new hs_email();
$template->assign_var('MODULE_SUBLINKS', 1);
$template->assign_block_vars('mod_subs', array(
    'TITLE' => 'Email List',
    'URL' => './admin/&mod=email_server&do=list'
));
$template->assign_block_vars('mod_subs', array(
    'TITLE' => 'Sync Email',
    'URL' => './admin/&mod=email_server&do=sync'
));
$template->assign_block_vars('mod_subs', array(
    'TITLE' => 'Send Email',
    'URL' => './admin/&mod=email_server&do=send'
));
switch ($do)
{
    case 'sync':
        $template_file .= "admin_email_message.html";
        $template->assign_var('PAGE_TITLE', 'Email Sync');
        $sync = $email->_get_mail();
        if ($sync)
        {
            $template->assign_var('MSG_TEXT', 'Emails have been sync\'d to the database successfully');
        }
        else
        {
            $template->assign_var('MSG_TEXT', 'There has been an issue synchronising emails');
        }
        $template->assign_var('RETURN_URL', './admin/&mod=email_server');
        break;
    case 'send':
        if (isset($_POST['send_mail']))
        {
            
        }
        else
        {
            
        }
        break;
    case 'list':
        $sql = "SELECT * FROM " . EMAIL_TABLE . " ORDER BY email_time DESC";
        if ($result = $db->query($sql))
        {
            $emails = $db->fetchall($result);
            if (count($emails) > 0)
            {
                $template->assign_var('TOTAL', count($emails));
                foreach ($emails as $mail)
                {
                    $template->assign_block_vars('emails', array(
                        'FROM' => $mail['email_from'],
                        'SUBJECT' => $mail['email_subject'],
                        'TIME' => date(DATE_RSS, $mail['email_time']),
                        'ID' => $mail['email_id']
                    ));
                }
            }
            else
            {
                $template->assign_var('TOTAL', 0);
            }
        }
        else
        {
            $template->assign_var('TOTAL', 0);
        }
        $template_file .= "admin_email_list.html";
        $template->assign_var('PAGE_TITLE', 'Email List');
        break;
    case 'read':
        $id = $db->clean(request_var('email_id', 0));
        if ($id > 0)
        {
            $sql = "SELECT * FROM " . EMAIL_TABLE . " WHERE email_id={$id}";
            if ($result = $db->query($sql))
            {
                $mail = $db->fetchrow($result);
                if (!isset($mail['email_id']))
                {
                    $template_file .= "admin_email_message.html";
                    $template->assign_vars(array(
                        'MSG_TEXT' => 'Invalid email id selected',
                        'RETURN_URL' => "./admin/&mod=email_server&do=list"
                    ));
                    break;
                }
                $template->assign_vars(array(
                    'PAGE_TITLE' => 'View Mail',
                    'MAIL_SUBJECT' => $mail['email_subject'],
                    'MAIL_FROM' => $mail['email_from'],
                    'MAIL_TIME' => date(DATE_RSS, $mail['email_time']),
                    'MAIL_BODY' => html_entity_decode($mail['email_text'])
                ));
                $template_file .= "admin_email_view.html";
            }
            else
            {
                $template_file .= "admin_email_message.html";
                $template->assign_vars(array(
                    'MSG_TEXT' => 'Failed to select email from database.',
                    'RETURN_URL' => "./admin/&mod=email_server&do=list"
                ));
            }
        }
        else
        {
            $template_file .= "admin_email_message.html";
            $template->assign_vars(array(
                'MSG_TEXT' => 'No valid mail id selected.',
                'RETURN_URL' => "./admin/&mod=email_server&do=list"
            ));
        }
        break;
    default:
        $template_file .= "admin_email_server.html";
        $template->assign_var('PAGE_TITLE', 'Email Management');
        break;
}