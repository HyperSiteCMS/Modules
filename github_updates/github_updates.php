<?php
/*
 * @package         HyperSite CMS
 * @file            test_mod.php
 * @file_desc       A Test module
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

/* Main Code here */
define('GITHUB_TABLE', $config->mysql['table_prefix'] . 'github');
$template_file .= "modules/github_updates/styles/{$config->config['site_theme']}/template/";
$template->assign_var('PAGE_TITLE', 'CMS Updates');
switch ($act)
{
    case 'view':
        $sha = $i;
        if ($sha != null)
        {
            $url = "https://api.github.com/repos/{$config->config['github_user']}/{$config->config['github_repo']}/commits/{$sha}";
            $curl = curl_init();
            curl_setopt($curl,CURLOPT_USERAGENT,'HyperSite CMS - Github Module 1.0 (http://www.hypersite.info)');
            $timeout = 30;
            curl_setopt($curl, CURLOPT_URL, utf8_encode($url));
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $timeout);
            $result = curl_exec($curl);
            $responseCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            if($responseCode == 200 && $result){
                $commit_info = json_decode($result, true); 
                $template->assign_vars(array(
                    'AUTHOR' => $commit_info['commit']['author']['name'],
                    'DATE' => $commit_info['commit']['author']['date'],
                    'MESSAGE' => nl2br($commit_info['commit']['message']),
                    'ERROR' => 0
                )); 
                foreach ($commit_info['files'] as $com_file)
                {
                    if ($com_file['status'] == 'added')
                    {
                        $template->assign_block_vars('files_added', array(
                            'NAME' => $com_file['filename'],
                        ));
                    }
                    else if ($com_file['status'] == 'modified')
                    {
                        $template->assign_block_vars('files_changed', array(
                            'NAME' => $com_file['filename'],
                        ));
                    }
                    else if ($com_file['status'] == 'removed')
                    {
                        $template->assign_block_vars('files_deleted', array(
                            'NAME' => $com_file['filename']
                        ));
                    }
                    else
                    {
                        $template->assign_block_vars('files_renamed', array(
                            'NAME' => $com_file['filename'],
                            'FROM' => $com_file['previous_filename']
                        ));
                    }
                }
                
            }else{
                $template->assign_vars(array(
                    'ERROR' => 1,
                    'ERROR_MSG' => 'Failed to get Information for Commit.'
                ));
            }
        }
        else
        {
            $template->assign_vars(array(
                'ERROR' => 1,
                'ERROR_MSG' => 'No commit specified.'
            ));
        }
        $template_file .= 'view_commit.html';
        break;
    default:
        $query = "SELECT * FROM " . GITHUB_TABLE . " ORDER BY commit_time DESC LIMIT 100";
        $result = $db->query($query);
        if ($result)
        {
            $commits = $db->fetchall($result);
            foreach ($commits as $commit)
            {
                $commit['commit_time'] = date('c', $commit['commit_time']);
                foreach ($commit as $key => $val)
                {
                    $ukey[strtoupper($key)] = $val;
                }
                $commit = $ukey;
                $template->assign_block_vars('commits', $commit);
            }
        }
        else 
        {
            $template->assign_vars(array(
                'ERROR' => 1,
                'ERROR_MSG' => 'Unable to gather commits from database.'
            ));
        }
        $template_file .= "commits.html";
        break;
}
    