CREATE USER 'c_user'@'localhost' IDENTIFIED BY 'Pass!2345' ;
GRANT SELECT,INSERT,UPDATE,DELETE ON c_events.* TO 'c_user'@'localhost' ;
