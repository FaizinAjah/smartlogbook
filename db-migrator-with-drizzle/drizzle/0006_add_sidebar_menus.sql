-- Migration 0006: Add sidebar menus and access permissions for Institusi and Peserta Magang

INSERT INTO `sidebar_menus` (`id`, `parent_id`, `label`, `route_name`, `icon`, `group`, `sort_order`, `is_active`, `created_at`, `updated_at`)
VALUES 
(5, NULL, 'Institusi', 'admin.institutions.index', '_admin._layout.icons.sidebar.institution', 'utama', 4, 1, NOW(), NOW()),
(6, NULL, 'Peserta Magang', 'admin.interns.index', '_admin._layout.icons.sidebar.intern', 'utama', 5, 1, NOW(), NOW())
ON DUPLICATE KEY UPDATE 
`label` = VALUES(`label`),
`route_name` = VALUES(`route_name`),
`icon` = VALUES(`icon`),
`group` = VALUES(`group`),
`sort_order` = VALUES(`sort_order`),
`is_active` = VALUES(`is_active`),
`updated_at` = NOW();

INSERT INTO `sidebar_menu_accesses` (`id`, `sidebar_menu_id`, `access_type`, `created_at`)
VALUES 
(7, 1, 3, NOW()),  -- Pembimbing -> Dashboard (Menu 1)
(8, 3, 3, NOW()),  -- Pembimbing -> Log Book (Menu 3)
(9, 5, 1, NOW()),  -- Super Admin -> Institusi (Menu 5)
(10, 6, 1, NOW()), -- Super Admin -> Peserta Magang (Menu 6)
(11, 6, 3, NOW())  -- Pembimbing -> Peserta Magang (Menu 6)
ON DUPLICATE KEY UPDATE 
`sidebar_menu_id` = VALUES(`sidebar_menu_id`),
`access_type` = VALUES(`access_type`);
