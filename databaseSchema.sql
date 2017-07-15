CREATE TABLE games (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `completed` tinyint(1) NOT NULL DEFAULT '0',
    `last_completed_turn_id` int(11),
    `turn_end_time` bigint(32) NOT NULL DEFAULT '0',
    `map` int(11),
    PRIMARY KEY (`id`)
);

CREATE TABLE users (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `username` varchar(255) NOT NULL DEFAULT '',
    `email` varchar(255) NOT NULL DEFAULT '',
    `password` varchar(255) NOT NULL DEFAULT '',
    PRIMARY KEY (`id`)
);

CREATE TABLE game_users (
    `game_id` int(11) NOT NULL,
    `user_id` int(11) NOT NULL,
    `is_bot` tinyint(1) NOT NULL DEFAULT '0',
    `coins` int(11) NOT NULL DEFAULT '0',
    `troops` int(11) NOT NULL DEFAULT '0',
    PRIMARY KEY (`game_id`,`user_id`),
    FOREIGN KEY (`game_id`) REFERENCES `games`(`id`),
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`)
);

CREATE TABLE turns (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `game_id` int(11) NOT NULL,
    `user_id` int(11) NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`game_id`) REFERENCES `games`(`id`),
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`)
);

CREATE TABLE territories (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `game_id` int(11) NOT NULL,
    `turn_id` int(11) NOT NULL,
    `is_occupied` tinyint(1) NOT NULL DEFAULT '0',
    `user_id` int(11) NOT NULL,
    `num_troops` int(11) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    FOREIGN KEY (`turn_id`) REFERENCES `turns`(`id`),
    FOREIGN KEY (`game_id`) REFERENCES `games`(`id`),
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`)
);

CREATE TABLE deployment_actions (
    `turn_id` int(11) NOT NULL,
    `coins_added` int(11) NOT NULL DEFAULT '0',
    `territory_id_attached` int(11) NOT NULL,
    `num_troops` int(11) NOT NULL DEFAULT '0',
    PRIMARY KEY (`turn_id`,`territory_id_attached`),
    FOREIGN KEY (`turn_id`) REFERENCES `turns`(`id`),
    FOREIGN KEY (`territory_id_attached`) REFERENCES `territories`(`id`)
);

CREATE TABLE attack_actions (
    `turn_id` int(11) NOT NULL,
    `attack_target` int(11) NOT NULL,
    `attack_from` int(11) NOT NULL,
    `num_troops` int(11) NOT NULL DEFAULT '0',
    PRIMARY KEY (`turn_id`,`attack_target`, `attack_from`),
    FOREIGN KEY (`turn_id`) REFERENCES `turns`(`id`),
    FOREIGN KEY (`attack_target`) REFERENCES `territories`(`id`),
    FOREIGN KEY (`attack_from`) REFERENCES `territories`(`id`)
);

