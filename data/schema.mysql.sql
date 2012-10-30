CREATE TABLE `user` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `display_name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `user_social_logins` (
  `user_id` int(11) NOT NULL,
  `service_name` varchar(255) NOT NULL,
  `service_id` varchar(255) DEFAULT NULL,
  `access_token` varchar(255) DEFAULT NULL,
  KEY `social_id` (`user_id`),
  CONSTRAINT `social_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;