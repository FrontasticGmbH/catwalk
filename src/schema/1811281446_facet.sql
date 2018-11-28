 CREATE TABLE facet (
    f_id VARCHAR(255) NOT NULL,
    f_sequence VARCHAR(255) NOT NULL,
    f_attribute_id VARCHAR(255) NOT NULL,
    f_attribute_type VARCHAR(255) NOT NULL,
    f_sort INT NOT NULL,
    f_is_enabled TINYINT(1) NOT NULL,
    f_label LONGTEXT DEFAULT NULL COMMENT '(DC2Type:object)',
    f_display_type VARCHAR(255) DEFAULT NULL,
    f_url_identifier VARCHAR(255) DEFAULT NULL,
    f_facet_options LONGTEXT DEFAULT NULL COMMENT '(DC2Type:object)',
    f_meta_data LONGTEXT NOT NULL COMMENT '(DC2Type:object)',
    f_is_deleted TINYINT(1) NOT NULL,
    INDEX IDX_56B9BA28A6096BE1 (f_id),
    INDEX IDX_56B9BA28A53C9AA4 (f_sequence),
    PRIMARY KEY(f_id)
) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB;
