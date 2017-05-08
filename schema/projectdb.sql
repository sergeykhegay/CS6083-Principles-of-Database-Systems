DROP TABLE IF EXISTS users CASCADE;
DROP TABLE IF EXISTS category CASCADE;
DROP TABLE IF EXISTS project CASCADE;
DROP TABLE IF EXISTS creditcard CASCADE;
DROP TABLE IF EXISTS follows CASCADE;
DROP TABLE IF EXISTS likes CASCADE;
DROP TABLE IF EXISTS comment CASCADE;
DROP TABLE IF EXISTS pledge CASCADE;
DROP TABLE IF EXISTS update CASCADE;

-- ##################################### USERS
CREATE TABLE users (
  uid             varchar(128) NOT NULL,
  upasswordhash   varchar(255) NOT NULL, -- http://php.net/manual/en/function.password-hash.php
  uname           varchar(45) NULL,
  uinterests      varchar(512) NULL,
  ucity           varchar(45) NULL,
  PRIMARY KEY(uid)
);


-- ##################################### CATEGORY
CREATE TABLE category ( 
  catname   varchar(16) NOT NULL,
  PRIMARY KEY (catname)
);


-- ##################################### PROJECT
CREATE TABLE project (
  uid              varchar(45) NOT NULL,
  pid              SERIAL NOT NULL,

  catname          varchar(16) NOT NULL,
  ptitle           varchar(45) NOT NULL,
  pdescription     varchar(400) NULL,
  pimage           varchar(255) NULL,

  pstartdate       timestamp DEFAULT current_timestamp,
  pfinishdate      timestamp DEFAULT current_timestamp + interval '30 day',
  pclosedate       timestamp NULL,
  psuccess         boolean DEFAULT FALSE,
  pminamount       int DEFAULT 10 NOT NULL,
  pmaxamount       int DEFAULT 100 NOT NULL,
  pcurrentamount   int DEFAULT 0 NOT NULL,
  pcancelled       boolean DEFAULT FALSE,
  pactive          boolean DEFAULT TRUE,
  PRIMARY KEY (uid, pid),
  FOREIGN KEY (uid) REFERENCES users (uid),
  FOREIGN KEY (catname) REFERENCES category (catname)
);

CREATE UNIQUE INDEX ON project (pid);


-- ##################################### CREDITCARD
CREATE TABLE creditcard (
  uid            varchar(45) NOT NULL,
  ccnumber       varchar(16) NOT NULL,
  ccaddeddate    timestamp DEFAULT current_timestamp,
  ccactive       boolean DEFAULT FALSE,
  PRIMARY KEY (uid, ccnumber),
  FOREIGN KEY (uid) REFERENCES users (uid)
);


-- ##################################### FOLLOWS
CREATE TABLE follows (
  uid1   varchar(45) NOT NULL,
  uid2   varchar(45) NOT NULL CHECK(uid1 != uid2),
  fdate  timestamp DEFAULT current_timestamp,
  PRIMARY KEY (uid1, uid2)
);



-- ##################################### LIKES
CREATE TABLE likes (
  uid        varchar(45) NOT NULL,
  pid        int NOT NULL,
  likedate   timestamp DEFAULT current_timestamp,
  PRIMARY KEY (uid, pid),
  FOREIGN KEY (uid) REFERENCES users (uid),
  FOREIGN KEY (pid) REFERENCES project (pid)
);


-- ##################################### COMMENT
CREATE TABLE comment (
  uid       varchar(45) NOT NULL,
  pid       int NOT NULL,
  comdate   timestamp DEFAULT current_timestamp,
  comtext   varchar(250) NULL,
  PRIMARY KEY (uid, pid, comdate),
  FOREIGN KEY (uid) REFERENCES users (uid),
  FOREIGN KEY (pid) REFERENCES project (pid)
);


CREATE TABLE pledge (
  uid          varchar(45) NOT NULL,
  pid          int NOT NULL,
  ccnumber     varchar(16) NOT NULL,
  plamount     int NULL CHECK(plamount > 0),
  plrating     int NULL CHECK(plrating > 0 AND plrating <= 5),
  pldate       timestamp DEFAULT current_timestamp,
  plcharged    boolean DEFAULT FALSE,
  plcancelled  boolean DEFAULT FALSE,
  PRIMARY KEY (uid, pid),
  FOREIGN KEY (uid) REFERENCES users (uid),
  FOREIGN KEY (pid) REFERENCES project (pid),
  FOREIGN KEY (uid, ccnumber) REFERENCES creditcard (uid, ccnumber)
);

CREATE OR REPLACE FUNCTION update_fund() RETURNS trigger AS
$table$
BEGIN
	WITH p2 as (SELECT project.pcurrentamount
				  FROM project
			     WHERE project.pid = NEW.pid)
	
	UPDATE project
	SET pcurrentamount = (SELECT pcurrentamount FROM p2) + NEW.plamount
	WHERE project.pid = NEW.pid;
	
	IF( (SELECT pcurrentamount FROM project WHERE pid = NEW.pid) >= 
		(SELECT pmaxamount FROM project WHERE pid = NEW.pid) )
	THEN
		UPDATE pledge 
			SET plcharged = 'TRUE' WHERE pid = NEW.pid;
		UPDATE project
			SET psuccess = 'TRUE', pactive = 'FALSE' WHERE pid = NEW.pid;
		UPDATE project
			SET pclosedate = current_timestamp WHERE pid = NEW.pid;
	END IF;
	
	RETURN NEW;
END;
$table$ LANGUAGE plpgsql;

CREATE TRIGGER update_fund AFTER INSERT ON pledge 
FOR EACH ROW EXECUTE PROCEDURE update_fund(); 


-- Prevent pledges to inactive projects
CREATE OR REPLACE FUNCTION insert_pledge() RETURNS trigger AS
$table$
BEGIN
	IF ( (SELECT pactive FROM project WHERE pid = NEW.pid) = FALSE)
	THEN
		RETURN NULL;
	END IF;
	
	RETURN NEW;
END;
$table$ LANGUAGE plpgsql;

CREATE TRIGGER insert_pledge BEFORE INSERT ON pledge 
FOR EACH ROW EXECUTE PROCEDURE insert_pledge(); 


CREATE TABLE update (
  uid             varchar(45) NOT NULL,
  pid             int NOT NULL,
  upddate         timestamp DEFAULT current_timestamp,
  updmedia        varchar(100), -- relative url to a file on the server
  updmediavideo   boolean DEFAULT FALSE, -- video or image
  updtitle        varchar(45),
  upddescription  varchar(400),
  PRIMARY KEY (uid, pid, upddate),
  FOREIGN KEY (uid, pid) REFERENCES project (uid, pid)
);