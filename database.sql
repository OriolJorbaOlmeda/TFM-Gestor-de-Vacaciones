
INSERT INTO company values (1, 'MPWAR', 'Bonanova, Barcelona');

INSERT INTO department (id, name, code, company_id)
values (1, 'Departamento de admin', 'DEP-ADMIN', 1);

INSERT INTO department (id, name, code, company_id)
values (2,'Recursos Humanos', 'DEP-RRHH', 1);

INSERT INTO department (id, name, code, company_id)
values (3,'Seguridad web', 'DEP-SW', 1);

INSERT INTO department (id, name, code, company_id)
values (4,'Departamento de informática', 'DEP-IF', 1);


INSERT INTO user (id, department_id, name, lastname, direction, city, province, postalcode, total_vacation_days, pending_vacation_days, email, roles, password)
values (1, 1, 'MireiaAdmin', 'Pepazo', 'Rambla, 2', 'Bilbo', 'Bizkaia', '45673', 20, 20, 'mire@gmail.com', '["ROLE_ADMIN"]', '$2y$13$3E5azhGwV/lNyapRbulGieD5SB8Gx5CGW/wuYunHkOEtQSzlVbZ0a' );


/* USUARIOS DEL DEPARTAMENTO 2 */

INSERT INTO user (id, department_id, name, lastname, direction, city, province, postalcode, total_vacation_days, pending_vacation_days, email, roles, password)
values (2, 2, 'ArianeDep2Super', 'Pepaza', 'Rambla, 2', 'Bilbo', 'Bizkaia', '45673', 20, 20, 'ariane@gmail.com', '["ROLE_SUPERVISOR"]', '$2y$13$3E5azhGwV/lNyapRbulGieD5SB8Gx5CGW/wuYunHkOEtQSzlVbZ0a' );

INSERT INTO user (id, department_id, name, lastname, direction, city, province, postalcode, total_vacation_days, pending_vacation_days, email, roles, password)
values (3, 2, 'OriolDep2Super', 'Pepaza', 'Rambla, 2', 'Bilbo', 'Bizkaia', '45673', 20, 20, 'oriol@gmail.com', '["ROLE_SUPERVISOR"]', '$2y$13$3E5azhGwV/lNyapRbulGieD5SB8Gx5CGW/wuYunHkOEtQSzlVbZ0a' );

INSERT INTO user (id, department_id, name, lastname, direction, city, province, postalcode, total_vacation_days, pending_vacation_days, email, roles, password, supervisor_id)
values (4, 2, 'PepaDep2Emplo', 'Pepaza', 'Rambla, 2', 'Terrassa', 'Barcelona', '08222', 20, 20, 'pepa@gmail.com', '["ROLE_EMPLEADO"]', '$2y$13$3E5azhGwV/lNyapRbulGieD5SB8Gx5CGW/wuYunHkOEtQSzlVbZ0a', 2);

INSERT INTO user (id, department_id, name, lastname, direction, city, province, postalcode, total_vacation_days, pending_vacation_days, email, roles, password, supervisor_id)
values (5, 2, 'JoseDep2Emplo', 'Pepazo', 'Rambla, 2', 'Terrassa', 'Barcelona', '08222', 20, 20, 'jose@gmail.com', '["ROLE_EMPLEADO"]', '$2y$13$3E5azhGwV/lNyapRbulGieD5SB8Gx5CGW/wuYunHkOEtQSzlVbZ0a', 3);

INSERT INTO user (id, department_id, name, lastname, direction, city, province, postalcode, total_vacation_days, pending_vacation_days, email, roles, password, supervisor_id)
values (6, 2, 'DavidDep2Emplo', 'Pepazo', 'Rambla, 2', 'Terrassa', 'Barcelona', '08222', 20, 20, 'david@gmail.com', '["ROLE_EMPLEADO"]', '$2y$13$3E5azhGwV/lNyapRbulGieD5SB8Gx5CGW/wuYunHkOEtQSzlVbZ0a', 3);



/* USUARIOS DEL DEPARTAMENTO 3 */

INSERT INTO user (id, department_id, name, lastname, direction, city, province, postalcode, total_vacation_days, pending_vacation_days, email, roles, password)
values (7, 3, 'AnaDep3Super', 'Pepaza', 'Rambla, 2', 'Bilbo', 'Bizkaia', '45673', 20, 20, 'ana@gmail.com', '["ROLE_SUPERVISOR"]', '$2y$13$3E5azhGwV/lNyapRbulGieD5SB8Gx5CGW/wuYunHkOEtQSzlVbZ0a' );

INSERT INTO user (id, department_id, name, lastname, direction, city, province, postalcode, total_vacation_days, pending_vacation_days, email, roles, password)
values (8, 3, 'TaniaDep3Super', 'Pepaza', 'Rambla, 2', 'Bilbo', 'Bizkaia', '45673', 20, 20, 'tania@gmail.com', '["ROLE_SUPERVISOR"]', '$2y$13$3E5azhGwV/lNyapRbulGieD5SB8Gx5CGW/wuYunHkOEtQSzlVbZ0a');

INSERT INTO user (id, department_id, name, lastname, direction, city, province, postalcode, total_vacation_days, pending_vacation_days, email, roles, password, supervisor_id)
values (9, 3, 'RosaDep3Emplo', 'Pepaza', 'Rambla, 2', 'Terrassa', 'Barcelona', '08222', 20, 20, 'rosa@gmail.com', '["ROLE_EMPLEADO"]', '$2y$13$3E5azhGwV/lNyapRbulGieD5SB8Gx5CGW/wuYunHkOEtQSzlVbZ0a', 8);

INSERT INTO user (id, department_id, name, lastname, direction, city, province, postalcode, total_vacation_days, pending_vacation_days, email, roles, password, supervisor_id)
values (10, 3, 'RitaDep3Emplo', 'Pepazo', 'Rambla, 2', 'Terrassa', 'Barcelona', '08222', 20, 20, 'rita@gmail.com', '["ROLE_EMPLEADO"]', '$2y$13$3E5azhGwV/lNyapRbulGieD5SB8Gx5CGW/wuYunHkOEtQSzlVbZ0a', 7);

INSERT INTO user (id, department_id, name, lastname, direction, city, province, postalcode, total_vacation_days, pending_vacation_days, email, roles, password, supervisor_id)
values (11, 3, 'LuisDep3Emplo', 'Pepazo', 'Rambla, 2', 'Terrassa', 'Barcelona', '08222', 20, 20, 'luis@gmail.com', '["ROLE_EMPLEADO"]', '$2y$13$3E5azhGwV/lNyapRbulGieD5SB8Gx5CGW/wuYunHkOEtQSzlVbZ0a', 7);






