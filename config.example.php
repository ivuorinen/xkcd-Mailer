<?php if ( empty($here) ) exit('No direct script access allowed');

/**
 * xkcd-Mailer configuration example
 * Save me as config.php
 */

// Your timezone, PHP5 required.
// See full list: http://www.php.net/manual/en/timezones.php
date_default_timezone_set("Europe/Helsinki");

// Your destination
$mail = "your@email.com";
$from = "xkcd mailer <xkcdmailer@example.com>";

