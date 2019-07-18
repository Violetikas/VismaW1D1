create schema if not exists Visma1 collate utf8_bin;

create table if not exists Patterns
(
    pattern_id int auto_increment
        primary key,
    pattern varchar(255) not null,
    constraint Patterns_pattern_uindex
        unique (pattern)
);

create table if not exists Words
(
    word_id int auto_increment
        primary key,
    word varchar(255) not null,
    hyphenatedWord varchar(255) not null,
    constraint Words_word_uindex
        unique (word)
);

create table if not exists WordsAndPatternsID
(
    word_id int(11) null,
    pattern_id int(11) null
);
