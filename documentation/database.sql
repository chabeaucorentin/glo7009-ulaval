--
-- Database : glo7009
--

-- --------------------------------------------------------

--
-- Dumping data for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
    `user_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_firstname` VARCHAR(50) NOT NULL,
    `user_lastname` VARCHAR(50) NOT NULL,
    `user_email` VARCHAR(100) NOT NULL,
    `user_password` VARCHAR(100) NOT NULL,
    PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_firstname`, `user_lastname`, `user_email`, `user_password`) VALUES
('Melissa', 'Ingram', 'melissaingram@test.ulaval.ca', '559891cc722dc455bc667f22689a8af7ec36696b271b3be2aad8e6bcb091b5aa'),      -- sha256(md5(ahfoo5Iuj))
('Brian', 'Mahoney', 'brianmahoney@test.ulaval.ca', '5ddb315526d74db3e99728bbb0e1eb23019f03cd1fb2da52e003f7cd54423942'),        -- sha256(md5(otooch8Ag))
('Charles', 'Atkinson', 'charlesatkinson@test.ulaval.ca', '45b7492a949421fe9a5df6ed7642da350f912e0312b44f70a05e58e8200dbde0'),  -- sha256(md5(ohl3iToPh))
('Cindy', 'Riding', 'cindyriding@test.ulaval.ca', 'ae4637b7975624cebac0ae2e7c3d0db6b46a05961369ea6e32d47779f747b670'),          -- sha256(md5(QuaX5yae9toh))
('James', 'Taylor', 'jamestaylor@test.ulaval.ca', 'b621e06112b49e322e3de8fc868138951042b1c4bf1278ce4d93155174ead7f2'),          -- sha256(md5(opha9ahG))
('Marsha', 'Thompson', 'marshathompson@test.ulaval.ca', '7b069da01bce8c2842596ab1f9e4394e0c195d9277f5b8e81b7c10a7cf920bc3'),    -- sha256(md5(chei6nae0Ae))
('William', 'Ritter', 'williamritter@test.ulaval.ca', '4cf76d5794fa081af1eab1069917500418652f11eacd0306cec122a3a14288bc');      -- sha256(md5(yu5Ahn6uu))

-- --------------------------------------------------------

--
-- Dumping data for table `tokens`
--

CREATE TABLE IF NOT EXISTS `tokens` (
    `token_code` VARCHAR(50) NOT NULL,
    `token_user_id` INT UNSIGNED NOT NULL,
    PRIMARY KEY (`token_code`),
    FOREIGN KEY (`token_user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
