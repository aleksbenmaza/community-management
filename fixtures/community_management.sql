DROP DATABASE IF EXISTS community_management;

CREATE DATABASE community_management;

USE community_management;

CREATE TABLE users(
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  first_name VARCHAR(64),
  last_name VARCHAR(64),
  email_address VARCHAR(64),
  hash CHAR(64),
  type BOOL NOT NULL
);

CREATE TABLE organizers(
  id BIGINT PRIMARY KEY,
  FOREIGN KEY(id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE attendees(
  id BIGINT PRIMARY KEY,
  FOREIGN KEY(id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE meetings(
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  organizer_id BIGINT NOT NULL,
  start_date DATE,
  end_date DATE,
  title VARCHAR(64),
  description VARCHAR(64),
  FOREIGN KEY(organizer_id) REFERENCES organizers(id) ON DELETE CASCADE
);

CREATE TABLE organizers__meetings(
  organizer_id BIGINT NOT NULL,
  meeting_id BIGINT NOT NULL,
  PRIMARY KEY(organizer_id, meeting_id),
  FOREIGN KEY(organizer_id) REFERENCES organizers(id),
  FOREIGN KEY(meeting_id) REFERENCES meetings(id)
);

CREATE TABLE attendees__meetings(
  attendee_id BIGINT NOT NULL,
  meeting_id BIGINT NOT NULL,
  PRIMARY KEY(attendee_id, meeting_id),
  FOREIGN KEY(attendee_id) REFERENCES attendees(id),
  FOREIGN KEY(meeting_id) REFERENCES meetings(id)
);