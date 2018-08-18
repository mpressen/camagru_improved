use camagru_db;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) PRIMARY KEY AUTO_INCREMENT,
  `login` varchar(50) UNIQUE NOT NULL,
  `pwd` varchar(255) NOT NULL,
  `mail` varchar(50) UNIQUE NOT NULL,
  `confirmkey` varchar(255) NOT NULL,
  `confirmation` tinyint(1) DEFAULT '0'
);
CREATE TABLE IF NOT EXISTS `pictures` (
  `id` int(11) PRIMARY KEY AUTO_INCREMENT,
  `user_id` int(11),
  `title` varchar(255),
  `publish` int(1) DEFAULT '1',
  `flip` tinyint(1),
  CONSTRAINT fk_users_to_pictures FOREIGN KEY (id) REFERENCES users(id)
);
CREATE TABLE IF NOT EXISTS `comments` (
  `id` int(11) PRIMARY KEY AUTO_INCREMENT,
  `user_id` int(11),
  `picture_id` int(11),
  `comments` varchar(500) NOT NULL,
  `timestamp` timestamp DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_users_to_comments FOREIGN KEY (id) REFERENCES users(id),
  CONSTRAINT fk_pictures_to_comments FOREIGN KEY (id) REFERENCES pictures(id) ON DELETE CASCADE
);
CREATE TABLE IF NOT EXISTS `likes` (
  `id` int(11) PRIMARY KEY AUTO_INCREMENT,
  `user_id` int(11),
  `picture_id` int(11),
  CONSTRAINT fk_users_to_likes FOREIGN KEY (id) REFERENCES users(id),
  CONSTRAINT fk_pictures_to_likes FOREIGN KEY (id) REFERENCES pictures(id) ON DELETE CASCADE
);