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
  uid          varchar(45) NOT NULL,
  upassword    varchar(45) NOT NULL,
  uname        varchar(45) NULL,
  uinterests   varchar(45) NULL,
  ucity        varchar(45) NULL,
  PRIMARY KEY(uid)
);

INSERT INTO users (uid, upassword, uname, uinterests)
  VALUES ('sergey@sergey.com', 'sergey', 'Sergey Khegay', 'Sleeping!');
INSERT INTO users (uid, upassword, uname, uinterests)
  VALUES ('justine@justine.com', 'justine', 'Justine Tsai', 'Eating');
INSERT INTO users (uid, upassword, uname, uinterests)
  VALUES ('rovan@rovan.me', 'rovan', 'Rovan', 'Baseball! Go Mets, go!');
INSERT INTO users (uid, upassword, uname, uinterests)
  VALUES ('brandon@gmail.com', 'brandon', 'Brandon Troy', 'Whatever');
INSERT INTO users (uid, upassword, uname, uinterests)
  VALUES ('bob@gmail.com', 'bob', 'BobInBrooklyn', 'Whatever');
INSERT INTO users (uid, upassword, uname, uinterests)
  VALUES ('best@gmail.com', 'best', 'Best Guy', 'Whatever');



-- ##################################### CATEGORY
CREATE TABLE category ( 
  catname   varchar(16) NOT NULL,
  PRIMARY KEY (catname)
);

INSERT INTO category (catname) VALUES ('Art');
INSERT INTO category (catname) VALUES ('Comics');
INSERT INTO category (catname) VALUES ('Crafts');
INSERT INTO category (catname) VALUES ('Music');
INSERT INTO category (catname) VALUES ('Theater');
INSERT INTO category (catname) VALUES ('Food');


-- ##################################### PROJECT
CREATE TABLE project (
  uid              varchar(45) NOT NULL,
  pid              SERIAL NOT NULL,

  catname          varchar(16) NOT NULL,
  ptitle           varchar(45) NOT NULL,
  pdescription     varchar(400) NULL,
  pimage           varchar(120) NULL,

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

INSERT INTO project (pid, uid, catname, ptitle, pdescription)
  VALUES (0, 'sergey@sergey.com', 'Food', 'Automatic rice cooker!', 'This is a super cool project!');
INSERT INTO project (pid, uid, catname, ptitle, pdescription)
  VALUES (1, 'sergey@sergey.com', 'Food', 'Automatic rice eater!', 'This is a super cool project2!');
INSERT INTO project (pid, uid, catname, ptitle, pdescription)
  VALUES (2, 'sergey@sergey.com', 'Food', 'Automatic dish washer!', 'For your significant ones');
INSERT INTO project (pid, uid, catname, ptitle, pdescription)
  VALUES (3, 'justine@justine.com', 'Music', 'Jazz album release', 'This is a super cool project!'); 
INSERT INTO project (pid, uid, catname, ptitle, pdescription)
  VALUES (4, 'justine@justine.com', 'Music', 'Band!', 'This is a super cool jazz band!');
INSERT INTO project (pid, uid, catname, ptitle, pdescription)
  VALUES (5, 'justine@justine.com', 'Music', 'Jazz Band!', 'Completed Jazz Project!');
  

INSERT INTO project (pid, uid, catname, ptitle, pdescription)
  VALUES (6, 'best@gmail.com', 'Music', 'Project1', 'Project 1 desc');
INSERT INTO project (pid, uid, catname, ptitle, pdescription)
  VALUES (7, 'best@gmail.com', 'Music', 'Project2', 'Project 2 desc');
INSERT INTO project (pid, uid, catname, ptitle, pdescription)
  VALUES (8, 'best@gmail.com', 'Music', 'Project3', 'Project 3 desc');

-- ##################################### CREDITCARD
CREATE TABLE creditcard (
  uid            varchar(45) NOT NULL,
  ccnumber       varchar(16) NOT NULL,
  ccaddeddate    timestamp DEFAULT current_timestamp,
  ccactive       boolean DEFAULT FALSE,
  PRIMARY KEY (uid, ccnumber),
  FOREIGN KEY (uid) REFERENCES users (uid)
);

INSERT INTO creditcard(uid, ccnumber, ccactive) VALUES ('justine@justine.com', '12345678901122', TRUE);
INSERT INTO creditcard(uid, ccnumber, ccactive) VALUES ('sergey@sergey.com', '00000222010233', TRUE);
INSERT INTO creditcard(uid, ccnumber, ccactive) VALUES ('rovan@rovan.me', '12000322010213', TRUE);
INSERT INTO creditcard(uid, ccnumber, ccactive) VALUES ('bob@gmail.com', '_bobcard_', TRUE);

-- ##################################### FOLLOWS
CREATE TABLE follows (
  uid1   varchar(45) NOT NULL,
  uid2   varchar(45) NOT NULL CHECK(uid1 != uid2),
  fdate  timestamp DEFAULT current_timestamp,
  PRIMARY KEY (uid1, uid2)
);

Insert into follows(uid1, uid2) values('bob@gmail.com', 'justine@justine.com');
Insert into follows(uid1, uid2) values('bob@gmail.com', 'sergey@sergey.com');
Insert into follows(uid1, uid2) values('justine@justine.com', 'sergey@sergey.com');
Insert into follows(uid1, uid2) values('justine@justine.com', 'bob@bob.com');

-- ##################################### LIKES
CREATE TABLE likes (
  uid        varchar(45) NOT NULL,
  pid        int NOT NULL,
  likedate   timestamp DEFAULT current_timestamp,
  PRIMARY KEY (uid, pid),
  FOREIGN KEY (uid) REFERENCES users (uid),
  FOREIGN KEY (pid) REFERENCES project (pid)
);

INSERT INTO likes(uid, pid) values('justine@justine.com', 1);
INSERT INTO likes(uid, pid) values('justine@justine.com', 2);
INSERT INTO likes(uid, pid) values('rovan@rovan.me', 3);
INSERT INTO likes(uid, pid) values('sergey@sergey.com', 4);

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

INSERT INTO comment(uid, pid, comtext) VALUES('justine@justine.com', 2, 'cool!');
INSERT INTO comment(uid, pid, comtext) VALUES('justine@justine.com', 4, 'nice!');
INSERT INTO comment(uid, pid, comtext) VALUES('sergey@sergey.com', 4, 'awesome!');

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


INSERT INTO pledge (uid, pid, ccnumber, plamount) VALUES('sergey@sergey.com', 3, '00000222010233', 10);
INSERT INTO pledge (uid, pid, ccnumber, plamount) VALUES('justine@justine.com', 3, '12345678901122', 40);
INSERT INTO pledge (uid, pid, ccnumber, plamount) VALUES('rovan@rovan.me', 3, '12000322010213', 100);


INSERT INTO pledge (uid, pid, ccnumber, plamount, plrating) VALUES('rovan@rovan.me', 6, '12000322010213', 250, 5);
INSERT INTO pledge (uid, pid, ccnumber, plamount, plrating) VALUES('rovan@rovan.me', 7, '12000322010213', 250, 5);
INSERT INTO pledge (uid, pid, ccnumber, plamount, plrating) VALUES('rovan@rovan.me', 8, '12000322010213', 250, 5);


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

