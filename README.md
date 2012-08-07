Owncloud Zarafa Integration
===========================

This is a simple owncloud app that integrates user authentication via the groupware Zarafa and adds the Zarafa WebApp to the navigation.
For integrating the WebApp some code has been taken off the owncloud "external" plugin.

For more information about Zarafa please visit http://www.zarafa.com

Features
--------

* User Authentication against Zarafa
* Simple Integration of the Zarafa WebApp into owncloud

Limitations
-----------

* Currently only single company zarafa setups with the default store being the right one (everything else has neither been tested nor is it supposed to work)
* Changing passwords of or deleting zarafa users via owncloud does not work

Requirements
------------

This app requires zarafa's php-mapi plugin.
The mapi includes have to be available through include paths.

Note: If you have the Zarafa WebApp running on the same server as owncloud you should be fine to go!

Configuration
-------------
* Login to your owncloud installation using any admin user.
* Enable the plugin and go to the system settings.
* There you will have to configure
** The Socket to connect to zarafa, this can be a file:// or http(s):// style socket.
** A valid zarafa user so the plugin can get the list of users

Usage
-----
After you finished the configuration you can login using any of your zarafa users.
You can apply any owncloud group (including the "admin" group) to zarafa users by using the owncloud user management console (this is owncloud functionality and nothing special).

It's strongly recommended to keep at least one admin user that is a pure owncloud user in case something breaks with the zarafa integration.
You may get locked out of your own owncloud installation if you don't!

Libs & Co
---------
The code for integrating the WebApp into the Navigation has been taken from ownclouds "External" App.
