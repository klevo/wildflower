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
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,NULL,1,8,'php5','PHP5',''),(2,NULL,11,12,'rails','Rails',''),(19,14,3,4,'grails','Grails','123'),(4,NULL,9,10,'no-meaning','No meaning','For posts without meaning.'),(21,NULL,13,14,'really-new-caegory-very-very-loooong','Really new caegory, very very loooong','123dasjjj'),(14,1,2,7,'personal-develompent','Personal Development',''),(23,14,5,6,'123-1','123','asdsad');
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
INSERT INTO `categories_posts` VALUES (1,2),(2,26),(4,31),(19,28),(19,31),(21,28),(21,31);
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
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `comments`
--

LOCK TABLES `comments` WRITE;
/*!40000 ALTER TABLE `comments` DISABLE KEYS */;
INSERT INTO `comments` VALUES (13,27,'klevoo','klevo@klevo.sk','','cache? 123',0,'2008-09-16 09:59:41','2008-10-20 15:15:49'),(14,27,'klevo','klevo@klevo.sk','','no cache now',0,'2008-09-16 10:01:24','2008-09-16 10:01:24');
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
INSERT INTO `pages` VALUES (52,NULL,1,4,0,'home-features','/','Home & Features','<ul id=\"home-feature-list\">\n<li id=\"feature-cake\">Content management system and application platform build on <a href=\"http://www.cakephp.org\">CakePHP</a> framework and <a href=\"http://jquery.com\">jQuery</a> Javascript library.</li>\n<li id=\"feature-standards\">Standards based.</li>\n<li id=\"feature-open-source\">Open source.</li>\n<li>User friendly.</li>\n<li><img src=\"/uploads/unit-tests-not.gif\" alt=\"unit-tests-not.gif\" /></li>\n<li>Requires up-to-date LAMP stacks. PHP 5.2+, MySQL 4.1+, mod_rewrite.</li>\n<li><a href=\"/feature-tour\">More features</a></li>\n</ul>\n<p class=\"download\"><a href=\"http://wildflower.googlecode.com/files/wildflower10a.zip\"><strong>Download now</strong><br /> Wildflower 1.0a, released 29th February 2008</a></p>','Content management system and application platform build on CakePHP framework and jQuery Javascript library.',NULL,0,'2008-02-26 16:09:00','2008-07-24 17:25:57',NULL,NULL),(53,NULL,11,16,0,'feature-tour','/feature-tour','Feature tour','<h3>Ease of use</h3>\n<p>The administration interface is optimized for the ease of use. When editing a page or a post, the integrated WYSIWYG editor automatically resizes to fit the user\'s screen height.</p>\n<h3>File manager II<br /></h3>\n<p>Upload any file type. You can categorize your uploads using tags.</p>\n<p><img src=\"/wildflower/img/thumb/All_falls_down-1440x900.jpg/120/120/1\" alt=\"\" /><img src=\"/wildflower/img/thumb/Igaer-1400x1050.jpg/120/120/1\" alt=\"\" /><img src=\"/wildflower/img/thumb/All_falls_down-1440x900.jpg/120/120/1\" alt=\"\" /><img src=\"/wildflower/img/thumb/The_night_is_coming-1400x1050.jpg/120/120/1\" alt=\"\" /></p>\n<h3>Revisions</h3>\n<p>Every change you do to a page or a post is remembered and you can go back to it. No more lost content.</p>','',NULL,0,'2008-02-26 17:44:00','2008-10-08 15:00:36','',1),(54,NULL,5,6,0,'documentation','/documentation','Documentation','<p><em>This section is slowly being filled up. Please be patient.</em></p>\r\n<p>Since a new release of Wildflower is on the way, with a lot of core changes, <strong>the following</strong> is intended for the code you can <a href=\"http://code.google.com/p/wildflower/source/checkout\">check out from Google Code</a>.</p>\r\n<h3>Who is it for?</h3>\r\n<p>Everyone who is able to deploy Wordpress should be able to get Wildflower up and running. However to fully enjoy and benefit from the features of this CMS, you should be competent in these areas (or willing to learn):</p>\r\n<ul>\r\n<li>Code separation. Understand the difference between view and bussiness logic.</li>\r\n<li>RESTful architecture.</li>\r\n<li>Unit testing.</li>\r\n<li>The heart of Wildflower is the CakePHP framework.</li>\r\n</ul>\r\n<p>This means this system is not so much for a typical PHP hacker, but for a programmer that is willing to learn and adopt the best practices and takes his profession seriously. Did I mention doing things in Cake is so much fun?</p>\r\n<h3>Requirements</h3>\r\n<ul>\r\n<li>Apache web server with mod_rewrite</li>\r\n<li>PHP 5.2+</li>\r\n<li>MySQL 4.1+</li>\r\n<li>If you want to use the <a href=\"http://code.google.com/p/ruckusing/\">Ruckusing database migrations</a> you need PEAR\'s MDB2 and Log packages<br /></li>\r\n</ul>\r\n<h3>Fresh installation</h3>\r\n<ol>\r\n<li>Extract the archive. Place the <em>wildflower</em> directory inside your web servers documents folder.</li>\r\n<li>Create a new MySQL database (<em>utf8_unicode_ci</em> collation is strongly recommented) and into this new database import the SQL file <em>wildflower/config/sql/wildflower.sql</em>.</li>\r\n<li>Edit the <em>app/config/database.php</em> file with your database connection details.</li>\r\n<li>You\'ve got a working copy of this site. You can start working on your project by modifying the application inside the <em>app</em> directory. When a new release of Wildflower comes, you simply replace the <em>cake</em>, <em>vendors</em> and <em>wildflower</em> directories.</li>\r\n</ol>\r\n<h3>Installing to an existing CakePHP application</h3>\r\n<ol>\r\n<li>Extract the archive and place the <em>wildflower/wildflower</em> directory inside your application root.</li>\r\n<li>Just include the Wildflower <em>bootstrap.php</em> file located at <em>/wildflower/config/bootstrap.php</em> in your <em>/app/config/bootstrap.php</em>.</li>\r\n<li>Load the SQL dump file <em>app/config/sql/wildflower.sql</em>&nbsp;into your database.</li>\r\n<li>Set up some routes. Check the <em>wildflower/config/routes.php</em> file for the default WF routes.<br /></li>\r\n</ol>\r\n<h3>Basic principles</h3>\r\n<p>A modern website usually consist of \"static\" pages, news or blog sections, contact form, provides RSS feeds and a number of features, specific to the site\'s aim or goal. The idea of Wildflower is to provide this common functionality, with a polished and user friendly interface and enable the programmer to effectively code the remaining specific features of the site, fully exploiting the PHP rapid development framework--CakePHP.</p>\r\n<p>Wildflower uses the additional MVC paths feature of the CakePHP framework. It sits in it\'s own directory inside the application root. This allows the user to create application specific controllers, models or views in her <em>/app</em> directory. By mirroring any view file from the wildflower/views folder inside app/views you can override the default Wildflower files. This is a great way to customize any aspect of the CMS, especially extending the admin interface with additional sections or customizing existing ones to the site\'s needs without touching the original Wildflower code.</p>\r\n<h3>A real world example of building a site with some custom functionality</h3>\r\n<p>Imagine you want to build a simple site with the following requirements:</p>\r\n<ul>\r\n<li>Content managed \"static\" pages</li>\r\n<li>Contact form</li>\r\n<li>Home page with four boxes with different content and each content managed</li>\r\n</ul>\r\n<p>The first two requirements you\'ve got out of the box. For the third one we\'ll create a new section in the admin interface and build our own model/view/controller that will handle the custom functionality.</p>\r\n<h4>Step 1: Create a new database table</h4>\r\n<p>todo.</p>\r\n<h4>Step 2: Create the MVC files</h4>\r\n<p>For this simple example we actually don\'t need to create any model file, since CakePHP supports <a href=\"http://www.littlehart.net/atthekeyboard/2008/08/05/dynamic-models-in-cakephp-12/\">dynamic models</a>. So let\'s create our controller. We\'ll create a file in <em>app/controllers/</em> called <em>home_page_boxes_controller.php</em> and put some code into it:</p>\r\n<pre>&lt;?php<br />class HomePageBoxesController extends AppController {<br />&nbsp;&nbsp;&nbsp; <br />&nbsp;&nbsp;&nbsp; function beforeFilter() {<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; parent::beforeFilter();<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; $this-&gt;pageTitle = \'Home\';<br />&nbsp;&nbsp;&nbsp; }<br /><br />&nbsp;&nbsp;&nbsp; function admin_index() {<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; $boxes = $this-&gt;HomePageBox-&gt;find(\'all\', \'id IN (1, 2, 3, 4)\');<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; $this-&gt;set(compact(\'boxes\'));<br />&nbsp;&nbsp;&nbsp; }<br />&nbsp;&nbsp;&nbsp; <br />&nbsp;&nbsp;&nbsp; function admin_update() {<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; foreach ($this-&gt;data[\'HomePageBox\'] as $name =&gt; $content) {<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; $id = explode(\'-\', $name);<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; $id = intval(array_pop($id));<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; $data[\'HomePageBox\'] = array(\'id\' =&gt; $id, \'content\' =&gt; $content);<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; $this-&gt;HomePageBox-&gt;create($data);<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; $this-&gt;HomePageBox-&gt;save(); <br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; }<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; $this-&gt;redirect(array(\'action\' =&gt; \'index\'));<br />&nbsp;&nbsp;&nbsp; }<br />&nbsp;&nbsp;&nbsp; <br />&nbsp;&nbsp;&nbsp; function index() {<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; $boxes = $this-&gt;HomePageBox-&gt;find(\'all\', \'id IN (1, 2, 3, 4)\');<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; $this-&gt;set(compact(\'boxes\'));<br />&nbsp;&nbsp;&nbsp; }<br />&nbsp;&nbsp;&nbsp; <br />}<br /></pre>\r\n<p>This code handles populating the the homepage and the admin section with data and updating (saving) new content in the admin section. Let\'s create the view files. Create a new folder called <em>home_page_boxes</em> under <em>app/views/</em> . Under this folder add <em>admin_index.ctp</em> and <em>index.ctp</em> files.</p>\r\n<p><em>admin_index.ctp</em> could look like this:</p>\r\n<pre>&lt;h2&gt;Homepage Boxes&lt;/h2&gt;<br /><br />&lt;?php<br />    echo $form-&gt;create(\'HomePageBox\', array(\'action\' =&gt; \'update\'));<br />    <br />    for ($i = 0; $i &lt; 4; $i++) {<br />        echo <br />        $form-&gt;input(\"content-{$boxes[$i][\'HomePageBox\'][\'id\']}\", <br />            array(\'type\' =&gt; \'textbox\', <br />                    \'value\' =&gt; $boxes[$i][\'HomePageBox\'][\'content\'], <br />                    \'label\' =&gt; \'Box \' . ($i + 1), \'between\' =&gt; \'&lt;br /&gt;\', \'class\' =&gt; \'box-fck\')),<br />        $form-&gt;submit(\'Save\');<br />    }<br />    <br />    echo $form-&gt;end();<br />?&gt;<br /></pre>\r\n<p>This will create an admin page with four TinyMCE editors each handling one box. Let\'s take a look at the <em>index.ctp</em> file:</p>\r\n<pre>&lt;?php foreach ($boxes as $box) { ?&gt;<br /><br />&lt;div class=\"home-box\"&gt;<br />    &lt;?php echo $box[\'HomePageBox\'][\'content\'] ?&gt;<br />&lt;/div&gt;<br /><br />&lt;?php } ?&gt;<br /></pre>\r\n<p>This will display the four boxes and their content.</p>\r\n<p>Now <strong>copy</strong> the <em>/wildflower/views/layout/admin_default.ctp</em> to <em>/app/views/layouts/</em> . There you can modify the file and add a link to our new home page boxes admin screen to the admin main menu. I\'ll leave this step to you. Remember: <strong>Every Wildflower view file that you mirror inside your app/views will be used instead of the original</strong>.</p>\r\n<h4>Step 3: Routes</h4>\r\n<p>Finally we need to let Cake know that we want to display the HomePageBoxesController::index() action when browsing to <em>your-site.com </em>root. Modify the first two routes in <em>app/config/routes.php</em> like this:</p>\r\n<pre>Router::connect(\'/\', array(\'controller\' =&gt; \'home_page_boxes\', \'action\' =&gt; \'index\'));<br />Router::connect(\'/app/webroot/\', array(\'controller\' =&gt; \'home_page_boxes\', \'action\' =&gt; \'index\'));<br /></pre>\r\n<p>Maybe you\'re wondering about the second route. On some server configurations Cake incorrectly detects the site root (/) as <em>/app/webroot</em> . This fixes it. If you don\'t experience this problem (the home page works fine without the second route) you can remove the route.</p>\r\n<p>As you can see, Wildflower enables you to use all the CakePHP power together with the out of the box functionality.</p>','',NULL,0,'2008-02-27 16:20:00','2008-09-09 21:01:36','',1),(64,53,12,15,1,'features-and-pages','/feature-tour/features-and-pages','Code, code, code','<p class=\"cake-debug\">Hello world <a href=\"#title\">how</a> are we today.</p>\r\n<h2 class=\"cake-debug\">Heading &lt;h2&gt;</h2>\r\n<p style=\"padding-left: 30px;\">This option enables you to specify a custom CSS file that extends the theme content <span style=\"background-color: #ff9900;\">CSS</span>. This <span style=\"color: #ff6600;\">CSS</span> file is the one used within the editor (the editable area). This option can also be a comma separated list of URLs.</p>\r\n<p class=\"cake-debug\">If you specify a relative path, it is resolved in relation to the URL of the (HTML) file that includes TinyMCE, NOT relative to TinyMCE itself.</p>\r\n<p class=\"cake-debug\"><img src=\"/wildflower/img/thumb/vetton_ru_501.jpg/120/120/1\" alt=\"\" /><img src=\"/wildflower/img/thumb/All_falls_down-1440x900.jpg/120/120/1\" alt=\"\" /><img src=\"/wildflower/img/thumb/Step_forward_little_tommy-1400x1050.jpg/120/120/1\" alt=\"\" /><br /><span style=\"text-decoration: line-through;\">strike me</span></p>','',NULL,1,'2008-07-01 16:40:00','2008-08-11 12:11:19','',1),(79,64,13,14,2,'a-page-about-nothing','/feature-tour/features-and-pages/a-page-about-nothing','A page about nothing 12','<p>\'wf_update\' sad da as das asd 213 sadsa sadas asd</p>\n<p>&nbsp;</p>\n<p>sadsad</p>\n<p>sad</p>','',NULL,0,'2008-07-02 20:29:00','2008-10-20 13:20:26','<p>efgh</p>',0),(83,NULL,7,8,0,'bugs','/bugs','Bugs','<ul>\n<li>Post can be viewed even if draft</li>\n<li>Add new category parent select box shows parents that should not be available</li>\n<li>When trying to edit a non existent page an SQL error is shown</li>\n<li>Public search should not search in drafts</li>\n<li>Upload::create does not fill short_name</li>\n<li>Image browser CSS rf &amp; IE7 fixing</li>\n<li>Image browser can be open multiple times and get\'s fcked up</li>\n<li>I deleted a comment an another one got deleted</li>\n<li>Preview does not render home template</li>\n<li>Deleting a comment a clicking cancel deletes the comment!</li>\n<li>When a new revision gets added by AJAX the old first one remains with link witouth a rev num.</li>\n</ul>\n<p>Opera bugs:</p>\n<ul>\n<li>live search cancel...</li>\n</ul>\n<p>IE7:</p>\n<ul>\n<li>sub toolbar get f*cked up on page edit screen when a write new post/page is used and then canceled</li>\n</ul>','',NULL,0,'2008-07-07 08:43:00','2008-10-07 15:08:59','',1),(164,NULL,19,22,0,'root','/root','root','','',NULL,0,'2008-09-13 17:54:00','2008-09-13 17:54:48','',1),(158,NULL,17,18,0,'wf-is-the-best','/wf-is-the-best','UI facelift','<p>New graphic elements.</p>','',NULL,0,'2008-08-08 22:26:00','2008-10-08 20:49:39','',1),(116,NULL,9,10,0,'todo-enhancements','/todo-enhancements','TODO, Enhancements','<ul>\n<li>Add keyboard shortcuts - like alt-A go to index...etc.<br /></li>\n<li>Google sitemaps generation</li>\n<li>SWFupload</li>\n<li>Password strength check from Wordpress</li>\n<li>Shift click on list to select more items</li>\n<li>Pages list drag and drop</li>\n<li>JLM compression and caching</li>\n<li>File manager folders</li>\n</ul>\n<p><img src=\"/wildflower/img/thumb/The_night_is_coming-1400x1050.jpg/120/120/1\" alt=\"\" /></p>','',NULL,1,'2008-07-15 20:44:00','2008-10-24 15:56:28','<p>helo</p>',0),(157,52,2,3,1,'test-page','/home-features/test-page','test page','<p>I want candy!</p>','',NULL,0,'2008-08-08 18:21:00','2008-08-12 16:44:08','',1),(165,164,20,21,1,'child','/root/child','child','','',NULL,0,'2008-09-13 17:54:00','2008-10-07 16:08:18','',1),(167,NULL,25,26,0,'vranne-kone','/vranne-kone','Vranné kone','Lorem ipsum. Vranné kone.',NULL,NULL,0,'2008-10-24 15:11:38','2008-10-24 15:11:38',NULL,NULL),(166,NULL,23,24,0,'hey-joe','/hey-joe','Hey Joe',NULL,NULL,NULL,1,'2008-10-04 00:56:34','2008-10-04 00:56:34',NULL,NULL),(171,NULL,31,32,0,'some-longer-title-with-number-467-1','/some-longer-title-with-number-467-1','Article 1','Lorem ipsum dolor sit amer. 123. čľž+áľšýí.11_?',NULL,NULL,0,'2008-10-24 16:54:20','2008-10-24 16:54:20',NULL,NULL),(168,NULL,NULL,NULL,0,'some-longer-title-with-number-467','/','Article 1','Lorem ipsum dolor sit amer. 123. čľž+áľšýí.11_?',NULL,NULL,0,'2008-10-24 15:11:38','2008-10-24 15:11:38',NULL,NULL),(169,NULL,27,28,0,'vranne-kone-1','/vranne-kone-1','Vranné kone','<p>Lorem ipsum. Vrann&eacute; kone.</p>','',NULL,0,'2008-10-24 15:12:00','2008-10-25 08:16:21','',1),(170,NULL,29,30,0,'contact','/contact','Contact formular','<p>This is a contact form for you.</p>','',NULL,0,'2008-10-24 15:12:00','2008-10-24 16:03:42','',0);
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
  PRIMARY KEY  (`id`),
  UNIQUE KEY `slug` (`slug`),
  FULLTEXT KEY `content` (`content`)
) ENGINE=MyISAM AUTO_INCREMENT=33 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `posts`
--

LOCK TABLES `posts` WRITE;
/*!40000 ALTER TABLE `posts` DISABLE KEYS */;
INSERT INTO `posts` VALUES (1,'a-shiny-new-post','There are not many posts out there','<div id=\"lipsum\">\n<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. In imperdiet odio in augue. Sed pharetra. Nullam faucibus odio. Nam rhoncus tristique augue. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Integer congue dapibus metus. Phasellus sed elit sodales orci iaculis tincidunt. Duis hendrerit, nulla eu hendrerit fermentum, diam sapien commodo enim, sed rutrum purus sapien sed pede. Phasellus vitae quam. Morbi aliquam, leo vitae consectetuer consectetuer, ligula diam volutpat eros, imperdiet egestas nulla tortor ac dui. Etiam feugiat, dui nec pharetra pharetra, erat augue vulputate sapien, ut tristique lacus felis at eros. Pellentesque eu erat. Nullam aliquet mollis dolor. Ut est orci, tempus pellentesque, semper sit amet, scelerisque in, quam. Aliquam consequat, orci nec ullamcorper condimentum, nibh ligula dictum nulla, eget pulvinar velit lacus sit amet nulla. Etiam semper faucibus mi. Aenean nunc sapien, venenatis vitae, dapibus sit amet, auctor non, lacus. Phasellus porttitor ante sit amet turpis. Vestibulum nec erat. Maecenas eros.</p>\n<p><img src=\"/wildflower/img/thumb/Good_Vibrations-1440x900.jpg/120/120/1\" alt=\"\" /></p>\n<p>Nullam quis nulla non sapien interdum varius. Cras hendrerit elementum leo. Fusce tincidunt, justo eu eleifend elementum, ante arcu blandit dolor, quis ullamcorper dui tellus sit amet quam. Vestibulum vulputate. Morbi mi odio, consectetuer ut, vulputate vitae, tristique ut, ipsum. Donec ipsum tortor, pulvinar a, pulvinar eget, commodo non, odio. Nullam dolor. Aliquam erat volutpat. Phasellus libero. Vivamus luctus lobortis libero. Ut ut elit. Sed elementum quam nec arcu. Nam id tellus non odio fermentum convallis. Nam a lacus.</p>\n<p>Phasellus ante arcu, gravida a, lobortis sit amet, volutpat non, velit. Nulla consectetuer quam gravida nulla. Integer eu purus. Morbi sit amet nunc. Mauris vehicula lacus ac lectus. Proin tortor nisl, faucibus non, molestie nec, tincidunt non, justo. Suspendisse massa lectus, hendrerit aliquam, elementum et, iaculis non, nunc. Etiam non dui. Morbi gravida massa sollicitudin ipsum. Suspendisse magna ante, facilisis ac, sagittis et, placerat et, diam. Fusce facilisis, nulla ac accumsan facilisis, mi nisi tristique quam, malesuada pulvinar neque leo ut augue. Mauris dui.</p>\n<p>Etiam nec risus at leo ullamcorper lobortis. In rhoncus massa ac velit. Nullam mollis consequat ligula. Integer iaculis, enim sed cursus hendrerit, neque dolor hendrerit erat, dignissim egestas quam quam vel quam. Etiam tellus libero, molestie non, mattis in, venenatis sed, dui. Proin non nisl ut massa ullamcorper interdum. Aliquam erat volutpat. Sed gravida. Quisque quis magna. Quisque non metus. Nullam euismod suscipit elit. Vivamus quis risus. Phasellus ut lectus. Nunc velit sem, viverra sed, convallis eu, convallis a, nisl. Maecenas bibendum orci in enim. Sed orci. Nullam adipiscing pellentesque purus. Sed risus orci, consequat nec, ornare sed, condimentum semper, mi. Fusce hendrerit, justo non volutpat pretium, neque mauris placerat est, id pretium mauris libero id eros.</p>\n<p>Sed risus mi, vestibulum ac, tincidunt at, condimentum id, tortor. Donec non mauris sed leo auctor auctor. Nullam facilisis. Quisque eu ipsum. Donec quis sem. Morbi rutrum magna in justo. Vestibulum eu orci. Praesent placerat, ipsum eget bibendum vulputate, velit dolor ultrices metus, tempus congue est lorem a eros. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Nam consectetuer erat et urna.</p>\n</div>',1,'bla bla bla','','2008-09-11 14:48:00','2008-09-16 09:56:43',0),(2,'another-new-post','Another new post','<p>And now we do this... 13456</p>',1,'',NULL,'2006-09-11 14:48:00','2008-10-07 16:52:38',0),(21,'today-i-ve-build-a-lot','Today I\'ve build a lot','<p>It\'s<img src=\"/wildflower/img/thumb/Little_World-1400x1050.jpg/120/120/1\" alt=\"\" /> true.<img src=\"/wildflower/img/thumb/Fantasia-1600x1200.jpg/120/120/1\" alt=\"\" /></p>',0,'',NULL,'2007-08-11 18:01:00','2008-09-21 20:22:34',0),(8,'use-base','use Base','<p>1234</p>\n<p><img src=\"/wildflower/img/thumb/Fantasia-1600x1200.jpg/120/120/1\" alt=\"\" /></p>',1,'',NULL,'2007-07-12 11:05:00','2008-08-26 19:23:55',1),(22,'lol-post','lol post','',1,'',NULL,'2006-10-17 17:59:00','2008-08-26 19:22:06',0),(23,'loool-post','loool post','<p>dasdas</p>',1,'',NULL,'2008-08-17 17:59:00','2008-10-07 15:09:39',1),(24,'hmm','hmm hmm #1','<p>sadasds</p>',1,'',NULL,'2008-08-17 17:59:00','2008-09-26 11:37:44',0),(32,'999','999',NULL,0,NULL,NULL,'2008-10-24 16:10:14','2008-10-24 16:10:14',0),(26,'lala-lala-la','lala lala la','<p>dsadasd</p>',1,'a description čšľá',NULL,'2008-08-17 18:53:00','2008-09-26 14:18:17',0),(28,'my-new-post-1','my new post','',0,'',NULL,'2008-09-09 21:06:00','2008-10-24 09:01:44',0),(29,'safari','safari',NULL,0,NULL,NULL,'2008-09-09 21:06:49','2008-09-09 21:06:49',1),(30,'','123',NULL,0,NULL,NULL,'2008-09-21 18:26:26','2008-09-21 18:26:26',0),(31,'cakephp-1-2-rc3','CakePHP 1.2 RC3','<p>Uptated to&nbsp;CakePHP 1.2 RC3</p>',0,'',NULL,'2008-10-24 09:05:00','2008-10-24 13:16:33',0);
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
) ENGINE=MyISAM AUTO_INCREMENT=38 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `revisions`
--

LOCK TABLES `revisions` WRITE;
/*!40000 ALTER TABLE `revisions` DISABLE KEYS */;
INSERT INTO `revisions` VALUES (1,'wildpage',79,'{\"WildPage\":{\"title\":\"A page about nothing\",\"content\":\"<p>\'wf_update\' sad da as das asd<\\/p>\",\"description_meta_tag\":\"\"}}',1,1,'2008-09-21 10:00:17'),(2,'wildpage',79,'{\"WildPage\":{\"title\":\"A page about nothing ss\",\"content\":\"<p>\'wf_update\' sad da as das asd 213<\\/p>\",\"description_meta_tag\":\"\"}}',2,1,'2008-09-21 10:22:26'),(3,'wildpost',21,'{\"WildPost\":{\"title\":\"Today I\'ve build a lot\",\"content\":\"<p>It\'s<img src=\\\"\\/wildflower\\/img\\/thumb\\/Little_World-1400x1050.jpg\\/120\\/120\\/1\\\" alt=\\\"\\\" \\/> true.<img src=\\\"\\/wildflower\\/img\\/thumb\\/Fantasia-1600x1200.jpg\\/120\\/120\\/1\\\" alt=\\\"\\\" \\/><\\/p>\",\"description_meta_tag\":\"\"}}',1,1,'2008-09-21 20:22:34'),(4,'wildpost',28,'{\"WildPost\":{\"title\":\"my new post\",\"content\":\"\",\"description_meta_tag\":\"\"}}',1,1,'2008-09-21 21:33:51'),(5,'wildpost',27,'{\"WildPost\":{\"title\":\"my new post1\",\"content\":\"<p>svn st 213<\\/p>\",\"description_meta_tag\":\"\"}}',1,1,'2008-09-25 16:22:01'),(6,'wildpage',79,'{\"WildPage\":{\"title\":\"A page about nothing\",\"content\":\"<p>\'wf_update\' sad da as das asd 213<\\/p>\",\"description_meta_tag\":\"\"}}',3,1,'2008-09-26 11:09:55'),(7,'wildpage',79,'{\"WildPage\":{\"title\":\"A page about nothing\",\"content\":\"<p>\'wf_update\' sad da as das asd 213 sadsa<\\/p>\",\"description_meta_tag\":\"\"}}',4,1,'2008-09-26 11:20:48'),(8,'wildpage',79,'{\"WildPage\":{\"title\":\"A page about nothing\",\"content\":\"<p>\'wf_update\' sad da as das asd 213 sadsa sadas<\\/p>\",\"description_meta_tag\":\"\"}}',5,1,'2008-09-26 11:21:10'),(9,'wildpage',79,'{\"WildPage\":{\"title\":\"A page about nothing\",\"content\":\"<p>\'wf_update\' sad da as das asd 213 sadsa sadas asd<\\/p>\",\"description_meta_tag\":\"\"}}',6,1,'2008-09-26 11:24:31'),(10,'wildpage',79,'{\"WildPage\":{\"title\":\"A page about nothing\",\"content\":\"<p>\'wf_update\' sad da as das asd 213 sadsa sadas asd<\\/p>\\n<p>&nbsp;<\\/p>\\n<p>sadsad<\\/p>\\n<p>sad<\\/p>\",\"description_meta_tag\":\"\"}}',7,1,'2008-09-26 11:24:44'),(11,'wildpage',79,'{\"WildPage\":{\"title\":\"A page about nothing 1\",\"content\":\"<p>\'wf_update\' sad da as das asd 213 sadsa sadas asd<\\/p>\\n<p>&nbsp;<\\/p>\\n<p>sadsad<\\/p>\\n<p>sad<\\/p>\",\"description_meta_tag\":\"\"}}',8,1,'2008-09-26 11:31:05'),(12,'wildpage',79,'{\"WildPage\":{\"title\":\"A page about nothing 12\",\"content\":\"<p>\'wf_update\' sad da as das asd 213 sadsa sadas asd<\\/p>\\n<p>&nbsp;<\\/p>\\n<p>sadsad<\\/p>\\n<p>sad<\\/p>\",\"description_meta_tag\":\"\"}}',9,1,'2008-09-26 11:31:11'),(13,'wildpage',83,'{\"WildPage\":{\"title\":\"Bugs\",\"content\":\"<ul>\\n<li>Post can be viewed even if draft<\\/li>\\n<li>Add new category parent select box shows parents that should not be available<\\/li>\\n<li>When trying to edit a non existent page an SQL error is shown<\\/li>\\n<li>Public search should not search in drafts<\\/li>\\n<li>Upload::create does not fill short_name<\\/li>\\n<li>Image browser CSS rf &amp; IE7 fixing<\\/li>\\n<li>Image browser can be open multiple times and get\'s fcked up<\\/li>\\n<li>I deleted a comment an another one got deleted<\\/li>\\n<li>Preview does not render home template<\\/li>\\n<li>Deleting a comment a clicking cancel deletes the comment!<\\/li>\\n<li>When a new revision gets added by AJAX the old first one remains with link witouth a rev num.<\\/li>\\n<\\/ul>\\n<p>Opera bugs:<\\/p>\\n<ul>\\n<li>live search cancel...<\\/li>\\n<\\/ul>\\n<p>IE7:<\\/p>\\n<ul>\\n<li>sub toolbar get f*cked up on page edit screen when a write new post\\/page is used and then canceled<\\/li>\\n<\\/ul>\",\"description_meta_tag\":\"\"}}',1,1,'2008-09-26 11:33:01'),(14,'wildpost',24,'{\"WildPost\":{\"title\":\"hmm hmm\",\"content\":\"<p>sadasds<\\/p>\",\"description_meta_tag\":\"\"}}',1,1,'2008-09-26 11:37:11'),(15,'wildpost',24,'{\"WildPost\":{\"title\":\"hmm hmm\",\"content\":\"<p>sadasds sadasd<\\/p>\",\"description_meta_tag\":\"\"}}',2,1,'2008-09-26 11:37:20'),(16,'wildpost',24,'{\"WildPost\":{\"title\":\"hmm hmm\",\"content\":\"<p>sadasds sadasd asdsadsa<\\/p>\",\"description_meta_tag\":\"\"}}',3,1,'2008-09-26 11:37:23'),(17,'wildpost',24,'{\"WildPost\":{\"title\":\"hmm hmm #1\",\"content\":\"<p>sadasds<\\/p>\",\"description_meta_tag\":\"\"}}',4,1,'2008-09-26 11:37:44'),(18,'wildpost',25,'{\"WildPost\":{\"title\":\"test 123\",\"content\":\"<p><img src=\\\"\\/wildflower\\/img\\/thumb\\/vetton_ru_501.jpg\\/120\\/120\\/1\\\" alt=\\\"\\\" \\/><img src=\\\"\\/wildflower\\/img\\/thumb\\/Good_Vibrations-1440x900.jpg\\/120\\/120\\/1\\\" alt=\\\"\\\" \\/><\\/p>\\n<p>Abcdedfgh.<\\/p>\",\"description_meta_tag\":\"\"}}',1,1,'2008-09-26 11:54:53'),(19,'wildpost',26,'{\"WildPost\":{\"title\":\"lala lala la\",\"content\":\"\",\"description_meta_tag\":\"\"}}',1,1,'2008-09-26 12:05:36'),(20,'wildpost',26,'{\"WildPost\":{\"title\":\"lala lala la\",\"content\":\"<p>dsadasd<\\/p>\",\"description_meta_tag\":\"\"}}',2,1,'2008-09-26 12:06:32'),(21,'wildpost',26,'{\"WildPost\":{\"title\":\"lala lala la\",\"content\":\"<p>dsadasd<\\/p>\",\"description_meta_tag\":\"a description \\u010d\\u0161\\u013e\\u00e1\"}}',3,1,'2008-09-26 14:18:17'),(22,'wildpost',27,'{\"WildPost\":{\"title\":\"my new post1\",\"content\":\"<p>svn st 213 da<\\/p>\",\"description_meta_tag\":\"\"}}',2,1,'2008-09-27 11:05:15'),(23,'wildpost',27,'{\"WildPost\":{\"title\":\"my new post1\",\"content\":\"<p>svn st 213 da <img src=\\\"\\/wildflower\\/img\\/thumb\\/Rembrandt-Belsazar.jpg\\/333\\/444\\\" alt=\\\"\\\" \\/><\\/p>\",\"description_meta_tag\":\"\"}}',3,1,'2008-09-27 11:05:46'),(24,'wildpage',166,'{\"WildPage\":{\"title\":\"Hey Joe\"}}',1,1,'2008-10-04 00:56:34'),(25,'wildpage',116,'{\"WildPage\":{\"title\":\"TODO, Enhancements\",\"content\":\"<ul>\\r\\n<li>Add keyboard shortcuts - like alt-A go to index...etc.<br \\/><\\/li>\\r\\n<li>Google sitemaps generation<\\/li>\\r\\n<li>SWFupload<\\/li>\\r\\n<li>Password strength check from Wordpress<\\/li>\\r\\n<li>Shift click on list to select more items<\\/li>\\r\\n<li>Pages list drag and drop<\\/li>\\r\\n<li>JLM compression and caching<\\/li>\\r\\n<li>File manager folders<\\/li>\\r\\n<\\/ul>\\r\\n<p><img src=\\\"\\/wildflower\\/img\\/thumb\\/The_night_is_coming-1400x1050.jpg\\/120\\/120\\/1\\\" alt=\\\"\\\" \\/><\\/p>\",\"description_meta_tag\":\"\"}}',1,1,'2008-10-07 13:51:57'),(26,'wildpost',23,'{\"WildPost\":{\"title\":\"loool post\",\"content\":\"<p>dasdas<\\/p>\",\"description_meta_tag\":\"\"}}',1,1,'2008-10-07 15:09:23'),(27,'wildpage',165,'{\"WildPage\":{\"title\":\"child\",\"content\":\"\",\"description_meta_tag\":\"\"}}',1,1,'2008-10-07 15:12:50'),(28,'wildpost',2,'{\"WildPost\":{\"title\":\"Another new post\",\"content\":\"<p>And now we do this... 13456<\\/p>\",\"description_meta_tag\":\"\"}}',1,1,'2008-10-07 16:12:14'),(29,'wildpage',53,'{\"WildPage\":{\"title\":\"Feature tour\",\"content\":\"<h3>Ease of use<\\/h3>\\n<p>The administration interface is optimized for the ease of use. When editing a page or a post, the integrated WYSIWYG editor automatically resizes to fit the user\'s screen height.<\\/p>\\n<h3>File manager II<br \\/><\\/h3>\\n<p>Upload any file type. You can categorize your uploads using tags.<\\/p>\\n<p><img src=\\\"\\/wildflower\\/img\\/thumb\\/All_falls_down-1440x900.jpg\\/120\\/120\\/1\\\" alt=\\\"\\\" \\/><img src=\\\"\\/wildflower\\/img\\/thumb\\/Igaer-1400x1050.jpg\\/120\\/120\\/1\\\" alt=\\\"\\\" \\/><img src=\\\"\\/wildflower\\/img\\/thumb\\/All_falls_down-1440x900.jpg\\/120\\/120\\/1\\\" alt=\\\"\\\" \\/><img src=\\\"\\/wildflower\\/img\\/thumb\\/The_night_is_coming-1400x1050.jpg\\/120\\/120\\/1\\\" alt=\\\"\\\" \\/><\\/p>\\n<h3>Revisions<\\/h3>\\n<p>Every change you do to a page or a post is remembered and you can go back to it. No more lost content.<\\/p>\",\"description_meta_tag\":\"\"}}',1,1,'2008-10-08 15:00:07'),(30,'wildpage',158,'{\"WildPage\":{\"title\":\"UI facelift\",\"content\":\"<p>New graphic elements.<\\/p>\",\"description_meta_tag\":\"\"}}',1,1,'2008-10-08 20:49:39'),(31,'wildpost',31,'{\"WildPost\":{\"title\":\"CakePHP 1.2 RC3\"}}',1,0,'2008-10-24 09:05:54'),(32,'wildpost',31,'{\"WildPost\":{\"title\":\"CakePHP 1.2 RC3\",\"content\":\"<p>Uptated to&nbsp;CakePHP 1.2 RC3<\\/p>\",\"description_meta_tag\":\"\"}}',2,0,'2008-10-24 09:08:24'),(33,'wildpage',116,'{\"WildPage\":{\"title\":\"TODO, Enhancements\",\"content\":\"<ul>\\n<li>Add keyboard shortcuts - like alt-A go to index...etc.<br \\/><\\/li>\\n<li>Google sitemaps generation<\\/li>\\n<li>SWFupload<\\/li>\\n<li>Password strength check from Wordpress<\\/li>\\n<li>Shift click on list to select more items<\\/li>\\n<li>Pages list drag and drop<\\/li>\\n<li>JLM compression and caching<\\/li>\\n<li>File manager folders<\\/li>\\n<\\/ul>\\n<p><img src=\\\"\\/wildflower\\/img\\/thumb\\/The_night_is_coming-1400x1050.jpg\\/120\\/120\\/1\\\" alt=\\\"\\\" \\/><\\/p>\",\"description_meta_tag\":\"\"}}',2,0,'2008-10-24 15:56:28'),(34,'wildpage',170,'{\"WildPage\":{\"title\":\"Article 1\",\"content\":\"<p>Lorem ipsum dolor sit amer. 123. \\u010d\\u013e\\u017e+&aacute;\\u013e&scaron;&yacute;&iacute;.11_?<\\/p>\",\"description_meta_tag\":\"\"}}',1,0,'2008-10-24 16:00:52'),(35,'wildpage',170,'{\"WildPage\":{\"title\":\"Contact formular\",\"content\":\"<p>This is a contact form for you.<\\/p>\",\"description_meta_tag\":\"\"}}',2,0,'2008-10-24 16:03:42'),(36,'wildpost',32,'{\"WildPost\":{\"title\":\"999\"}}',1,0,'2008-10-24 16:10:14'),(37,'wildpage',169,'{\"WildPage\":{\"title\":\"Vrann\\u00e9 kone\",\"content\":\"<p>Lorem ipsum. Vrann&eacute; kone.<\\/p>\",\"description_meta_tag\":\"\"}}',1,0,'2008-10-24 16:12:19');
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
INSERT INTO `schema_info` VALUES (9);
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
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `uploads`
--

LOCK TABLES `uploads` WRITE;
/*!40000 ALTER TABLE `uploads` DISABLE KEYS */;
INSERT INTO `uploads` VALUES (7,'Good_Vibrations-1440x900.jpg','Good_Vibrations-1440x900.jpg','image/jpeg','2008-10-10 12:49:23','2008-10-10 12:49:23');
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
INSERT INTO `users` VALUES (1,'admin','24c05ce1409afb5dad4c5bddeb924a4bc3ea00f5','admin@localhost.sk','Mr Admin','4901d6e0-62e8-4fcb-aef2-0261a695e31f','2008-07-11 14:24:43','2008-10-24 16:08:32');
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

-- Dump completed on 2008-10-25  6:24:22
