<?php

namespace Aggregator\Support;
/*
 * @author     : Section SNIR Touchard Le Mans
 * @mail       : philaure@wanadoo.fr
 * @homepage   : https://philippes.ddns.net/
 * @version    : 1.0.0
 * @repository : https://github.com/PhilippeSimier/Aggregator
 */

class CronManager
{
    protected $version;
    protected $service_executor;


    public function __construct()
    {
        $this->version = '1.0.0';
        $this->service_executor = `whoami`;
    }


    /**
     * @brief : returns `crontab -l` result
     */
    public function get_crontab()
    {
        exec("crontab -l", $output, $exitcode);
        $result = false;
        if ($exitcode === 0) {
            $result = $output;
        }
        return $result;
    }


    /**
     * @brief : only returns working cronjobs except for comment and blank
     */
    public function get_listed_cronjob()
    {
        exec("crontab -l", $output, $exitcode);
        $result = [];
        if ($exitcode === 0) {
            foreach ($output as $cronjob_index => $cronjob) {
                if ($cronjob && (substr($cronjob, 0, 1) != '#')) {
                    $result[] = $cronjob;
                } else {
                    continue;
                }
            }
        }
        return $result;
    }


    public function cron_duplication_checker($cron_tag)
    {
        $listed_cronjob = $this->get_listed_cronjob();
        $result = false;
        if ($listed_cronjob) {
            foreach ($listed_cronjob as $line => $cronjob) {
                $cron_duplication_check = strpos($cronjob, '#CRONTAG='.$cron_tag);
                if ($cron_duplication_check) {
                    $result = true;
                }
            }
        }
        return $result;
    }


    public function add_cronjob($command, $cron_tag)
    {
        $result = array(
            'status' => 'status',
            'msg'    => 'msg',
            'data'   => 'data'
        );

        $cron_duplication_check = $this->cron_duplication_checker($cron_tag);
        $managed_command = '(crontab -l; echo "'.$command.' #CRONTAG='.$cron_tag.'") | crontab -';

        if (!$cron_tag) {
            $result['status'] = 'INPUT_ERROR';
            $result['msg'] = 'cron_tag is required';
            $result['data'] = $managed_command;
        } else if ($cron_duplication_check) {
            $result['status'] = 'FAILED';
            $result['msg'] = 'duplicated cron tag exists';
            $result['data'] = $cron_duplication_check;
        } else {
            exec($managed_command, $output, $exitcode);
            $result['data'] = array(
                'cron_add_output'   => $output,
                'cron_add_exitcode' => $exitcode,
                'managed_command'   => $managed_command
            );
            if ($exitcode === 0) {
                $result['status'] = 'SUCCESS';
                $result['msg'] = 'added new cronjob';
            } else if ($exitcode === 127) {
                $result['status'] = 'ERROR';
                $result['msg'] = 'crond is not running or not installed';
            } else {
                $result['status'] = 'ERROR';
                $result['msg'] = 'error occurred in progress to register new cron job';
            }
        }
        return $result;
    }


    public function remove_cronjob($cron_tag)
    {
        $cron_duplication_check = $this->cron_duplication_checker($cron_tag);
        $result = false;
        if ($cron_duplication_check) {
            exec("crontab -l | sed '/\(.*#CRONTAG=$cron_tag\)/d' | crontab ", $output, $exit_code);
            if ($exit_code === 0) {
                $result = true;
            }
        }
        return $result;
    }


}
