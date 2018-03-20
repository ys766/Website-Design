/* TODO: create tables */
CREATE TABLE `images` (
  `id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
  `image_name` TEXT NOT NULL,
  `description` TEXT,
  `user_id` INTEGER NOT NULL
);

CREATE TABLE `tags` (
  `id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
  `tag_name` TEXT NOT NULL
);

CREATE TABLE `image_tag` (
  `image_id` INTEGER NOT NULL,
  `tag_id` INTEGER NOT NULL
);

CREATE TABLE `accounts` (
  `id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
  `username` TEXT NOT NULL UNIQUE,
  `password` TEXT NOT NULL,
  `session` TEXT,
  `realname` TEXT, NOT NULL
);
/* TODO: initial seed data */
INSERT INTO accounts (username, password, realname)
VALUES ("ronald94@yahoo.com", "harrysheng0100", "Ronald Sheng");
INSERT INTO accounts (username, password, realname)
VALUES ("ys766", "I_love_Traveling", "Kevin G.");

INSERT INTO images (image_name, description, user_id)
VALUES ("Shanghai_1.jpg", "The Bund", 1);

INSERT INTO tags (tag_name)
VALUES ("Asian");
INSERT INTO tags (tag_name)
VALUES ("China");

INSERT INTO image_tag(image_id, tag_id)
VALUES (1,1);
INSERT INTO image_tag(image_id, tag_id)
VALUES (1,2);
