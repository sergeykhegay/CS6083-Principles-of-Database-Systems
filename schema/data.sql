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

INSERT INTO category (catname) VALUES ('Art');
INSERT INTO category (catname) VALUES ('Comics');
INSERT INTO category (catname) VALUES ('Crafts');
INSERT INTO category (catname) VALUES ('Music');
INSERT INTO category (catname) VALUES ('Theater');
INSERT INTO category (catname) VALUES ('Food');


-- ##################################### PROJECT

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

INSERT INTO creditcard(uid, ccnumber, ccactive) VALUES ('justine@justine.com', '12345678901122', TRUE);
INSERT INTO creditcard(uid, ccnumber, ccactive) VALUES ('sergey@sergey.com', '00000222010233', TRUE);
INSERT INTO creditcard(uid, ccnumber, ccactive) VALUES ('rovan@rovan.me', '12000322010213', TRUE);
INSERT INTO creditcard(uid, ccnumber, ccactive) VALUES ('bob@gmail.com', '_bobcard_', TRUE);


-- ##################################### FOLLOWS

Insert into follows(uid1, uid2) values('bob@gmail.com', 'justine@justine.com');
Insert into follows(uid1, uid2) values('bob@gmail.com', 'sergey@sergey.com');
Insert into follows(uid1, uid2) values('justine@justine.com', 'sergey@sergey.com');
Insert into follows(uid1, uid2) values('justine@justine.com', 'bob@bob.com');


-- ##################################### LIKES

INSERT INTO likes(uid, pid) values('justine@justine.com', 1);
INSERT INTO likes(uid, pid) values('justine@justine.com', 2);
INSERT INTO likes(uid, pid) values('rovan@rovan.me', 3);
INSERT INTO likes(uid, pid) values('sergey@sergey.com', 4);


-- ##################################### COMMENT


INSERT INTO comment(uid, pid, comtext) VALUES('justine@justine.com', 2, 'cool!');
INSERT INTO comment(uid, pid, comtext) VALUES('justine@justine.com', 4, 'nice!');
INSERT INTO comment(uid, pid, comtext) VALUES('sergey@sergey.com', 4, 'awesome!');


-- ##################################### PLEDGE

INSERT INTO pledge (uid, pid, ccnumber, plamount) VALUES('sergey@sergey.com', 3, '00000222010233', 10);
INSERT INTO pledge (uid, pid, ccnumber, plamount) VALUES('justine@justine.com', 3, '12345678901122', 40);
INSERT INTO pledge (uid, pid, ccnumber, plamount) VALUES('rovan@rovan.me', 3, '12000322010213', 100);


INSERT INTO pledge (uid, pid, ccnumber, plamount, plrating) VALUES('rovan@rovan.me', 6, '12000322010213', 250, 5);
INSERT INTO pledge (uid, pid, ccnumber, plamount, plrating) VALUES('rovan@rovan.me', 7, '12000322010213', 250, 5);
INSERT INTO pledge (uid, pid, ccnumber, plamount, plrating) VALUES('rovan@rovan.me', 8, '12000322010213', 250, 5);


-- ##################################### UPDATE




