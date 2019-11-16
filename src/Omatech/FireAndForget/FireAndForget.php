<?php

namespace Omatech\FireAndForget;

class FireAndForget
{
    public $url;
    public $params;
    public $debug='';
    public $time_spent;

    public function __construct ($url, $params=array())
    {
        $this->url=$url;
        $this->params=$params;
    }

    public function send ()
    {
        $start_microtime=microtime(true);
        // create POST string   
        $post_params = array();
        if ($this->params)
        {
            foreach ($this->params as $key => &$val) 
            {
                $post_params[] = $key . '=' . urlencode($val);
            }
        }
        $post_string = implode('&', $post_params);    

        // get URL segments
        $parts = parse_url($this->url);

        $this->debug.="Parts:\n";
        $this->debug.=print_r($parts, true);

        $this->debug.="Post String:\n";
        $this->debug.=$post_string;
        // workout port and open socket
        $default_port=80;
        if (isset($parts['scheme']))
        {
            if ($parts['scheme']=='https')
            {
                $default_port=443;
            }    
        }
        $port = isset($parts['port']) ? $parts['port'] : $default_port;
        $host = $parts['host'];

        $fp = fsockopen($host, $port, $errno, $errstr, 30);

        // create output string
        $output  = "POST " . $parts['path'] . " HTTP/1.1\r\n";
        $output .= "Host: " . $parts['host'] . "\r\n";
        $output .= "Content-Type: application/x-www-form-urlencoded\r\n";
        $output .= "Content-Length: " . strlen($post_string) . "\r\n";
        $output .= "Connection: Close\r\n\r\n";
        $output .= isset($post_string) ? $post_string : '';

        $this->debug.="Sending output:\n$output\nTo host $host port $port \n";

        if ($fp)
        {
            // send output to $url handle
            fwrite($fp, $output);
            fclose($fp);
            $this->debug.="Sent succesfully!\n";
        }
        else
        {
            $this->debug.="Cannot open socket\n";
        }

        $end_microtime=microtime(true);
        $total_time=round(($end_microtime-$start_microtime), 4);
        $this->debug.="Total microtime $total_time\n";
        $this->time_spent=$total_time;
    }

}
