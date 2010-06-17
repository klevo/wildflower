Wildflower 1.3 Beta - A CakePHP CMS
===================================

	@todo check inflections
	@todo check routes - use libs for slug?
	@todo check Cache
	@todo update validation
	@todo use Component triggerCallback 
	@todo checkout helpers that override output they need to manually output it - check partial_layout
	@todo check pagination changes
	@todo make sure trun & highlight have correct options
	@todo when a page/post has a refer of search use highlight to mark search string

Requirements
------------

    * Apache web server with mod_rewrite
    * No problem to run under some other web server like lighttpd or IIS, you just need to emulate the mod_rewrite rules 
    * PHP 5.2 or higher (reported to work with 5.1)
    * MySQL 4.1 or higher

Installation
-----------

   1. Extract the archive. Place the 'wildflower' directory inside your apache document root or some folder under it. Of course you can rename the 'wildflower' directory at will*
   2. Create a new MySQL database. Use 'utf8_unicode_ci' collation. Into this new database import the SQL dump file 'app/config/sql/wildflower.sql'.
   3. Edit the app/config/database.php file with your database connection details.
   4. You can start working on your project by modifying the application inside the app directory. Upgrading requires little effort and time (see Upgrading).
   5. The admin area is located under 'http://your-site/admin'.
   6. Default login/password combination for admin area is admin/admin321 -- change this by clicking users (main admin menu far right) click admin user, then click change password.
   7. Review settings in vendors/wf_core.php (more is set in db login goto settings)
   

The Wildflower Way - or why its not a CakePHP plugin
----------------------------------------------------

WF uses the additional MVC paths CakePHP feature. All the Wildflower MVC (models, views, controllers) are located inside the /wildflower directory. Except the app_controller.php, app_model.php and app_helper.php. These are of course essential for proper WF functioning and are found standardly in the /app folder. This architecture enables you to copy any WF MVC file into your /app folder, modify it and Cake will automatically use it. With the core features of Wildflower and some settings can ease the customisation of the site it powers.  Adding custom controllers/models/etc, for your custom functionality is simple.  Since your app specific code isolated upgrading is simple.


Upgrading
---------

    0. Backup :)
    1. Copy and overwrite all the files with the new version.
    2. Run database migrations. From your working folder launch from the shell: 'php vendors/ruckusing/main.php db:migrate'. This will update your database to the latest version.
   

Problems logging in after updating to AuthComponent enabled version? (1.3b1) / need to generate a password to insert directly into DB?
------------------------------------------------------------

Use the UtilityShell to generate an AuthComponent compatible password hash:
./cake/console/cake wildflower hash myPassword

Insert the result into your database in the 'password' field of the 'users' table.

or run this sql (get the salt value from - app/config/core.php)

    UPDATE users SET password = sha1(concat('salt', 'admin123')) WHERE login = 'admin';

Support & more information
--------------------------

  - [github.com/klevo/wildflower][1] 
  - [forums.wf.klevo.sk][2]
  - [twitter.com/wildflowerCMS][3]
  - [github.com/klevo/wildflower/issues][4]
  - [irc.freenode.net/wildflower][5]

The wf.klevo.sk is out of date, content refers to an old version of WF - updating soon :)

Wildflower Logo designed by Oliver Treend
[olivertreend.com][6]


Content of CakePHP README file
------------------------------

CakePHP is a rapid development framework for PHP which uses commonly known design patterns like Active Record, Association Data Mapping, Front Controller and MVC. Our primary goal is to provide a structured framework that enables PHP users at all levels to rapidly develop robust web applications, without any loss to flexibility.

The Cake Software Foundation - promoting development related to CakePHP
http://www.cakefoundation.org/

CakePHP - the rapid development PHP framework
http://www.cakephp.org

Cookbook - user documentation for learning about CakePHP
http://book.cakephp.org

API - quick reference to CakePHP
http://api.cakephp.org

The Bakery - everything CakePHP
http://bakery.cakephp.org

The Show - live and archived podcasts about CakePHP and more
http://live.cakephp.org

CakePHP Google Group - community mailing list and forum
http://groups.google.com/group/cake-php

#cakephp on irc.freenode.net - chat with CakePHP developers
irc://irc.freenode.net/cakephp

CakeForge - open development for CakePHP
http://cakeforge.org

CakePHP gear
http://www.cafepress.com/cakefoundation

Recommended Reading
http://astore.amazon.com/cakesoftwaref-20/


  [1]: http://github.com/klevo/wildflower
  [2]: http://forums.wf.klevo.sk
  [3]: http://twitter.com/wildflowerCMS
  [4]: http://github.com/klevo/wildflower/issues
  [5]: irc://irc.freenode.net/wildflower
  [6]: http://www.olivertreend.com