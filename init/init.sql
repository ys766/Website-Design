/* TODO: create tables */
CREATE TABLE images (
  id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
  image_name TEXT NOT NULL,
  image_ext TEXT NOT NULL,
  user_id INTEGER NOT NULL,
  description TEXT,
  citation TEXT,
  FOREIGN KEY (user_id) REFERENCES accounts(id)
);

CREATE TABLE tags (
  id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
  tag_name TEXT NOT NULL UNIQUE
);

CREATE TABLE image_tag (
  id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
  image_id INTEGER NOT NULL,
  tag_id INTEGER NOT NULL,
  FOREIGN KEY (image_id) REFERENCES images(id),
  FOREIGN KEY (tag_id) REFERENCES tags(id)
);

CREATE TABLE accounts (
  id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
  username TEXT NOT NULL UNIQUE,
  password TEXT NOT NULL,
  realname TEXT NOT NULL,
  session TEXT UNIQUE
);

/* TODO: initial seed data */
INSERT INTO accounts (username, password, realname)
VALUES ("ronald94@yahoo.com", "$2y$10$UbmifY8q9xRiI7/R3jzCQeVfGqy14qra5YJ13KWQKNw4isdsB3h/G", "Jamba Juice"); /* password: abcd1234*/
INSERT INTO accounts (username, password, realname)
VALUES ("ys766", "$2y$10$kVAeWyLFA7hyPaBMD9A3Jux6nsWGZGzr9nx7WOAZ2heNJttvmFBqq", "In N Out"); /* password: I_love_Traveling */
INSERT INTO accounts (username, password, realname)
VALUES ("YuzheSheng", "$2y$10$itiPYmv3S69wb7ju90VRuOaVA7kxGwFMzGPRj/3ZniSxE5d104Tsy", "DeepDish Pizza"); /* password: happycodingMonkey */

INSERT INTO images (image_name, image_ext, description, user_id, citation)
VALUES ("morocco.jpg", "jpg", "Morocco Temple", 1, "http://outgotrip.com/product/colours-of-morocco/");
INSERT INTO images (image_name, image_ext, description, user_id, citation)
VALUES ("Istanbul.jpg", "jpg", "Istanbul, Turkey", 3, "https://handluggageonly.co.uk/2016/02/01/11-experiences-you-will-want-to-try-in-istanbul/");
INSERT INTO images (image_name, image_ext, description, user_id, citation)
VALUES ("Dubai.jpg", "jpg", "The Burj Khalifa in Dubai, United Arab Emirates", 1, "https://www.emiratesholidays.com/gb_en/destination/middle-east/dubai");
INSERT INTO images (image_name, image_ext, description, user_id, citation)
VALUES ("Arcticlight.jpg", "jpg", "Aurora in Northern Norway", 1, "https://www.wanderingeducators.com/best/traveling/arctic-light-aurora-borealis-vesterålen-northern-norway.html");
INSERT INTO images (image_name, image_ext, description, user_id, citation)
VALUES ("antarctica.jpg", "jpg", "Antartica view", 3, "https://wikitravel.org/en/Antarctica");
INSERT INTO images (image_name, image_ext, description, user_id, citation)
VALUES ("AfricaSafari.jpg", "jpg", "Elephant in an African grassland", 2, "https://www.zicasso.com/african-safari");
INSERT INTO images (image_name, image_ext, description, user_id, citation)
VALUES ("capetown.jpg", "jpg", "Coast in Cape Town, South Africa", 3, "https://www.capetownmagazine.com/top-beaches-in-cape-town-and-surrounds");
INSERT INTO images (image_name, image_ext, description, user_id, citation)
VALUES ("Maldivesbeach.jpg", "jpg", "A beach in Maldives", 1, "https://en.wikipedia.org/wiki/Maldives");
INSERT INTO images (image_name, image_ext, description, user_id, citation)
VALUES ("shanghaiBundevening.jpg", "jpg", "The Bund in Shanghai", 1, "https://www.lonelyplanet.com/china/shanghai");
INSERT INTO images (image_name, image_ext, description, user_id, citation)
VALUES ("moroccobuilding.jpg", "jpg", "Morocco", 2, "https://www.gadventures.com/trips/highlights-of-morocco/DCMH/");
INSERT INTO images (image_name, image_ext, description, user_id, citation)
VALUES ("xinjiang.jpg", "jpg", "Xinjiang in China", 2, "http://www.chinadaily.com.cn/opinion/2015-10/27/content_22299021.htm");
INSERT INTO images (image_name, image_ext, description, user_id, citation)
VALUES ("lijiang.jpg", "jpg", "Lijiang, Yunnan Province in China", 3, "https://www.pinterest.com/pin/266908715394586145/");
INSERT INTO images (image_name, image_ext, description, user_id, citation)
VALUES ("tokyotower.jpg", "jpg", "Tokyo Tower in Tokyo, Japan", 1, "https://www.lonelyplanet.com/japan/tokyo/attractions/tokyo-tower/a/poi-sig/396309/356817");
INSERT INTO images (image_name, image_ext, description, user_id, citation)
VALUES ("bigbenlondon.jpg", "jpg", "Big Ben in London, Britain", 2, "https://www.100resilientcities.org/cities/london/");
INSERT INTO images (image_name, image_ext, description, user_id, citation)
VALUES ("statueofLiberty.jpg", "jpg", "The Statue of Liberty in New York City", 2, "https://www.flickr.com/photos/dominiquejames/4621961395/");
INSERT INTO images (image_name, image_ext, description, user_id, citation)
VALUES ("hawaiibeach.jpg", "jpg", "A beach in Hawaii, US", 2, "http://beatofhawaii.com/the-cheapest-time-to-fly-to-hawaii-is-coming-soon/");
INSERT INTO images (image_name, image_ext, description, user_id, citation)
VALUES ("HKVictoria.jpg", "jpg", "The Victoria Harbor in Hong Kong", 2, "http://www.nationsonline.org/oneworld/hong_kong.htm");
INSERT INTO images (image_name, image_ext, description, user_id, citation)
VALUES ("africasavanna.jpg", "jpg", "African tropical savanna", 1, "https://sciencetrends.com/what-tropical-savanna/");
INSERT INTO images (image_name, image_ext, description, user_id, citation)
VALUES ("Shanghaibund.jpg", "jpg", "The Bund in Shanghai", 1, "https://www.chinadiscovery.com/shanghai/the-bund.html");
INSERT INTO images (image_name, image_ext, description, user_id, citation)
VALUES ("NYCEmpire.jpg", "jpg", "New York City Empire State Building", 1, "https://en.wikipedia.org/wiki/Empire_State_Building");
INSERT INTO images (image_name, image_ext, description, user_id, citation)
VALUES ("cherryblossm.jpg", "jpg", "Cherry Blossom", 2, "https://traveler.marriott.com/tokyo/the-best-time-to-view-japan-cherry-blossoms/");
INSERT INTO images (image_name, image_ext, description, user_id, citation)
VALUES ("NYCjenga.jpg", "jpg", "Jenga building in Tribeca, NYC", 1, "http://www.nydailynews.com/life-style/real-estate/tribeca-new-tallest-60-story-tower-rising-jenga-game-article-1.1339903/");
INSERT INTO images (image_name, image_ext, description, user_id, citation)
VALUES ("Eiffeltower.jpg", "jpg", "Eiffel Tower in Paris, France", 2, "https://www.amazon.com/Eiffel-Tower-Dusk-Poster-Print/dp/B000XRNKZM");
INSERT INTO images (image_name, image_ext, description, user_id, citation)
VALUES ("willistower.jpg", "jpg", "The Willis Tower in Chicago, IL, US", 3, "http://peoriapublicradio.org/post/willis-tower-chicago-prepares-500m-face-lift");
/*1*/
INSERT INTO tags (tag_name)
VALUES ("asian");
/*2*/
INSERT INTO tags (tag_name)
VALUES ("china");
/*3*/
INSERT INTO tags(tag_name)
VALUES("nature");
/*4*/
INSERT INTO tags(tag_name)
VALUES("buildings");
/*5*/
INSERT INTO tags(tag_name)
VALUES("us");
/*6*/
INSERT INTO tags(tag_name)
VALUES("africa");
/*7*/
INSERT INTO tags(tag_name)
VALUES("morocco");
/*8*/
INSERT INTO tags(tag_name)
VALUES ("europe");
/*9*/
INSERT INTO tags(tag_name)
VALUES ("animal");
/*10*/
INSERT INTO tags(tag_name)
VALUES ("japan");
/*11*/
INSERT INTO tags(tag_name)
VALUES ("london");
/*12*/
INSERT INTO tags(tag_name)
VALUES ("bridge");

INSERT INTO image_tag(image_id, tag_id)
VALUES (1,4);
INSERT INTO image_tag(image_id, tag_id)
VALUES (1,7);
INSERT INTO image_tag(image_id, tag_id)
VALUES (1,6);
INSERT INTO image_tag(image_id, tag_id)
VALUES (2,1);
INSERT INTO image_tag(image_id, tag_id)
VALUES (3,4);
INSERT INTO image_tag(image_id, tag_id)
VALUES (4,3);
INSERT INTO image_tag(image_id, tag_id)
VALUES (4,8);
INSERT INTO image_tag(image_id, tag_id)
VALUES (5,3);
INSERT INTO image_tag(image_id, tag_id)
VALUES (6,3);
INSERT INTO image_tag(image_id, tag_id)
VALUES (6,6);
INSERT INTO image_tag(image_id, tag_id)
VALUES (6,9);
INSERT INTO image_tag(image_id, tag_id)
VALUES (7,3);
INSERT INTO image_tag(image_id, tag_id)
VALUES (7,6);
INSERT INTO image_tag(image_id, tag_id)
VALUES (8,3);
INSERT INTO image_tag(image_id, tag_id)
VALUES (9,1);
INSERT INTO image_tag(image_id, tag_id)
VALUES (9,2);
INSERT INTO image_tag(image_id, tag_id)
VALUES (9,4);
INSERT INTO image_tag(image_id, tag_id)
VALUES (10,4);
INSERT INTO image_tag(image_id, tag_id)
VALUES (10,7);
INSERT INTO image_tag(image_id, tag_id)
VALUES (10,6);
INSERT INTO image_tag(image_id, tag_id)
VALUES (11,1);
INSERT INTO image_tag(image_id, tag_id)
VALUES (11,2);
INSERT INTO image_tag(image_id, tag_id)
VALUES (11,3);
INSERT INTO image_tag(image_id, tag_id)
VALUES (12,1);
INSERT INTO image_tag(image_id, tag_id)
VALUES (12,2);
INSERT INTO image_tag(image_id, tag_id)
VALUES (12,4);
INSERT INTO image_tag(image_id, tag_id)
VALUES (12,12);
INSERT INTO image_tag(image_id, tag_id)
VALUES (13,1);
INSERT INTO image_tag(image_id, tag_id)
VALUES (13,4);
INSERT INTO image_tag(image_id, tag_id)
VALUES (13,10);
INSERT INTO image_tag(image_id, tag_id)
VALUES (14,4);
INSERT INTO image_tag(image_id, tag_id)
VALUES (14,8);
INSERT INTO image_tag(image_id, tag_id)
VALUES (14,11);
INSERT INTO image_tag(image_id, tag_id)
VALUES (15,4);
INSERT INTO image_tag(image_id, tag_id)
VALUES (15,5);
INSERT INTO image_tag(image_id, tag_id)
VALUES (16,3);
