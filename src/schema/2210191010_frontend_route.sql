CREATE TABLE frontend_route (
    fr_id INT NOT NULL AUTO_INCREMENT,
    fr_node_id INT NOT NULL,
    fr_route VARCHAR(255) NOT NULL,
    fr_locale VARCHAR(255) NOT NULL,

    PRIMARY KEY(fr_id)
) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;
