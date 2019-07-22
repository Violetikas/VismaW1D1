create schema if not exists Visma1 collate utf8_bin;

create table if not exists Patterns
(
    pattern_id int auto_increment
        primary key,
    pattern    varchar(255) not null,
    constraint Patterns_pattern_uindex
        unique (pattern)
);

create table if not exists Words
(
    word_id int auto_increment
        primary key,
    word    varchar(255) not null,
    constraint Words_word_uindex
        unique (word)
);

create table if not exists HyphenatedWords
(
    word_id        int          not null primary key ,
    hyphenatedWord varchar(255) not null,
    constraint HyphenatedWords_Words_word_id_fk
        foreign key (word_id) references Words (word_id) on delete cascade
);


create table WordsAndPatternsID
(
    word_id    int not null,
    pattern_id int not null,
    constraint WordsAndPatternsID_Patterns_pattern_id_fk
        foreign key (pattern_id) references Patterns (pattern_id),
    constraint WordsAndPatternsID_Words_word_id_fk
        foreign key (word_id) references Words (word_id) on delete cascade
);


