<?php

/**
 * xkcd-Mailer
 *
 * Sends HTML-email containing hotlinked the latest xkcd
 * strip with alt/title-text underneath the image.
 *
 * @author   Ismo Vuorinen <ivuorinen@me.com>
 * @author   wojas <https://github.com/wojas>
 * @author   Raam Dev <https://github.com/raamdev>
 * @link     https://github.com/ivuorinen/xkcd-Mailer
 * @license  The MIT License http://www.opensource.org/licenses/mit-license.php
 * @version  1.0.20140525
 */

// Use config.example.php as base for your configurations.

$here = __DIR__;
if (! is_readable($here . '/config.php')) {
    die("Please configure me. I don't know where I should sent the comic. (Config file {$here}/config.php missing.)");
}

require_once $here . '/config.php';

if (! isset($mail, $from) || empty($mail) || empty($from)) {
    die('Configuration values $mail and $from cannot be empty');
}

$lastfile = $lastfile ?? 'last.txt';
$debug    = $debug ?? false;
$send     = $send ?? false;

$feed = "https://xkcd.com/atom.xml";

/**
 * Get xml feed and parse it.
 * Checks if http(s):// wrapper is allowed.
 *
 * @param string $feed Feed to fetch and parse.
 *
 * @return \SimpleXMLElement
 */
function getFeed(string $feed): SimpleXMLElement
{
    if (ini_get('allow_url_fopen')) {
        // https://bugs.php.net/bug.php?id=62577
        return simplexml_load_string(file_get_contents($feed));
    }

    // If http:// wrapper is disabled (by allow_url_fopen=0, for example), then fall back on cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $feed);
    curl_setopt($ch, CURLOPT_FAILONERROR, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    $result = curl_exec($ch);
    curl_close($ch);

    return simplexml_load_string($result);
}

$data = getFeed($feed);
$item = $data->entry[0];

$last = file_exists($lastfile)
    ? file_get_contents($lastfile)
    : 0;

$parts   = explode('/', $item->id, 4);
$current = (int) $parts[3];

if ($current > $last) {
    $date = date("Y-m-d", strtotime($item->updated));
    preg_match("#title=\"(.+)\"#iU", $item->summary, $t);

    // To send HTML mail, the Content-type header must be set
    $headers = "Content-type: text/html; charset=UTF-8\r\n" .
               sprintf("From: %s\r\n", $from);

    $subject   = sprintf('xkcd %s: %s', $date, $item->title);
    $punchline = $t[1];

    $title = sprintf('<h1><a href="%s">%s</a></h1>', $item->id, $item->title);
    $body  = sprintf(
        '<small>Posted %s</small><br>%s<br><p>%s</p>',
        $date,
        $item->summary,
        $punchline
    );

    $msg = sprintf(
        "<html lang='en'><body>%s\n%s</body></html>",
        $title,
        $body
    );

    if ($send) {
        mail($mail, $subject, $msg, $headers);
    } else {
        echo htmlspecialchars($msg, ENT_QUOTES) . "\n\n";
    }

    $file_write_result = file_put_contents($lastfile, $current);

    if (! $file_write_result) {
        echo htmlspecialchars("Error writing to file: $lastfile\n", ENT_QUOTES);
        exit(1);
    }

    if ($debug) {
        echo htmlspecialchars("New last is $current (was $last)\n", ENT_QUOTES);
    }
    exit(0);
}

if ($debug) {
    echo htmlspecialchars(
        sprintf("No new XKCD: last=%d current=%d\n", $last, $current),
        ENT_QUOTES
    );
}
