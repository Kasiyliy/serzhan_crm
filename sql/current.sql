insert into users(first_name, last_name, login, password, phone_number)
VALUES ('Admin', 'Admin', 'admin', '112233', '+77005554797');
alter table users_roles add unique index ui_users_roles (user_id , role_id) ;