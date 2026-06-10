-- ================================================
-- QUIZ OIGNONS — Base de données
-- ================================================
-- Importation : mysql -u root -p quiz_game < quiz_game.sql
-- ================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ------------------------------------------------
-- TABLE : users
-- ------------------------------------------------
CREATE TABLE IF NOT EXISTS `users` (
  `id`           INT          UNSIGNED NOT NULL AUTO_INCREMENT,
  `pseudo`       VARCHAR(30)  NOT NULL,
  `email`        VARCHAR(255) NOT NULL UNIQUE,
  `password`     VARCHAR(255) NOT NULL,
  `oignons`      INT          UNSIGNED NOT NULL DEFAULT 0,
  `games_played` INT          UNSIGNED NOT NULL DEFAULT 0,
  `is_admin`     TINYINT(1)   NOT NULL DEFAULT 0,
  `created_at`   TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------
-- TABLE : questions
-- ------------------------------------------------
CREATE TABLE IF NOT EXISTS `questions` (
  `id`             INT          UNSIGNED NOT NULL AUTO_INCREMENT,
  `question`       TEXT         NOT NULL,
  `answer1`        VARCHAR(255) NOT NULL,
  `answer2`        VARCHAR(255) NOT NULL,
  `answer3`        VARCHAR(255) NOT NULL,
  `answer4`        VARCHAR(255) NOT NULL,
  `correct_answer` TINYINT(1)   NOT NULL COMMENT '1, 2, 3 ou 4',
  `created_at`     TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------
-- TABLE : games
-- ------------------------------------------------
CREATE TABLE IF NOT EXISTS `games` (
  `id`              INT       UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id`         INT       UNSIGNED NOT NULL,
  `score`           INT       UNSIGNED NOT NULL DEFAULT 0,
  `total_questions` INT       UNSIGNED NOT NULL DEFAULT 0,
  `correct_answers` INT       UNSIGNED NOT NULL DEFAULT 0,
  `oignons_earned`  INT       UNSIGNED NOT NULL DEFAULT 0,
  `played_at`       TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_games_user`
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------
-- TABLE : game_questions (liaison games <-> questions)
-- ------------------------------------------------
CREATE TABLE IF NOT EXISTS `game_questions` (
  `id`          INT       UNSIGNED NOT NULL AUTO_INCREMENT,
  `game_id`     INT       UNSIGNED NOT NULL,
  `question_id` INT       UNSIGNED NOT NULL,
  `user_answer` TINYINT(1) NOT NULL COMMENT 'Réponse donnée par le joueur (1-4)',
  `is_correct`  TINYINT(1) NOT NULL DEFAULT 0,
  `answered_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_gq_game`
    FOREIGN KEY (`game_id`) REFERENCES `games` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_gq_question`
    FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;

-- ------------------------------------------------
-- DONNÉES DE TEST
-- ------------------------------------------------

-- Compte admin (mot de passe : Admin1234!)
INSERT INTO `users` (`pseudo`, `email`, `password`, `oignons`, `games_played`, `is_admin`)
VALUES (
  'Siren',
  'admin@quiz-oignons.fr',
  '$2y$10$m.oJM6lbDWUBBeV3lZmiouURlHtb21NE2f9TXf4VZ1DDjfF0HA4sO',
  5,
  0,
  1
);

-- Quelques questions de démo
INSERT INTO `questions` (`question`, `answer1`, `answer2`, `answer3`, `answer4`, `correct_answer`) VALUES
('Quelle est la capitale de la France ?',       'Lyon',     'Marseille', 'Paris',    'Bordeaux',  3),
('Combien font 7 × 8 ?',                        '54',       '56',        '48',       '64',        2),
('Quel est le plus grand océan du monde ?',      'Atlantique','Indien',   'Arctique', 'Pacifique', 4),
('Qui a peint la Joconde ?',                    'Raphaël',  'Michel-Ange','Léonard de Vinci','Botticelli', 3),
('En quelle année a eu lieu la Révolution française ?', '1789', '1799', '1776', '1815', 1),
('Quel est le symbole chimique de l''or ?',     'Ag',       'Fe',        'Au',       'Cu',        3),
('Combien de planètes y a-t-il dans le système solaire ?', '7', '8', '9', '10', 2),
('Qui a écrit "Les Misérables" ?',              'Balzac',   'Zola',      'Flaubert', 'Victor Hugo', 4),
('Quelle est la monnaie du Japon ?',            'Yuan',     'Won',       'Yen',      'Ringgit',   3),
('Quel sport se joue à Wimbledon ?',            'Golf',     'Tennis',    'Cricket',  'Badminton', 2);
