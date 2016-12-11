Owncloud Zarafa Integration
===========================

This is a simple nextcloud app that integrates user authentication via the Kopano Groupware and adds the Kopano WebApp to the navigation.
For integrating the WebApp some code has been taken off the nextcloud "external" plugin.

For more information about Kopano please visit http://www.kopano.com

Features
--------

* User Authentication against Kopano
* Simple Integration of the Kopano WebApp into nextcloud

Limitations
-----------

* Currently only single company Kopano setups with the default store being the right one (everything else has neither been tested nor is it supposed to work)
* Changing passwords of or deleting Kopano users via nextcloud does not work

Requirements
------------

This app requires Kopano's php-mapi plugin.
The mapi includes have to be available through include paths.

**Note**: If you have the Kopano WebApp running on the same server as nextcloud you should be fine to go!

Configuration
-------------
* Login to your nextcloud installation using any admin user.
* Enable the plugin and go to the system settings.
* There you will have to configure
    * The Socket to connect to Kopano, this can be a file:// or http(s):// style socket.
    * A valid Kopano user so the plugin can get the list of users (if anyone has a suggestion on how to get rid of this, tell me!)
    * The url to your WebApp (it will be loaded into an iframe)

Usage
-----
After you finished the configuration you can login using any of your Kopano users.
You can apply any nextcloud group (including the "admin" group) to Kopano users by using the nextcloud user management console (this is nextcloud functionality and nothing special).

It's strongly recommended to keep at least one admin user that is a pure nextcloud user in case something breaks with the Kopano integration.
You may get locked out of your own nextcloud installation if you don't!

Libs & Co
---------
The code for integrating the WebApp into the Navigation has been taken from nextclouds "External" App.
