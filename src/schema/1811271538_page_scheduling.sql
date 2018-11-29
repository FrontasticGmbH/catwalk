ALTER TABLE page
  ADD COLUMN p_state VARCHAR(16) NOT NULL DEFAULT 'default',
  ADD COLUMN p_scheduled_from_timestamp BIGINT,
  ADD COLUMN p_scheduled_to_timestamp BIGINT;

ALTER TABLE page
  ALTER COLUMN p_state DROP DEFAULT;
