CREATE TABLE redirect
(
    rd_id          VARCHAR(255)          NOT NULL,
    rd_sequence    VARCHAR(255)          NOT NULL,
    rd_path        TEXT                  NOT NULL,
    rd_target_type ENUM ('node', 'link') NOT NULL,
    rd_target      TEXT                  NOT NULL,
    rd_meta_data   LONGTEXT              NOT NULL COMMENT '(DC2Type:object)',
    rd_is_deleted  TINYINT(1)            NOT NULL,
    INDEX rd_idx_sequence (rd_sequence),
    INDEX rd_idx_path (rd_path(255)),
    PRIMARY KEY (rd_id)
) DEFAULT CHARACTER SET UTF8
  COLLATE UTF8_unicode_ci
  ENGINE = InnoDB;
