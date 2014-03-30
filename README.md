# xkcd-Mailer #

Takes the first/latest item from the [xkcd](http://xkcd.com/) atom-feed and mails the image and punchline to a specified email address, if it has not been sent before.

## configuration ##

The script needs a simple configuration. Modify ``config.example.php`` to fit your needs and save as ``config.php``

```php
<?php
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

// File to write ID of last post to
$lastfile = "last.txt";
```


## crontab example ##

Run every hour.

    0 * * * * /usr/bin/php /full/path/to/xkcd-mailer.php

This version will check if the last post was already emailed and will only send the post
if it has not been emailed yet.

## caveats ##

Make sure to set $lastfile to a path that you have write access to.

## changes ##

- 2014-03-30 @wojas added check to see if a post already sent

## contributing ##

- Fork the code
- Do your changes
- Send pull request
- Bask in glory of open source love

## forks <3 ##

* [raamdev/pennyarcade-Mailer](https://github.com/raamdev/pennyarcade-Mailer) - [Penny Arcade](http://penny-arcade.com/comic) emailer by Raam Dev
