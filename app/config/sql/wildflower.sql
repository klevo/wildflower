-- MySQL dump 10.11
--
-- Host: localhost    Database: wildflower
-- ------------------------------------------------------
-- Server version	5.0.51a-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `categories` (
  `id` int(11) NOT NULL auto_increment,
  `parent_id` int(11) default NULL,
  `lft` int(11) default NULL,
  `rght` int(11) default NULL,
  `slug` varchar(255) collate utf8_unicode_ci NOT NULL,
  `title` varchar(255) collate utf8_unicode_ci NOT NULL,
  `description` varchar(255) collate utf8_unicode_ci default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `parent_id` (`parent_id`),
  KEY `tree_left` (`lft`),
  KEY `tree_right` (`rght`)
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,NULL,1,10,'php5','PHP5',''),(2,NULL,13,14,'rails','Rails',''),(19,14,3,4,'grails','Grails','123'),(4,NULL,11,12,'no-meaning','No meaning','For posts without meaning.'),(21,NULL,15,16,'really-new-caegory-very-very-loooong','Really new caegory, very very loooong','123dasjjj'),(14,1,2,7,'personal-develompent','Personal Development',''),(23,14,5,6,'123-1','123','asdsad'),(24,1,8,9,'sadsa','sadsa','asad?');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories_posts`
--

DROP TABLE IF EXISTS `categories_posts`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `categories_posts` (
  `category_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  KEY `category_id` (`category_id`,`post_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `categories_posts`
--

LOCK TABLES `categories_posts` WRITE;
/*!40000 ALTER TABLE `categories_posts` DISABLE KEYS */;
INSERT INTO `categories_posts` VALUES (2,1),(4,1),(14,2),(19,2),(21,2),(24,2);
/*!40000 ALTER TABLE `categories_posts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `comments`
--

DROP TABLE IF EXISTS `comments`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `comments` (
  `id` int(11) NOT NULL auto_increment,
  `post_id` int(11) NOT NULL,
  `name` varchar(255) collate utf8_unicode_ci NOT NULL,
  `email` char(80) collate utf8_unicode_ci NOT NULL,
  `url` char(80) collate utf8_unicode_ci default NULL,
  `content` text collate utf8_unicode_ci NOT NULL,
  `spam` tinyint(1) NOT NULL default '0',
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `post_id` (`post_id`),
  KEY `spam` (`spam`)
) ENGINE=MyISAM AUTO_INCREMENT=27 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `comments`
--

LOCK TABLES `comments` WRITE;
/*!40000 ALTER TABLE `comments` DISABLE KEYS */;
INSERT INTO `comments` VALUES (26,1493,'klevo','klevo@klevo.sk','http://klevo.sk','Lorem.',0,'2008-12-05 12:44:55','2008-12-05 12:44:55'),(24,1469,'John Rambo','rambo@klevo.sk','','\'With selected\' control working on posts::wf_index',0,'2008-11-08 23:07:40','2008-11-08 23:07:40'),(25,1469,'Tomáš','tomas@tomas.com','http://google.com','Hello.',0,'2008-11-08 23:12:36','2008-11-08 23:12:36'),(21,32,'klevo','klevo@klevo.sk','http://klevo.sk','aloha',0,'2008-10-25 09:37:04','2008-10-25 09:37:04'),(22,32,'klevo','klevo@klevo.sk','http://klevo.sk','aloha',0,'2008-10-25 09:37:53','2008-10-25 09:37:53'),(23,28,'Mr Pruser','pruser@klevo.sk','','To nemyslis vazne, kradnut clanky od SimpleTestu!?',0,'2008-10-25 09:48:36','2008-10-25 09:48:36');
/*!40000 ALTER TABLE `comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `messages`
--

DROP TABLE IF EXISTS `messages`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `messages` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(100) collate utf8_unicode_ci NOT NULL,
  `email` varchar(100) collate utf8_unicode_ci NOT NULL,
  `phone` varchar(100) collate utf8_unicode_ci NOT NULL,
  `content` text collate utf8_unicode_ci,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL,
  `subject` varchar(255) collate utf8_unicode_ci default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Stores all contact form communication';
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `messages`
--

LOCK TABLES `messages` WRITE;
/*!40000 ALTER TABLE `messages` DISABLE KEYS */;
INSERT INTO `messages` VALUES (3,'pajtas','pajtas@klevo.sk','432423523','Let\'s say we have an application that writes a number of files to disk and that it is appropriate to report write errors to the user. We don\'t want to add code for this all over the different parts of our application, so this is a great case for using a new error type.','2008-09-21 10:29:02','2008-09-21 10:29:05','CakePHP error handling');
/*!40000 ALTER TABLE `messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pages`
--

DROP TABLE IF EXISTS `pages`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `pages` (
  `id` int(11) NOT NULL auto_increment,
  `parent_id` int(11) default NULL,
  `lft` int(11) default NULL,
  `rght` int(11) default NULL,
  `level` int(3) NOT NULL default '0' COMMENT 'Page level in the tree hierarchy',
  `slug` varchar(255) collate utf8_unicode_ci NOT NULL COMMENT 'URL friendly page name',
  `url` varchar(255) collate utf8_unicode_ci NOT NULL COMMENT 'Full URL relative to root of the application',
  `title` varchar(255) collate utf8_unicode_ci NOT NULL,
  `content` text collate utf8_unicode_ci,
  `description_meta_tag` text collate utf8_unicode_ci,
  `keywords_meta_tag` text collate utf8_unicode_ci,
  `draft` tinyint(1) NOT NULL default '0',
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL,
  `sidebar_content` text collate utf8_unicode_ci,
  `user_id` int(11) default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `parent_id` (`parent_id`),
  KEY `lft` (`lft`),
  KEY `rght` (`rght`),
  KEY `draft` (`draft`),
  FULLTEXT KEY `content` (`content`)
) ENGINE=MyISAM AUTO_INCREMENT=172 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `pages`
--

LOCK TABLES `pages` WRITE;
/*!40000 ALTER TABLE `pages` DISABLE KEYS */;
INSERT INTO `pages` VALUES (52,NULL,1,4,0,'home-features','/home-features','Home & Features','<ul id=\"home-feature-list\">\r\n<li id=\"feature-cake\">Content management system and application platform build on <a href=\"http://www.cakephp.org\">CakePHP</a> framework and <a href=\"http://jquery.com\">jQuery</a> Javascript library.</li>\r\n<li id=\"feature-standards\">Standards based.</li>\r\n<li id=\"feature-open-source\">Open source.</li>\r\n<li>User friendly.</li>\r\n<li>Unit test coverage.</li>\r\n<li>It\'s a CakePHP plugin. Use with any existing CakePHP 1.2 application.</li>\r\n<li>Free bananas.</li>\r\n<li><a href=\"/feature-tour\">More features</a></li>\r\n</ul>\r\n<p class=\"download\"><a href=\"http://wildflower.googlecode.com/files/wildflower10a.zip\"><strong>Download now</strong><br /> Wildflower 1.0a, released 29th February 2008</a></p>','Content management system and application platform build on CakePHP framework and jQuery Javascript library.',NULL,0,'2008-02-26 16:09:00','2008-11-09 13:38:10','',1),(53,NULL,11,16,0,'feature-tour','/feature-tour','Feature tour','<h3>Ease of use</h3>\n<p>The administration interface is optimized for the ease of use. When editing a page or a post, the integrated WYSIWYG editor automatically resizes to fit the user\'s screen height.</p>\n<h3>File manager II<br /></h3>\n<p>Upload any file type. You can categorize your uploads using tags.</p>\n<p><img src=\"/wildflower/img/thumb/All_falls_down-1440x900.jpg/120/120/1\" alt=\"\" /><img src=\"/wildflower/img/thumb/Igaer-1400x1050.jpg/120/120/1\" alt=\"\" /><img src=\"/wildflower/img/thumb/All_falls_down-1440x900.jpg/120/120/1\" alt=\"\" /><img src=\"/wildflower/img/thumb/The_night_is_coming-1400x1050.jpg/120/120/1\" alt=\"\" /></p>\n<h3>Revisions</h3>\n<p>Every change you do to a page or a post is remembered and you can go back to it. No more lost content.</p>','',NULL,0,'2008-02-26 17:44:00','2008-10-08 15:00:36','',1),(54,NULL,5,6,0,'documentation','/documentation','Documentation','<p><em>This section is slowly being filled up. Please be patient.</em></p>\n<h3>Who is it for?</h3>\n<p>Everyone who is able to deploy Wordpress should be able to get Wildflower up and running. However to fully enjoy and benefit from the features of this CMS, you should be competent in these areas (or willing to learn):</p>\n<ul>\n<li>Code separation. Understand the difference between view and bussiness logic.</li>\n<li>RESTful architecture.</li>\n<li>Unit testing.</li>\n<li>The heart of Wildflower is the CakePHP framework.</li>\n</ul>\n<p>This means this system is not so much for a typical PHP hacker, but for an agile programmer that is willing to learn and adopt the best practices. We also want to have fun along the way, so the idea of the system is not to stand in your way.</p>\n<h3>Requirements</h3>\n<ul>\n<li>Apache web server with mod_rewrite</li>\n<li>PHP 5.2+</li>\n<li>MySQL 4.1+</li>\n<li>If you want to use the <a href=\"http://code.google.com/p/ruckusing/\">Ruckusing database migrations</a> you need PEAR\'s MDB2 and Log packages</li>\n</ul>\n<h3>Fresh installation</h3>\n<ol>\n<li>Extract the archive. Place the <em>wildflower</em> directory inside your web servers documents folder.</li>\n<li>Create a new MySQL database (<em>utf8_unicode_ci</em> collation is strongly recommented) and into this new database import the SQL file <span style=\"font-style: italic;\">app</span><em>/config/sql/wildflower.sql</em>.</li>\n<li>Edit the <em>app/config/database.php</em> file with your database connection details.</li>\n<li>You\'ve got a working copy of this site. You can start working on your project by modifying the application inside the <em>app</em> directory. When a new release of Wildflower comes, you simply replace the <em>cake</em>, <em>vendors</em> and <em>wildflower</em> directories.</li>\n<li>Access the admin area at <em>/admin</em>. The initial login/password combination is <strong>admin</strong>/<strong>admin321</strong>.</li>\n</ol>\n<h3>Installing to an existing CakePHP application</h3>\n<ol>\n<li>Extract the archive and place the <em>wildflower/wildflower</em> directory inside your application root.</li>\n<li>Just include the Wildflower <em>bootstrap.php</em> file located at <em>/wildflower/config/bootstrap.php</em> in your <em>/app/config/bootstrap.php</em>.</li>\n<li>Load the SQL dump file <em>app/config/sql/wildflower.sql</em>&nbsp;into your database.</li>\n<li>Set up some routes. Check the <em>wildflower/config/routes.php</em> file for the default WF routes.</li>\n<li>Your app_controller, app_model and app_helper files should extend WildflowerController, WildflowerModel and WildflowerHelper.</li>\n<li>Copy <em>app/webroot/css/wfadmin</em> from the archive to your <em>app/webroot/css</em>.</li>\n</ol>\n<h3>Basic principles</h3>\n<p>A modern website usually consist of \"static\" pages, news or blog sections, contact form, provides RSS feeds and a number of features, specific to the site\'s aim or goal. The idea of Wildflower is to provide this common functionality, with a polished and user friendly interface and enable the programmer to effectively code the remaining specific features of the site, fully exploiting the PHP rapid development framework--CakePHP.</p>\n<p>Wildflower uses the additional MVC paths feature of the CakePHP framework. It sits in it\'s own directory inside the application root. This allows the user to create application specific controllers, models or views in her <em>/app</em> directory. By mirroring any view file from the wildflower/views folder inside app/views you can override the default Wildflower files. This is a great way to customize any aspect of the CMS, especially extending the admin interface with additional sections or customizing existing ones to the site\'s needs without touching the original Wildflower code.</p>\n<h3>A real world example of building a site with some custom functionality</h3>\n<p>Imagine you want to build a simple site with the following requirements:</p>\n<ul>\n<li>Content managed \"static\" pages</li>\n<li>Contact form</li>\n<li>Home page with four boxes with different content and each content managed</li>\n</ul>\n<p>The first two requirements you\'ve got out of the box. For the third one we\'ll create a new section in the admin interface and build our own model/view/controller that will handle the custom functionality.</p>\n<h4>Step 1: Create a new database table</h4>\n<p>Let\'s create a very basic table to hold our data.</p>\n<p><code>CREATE TABLE home_page_boxes (<br /> &nbsp; id int(11) unsigned NOT NULL auto_increment,<br /> &nbsp; content text,<br /> &nbsp; PRIMARY KEY&nbsp; (id)<br /> );</code></p>\n<p>We\'ll be using 4 table rows to represent our boxes. Insert those right away:</p>\n<p><code>INSERT INTO home_page_boxes (id, content) VALUES(1, \'Box 1 content.\');<br /> INSERT INTO home_page_boxes (id, content) VALUES(2, \'Box 2 content.\');<br /> INSERT INTO home_page_boxes (id, content) VALUES(3, \'Box 3 content.\');<br /> INSERT INTO home_page_boxes (id, content) VALUES(4, \'Box 4 content.\');</code></p>\n<h4>Step 2: Create the MVC files</h4>\n<p>For this simple example we actually don\'t need to create any model file, since CakePHP supports <a href=\"http://www.littlehart.net/atthekeyboard/2008/08/05/dynamic-models-in-cakephp-12/\">dynamic models</a>. So let\'s create our controller. We\'ll create a file in <em>app/controllers/</em> called <em>home_page_boxes_controller.php</em> and put some code into it:</p>\n<pre>&lt;?php\nclass HomePageBoxesController extends AppController {\n&nbsp;&nbsp;&nbsp; \n&nbsp;&nbsp;&nbsp; function beforeFilter() {\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; parent::beforeFilter();\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; $this-&gt;pageTitle = \'Home\';\n&nbsp;&nbsp;&nbsp; }\n\n&nbsp;&nbsp;&nbsp; function admin_index() {\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; $boxes = $this-&gt;HomePageBox-&gt;find(\'all\', \'id IN (1, 2, 3, 4)\');\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; $this-&gt;set(compact(\'boxes\'));\n&nbsp;&nbsp;&nbsp; }\n&nbsp;&nbsp;&nbsp; \n&nbsp;&nbsp;&nbsp; function admin_update() {\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; foreach ($this-&gt;data[\'HomePageBox\'] as $name =&gt; $content) {\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; $id = explode(\'-\', $name);\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; $id = intval(array_pop($id));\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; $data[\'HomePageBox\'] = array(\'id\' =&gt; $id, \'content\' =&gt; $content);\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; $this-&gt;HomePageBox-&gt;create($data);\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; $this-&gt;HomePageBox-&gt;save(); \n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; }\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; $this-&gt;redirect(array(\'action\' =&gt; \'index\'));\n&nbsp;&nbsp;&nbsp; }\n&nbsp;&nbsp;&nbsp; \n&nbsp;&nbsp;&nbsp; function index() {\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; $boxes = $this-&gt;HomePageBox-&gt;find(\'all\', \'id IN (1, 2, 3, 4)\');\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; $this-&gt;set(compact(\'boxes\'));\n&nbsp;&nbsp;&nbsp; }\n&nbsp;&nbsp;&nbsp; \n}\n</pre>\n<p>This code handles populating the the homepage and the admin section with data and updating (saving) new content in the admin section. Let\'s create the view files. Create a new folder called <em>home_page_boxes</em> under <em>app/views/</em> . Under this folder add <em>admin_index.ctp</em> and <em>index.ctp</em> files.</p>\n<p><em>admin_index.ctp</em> could look like this:</p>\n<pre>&lt;h2&gt;Homepage Boxes&lt;/h2&gt;\n\n&lt;?php\n    echo $form-&gt;create(\'HomePageBox\', array(\'action\' =&gt; \'update\'));\n    \n    for ($i = 0; $i &lt; 4; $i++) {\n        echo \n        $form-&gt;input(\"content-{$boxes[$i][\'HomePageBox\'][\'id\']}\", \n            array(\'type\' =&gt; \'textbox\', \n                    \'value\' =&gt; $boxes[$i][\'HomePageBox\'][\'content\'], \n                    \'label\' =&gt; \'Box \' . ($i + 1), \'between\' =&gt; \'&lt;br /&gt;\', \'class\' =&gt; \'box-fck\')),\n        $form-&gt;submit(\'Save\');\n    }\n    \n    echo $form-&gt;end();\n?&gt;\n</pre>\n<p>This will create an admin page with four TinyMCE editors each handling one box. Let\'s take a look at the <em>index.ctp</em> file:</p>\n<pre>&lt;?php foreach ($boxes as $box) { ?&gt;\n\n&lt;div class=\"home-box\"&gt;\n    &lt;?php echo $box[\'HomePageBox\'][\'content\'] ?&gt;\n&lt;/div&gt;\n\n&lt;?php } ?&gt;\n</pre>\n<p>This will display the four boxes and their content.</p>\n<p>Now <strong>copy</strong> the <em>/wildflower/views/layout/admin_default.ctp</em> to <em>/app/views/layouts/</em> . There you can modify the file and add a link to our new home page boxes admin screen to the admin main menu. I\'ll leave this step to you. Remember: <strong>Every Wildflower view file that you mirror inside your app/views will be used instead of the original</strong>.</p>\n<h4>Step 3: Routes</h4>\n<p>Finally we need to let Cake know that we want to display the HomePageBoxesController::index() action when browsing to <em>your-site.com </em>root. Modify the first two routes in <em>app/config/routes.php</em> like this:</p>\n<pre>Router::connect(\'/\', array(\'controller\' =&gt; \'home_page_boxes\', \'action\' =&gt; \'index\'));\nRouter::connect(\'/app/webroot/\', array(\'controller\' =&gt; \'home_page_boxes\', \'action\' =&gt; \'index\'));\n</pre>\n<p>Maybe you\'re wondering about the second route. On some server configurations Cake incorrectly detects the site root (/) as <em>/app/webroot</em> . This fixes it. If you don\'t experience this problem (the home page works fine without the second route) you can remove the route.</p>\n<p>As you can see, Wildflower enables you to use all the CakePHP power together with the out of the box functionality.</p>','',NULL,0,'2008-02-27 16:20:00','2008-10-25 09:14:45','',0),(64,53,12,15,1,'features-and-pages','/feature-tour/features-and-pages','Code, code, code','<p class=\"cake-debug\">Hello world <a href=\"#title\">how</a> are we today.</p>\r\n<h2 class=\"cake-debug\">Heading &lt;h2&gt;</h2>\r\n<p style=\"padding-left: 30px;\">This option enables you to specify a custom CSS file that extends the theme content <span style=\"background-color: #ff9900;\">CSS</span>. This <span style=\"color: #ff6600;\">CSS</span> file is the one used within the editor (the editable area). This option can also be a comma separated list of URLs.</p>\r\n<p class=\"cake-debug\">If you specify a relative path, it is resolved in relation to the URL of the (HTML) file that includes TinyMCE, NOT relative to TinyMCE itself.</p>\r\n<p class=\"cake-debug\"><img src=\"/wildflower/img/thumb/vetton_ru_501.jpg/120/120/1\" alt=\"\" /><img src=\"/wildflower/img/thumb/All_falls_down-1440x900.jpg/120/120/1\" alt=\"\" /><img src=\"/wildflower/img/thumb/Step_forward_little_tommy-1400x1050.jpg/120/120/1\" alt=\"\" /><br /><span style=\"text-decoration: line-through;\">strike me</span></p>','',NULL,1,'2008-07-01 16:40:00','2008-08-11 12:11:19','',1),(79,64,13,14,2,'a-page-about-nothing','/feature-tour/features-and-pages/a-page-about-nothing','A page about nothing 12','<p>\'wf_update\' sad da as das asd 213 sadsa sadas asd</p>\n<p>&nbsp;</p>\n<p>sadsad</p>\n<p>sad</p>','',NULL,0,'2008-07-02 20:29:00','2008-10-20 13:20:26','<p>efgh</p>',0),(83,NULL,7,8,0,'bugs','/bugs','Bugs','<ul>\n<li>Post can be viewed even if draft</li>\n<li>Add new category parent select box shows parents that should not be available</li>\n<li>When trying to edit a non existent page an SQL error is shown</li>\n<li>Public search should not search in drafts</li>\n<li>Upload::create does not fill short_name</li>\n<li>Image browser CSS rf &amp; IE7 fixing</li>\n<li>Image browser can be open multiple times and get\'s fcked up</li>\n<li>I deleted a comment an another one got deleted</li>\n<li>Preview does not render home template</li>\n<li>Deleting a comment a clicking cancel deletes the comment!</li>\n<li>When a new revision gets added by AJAX the old first one remains with link witouth a rev num.</li>\n<li>Paging stuff needs to recompute itself after delete.</li>\n</ul>\n<p>Opera bugs:</p>\n<ul>\n<li>live search cancel...</li>\n</ul>\n<p>IE7:</p>\n<ul>\n<li>sub toolbar get f*cked up on page edit screen when a write new post/page is used and then canceled</li>\n</ul>','',NULL,0,'2008-07-07 08:43:00','2008-10-25 09:52:25','',0),(164,NULL,19,22,0,'root','/root','root','','',NULL,0,'2008-09-13 17:54:00','2008-09-13 17:54:48','',1),(158,NULL,17,18,0,'wf-is-the-best','/wf-is-the-best','UI facelift','<p>New graphic elements.</p>','',NULL,0,'2008-08-08 22:26:00','2008-10-08 20:49:39','',1),(116,NULL,9,10,0,'todo-enhancements','/todo-enhancements','TODO, Enhancements','<ul>\n<li>Add keyboard shortcuts - like alt-A go to index...etc.<br /></li>\n<li>Google sitemaps generation</li>\n<li>SWFupload</li>\n<li>Password strength check from Wordpress</li>\n<li>Shift click on list to select more items</li>\n<li>Pages list drag and drop</li>\n<li>JLM compression and caching</li>\n<li>File manager folders</li>\n</ul>\n<p><img src=\"/wildflower/img/thumb/The_night_is_coming-1400x1050.jpg/120/120/1\" alt=\"\" /></p>','',NULL,0,'2008-07-15 20:44:00','2008-10-24 15:56:28','<p>helo</p>',0),(157,52,2,3,1,'test-page','/home-features/test-page','test page','<p>I want candy!</p>','',NULL,0,'2008-08-08 18:21:00','2008-11-08 22:58:56','',1),(165,164,20,21,1,'child','/root/child','child','','',NULL,0,'2008-09-13 17:54:00','2008-10-07 16:08:18','',1),(167,NULL,23,24,0,'vranne-kone','/vranne-kone','Vranné kone','Lorem ipsum. Vranné kone.',NULL,NULL,0,'2008-10-24 15:11:38','2008-10-24 15:11:38',NULL,NULL),(171,NULL,27,28,0,'some-longer-title-with-number-467-1','/some-longer-title-with-number-467-1','Article 1','Lorem ipsum dolor sit amer. 123. čľž+áľšýí.11_?',NULL,NULL,0,'2008-10-24 16:54:20','2008-10-24 16:54:20',NULL,NULL),(168,NULL,NULL,NULL,0,'some-longer-title-with-number-467','/','Article 1','Lorem ipsum dolor sit amer. 123. čľž+áľšýí.11_?',NULL,NULL,0,'2008-10-24 15:11:38','2008-10-24 15:11:38',NULL,NULL),(170,NULL,25,26,0,'contact','/contact','Contact formular','<p>This is a contact form for you.</p>','',NULL,0,'2008-10-24 15:12:00','2008-10-24 16:03:42','',0);
/*!40000 ALTER TABLE `pages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `posts`
--

DROP TABLE IF EXISTS `posts`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `posts` (
  `id` int(11) NOT NULL auto_increment,
  `slug` varchar(255) collate utf8_unicode_ci NOT NULL,
  `title` varchar(255) collate utf8_unicode_ci NOT NULL,
  `content` text collate utf8_unicode_ci,
  `user_id` int(11) NOT NULL COMMENT 'ID of the author of the post.',
  `description_meta_tag` text collate utf8_unicode_ci,
  `keywords_meta_tag` text collate utf8_unicode_ci,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL,
  `draft` int(1) NOT NULL default '0',
  `uuid` varchar(255) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `slug` (`slug`),
  FULLTEXT KEY `content` (`content`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `posts`
--

LOCK TABLES `posts` WRITE;
/*!40000 ALTER TABLE `posts` DISABLE KEYS */;
INSERT INTO `posts` VALUES (1,'e12fb31d656da0fe469c45b84fd1de4b2d0104d8','The first post','<p>Ola ola.</p>',0,'Just a draft.',NULL,'2008-12-05 18:48:00','2008-12-14 12:03:17',0,'e12fb31d656da0fe469c45b84fd1de4b2d0104d8'),(2,'58a268f56e1206c7ac7841958aab194d9a65230d','Git is COOL 1','<p>wildflower.categories: OK<br />wildflower.categories_posts: Table is already up to date<br />wildflower.comments: OK<br />wildflower.messages: Table is already up to date<br />wildflower.pages: OK<br />wildflower.posts: Table is already up to date<br />wildflower.revisions: Table is already up to date<br />wildflower.schema_info: OK<br />wildflower.settings: OK<br />wildflower.sitemaps: Table is already up to date<br />wildflower.tags: Table is already up to date<br />wildflower.tags_uploads: Table is already up to date<br />wildflower.uploads: OK<br />wildflower.users: OK</p>\r\n<p><img src=\"/wildflower/img/thumb/company-image.jpg/0/0\" alt=\"company-image.jpg\" /><img src=\"/wildflower/img/thumb/company-image.jpg/0/0\" alt=\"company-image.jpg\" /><img src=\"/wildflower/img/thumb/company-image.jpg/0/0\" alt=\"company-image.jpg\" /></p>',1,'',NULL,'2008-12-05 18:50:00','2008-12-14 12:38:40',0,'58a268f56e1206c7ac7841958aab194d9a65230d'),(4,'9c29dcead04186c67250aeac5cb62bf91f8b375a','Writing a new post','<p>Play tennis.</p>\r\n<p><img src=\"/wildflower/uploads/Pixel_tennis_by_iTop_edition.gif\" alt=\"Pixel_tennis_by_iTop_edition.gif\" /></p>',1,'',NULL,'2008-12-06 18:48:00','2008-12-14 12:44:13',0,'9c29dcead04186c67250aeac5cb62bf91f8b375a');
/*!40000 ALTER TABLE `posts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `revisions`
--

DROP TABLE IF EXISTS `revisions`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `revisions` (
  `id` int(11) NOT NULL auto_increment,
  `type` varchar(255) collate utf8_unicode_ci NOT NULL,
  `node_id` int(11) NOT NULL,
  `content` text collate utf8_unicode_ci NOT NULL,
  `revision_number` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `type` (`type`,`node_id`)
) ENGINE=MyISAM AUTO_INCREMENT=26 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `revisions`
--

LOCK TABLES `revisions` WRITE;
/*!40000 ALTER TABLE `revisions` DISABLE KEYS */;
INSERT INTO `revisions` VALUES (1,'wildpost',1,'{\"WildPost\":[]}',1,1,'2008-12-05 18:48:16'),(2,'wildpost',1,'{\"WildPost\":{\"title\":\"The first post\",\"content\":\"<p>Ola ola.<\\/p>\"}}',2,1,'2008-12-05 18:48:38'),(3,'wildpost',2,'{\"WildPost\":[]}',1,1,'2008-12-05 18:49:10'),(4,'wildpost',2,'{\"WildPost\":{\"title\":\"Git is COOL\",\"content\":\"<p>wildflower.categories: OK<br \\/>wildflower.categories_posts: Table is already up to date<br \\/>wildflower.comments: OK<br \\/>wildflower.messages: Table is already up to date<br \\/>wildflower.pages: OK<br \\/>wildflower.posts: Table is already up to date<br \\/>wildflower.revisions: Table is already up to date<br \\/>wildflower.schema_info: OK<br \\/>wildflower.settings: OK<br \\/>wildflower.sitemaps: Table is already up to date<br \\/>wildflower.tags: Table is already up to date<br \\/>wildflower.tags_uploads: Table is already up to date<br \\/>wildflower.uploads: OK<br \\/>wildflower.users: OK<\\/p>\"}}',2,1,'2008-12-05 18:49:23'),(5,'wildpost',2,'{\"WildPost\":{\"title\":\"Git is COOL\",\"content\":\"<p>wildflower.categories: OK<br \\/>wildflower.categories_posts: Table is already up to date<br \\/>wildflower.comments: OK<br \\/>wildflower.messages: Table is already up to date<br \\/>wildflower.pages: OK<br \\/>wildflower.posts: Table is already up to date<br \\/>wildflower.revisions: Table is already up to date<br \\/>wildflower.schema_info: OK<br \\/>wildflower.settings: OK<br \\/>wildflower.sitemaps: Table is already up to date<br \\/>wildflower.tags: Table is already up to date<br \\/>wildflower.tags_uploads: Table is already up to date<br \\/>wildflower.uploads: OK<br \\/>wildflower.users: OK<\\/p>\\r\\n<p>&nbsp;<\\/p>\"}}',3,1,'2008-12-05 19:53:26'),(6,'wildpost',2,'{\"WildPost\":{\"title\":\"Git is COOL\",\"content\":\"<p>wildflower.categories: OK<br \\/>wildflower.categories_posts: Table is already up to date<br \\/>wildflower.comments: OK<br \\/>wildflower.messages: Table is already up to date<br \\/>wildflower.pages: OK<br \\/>wildflower.posts: Table is already up to date<br \\/>wildflower.revisions: Table is already up to date<br \\/>wildflower.schema_info: OK<br \\/>wildflower.settings: OK<br \\/>wildflower.sitemaps: Table is already up to date<br \\/>wildflower.tags: Table is already up to date<br \\/>wildflower.tags_uploads: Table is already up to date<br \\/>wildflower.uploads: OK<br \\/>wildflower.users: OK<\\/p>\\r\\n<p><img src=\\\"\\/wildflower\\/img\\/thumb\\/company-image.jpg\\/0\\/0\\\" alt=\\\"company-image.jpg\\\" \\/><\\/p>\"}}',4,1,'2008-12-05 19:55:31'),(7,'wildpost',2,'{\"WildPost\":{\"title\":\"Git is COOL\",\"content\":\"<p>wildflower.categories: OK<br \\/>wildflower.categories_posts: Table is already up to date<br \\/>wildflower.comments: OK<br \\/>wildflower.messages: Table is already up to date<br \\/>wildflower.pages: OK<br \\/>wildflower.posts: Table is already up to date<br \\/>wildflower.revisions: Table is already up to date<br \\/>wildflower.schema_info: OK<br \\/>wildflower.settings: OK<br \\/>wildflower.sitemaps: Table is already up to date<br \\/>wildflower.tags: Table is already up to date<br \\/>wildflower.tags_uploads: Table is already up to date<br \\/>wildflower.uploads: OK<br \\/>wildflower.users: OK<\\/p>\\r\\n<p><img src=\\\"\\/wildflower\\/img\\/thumb\\/company-image.jpg\\/0\\/0\\\" alt=\\\"company-image.jpg\\\" \\/><img src=\\\"\\/wildflower\\/img\\/thumb\\/company-image.jpg\\/0\\/0\\\" alt=\\\"company-image.jpg\\\" \\/><img src=\\\"\\/wildflower\\/img\\/thumb\\/company-image.jpg\\/0\\/0\\\" alt=\\\"company-image.jpg\\\" \\/><\\/p>\\r\\n<p><img src=\\\"\\/wildflower\\/img\\/thumb\\/Good_Vibrations-1440x900.jpg\\/0\\/0\\\" alt=\\\"Good_Vibrations-1440x900.jpg\\\" \\/><\\/p>\"}}',5,1,'2008-12-05 21:28:02'),(8,'wildpost',3,'{\"WildPost\":[]}',1,1,'2008-12-05 21:30:10'),(9,'wildpost',2,'{\"WildPost\":{\"title\":\"Git is COOL\",\"content\":\"<p>wildflower.categories: OK<br \\/>wildflower.categories_posts: Table is already up to date<br \\/>wildflower.comments: OK<br \\/>wildflower.messages: Table is already up to date<br \\/>wildflower.pages: OK<br \\/>wildflower.posts: Table is already up to date<br \\/>wildflower.revisions: Table is already up to date<br \\/>wildflower.schema_info: OK<br \\/>wildflower.settings: OK<br \\/>wildflower.sitemaps: Table is already up to date<br \\/>wildflower.tags: Table is already up to date<br \\/>wildflower.tags_uploads: Table is already up to date<br \\/>wildflower.uploads: OK<br \\/>wildflower.users: OK<\\/p>\\r\\n<p><img src=\\\"\\/wildflower\\/img\\/thumb\\/company-image.jpg\\/0\\/0\\\" alt=\\\"company-image.jpg\\\" \\/><img src=\\\"\\/wildflower\\/img\\/thumb\\/company-image.jpg\\/0\\/0\\\" alt=\\\"company-image.jpg\\\" \\/><img src=\\\"\\/wildflower\\/img\\/thumb\\/company-image.jpg\\/0\\/0\\\" alt=\\\"company-image.jpg\\\" \\/><\\/p>\"}}',6,1,'2008-12-06 10:10:05'),(10,'wildpost',1,'{\"WildPost\":{\"title\":\"The first post\",\"content\":\"<p>Ola ola.<\\/p>\",\"description_meta_tag\":\"\"}}',3,1,'2008-12-06 10:38:25'),(11,'wildpost',1,'{\"WildPost\":{\"title\":\"The first post\",\"content\":\"<p>Ola ola.<\\/p>\",\"description_meta_tag\":\"Just a draft\"}}',4,1,'2008-12-06 10:38:51'),(12,'wildpost',1,'{\"WildPost\":{\"title\":\"The first post\",\"content\":\"<p>Ola ola.<\\/p>\",\"description_meta_tag\":\"Just a draft.\"}}',5,1,'2008-12-06 10:48:28'),(13,'wildpost',2,'{\"WildPost\":{\"title\":\"Git is COOL\",\"content\":\"<p>wildflower.categories: OK<br \\/>wildflower.categories_posts: Table is already up to date<br \\/>wildflower.comments: OK<br \\/>wildflower.messages: Table is already up to date<br \\/>wildflower.pages: OK<br \\/>wildflower.posts: Table is already up to date<br \\/>wildflower.revisions: Table is already up to date<br \\/>wildflower.schema_info: OK<br \\/>wildflower.settings: OK<br \\/>wildflower.sitemaps: Table is already up to date<br \\/>wildflower.tags: Table is already up to date<br \\/>wildflower.tags_uploads: Table is already up to date<br \\/>wildflower.uploads: OK<br \\/>wildflower.users: OK<\\/p>\\r\\n<p><img src=\\\"\\/wildflower\\/img\\/thumb\\/company-image.jpg\\/0\\/0\\\" alt=\\\"company-image.jpg\\\" \\/><img src=\\\"\\/wildflower\\/img\\/thumb\\/company-image.jpg\\/0\\/0\\\" alt=\\\"company-image.jpg\\\" \\/><img src=\\\"\\/wildflower\\/img\\/thumb\\/company-image.jpg\\/0\\/0\\\" alt=\\\"company-image.jpg\\\" \\/><\\/p>\",\"description_meta_tag\":\"\"}}',7,1,'2008-12-06 12:21:00'),(14,'wildpost',4,'{\"WildPost\":[]}',1,1,'2008-12-06 18:48:53'),(15,'wildpost',4,'{\"WildPost\":{\"title\":\"Writing a new post\",\"content\":\"<p>And again I am coding.<\\/p>\",\"description_meta_tag\":\"\"}}',2,1,'2008-12-06 18:49:12'),(16,'wildpost',5,'{\"WildPost\":[]}',1,1,'2008-12-06 18:58:17'),(17,'wildpost',4,'{\"WildPost\":{\"title\":\"Writing a new post\",\"content\":\"<p>And again I am coding.<img src=\\\"\\/wildflower\\/img\\/thumb\\/company-image.jpg\\/0\\/0\\\" alt=\\\"company-image.jpg\\\" \\/><\\/p>\",\"description_meta_tag\":\"\"}}',3,0,'2008-12-06 19:00:39'),(18,'wildpost',4,'{\"WildPost\":{\"title\":\"Writing a new post\",\"content\":\"<p>And again I am coding.<img src=\\\"\\/wildflower\\/uploads\\/company-image.jpg\\\" alt=\\\"company-image.jpg\\\" \\/><img src=\\\"\\/wildflower\\/uploads\\/Good_Vibrations-1440x900.jpg\\\" alt=\\\"Good_Vibrations-1440x900.jpg\\\" \\/><\\/p>\",\"description_meta_tag\":\"\"}}',4,1,'2008-12-11 17:35:14'),(19,'wildpost',4,'{\"WildPost\":{\"title\":\"Writing a new post\",\"content\":\"<p>And again I am coding.<img src=\\\"\\/wildflower\\/uploads\\/company-image.jpg\\\" alt=\\\"company-image.jpg\\\" \\/><\\/p>\",\"description_meta_tag\":\"\"}}',5,1,'2008-12-11 17:35:30'),(20,'wildpost',4,'{\"WildPost\":{\"title\":\"Writing a new post\",\"content\":\"<h1>And again I am coding.<img src=\\\"\\/wildflower\\/uploads\\/company-image.jpg\\\" alt=\\\"company-image.jpg\\\" \\/><img src=\\\"\\/wildflower\\/uploads\\/company-image.jpg\\\" alt=\\\"company-image.jpg\\\" \\/><img src=\\\"\\/wildflower\\/uploads\\/company-image.jpg\\\" alt=\\\"company-image.jpg\\\" \\/><\\/h1>\",\"description_meta_tag\":\"\"}}',6,1,'2008-12-11 17:56:25'),(21,'wildpost',2,'{\"WildPost\":{\"title\":\"Git is COOL 213\",\"content\":\"<p>wildflower.categories: OK<br \\/>wildflower.categories_posts: Table is already up to date<br \\/>wildflower.comments: OK<br \\/>wildflower.messages: Table is already up to date<br \\/>wildflower.pages: OK<br \\/>wildflower.posts: Table is already up to date<br \\/>wildflower.revisions: Table is already up to date<br \\/>wildflower.schema_info: OK<br \\/>wildflower.settings: OK<br \\/>wildflower.sitemaps: Table is already up to date<br \\/>wildflower.tags: Table is already up to date<br \\/>wildflower.tags_uploads: Table is already up to date<br \\/>wildflower.uploads: OK<br \\/>wildflower.users: OK<\\/p>\\r\\n<p><img src=\\\"\\/wildflower\\/img\\/thumb\\/company-image.jpg\\/0\\/0\\\" alt=\\\"company-image.jpg\\\" \\/><img src=\\\"\\/wildflower\\/img\\/thumb\\/company-image.jpg\\/0\\/0\\\" alt=\\\"company-image.jpg\\\" \\/><img src=\\\"\\/wildflower\\/img\\/thumb\\/company-image.jpg\\/0\\/0\\\" alt=\\\"company-image.jpg\\\" \\/><\\/p>\",\"description_meta_tag\":\"\"}}',8,0,'2008-12-14 12:07:28'),(22,'wildpost',2,'{\"WildPost\":{\"title\":\"Git is COOL 213 asd\",\"content\":\"<p>wildflower.categories: OK<br \\/>wildflower.categories_posts: Table is already up to date<br \\/>wildflower.comments: OK<br \\/>wildflower.messages: Table is already up to date<br \\/>wildflower.pages: OK<br \\/>wildflower.posts: Table is already up to date<br \\/>wildflower.revisions: Table is already up to date<br \\/>wildflower.schema_info: OK<br \\/>wildflower.settings: OK<br \\/>wildflower.sitemaps: Table is already up to date<br \\/>wildflower.tags: Table is already up to date<br \\/>wildflower.tags_uploads: Table is already up to date<br \\/>wildflower.uploads: OK<br \\/>wildflower.users: OK<\\/p>\\r\\n<p><img src=\\\"\\/wildflower\\/img\\/thumb\\/company-image.jpg\\/0\\/0\\\" alt=\\\"company-image.jpg\\\" \\/><img src=\\\"\\/wildflower\\/img\\/thumb\\/company-image.jpg\\/0\\/0\\\" alt=\\\"company-image.jpg\\\" \\/><img src=\\\"\\/wildflower\\/img\\/thumb\\/company-image.jpg\\/0\\/0\\\" alt=\\\"company-image.jpg\\\" \\/><\\/p>\",\"description_meta_tag\":\"\"}}',9,0,'2008-12-14 12:12:02'),(23,'wildpost',2,'{\"WildPost\":{\"title\":\"Git is COOL\",\"content\":\"<p>wildflower.categories: OK<br \\/>wildflower.categories_posts: Table is already up to date<br \\/>wildflower.comments: OK<br \\/>wildflower.messages: Table is already up to date<br \\/>wildflower.pages: OK<br \\/>wildflower.posts: Table is already up to date<br \\/>wildflower.revisions: Table is already up to date<br \\/>wildflower.schema_info: OK<br \\/>wildflower.settings: OK<br \\/>wildflower.sitemaps: Table is already up to date<br \\/>wildflower.tags: Table is already up to date<br \\/>wildflower.tags_uploads: Table is already up to date<br \\/>wildflower.uploads: OK<br \\/>wildflower.users: OK<\\/p>\\r\\n<p><img src=\\\"\\/wildflower\\/img\\/thumb\\/company-image.jpg\\/0\\/0\\\" alt=\\\"company-image.jpg\\\" \\/><img src=\\\"\\/wildflower\\/img\\/thumb\\/company-image.jpg\\/0\\/0\\\" alt=\\\"company-image.jpg\\\" \\/><img src=\\\"\\/wildflower\\/img\\/thumb\\/company-image.jpg\\/0\\/0\\\" alt=\\\"company-image.jpg\\\" \\/><\\/p>\",\"description_meta_tag\":\"\"}}',10,0,'2008-12-14 12:16:21'),(24,'wildpost',2,'{\"WildPost\":{\"title\":\"Git is COOL 1\",\"content\":\"<p>wildflower.categories: OK<br \\/>wildflower.categories_posts: Table is already up to date<br \\/>wildflower.comments: OK<br \\/>wildflower.messages: Table is already up to date<br \\/>wildflower.pages: OK<br \\/>wildflower.posts: Table is already up to date<br \\/>wildflower.revisions: Table is already up to date<br \\/>wildflower.schema_info: OK<br \\/>wildflower.settings: OK<br \\/>wildflower.sitemaps: Table is already up to date<br \\/>wildflower.tags: Table is already up to date<br \\/>wildflower.tags_uploads: Table is already up to date<br \\/>wildflower.uploads: OK<br \\/>wildflower.users: OK<\\/p>\\r\\n<p><img src=\\\"\\/wildflower\\/img\\/thumb\\/company-image.jpg\\/0\\/0\\\" alt=\\\"company-image.jpg\\\" \\/><img src=\\\"\\/wildflower\\/img\\/thumb\\/company-image.jpg\\/0\\/0\\\" alt=\\\"company-image.jpg\\\" \\/><img src=\\\"\\/wildflower\\/img\\/thumb\\/company-image.jpg\\/0\\/0\\\" alt=\\\"company-image.jpg\\\" \\/><\\/p>\",\"description_meta_tag\":\"\"}}',11,0,'2008-12-14 12:38:36'),(25,'wildpost',4,'{\"WildPost\":{\"title\":\"Writing a new post\",\"content\":\"<p>Play tennis.<\\/p>\\r\\n<p><img src=\\\"\\/wildflower\\/uploads\\/Pixel_tennis_by_iTop_edition.gif\\\" alt=\\\"Pixel_tennis_by_iTop_edition.gif\\\" \\/><\\/p>\",\"description_meta_tag\":\"\"}}',7,0,'2008-12-14 12:44:13');
/*!40000 ALTER TABLE `revisions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `schema_info`
--

DROP TABLE IF EXISTS `schema_info`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `schema_info` (
  `version` int(11) unsigned NOT NULL default '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `schema_info`
--

LOCK TABLES `schema_info` WRITE;
/*!40000 ALTER TABLE `schema_info` DISABLE KEYS */;
INSERT INTO `schema_info` VALUES (10);
/*!40000 ALTER TABLE `schema_info` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `settings` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) collate utf8_unicode_ci NOT NULL,
  `value` text collate utf8_unicode_ci NOT NULL,
  `description` varchar(255) collate utf8_unicode_ci default NULL,
  `type` enum('text','textbox','select','checkbox','radio','password') collate utf8_unicode_ci NOT NULL,
  `label` varchar(255) collate utf8_unicode_ci default NULL,
  `order` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES (1,'site_name','Wildflower 1.2','','text',NULL,1),(2,'description','A CakePHP CMS','','textbox',NULL,2),(3,'home_page_id','52','Page that will be shown when visiting the site root.','select','Home page',3),(4,'contact_email','klevo@klevo.sk','You`ll receive notifications when somebody posts a comment or uses the contact form on this email address.','text','Contact email address',4),(5,'google_analytics_code','','','textbox',NULL,10),(6,'wordpress_api_key','','','text',NULL,9),(7,'smtp_server','','','text',NULL,6),(8,'smtp_username','','','text',NULL,7),(9,'smtp_password','','','text',NULL,8),(11,'email_delivery','debug',NULL,'select',NULL,5),(12,'cache','off',NULL,'select','Page and post caching',11);
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sitemaps`
--

DROP TABLE IF EXISTS `sitemaps`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `sitemaps` (
  `id` int(11) NOT NULL auto_increment,
  `lft` int(11) NOT NULL,
  `rght` int(11) NOT NULL,
  `parent_id` int(11) default NULL,
  `loc` varchar(255) collate utf8_unicode_ci NOT NULL,
  `lastmod` datetime default NULL,
  `changefreq` enum('always','hourly','daily','weekly','monthly','yearly','never') collate utf8_unicode_ci default NULL,
  `priority` float default NULL,
  PRIMARY KEY  (`id`),
  KEY `lft` (`lft`,`rght`,`parent_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Google Sitemap protocol compatible sitemap';
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `sitemaps`
--

LOCK TABLES `sitemaps` WRITE;
/*!40000 ALTER TABLE `sitemaps` DISABLE KEYS */;
/*!40000 ALTER TABLE `sitemaps` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tags`
--

DROP TABLE IF EXISTS `tags`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tags` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tags`
--

LOCK TABLES `tags` WRITE;
/*!40000 ALTER TABLE `tags` DISABLE KEYS */;
/*!40000 ALTER TABLE `tags` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tags_uploads`
--

DROP TABLE IF EXISTS `tags_uploads`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tags_uploads` (
  `upload_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL,
  KEY `tag_id` (`tag_id`),
  KEY `upload_id` (`upload_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tags_uploads`
--

LOCK TABLES `tags_uploads` WRITE;
/*!40000 ALTER TABLE `tags_uploads` DISABLE KEYS */;
/*!40000 ALTER TABLE `tags_uploads` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `uploads`
--

DROP TABLE IF EXISTS `uploads`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `uploads` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) collate utf8_unicode_ci NOT NULL,
  `title` varchar(255) collate utf8_unicode_ci NOT NULL,
  `mime` varchar(20) collate utf8_unicode_ci NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `mime` (`mime`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `uploads`
--

LOCK TABLES `uploads` WRITE;
/*!40000 ALTER TABLE `uploads` DISABLE KEYS */;
INSERT INTO `uploads` VALUES (7,'Good_Vibrations-1440x900.jpg','Good_Vibrations-1440x900.jpg','image/jpeg','2008-10-10 12:49:23','2008-10-10 12:49:23'),(9,'company-image.jpg','company-image.jpg','image/jpeg','2008-12-05 18:25:06','2008-12-05 18:25:06'),(10,'12-09-2002 17.30.57.JPG','That\'s klevo doing Aikido','image/jpeg','2008-12-05 18:25:39','2008-12-05 18:25:39'),(11,'Pixel_tennis_by_iTop_edition.gif','Pixel_tennis_by_iTop_edition.gif','image/gif','2008-12-14 12:43:22','2008-12-14 12:43:22');
/*!40000 ALTER TABLE `uploads` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `users` (
  `id` int(11) NOT NULL auto_increment,
  `login` varchar(255) collate utf8_unicode_ci NOT NULL,
  `password` char(40) collate utf8_unicode_ci NOT NULL,
  `email` varchar(255) collate utf8_unicode_ci NOT NULL,
  `name` varchar(255) collate utf8_unicode_ci default NULL,
  `cookie` varchar(255) collate utf8_unicode_ci default NULL,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `cookie` (`cookie`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'admin','24c05ce1409afb5dad4c5bddeb924a4bc3ea00f5','admin@localhost.sk','Mr Admin','4902c954-4788-4a46-bc75-00a7a695e31f','2008-07-11 14:24:43','2008-10-25 09:23:00');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2008-12-14 12:44:15
