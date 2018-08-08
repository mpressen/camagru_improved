use camagru_db;
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(11) PRIMARY KEY AUTO_INCREMENT,
  `login` varchar(50) UNIQUE NOT NULL,
  `pwd` varchar(255) NOT NULL,
  `mail` varchar(50) UNIQUE NOT NULL,
  `confirmkey` varchar(15) NOT NULL,
  `confirmation` tinyint(1) DEFAULT '0'
);
CREATE TABLE IF NOT EXISTS `pictures` (
  `picture_id` int(11) PRIMARY KEY AUTO_INCREMENT,
  `user_id` int(11),
  `title` varchar(255),
  `publish` int(1) DEFAULT '1',
  `flip` tinyint(1),
  CONSTRAINT fk_users_to_pictures FOREIGN KEY (user_id) REFERENCES users(user_id)
);
CREATE TABLE IF NOT EXISTS `comments` (
  `comment_id` int(11) PRIMARY KEY AUTO_INCREMENT,
  `user_id` int(11),
  `picture_id` int(11),
  `comments` varchar(500) NOT NULL,
  `timestamp` timestamp DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_users_to_comments FOREIGN KEY (user_id) REFERENCES users(user_id),
  CONSTRAINT fk_pictures_to_comments FOREIGN KEY (picture_id) REFERENCES pictures(picture_id) ON DELETE CASCADE
);
CREATE TABLE IF NOT EXISTS `likes` (
  `likes_id` int(11) PRIMARY KEY AUTO_INCREMENT,
  `user_id` int(11),
  `picture_id` int(11),
  CONSTRAINT fk_users_to_likes FOREIGN KEY (user_id) REFERENCES users(user_id),
  CONSTRAINT fk_pictures_to_likes FOREIGN KEY (picture_id) REFERENCES pictures(picture_id) ON DELETE CASCADE
);