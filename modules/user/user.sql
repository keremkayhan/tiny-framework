CREATE TABLE  `user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(155) DEFAULT NULL,
  `password` varchar(155) DEFAULT NULL,
  `is_active` tinyint(1) unsigned DEFAULT '0',
  `validate` varchar(45) DEFAULT NULL,
  `remember_me` varchar(100) DEFAULT NULL,
  `credential` varchar(45) DEFAULT NULL,
  `login_count` int(10) unsigned DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;