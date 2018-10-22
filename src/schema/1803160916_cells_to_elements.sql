UPDATE page SET p_regions = REPLACE(p_regions, 's:5:"cells"', 's:8:"elements"');
UPDATE preview SET pr_page = REPLACE(pr_page, 's:5:"cells"', 's:8:"elements"');
