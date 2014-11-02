to do list
=============

Overview:
Custom MVC framework plus simple to do list

Tutorial used: 
http://anantgarg.com/2009/03/13/write-your-own-php-mvc-framework-part-1/

Installation
=============
CREATE TABLE `items` (
  `id` int(11) NOT NULL auto_increment,
  `item_name` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
);
 
INSERT INTO `items` VALUES(1, 'Get Milk');
INSERT INTO `items` VALUES(2, 'Buy Application');
