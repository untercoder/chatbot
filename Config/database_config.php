<?php
define("DATABASE_HOST", "mysql:host=localhost;");
//Имя пользователя
define("DATABASE_USER", "a359271_1");
//Пароль от базы данных
define("DATABASE_PASSWD","cfuBW8B7Y3a6");
//Имя базы данных
define("DATABASE_NAME", "dbname=a359271_1;");
//Кодировка
define("CHARSET", "charset=utf8;");

define('CREATE_USER_ACTION',
    'CREATE TABLE `user_action`     ( 
                                                        `peer_id` INT NOT NULL , 
                                                        `iterator` INT NOT NULL , 
                                                        `command` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
                                                        `action` BOOLEAN NOT NULL
                                                        ) 
                        ENGINE = InnoDB CHARSET=utf8 COLLATE utf8_unicode_ci;');

define('CREATE_USER_DATA',
    'CREATE TABLE `user_data`    ( 
                                                    `first_reg` BOOLEAN NOT NULL ,
                                                    `peer_id` INT NOT NULL , 
                                                    `name` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL ,
                                                    `user_vk_id` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL ,
                                                    `location` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL , 
                                                    `age` INT NULL , 
                                                    `interest` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL , 
                                                    `comment` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL , 
                                                    `photo` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL , 
                                                    `audio` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL     
                                                    ) 
                        ENGINE = InnoDB CHARSET=utf8 COLLATE utf8_unicode_ci;');

define('CREATE_USER_SEARCH_PARAM',
    'CREATE TABLE `user_search_param` 
                                                    ( 
                                                    `first_reg` BOOLEAN NOT NULL ,   
                                                    `peer_id` INT NOT NULL , 
                                                    `location` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,  
                                                    `interest` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
                                                    `search_result` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL 
                                                    ) 
                        ENGINE = InnoDB;');