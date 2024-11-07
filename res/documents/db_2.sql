CREATE TABLE `nrv_user` (
                        `user_uuid` char(36) NOT NULL,
                        `password` varchar(256) NOT NULL,
                        `user_role` int(11) NOT NULL DEFAULT 1,
                        CONSTRAINT pk_user PRIMARY KEY (`user_uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE `nrv_user2evening` (
                                `user_uuid` CHAR(36) NOT NULL,
                                `evening_uuid` CHAR(36) NOT NULL,
                                CONSTRAINT pk_user2show PRIMARY KEY (`user_uuid`, `evening_uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE `nrv_evening` (
                           `evening_uuid` CHAR(36) NOT NULL,
                           `evening_title` VARCHAR(256) NOT NULL,
                           `evening_theme` VARCHAR(256) NOT NULL,
                           `evening_date` DATE NOT NULL,
                           `evening_location_id` INT(11) NOT NULL,
                           `evening_description` TEXT NOT NULL,
                           `evening_price` DECIMAL(10,2) NOT NULL,
                           'evening_programmed' BOOL,
                           CONSTRAINT pk_evening PRIMARY KEY (`evening_uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE `nrv_evening2show` (
                                `evening_uuid` CHAR(36) NOT NULL,
                                `show_uuid` CHAR(36) NOT NULL,
                                CONSTRAINT pk_evening2show PRIMARY KEY (`evening_uuid`, `show_uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE `nrv_show` (
                        `show_uuid` CHAR(36) NOT NULL,
                        `show_title` VARCHAR(256) NOT NULL,
                        `show_description` TEXT NOT NULL,
                        `show_start_date` DATETIME NOT NULL,
                        `show_duration` TIME NOT NULL,
                        `show_style_id` INT(11) NOT NULL,
                        `show_url` VARCHAR(256), -- ex: lien youtube
                        'show_programmed' BOOL,
                        CONSTRAINT pk_show PRIMARY KEY (`show_uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE `nrv_show2artist` (
                               `show_uuid` CHAR(36) NOT NULL,
                               `artist_uuid` CHAR(36) NOT NULL,
                               CONSTRAINT pk_show2artist PRIMARY KEY (`show_uuid`, `artist_uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE `nrv_artist` (
                          `artist_uuid` CHAR(36) NOT NULL,
                          `artist_name` VARCHAR(256) NOT NULL,
                          `artist_description` TEXT NOT NULL,
                          `artist_url` VARCHAR(256), -- ex: lien youtube / wikipédia
                          CONSTRAINT pk_artist PRIMARY KEY (`artist_uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE `nrv_location` (
                            `location_id` INT(11) NOT NULL AUTO_INCREMENT,
                            `location_name` VARCHAR(256) NOT NULL,
                            `location_place_number` INT(11) NOT NULL,
                            `location_address` VARCHAR(256) NOT NULL,
                            `location_url` VARCHAR(256) NOT NULL, -- ex: https://maps.app.goo.gl/zQasPrPAEaGfZ5iq9
                            CONSTRAINT pk_location PRIMARY KEY (`location_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE `nrv_style` (
    `style_id` INT(11) NOT NULL AUTO_INCREMENT,
    `style_name` VARCHAR(256) NOT NULL,
    CONSTRAINT pk_location PRIMARY KEY (`style_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

ALTER TABLE `nrv_user2evening`
    ADD CONSTRAINT fk_nrv_user2evening_user FOREIGN KEY (`user_uuid`) REFERENCES `nrv_user`(`user_uuid`);
ALTER TABLE `nrv_user2evening`
    ADD CONSTRAINT fk_nrv_user2evening_evening FOREIGN KEY (`evening_uuid`) REFERENCES `nrv_evening`(`evening_uuid`);

ALTER TABLE `nrv_evening`
    ADD CONSTRAINT fk_evening_location FOREIGN KEY (`evening_location_id`) REFERENCES `nrv_location`(`location_id`);

ALTER TABLE `nrv_evening2show`
    ADD CONSTRAINT fk_evening2show_evening FOREIGN KEY (`evening_uuid`) REFERENCES `nrv_evening`(`evening_uuid`);
ALTER TABLE `nrv_evening2show`
    ADD CONSTRAINT fk_evening2show_show FOREIGN KEY (`show_uuid`) REFERENCES `nrv_show`(`show_uuid`);

ALTER TABLE `nrv_show2artist`
    ADD CONSTRAINT fk_show2artist_show FOREIGN KEY (`show_uuid`) REFERENCES `nrv_show`(`show_uuid`);
ALTER TABLE `nrv_show2artist`
    ADD CONSTRAINT fk_show2artist_artist FOREIGN KEY (`artist_uuid`) REFERENCES `nrv_artist`(`artist_uuid`);

ALTER TABLE `nrv_show`
    ADD CONSTRAINT fk_show_style FOREIGN KEY (`show_style_id`) REFERENCES `nrv_style`(`style_id`);
