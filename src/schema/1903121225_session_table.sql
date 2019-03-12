/* NOTE: Different from our default column naming schema, but I will keep the Symfony standard names here */
CREATE TABLE `http_session` (
  `sess_id` VARCHAR(128) NOT NULL PRIMARY KEY,
  `sess_data` BLOB NOT NULL,
  `sess_time` INTEGER UNSIGNED NOT NULL,
  `sess_lifetime` MEDIUMINT NOT NULL
) COLLATE utf8_bin, ENGINE = InnoDB;
