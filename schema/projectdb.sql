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

INSERT INTO category (catname) VALUES ('Art');
INSERT INTO category (catname) VALUES ('Comics');
INSERT INTO category (catname) VALUES ('Crafts');
INSERT INTO category (catname) VALUES ('Music');
INSERT INTO category (catname) VALUES ('Theater');
INSERT INTO category (catname) VALUES ('Food');

-- ##################################### PROJECT
CREATE TABLE project (
  uid              varchar(45) NOT NULL,
  pid              SERIAL UNIQUE NOT NULL,

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
  ccid           SERIAL NOT NULL UNIQUE ,
  uid            varchar(45) NOT NULL,
  ccname         varchar(45) NOT NULL,
  ccnumber       varchar(16) NOT NULL,
  ccaddeddate    timestamp DEFAULT current_timestamp,
  ccactive       boolean DEFAULT TRUE,
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
  likeactive boolean DEFAULT TRUE,
  PRIMARY KEY (uid, pid),
  FOREIGN KEY (uid) REFERENCES users (uid),
  FOREIGN KEY (pid) REFERENCES project (pid)
);


-- ##################################### COMMENT
CREATE TABLE comment (
  cid       SERIAL NOT NULL,
  uid       varchar(45) NOT NULL,
  pid       int NOT NULL,
  comdate   timestamp DEFAULT current_timestamp,
  comtext   varchar(250) NULL,
  PRIMARY KEY (uid, pid, comdate),
  FOREIGN KEY (uid) REFERENCES users (uid),
  FOREIGN KEY (pid) REFERENCES project (pid)
);


CREATE TABLE pledge (
  uid            varchar(45) NOT NULL,
  pid            int NOT NULL,
  ccnumber       varchar(16) NOT NULL,

  plamount       int NULL CHECK(plamount > 0),
  plrating       int NULL CHECK(plrating > 0 AND plrating <= 5),  
  pldate         timestamp DEFAULT current_timestamp,
  plcharged      boolean DEFAULT FALSE,
  plchargeddate  timestamp NULL,
  plcancelled    boolean DEFAULT FALSE,
  
  PRIMARY KEY (uid, pid),
  FOREIGN KEY (uid) REFERENCES users (uid),
  FOREIGN KEY (pid) REFERENCES project (pid),
  FOREIGN KEY (uid, ccnumber) REFERENCES creditcard (uid, ccnumber)
);


CREATE OR REPLACE FUNCTION update_project_on_pledge_insert() RETURNS trigger AS
$table$
BEGIN
  IF (NEW.plcancelled = 'TRUE')
  THEN
    RETURN NEW;
  END IF;

  IF( (SELECT psuccess FROM project WHERE pid = NEW.pid) = 'TRUE')
  THEN
    RETURN NULL;
  END IF;

  -- Select current amount pledged to the project
	WITH p2 as (SELECT project.pcurrentamount
				        FROM project
			         WHERE project.pid = NEW.pid)
	
	UPDATE project
	  SET pcurrentamount = (SELECT pcurrentamount FROM p2) + NEW.plamount
	  WHERE project.pid = NEW.pid;
	
	IF ( (SELECT pcurrentamount FROM project WHERE pid = NEW.pid) >= 
		   (SELECT pmaxamount FROM project WHERE pid = NEW.pid) )
	THEN
	
    -- Project successfully funded
    UPDATE project
			SET psuccess = 'TRUE', 
          pactive = 'FALSE',
          pclosedate = current_timestamp
      WHERE pid = NEW.pid;

    -- Charge all pledges
    UPDATE pledge 
      SET plcharged = 'TRUE',
          plchargeddate = current_timestamp
      WHERE pid = NEW.pid;
	END IF;
	
	RETURN NEW;
END;
$table$ LANGUAGE plpgsql;


-- Prevent pledges to inactive projects
CREATE OR REPLACE FUNCTION check_before_pledge_insert() RETURNS trigger AS
$table$
BEGIN
  IF ( (SELECT pactive FROM project WHERE pid = NEW.pid) = 'FALSE')
	THEN
		RETURN NULL;
	END IF;
	
	RETURN NEW;
END;
$table$ LANGUAGE plpgsql;


DROP TRIGGER IF EXISTS insert_pledge ON pledge;
DROP TRIGGER IF EXISTS update_fund ON pledge;

DROP TRIGGER IF EXISTS update_project_on_pledge_insert ON pledge;
CREATE TRIGGER update_project_on_pledge_insert AFTER INSERT OR UPDATE ON pledge 
FOR EACH ROW EXECUTE PROCEDURE update_project_on_pledge_insert(); 

DROP TRIGGER IF EXISTS check_before_pledge_insert ON pledge;
CREATE TRIGGER check_before_pledge_insert BEFORE INSERT ON pledge 
FOR EACH ROW EXECUTE PROCEDURE check_before_pledge_insert();

-- DROP TRIGGER IF EXISTS check_before_pledge_update ON pl;
-- CREATE TRIGGER check_before_pledge_update BEFORE UPDATE ON pledge 
-- FOR EACH ROW EXECUTE PROCEDURE check_before_pledge_insert(); 



-- UPDATES
CREATE TABLE update (
  updid           SERIAL UNIQUE NOT NULL,
  pid             int NOT NULL,
  
  upddate         timestamp DEFAULT current_timestamp,
  updtitle        varchar(45),
  upddescription  varchar(400),
  updmedia        varchar(100), -- relative url to a file on the server
  updmediavideo   boolean DEFAULT FALSE, -- video or image
  PRIMARY KEY (updid),
  FOREIGN KEY (pid) REFERENCES project (pid)
);




-- EVENTS

DROP VIEW events_view;
CREATE OR REPLACE VIEW events_view (action, date, uid, id1, id2, context) AS
  (SELECT text 'follow' AS action,
       fdate AS date,
         uid1 AS uid, 
         uid2 AS id1, 
         null AS id2, 
         null AS context
    FROM follows)
    
  UNION ALL
  
  (SELECT text 'like' AS action, 
       likedate AS date,  
         likes.uid AS uid, 
         likes.pid::varchar AS id1, 
         null AS id2, 
         ptitle AS context
    FROM likes 
         CROSS JOIN project
   WHERE likes.pid = project.pid AND
         likeactive = TRUE)
         
  UNION ALL
  
  (SELECT text 'unlike' AS action, 
       likedate AS date,  
         likes.uid AS uid, 
         likes.pid::varchar AS id1, 
         null AS id2, 
         ptitle AS context
    FROM likes 
         CROSS JOIN project
   WHERE likes.pid = project.pid AND
         likeactive = FALSE)
   
   UNION ALL
   
  (SELECT text 'comment' AS action, 
       comdate AS date, 
         comment.uid AS uid, 
         comment.pid::varchar AS id1, 
         cid::varchar AS id2, 
         ptitle AS context
    FROM comment
         CROSS JOIN project
   WHERE comment.pid = project.pid)
   
   UNION ALL
   
  (SELECT text 'pledge' AS action, 
       pldate AS date,  
         pledge.uid AS uid, 
         pledge.pid::varchar AS id1, 
         null AS id2, 
         ptitle AS context
    FROM pledge
         CROSS JOIN project
   WHERE pledge.pid = project.pid AND
         plcancelled = FALSE)
   
   UNION ALL
   
  (SELECT text 'pledge' AS action, 
       pldate AS date,  
         pledge.uid AS uid, 
         pledge.pid::varchar AS id1, 
         null AS id2, 
         ptitle AS context
    FROM pledge
         CROSS JOIN project
   WHERE pledge.pid = project.pid AND
         plcancelled = TRUE)
    
    UNION ALL
   
  (SELECT text 'update' AS action, 
       upddate AS date, 
         project.uid AS uid, 
         update.pid::varchar AS id1, 
         null AS id2, 
         ptitle AS context
    FROM update
         CROSS JOIN project
   WHERE update.pid = project.pid)
         
    UNION ALL
   
  (SELECT text 'create' AS action, 
       pstartdate AS date,  
         uid AS uid, 
         pid::varchar AS id1, 
         null AS id2, 
         ptitle AS context
    FROM project
   WHERE pcancelled = FALSE)
         
    UNION ALL
   
  (SELECT text 'cancell' AS action, 
       pclosedate AS date,  
         uid AS uid, 
         pid::varchar AS id1, 
         null AS id2, 
         ptitle AS context
    FROM project
   WHERE pcancelled = TRUE)
;
   
   
SELECT * from events_view;
