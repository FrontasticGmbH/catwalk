ALTER TABLE http_session MODIFY sess_lifetime int(10);
CREATE INDEX `EXPIRY` ON http_session (`sess_lifetime`);
