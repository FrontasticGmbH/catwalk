ALTER TABLE page
    ADD COLUMN p_nodes_pages_of_type_sort_index BIGINT,
    ADD COLUMN p_schedule_criterion             LONGTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
