CREATE TABLE sitemap (
    sm_id INT NOT NULL AUTO_INCREMENT,
    sm_generation_timestamp INT NOT NULL,
    sm_filename VARCHAR(255) NOT NULL,
    sm_basedir VARCHAR(255) NOT NULL,
    sm_filepath VARCHAR(255) NOT NULL,
    sm_content LONGTEXT NOT NULL,

    INDEX IDX_35179CD281C5EFE (sm_id),
    INDEX IDX_35179CD29FA9BB289EB477C (sm_generation_timestamp, sm_filepath),
    INDEX IDX_35179CD29FA9BB2F73F6E6B (sm_generation_timestamp, sm_basedir),

    PRIMARY KEY(sm_id)
) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;
