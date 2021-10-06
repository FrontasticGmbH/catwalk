CREATE TABLE sitemap (
    sm_id INT NOT NULL,
    sm_generation_timestamp INT NOT NULL,
    sm_filename VARCHAR(255) NOT NULL,
    sm_content LONGTEXT NOT NULL,

    INDEX IDX_35179CD281C5EFE (sm_id),
    INDEX IDX_35179CD29FA9BB2DC9CB275 (sm_generation_timestamp, sm_filename),

    PRIMARY KEY(sm_id)
) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;
