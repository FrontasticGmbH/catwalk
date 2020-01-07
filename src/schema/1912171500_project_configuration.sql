CREATE TABLE project_configuration
(
    pc_id            VARCHAR(255) NOT NULL,
    pc_configuration LONGTEXT     NOT NULL COMMENT '(DC2Type:object)',
    pc_meta_data     LONGTEXT     NOT NULL COMMENT '(DC2Type:object)',
    pc_sequence      VARCHAR(255) NOT NULL,
    pc_is_deleted    TINYINT(1)   NOT NULL,
    INDEX pc_idx_sequence (pc_sequence),
    PRIMARY KEY (pc_id)
) DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci
  ENGINE = InnoDB;
