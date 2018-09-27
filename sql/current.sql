alter table orders add column user_id int not null;

alter table orders add constraint foreign  key
fk_orders_users (user_id) references users(id)
on update restrict on delete restrict ;

