
CREATE TABLE user (

   id INT NOT NULL AUTO_INCREMENT,
   nick VARCHAR(20) NOT NULL,
   summoner_name VARCHAR(40) NOT NULL,
   summoner_id INT NOT NULL,
   server VARCHAR(20) NOT NULL,
   submission_date DATE,
   PRIMARY KEY ( id )

);

CREATE TABLE friends (

	idUser INT NOT NULL,
	summonerID INT NOT NULL,
	PRIMARY KEY (idUser,summonerID),
	FOREIGN KEY (idUser) REFERENCES user(id)

);
