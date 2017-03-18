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
class hs_email
{
    public $imap = array();
    public $smtp = array();
    public $from_email = '';
    private $username = '';
    private $password = '';
    
    function __construct() 
    {
        global $config;
        $this->imap = array(
            'server' => $config->config['imap_server'],
            'port' => $config->config['imap_port']
        );
        $this->smtp = array(
            'server' => $config->config['smtp_server'],
            'port' => $config->config['smtp_server']
        );
        $this->from_email = $config->config['from_email'];
        $this->username = $config->config['email_username'];
        $this->password = $config->config['email_password'];
        return true;
    }
    
    function _get_mail()
    {
        global $db;
        $imap_server = '{'.$this->imap['server'].':'.$this->imap['port'].'/imap/ssl/novalidate-cert}INBOX';
        $imap = imap_open($imap_server, $this->username, $this->password);
        if (!$imap)
        {
            return false;
        }
        $emails = imap_search($imap, 'ALL');
        if ($emails)
        {
            //Make sure the newer emails are first.
            rsort($emails); 
            foreach ($emails as $email_number)
            {
                $overview = imap_fetch_overview($imap, $email_number, 0);
                $body = imap_fetchbody($imap, $email_number, 2);
                $array = array(
                    'email_from' => htmlentities($overview[0]->from),
                    'email_subject' => htmlentities($overview[0]->subject),
                    'email_time' => strtotime($overview[0]->date),
                    'email_text' => strip_tags($body, '<br><a><span><font><div>')
                );
                //First check if this email is already in database
                $query = "SELECT * FROM " . EMAIL_TABLE . " WHERE email_from='{$array['email_from']}' AND email_time={$array['email_time']} AND email_subject='{$array['email_subject']}' AND email_text='{$array['email_text']}'";
                $exists = $db->query($query);
                $existing = $db->fetchrow($exists);
                if (!isset($existing['email_id']))
                {
                    $sql = $db->build_query('insert', EMAIL_TABLE, $array);
                    $result = $db->query($sql);
                    if (!$result)
                    {
                        return false;
                    }
                }
            }
            return true;
        }
        return false;
    }
    
    function _send_mail()
    {
        
    }
}