<?php
    /**
     * xkcd-Mailer
     *
     * Sends HTML-email containing hotlinked latest xkcd
     * strip with alt/title-text underneath the image.
     *
     * @author Ismo Vuorinen
     * @version 1.0.20130619
     * @license http://www.opensource.org/licenses/mit-license.php The MIT License
     * @package default
     **/

    // Use config.example.php as base for your configurations.
    $lastfile = "last.txt";

    $here = dirname(__FILE__);
    if (! is_readable($here . '/config.php')) {
        die("Please configure me. I don't know where I should sent the comic. (Config file {$here}/config.php missing.)");
    }
    require_once $here . '/config.php';

    $feed = "http://xkcd.com/atom.xml";

    // Check if http:// wrapper is allowed
    if (ini_get('allow_url_fopen')) {
        $data = simplexml_load_file($feed);
    } else {
        // If http:// wrapper is disabled (by allow_url_fopen=0, for example), then fall back on cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $feed);
        curl_setopt($ch, CURLOPT_FAILONERROR, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        $result = curl_exec($ch);
        curl_close($ch);

        $data = simplexml_load_string($result);
    }

    $item = $data->entry[0];

    $last = 0;
    if (file_exists($lastfile)) {
        $f    = fopen($lastfile, 'r');
        $last = (int) fread($f, 1024);
        fclose($f);
    }

    $parts   = explode('/', $item->id);
    $current = (int) $parts[3];

    if ($current > $last) {
        $date = date("Y-m-d", strtotime($item->updated));
        preg_match("#title=\"(.+)\"#iU", $item->summary, $t);

        // To send HTML mail, the Content-type header must be set
        //$headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers  = 'Content-type: text/html; charset=UTF-8' . "\r\n";
        $headers .= 'From: '. $from . "\r\n";

        $subject    = "xkcd {$date}: {$item->title}";
        $punchline  = $t[1];

        $msg = "<html><body><h1><a href=\"{$item->id}\">{$item->title}</a></h1>\n"
        . "<small>Posted {$date}</small><br />\n"
        . $item->summary."<br />\n"
        . "<p>{$punchline}</p></body></html>\n";

        mail($mail, $subject, $msg, $headers);

        $f = fopen($lastfile, 'w');
        fwrite($f, $current);
        fclose($f);

        echo "New last is $current (was $last)\n";
    } else {
        echo "No new XKCD: last=$last current=$current\n";
    }
