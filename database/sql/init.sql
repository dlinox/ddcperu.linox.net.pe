
INSERT INTO `agencies` (`id`, `name`, `code_nsc`, `ruc`, `denomination`, `email_institutional`, `phone`, `license_start`, `license_end`, `address`, `country`, `city`, `ubigeo`, `is_enabled`, `created_at`, `updated_at`) VALUES (2, 'CCIPSA', '2316951', '00000000001', 'Consultoria y Capacitación Internacional Portocarrero S.A.', 'ingfportocarrero@gmail.com', '084129692', '2024-03-31', '2025-03-31', NULL, NULL, NULL, '000000', 1, '2024-08-03 15:32:01', '2024-08-03 15:32:01');

INSERT INTO `courses` (`id`, `name`, `code`, `description`, `is_enabled`, `created_at`, `updated_at`) VALUES (1, 'DDC 10th Ed 4Hrs NSC', 'DDC4H', 'Curso de manejo Defensivo de 4 Horas', 1, '2024-07-21 08:03:52', '2024-08-03 15:36:08');
INSERT INTO `courses` (`id`, `name`, `code`, `description`, `is_enabled`, `created_at`, `updated_at`) VALUES (2, 'DDC 10th Ed 6/8Hrs NSC', 'DDC6-8H', 'Curso de manejo Defensivo de 6/8 Horas', 1, '2024-07-21 08:03:52', '2024-08-03 15:38:21');
INSERT INTO `courses` (`id`, `name`, `code`, `description`, `is_enabled`, `created_at`, `updated_at`) VALUES (3, 'PTD 5th Ed 5 Hrs NSC', 'PTD5H', 'Curso MD para conductores profesionales 5 Horas', 1, '2024-08-03 15:39:48', '2024-08-03 15:39:48');
INSERT INTO `courses` (`id`, `name`, `code`, `description`, `is_enabled`, `created_at`, `updated_at`) VALUES (4, 'Lift Truck Operator -NSC', 'LTO-NSC', 'Curso de operación segura de Montacargas del NSC', 1, '2024-08-03 15:43:35', '2024-08-03 15:43:35');
INSERT INTO `courses` (`id`, `name`, `code`, `description`, `is_enabled`, `created_at`, `updated_at`) VALUES (5, 'FIRDAID-RCP-DEA', 'FARD8', 'Curso de Primeros Auxilios RCP y DEA - NSC', 1, '2024-08-03 15:47:20', '2024-08-03 15:47:20');

INSERT INTO `instructors` (`id`, `instructor_id`, `document_type`, `document_number`, `name`, `last_name`, `email`, `phone_number`, `instructor_link`, `agency_id`, `is_enabled`, `created_at`, `updated_at`) VALUES (2, '010008017', '001', '00128076', 'FRANCISCO JERONIMO', 'PORTOCARRERO CARCAMO', 'ingfportocarrero@gmail.com', '022804170', 'https://www.nsc.org/instructor-lookup/results?InstructorID=010008017', 2, 1, '2024-08-03 15:54:09', '2024-08-03 15:54:09');
INSERT INTO `instructor_licenseds` (`id`, `instructor_id`, `course_id`, `start_date`, `end_date`, `is_enabled`, `is_current`, `created_at`, `updated_at`) VALUES (1, 2, 1, '2024-03-31', '2025-03-31', 1, 0, '2024-08-03 15:54:49', '2024-08-03 15:54:49');
INSERT INTO `instructor_licenseds` (`id`, `instructor_id`, `course_id`, `start_date`, `end_date`, `is_enabled`, `is_current`, `created_at`, `updated_at`) VALUES (2, 2, 2, '2024-03-31', '2025-03-31', 1, 0, '2024-08-03 15:55:02', '2024-08-03 15:55:02');

INSERT INTO `students` (`id`, `document_type`, `document_number`, `name`, `paternal_surname`, `maternal_surname`, `email`, `phone_number`, `is_enabled`, `agency_id`, `link`, `created_at`, `updated_at`) VALUES (3, '001', '00012', 'ALMA', 'VALER', 'VILCA', 'ALVALVIL1601@GMAIL.COM', '988760019', 1, 2, NULL, '2024-08-03 16:00:44', '2024-08-03 16:25:07');

INSERT INTO `administrators` (`id`, `document_type`, `document_number`, `name`, `last_name`, `phone_number`, `is_sub_admin`, `is_enabled`, `created_at`, `updated_at`) VALUES (1, '001', '11111111', 'Admin', 'Admin', '1111111111', 0, 1, '2024-07-21 08:03:52', '2024-07-21 08:03:52');
INSERT INTO `administrators` (`id`, `document_type`, `document_number`, `name`, `last_name`, `phone_number`, `is_sub_admin`, `is_enabled`, `created_at`, `updated_at`) VALUES (2, '001', '73099576', 'ALMA MARYCIELO', 'VALER VILCA', '988760019', 0, 1, '2024-08-03 15:18:42', '2024-08-03 15:18:42');
INSERT INTO `administrators` (`id`, `document_type`, `document_number`, `name`, `last_name`, `phone_number`, `is_sub_admin`, `is_enabled`, `created_at`, `updated_at`) VALUES (3, '001', '001300491', 'Francis Eleana', 'Portocarrero Soto', '081307953', 1, 1, '2024-08-03 15:50:34', '2024-08-03 15:50:34');


INSERT INTO `permissions` (`id`, `name`, `menu`, `guard_name`, `created_at`, `updated_at`) VALUES (1, 'a.agencies', 'Gestion de Sub agencias', 'web', '2024-07-21 08:03:52', '2024-07-21 08:03:52');
INSERT INTO `permissions` (`id`, `name`, `menu`, `guard_name`, `created_at`, `updated_at`) VALUES (2, 'a.courses', 'Gestion de Cursos', 'web', '2024-07-21 08:03:52', '2024-07-21 08:03:52');
INSERT INTO `permissions` (`id`, `name`, `menu`, `guard_name`, `created_at`, `updated_at`) VALUES (3, 'a.certificates', 'Gestion de Certificados', 'web', '2024-07-21 08:03:52', '2024-07-21 08:03:52');
INSERT INTO `permissions` (`id`, `name`, `menu`, `guard_name`, `created_at`, `updated_at`) VALUES (4, 'a.reports', 'Reportes', 'web', '2024-07-21 08:03:52', '2024-07-21 08:03:52');
INSERT INTO `permissions` (`id`, `name`, `menu`, `guard_name`, `created_at`, `updated_at`) VALUES (5, 'a.administrators', 'Gestion de Administradores', 'web', '2024-07-21 08:03:52', '2024-07-21 08:03:52');
INSERT INTO `permissions` (`id`, `name`, `menu`, `guard_name`, `created_at`, `updated_at`) VALUES (6, 'a.students.admin', 'Gestion de Estudiantes', 'web', '2024-07-21 08:03:52', '2024-07-21 08:03:52');
INSERT INTO `permissions` (`id`, `name`, `menu`, `guard_name`, `created_at`, `updated_at`) VALUES (7, 'a.instructors', 'Gestion de Instructores', 'web', '2024-07-21 08:03:52', '2024-07-21 08:03:52');
INSERT INTO `permissions` (`id`, `name`, `menu`, `guard_name`, `created_at`, `updated_at`) VALUES (8, 's.instructors', 'Gestion de Instructores', 'web', '2024-07-21 08:03:52', '2024-07-21 08:03:52');
INSERT INTO `permissions` (`id`, `name`, `menu`, `guard_name`, `created_at`, `updated_at`) VALUES (9, 's.certificates', 'Gestion de Certificados', 'web', '2024-07-21 08:03:52', '2024-07-21 08:03:52');
INSERT INTO `permissions` (`id`, `name`, `menu`, `guard_name`, `created_at`, `updated_at`) VALUES (10, 's.students', 'Gestion de Estudiantes', 'web', '2024-07-21 08:03:52', '2024-07-21 08:03:52');
INSERT INTO `permissions` (`id`, `name`, `menu`, `guard_name`, `created_at`, `updated_at`) VALUES (11, 'i.certificates', 'Gestion certificados', 'web', '2024-07-21 08:03:52', '2024-07-21 08:03:52');


INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `agency_id`, `profile_id`, `is_enabled`, `email_verified_at`, `remember_token`, `created_at`, `updated_at`) VALUES (1, 'admin', 'admin@test.com', '$2y$12$sYwKkSOaAUnYmQzje3GXWel2hM.apMGGlIX9pBmGSv6C07UnEzRFi', '001', NULL, 1, 1, NULL, NULL, '2024-07-21 08:03:52', '2024-07-21 08:03:52');
INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `agency_id`, `profile_id`, `is_enabled`, `email_verified_at`, `remember_token`, `created_at`, `updated_at`) VALUES (2, 'examenes@ddcperu.com.pe', 'examenes@ddcperu.com.pe', '$2y$12$HE8zbGoE5wFji47UJvpzaerL18leoxKg2mRCfv5KxkWfgK3euAqRa', '001', NULL, 2, 1, NULL, NULL, '2024-08-03 15:18:42', '2024-08-03 15:18:42');
INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `agency_id`, `profile_id`, `is_enabled`, `email_verified_at`, `remember_token`, `created_at`, `updated_at`) VALUES (3, 'francis.portocarrero.ccipsa@gmail.com', 'francis.portocarrero.ccipsa@gmail.com', '$2y$12$CxBM6yGNHSEE0p7AVIA/jOy4i0LX/vU.D8oucsGvFk6rEokAwZE4S', '001', 2, 3, 1, NULL, NULL, '2024-08-03 15:50:34', '2024-08-03 15:50:34');
INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `agency_id`, `profile_id`, `is_enabled`, `email_verified_at`, `remember_token`, `created_at`, `updated_at`) VALUES (5, 'ingfportocarrero@gmail.com', 'ingfportocarrero@gmail.com', '$2y$12$CZxJEWSX5KfbRK5TW2Q56OZu3HIGc6p4LHNQ9BPUueeizjuNK.HOi', '003', 2, 2, 1, NULL, NULL, '2024-08-03 15:54:10', '2024-08-03 15:54:10');



INSERT INTO `model_has_permissions` (`permission_id`, `model_type`, `model_id`) VALUES (1, 'App\\Models\\User', 1);
INSERT INTO `model_has_permissions` (`permission_id`, `model_type`, `model_id`) VALUES (2, 'App\\Models\\User', 1);
INSERT INTO `model_has_permissions` (`permission_id`, `model_type`, `model_id`) VALUES (3, 'App\\Models\\User', 1);
INSERT INTO `model_has_permissions` (`permission_id`, `model_type`, `model_id`) VALUES (4, 'App\\Models\\User', 1);
INSERT INTO `model_has_permissions` (`permission_id`, `model_type`, `model_id`) VALUES (5, 'App\\Models\\User', 1);
INSERT INTO `model_has_permissions` (`permission_id`, `model_type`, `model_id`) VALUES (6, 'App\\Models\\User', 1);
INSERT INTO `model_has_permissions` (`permission_id`, `model_type`, `model_id`) VALUES (7, 'App\\Models\\User', 1);
INSERT INTO `model_has_permissions` (`permission_id`, `model_type`, `model_id`) VALUES (1, 'App\\Models\\User', 2);
INSERT INTO `model_has_permissions` (`permission_id`, `model_type`, `model_id`) VALUES (2, 'App\\Models\\User', 2);
INSERT INTO `model_has_permissions` (`permission_id`, `model_type`, `model_id`) VALUES (3, 'App\\Models\\User', 2);
INSERT INTO `model_has_permissions` (`permission_id`, `model_type`, `model_id`) VALUES (4, 'App\\Models\\User', 2);
INSERT INTO `model_has_permissions` (`permission_id`, `model_type`, `model_id`) VALUES (5, 'App\\Models\\User', 2);
INSERT INTO `model_has_permissions` (`permission_id`, `model_type`, `model_id`) VALUES (6, 'App\\Models\\User', 2);
INSERT INTO `model_has_permissions` (`permission_id`, `model_type`, `model_id`) VALUES (7, 'App\\Models\\User', 2);
INSERT INTO `model_has_permissions` (`permission_id`, `model_type`, `model_id`) VALUES (8, 'App\\Models\\User', 3);
INSERT INTO `model_has_permissions` (`permission_id`, `model_type`, `model_id`) VALUES (9, 'App\\Models\\User', 3);
INSERT INTO `model_has_permissions` (`permission_id`, `model_type`, `model_id`) VALUES (10, 'App\\Models\\User', 3);
INSERT INTO `model_has_permissions` (`permission_id`, `model_type`, `model_id`) VALUES (11, 'App\\Models\\User', 5);
