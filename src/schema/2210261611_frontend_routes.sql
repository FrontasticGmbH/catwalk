DROP TABLE frontend_routes;

CREATE TABLE frontend_routes (
    fr_id INT NOT NULL,
    fr_frontend_routes LONGTEXT NOT NULL,

    PRIMARY KEY(fr_id)
) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;
