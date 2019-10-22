PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;

CREATE TABLE IF NOT EXISTS "films" (
    `id`    INTEGER NOT NULL UNIQUE,
    `title` TEXT NOT NULL,
    `studio`    INTEGER DEFAULT null,
    `rating`    TEXT DEFAULT null,
    `year`  TEXT DEFAULT null,
    `summary`   TEXT DEFAULT null,
    `thumb` TEXT DEFAULT null,
    `art`   TEXT DEFAULT null,
    `guid`  TEXT DEFAULT null,
    `imdbId`    TEXT DEFAULT null,
    `thumbUrl`  TEXT DEFAULT null,
    `artUrl`    TEXT DEFAULT null,
    `active` INTEGER DEFAULT 1,
    `library_id` INTEGER DEFAULT null
    PRIMARY KEY(`id`)
);

CREATE TABLE `libraries` (
    `id`    INTEGER UNIQUE,
    `library_name`  TEXT NOT NULL,
    `library_name_slug` TEXT NOT NULL,
    `library_type`  TEXT NOT NULL
);

CREATE TABLE `genres` (
    `id`    INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
    `genre` TEXT NOT NULL UNIQUE,
    `genre_slug`    TEXT NOT NULL UNIQUE
);

CREATE TABLE `subgenres` (
    `id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
    `subgenre` TEXT NOT NULL UNIQUE, `subgenre_slug` TEXT NOT NULL UNIQUE
);

CREATE TABLE `map_genre_film` (
    `film_id` INTEGER NOT NULL,
    `genre_id` INTEGER NOT NULL,
    PRIMARY KEY(`film_id`,`genre_id`)
);

CREATE TABLE `map_subgenre_film` (
    `film_id` INTEGER NOT NULL,
    `subgenre_id` INTEGER NOT NULL,
    PRIMARY KEY(`film_id`,`subgenre_id`)
);

CREATE TABLE IF NOT EXISTS "settings" (
    `id`    INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
    `setting_name`  TEXT NOT NULL UNIQUE,
    `setting_value` TEXT,
    `last_updated`  TIMESTAMP DEFAULT current_timestamp,
    `description`   text,
    `sort_order`    integer,
    `setting_slug`  TEXT,
    `widget_type` TEXT DEFAULT 'text',
    `conditional` TEXT DEFAULT null
);

INSERT INTO settings(setting_name, setting_value, description, sort_order, setting_slug, widget_type, conditional)
VALUES('URL', '', 'URL for Sinema (not used)', 1, 'url', 'text', null);

INSERT INTO settings(setting_name, setting_value, description, sort_order, setting_slug, widget_type, conditional)
VALUES('Kept Subgenres', 'Giallo;Slasher;Revenge;Women In Prison;Prison;Pinky Violence;Western', 'List of Subgenre that you want to keep. These are separated by ; in the database.', 500, 'kept-subgenres', 'selectize', null);

INSERT INTO settings(setting_name, setting_value, description, sort_order, setting_slug, widget_type, conditional)
VALUES('Plex Url','','The URL for the plex server in the format "http://<url:port>"',100,'plex-url', 'text', null);

INSERT INTO settings(setting_name, setting_value, description, sort_order, setting_slug, widget_type, conditional)
VALUES('Plex API Token', '', 'The API token of your plex server', 200, 'plex-api-token', 'text', null);

INSERT INTO settings(setting_name, setting_value, description, sort_order, setting_slug, widget_type, conditional)
VALUES('Enable Plex', '1', 'Enable Plex Integration (not implemented)', 600, 'enable-plex', 'checkbox', null);

INSERT INTO settings(setting_name, setting_value, description, sort_order, setting_slug, widget_type, conditional)
VALUES('Enable Prerolls', '1', 'To enable preroll support', 700, 'enable-prerolls', 'checkbox', null);

INSERT INTO settings(setting_name, setting_value, description, sort_order, setting_slug, widget_type, conditional)
VALUES('Enable Trailers', '1', 'To enable trailers in the system', 800, 'enable-trailers', 'checkbox', null);

CREATE TABLE `countries` (
    `id`    INTEGER PRIMARY KEY AUTOINCREMENT UNIQUE,
    `country`   TEXT UNIQUE,
    `country_slug`  TEXT UNIQUE
);

CREATE TABLE IF NOT EXISTS "map_country_film" (
    `film_id`   INTEGER NOT NULL,
    `country_id`    INTEGER NOT NULL,
    PRIMARY KEY(`film_id`,`country_id`)
);

CREATE TABLE `grindhouse` (
    `id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
    `name` TEXT DEFAULT null,
    `tagline` TEXT DEFAULT null,
    `last_updated` TIMESTAMP DEFAULT current_timestamp
);

CREATE TABLE `preroll_type` (
    `id`    INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
    `preroll_type_name` TEXT NOT NULL UNIQUE,
    `preroll_type_slug` TEXT NOT NULL UNIQUE,
    `preroll_type_description`  TEXT
);

INSERT INTO preroll_type(preroll_type_name, preroll_type_slug, preroll_type_description)
VALUES('Intro','intro','Intro');

INSERT INTO preroll_type(preroll_type_name, preroll_type_slug, preroll_type_description)
VALUES('Intermission','intermission','Intermission between features');

INSERT INTO preroll_type(preroll_type_name, preroll_type_slug, preroll_type_description)
VALUES('Joiner','joiner','Joins trailers together');

INSERT INTO preroll_type(preroll_type_name, preroll_type_slug, preroll_type_description)
VALUES('Feature Presentation','feature-presentation','Play before a feature presentation');

INSERT INTO preroll_type(preroll_type_name, preroll_type_slug, preroll_type_description)
VALUES('Information','information','Notes from theater management');

INSERT INTO preroll_type(preroll_type_name, preroll_type_slug, preroll_type_description)
VALUES('Outro','outro','Things that would be played at the end of the night');

CREATE TABLE IF NOT EXISTS "prerolls" (
    `id`    INTEGER NOT NULL UNIQUE,
    `title` TEXT NOT NULL,
    `summary`   TEXT DEFAULT null,
    `thumb` TEXT DEFAULT null,
    `art`   TEXT DEFAULT null,
    `guid`  TEXT DEFAULT null,
    `thumbUrl`  TEXT DEFAULT null,
    `artUrl`    TEXT DEFAULT null,
    `preroll_type_id`   INTEGER DEFAULT null,
    `active`    INTEGER DEFAULT 1,
    `preroll_series_id` INTEGER DEFAULT null
    PRIMARY KEY(`id`)
);

CREATE TABLE `preroll_series` (
    `id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
    `preroll_series_name` TEXT NOT NULL,
    `preroll_series_slug` TEXT NOT NULL,
    `active` INTEGER DEFAULT 1
)

COMMIT;
