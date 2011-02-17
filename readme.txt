=== Post Affiliate Pro ===
Contributors: jurajsim
Tags: affiliate marketing, pap, post affiliate pro, qualityunit
Requires at least: 3.0.0
Tested up to: 3.0.2
Stable tag: 1.1.1

This plugin integrates Post Affiliate Pro software into any Wordpress installation.

== Description ==

This plugin integrates Post Affiliate Pro software into any Wordpress installation. You can find more info about the software [here](href='http://www.qualityunit.com/postaffiliatepro/ "Affiliate software")

Supported features:

*	Integrates wordpress users signups with Post Affiliate Pro signups
*   Integrates Post Affiliate Pro click tracking into Wordpress
*   Include Top affiliates widget with basic affiliate statistics

== Installation ==

1. Create directory postaffiliatepro in '/wp-content/plugins/'
1. Unzip `postaffiliatepro.zip` to the `/wp-content/plugins/postaffiliatepro` directory
1. Login to you Post Affiliate Pro installation as merchant and go to Main menu -> Tools -> Integration -> API Integration
1. From this window download your API file by clicking on 'Download PAP API' link
1. Upload PapApi.class.php file to your plugin directory /wp-content/plugins/postaffiliatepro

== Frequently Asked Questions ==

= What is Post Affiliate Pro? =

Post Affiliate Pro is an award-winning affiliate software designed to empower or establish new affiliate program.
For more info check out [this page](href='http://www.qualityunit.com/postaffiliatepro/ "Affiliate software")

== Screenshots ==

1. Plugin add extra menu to your WP installation
2. General options screen
3. Signup options screen
4. Click tracking options screen
5. Top affiliates widget config

== Changelog ==

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

* no special requirements, just overwrite plugin files. All should work.

== Arbitrary section ==

Now for form generation purposes php libraby htmlForm from http://stefangabos.blogspot.com/ is used.

If you have any thoughts how to make this plugin better, do not hasitate to leave your ideas in plugin forum.
