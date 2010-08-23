# xkcd-Mailer #

Takes the first/latest item from the xkcd atom-feed and mails the image and punchline to a specified email address.

## crontab example ##

5 minutes over 7am on monday, wednesday and friday.
    
    5 7 * * 1,3,5 /usr/bin/php /home/users/viir/code/php/xkcd-mailer.php

