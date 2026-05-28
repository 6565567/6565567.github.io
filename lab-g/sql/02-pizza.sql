create table pizza
(
    id integer not null
        constraint pizza_pk
            primary key autoincrement,
    name text not null,
    size text not null
);