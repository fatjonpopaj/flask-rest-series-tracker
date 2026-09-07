START TRANSACTION;

CREATE DATABASE webtechDBGruppe20;

use webtechDBGruppe20;

CREATE TABLE user (
  userID INTEGER NOT NULL AUTO_INCREMENT, username varchar(128), password varchar(128),
  PRIMARY KEY (userID)
);

INSERT INTO user (userID, username, password) VALUES
(0, 'User1', 'password'),
(1, 'User2', '1234'),
(2, 'Admin', 'adminPass');

CREATE TABLE series (
  seriesID INTEGER NOT NULL AUTO_INCREMENT,
  title varchar(128),
  seasons INTEGER,
  genre varchar(128),
  plattform varchar(128),
  PRIMARY KEY (seriesID)
);

INSERT INTO series (seriesID, title, seasons, genre, plattform) VALUES
(0, 'Star Wars: The Clone Wars', 7, 'Sci-Fi', 'Disney+'),
(1, 'Breaking Bad', 5, 'Drama', 'Netflix');

CREATE TABLE userSeries (
  userID INTEGER, seriesID INTEGER, rating INTEGER,
  PRIMARY KEY (userID, seriesID),
  FOREIGN KEY (userID) REFERENCES user(userID),
  FOREIGN KEY (seriesID) REFERENCES series(seriesID)
);

INSERT INTO userSeries (userID, seriesID, rating) VALUES
(0, 0, 5),
(0, 1, 2),
(1, 1, 5),
(2, 0, 3);

COMMIT;
