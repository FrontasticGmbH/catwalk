 CREATE TABLE custom_data_source (
    c_id VARCHAR(255) NOT NULL,
    c_type VARCHAR(255) NOT NULL,
    c_sequence VARCHAR(255) NOT NULL,
    c_name VARCHAR(255) NOT NULL,
    c_description VARCHAR(255) NOT NULL,
    c_icon VARCHAR(255) NOT NULL,
    c_category VARCHAR(255) NOT NULL,
    c_configuration_schema LONGTEXT NOT NULL COMMENT '(DC2Type:object)',
    c_environment varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
    c_meta_data LONGTEXT NOT NULL COMMENT '(DC2Type:object)',
    c_is_active TINYINT(1) NOT NULL,
    c_is_deleted TINYINT(1) NOT NULL,
    INDEX IDX_948FBA091D79BD3 (c_id),
    INDEX IDX_948FBA0431551E0 (c_sequence),
    PRIMARY KEY(c_id)
) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB;
