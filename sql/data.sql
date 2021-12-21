create table review_mapping
(
    id      int auto_increment
        primary key,
    user_id int not null,
    cat_id  int not null
);

create table review_edit
(
    id          int auto_increment
        primary key,
    user_id     int          not null,
    page_title  varchar(255) not null,
    page_text   mediumblob   not null,
    edit_status tinyint      null
);
