ALTER DATABASE CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

ALTER TABLE `app`
    CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    CHANGE `a_id` `a_id`                                     varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
    CHANGE `a_sequence` `a_sequence`                         varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
    CHANGE `a_identifier` `a_identifier`                     varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
    CHANGE `a_name` `a_name`                                 varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
    CHANGE `a_environment` `a_environment`                   varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
    CHANGE `a_description` `a_description`                   longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci     NULL,
    CHANGE `a_configuratioa_schema` `a_configuratioa_schema` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci     NOT NULL,
    CHANGE `a_meta_data` `a_meta_data`                       longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci     NOT NULL;

ALTER TABLE `facet`
    CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    CHANGE `f_id` `f_id`                         varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
    CHANGE `f_sequence` `f_sequence`             varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
    CHANGE `f_attribute_id` `f_attribute_id`     varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
    CHANGE `f_attribute_type` `f_attribute_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
    CHANGE `f_url_identifier` `f_url_identifier` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
    CHANGE `f_label` `f_label`                   longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci     NULL,
    CHANGE `f_facet_options` `f_facet_options`   longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci     NULL,
    CHANGE `f_meta_data` `f_meta_data`           longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci     NOT NULL;

ALTER TABLE `http_session`
    CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    CHANGE `sess_id` `sess_id` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;

ALTER TABLE `layout`
    CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    CHANGE `l_id` `l_id`               varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
    CHANGE `l_sequence` `l_sequence`   varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
    CHANGE `l_name` `l_name`           varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
    CHANGE `l_regions` `l_regions`     longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci     NOT NULL,
    CHANGE `l_meta_data` `l_meta_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci     NOT NULL;

ALTER TABLE `node`
    CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    CHANGE `n_id` `n_id`                       varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
    CHANGE `n_sequence` `n_sequence`           varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
    CHANGE `n_name` `n_name`                   varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
    CHANGE `n_path` `n_path`                   varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
    CHANGE `n_configuration` `n_configuration` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci     NOT NULL,
    CHANGE `n_meta_data` `n_meta_data`         longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci     NOT NULL,
    CHANGE `n_streams` `n_streams`             longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci     NOT NULL;

ALTER TABLE `page`
    CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    CHANGE `p_id` `p_id`               varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
    CHANGE `p_sequence` `p_sequence`   varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
    CHANGE `l_id` `l_id`               varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
    CHANGE `p_node` `p_node`           varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
    CHANGE `p_state` `p_state`         varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci  NOT NULL,
    CHANGE `p_regions` `p_regions`     longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci     NOT NULL,
    CHANGE `p_meta_data` `p_meta_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci     NOT NULL;

ALTER TABLE `page_matcher_rules`
    CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    CHANGE `pmr_id` `pmr_id`               varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
    CHANGE `pmr_sequence` `pmr_sequence`   varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
    CHANGE `pmr_rules` `pmr_rules`         longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci     NOT NULL,
    CHANGE `pmr_meta_data` `pmr_meta_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci     NOT NULL;

ALTER TABLE `preview`
    CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    CHANGE `pr_id` `pr_id`               varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
    CHANGE `pr_node` `pr_node`           longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci     NOT NULL,
    CHANGE `pr_page` `pr_page`           longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci     NOT NULL,
    CHANGE `pr_meta_data` `pr_meta_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci     NOT NULL;

ALTER TABLE `redirect`
    CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    CHANGE `rd_id` `rd_id`                   varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
    CHANGE `rd_sequence` `rd_sequence`       varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
    CHANGE `rd_target_type` `rd_target_type` varchar(31) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci  NOT NULL,
    CHANGE `rd_path` `rd_path`               text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci         NOT NULL,
    CHANGE `rd_query` `rd_query`             text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci         NULL,
    CHANGE `rd_target` `rd_target`           text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci         NOT NULL,
    CHANGE `rd_meta_data` `rd_meta_data`     longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci     NOT NULL;

ALTER TABLE `tastic`
    CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    CHANGE `t_id` `t_id`                                     varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
    CHANGE `t_sequence` `t_sequence`                         varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
    CHANGE `t_tastic_type` `t_tastic_type`                   varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
    CHANGE `t_name` `t_name`                                 varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
    CHANGE `t_environment` `t_environment`                   varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
    CHANGE `t_description` `t_description`                   longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci     NULL,
    CHANGE `t_configuration_schema` `t_configuration_schema` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci     NOT NULL,
    CHANGE `t_meta_data` `t_meta_data`                       longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci     NOT NULL;
