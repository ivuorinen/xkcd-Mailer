# xkcd-Mailer #

Takes the first/latest item from the [xkcd](http://xkcd.com/) atom-feed and mails the image and punchline to a specified email address.

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
```


## crontab example ##

15 minutes over 7am on monday, wednesday and friday.

    15 7 * * 1,3,5 /usr/bin/php /full/path/to/xkcd-mailer.php


## caveats ##

- Script doesn't check has the feed been updated, possibly causing old strip delivery


## contributing ##

- Fork the code
- Do your changes
- Send pull request
- Bask in glory of open source love

## forks <3 ##

* [raamdev/pennyarcade-Mailer](https://github.com/raamdev/pennyarcade-Mailer) - [Penny Arcade](http://penny-arcade.com/comic) emailer by Raam Dev
