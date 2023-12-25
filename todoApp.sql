create database todolist;
use todolist;
create table if not exists `todo` (
`id` bigint(20) not null auto_increment,
`title` varchar(2048) not null,
`done` tinyint(1) not null default '0',
`created_at` timestamp not null default current_timestamp,
primary key (`id`)
);
-- See All Todos :
select * from todo;