ALTER TABLE tastic
    CHANGE w_id t_id VARCHAR(255) NOT NULL,
    CHANGE w_sequence t_sequence VARCHAR(255) NOT NULL,
    CHANGE w_tastic_type t_tastic_type VARCHAR(255) NOT NULL,
    CHANGE w_name t_name VARCHAR(255) DEFAULT NULL,
    CHANGE w_description t_description LONGTEXT DEFAULT NULL,
    CHANGE w_configuratiow_schema t_configuration_schema LONGTEXT NOT NULL COMMENT '(DC2Type:object)',
    CHANGE w_environment t_environment VARCHAR(255) DEFAULT NULL,
    CHANGE w_meta_data t_meta_data LONGTEXT NOT NULL COMMENT '(DC2Type:object)',
    CHANGE w_is_deleted t_is_deleted TINYINT(1) NOT NULL;

