/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

ALTER TABLE `general_settings`
ADD COLUMN `cors` text COMMENT '';

UPDATE `sidebar_menus` SET `options` = '{\n  \"name\": \"Transactions\",\n  \"icon\": \"briefcase\",\n  \"slug\": \"\",\n  \"submenu\": [\n    {\n      \"url\": \"deposit/history\",\n      \"name\": \"Deposit History\",\n      \"icon\": \"chevron-right\",\n      \"slug\": \"/deposit/history\"\n    },\n    {\n      \"url\": \"withdraw/history\",\n      \"name\": \"Withdraw History\",\n      \"icon\": \"chevron-right\",\n      \"slug\": \"/withdraw/history\"\n    },\n    {\n      \"url\": \"transaction/history\",\n      \"name\": \"Transaction History\",\n      \"icon\": \"chevron-right\",\n      \"slug\": \"/transaction/history\"\n    }\n  ]\n}' WHERE `id` = 28;

INSERT INTO `platforms` (`id`, `option`, `settings`, `updated_at`) VALUES
(4, 'system', '{\"sw\":1}', '2022-04-03 02:44:39');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;