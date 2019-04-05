ALTER TABLE redirect
    ADD COLUMN rd_query TEXT AFTER rd_path;

UPDATE redirect SET rd_query = '';

ALTER TABLE redirect
    MODIFY COLUMN rd_query TEXT NOT NULL,
    MODIFY COLUMN rd_target_type VARCHAR(31) NOT NULL;
