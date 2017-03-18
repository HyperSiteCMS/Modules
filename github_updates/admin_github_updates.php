<?php
/*
 * @package         HyperSite CMS
 * @file            admin_github_updates.php
 * @file_desc       Retrieve commits from github
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
$do = request_var('do', 'none');
define('GITHUB_TABLE', $config->mysql['table_prefix'] . 'github');
$template_file .= "modules/github_updates/styles/{$config->config['site_theme']}/template/admin_github.html";
$template->assign_var('PAGE_TITLE', 'Github ACP');
switch ($do)
{
    case 'update':
        $url = 'https://api.github.com/repos/'. $config->config['github_user'] . '/' . $config->config['github_repo'] . '/commits';
        $curl = curl_init();
        curl_setopt($curl,CURLOPT_USERAGENT,'HyperSite CMS - Github Module 1.0 (http://www.hypersite.info)');
        $timeout = 30;
        curl_setopt($curl, CURLOPT_URL, utf8_encode($url));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $timeout);
        $result = curl_exec($curl);
        $responseCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        if($responseCode == 200 && $result)
        {
            $commits = json_decode($result, true);
            foreach ($commits as $commit)
            {
                $info = array(
                    'commit_author' => $db->clean($commit['commit']['author']['name']),
                    'commit_time' => strtotime($commit['commit']['author']['date']),
                    'commit_sha' => $commit['sha']
                );
                $query = "SELECT * FROM " . GITHUB_TABLE . " WHERE commit_sha='{$commit['sha']}'";
                $sharesult = $db->query($query);
                $sharow = $db->fetchrow($sharesult);
                if (!$sharow) 
                {
                    $query = $db->build_query('insert', GITHUB_TABLE, $info);
                    $result = $db->query($query);
                    if (!$result)
                    {
                        $template->assign_vars(array(
                            'ERROR' => 1,
                            'ERROR_MSG' => 'Failed to put into Database:<br/>' . $query
                        ));
                        break;
                    }
                }
            }
            $template->assign_vars(array(
               'MESSAGE' => 1,
                'TEXT' => 'Successfully updated the database. Added ' . count($commits) . ' Commits.'
            ));
        }
        else
        {
            $template->assign_vars(array(
                'ERROR' => 1,
                'ERROR_MSG' => 'Could not retrieve git list fomr github. Please check settings.'
            ));
        }
        break;
    default:
    case 'none':
        $template->assign_vars(array(
            'ERROR' => 0,
            'MESSAGE' => ''
        ));
        break;
}


