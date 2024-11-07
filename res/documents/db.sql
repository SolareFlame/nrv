CREATE TABLE `user` (
    `user_uuid` char(36) NOT NULL,
    `password` varchar(256) NOT NULL,
    `user_role` int(11) NOT NULL DEFAULT 1,
    CONSTRAINT pk_user PRIMARY KEY (`user_uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE `user2evening` (
    `user_uuid` CHAR(36) NOT NULL,
    `evening_uuid` CHAR(36) NOT NULL,
    CONSTRAINT pk_user2show PRIMARY KEY (`user_uuid`, `evening_uuid`),
    CONSTRAINT fk_user FOREIGN KEY (`user_uuid`) REFERENCES `user`(`user_uuid`),
    CONSTRAINT fk_show FOREIGN KEY (`evening_uuid`) REFERENCES `show`(`evening_uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE `evening` (
    `evening_uuid` CHAR(36) NOT NULL,
    `evening_title` VARCHAR(256) NOT NULL,
    `evening_theme` VARCHAR(256) NOT NULL,
    `evening_date` DATE NOT NULL,
    `evening_location` VARCHAR(256) NOT NULL,
    `evening_description` TEXT NOT NULL,
    `evening_price` DECIMAL(10,2) NOT NULL,
    CONSTRAINT pk_evening PRIMARY KEY (`evening_uuid`),
    CONSTRAINT fk_location FOREIGN KEY (`evening_location`) REFERENCES `location`(`location_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE `evening2show` (
    `evening_uuid` CHAR(36) NOT NULL,
	`show_uuid` CHAR(36) NOT NULL,
	CONSTRAINT pk_evening2show PRIMARY KEY (`evening_uuid`, `show_uuid`),
	CONSTRAINT fk_evening FOREIGN KEY (`evening_uuid`) REFERENCES `evening`(`evening_uuid`),
	CONSTRAINT fk_show FOREIGN KEY (`show_uuid`) REFERENCES `show`(`show_uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE `show` (
    `show_uuid` CHAR(36) NOT NULL,
    `show_title` VARCHAR(256) NOT NULL,
    `show_description` TEXT NOT NULL,
    `show_start_time` TIME NOT NULL,
    `show_duration` TIME NOT NULL,
    `show_style` VARCHAR(256) NOT NULL,
    `show_url` VARCHAR(256), --ex: lien youtube
    CONSTRAINT pk_show PRIMARY KEY (`show_uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE `show2artist` (
    `show_uuid` CHAR(36) NOT NULL,
    `artist_uuid` CHAR(36) NOT NULL,
    CONSTRAINT pk_show2artist PRIMARY KEY (`show_uuid`, `artist_uuid`),
    CONSTRAINT fk_show FOREIGN KEY (`show_uuid`) REFERENCES `show`(`show_uuid`),
    CONSTRAINT fk_artist FOREIGN KEY (`artist_uuid`) REFERENCES `artist`(`artist_uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE `artist` (
    `artist_uuid` CHAR(36) NOT NULL,
    `artist_name` VARCHAR(256) NOT NULL,
    `artist_description` TEXT NOT NULL,
    `artist_url` VARCHAR(256), --ex: lien youtube / wikipédia
    CONSTRAINT pk_artist PRIMARY KEY (`artist_uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE `location` (
    `location_id` INT(11) NOT NULL AUTO_INCREMENT,
    `location_name` VARCHAR(256) NOT NULL,
    `location_place_number` INT(11) NOT NULL,
    `location_address` VARCHAR(256) NOT NULL,
    `location_url` VARCHAR(256) NOT NULL, --ex: https://maps.app.goo.gl/zQasPrPAEaGfZ5iq9
    CONSTRAINT pk_location PRIMARY KEY (`location_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;



