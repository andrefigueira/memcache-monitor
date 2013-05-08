memcache-monitor
================

A memcache monitoring web based GUI access via the browser.

Requirements
================

* PHP 5.3.5+
* Memcache 1.4+

That should pretty much do it, Any questions let me know..... Feel free to fork!

Installation
================

Move the source to a folder in your websites directory e.g. /memcache-monitor/

1- Open the lib/config.php file.

2- Enter a username and password, make it something strong!

3- Set your root folder for the memcache-monitor

4- Make sure that that the Memcache host and port are correct, I've set them as default...

5- Start monitoring....

Future development
================

I will be extending this as I need it, for the moment, I'm running memcache on one server only, but I will modify this monitoring code to be useful when using more than 1 node also...

I may also someday add somthing to add/edit/delete/flush keys also, but for the moment this is enough for me and I thought I'd share it with you lovely people...