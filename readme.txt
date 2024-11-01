=== Social Nation It'sMe OAuth Login Multibutton ===
Contributors: socialnationdev
Tags:  login, register, social login, social networks, itsme, 
Requires at least: 4.4
Tested up to: 4.8.2
Stable tag: 1.2.6
License: GPLv2
License URI: https://www.gnu.org/licenses/gpl-2.0.html

== Description ==

Social Nation It'sMe OAuth Login Multibutton is a web service that authorizes It'sMe users to log in on the Partner’s website through web or mobile application, using the It'sMe credentials.

Once logged-on Partner can get the information that the user has authorized to share.

This service has been developed by implementing the authorization protocol oAuth 2.0.

Social Nation It'sMe offers to partners the following technologies:
- REST API: All services exposed by Social Nation It'sMe are exposed through REST services accessible from Partner.
- OAuth 2.0: The authorization protocol used for the resource provisioning to the Partner.
- LOGIN WITH It'sMe: A single sign-on using the It'sMe credentials to access the Partner web site without the need to create new login credentials.

USING THE SHORTCODE

To display It'sMe Login button inside a WordPress page, you can use the shortcode:
[socialnation_itsme_login text="Your text before the button..." scope="your scope..." image_uri="relative image path..." text_after="Your text after the button..."]

To customize the style of It’sMe Login button you can overwrite the following CSS classes:
div.sn_im_oauth_lm_login_button{ ... }
a.sn_im_oauth_lm_login_button{ ... }
img.sn_im_oauth_lm_login_button{ ... }
.sn_im_oauth_lm_login_button_text{  }
.sn_im_oauth_lm_login_button_img{  }
.sn_im_oauth_lm_login_button_text_after{  }

For basic usage, you can also have a look at the [Social Nation Developers Web Site](https://developers.socialnation.it)

== Installation ==

AUTOMATIC INSTALL

- From your WordPress dashboard, navigate to the Plugins menu and click Add New.
- In the search field type "Social Nation Itsme Login" and click Search Plugins. 
- Once you’ve found our It'sMe plugin you can install it by simply clicking “Install Now”.

MANUAL INSTALL

- Upload "social-nation-itsme-login" folder in the "/wp-content/plugins/" folder.
- Activate the plugin using the "Plugins" menu in WordPress.


INITIAL SETUP

You will find "Social Nation It'sMe OAuth Login Multibutton" sub-menu in the "Settings" menu in WordPress.
Complete the fields "Name OAuth" (Customer Service ID), "OAuth Value" (Customer Service Secret Password), "Redirect URI" (Token URL Notification) and save the changes.
You can set "Default scope" filed that will be used as default scope for all buttons. This setting is overwritten by the "scope" attribute of [socialnation_itsme_login] shortcode.
Either the "Layout Bottone Login" field is used as default button image of the schortcode [socialnation_itsme_login] and is overwritten by the "image_uri" attribute.
Note: If you want to test It'mMe Login with your test credentials the flag "Test mode" should be selected.

Selecting the flag "Enable Log" a .log file is created in which you can see the transactions carried out by the plugin.

The .log file is created in the “wp-content/uploads/social-nation-itsme-login” folder. 
Check if WordPress user has write permissions to the uploads folder. 
Once activated, and every time you save the settings the plugin tries to create this directory.
If during activation you do not have write permission the uploads folder is not created. 
You will need to properly set permissions and then save It’sMe Login settings, in this way the plugin will create itsme-log folder, and you can view the log file.

For more information read the guidance [WordPress Changing File Permissions](https://codex.wordpress.org/Changing_File_Permissions)

== Screenshots ==

1. Report Settings.
2. General Settings.
3. It'sMe Login Button Example 1.
4. It'sMe Login Button Example 2.
5. It'sMe Login Button Example 3.
6. It'sMe Login Button Example 4.
7. It'sMe Login Button Example 5.
8. It'sMe Login Button Example 6.
9. It'sMe Login Button Example 7.
10. It'sMe Login Button Example 8.

== Changelog ==

= 1.2.6 =

* Release date: March 1st, 2018

= 1.2.5 =

* Release date: March 1st, 2018

**Bug Fixes**

* fix refresh token problem on user report

= 1.2.4 =

* Release date: February 14th, 2018

**Bug Fixes**

* fix bug when saving user scope 

= 1.2.2 =

* Release date: February 13th, 2018

**Bug Fixes**

* fix login button shortcode problem with return_uri

= 1.2.1 =

* Release date: January 30th, 2018

** New Features **

* Return url after It'sMe login

* Add client_id and client_secret check before showing It'sMe login button shortcode

= 1.2 =

* Release date: January 23th, 2018

**Bug Fixes**

* fix user report multiscope problems


= 1.0.6 =

* Release date: December 5th, 2017

**Bug Fixes**

* change wp admin options slug
