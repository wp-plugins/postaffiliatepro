=== Post Affiliate Pro ===
Contributors: jurajsim
Tags: affiliate marketing, pap, post affiliate pro, qualityunit
Requires at least: 3.0.0
Tested up to: 3.1
Stable tag: 1.2.4

This plugin integrates Post Affiliate Pro software into any Wordpress installation. 
Post Affiliate Pro is one of the leaders on the market with affiliate tracking softwares. It is ultimate solution for all types of businesses.

== Description ==

This plugin integrates Post Affiliate Pro - affiliate software into any Wordpress installation.
Post Affiliate Pro is award winning affiliate software with complete set of affiliate marketing features.
You can rely on bullet-proof click/sale tracking technology, which combines multiple tracking methods into one powerful tracking system.
 
You can find more info about the software [here](href='http://www.qualityunit.com/postaffiliatepro/#wordpress "Affiliate software")

Supported features:

*	Integrates wordpress users signups with Post Affiliate Pro signups
*   Integrates Post Affiliate Pro click tracking into Wordpress
*   Include Top affiliates widget with basic affiliate statistics
*   Shortcode for affiliates 
*   Integration with Contact form 7 (http://contactform7.com/)
*   Also work with S2 member

== Installation ==

1. Create directory postaffiliatepro in '/wp-content/plugins/'
2. Unzip `postaffiliatepro.zip` to the `/wp-content/plugins/postaffiliatepro` directory
3. Login to you Post Affiliate Pro installation as merchant and go to Main menu -> Tools -> Integration -> API Integration
4. From this window download your API file by clicking on 'Download PAP API' link
5. Upload PapApi.class.php file to your plugin directory /wp-content/plugins/postaffiliatepro
6. Activate Post Affiliate Pro plugin
7. Set user credentials in plugin settings.

== Frequently Asked Questions ==

= What is Post Affiliate Pro? =

Post Affiliate Pro is an award-winning affiliate tracking software designed to empower or establish in-house affiliate program.
For more info check out [this page](href='http://www.qualityunit.com/postaffiliatepro/#wordpress "Affiliate software")

= How can I use affiliate shortcode? =

Here are few examples of usage:
[affiliate item="name"/] - prints name of currently loaded affiliate.
[affiliate item="loginur"/] - prints link "Affiliate panel" that affiliate can use to login to his panel 
[affiliate item="loginur" caption="Log me in!"/] - prints link "Log me in!" that affiliate can use to login to his panel
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


== Screenshots ==

1. Plugin add extra menu to your WP installation
2. General options screen
3. Signup options screen
4. Click tracking options screen
5. Top affiliates widget config
6. You can also use shortcodes

== Changelog ==

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
