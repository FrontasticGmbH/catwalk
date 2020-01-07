CREATE TABLE `schema`
(
    s_id          VARCHAR(255) NOT NULL,
    s_schema_type VARCHAR(255) NOT NULL,
    s_schema      LONGTEXT     NOT NULL COMMENT '(DC2Type:object)',
    s_meta_data   LONGTEXT     NOT NULL COMMENT '(DC2Type:object)',
    s_sequence    VARCHAR(255) NOT NULL,
    s_is_deleted  TINYINT(1)   NOT NULL,
    INDEX s_idx_sequence (s_sequence),
    INDEX s_idx_schema_type (s_schema_type, s_is_deleted),
    PRIMARY KEY (s_id)
) DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci
  ENGINE = InnoDB;
