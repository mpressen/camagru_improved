use camagru_db;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) PRIMARY KEY AUTO_INCREMENT,
  `login` varchar(50) UNIQUE NOT NULL,
  `pwd` varchar(255) NOT NULL,
  `mail` varchar(50) UNIQUE NOT NULL,
  `confirmkey` varchar(255) NOT NULL,
  `confirmation` tinyint(1) DEFAULT '0',
  `current_session_id` varchar(255) NOT NULL
);
CREATE TABLE IF NOT EXISTS `pictures` (
  `id` int(11) PRIMARY KEY AUTO_INCREMENT,
  `user_id` int(11),
  `path` varchar(255),
  CONSTRAINT fk_users_to_pictures FOREIGN KEY (user_id) REFERENCES users(id)
);
CREATE TABLE IF NOT EXISTS `comments` (
  `id` int(11) PRIMARY KEY AUTO_INCREMENT,
  `user_id` int(11),
  `picture_id` int(11),
  `comment` varchar(500) NOT NULL,
  `timestamp` timestamp DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_users_to_comments FOREIGN KEY (user_id) REFERENCES users(id),
  CONSTRAINT fk_pictures_to_comments FOREIGN KEY (picture_id) REFERENCES pictures(id) ON DELETE CASCADE
);
CREATE TABLE IF NOT EXISTS `likes` (
  `id` int(11) PRIMARY KEY AUTO_INCREMENT,
  `user_id` int(11),
  `picture_id` int(11),
  CONSTRAINT fk_users_to_likes FOREIGN KEY (user_id) REFERENCES users(id),
  CONSTRAINT fk_pictures_to_likes FOREIGN KEY (picture_id) REFERENCES pictures(id) ON DELETE CASCADE
);