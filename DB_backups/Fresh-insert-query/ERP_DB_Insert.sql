/* Sub_module_menus:
Note: Replace with your DB Name
*/
USE erp_subscription1;
INSERT INTO `sub_module_menus` (`id`, `parent_id`, `module_name`, `icon`, `url`, `module_type`, `unique_code`, `sequence_order`, `description`, `status`, `deleted_at`, `created_at`, `updated_at`)
VALUES('1',NULL,'Dashboard',NULL,NULL,'Menu','dashboard','1',NULL,'Active',NULL,NULL,NULL);
INSERT INTO `sub_module_menus` (`id`, `parent_id`, `module_name`, `icon`, `url`, `module_type`, `unique_code`, `sequence_order`, `description`, `status`, `deleted_at`, `created_at`, `updated_at`)
VALUES('2',NULL,'Manage Users',NULL,NULL,'Menu','manage_users','1',NULL,'Active',NULL,NULL,NULL);
INSERT INTO `sub_module_menus` (`id`, `parent_id`, `module_name`, `icon`, `url`, `module_type`, `unique_code`, `sequence_order`, `description`, `status`, `deleted_at`, `created_at`, `updated_at`)
VALUES('3','2','Users',NULL,NULL,'SubMenu','users','1',NULL,'Active',NULL,NULL,NULL);
INSERT INTO `sub_module_menus` (`id`, `parent_id`, `module_name`, `icon`, `url`, `module_type`, `unique_code`, `sequence_order`, `description`, `status`, `deleted_at`, `created_at`, `updated_at`)
VALUES('4',NULL,'Roles',NULL,NULL,'Menu','roles','1',NULL,'Active',NULL,NULL,NULL);

/* Sub_user_roles: */
INSERT  INTO `sub_user_roles`(`id`,`client_id`,`role_name`,`role_unique_code`,`web_access`,`mobile_access`,`primary_access`,`status`,`created_by`,`updated_by`,`deleted_at`,`created_at`,`updated_at`)
VALUES (1,NULL,'Super Admin','super_admin','Yes','Yes','No','Active',NULL,NULL,NULL,NULL,NULL);

INSERT  INTO `sub_user_roles`(`id`,`client_id`,`role_name`,`role_unique_code`,`web_access`,`mobile_access`,`primary_access`,`status`,`created_by`,`updated_by`,`deleted_at`,`created_at`,`updated_at`)
VALUES (2,NULL,'Client','client','Yes','Yes','Yes','Active',NULL,NULL,NULL,NULL,NULL);

INSERT  INTO `sub_user_roles`(`id`,`client_id`,`role_name`,`role_unique_code`,`web_access`,`mobile_access`,`primary_access`,`status`,`created_by`,`updated_by`,`deleted_at`,`created_at`,`updated_at`)
VALUES (3,NULL,'Admin','admin','Yes','Yes','No','Active',NULL,NULL,NULL,NULL,NULL);

INSERT  INTO `sub_user_roles`(`id`,`client_id`,`role_name`,`role_unique_code`,`web_access`,`mobile_access`,`primary_access`,`status`,`created_by`,`updated_by`,`deleted_at`,`created_at`,`updated_at`)
VALUES (4,NULL,'Supervisor','supervisor','Yes','Yes','No','Active',NULL,NULL,NULL,NULL,NULL);


/* Sub_user_rights: */
INSERT INTO `sub_user_rights` (`client_id`, `role_id`, `menu_id`, `can_add`, `can_delete`, `can_update`, `can_view`, `status`, `created_by`, `updated_by`, `deleted_at`, `created_at`, `updated_at`)
VALUES ('0', '2', '1', 'Yes', 'Yes', 'Yes', 'Yes', 'Active', '0', NULL, NULL, NULL, NULL);

INSERT INTO `sub_user_rights` (`client_id`, `role_id`, `menu_id`, `can_add`, `can_delete`, `can_update`, `can_view`, `status`, `created_by`, `updated_by`, `deleted_at`, `created_at`, `updated_at`)
VALUES ('0', '2', '2', 'Yes', 'Yes', 'Yes', 'Yes', 'Active', '0', NULL, NULL, NULL, NULL);

INSERT INTO `sub_user_rights` (`client_id`, `role_id`, `menu_id`, `can_add`, `can_delete`, `can_update`, `can_view`, `status`, `created_by`, `updated_by`, `deleted_at`, `created_at`, `updated_at`)
VALUES ('0', '2', '3', 'Yes', 'Yes', 'Yes', 'Yes', 'Active', '0', NULL, NULL, NULL, NULL);

INSERT INTO `sub_user_rights` (`client_id`, `role_id`, `menu_id`, `can_add`, `can_delete`, `can_update`, `can_view`, `status`, `created_by`, `updated_by`, `deleted_at`, `created_at`, `updated_at`)
VALUES ('0', '2', '4', 'Yes', 'Yes', 'Yes', 'Yes', 'Active', '0', NULL, NULL, NULL, NULL);

INSERT INTO `sub_user_rights` (`client_id`, `role_id`, `menu_id`, `can_add`, `can_delete`, `can_update`, `can_view`, `status`, `created_by`, `updated_by`, `deleted_at`, `created_at`, `updated_at`)
VALUES ('0', '3', '1', 'Yes', 'Yes', 'Yes', 'Yes', 'Active', '0', NULL, NULL, NULL, NULL);

INSERT INTO `sub_user_rights` (`client_id`, `role_id`, `menu_id`, `can_add`, `can_delete`, `can_update`, `can_view`, `status`, `created_by`, `updated_by`, `deleted_at`, `created_at`, `updated_at`)
VALUES ('0', '3', '2', 'Yes', 'Yes', 'Yes', 'Yes', 'Active', '0', NULL, NULL, NULL, NULL);

INSERT INTO `sub_user_rights` (`client_id`, `role_id`, `menu_id`, `can_add`, `can_delete`, `can_update`, `can_view`, `status`, `created_by`, `updated_by`, `deleted_at`, `created_at`, `updated_at`)
VALUES ('0', '3', '3', 'Yes', 'Yes', 'Yes', 'Yes', 'Active', '0', NULL, NULL, NULL, NULL);

INSERT INTO `sub_user_rights` (`client_id`, `role_id`, `menu_id`, `can_add`, `can_delete`, `can_update`, `can_view`, `status`, `created_by`, `updated_by`, `deleted_at`, `created_at`, `updated_at`)
VALUES ('0', '3', '4', 'Yes', 'Yes', 'Yes', 'Yes', 'Active', '0', NULL, NULL, NULL, NULL);

INSERT INTO `sub_user_rights` (`client_id`, `role_id`, `menu_id`, `can_add`, `can_delete`, `can_update`, `can_view`, `status`, `created_by`, `updated_by`, `deleted_at`, `created_at`, `updated_at`)
VALUES ('0', '4', '1', 'Yes', 'Yes', 'Yes', 'Yes', 'Active', '0', NULL, NULL, NULL, NULL);

INSERT INTO `sub_user_rights` (`client_id`, `role_id`, `menu_id`, `can_add`, `can_delete`, `can_update`, `can_view`, `status`, `created_by`, `updated_by`, `deleted_at`, `created_at`, `updated_at`)
VALUES ('0', '4', '2', 'Yes', 'Yes', 'Yes', 'Yes', 'Active', '0', NULL, NULL, NULL, NULL);

INSERT INTO `sub_user_rights` (`client_id`, `role_id`, `menu_id`, `can_add`, `can_delete`, `can_update`, `can_view`, `status`, `created_by`, `updated_by`, `deleted_at`, `created_at`, `updated_at`)
VALUES ('0', '4', '3', 'Yes', 'Yes', 'Yes', 'Yes', 'Active', '0', NULL, NULL, NULL, NULL);


/* Sub_Users: */
INSERT  INTO `sub_users`(`client_id`,`role_id`,`username`,`email`,`password`,`secondary_password`,`first_name`,`last_name`,`phone_number`,`alter_phone_number`,`address`,`city`,`state`,`zipcode`,`timezone`,`user_type`,`can_login`,`profile_picture`,`remember_token`,`status`,`created_by`,`updated_by`,`deleted_at`,`created_at`,`updated_at`) VALUES
(NULL,1,'BUIAdmin','admin@email.com','$2y$12$CSp9IiYw4G9XvLuXiyL5cOubRQFDKUR1x81/KovIrSw1nj015WuZ6','$2y$12$CSp9IiYw4G9XvLuXiyL5cOubRQFDKUR1x81/KovIrSw1nj015WuZ6','BUI','Admin','56767676776',NULL,NULL,NULL,NULL,NULL,NULL,'Super Admin','Yes','profile_pictures/QaBqHMONyKEXm9SuPYzago9F6PcoaN8YHkka1AhG.png',NULL,'Active',NULL,NULL,NULL,NULL,'2025-01-08 07:25:28');

/* Other Tables */
-- Same structure applies for all other tables where `id` is used as the primary key.
