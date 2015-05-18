=== Post Affiliate Pro ===
Contributors: jurajsim
Tags: affiliate marketing, pap, post affiliate pro, qualityunit
Requires at least: 3.0.0
Tested up to: 3.8
Stable tag: 1.2.29

This plugin integrates Post Affiliate Pro software into any WordPress installation. Post Affiliate Pro is the leading affiliate tracking tool with more than 27,000 active customers worldwide. 
Post Affiliate Pro is the leading affiliate tracking tool with more than 27,000 active customers worldwide.

== Description ==

This plugin integrates Post Affiliate Pro - affiliate software into any Wordpress installation.
Post Affiliate Pro is award winning affiliate software with complete set of affiliate marketing features.
You can rely on bullet-proof click/sale tracking technology, which combines multiple tracking methods into one powerful tracking system.

= Promotional video =
[vimeo http://vimeo.com/26098217]
You can find more info about PostAffiliatePro [here](http://www.postaffiliatepro.com/#wordpress "Affiliate software"). 

Supported features:

*	Integrates wordpress users signups with Post Affiliate Pro signups
*   Integrates Post Affiliate Pro click tracking into Wordpress
*   Include Top affiliates widget with basic affiliate statistics
*   Shortcode for affiliates 
*   Integration with Contact form 7 (http://contactform7.com/) till version 3 (not included)
*   Also work with S2 member

== Installation ==

1. Create directory postaffiliatepro in '/wp-content/plugins/'
2. Unzip `postaffiliatepro.zip` to the `/wp-content/plugins/postaffiliatepro` directory
3. Activate Post Affiliate Pro plugin
4. Set user credentials in plugin settings.

Note: If the plugin does not show up, login to you Post Affiliate Pro installation as merchant and go to Main menu -> Tools -> Integration -> API Integration
From this window download your API file by clicking on 'Download PAP API' link
Upload PapApi.class.php file to your plugin directory /wp-content/plugins/postaffiliatepro
Refresh your WP-Admin panel

== Frequently Asked Questions ==

= Q: After update, all menus are gone and plugin is not working at all =
A: In situation like this you should check your PapApi.class.php file in your PostAffiliatePro plugin directory.
In most of the cases, this file was missing after update and that caused plugin malfunction.
Without PapApi.class.php file plugin can not operate correctly and because of thet it disable its self to prevent
damaging the main pages with error or warning messages etc.

= What is Post Affiliate Pro? =

Post Affiliate Pro is an award-winning affiliate tracking software designed to empower or establish in-house affiliate program.
For more info check out [this page](href='http://www.qualityunit.com/postaffiliatepro/#wordpress "Affiliate software")

= Can Post Affilate Pro user use same passowrd as in Wordpress? =

No. This is not possible at the moment. Passwords will be always different.

= How can I use affiliate shortcode? =

Here are few examples of usage:

[affiliate item="name"/] - prints name of currently loaded affiliate.

[affiliate item="loginurl"/] - prints link "Affiliate panel" that affiliate can use to login to his panel
 
[affiliate item="loginurl" caption="Log me in!"/] - prints link "Log me in!" that affiliate can use to login to his panel

[affiliate item="loginurl_raw"/] - prints raw url link: http://www.yoursite.com/affiliate/affiliates/panel.php?S=sessionid

[affiliate item="OTHER_ATTRIBUTES"/] - prints other affiliate attributes.  OTHER_ATTRIBUTES can be one of these items:

* userid - id of user
* refid - user referral id
* rstatus - user status
* minimumpayout - amount of minimum payout for user
* payoutoptionid - id of payout option used by user 
* note - user note
* photo - url of user image
* username - username
* rpassword - user passwrod
* firstname - user first name
* lastname - user last name
* parentuserid - id od parent user
* ip - user signup ip
* notificationemail - user notification email
* data1 to data25 - user data fields

example of getting user notification email:

[affiliate item="notificationemail"]

= Is it possible to integrate this plugin with s2Member? =
Yes it is. But you must have in mind, that you should not use any mandatory fields in Post Affiliate Pro signup.
You should use only optional fields.

= Is it possible to integrate this plugin with Magic members? =
Yes it is. But this feature is just experimental at this time.

== Screenshots ==

1. Plugin add extra menu to your WP installation
2. General options screen
3. Signup options screen
4. Click tracking options screen
5. Top affiliates widget config
6. You can also use shortcodes

== Changelog ==

= 1.2.29 =
* fixed some PHP notifiation

= 1.2.27 =
* fixed bug with duplicate mail if is turn on setting in pap and also in wp plugin
* fixed bug with Contact Form 7 when is used custom db prefix

= 1.2.26 =
* added item 'loginurl_raw' to affiliate shortcode for displaying url link

= 1.2.25 =
* fixed affiliate loading problem

= 1.2.24 =
* fixed some bugs dururing affiliate signup

= 1.2.22 =
* minor fixes and code refactoring

= 1.2.21 =
* fixed invisible shortcodes when affiliates do not use email names in PAP

= 1.2.20 =
* fixed bug with contact form 7: Call to a member function get_results() on a non-object in /home/bandi/public_html/sikeresemenyek.hu/wp-content/plugins/postaffiliatepro/Util/ContactForm7Helper.class.php on line 50

= 1.2.19 =
* fixed bug with shortcodes disappearing.

= 1.2.18 =
* tested compatibility with WP 3.5.1

= 1.2.17 =
* minor fixes

= 1.2.16 =
* fixed Contact form 7 form count handling 

= 1.2.15 =
* shortcodes descriptions fixing

= 1.2.14 =
* descriptions fixing

= 1.2.13 =
* shortcodes problmes fix

= 1.2.12 =
* just fixes typos in some texts
* minor code changes 

= 1.2.11 =
* change some texts

= 1.2.10 =
* just typos in some texts

= 1.2.9 =
* just typos in some texts

= 1.2.8 =
* bugfixes

= 1.2.7 =
* experimental support for Magic members Wordpress plugin

= 1.2.6 =
* readme.txt changed - small changes

= 1.2.5 =
* tested on WP 3.2.1

= 1.2.4 =
* screenshots update

= 1.2.3 =
* fixed some minor bugs
* just got report, that plugin works well with S2 member WordPress plugin

= 1.2.2 =
* add support for Contact form 7 integration

= 1.2.1 =
* small bugfixes 
* added chache for affialite login links urls

= 1.2.0 =
* add "affiliate" shortcode

= 1.1.5 =
* fixed critical error with broken shortcodes
* wp_content hook is not used anymore, plugin use wp_head instead

= 1.1.4 =
* fixed critical error with disappearing content

= 1.1.3 =
* fixed crash on plugin load: Warning: SimpleXMLElement::__construct() [simplexmlelement.--construct]: Entity: line 39: parser error : Opening and ending tag mismatch: ...

= 1.1.2 =
* minor bugfixes

= 1.1.1 =
* added possibility to insert newly created affiliate to private campaigns
* added support for click tracking integration
* added Top affiliates widget where you can see your top affiliates names, commissions, total costs etc. 
* signup and/or click tracking can now be enabled/disabled
* many internal chnages, code completly rewritten
* some minor bugs fixed

= 1.0.8 =
* corrected some spelling
* fixed non-functional signup dialog
* add option to send emails from pap when new affiliate signs-up

= 1.0.7 =
* bigfixes

= 1.0.6 =
* chnage menu possition from top to bottom

= 1.0.5 =
* added some more accurate descriptions to signup options form

= 1.0.4 =
* minor bugfixes

= 1.0.3 =
* Added suuport for default status for signing affiliates

= 1.0.2 =
* Fixed bug on signup option page when API file was not on place or out of date

= 1.0.1 =
* Add support to attach some concrete affiliate as parent for every new signed up user from wordpress.

== Upgrade Notice ==

* from 1.0.X to 1.1.X - you need to change path to your Post Afiliate Pro in general settings from http://www.yoursite.com/affiliate/scripts to http://www.yoursite.com/affiliate/ (remove directory 'script' at the end of url)
* other than that, there are no special requirements, just overwrite plugin files. All should work.

== Arbitrary section ==

Now, for html form generation purposes php libraby htmlForm from http://stefangabos.blogspot.com/ is used.

If you have any thoughts how to make this plugin better, do not hasitate to leave your ideas in plugin forum, or write an email to support@qualityunit.com.
