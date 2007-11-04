--
-- Structure of the table community_user
--

CREATE TABLE `community_user` (
    `login` VARCHAR(20) NOT NULL,
    `password` VARCHAR(40) NOT NULL DEFAULT '',
    `status` INT NOT NULL DEFAULT 0,
    `email` VARCHAR(128) NOT NULL DEFAULT '',
    `nickname` VARCHAR(128) NOT NULL DEFAULT '',
    `keyactivate` VARCHAR(10) NOT NULL DEFAULT '',
    PRIMARY KEY (`login`)
);
