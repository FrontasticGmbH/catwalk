ALTER TABLE redirect
    ADD COLUMN rd_language VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci AFTER rd_target;
