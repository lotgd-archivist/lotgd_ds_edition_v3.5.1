-- MySQL dump 10.16  Distrib 10.1.10-MariaDB, for Win32 (AMD64)
--
-- Host: localhost    Database: ds39
-- ------------------------------------------------------
-- Server version	10.1.10-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `account_bio_freetexts`
--

DROP TABLE IF EXISTS `account_bio_freetexts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `account_bio_freetexts` (
  `acctid` int(10) unsigned NOT NULL COMMENT 'Account ID',
  `fieldid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Feld ID (primary)',
  `field_title` varchar(255) NOT NULL,
  `field_value` text NOT NULL,
  `pos2` varchar(255) NOT NULL DEFAULT 'Interessantes',
  `sort` int(255) NOT NULL DEFAULT '1',
  PRIMARY KEY (`fieldid`),
  KEY `sort` (`sort`),
  KEY `pos2` (`pos2`),
  KEY `acctid` (`acctid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Diese Tabelle enthält die Freitextfelder für die Bio';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `account_bio_freetexts`
--

LOCK TABLES `account_bio_freetexts` WRITE;
/*!40000 ALTER TABLE `account_bio_freetexts` DISABLE KEYS */;
/*!40000 ALTER TABLE `account_bio_freetexts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `account_extra_info`
--

DROP TABLE IF EXISTS `account_extra_info`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `account_extra_info` (
  `acctid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `bio` varchar(255) NOT NULL,
  `biotime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `imgtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `sentence` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `treepick` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `poollook` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `worms` smallint(5) unsigned NOT NULL DEFAULT '0',
  `minnows` smallint(5) unsigned NOT NULL DEFAULT '0',
  `boatcoupons` smallint(5) unsigned NOT NULL DEFAULT '0',
  `fishturn` smallint(4) NOT NULL DEFAULT '0',
  `abyss` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `oldspirit` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `gotfreeale` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `beerspent` int(10) unsigned NOT NULL DEFAULT '0',
  `bestdragonage` int(10) unsigned NOT NULL DEFAULT '0',
  `abused` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `mountextrarounds` mediumint(9) NOT NULL DEFAULT '0',
  `mountspecialdate` varchar(30) NOT NULL DEFAULT '',
  `disciples_spoiled` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `beatenup` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `guildtransferred_gold` int(10) unsigned NOT NULL DEFAULT '0',
  `guildtransferred_gems` int(10) unsigned NOT NULL DEFAULT '0',
  `xmountname` varchar(50) DEFAULT NULL,
  `hasxmount` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `rename_mount` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `mount_sausage` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `correctlogout` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `hadnewday` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `rouletterounds` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `xchangedtoday` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `treasure_f` smallint(5) unsigned NOT NULL DEFAULT '0',
  `timesbeaten` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `bunnies` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `runaway` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `bio_freetexts_count` int(10) unsigned NOT NULL DEFAULT '1' COMMENT 'Anzahl möglicher Freifelder',
  `advanced_title_options` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT 'Indikator ob Benutzer den Titel ausblenden oder nach hinten stellen darf.',
  `trophyhunter` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `dollturns` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `charclass` varchar(255) NOT NULL,
  `mastertrain` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `cage_action` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `bunnyhunt` mediumint(8) unsigned NOT NULL,
  `guildfights` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `ctitle` varchar(100) NOT NULL,
  `cname` varchar(100) NOT NULL,
  `csign` char(12) NOT NULL,
  `title_postorder` tinyint(1) unsigned NOT NULL COMMENT 'Wenn 1, wird eigener Titel hinter den Namen gesetzt.',
  `title_hide` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT 'Wenn 1, wird der Titel nicht angezeigt.',
  `sympathy` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `symp_given` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `symp_votes` smallint(5) unsigned NOT NULL DEFAULT '0',
  `temple_servant` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `ctitle_backup` varchar(50) NOT NULL DEFAULT '',
  `profession_tmp` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `html_locked` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `discussion` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `wounds` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `doc_visited` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `DDL_tent` int(10) unsigned NOT NULL DEFAULT '0',
  `shortcuts` smallint(5) unsigned NOT NULL DEFAULT '0',
  `lottery` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `maze` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `castlemaze_visited` text NOT NULL,
  `boughtroomtoday` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `seenbard` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `seenacademy` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `usedouthouse` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `hauntedby` varchar(120) NOT NULL DEFAULT '',
  `birthday` varchar(20) NOT NULL DEFAULT '',
  `referer` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `refererawarded` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `namecheck` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `namecheckday` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `avatar` varchar(120) NOT NULL DEFAULT '',
  `goldin` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `goldout` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `gemsin` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `gemsout` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `witch` tinyint(1) NOT NULL,
  `itemsin` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `itemsout` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `maze_map` blob NOT NULL,
  `spittoday` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `runes_ident` varchar(255) NOT NULL DEFAULT 'a:0:{}',
  `beggar` bigint(20) NOT NULL DEFAULT '0',
  `dpower` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `ext_profile` text,
  `games_played` smallint(5) unsigned NOT NULL DEFAULT '0',
  `donations` float unsigned NOT NULL DEFAULT '0',
  `char_birthdate` varchar(11) NOT NULL DEFAULT '0',
  `combos` text NOT NULL,
  `quizpoints` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `quizpoints_spent` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `job` smallint(5) unsigned NOT NULL DEFAULT '0',
  `jobturns` tinyint(3) unsigned NOT NULL DEFAULT '5',
  `gladiatorfights` tinyint(2) unsigned NOT NULL DEFAULT '15',
  `last_crime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `tussle` text,
  `tussle_rounds` int(10) unsigned NOT NULL,
  `bullfightwins` int(10) unsigned NOT NULL DEFAULT '0',
  `free_resurrections` tinyint(3) unsigned NOT NULL DEFAULT '10',
  `cave_xtal` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `cave_chest` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `cave_remind` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `resurrections_today` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `bio_extra_notes` text NOT NULL,
  `bloodchampdays` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `seenpetitions` text NOT NULL,
  `kleineswesen` tinyint(4) NOT NULL DEFAULT '0',
  `kala_visits` tinyint(3) unsigned DEFAULT '0',
  `gourmet` smallint(5) unsigned NOT NULL,
  `hunterlevel` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `together_with` varchar(255) DEFAULT NULL COMMENT 'Zusammen mit',
  `weaponname` varchar(128) NOT NULL,
  `gotalekegs` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `together_yesno` tinyint(1) NOT NULL DEFAULT '0',
  `creaturewin` varchar(120) NOT NULL,
  `creaturelose` varchar(120) NOT NULL,
  `chessgameswon` mediumint(9) NOT NULL DEFAULT '0',
  `maxbonestack` smallint(5) unsigned NOT NULL,
  `goldmine_visits` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT 'Anzahl der Goldminenbesuche am richtigen ND',
  `dragonpoints_changed` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT 'Sind schon Änderungen an den Heldenpunkten erfolgt?',
  `hotkey_hexcode` varchar(7) NOT NULL DEFAULT 'default' COMMENT 'Welche Farbe sollen die Hotkeys besitzen',
  `seenpirate` int(11) NOT NULL DEFAULT '0',
  `stierextra` tinyint(1) NOT NULL DEFAULT '0',
  `msg_chars` text NOT NULL,
  `wetterhexe_charm` tinyint(3) NOT NULL DEFAULT '0',
  `wastedgold` int(11) unsigned NOT NULL DEFAULT '0',
  `gladarena` tinyint(1) NOT NULL DEFAULT '3',
  `gladpush` tinyint(1) NOT NULL DEFAULT '5',
  `quests_sterne` int(255) NOT NULL DEFAULT '0',
  `quests_time` int(255) NOT NULL DEFAULT '0',
  `quests_solved` int(255) NOT NULL DEFAULT '0',
  `questinator` varchar(255) NOT NULL,
  `ext_rp` text NOT NULL,
  `ext_ooc` text NOT NULL,
  `ext_multis` text NOT NULL,
  `stecktabs` text NOT NULL,
  `ext_bio_orte` text NOT NULL,
  PRIMARY KEY (`acctid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPRESSED COMMENT='Additional Information for lotgd accounts';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `account_extra_info`
--

LOCK TABLES `account_extra_info` WRITE;
/*!40000 ALTER TABLE `account_extra_info` DISABLE KEYS */;
INSERT INTO `account_extra_info` VALUES (1,'','0000-00-00 00:00:00','0000-00-00 00:00:00',0,0,0,0,0,0,5,0,0,0,0,0,0,0,'',0,0,0,0,NULL,0,0,0,0,2,5,0,0,0,0,0,1,0,0,5,'',0,0,0,0,'','','',0,0,0,0,0,0,'',0,0,0,0,0,0,0,0,0,'',0,0,0,0,'','0000-00-00',0,0,0,0,'',0,0,0,0,0,0,0,'',0,'a:0:{}',0,0,NULL,0,0,'0','',0,0,0,5,15,'0000-00-00 00:00:00',NULL,0,0,10,0,0,0,0,'',0,'',0,0,0,0,NULL,'',0,0,'','',0,0,0,0,'default',0,0,'{}',0,0,3,5,0,0,0,'{}','','','','','');
/*!40000 ALTER TABLE `account_extra_info` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `account_ignore`
--

DROP TABLE IF EXISTS `account_ignore`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `account_ignore` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `acctid` int(100) NOT NULL,
  `ignoreid` int(40) NOT NULL,
  `reason` varchar(255) NOT NULL,
  `date` date DEFAULT NULL,
  `type1` smallint(1) NOT NULL DEFAULT '0',
  `type2` smallint(1) NOT NULL DEFAULT '0',
  `type3` smallint(1) NOT NULL DEFAULT '0',
  `type4` smallint(1) NOT NULL DEFAULT '0',
  `type5` smallint(1) NOT NULL DEFAULT '0',
  `type6` smallint(1) NOT NULL DEFAULT '0',
  `type7` smallint(1) NOT NULL DEFAULT '0',
  `type8` smallint(1) NOT NULL DEFAULT '0',
  `type9` smallint(1) NOT NULL DEFAULT '0',
  `type100` smallint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `type100` (`type100`),
  KEY `type9` (`type9`),
  KEY `type8` (`type8`),
  KEY `type7` (`type7`),
  KEY `type6` (`type6`),
  KEY `type5` (`type5`),
  KEY `type4` (`type4`),
  KEY `type3` (`type3`),
  KEY `type2` (`type2`),
  KEY `type1` (`type1`),
  KEY `date` (`date`),
  KEY `ignoreid` (`ignoreid`),
  KEY `acctid` (`acctid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `account_ignore`
--

LOCK TABLES `account_ignore` WRITE;
/*!40000 ALTER TABLE `account_ignore` DISABLE KEYS */;
/*!40000 ALTER TABLE `account_ignore` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `account_multi`
--

DROP TABLE IF EXISTS `account_multi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `account_multi` (
  `master` int(10) unsigned DEFAULT NULL,
  `slave` int(10) unsigned DEFAULT NULL,
  KEY `master` (`master`,`slave`),
  KEY `slave` (`slave`),
  KEY `master_2` (`master`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `account_multi`
--

LOCK TABLES `account_multi` WRITE;
/*!40000 ALTER TABLE `account_multi` DISABLE KEYS */;
/*!40000 ALTER TABLE `account_multi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `account_stats`
--

DROP TABLE IF EXISTS `account_stats`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `account_stats` (
  `acctid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `logintime` int(10) unsigned NOT NULL DEFAULT '0',
  `onlinetime` int(10) unsigned NOT NULL DEFAULT '0',
  `comments` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `comments_rp` smallint(5) unsigned NOT NULL DEFAULT '0',
  `comments_rp_len` bigint(20) NOT NULL DEFAULT '0',
  `comments_rp_ges` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `pvpkilled` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `pvpkills` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `mailsent` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `mailreceived` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `turns_not_used` int(10) unsigned NOT NULL DEFAULT '0',
  `commentlength` int(10) unsigned NOT NULL DEFAULT '0',
  `browser` varchar(5) NOT NULL DEFAULT 'ukn',
  `browser_version` varchar(5) NOT NULL DEFAULT '0.0',
  `petitions` smallint(5) unsigned NOT NULL DEFAULT '0',
  `collect_special` int(10) unsigned DEFAULT '0' COMMENT 'Anzahl der eingesammelten Dinger beim Sammelspecial',
  `tombraids` mediumint(8) unsigned DEFAULT '0',
  `unique_items_made` int(10) unsigned DEFAULT '0',
  `abandoncastle_visits` int(10) unsigned DEFAULT '0',
  `xmas_cards_sent` mediumint(8) unsigned DEFAULT '0',
  `gardenmaze_visits` int(11) NOT NULL DEFAULT '0',
  `analloni_rituals` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`acctid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Enthält versch. Statistiken über das Benutzerverhalten.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `account_stats`
--

LOCK TABLES `account_stats` WRITE;
/*!40000 ALTER TABLE `account_stats` DISABLE KEYS */;
INSERT INTO `account_stats` VALUES (1,0,0,0,0,0,0,0,0,0,0,0,0,'unb','1',1,0,0,0,0,0,0,0);
/*!40000 ALTER TABLE `account_stats` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `accounts`
--

DROP TABLE IF EXISTS `accounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `accounts` (
  `acctid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `sex` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `specialty` int(4) unsigned NOT NULL DEFAULT '0',
  `race` char(3) NOT NULL DEFAULT '',
  `profession` smallint(5) NOT NULL DEFAULT '0',
  `experience` mediumint(8) NOT NULL DEFAULT '0',
  `charisma` int(11) unsigned NOT NULL DEFAULT '0',
  `seenlover` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `gold` int(11) NOT NULL,
  `weapon` varchar(255) NOT NULL DEFAULT 'Fäuste',
  `rename_weapons` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `armor` varchar(255) NOT NULL DEFAULT 'Straßenkleidung',
  `marks` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `seenmaster` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `level` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `defence` smallint(5) unsigned NOT NULL DEFAULT '1',
  `attack` smallint(5) unsigned NOT NULL DEFAULT '1',
  `alive` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `goldinbank` int(11) NOT NULL DEFAULT '0',
  `marriedto` int(11) unsigned NOT NULL DEFAULT '0',
  `spirits` tinyint(4) NOT NULL DEFAULT '0',
  `laston` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `hitpoints` mediumint(8) unsigned NOT NULL DEFAULT '10',
  `maxhitpoints` mediumint(8) unsigned NOT NULL DEFAULT '10',
  `gems` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `gemsinbank` smallint(6) NOT NULL DEFAULT '0',
  `weaponvalue` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `armorvalue` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `location` smallint(5) unsigned NOT NULL DEFAULT '0',
  `turns` smallint(5) unsigned NOT NULL DEFAULT '10',
  `title` varchar(40) NOT NULL DEFAULT '',
  `password` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '',
  `badguy` text NOT NULL,
  `allowednavs` mediumtext NOT NULL,
  `output` mediumtext NOT NULL,
  `loggedin` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `resurrections` smallint(5) unsigned NOT NULL DEFAULT '0',
  `superuser` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `superuser_id_switch` int(3) unsigned NOT NULL DEFAULT '0',
  `surights` text NOT NULL,
  `weapondmg` smallint(6) unsigned NOT NULL DEFAULT '0',
  `armordef` smallint(6) unsigned NOT NULL DEFAULT '0',
  `age` smallint(5) unsigned NOT NULL DEFAULT '0',
  `charm` mediumint(8) unsigned NOT NULL DEFAULT '10',
  `specialinc` varchar(50) NOT NULL DEFAULT '',
  `specialmisc` text NOT NULL,
  `login` varchar(50) NOT NULL DEFAULT '',
  `lastmotd` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `lastmotc` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `playerfights` tinyint(3) unsigned NOT NULL DEFAULT '3',
  `lasthit` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `seendragon` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `dragonkills` smallint(5) unsigned NOT NULL DEFAULT '0',
  `drunkenness` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `locked` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `restorepage` varchar(128) DEFAULT '',
  `restatlocation` mediumint(3) unsigned NOT NULL DEFAULT '0',
  `hashorse` smallint(5) unsigned NOT NULL DEFAULT '0',
  `petid` int(10) unsigned NOT NULL DEFAULT '0',
  `petfeed` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `bufflist` text NOT NULL,
  `lastip` varchar(40) NOT NULL DEFAULT '',
  `uniqueid` varchar(32) DEFAULT NULL,
  `dragonpoints` text NOT NULL,
  `emailaddress` varchar(128) NOT NULL DEFAULT '',
  `emailvalidation` varchar(32) NOT NULL DEFAULT '',
  `prefs` text NOT NULL,
  `pvpflag` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `hauntpoints` smallint(5) unsigned NOT NULL DEFAULT '0',
  `soulpoints` smallint(5) unsigned NOT NULL DEFAULT '0',
  `gravefights` smallint(5) unsigned NOT NULL DEFAULT '0',
  `deathpower` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `recentcomments` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `donation` mediumint(8) NOT NULL,
  `donationspent` mediumint(8) NOT NULL,
  `donationconfig` text NOT NULL,
  `banoverride` tinyint(4) unsigned DEFAULT '0',
  `buffbackup` text NOT NULL,
  `bounty` smallint(5) unsigned NOT NULL DEFAULT '0',
  `pk` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `dragonage` smallint(5) unsigned NOT NULL DEFAULT '0',
  `bounties` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `fedmount` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `house` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `housekey` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `punch` mediumint(8) unsigned NOT NULL DEFAULT '1',
  `battlepoints` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `mazeturn` smallint(5) unsigned NOT NULL DEFAULT '0',
  `pqtemp` text NOT NULL,
  `steuertage` tinyint(3) unsigned NOT NULL DEFAULT '4',
  `reputation` tinyint(4) NOT NULL DEFAULT '0',
  `activated` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `imprisoned` tinyint(6) NOT NULL DEFAULT '0',
  `prangerdays` int(4) unsigned NOT NULL DEFAULT '0',
  `castleturns` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `guildid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `guildfunc` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `guildrank` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `specialtyuses` text NOT NULL,
  `expedition` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `DDL_location` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `ddl_rank` int(3) unsigned NOT NULL DEFAULT '0',
  `balance_forest` tinyint(4) NOT NULL DEFAULT '0',
  `balance_dragon` tinyint(4) NOT NULL DEFAULT '0',
  `maze_visited` varchar(144) NOT NULL DEFAULT '',
  `daysinjail` int(11) NOT NULL DEFAULT '0',
  `chat_section` varchar(64) DEFAULT NULL,
  `chat_status` int(4) NOT NULL DEFAULT '1',
  `quiz_order` varchar(255) DEFAULT NULL,
  `httpreq_flag` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `plu_mi` text,
  `conf_bits` int(11) NOT NULL DEFAULT '0',
  `newday_bits` int(10) unsigned NOT NULL DEFAULT '0',
  `exchangequest` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `pvpsperre` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `kleidung` varchar(255) NOT NULL,
  `nohof` tinyint(1) NOT NULL DEFAULT '0',
  `quests_temp` text NOT NULL,
  `newmail` tinyint(4) NOT NULL DEFAULT '0',
  `calender_last` datetime NOT NULL,
  PRIMARY KEY (`acctid`),
  KEY `name` (`name`),
  KEY `level` (`level`),
  KEY `login` (`login`),
  KEY `alive` (`alive`),
  KEY `laston` (`laston`),
  KEY `lasthit` (`lasthit`),
  KEY `emailaddress` (`emailaddress`),
  KEY `locked` (`locked`),
  KEY `activated` (`activated`),
  KEY `guildid` (`guildid`),
  KEY `online` (`loggedin`,`laston`,`activated`),
  KEY `lastip` (`lastip`),
  KEY `uniqueid` (`uniqueid`),
  KEY `newmail` (`newmail`),
  KEY `nohof` (`nohof`),
  KEY `bounty` (`bounty`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `accounts`
--

LOCK TABLES `accounts` WRITE;
/*!40000 ALTER TABLE `accounts` DISABLE KEYS */;
INSERT INTO `accounts` VALUES (1,'Fremder Admin`0',0,17,'men',0,0,0,0,0,'Fäuste',0,'Straßenkleidung',0,0,1,10,10,1,0,0,-1,'2016-02-01 00:00:00',10,10,0,0,0,0,0,0,'Fremder','$2y$10$9rKQ0sOD.DyngvXfCZvwtem0qlnODoGdAcshq/mFwzLupS4J2QOk.','','','',0,1,1,0,'{}',0,0,0,10,'','','Admin','2016-02-01 00:00:00','2016-02-01 00:00:00',2,'2016-02-01 00:00:00',0,0,0,0,'village.php',0,0,0,'0000-00-00 00:00:00','{}','','','{}','','','{}','0000-00-00 00:00:00',0,55,10,0,'2016-02-01 00:00:00',0,0,'{}',0,'',0,0,0,0,0,0,0,0,0,0,'',4,45,0,0,0,1,0,1,0,'{\"darkartuses\":0,\"magicuses\":0,\"thieveryuses\":0,\"heroismuses\":0,\"juggleryuses\":0,\"transmutationuses\":0,\"druiduses\":0,\"cattinessuses\":0,\"wisdomuses\":0,\"elementaluses\":0,\"nothingspecialuses\":0,\"healinguses\":0,\"rangeduses\":0,\"meleeuses\":0,\"unarmeduses\":0,\"illusionuses\":0,\"whitemagicuses\":1}',0,1,0,0,0,'',0,'',0,NULL,0,'',0,0,0,0,'',0,'',1,'2016-02-01 00:00:00');
/*!40000 ALTER TABLE `accounts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `appoencode`
--

DROP TABLE IF EXISTS `appoencode`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `appoencode` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `code` char(1) NOT NULL DEFAULT '',
  `color` varchar(6) DEFAULT NULL,
  `tag` varchar(20) DEFAULT NULL,
  `style` tinytext,
  `allowed` enum('0','1') NOT NULL DEFAULT '1',
  `active` enum('0','1') NOT NULL DEFAULT '1',
  `listorder` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `active` (`active`),
  KEY `listorder` (`listorder`),
  KEY `allowed` (`allowed`),
  KEY `code` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=181 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `appoencode`
--

LOCK TABLES `appoencode` WRITE;
/*!40000 ALTER TABLE `appoencode` DISABLE KEYS */;
INSERT INTO `appoencode` VALUES (1,'1','0000B0',NULL,NULL,'1','1',38),(2,'2','00B000',NULL,NULL,'1','1',12),(3,'3','00B0B0',NULL,NULL,'1','1',6),(4,'4','B00000',NULL,NULL,'1','1',71),(5,'5','B000CC',NULL,NULL,'1','1',53),(6,'6','B0B000',NULL,NULL,'1','1',84),(7,'7','B0B0B0',NULL,NULL,'1','1',106),(8,'8','DDFFBB',NULL,NULL,'1','1',22),(9,'9','0070FF',NULL,NULL,'1','1',34),(10,'!','0000FF',NULL,NULL,'1','1',37),(11,'@','00FF00',NULL,NULL,'1','1',15),(12,'#','00FFFF',NULL,NULL,'1','1',29),(13,'$','FF0000',NULL,NULL,'1','1',72),(14,'%','FF00FF',NULL,NULL,'1','1',55),(15,'^','FFFF00',NULL,NULL,'1','1',82),(16,'&','FFFFFF',NULL,NULL,'1','1',109),(17,')','999999',NULL,NULL,'1','1',105),(18,'~','222222',NULL,NULL,'0','1',118),(19,'Q','FF6600',NULL,NULL,'1','1',77),(20,'q','FF9900',NULL,NULL,'1','1',79),(21,'r','EEBBEE',NULL,NULL,'1','1',62),(22,'R','DE89DE',NULL,NULL,'1','1',58),(23,'V','9A5BEE',NULL,NULL,'1','1',46),(24,'v','AABBEE',NULL,NULL,'1','1',61),(25,'g','AAFF99',NULL,NULL,'1','1',20),(26,'G','7EF77B',NULL,NULL,'1','1',17),(27,'T','6b563f',NULL,NULL,'1','1',99),(28,'t','F8DB83',NULL,NULL,'1','1',89),(29,'c',NULL,'center',NULL,'1','1',122),(30,'H',NULL,'span','class=\'navhi\'','0','1',123),(31,'b',NULL,'strong',NULL,'1','1',119),(32,'Ã',NULL,'pre',NULL,'0','0',124),(33,'i',NULL,'i',NULL,'1','1',120),(34,'n',NULL,'br /',NULL,'1','1',121),(35,'w','00BFFF',NULL,NULL,'1','1',30),(36,'f','E0FFFF',NULL,NULL,'1','1',23),(37,'d','FF7F24',NULL,NULL,'1','1',78),(38,'e','BEBEBE',NULL,NULL,'1','1',107),(39,'s','DEDEDE',NULL,NULL,'1','1',108),(40,'a','CAFF70',NULL,NULL,'1','1',18),(41,'p','B4EEB4',NULL,NULL,'1','1',21),(42,'k','00FA9A',NULL,NULL,'1','1',4),(43,'l','8B2252',NULL,NULL,'1','1',67),(44,'m','663300',NULL,NULL,'1','1',95),(45,'W','333366',NULL,NULL,'1','1',42),(46,'x','FF6EB4',NULL,NULL,'1','1',64),(50,'y','FEFEBB',NULL,NULL,'1','1',88),(52,'M','68228B',NULL,NULL,'1','1',51),(53,'D','FB4D00',NULL,NULL,'1','1',76),(54,'I','FFBF5F',NULL,NULL,'1','1',90),(55,'/','FFFF81',NULL,NULL,'1','1',86),(56,'J','007F00',NULL,NULL,'1','1',11),(57,'j','1DD000',NULL,NULL,'1','1',14),(59,'P','00B771',NULL,NULL,'1','1',3),(60,'F','82E0FF',NULL,NULL,'1','1',26),(61,'*','AAFFFF',NULL,NULL,'1','1',24),(62,'§','007B7B',NULL,NULL,'1','1',7),(63,'=','C600C6',NULL,NULL,'1','1',54),(64,'?','FF5BFF',NULL,NULL,'1','1',56),(66,'E','C7A9EE',NULL,NULL,'1','1',60),(67,'K','6E8FEE',NULL,NULL,'1','1',32),(68,'S','4D3E2D',NULL,NULL,'1','1',100),(69,'Y','A07E4E',NULL,NULL,'1','1',97),(70,'L','A0366B',NULL,NULL,'1','1',66),(71,'X','B54973',NULL,NULL,'1','1',65),(72,'(','676767',NULL,NULL,'1','1',104),(73,'Z','6D3A3A',NULL,NULL,'1','1',116),(74,'z','AD5C5C',NULL,NULL,'1','1',114),(75,'O','F17777',NULL,NULL,'1','1',113),(76,'o','F1AEAE',NULL,NULL,'1','1',112),(77,'U','9D620E',NULL,NULL,'1','1',94),(78,'u','C88A32',NULL,NULL,'1','1',92),(92,'A','800000',NULL,NULL,'1','1',70),(98,'C','5164F2',NULL,NULL,'1','1',33),(99,']','5E4141',NULL,NULL,'1','1',117),(100,'[','A58686',NULL,NULL,'1','1',111),(101,'h','72749D',NULL,NULL,'1','1',44),(103,'_','B89AA3',NULL,NULL,'1','1',110),(104,'{','00A8FF',NULL,NULL,'1','1',31),(105,'}','E09853',NULL,NULL,'1','1',91),(127,',','660000',NULL,NULL,'1','1',69),(128,'N','333333',NULL,NULL,'1','1',101),(129,'B','30495F',NULL,NULL,'1','1',8),(131,';','775532',NULL,NULL,'1','1',98),(132,':','774433',NULL,NULL,'1','1',115),(133,'.','779988',NULL,NULL,'1','1',1),(139,'|','698B6A',NULL,NULL,'1','1',2),(143,'-','FFFF33',NULL,NULL,'1','1',83),(145,'Á','203321',NULL,NULL,'1','1',9),(146,'°','33334C',NULL,NULL,'1','1',43),(147,'ö','404040',NULL,NULL,'1','1',103),(148,'á','00D900',NULL,NULL,'1','1',13),(149,'Ì','40454d',NULL,NULL,'1','1',102),(150,'À','315902',NULL,NULL,'1','1',10),(151,'ì','B58F58',NULL,NULL,'1','1',93),(152,'Î','4c2600',NULL,NULL,'1','1',96),(153,'à','7FFF00',NULL,NULL,'1','1',16),(154,'î','d1d1a5',NULL,NULL,'1','1',87),(155,'Ó','8e8e01',NULL,NULL,'1','1',85),(156,'Ä','5CE9FF',NULL,NULL,'1','1',27),(157,'ó','FFCC00',NULL,NULL,'1','1',81),(158,'ä','00BFBF',NULL,NULL,'1','1',5),(159,'Ò','D5B225',NULL,NULL,'1','1',80),(160,'Â','C6F287',NULL,NULL,'1','1',19),(161,'ò','ff4c2c',NULL,NULL,'1','1',75),(162,'Ö','FF3333',NULL,NULL,'1','1',74),(163,'â','80FFFF',NULL,NULL,'1','1',25),(164,'Ô','FF2E1F',NULL,NULL,'1','1',73),(165,'É','FF9ED2',NULL,NULL,'1','1',63),(166,'ô','31110d',NULL,NULL,'1','1',68),(167,'é','0852FF',NULL,NULL,'1','1',35),(168,'È','03DDFF',NULL,NULL,'1','1',28),(169,'è','004CAC',NULL,NULL,'1','1',36),(170,'Ê','000087',NULL,NULL,'1','1',39),(171,'ê','000057',NULL,NULL,'1','1',40),(172,'Ú','F295F2',NULL,NULL,'1','1',59),(173,'ú','FF80FF',NULL,NULL,'1','1',57),(174,'Ù','840099',NULL,NULL,'1','1',52),(175,'ù','4e1968',NULL,NULL,'1','1',50),(176,'ü','4d2e77',NULL,NULL,'1','1',49),(177,'Û','503e68',NULL,NULL,'1','1',48),(178,'û','7344b2',NULL,NULL,'1','1',47),(179,'Í','28284f',NULL,NULL,'1','1',41),(180,'í','9b29ff',NULL,NULL,'1','1',45);
/*!40000 ALTER TABLE `appoencode` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `armor`
--

DROP TABLE IF EXISTS `armor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `armor` (
  `armorid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `armorname` varchar(128) DEFAULT NULL,
  `value` int(11) NOT NULL DEFAULT '0',
  `defense` int(11) NOT NULL DEFAULT '1',
  `level` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`armorid`),
  KEY `level` (`level`)
) ENGINE=InnoDB AUTO_INCREMENT=227 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `armor`
--

LOCK TABLES `armor` WRITE;
/*!40000 ALTER TABLE `armor` DISABLE KEYS */;
INSERT INTO `armor` VALUES (1,'Rüschenunterhosen',48,1,0),(2,'Flanell Pyjama',225,2,0),(3,'Einfache lange Unterhosen',585,3,0),(4,'Einfaches Unterhemd',990,4,0),(5,'Gestrickte Socken',1575,5,0),(6,'Gestrickte Handschuhe',2250,6,0),(7,'Alte Lederlatschen',2790,7,0),(8,'Einfache Hosen',3420,8,0),(9,'Einfache Jacke',4230,9,0),(10,'Zigeunerumhang',5040,10,0),(11,'Alte Lederkappe',5850,11,0),(12,'Alter Lederarmschutz',6840,12,0),(13,'Regenschirm',8010,13,0),(14,'Alte Lederhosen',9000,14,0),(15,'Alte Lederjacke',10350,15,0),(16,'Flip-Flops',48,1,1),(17,'Badeanzug und Handtuch',225,2,1),(18,'Baumwollunterhemd',585,3,1),(19,'Baumwollsocken',990,4,1),(20,'Baumwollhandschuhe',1575,5,1),(21,'Lederstiefel',2250,6,1),(22,'Lederkappe',2790,7,1),(23,'Lederarmschützer',3420,8,1),(24,'Lederleggings',4230,9,1),(25,'Ledermantel',5040,10,1),(26,'Ledercape mit Kapuze',5850,11,1),(27,'Hirschlederleggings',6840,12,1),(28,'Hirschledergürtel',8010,13,1),(29,'Hirschledermantel',9000,14,1),(30,'Kleines Rohlederschild',10350,15,1),(31,'Arbeitsstiefel',48,1,2),(32,'Latzhose',225,2,2),(33,'Feste Lederhandschuhe',585,3,2),(34,'Feste Lederarmschützer',990,4,2),(35,'Feste Lederstiefel',1575,5,2),(36,'Stabiler Lederhelm',2250,6,2),(37,'Robuste Lederhosen',2790,7,2),(38,'Schwerer Ledermantel',3420,8,2),(39,'Schwerer Lederumhang',4230,9,2),(40,'Holzfällerhelm',5040,10,2),(41,'Holzfällerhandschuh',5850,11,2),(42,'Holzfällerarmschutz',6840,12,2),(43,'Holzfällerbeinschienen',8010,13,2),(44,'Drachenschild der Holzfäller',9000,14,2),(45,'Holzfällerumhang',10350,15,2),(46,'Kleines Wolfsfell',48,1,3),(47,'Wolfsfell-Lendenschurz',225,2,3),(48,'Wolfsfellhandschuhe',585,3,3),(49,'Wolfsfellstiefel',990,4,3),(50,'Wolfsfellarmschutz',1575,5,3),(51,'Wolfsfellhosen',2250,6,3),(52,'Wolfsfellumhang',2790,7,3),(53,'Wolfsfellkappe',3420,8,3),(54,'Wolfmasters Armschutz',4230,9,3),(55,'Wolfmasters Handschuh',5040,10,3),(56,'Wolfmasters Helm',5850,11,3),(57,'Wolfmasters Leggings',6840,12,3),(58,'Wolfmasters Lederwams',8010,13,3),(59,'Schild des Wolfmasters',9000,14,3),(60,'Wolfschutzcape',10350,15,3),(61,'Halskette',48,1,4),(62,'Eiserner Armreif',225,2,4),(63,'Nietenbesetzter Lederhelm',585,3,4),(64,'Nietenbesetzter Handschuh',990,4,4),(65,'Lederstiefel mit Stahlkappen',1575,5,4),(66,'Nietenbesetzte Lederleggings',2250,6,4),(67,'Nietenbesetzte Tunika',2790,7,4),(68,'Umhang des Gerbers',3420,8,4),(69,'Rostige Kettenhaube',4230,9,4),(70,'Rostige Kettenhandschuhe',5040,10,4),(71,'Rostiger Kettenarmschutz',5850,11,4),(72,'Rostige Kettenstiefel',6840,12,4),(73,'Rostige Kettenbeinschienen',8010,13,4),(74,'Rostiges Eisenschild',9000,14,4),(75,'Rostiges Kettenhemd',10350,15,4),(76,'Häschenpantoffeln',48,1,5),(77,'Fleezepyjama',225,2,5),(78,'Bequeme Unterwäsche aus Leder',585,3,5),(79,'Schwere Kettenhaube',990,4,5),(80,'Schwerer Handschuh',1575,5,5),(81,'Schwerer Kettenarmschutz',2250,6,5),(82,'Schwere Kettenstiefel',2790,7,5),(83,'Schwere Kettenbeinschienen',3420,8,5),(84,'Schwere Kettentunika',4230,9,5),(85,'Armschutz für Drachenkrieger',5040,10,5),(86,'Handschuh für Drachenkrieger',5850,11,5),(87,'Stiefel für Drachenkrieger',6840,12,5),(88,'Beinschienen für Drachenkrieger',8010,13,5),(89,'Schild für Drachenkrieger',9000,14,5),(90,'Brustpanzer für Drachenkrieger',10350,15,5),(91,'Derbe Hosen',48,1,6),(92,'Baumwollhemd',225,2,6),(93,'Guter Bronzehelm',585,3,6),(94,'Guter Panzerhandschuh',990,4,6),(95,'Guter Bronzearmschutz',1575,5,6),(96,'Gute Bronzestiefel',2250,6,6),(97,'Guter Bronzebeinschutz',2790,7,6),(98,'Guter Bronzebrustpanzer',3420,8,6),(99,'Verzauberter Bronzehelm',4230,9,6),(100,'Verzauberter Bronzehandschuh',5040,10,6),(101,'Verzauberter Bronzearmschutz',5850,11,6),(102,'Verzauberte Bronzestiefel',6840,12,6),(103,'Verzauberte Bronzebeinschienen',8010,13,6),(104,'Verzauberter Bronzebrustpanzer',9000,14,6),(105,'Schützendes Einhornfell',10350,15,6),(106,'Breiter Ledergürtel',48,1,7),(107,'Zipfelmütze',225,2,7),(108,'Perfekter Stahlhelm',585,3,7),(109,'Perfekte Stahlhandschuhe',990,4,7),(110,'Perfekte Stahlstiefel',1575,5,7),(111,'Perfekte Armschützer aus Stahl',2250,6,7),(112,'Perfekte Beinschienen aus Stahl',2790,7,7),(113,'Perfektes Brustschild aus Stahl',3420,8,7),(114,'Umhang aus Greif-Federn',4230,9,7),(115,'Zwergen Kettenhaube',5040,10,7),(116,'Zwergen Panzerhandschuhe',5850,11,7),(117,'Zwergen Kettenstiefel',6840,12,7),(118,'Zwergen Kettenarmschützer',8010,13,7),(119,'Zwergen Kettenbeinschützer',9000,14,7),(120,'Zwergen Kettenbrustschutz',10350,15,7),(121,'Feigenblatt',48,1,8),(122,'Kilt',225,2,8),(123,'Majestätischer Goldhelm',585,3,8),(124,'Majestätische Goldhandschuhe',990,4,8),(125,'Majestätische Goldstiefel',1575,5,8),(126,'Majestätische goldene Armschützer',2250,6,8),(127,'Majestätische goldene Beinschienen',2790,7,8),(128,'Majestätisches goldenes Brustschild',3420,8,8),(129,'Majestätisches Goldschild',4230,9,8),(130,'Goldverzierter Umhang',5040,10,8),(131,'Verzauberter Rubinring',5850,11,8),(132,'Verzauberter Saphirring',6840,12,8),(133,'Verzauberter Jadering',8010,13,8),(134,'Verzauberter Amethystring',9000,14,8),(135,'Verzauberter Diamantring',10350,15,8),(136,'Silberner Knopf',48,1,9),(137,'Nachtgewand aus Elfenseide',225,2,9),(138,'Handschuhe aus Elfenseide',585,3,9),(139,'Hausschuhe aus Elfenseide',990,4,9),(140,'Stirnband aus Elfenseide',1575,5,9),(141,'Leggings aus Elfenseide',2250,6,9),(142,'Tunika aus Elfenseide',2790,7,9),(143,'Umhang aus Elfenseide',3420,8,9),(144,'Ring der Nacht',4230,9,9),(145,'Ring des Tages',5040,10,9),(146,'Ring der Einsamkeit',5850,11,9),(147,'Ring des Friedens',6840,12,9),(148,'Ring des Mutes',8010,13,9),(149,'Ring der Keuschheit',9000,14,9),(150,'Der eine Ring',10350,15,9),(151,'Pegasus\' Tarnumhang',5040,10,10),(152,'Pegasus\' Brustschild',4230,9,10),(153,'Pegasus\' Beinschützer',3420,8,10),(154,'Pegasus\' bessere Stiefel',2790,7,10),(155,'Pegasus\' Stiefel',2250,6,10),(156,'Pegasus\' Armschützer',1575,5,10),(157,'Pegasus\' Panzerhandschuhe',990,4,10),(158,'Pegasus\' Helm',585,3,10),(159,'Pegasus\' Schuhe',225,2,10),(160,'Freizeitanzug',48,1,10),(161,'Pegasus\' Federschmuck',5850,11,10),(162,'Pegasus\' Federgürtel',6840,12,10),(163,'Pegasus\' geschmücktes Bild',8010,13,10),(164,'Pegasus\' geschmückter Federring',9000,14,10),(165,'Pegasus\' geschmückte Krone',10350,15,10),(166,'Neue Klamotten',48,1,11),(167,'Hühnerkostüm',225,2,11),(168,'Fehdehandschuh der Gnade',585,3,11),(169,'Armschützer der Schönheit',990,4,11),(170,'Helm der Heilung',1575,5,11),(171,'Beinschützer des Glücks',2250,6,11),(172,'Stiefel der Helden',2790,7,11),(173,'Tunika der Toleranz',3420,8,11),(174,'Deckmantel der Zuversicht',4230,9,11),(175,'Ring der Rechtschaffenheit',5040,10,11),(176,'Nackenschutz der Selbstliebe',5850,11,11),(177,'Anhänger der Macht',6840,12,11),(178,'Brustschutz der Mildtätigkeit',8010,13,11),(179,'Schild der Überlegenheit',9000,14,11),(180,'Zepter der Stärke',10350,15,11),(181,'Drachenhaut Lederhelm',48,1,12),(182,'Drachenhaut  Beinschutz',225,2,12),(183,'Drachenhaut  Lederstiefel',585,3,12),(184,'Drachenhaut  Lederarmschutz',990,4,12),(185,'Drachenhaut  Lederleggings',1575,5,12),(186,'Drachenhaut  Ledertunika',2250,6,12),(187,'Drachenhaut Ledermantel',2790,7,12),(188,'Drachenhorn Helm',3420,8,12),(189,'Drachenhorn Beinschützer',4230,9,12),(190,'Drachenhorn Stiefel',5040,10,12),(191,'Drachenhorn Armschützer',5850,11,12),(192,'Drachenhorn Hosenträger',6840,12,12),(193,'Drachenhorn Brustschild',8010,13,12),(194,'Drachenhorn Tarnmantel',9000,14,12),(195,'Drachenkrallen Amulett',10350,15,12),(197,'Srosh Handschuhe',48,1,13),(198,'Asman Kappe',225,2,13),(199,'Rustam Schuhe',585,3,13),(200,'Yasht Schulterschutz',990,4,13),(201,'Tasan Kettenhemd',1575,5,13),(202,'Mainyu Brustpanzer',2250,6,13),(203,'Airyaman Armschienen',2790,7,13),(204,'Spenta Beinschienen',3420,8,13),(205,'Allatum Ring',4230,9,13),(206,'Natat Zauber',5040,10,13),(207,'Apaosa Helm',5850,11,13),(208,'Vahishta Gürtel',6840,12,13),(209,'Ereta Brosche',8010,13,13),(210,'Apam Amulett',9000,14,13),(211,'Frahashis Eisenhaut',10350,15,13),(212,'Yazata Stiefel',48,1,14),(213,'Yazata Schulterschutz',225,2,14),(214,'Yazata Lederhemd',585,3,14),(215,'Yazata Kettenhemd',990,4,14),(216,'Yazata Harnisch',1575,5,14),(217,'Yazata Zauber',2250,6,14),(218,'Yazata Ring',2790,7,14),(219,'Yazata Armschienen',3420,8,14),(220,'Yazata Beinschienen',4230,9,14),(221,'Yazata Helm',5040,10,14),(222,'Yazata Amulett',5850,11,14),(223,'Yazata Brustschild',6840,12,14),(224,'Yazata Tunika',8010,13,14),(225,'Yazata Mantel',9000,14,14),(226,'Yazata Brustpanzer',10350,15,14);
/*!40000 ALTER TABLE `armor` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bans`
--

DROP TABLE IF EXISTS `bans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bans` (
  `ipfilter` varchar(15) NOT NULL DEFAULT '',
  `uniqueid` varchar(32) NOT NULL DEFAULT '',
  `mailfilter` varchar(120) NOT NULL DEFAULT '',
  `loginfilter` varchar(50) NOT NULL DEFAULT '',
  `banexpire` date DEFAULT NULL,
  `banreason` text NOT NULL,
  `last_try` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`),
  KEY `ALL_VALUES` (`ipfilter`,`uniqueid`,`mailfilter`,`banexpire`),
  KEY `ipfilter` (`ipfilter`),
  KEY `uniqueid` (`uniqueid`),
  KEY `last_try` (`last_try`),
  KEY `banexpire` (`banexpire`),
  KEY `loginfilter_2` (`loginfilter`),
  KEY `mailfilter_2` (`mailfilter`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bans`
--

LOCK TABLES `bans` WRITE;
/*!40000 ALTER TABLE `bans` DISABLE KEYS */;
/*!40000 ALTER TABLE `bans` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bio_guestbook`
--

DROP TABLE IF EXISTS `bio_guestbook`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bio_guestbook` (
  `id` int(11) UNSIGNED NOT NULL,
  `owner` int(255) NOT NULL,
  `acctid` int(255) NOT NULL,
  `text` text NOT NULL,
  `date` datetime NOT NULL,
  `ip` varchar(255) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `owner` (`owner`),
  KEY `acctid` (`acctid`),
  KEY `date` (`date`),
  KEY `deleted` (`deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bio_guestbook`
--

LOCK TABLES `bio_guestbook` WRITE;
/*!40000 ALTER TABLE `bio_guestbook` DISABLE KEYS */;
/*!40000 ALTER TABLE `bio_guestbook` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `blacklist`
--

DROP TABLE IF EXISTS `blacklist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `blacklist` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `value` varchar(255) NOT NULL DEFAULT '',
  `remarks` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `type_value` (`type`,`value`(10))
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='Speichert unerwünschte Namensbestandteile bzw. Emailadresse';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blacklist`
--

LOCK TABLES `blacklist` WRITE;
/*!40000 ALTER TABLE `blacklist` DISABLE KEYS */;
INSERT INTO `blacklist` VALUES (1,3,'arschloch','');
/*!40000 ALTER TABLE `blacklist` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `boards`
--

DROP TABLE IF EXISTS `boards`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `boards` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `message` text NOT NULL,
  `author` int(10) unsigned NOT NULL DEFAULT '0',
  `postdate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `section` varchar(30) NOT NULL,
  `expire` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `section` (`section`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Enthält Nachrichten an schwarzen Brettern';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `boards`
--

LOCK TABLES `boards` WRITE;
/*!40000 ALTER TABLE `boards` DISABLE KEYS */;
/*!40000 ALTER TABLE `boards` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bookmarks`
--

DROP TABLE IF EXISTS `bookmarks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bookmarks` (
  `id` int(255) unsigned NOT NULL AUTO_INCREMENT,
  `acctid` int(255) NOT NULL,
  `section` varchar(255) NOT NULL,
  `lasttime` datetime NOT NULL,
  `uptime` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `acctid` (`acctid`),
  KEY `section` (`section`),
  KEY `lasttime` (`lasttime`),
  KEY `uptime` (`uptime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bookmarks`
--

LOCK TABLES `bookmarks` WRITE;
/*!40000 ALTER TABLE `bookmarks` DISABLE KEYS */;
/*!40000 ALTER TABLE `bookmarks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bookmarks_name`
--

DROP TABLE IF EXISTS `bookmarks_name`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bookmarks_name` (
  `section` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`section`),
  KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bookmarks_name`
--

LOCK TABLES `bookmarks_name` WRITE;
/*!40000 ALTER TABLE `bookmarks_name` DISABLE KEYS */;
/*!40000 ALTER TABLE `bookmarks_name` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `calendar_events`
--

DROP TABLE IF EXISTS `calendar_events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `calendar_events` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `acctid` int(255) NOT NULL,
  `private` tinyint(1) NOT NULL,
  `groupid` int(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `color` varchar(7) NOT NULL,
  `textColor` varchar(7) NOT NULL,
  `changed` datetime NOT NULL,
  `recuring` int(255) NOT NULL,
  `next` datetime NOT NULL,
  `notified` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `acctid` (`acctid`),
  KEY `private` (`private`),
  KEY `groupid` (`groupid`),
  KEY `start_date` (`start_date`),
  KEY `end_date` (`end_date`),
  KEY `changed` (`changed`),
  KEY `recuring` (`recuring`),
  KEY `next` (`next`),
  KEY `title` (`title`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `calendar_events`
--

LOCK TABLES `calendar_events` WRITE;
/*!40000 ALTER TABLE `calendar_events` DISABLE KEYS */;
/*!40000 ALTER TABLE `calendar_events` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `calendar_events_user`
--

DROP TABLE IF EXISTS `calendar_events_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `calendar_events_user` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `eventid` int(255) NOT NULL,
  `acctid` int(255) NOT NULL,
  `notified` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `notified` (`notified`),
  KEY `eventid` (`eventid`),
  KEY `acctid` (`acctid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `calendar_events_user`
--

LOCK TABLES `calendar_events_user` WRITE;
/*!40000 ALTER TABLE `calendar_events_user` DISABLE KEYS */;
/*!40000 ALTER TABLE `calendar_events_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `calendar_groups`
--

DROP TABLE IF EXISTS `calendar_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `calendar_groups` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `owner` int(255) NOT NULL,
  `type` tinyint(1) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `owner` (`owner`),
  KEY `type` (`type`),
  KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `calendar_groups`
--

LOCK TABLES `calendar_groups` WRITE;
/*!40000 ALTER TABLE `calendar_groups` DISABLE KEYS */;
/*!40000 ALTER TABLE `calendar_groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `calendar_groups_user`
--

DROP TABLE IF EXISTS `calendar_groups_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `calendar_groups_user` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `groupid` int(255) NOT NULL,
  `acctid` int(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `groupid` (`groupid`),
  KEY `acctid` (`acctid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `calendar_groups_user`
--

LOCK TABLES `calendar_groups_user` WRITE;
/*!40000 ALTER TABLE `calendar_groups_user` DISABLE KEYS */;
/*!40000 ALTER TABLE `calendar_groups_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cases`
--

DROP TABLE IF EXISTS `cases`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cases` (
  `newsid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `newstext` text NOT NULL,
  `accountid` int(11) unsigned NOT NULL DEFAULT '0',
  `judgeid` int(10) unsigned NOT NULL DEFAULT '0',
  `court` tinyint(4) NOT NULL DEFAULT '0',
  `persons` text NOT NULL,
  PRIMARY KEY (`newsid`),
  KEY `accountid` (`accountid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cases`
--

LOCK TABLES `cases` WRITE;
/*!40000 ALTER TABLE `cases` DISABLE KEYS */;
/*!40000 ALTER TABLE `cases` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `commentary`
--

DROP TABLE IF EXISTS `commentary`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `commentary` (
  `commentid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `section` varchar(64) DEFAULT NULL,
  `author` int(11) unsigned NOT NULL DEFAULT '0',
  `real_acctid` int(255) NOT NULL,
  `comment` text NOT NULL,
  `cache` text NOT NULL,
  `cached` tinyint(1) NOT NULL DEFAULT '0',
  `postdate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `editdate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `su_min` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `self` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `deleted_by` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `edited` int(11) unsigned DEFAULT NULL,
  `flags` int(10) unsigned DEFAULT '0',
  PRIMARY KEY (`commentid`),
  KEY `postdate` (`postdate`),
  KEY `section_deletedby` (`section`,`deleted_by`),
  KEY `section` (`section`),
  KEY `author` (`author`),
  KEY `edited` (`edited`),
  KEY `flags` (`flags`),
  KEY `self` (`self`),
  KEY `su_min` (`su_min`),
  KEY `editdate` (`editdate`),
  KEY `cached` (`cached`),
  KEY `acctid` (`real_acctid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `commentary`
--

LOCK TABLES `commentary` WRITE;
/*!40000 ALTER TABLE `commentary` DISABLE KEYS */;
/*!40000 ALTER TABLE `commentary` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `commentary_emotes`
--

DROP TABLE IF EXISTS `commentary_emotes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `commentary_emotes` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `regex` varchar(255) NOT NULL,
  `parse` text NOT NULL,
  `right` int(11) NOT NULL DEFAULT '0',
  `lgt` int(11) NOT NULL,
  `must` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT 'name',
  `issa` tinyint(1) NOT NULL DEFAULT '1',
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `type` varchar(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `type` (`type`),
  KEY `active` (`active`),
  KEY `issa` (`issa`),
  KEY `name` (`name`),
  KEY `must` (`must`),
  KEY `lgt` (`lgt`),
  KEY `right` (`right`),
  KEY `regex` (`regex`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `commentary_emotes`
--

LOCK TABLES `commentary_emotes` WRITE;
/*!40000 ALTER TABLE `commentary_emotes` DISABLE KEYS */;
INSERT INTO `commentary_emotes` VALUES (1,'/msg\\s*(.*)','`7<$m1>`0',48,4,'','',0,1,''),(2,':\\s*(.*)','<ecol> <$m1>',0,1,'','name',1,1,''),(3,'/mc([0-9])\\s*(.*)','<mc<$m1>ecol> <m_verb>: <mc<$m1>tcol><$m2>',0,4,'mc<$m1>','mc<$m1>',1,1,'mc<$m1>'),(4,'/mc([0-9]):\\s*(.*)','<mc<$m1>ecol> <$m2>',0,5,'mc<$m1>','mc<$m1>',1,1,'mc<$m1>'),(5,'/mc([0-9])s\\s*(.*)','<mc<$m1>ecol>s <$m2>',0,5,'mc<$m1>','mc<$m1>',1,1,'mc<$m1>'),(6,'(.*)','<ecol> <m_verb>: <tcol><$m1>',0,0,'','name',1,1,''),(7,'/k\\s*(.*)','<kecol> <m_verb>: <ktcol><$m1>',0,2,'kn','kn',1,1,'k'),(8,'/ks\\s*(.*)','<kecol>s <$m1>',0,3,'kn','kn',1,1,'k'),(9,'/k:\\s*(.*)','<kecol> <$m1>',0,3,'kn','kn',1,1,'k'),(10,'/[xX]\\s*(.*)','<ecol> <$m1>',0,2,'','[.]',0,1,''),(11,'::\\s*(.*)','<ecol> <$m1>',0,2,'','name',1,1,''),(12,'/me\\s*(.*)','<ecol> <$m1>',0,3,'','name',1,1,''),(13,'/ms\\s*(.*)','<ecol>s <$m1>',0,3,'','name',1,1,''),(14,'/mc([0-9][0-9])\\s*(.*)','<mc<$m1>ecol> <m_verb>: <mc<$m1>tcol><$m2>',0,5,'mc<$m1>','mc<$m1>',1,1,'mc<$m1>'),(15,'/mc([0-9][0-9]):\\s*(.*)','<mc<$m1>ecol> <$m2>',0,6,'mc<$m1>','mc<$m1>',1,1,'mc<$m1>'),(16,'/mc([0-9][0-9])s\\s*(.*)','<mc<$m1>ecol>s <$m2>',0,6,'mc<$m1>','mc<$m1>',1,1,'mc<$m1>');
/*!40000 ALTER TABLE `commentary_emotes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `creatures`
--

DROP TABLE IF EXISTS `creatures`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `creatures` (
  `creatureid` int(11) NOT NULL AUTO_INCREMENT,
  `creaturename` varchar(50) DEFAULT NULL,
  `creaturelevel` int(11) DEFAULT NULL,
  `creatureweapon` varchar(50) DEFAULT NULL,
  `creaturelose` varchar(120) DEFAULT NULL,
  `creaturewin` varchar(120) DEFAULT NULL,
  `creaturegold` int(11) DEFAULT NULL,
  `creatureexp` int(11) DEFAULT NULL,
  `creaturehealth` int(11) DEFAULT NULL,
  `creatureattack` int(11) DEFAULT NULL,
  `creaturedefense` int(11) DEFAULT NULL,
  `oldcreatureexp` int(11) DEFAULT NULL,
  `createdby` varchar(50) DEFAULT NULL,
  `location` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`creatureid`),
  KEY `creaturelevel` (`creaturelevel`),
  KEY `location` (`location`)
) ENGINE=InnoDB AUTO_INCREMENT=871 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `creatures`
--

LOCK TABLES `creatures` WRITE;
/*!40000 ALTER TABLE `creatures` DISABLE KEYS */;
INSERT INTO `creatures` VALUES (1,'Dornenstrauch',1,'Verdammte Dornen','Wie können unbewegliche Objekte nur so wehrhaft sein?',NULL,36,14,11,1,1,14,'anpera',0),(2,'Unverschämter Schüler',1,'Verschlissenes Buch','Du hast diesen Schüler zum permanenten Nachsitzen verurteilt.',NULL,36,14,10,1,1,14,'anpera',0),(3,'Einhornbaby',1,'Stumpfes Horn','Du fühlst dich wie ein Trottel dabei, etwas sooo süßes umgebracht zu haben.','',36,14,11,1,1,14,'anpera',0),(5,'Grunzendes Schwein',1,'Ringelschwänzchen','Mmmh, Schinken...',NULL,36,14,10,1,1,14,'anpera',0),(6,'Schlammloch',2,'Zähe Pampe','Es wird Stunden dauern, das wieder sauber zu bekommen...','',97,24,22,2,3,14,'anpera',0),(7,'Zwergenhaftes Krallenäffchen',2,'Stück Baumrinde','Der seltsame kleine Affe fällt vom Baum und bleibt regungslos liegen.',NULL,97,24,22,2,3,14,'anpera',0),(11,'Steintroll',2,'Mühlsteinmahlzähne','Dieser Troll war steinhart hässlich!','',97,24,22,2,3,14,'anpera',0),(12,'Natter',2,'Hypnotisierende Augen','Du unterbrichst den Blickkontakt, um dein eigenes Leben zu retten.','',97,24,22,2,3,14,'anpera',0),(13,'Windan, der Barbar',2,'Federspeer','Er war wirklich nur ein Federgewicht.','',97,24,22,2,3,14,'anpera',0),(14,'Junge Hexenschülerin',2,'Neu gelernte Zaubersprüche','Vielleicht hätte sie härter studieren sollen.',NULL,97,24,21,2,3,14,'anpera',0),(15,'24 Amseln',3,'Vogelkacke','Nur weg hier.',NULL,148,34,32,5,4,14,'anpera',0),(16,'Amazone',3,'Pfeil und Bogen','Das wunderhübsche Kriegermädchen hat zum ersten Mal einen Kampf verloren','',148,34,33,5,4,14,'anpera',0),(18,'Blütenzauberin',3,'Blumenstängel','Diese Blume riecht bald nicht mehr gut.','',148,34,33,5,4,14,'anpera',0),(20,'Chinesischer Koch',3,'Katzenfutter','Shin shang shong!','',148,34,33,5,4,14,'anpera',0),(21,'Waldkobold',3,'Nerviges Gekicher','Diesen Kobold hast du kleingehackt!',NULL,148,34,33,5,4,14,'anpera',0),(22,'Krümelmonster',3,'Riesige Kekskrümel','Kein Wunder, dass es so schwächlich war. Da ist ja kaum was im Magen gelandet.',NULL,148,34,33,5,4,14,'anpera',0),(27,'Goblinwächter',4,'Wächterhammer','harr harr harr',NULL,162,45,43,7,6,15,'anpera',0),(29,'Kleiner Drache',4,'Heiße Luft','Für einen Moment hast du geglaubt, DAS war der legendäre grüne Drache.','',162,45,44,7,6,15,'anpera',0),(32,'Ausgewachsenes Einhorn',5,'Mächtiges Horn','Ein Einhorn ist etwas Schönes, sogar wenn es tot ist.','',198,55,55,9,7,15,'anpera',0),(37,'Veteranenhahn',5,'Ohrenbetäubendes Krähen','Es ist so still plötzlich...','',198,55,55,9,7,15,'anpera',0),(38,'Gigantischer Wassergeist',5,'Sintflutartige Wolkenbrüche','Der Wassergeist wurde auf einen Frühlingsschauer reduziert!',NULL,198,55,53,9,7,15,'anpera',0),(39,'Küstenräuber',5,'Gestohlenes Schwert','Dein Gold ist mein!',NULL,198,55,53,9,7,15,'anpera',0),(49,'Strauchdieb',6,'Schwerer Ast','Komisch. Er hatte gar keinen Strauch dabei.',NULL,234,66,64,11,8,16,'anpera',0),(56,'Lehrer',7,'Hausaufgaben','Angriff: 1, Verteidigung: 1, so schauts aus!',NULL,268,77,74,13,10,17,'anpera',0),(57,'Spaßvogel',7,'Schallendes Lachen','hahaha hihihi hohoho','',268,77,77,13,10,17,'anpera',0),(58,'Untote Moorleiche',7,'Verrosteter Schlegel','Stirb an einem anderen Tag nochmal.',NULL,268,77,74,13,10,17,'anpera',0),(59,'Meuchelmörder',8,'Langdolch','Meuchelmörder ermordet. Gibt\'s ne Belohnung?',NULL,302,89,84,15,11,19,'anpera',0),(78,'Huhn',10,'Spitzer Schnabel','Das Glück lächelt auf dich herab - du wirst heute gut essen.',NULL,369,114,110,19,14,24,'anpera',0),(79,'Bogenschütze',10,'Tödliches Zielen','Er brauchte so lange zum Zielen, dass du zu ihm liefst und ihn einfach in den Hintern getreten hast.',NULL,369,114,105,19,14,24,'anpera',0),(80,'Kampfhund',10,'Eisenbeschlagene Zähne','Das wäre ein prächtiges Exemplar für Hundekämpfe gewesen. Schade drum.',NULL,369,114,105,19,14,24,'anpera',0),(86,'Dein Spiegelbild im Wasser',11,'- deine Waffe','Wie dumm muss man sein, um sein eigenes Spiegelbild anzugreifen .... und zu gewinnen?',NULL,402,127,115,21,15,27,'anpera',0),(89,'Heulsuse',11,'Überflutung','Einen Schwamm! Ein Königreich für einen Schwamm!',NULL,402,127,115,21,15,27,'anpera',0),(90,'Soldat der Stadt Eythgim',11,'Kriegsschrei','Eythgim scheint einen persönlichen Krieg mit dir zu führen.',NULL,402,127,115,21,15,27,'anpera',0),(91,'Racheengel',11,'Flammenschwert','Leben und sterben lassen. Das ist meine Rache!',NULL,402,127,115,21,15,27,'anpera',1),(95,'Magischer Spiegel',12,'Schmeichelnde Bemerkungen','Spieglein, Spieglein auf dem Boden, du gehörst jetzt zu den Toten.',NULL,435,141,125,23,17,31,'anpera',0),(96,'Wütender Oger',12,'Ausgerissener Baum','Nu bist du Baumfutter',NULL,435,141,132,23,17,31,'anpera',0),(97,'Verrückter Fussballmoderator',12,'Lautes Gebrüll','TOOOOOOOOOOOOOR!!!!!!',NULL,435,141,125,23,17,31,'anpera',0),(106,'Gigant',13,'Überwältigende Keule','Aaaah - beinahe wäre er auf dich gefallen!',NULL,467,156,135,25,18,36,'anpera',0),(108,'Eisdrache',13,'Frostatem','Du hast ihn besiegt, dir aber sicher eine Erkältung eingefangen.',NULL,467,156,143,25,18,36,'anpera',0),(113,'Mephisto',14,'Kugelblitze','Er hat nichts fallen lassen! Das trifft dich härter als seine Magie...',NULL,499,172,145,27,20,42,'anpera',0),(115,'Schwarzer Drache',14,'Zähne und Klauen','Irgendwo hast du so einen Drachen schonmal gesehen....',NULL,499,172,154,27,20,42,'anpera',0),(136,'Chink',15,'Beschworene Doppelgänger','Er kam aus dem Nichts und es blieb von ihm nichts.','',531,189,165,29,21,0,'anpera',0),(138,'Rattenherde',8,'Unglaublich viele scharfe Zähne','Es geht auch ohne Flöte...',NULL,302,89,84,15,11,NULL,'anpera',0),(146,'Ulysses Wolfgang',14,'Lächerliche Lügen','Tod den Lügen!  Tod Ulysses Wolfgang',NULL,499,172,145,27,20,NULL,'anpera',0),(155,'Regierung',7,'Steuern','Diese Steuerrückzahlung bringst du auf die Bank.','',268,77,77,13,10,NULL,'anpera',0),(156,'Barfliege',1,'Alkoholfahne','Gut, dass du das Biest erledigt hast, bevor es die Zirrhose getan hätte!',NULL,36,14,10,1,1,NULL,'anpera',0),(161,'Wanderer',1,'Wanderstab','Seine letzten Worte waren: \"...Ich muss den Drachen beschützen.\"','',36,14,11,1,1,NULL,'anpera',0),(162,'Camper',2,'Marshmallow Röststock','Mit dem letzten Atem spricht er: \"...und ich hab nichtmal den Drachen gesehen.\"',NULL,97,24,21,2,3,NULL,'anpera',0),(167,'Eingefleischter Drachenbeobachter',7,'Fernglas','Alles was er sagen konnte war: \"Pssssst, du verscheuchst die Drachen!\"',NULL,268,77,77,13,10,NULL,'anpera',0),(177,'Ein Fledermäuschen',1,'Winzige Flatterflügel','Dummes Ding.','',36,14,11,1,1,NULL,'anpera',1),(219,'Verführerische Vampirin',14,'Temperamentvolle Bewegungen','Es war eine Schande sie zu töten.','',499,172,154,27,20,NULL,'anpera',1),(221,'Vampirus der Fledermausgott',16,'Göttliche Macht','Du bist sogar noch göttlicher als er!',NULL,563,207,166,31,22,NULL,'anpera',1),(226,'Wassergeist',1,'Wasserspritzer','Das war erfrischend.',NULL,36,14,10,1,1,NULL,'anpera',0),(233,'Lebendiger Zweig',1,'Beeren und Dornen','Ich habe ihn umgeknickt wie einen dürren .... naja, Zweig',NULL,36,14,11,1,1,NULL,'anpera',0),(237,'Goblin Helferlein',1,'Stumpfes Messer','Wer hätte gedacht, dass Goblins Helfer haben?',NULL,36,14,10,1,1,NULL,'anpera',0),(240,'Untoter Ruderer',1,'Vergammeltes Ruder','Komisch ... hab hier gar kein Wasser in der Nähe gesehen...',NULL,36,14,10,1,1,NULL,'anpera',1),(242,'Forumuser',2,'Unkontrollierter Spam','Stopp Spam!',NULL,97,24,22,2,3,NULL,'anpera',0),(243,'Untoter Newbie',2,'Ignorierte F.A.Q.','Er hätte vielleicht doch mal die F.A.Q. lesen sollen...',NULL,97,24,22,2,3,NULL,'anpera',1),(314,'Elmearischer Spion',9,'Kleiner Dolch','Er isst seine Informationen auf, bevor du sie an dich nehmen kannst..',NULL,336,101,94,17,13,NULL,'anpera',0),(321,'Seelenfresser',8,'Unsagbarer Appetit','Fahr zur Hölle mit all den Seelen in dir.',NULL,302,89,88,15,11,NULL,'anpera',1),(404,'Große haarige Spinne',1,'Klebrige Spinnweben','Ich *HASSE* Spinnen!','',36,14,11,1,1,NULL,'anpera',0),(439,'Schattenkrieger',13,'Unsichtbare Klinge','Des Teufels Schergen schwinden dahin.',NULL,467,156,135,25,18,NULL,'anpera',1),(690,'Späher',3,'Stumpfer Dolch','Er konnte nur noch seinen Dolch wegwerfen, damit du ihn nicht bekommst.',NULL,148,34,32,5,4,14,'anpera',0),(697,'Goblinwächter',4,'Wächterhammer','harr harr harr',NULL,162,45,43,7,6,15,'anpera',0),(768,'Kleine Fledermaus',2,'Kleine Giftzähne','Der kleine Säuger fällt auf den Boden',NULL,97,24,21,2,3,NULL,'anpera',1),(796,'Streunende Katze',1,'Nächtliche Katzenmusik','Endlich! Ruhe in der Nacht.','',36,14,11,1,1,NULL,'anpera',0),(799,'Tollwütige Maus',1,'Kleine Zähnchen','Diese kleinen Zähnchen haben WEH getan!','',36,14,11,1,1,NULL,'anpera',0),(867,'Cicero',15,'Wohlüberlegtes Gelaber in unverständlichem Latein','Jetzt war er mit seinem Latein am Ende',NULL,531,189,155,29,21,NULL,'anpera',0),(868,'Versicherungsvertreter',14,'Seelenfänger','Hoffentlich hatte er eine gute Lebensversicherung',NULL,499,172,145,27,20,NULL,'anpera',0),(870,'Brennender Bush',12,'Wurzel des Bösen','Du bezweifelst, dass du alles erwischt hast.',NULL,435,141,125,23,17,NULL,'anpera',0);
/*!40000 ALTER TABLE `creatures` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `crimes`
--

DROP TABLE IF EXISTS `crimes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `crimes` (
  `newsid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `newstext` text NOT NULL,
  `newsdate` date NOT NULL DEFAULT '0000-00-00',
  `accountid` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`newsid`,`newsdate`),
  KEY `accountid` (`accountid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `crimes`
--

LOCK TABLES `crimes` WRITE;
/*!40000 ALTER TABLE `crimes` DISABLE KEYS */;
/*!40000 ALTER TABLE `crimes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `crops`
--

DROP TABLE IF EXISTS `crops`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `crops` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sort` int(11) NOT NULL,
  `garden` int(11) NOT NULL,
  `sizeh` smallint(6) NOT NULL,
  `sizev` smallint(6) NOT NULL,
  `occupies` varchar(100) NOT NULL,
  `position` int(11) NOT NULL,
  `stage` int(11) NOT NULL,
  `condition` int(11) NOT NULL,
  `age` int(11) NOT NULL,
  `fruit` mediumint(9) NOT NULL,
  `harvest` int(10) unsigned NOT NULL,
  `care` date NOT NULL,
  `owner_name` varchar(100) NOT NULL,
  `owner_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sort` (`sort`),
  KEY `garden` (`garden`),
  KEY `sizeh` (`sizeh`),
  KEY `sizev` (`sizev`),
  KEY `occupies` (`occupies`),
  KEY `position` (`position`),
  KEY `stage` (`stage`),
  KEY `condition` (`condition`),
  KEY `age` (`age`),
  KEY `fruit` (`fruit`),
  KEY `harvest` (`harvest`),
  KEY `care` (`care`),
  KEY `owner_name` (`owner_name`),
  KEY `owner_id` (`owner_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `crops`
--

LOCK TABLES `crops` WRITE;
/*!40000 ALTER TABLE `crops` DISABLE KEYS */;
/*!40000 ALTER TABLE `crops` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `crops_tpl`
--

DROP TABLE IF EXISTS `crops_tpl`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `crops_tpl` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `size` varchar(255) NOT NULL,
  `stage` varchar(255) NOT NULL,
  `fruit` varchar(255) NOT NULL,
  `assert` varchar(255) NOT NULL,
  `sensibility` int(11) NOT NULL,
  `lifespan` int(11) NOT NULL,
  `sprout` int(11) NOT NULL,
  `path` varchar(100) NOT NULL,
  `pest` tinyint(4) NOT NULL DEFAULT '0',
  `start_tpl` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  KEY `size` (`size`),
  KEY `stage` (`stage`),
  KEY `fruit` (`fruit`),
  KEY `assert` (`assert`),
  KEY `sensibility` (`sensibility`),
  KEY `lifespan` (`lifespan`),
  KEY `sprout` (`sprout`),
  KEY `path` (`path`),
  KEY `pest` (`pest`),
  KEY `start_tpl` (`start_tpl`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `crops_tpl`
--

LOCK TABLES `crops_tpl` WRITE;
/*!40000 ALTER TABLE `crops_tpl` DISABLE KEYS */;
INSERT INTO `crops_tpl` VALUES (3,'Sonnenblume','{\"0\":\"0:1x1\\r\",\"1\":\"5:1x1\\r\",\"2\":\"11:1x1\\r\",\"3\":\"16:1x1\\r\",\"4\":\"22:2x2\"}','{\"0\":\"Saat\\r\",\"1\":\"Keimling\\r\",\"2\":\"Jungpflanze\\r\",\"3\":\"Sonnenbl\\u00fcmchen\\r\",\"4\":\"Sonnenblume\"}','{\"0\":\"4:sunflower:5:10:0:1\"}','{\"0\":\"0\\r\",\"1\":\"0\\r\",\"2\":\"2\\r\",\"3\":\"4\\r\",\"4\":\"6\"}',40,100,55,'sunflower',0,''),(4,'Apfelbaum','{\"0\":\"0:1x1\\r\",\"1\":\"7:1x1\\r\",\"2\":\"25:1x2\\r\",\"3\":\"55:3x2\"}','{\"0\":\"Saat\\r\",\"1\":\"Keimling\\r\",\"2\":\"B\\u00e4umchen\\r\",\"3\":\"Baum\"}','{\"0\":\"3:apple:1:3:12:0\"}','{\"0\":\"0\\r\",\"1\":\"0\\r\",\"2\":\"5\\r\",\"3\":\"10\"}',10,0,70,'apple',0,''),(5,'Unkraut','{\"0\":\"0:1x1\"}','{\"0\":\"Nerviges Unkraut\"}','{\"0\":\"\"}','{\"0\":\"50\"}',-100,1000,0,'pest',1,''),(6,'Schnecken','{\"0\":\"0:1x1\"}','{\"0\":\"eine Schneckenplage\"}','{\"0\":\"\"}','{\"0\":\"300\"}',-500,1000,0,'slugs',1,''),(7,'Kirschbaum','{\"0\":\"0:1x1\\r\",\"1\":\"5:1x1\\r\",\"2\":\"15:1x1\\r\",\"3\":\"35:1x2\\r\",\"4\":\"60:3x2\"}','{\"0\":\"Kirschkern\\r\",\"1\":\"Keimling\\r\",\"2\":\"B\\u00e4umchen\\r\",\"3\":\"Gro\\u00dfer Baum\\r\",\"4\":\"Elfenwohnheim\"}','{\"0\":\"3:kirsche:5:12:20:0\\r\",\"1\":\"4:krschhlz:1:1:0:1\"}','{\"0\":\"0\\r\",\"1\":\"0\\r\",\"2\":\"2\\r\",\"3\":\"3\\r\",\"4\":\"5\"}',15,0,65,'cherry',0,''),(8,'Alraune','{\"0\":\"0:1x1\\r\",\"1\":\"7:1x1\\r\",\"2\":\"15:2x2\\r\",\"3\":\"40:2x2\"}','{\"0\":\"Saat\\r\",\"1\":\"Keimling\\r\",\"2\":\"Jungpflanze\\r\",\"3\":\"Ausgewachsene Pflanze\"}','{\"0\":\"2:alrwrzl:1:1:0:1\\r\",\"1\":\"3:alrfrcht:2:5:12:0\"}','{\"0\":\"0\\r\",\"1\":\"0\\r\",\"2\":\"5\\r\",\"3\":\"5\"}',60,1800,45,'mandrake',0,''),(9,'Hanf','{\"0\":\"0:1x1\\r\",\"1\":\"5:1x1\\r\",\"2\":\"13:1x1\\r\",\"3\":\"25:1x1\\r\",\"4\":\"40:2x1\"}','{\"0\":\"Samen\\r\",\"1\":\"Keimling\\r\",\"2\":\"Jungpflanze\\r\",\"3\":\"Erwachsene Pflanze\\r\",\"4\":\"Hanfstrauch\"}','{\"0\":\"2:hanf:2:5:12:0\\r\",\"1\":\"4:hnfsaat:3:6:36:0\"}','{\"0\":\"0\\r\",\"1\":\"2\\r\",\"2\":\"5\\r\",\"3\":\"10\\r\",\"4\":\"15\"}',25,1200,65,'hemp',0,''),(10,'Erdnuss','{\"0\":\"0:1x1\\r\",\"1\":\"6:1x1\\r\",\"2\":\"15:1x2\\r\",\"3\":\"30:2x3\"}','{\"0\":\"Samen\\r\",\"1\":\"Keimling\\r\",\"2\":\"Erdnusspfl\\u00e4nzchen\\r\",\"3\":\"Erdnusspflanze\"}','{\"0\":\"2:erdnuss:1:3:16:0\\r\",\"1\":\"3:macanut:2:4:0:1\"}','{\"0\":\"0\\r\",\"1\":\"0\\r\",\"2\":\"10\\r\",\"3\":\"20\"}',30,800,80,'nut',0,'');
/*!40000 ALTER TABLE `crops_tpl` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ddlnews`
--

DROP TABLE IF EXISTS `ddlnews`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ddlnews` (
  `newsid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `newstext` text NOT NULL,
  `newsdate` date NOT NULL DEFAULT '0000-00-00',
  `accountid` int(11) unsigned NOT NULL DEFAULT '0',
  `guildid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`newsid`,`newsdate`),
  KEY `accountid` (`accountid`),
  KEY `guildid` (`guildid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ddlnews`
--

LOCK TABLES `ddlnews` WRITE;
/*!40000 ALTER TABLE `ddlnews` DISABLE KEYS */;
/*!40000 ALTER TABLE `ddlnews` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `debuglog`
--

DROP TABLE IF EXISTS `debuglog`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `debuglog` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `actor` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `target` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `message` text NOT NULL,
  `ip` varchar(15) NOT NULL DEFAULT '',
  `uid` varchar(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `date` (`date`),
  KEY `actor` (`actor`),
  KEY `target` (`target`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `debuglog`
--

LOCK TABLES `debuglog` WRITE;
/*!40000 ALTER TABLE `debuglog` DISABLE KEYS */;
/*!40000 ALTER TABLE `debuglog` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dg_books`
--

DROP TABLE IF EXISTS `dg_books`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dg_books` (
  `bookid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `guildid` int(10) unsigned NOT NULL,
  `theme` tinyint(7) DEFAULT '0',
  `acctid` int(10) unsigned NOT NULL,
  `author` varchar(250) NOT NULL,
  `activated` int(10) NOT NULL,
  `su_activated` tinyint(4) DEFAULT '0',
  `title` varchar(250) NOT NULL,
  `txt` text NOT NULL,
  PRIMARY KEY (`bookid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dg_books`
--

LOCK TABLES `dg_books` WRITE;
/*!40000 ALTER TABLE `dg_books` DISABLE KEYS */;
/*!40000 ALTER TABLE `dg_books` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dg_guilds`
--

DROP TABLE IF EXISTS `dg_guilds`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dg_guilds` (
  `guildid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(70) NOT NULL,
  `name_raw` varchar(70) NOT NULL,
  `bio` text NOT NULL,
  `points` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `state` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `founded` varchar(40) NOT NULL DEFAULT '',
  `guard_hp_before` smallint(5) unsigned NOT NULL DEFAULT '0',
  `guard_hp` smallint(5) unsigned NOT NULL DEFAULT '0',
  `fights_suffered_period` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `war_target` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `regalia` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `build_list` text NOT NULL,
  `ranks` text NOT NULL,
  `treaties` text NOT NULL,
  `transfers` text NOT NULL,
  `points_spent` int(10) unsigned NOT NULL DEFAULT '0',
  `gold` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `gems` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `taxdays` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `fights` tinyint(3) unsigned NOT NULL DEFAULT '3',
  `lastupdate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `last_state_change` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `founder` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `rules` text NOT NULL,
  `professions_allowed` varchar(255) NOT NULL DEFAULT '',
  `guildwar_allowed` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `taxfree_allowed` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `immune_days` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `hitlist` text NOT NULL,
  `building_vars` text NOT NULL,
  `gold_tax` int(10) unsigned NOT NULL DEFAULT '0',
  `gold_tribute` int(10) unsigned NOT NULL DEFAULT '0',
  `gems_tax` int(10) unsigned NOT NULL DEFAULT '0',
  `gems_tribute` int(10) unsigned NOT NULL DEFAULT '0',
  `fights_suffered` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `reputation` tinyint(3) unsigned NOT NULL DEFAULT '50',
  `gold_in` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `gems_in` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `ext_room_name` varchar(100) NOT NULL DEFAULT '',
  `ext_room_desc` text NOT NULL,
  `ext_room_name2` varchar(100) NOT NULL DEFAULT '',
  `ext_room_desc2` text NOT NULL,
  `top_repu` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `atk_upgrade` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `def_upgrade` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `regalia_sold` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `last_regalia_blackmarket` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `guild_own_description` text NOT NULL COMMENT 'Eigene Beschreibung für die Gildenhalle',
  PRIMARY KEY (`guildid`),
  KEY `state` (`state`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Haupttabelle für die Gilden';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dg_guilds`
--

LOCK TABLES `dg_guilds` WRITE;
/*!40000 ALTER TABLE `dg_guilds` DISABLE KEYS */;
/*!40000 ALTER TABLE `dg_guilds` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dg_log`
--

DROP TABLE IF EXISTS `dg_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dg_log` (
  `logid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `guild` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `target` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `message` varchar(255) NOT NULL DEFAULT '',
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`logid`),
  KEY `guild` (`guild`),
  KEY `target` (`target`),
  KEY `date` (`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Logs der Gilden';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dg_log`
--

LOCK TABLES `dg_log` WRITE;
/*!40000 ALTER TABLE `dg_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `dg_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `disc_rem_list`
--

DROP TABLE IF EXISTS `disc_rem_list`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `disc_rem_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lastuser` varchar(120) NOT NULL,
  `discname` varchar(90) NOT NULL,
  `disclevel` int(10) NOT NULL,
  `remdate` varchar(90) NOT NULL,
  `owner_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `disc_rem_list`
--

LOCK TABLES `disc_rem_list` WRITE;
/*!40000 ALTER TABLE `disc_rem_list` DISABLE KEYS */;
/*!40000 ALTER TABLE `disc_rem_list` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `disciples`
--

DROP TABLE IF EXISTS `disciples`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `disciples` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(80) DEFAULT NULL,
  `state` tinyint(4) NOT NULL DEFAULT '0',
  `oldstate` tinyint(4) NOT NULL DEFAULT '0',
  `level` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `extra` int(11) DEFAULT '0',
  `master` int(10) unsigned NOT NULL DEFAULT '0',
  `best_one` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `free_day` varchar(14) NOT NULL DEFAULT '0000-00-00',
  PRIMARY KEY (`id`),
  KEY `master` (`master`),
  KEY `best_one` (`best_one`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `disciples`
--

LOCK TABLES `disciples` WRITE;
/*!40000 ALTER TABLE `disciples` DISABLE KEYS */;
/*!40000 ALTER TABLE `disciples` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `donationhistory`
--

DROP TABLE IF EXISTS `donationhistory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `donationhistory` (
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `receiver` int(10) unsigned DEFAULT NULL,
  `dp_amount` int(11) DEFAULT NULL,
  `donation` float DEFAULT NULL,
  `reason` text,
  PRIMARY KEY (`timestamp`),
  KEY `receiver` (`receiver`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='List of Donations';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `donationhistory`
--

LOCK TABLES `donationhistory` WRITE;
/*!40000 ALTER TABLE `donationhistory` DISABLE KEYS */;
/*!40000 ALTER TABLE `donationhistory` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `extended_text`
--

DROP TABLE IF EXISTS `extended_text`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `extended_text` (
  `id` varchar(255) NOT NULL DEFAULT '',
  `text` text NOT NULL,
  `category` varchar(255) NOT NULL DEFAULT 'standard',
  `subcategory` varchar(255) NOT NULL DEFAULT '',
  `author` varchar(120) NOT NULL DEFAULT '',
  `tags` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `category` (`category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Saves some long texts in the database';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `extended_text`
--

LOCK TABLES `extended_text` WRITE;
/*!40000 ALTER TABLE `extended_text` DISABLE KEYS */;
INSERT INTO `extended_text` VALUES ('about_lotgd','`@Legend of the Green Dragon`nBy Eric Stevens`n`n\r\n			`cLoGD version {{ return GAME_VERSION; }}`c\r\n			\r\n			MightyE tells you, \"`2Legend of the Green Dragon is my remake of the classic\r\n			BBS Door game, Legend of the Red Dragon (aka LoRD) by Seth Able Robinson.  \r\n			`n`n`@\"`2LoRD is now owned by Gameport (<a href=\'http://www.gameport.com/bbs/lord.html\'>http://www.gameport.com/bbs/lord.html</a>), and\r\n			they retain exclusive rights to the LoRD name and game.  That\'s why all content in \r\n			\r\n			Legend of the Green Dragon is new, with only a very few nods to the original game, such \r\n			as the buxom barmaid, Violet, and the handsome bard, Seth.`n`n\r\n			`@\"`2Although serious effort was made to preserve the original feel of the game, \r\n			numerous departures were taken from the original game to enhance playability, and \r\n			\r\n			to adapt it to the web.`n`n\r\n			`@\"`2LoGD is released under the GNU General Public License (GPL), which essentially means \r\n			that the source code to the game, and all derivatives of the game must remain open and\r\n			available upon request.`n`n\r\n			\r\n			`@\"`2You can download the latest version of LoGD at <a href=\'http://sourceforge.net/projects/lotgd\' target=\'_blank\'>http://sourceforge.net/projects/lotgd</a>\r\n			 and you can play the latest version at <a href=\'http://lotgd.net/\'>http://lotgd.net</a>.`n`n\r\n			`@\"`2LoGD is programmed in PHP with a MySQL backend.  It is known to run on Windows and Linux with appropriate\r\n				setups.  Most code has been written by Eric Stevens, with some pieces by other authors (denoted in source at these locations), \r\n				and the code has been released under the \r\n				<a href=\"http://www.gnu.org/copyleft/gpl.html\">GNU General Public License</a>.  Users of the source\r\n				are bound to the terms therein.`@\"`n`n\r\n			\r\n			`@\"`2Users of the source are free to view and modify the source, but original copyright information, and\r\n				original text from the about page must be preserved, though they may be added to.`@\"`n`n\r\n			`@\"`2I hope you enjoy the game!`@\"\r\n','standard','Administratives','',''),('about_server','','standard','Allgemein','',''),('dragonpointreset_info','`IHier hast du die Möglichkeit, deine Heldenpunkte vollkommen neu verteilen zu lassen. Fülle dazu bitte das unten angefügte Formular aus, indem du eine Begründung für den Reset angibst und kurz beschreibst, wie du deine Punkte neu vergeben möchtest.`n\n`n\nBitte beachte: du kannst diese Änderung nur ein einziges Mal vornehmen. Zudem ist es nur gestattet, wenn du einen zu hohen Prozentsatz deiner Heldenpunkte auf LP vergeben hast (ca. 45% oder mehr) und du im Wald aufgrund zu niedriger Angriffs- und Verteidigungswerte erhebliche Schwierigkeiten hast. Ist das nicht der Fall, wirst du wie bisher auf die Möglichkeit der Erneuerung zurückgreifen müssen.`n\n`n\nDer Preis (in DP) passt sich der Anzahl der Heldentaten und deinen Besitzverhältnissen an.`n','standard','Anfragen','Eleya','Drachenpunkte,Heldenpunkte,Reset,Zurücksetzen'),('exp_lake','','expedition_texte','Administratives','',''),('faq_start','','rules_faq','Administratives','',''),('GPL','`I`b`cGNU GENERAL PUBLIC LICENSE`b`nVersion 2, June 1991`c\n`n`n\n `tCopyright (C) 1989, 1991 Free Software Foundation, Inc.`n\n 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA`n\n Everyone is permitted to copy and distribute verbatim copies of this license document, but changing it is not allowed.\n`n`n\n`I`cPreamble`c`t\n`n`n\n  The licenses for most software are designed to take away your freedom to share and change it.  By contrast, the GNU General Public License is intended to guarantee your freedom to share and change free\nsoftware--to make sure the software is free for all its users.  This General Public License applies to most of the Free Software Foundation\\\'s software and to any other program whose authors commit to\nusing it.  (Some other Free Software Foundation software is covered by the GNU Library General Public License instead.)  You can apply it to your programs, too.\n`n`n\n  When we speak of free software, we are referring to freedom, not price.  Our General Public Licenses are designed to make sure that you have the freedom to distribute copies of free software (and charge for\nthis service if you wish), that you receive source code or can get it if you want it, that you can change the software or use pieces of it in new free programs; and that you know you can do these things.\n`n`n\n  To protect your rights, we need to make restrictions that forbid anyone to deny you these rights or to ask you to surrender the rights.\nThese restrictions translate to certain responsibilities for you if you distribute copies of the software, or if you modify it.\n`n`n\n  For example, if you distribute copies of such a program, whether gratis or for a fee, you must give the recipients all the rights that you have.  You must make sure that they, too, receive or can get the\nsource code.  And you must show them these terms so they know their rights.\n`n`n\n  We protect your rights with two steps:`n (1) copyright the software, and`n\n(2) offer you this license which gives you legal permission to copy, distribute and/or modify the software.\n`n`n\n  Also, for each author\\\'s protection and ours, we want to make certain that everyone understands that there is no warranty for this free\nsoftware.  If the software is modified by someone else and passed on, we want its recipients to know that what they have is not the original, so\nthat any problems introduced by others will not reflect on the original authors\\\' reputations.\n`n`n\n  Finally, any free program is threatened constantly by software patents.  We wish to avoid the danger that redistributors of a free\nprogram will individually obtain patent licenses, in effect making the program proprietary.  To prevent this, we have made it clear that any\npatent must be licensed for everyone\\\'s free use or not licensed at all.\n`n`n\n  The precise terms and conditions for copying, distribution and modification follow.\n`n`n`n`b`I`cGNU GENERAL PUBLIC LICENSE`b`n\n   TERMS AND CONDITIONS FOR COPYING, DISTRIBUTION AND MODIFICATION`c\n`n`n\n `t 0. This License applies to any program or other work which contains a notice placed by the copyright holder saying it may be distributed\nunder the terms of this General Public License.  The \\\\\\\"Program\\\\\\\", below, refers to any such program or work, and a \\\\\\\"work based on the Program\\\\\\\"\nmeans either the Program or any derivative work under copyright law: that is to say, a work containing the Program or a portion of it,\neither verbatim or with modifications and/or translated into another language.  (Hereinafter, translation is included without limitation in\nthe term \\\\\\\"modification\\\\\\\".)  Each licensee is addressed as \\\\\\\"you\\\\\\\".\n`n`n\nActivities other than copying, distribution and modification are not covered by this License; they are outside its scope.  The act of\nrunning the Program is not restricted, and the output from the Program is covered only if its contents constitute a work based on the\nProgram (independent of having been made by running the Program). Whether that is true depends on what the Program does.\n`n`n\n  1. You may copy and distribute verbatim copies of the Program\\\'s source code as you receive it, in any medium, provided that you\nconspicuously and appropriately publish on each copy an appropriate copyright notice and disclaimer of warranty; keep intact all the\nnotices that refer to this License and to the absence of any warranty; and give any other recipients of the Program a copy of this License\nalong with the Program.\n`n`n\nYou may charge a fee for the physical act of transferring a copy, and you may at your option offer warranty protection in exchange for a fee.\n`n`n\n  2. You may modify your copy or copies of the Program or any portion of it, thus forming a work based on the Program, and copy and\ndistribute such modifications or work under the terms of Section 1 above, provided that you also meet all of these conditions:\n`n`n\n    `ba)`b You must cause the modified files to carry prominent notices stating that you changed the files and the date of any change.\n`n`n\n    `bb)`b You must cause any work that you distribute or publish, that in whole or in part contains or is derived from the Program or any\n    part thereof, to be licensed as a whole at no charge to all third parties under the terms of this License.\n`n`n\n    `bc)`b If the modified program normally reads commands interactively when run, you must cause it, when started running for such\n    interactive use in the most ordinary way, to print or display an announcement including an appropriate copyright notice and a\n    notice that there is no warranty (or else, saying that you provide a warranty) and that users may redistribute the program under\n    these conditions, and telling the user how to view a copy of this License.  (Exception: if the Program itself is interactive but\n    does not normally print such an announcement, your work based on the Program is not required to print an announcement.)\n`n`n`nThese requirements apply to the modified work as a whole.  If identifiable sections of that work are not derived from the Program,\nand can be reasonably considered independent and separate works in themselves, then this License, and its terms, do not apply to those\nsections when you distribute them as separate works.  But when you distribute the same sections as part of a whole which is a work based\non the Program, the distribution of the whole must be on the terms of this License, whose permissions for other licensees extend to the\nentire whole, and thus to each and every part regardless of who wrote it.\n`n`n\nThus, it is not the intent of this section to claim rights or contest your rights to work written entirely by you; rather, the intent is to\nexercise the right to control the distribution of derivative or collective works based on the Program.\n`n`n\nIn addition, mere aggregation of another work not based on the Program with the Program (or with a work based on the Program) on a volume of\na storage or distribution medium does not bring the other work under the scope of this License.\n`n`n\n  3. You may copy and distribute the Program (or a work based on it, under Section 2) in object code or executable form under the terms of\nSections 1 and 2 above provided that you also do one of the following:\n`n`n\n    `ba)`b Accompany it with the complete corresponding machine-readable source code, which must be distributed under the terms of Sections\n    1 and 2 above on a medium customarily used for software interchange; or,\n`n`n\n    `bb)`b Accompany it with a written offer, valid for at least three years, to give any third party, for a charge no more than your\n    cost of physically performing source distribution, a complete machine-readable copy of the corresponding source code, to be\n    distributed under the terms of Sections 1 and 2 above on a medium customarily used for software interchange; or,\n`n`n\n    `bc)`b Accompany it with the information you received as to the offer to distribute corresponding source code.  (This alternative is\n    allowed only for noncommercial distribution and only if you received the program in object code or executable form with such\n    an offer, in accord with Subsection b above.)\n`n`n\nThe source code for a work means the preferred form of the work for making modifications to it.  For an executable work, complete source\ncode means all the source code for all modules it contains, plus any associated interface definition files, plus the scripts used to\ncontrol compilation and installation of the executable.  However, as a special exception, the source code distributed need not include\nanything that is normally distributed (in either source or binary form) with the major components (compiler, kernel, and so on) of the\noperating system on which the executable runs, unless that component itself accompanies the executable.\n`n`n\nIf distribution of executable or object code is made by offering access to copy from a designated place, then offering equivalent\naccess to copy the source code from the same place counts as distribution of the source code, even though third parties are not\ncompelled to copy the source along with the object code.\n`n`n`n  4. You may not copy, modify, sublicense, or distribute the Program except as expressly provided under this License.  Any attempt\notherwise to copy, modify, sublicense or distribute the Program is void, and will automatically terminate your rights under this License.\nHowever, parties who have received copies, or rights, from you under this License will not have their licenses terminated so long as such\nparties remain in full compliance.\n`n`n\n  5. You are not required to accept this License, since you have not signed it.  However, nothing else grants you permission to modify or\ndistribute the Program or its derivative works.  These actions are prohibited by law if you do not accept this License.  Therefore, by\nmodifying or distributing the Program (or any work based on the Program), you indicate your acceptance of this License to do so, and\nall its terms and conditions for copying, distributing or modifying the Program or works based on it.\n`n`n\n  6. Each time you redistribute the Program (or any work based on the Program), the recipient automatically receives a license from the\noriginal licensor to copy, distribute or modify the Program subject to these terms and conditions.  You may not impose any further\nrestrictions on the recipients\\\' exercise of the rights granted herein. You are not responsible for enforcing compliance by third parties to\nthis License.\n`n`n\n  7. If, as a consequence of a court judgment or allegation of patent infringement or for any other reason (not limited to patent issues),\nconditions are imposed on you (whether by court order, agreement or otherwise) that contradict the conditions of this License, they do not\nexcuse you from the conditions of this License.  If you cannot distribute so as to satisfy simultaneously your obligations under this\nLicense and any other pertinent obligations, then as a consequence you may not distribute the Program at all.  For example, if a patent\nlicense would not permit royalty-free redistribution of the Program by all those who receive copies directly or indirectly through you, then\nthe only way you could satisfy both it and this License would be to refrain entirely from distribution of the Program.\n`n`n\nIf any portion of this section is held invalid or unenforceable under any particular circumstance, the balance of the section is intended to\napply and the section as a whole is intended to apply in other circumstances.\n`n`n\nIt is not the purpose of this section to induce you to infringe any patents or other property right claims or to contest validity of any\nsuch claims; this section has the sole purpose of protecting the integrity of the free software distribution system, which is\nimplemented by public license practices.  Many people have made generous contributions to the wide range of software distributed\nthrough that system in reliance on consistent application of that system; it is up to the author/donor to decide if he or she is willing\nto distribute software through any other system and a licensee cannot impose that choice.\n`n`n\nThis section is intended to make thoroughly clear what is believed to be a consequence of the rest of this License.\n`n`n`n  8. If the distribution and/or use of the Program is restricted in certain countries either by patents or by copyrighted interfaces, the\noriginal copyright holder who places the Program under this License may add an explicit geographical distribution limitation excluding\nthose countries, so that distribution is permitted only in or among countries not thus excluded.  In such case, this License incorporates\nthe limitation as if written in the body of this License. \n`n`n\n  9. The Free Software Foundation may publish revised and/or new versions of the General Public License from time to time.  Such new versions will\nbe similar in spirit to the present version, but may differ in detail to address new problems or concerns.\n`n`n\nEach version is given a distinguishing version number.  If the Program specifies a version number of this License which applies to it and \\\\\\\"any\nlater version\\\\\\\", you have the option of following the terms and conditions either of that version or of any later version published by the Free\nSoftware Foundation.  If the Program does not specify a version number of this License, you may choose any version ever published by the Free Software\nFoundation.\n`n`n\n  10. If you wish to incorporate parts of the Program into other free programs whose distribution conditions are different, write to the author\nto ask for permission.  For software which is copyrighted by the Free Software Foundation, write to the Free Software Foundation; we sometimes\nmake exceptions for this.  Our decision will be guided by the two goals of preserving the free status of all derivatives of our free software and\nof promoting the sharing and reuse of software generally.\n`n`n\n`I`cNO WARRANTY`c\n\n  11. BECAUSE THE PROGRAM IS LICENSED FREE OF CHARGE, THERE IS NO WARRANTY FOR THE PROGRAM, TO THE EXTENT PERMITTED BY APPLICABLE LAW.  EXCEPT WHEN\nOTHERWISE STATED IN WRITING THE COPYRIGHT HOLDERS AND/OR OTHER PARTIES PROVIDE THE PROGRAM \\\\\\\"AS IS\\\\\\\" WITHOUT WARRANTY OF ANY KIND, EITHER EXPRESSED\nOR IMPLIED, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE.  THE ENTIRE RISK AS\nTO THE QUALITY AND PERFORMANCE OF THE PROGRAM IS WITH YOU.  SHOULD THE PROGRAM PROVE DEFECTIVE, YOU ASSUME THE COST OF ALL NECESSARY SERVICING,\nREPAIR OR CORRECTION.\n`n`n\n  12. IN NO EVENT UNLESS REQUIRED BY APPLICABLE LAW OR AGREED TO IN WRITING WILL ANY COPYRIGHT HOLDER, OR ANY OTHER PARTY WHO MAY MODIFY AND/OR\nREDISTRIBUTE THE PROGRAM AS PERMITTED ABOVE, BE LIABLE TO YOU FOR DAMAGES, INCLUDING ANY GENERAL, SPECIAL, INCIDENTAL OR CONSEQUENTIAL DAMAGES ARISING\nOUT OF THE USE OR INABILITY TO USE THE PROGRAM (INCLUDING BUT NOT LIMITED TO LOSS OF DATA OR DATA BEING RENDERED INACCURATE OR LOSSES SUSTAINED BY\nYOU OR THIRD PARTIES OR A FAILURE OF THE PROGRAM TO OPERATE WITH ANY OTHER PROGRAMS), EVEN IF SUCH HOLDER OR OTHER PARTY HAS BEEN ADVISED OF THE\nPOSSIBILITY OF SUCH DAMAGES.\n`n`n\n`cEND OF TERMS AND CONDITIONS`n`n`n\nHow to Apply These Terms to Your New Programs`c\n`n`n\n  `tIf you develop a new program, and you want it to be of the greatest possible use to the public, the best way to achieve this is to make it\nfree software which everyone can redistribute and change under these terms.\n`n`n\n  To do so, attach the following notices to the program.  It is safest to attach them to the start of each source file to most effectively\nconvey the exclusion of warranty; and each file should have at least the \\\\\\\"copyright\\\\\\\" line and a pointer to where the full notice is found.\n`n`n\n    <one line to give the program\\\'s name and a brief idea of what it does.>`n\n    Copyright (C) <year>  <name of author>\n`n`n\n    This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by\n    the Free Software Foundation; either version 2 of the License, or (at your option) any later version.\n`n`n\n    This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of\n    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.\n`n`n\n    You should have received a copy of the GNU General Public License along with this program; if not, write to the`n\nFree Software Foundation, Inc.,`n\n59 Temple Place, Suite 330, Boston, MA  02111-1307  USA\n`n`n`n\nAlso add information on how to contact you by electronic and paper mail.\n`n`n\nIf the program is interactive, make it output a short notice like this when it starts in an interactive mode:\n`n`n\n    `iGnomovision version 69, Copyright (C) year name of author`n\n    Gnomovision comes with ABSOLUTELY NO WARRANTY; for details type \\\'show w\\\'.`n\n    This is free software, and you are welcome to redistribute it under certain conditions; type \\\'show c\\\' for details.`i\n`n`n\nThe hypothetical commands \\\'show w\\\' and \\\'show c\\\' should show the appropriate parts of the General Public License.  Of course, the commands you use may\nbe called something other than how w\\\' and \\\\how c\\\'; they could even be mouse-clicks or menu items--whatever suits your program.\n`n`n\nYou should also get your employer (if you work as a programmer) or your school, if any, to sign a \\\\\\\"copyright disclaimer\\\\\\\" for the program, if\nnecessary.  Here is a sample; alter the names:\n`n`n\n`i  Yoyodyne, Inc., hereby disclaims all copyright interest in the program \\\'Gnomovision\\\' (which makes passes at compilers) written by James Hacker.\n`n`n`i\n <signature of Ty Coon>, 1 April 1989`n\n  Ty Coon, President of Vice\n`n`n\nThis General Public License does not permit incorporating your program into proprietary programs.  If your program is a subroutine library, you may\nconsider it more useful to permit linking proprietary applications with the library.  If this is what you want to do, use the GNU Library General\nPublic License instead of this License.','standard','Administratives','',''),('guard_policy','','policies_general','Administratives','',''),('guild_found_info',' ','guild_manuals','Administratives','',''),('guild_invitations','Du betrittst ein kleines Zimmer, welches gerade groß genug für einen Holzschemel samt Schreibtisch ist, auf dem Tinte und Pergament bereitstehen. Hier kannst du Einladungen an Personen verfassen lassen, denen somit ein Besuch in der Gilde genehmigt wird.\nDu weißt, dass dies nicht vollkommen umsonst ist, sondern deine Gilde {{ return getsetting(\\\'guildinvitationcost\\\',4); }} GP pro Stunde für einen Besucher kostet.\nEin Schreiber fragt dich, wie lange der Gast verweilen soll und stellt dann den Passierschein aus, der jedoch die Gildenwache nur am ausgeschrieben Tag und zur verzeichneten Stunden dazu bewegen wird, dem Besucher auch wirklich die Tore zu öffnen.','guild_manuals','Allgemein','Callyshee','Gilde,Einladung,Besucher,Gast,Gildeneinladung'),('heldenpunkte_wkverbot','`qDu kannst nicht mehr als ein Drittel deiner Drachenkills an Waldkämpfen erwerben. Wähle bitte eine andere Option.`0','standard','Allgemein','Takehon','Heldenpunkte Waldkämpfe Limit'),('judge_policy','','policies_general','Allgemein','','Richter, Regeln, Gesetzbuch'),('leader_manual','','guild_manuals','Administratives','',''),('lib_rules','...','standard','Administratives','',''),('mail_account_expiration','Hallo %user_name%!<br><br>\r\nLeider hast du dich seit einiger Zeit nicht mehr mit Deinem Charakter <b>\\\"%user_name%\\\"</b> in %town_name% ( <b>%server_url%</b> ) angemeldet.<br>\r\nDein Account würde in %days_until_deleted% Tagen verfallen und automatisch gelöscht werden. Wenn du den Charakter jedoch retten möchtest, \r\ndann solltest du dich baldmöglichst wieder einmal bei uns einloggen, denn es gibt seit deinem letzten Besuch viel Neues zu entdecken.<br>\r\nAls kleinen Anreiz haben wir dir %amount_dp% Donationpoints geschenkt, mit denen du dir viele besondere Dinge erkaufen kannst.<br>\r\nWir würden uns freuen dich bald wieder zu sehen.<br><br>\r\nViele Grüße<br><br><br>\r\n%team_name%','expedition_texte','Administratives','Dragonslayer',''),('petition_greetings','\n{{\n$arr_time = getdate();\n\n$str_ret = \\\'\\\';\nif($arr_time[\\\'hours\\\'] > 16) {\n$str_ret = \\\'Noch einen schönen Abend\\\';\n}\nelseif($arr_time[\\\'hours\\\'] < 5) {\n$str_ret = \\\'Noch eine gute Nacht\\\';\n}\nelseif($arr_time[\\\'hours\\\'] < 10) {\n$str_ret = \\\'Noch einen schönen Morgen\\\';\n}\nelse {\n$str_ret = \\\'Schöne Grüße\\\';\n}\nif($arr_time[\\\'wday\\\'] >= 5 || ($arr_time[\\\'wday\\\'] == 0 && $arr_time[\\\'hours\\\'] < 12)) {\n$str_ret .= \\\' und ein angenehmes Wochenende\\\';\n}\nreturn($str_ret);\n}}\nDein {{return(getsetting(\\\'teamname\\\',\\\'Drachenserver-Team\\\'));}}','standard','Administratives','talion',''),('quest_exchange','{{ global $session, $number;\r\n$num=(isset($number)?$number:$session[\\\'user\\\'][\\\'exchangequest\\\']);\r\nswitch($num) {\r\ncase 0:\r\n$out=\\\'Du wirst in naher Zukunft einer Fee begegnen. Dies wird der Anfang einer langen Reise sein.\\\';\r\nbreak;\r\n\r\ncase 1:\r\n$out=\\\'Ich sehe, Du wirst eines Tages auf dem Dorfplatz eine gute Tat vollbringen.\\\';\r\nbreak;\r\n\r\ncase 2:\r\n$out=\\\'Ich sehe Dich in der Bibliothek zusammen mit einem Dichter an einem Tisch sitzen. Draußen zeigen sich die ersten Schneeglöckchen.\\\';\r\nbreak;\r\n\r\ncase 3:\r\n$out=\\\'Der Schnee ist einem zarten Grün gewichen. Ich sehe Dich an einem Platz wo sich Verliebte treffen. Deine dichterischen Fähigkeiten werden jemand sehr glücklich machen.\\\';\r\nbreak;\r\n\r\ncase 4:\r\n$out=\\\'Ich sehe, Du wirst dereinst, wenn das Ostara-Fest bevorsteht, auf Deinen Streifzügen durch den Wald einer Hexe begegnen.\\\';\r\nbreak;\r\n\r\ncase 5:\r\n$out=\\\'Ich sehe Dich mit Hexen und Magiern an Poseidons See die Walpurgisnacht feiern.\\\';\r\nbreak;\r\n\r\ncase 6:\r\n$out=\\\'Ich sehe einen Schrein. Es ist der Schrein des Erdgottes. Du gehörst zum Kreis der Auserwählten, die seine Magie zu nutzen verstehen.\\\';\r\nbreak;\r\n\r\ncase 7:\r\n$out=\\\'Ein Schluck besonderes Wasser wird Dir im Kampf gottgleiche Kräfte verleihen. Diese Kraft wird Dir jedoch nur im Wald nützlich sein.\\\';\r\nbreak;\r\n\r\ncase 8:\r\n$out=\\\'Ich sehe die Maiglöckchen vor einer tiefen Höhle blühen. Nein, es ist ein altes Bergwerk. Hier wirst Du eine längst verschollene Person treffen.\\\';\r\nbreak;\r\n\r\ncase 9:\r\n$out=\\\'Ich sehe einen Markt, auf dem Du Dich mit einem fahrenden Händler unterhältst. Sieh Dich vor, dass man Dich nicht übers Ohr haut.\\\';\r\nbreak;\r\n\r\ncase 10:\r\n$out=\\\'Kurz vor Sommerbeginn sind die größeren unter den Seebewohnern für ein Flötenspiel besonders empfänglich.\\\';\r\nbreak;\r\n\r\ncase 11:\r\n$out=\\\'Dass Du Dich im Wald mit hübschen Fremden vergnügst bleibt Dein süßes Geheimnis.\\\';\r\nbreak;\r\n\r\ncase 12:\r\n$out=\\\'Dass Du Dich im Wald mit hübschen Fremden vergnügst bleibt Dein süßes Geheimnis. Zumindest solange Du es nicht in einer gemütlichen Runde am Lagerfeuer ausplauderst.\\\';\r\nbreak;\r\n\r\ncase 13:\r\n$out=\\\'In der Schenke triffst du einen rauflustigen Trunkenbold. Doch Du schaffst es, ihn zu besänftigen.\\\';\r\nbreak;\r\n\r\ncase 14:\r\n$out=\\\'Bereits Ende September beginnen die Konditoren mit der Weihnachtsbäckerei. Doch gelegentlich kommt es vor dass sich eine Lieferung der Zutaten verspätet.\\\';\r\nbreak;\r\n\r\ncase 15:\r\n$out=\\\'Ich sehe Dich im nebelbedeckten Tal zusammen mit einem fremden Wanderer essen.\\\';\r\nbreak;\r\n\r\ncase 16:\r\n$out=\\\'Ich sehe Dich hoch in den Bergen an einem wunderschönen Platz, welcher sonst von einem Yeti bewacht wird. Wisse, dass Yetis erst kurz vor Mitternacht schlafen.\\\';\r\nbreak;\r\n\r\ncase 17:\r\n$out=\\\'Ich kann nicht genau erkennen wo Du bist. Doch wisse, dass es im ganzen Dorf nur einen Mann gibt, der sich mit magischen Steinen auskennt.\\\';\r\nbreak;\r\n\r\ncase 18:\r\n$out=\\\'Welch gruseliger Ort! Eine stürmische Herbstnacht. Ich sehe Dich in dichtem Nebel eine Geisterbeschwörung zelebrieren. Deine Geduld ist endlich von Erfolg gekrönt.\\\';\r\nbreak;\r\n\r\ncase 19:\r\n$out=\\\'Ich sehe, Du wirst eine Reise mit einem Boot machen. Es ist aber nicht das Meer, auf dem Du tapfer gen Osten segelst, es ist nur Poseidons See.\\\';\r\nbreak;\r\n\r\ncase 20:\r\n$out=\\\'Anfang November. Eine Heilerin. Es ist offenbar nicht Golinda, denn Du bist völlig gesund.\\\';\r\nbreak;\r\n\r\ncase 21:\r\n$out=\\\'Ich sehe eine sehr tiefe und feuchte Höhle. Auf dem Boden liegen Goldstücke und einige Hausschlüssel. Hoch über Dir ist ein heller Ring.\\\';\r\nbreak;\r\n\r\ncase 22:\r\n$out=\\\'Sei gewarnt vor Plagiaten! Der Schwarze Drache ist eine gefährliche Kreatur, die abgeschieden im Gebirge lebt. Nur jedes zehnte Mal wenn Du für den Grünen Drachen bereit bist hast Du die Chance, auch einen Schwarzen Drachen zu besiegen.\\\';\r\nbreak;\r\n\r\ncase 23:\r\n$out=\\\'Die Kraft eines magischen Steinkreises wird Dich der Lösung ein Stück näher bringen.\\\';\r\nbreak;\r\n\r\ncase 24:\r\n$out=\\\'Ich sehe Dich Dein Haus aufräumen. Eine sinnvolle Entscheidung, sich so die langen Winterabende zu vertreiben.\\\';\r\nbreak;\r\n\r\ncase 25:\r\n$out=\\\'Ich sehe, Du hast ein Herz für Kuscheldämonen.\\\';\r\nbreak;\r\n\r\ncase 26:\r\n$out=\\\'Oh, wie ich sehe bist Du auf dem rechten Weg und nicht mehr weit von der Lösung des Rätsels entfernt. Lediglich die vereinten Kräfte der `balten Völker`b sind es, welche Dir jetzt noch fehlen um dein Ziel zu erreichen!\\\';\r\nbreak;\r\n\r\ncase 27:\r\n$out=\\\'Ja! Du hast es beinahe geschafft! Nur noch die Kraft `beines`b Mannes ist nötig um das Ziel zu erreichen! Du triffst ihn an der verfallenen Kirche.\\\';\r\nbreak;\r\n\r\ncase 28:\r\n$out=\\\'Du hast Dein Ziel erreicht. Die Fee, welche Dich für Deine Mühen belohnt, wartet auf der Wolkeninsel.\\\';\r\nbreak;\r\n\r\ncase 29:\r\n$out=\\\'Du siehst geschwächt aus. Man könnte beinahe meinen ein Drache hätte dich so zugerichtet, aber das würdest du ja sicherlich wissen. Deine Brosche hat erheblich an Kraft verloren, was mit einer Meditation, im Tempel der Weisen, eventuell rückgängig zu machen wäre.\\\';\r\nbreak;\r\n\r\ncase 30:\r\n$out=\\\'Die Götter haben mir verboten, Dir weitere Informationen über Deine Zukunft zu geben. Aber du darfst natürlich gerne wiederkommen und mir 30 Edelsteine schenken.\\\';\r\nbreak;\r\n\r\ndefault:\r\n$out=\\\'Wisse, wenn Du ein Déjà Vu hast, dann ändern SIE etwas am Netz des Schicksals. Diese Antwort war kostenlos.\\\';\r\n$session[\\\'user\\\'][\\\'gems\\\']+=$num;\r\n}\r\nreturn $out; }}\r\n`nNutze dieses Wissen weise!\r\n','Lsungshilfe','Allgemein','DS-Edition 3.0 (Salator)','Quest, Tausch, Lösung'),('regalia_history','','guild_manuals','','',''),('regalia_history_ext','','guild_manuals','Administratives','',''),('rpbio_hilfe','hilfe','rpbio','hilfe','',''),('rpbio_hilfe_alg','hilfe','rpbio','hilfe','',''),('runen_help','`b`4HILFE ZUM RUNENMEISTER`b`n`n\n\n`&1. Warum finde ich nur unidentifizierte Runen?`n\n`^Weil du wahrscheinlich noch nicht beim Runenmeister warst und die Runen identifizieren lassen hast.`n`n\n\n`&2.Warum habe ich nur so wenig unterschiedliche Runen?`n\n`^Die Runen haben eine unterschiedliche Häufigkeit. Also gibt es Runen, die man häufiger findet, als andere. Bei dem Punkt Runen anzeigen werden Dir die Runen aufgelistet, die Du schon kennst. Und bei jeder Rune steht da, wie häufig sie vorkommt.`n`n\n\n`&3. Wie geht das mit der Runenmagie?`n\n`^Du klickst auf Runenmagie beim Runenmeister. Dann siehst du eine Liste mit den Gegenständen, die du verwenden kanst und die Hinweise, dass Du 100 Gold bezahlen musst um die Kombination zu testen und, dass nicht alle Felder (Schalen) gefüllt sein müssen. `n`n\n\nZur Auswahl:`n\nDu klickst mit der Maus auf das Bild oder den Text und ziehst es/ihn mit gedrückter Maustaste auf ein freies Feld. Dabei muss sich der Mauszeiger über dem grauen Feld befinden. Beim Loslassen rutscht der Gegenstand automatisch an die Richtige Position!`n\nFalls du 3 Fehu - Runen haben solltest, probiere es einfach mal aus.`n`n\n\n`b`&Fehu + Fehu + Fehu = Uruz`b`n`n\n\n`^Zur Zeit sind folgende Magieergebnisse möglich:`n\n`&-Angriffswert der Waffe verbessern`n\n-Verteidigungswert der Rüstung verbessern`n\n-Erhöhung der permanenten Lebenspunkte`n\n-Erhöhung der Charmepunkte`n\n-andere Runen (siehe Beispiel)`n\n-andere Items (zb. schwarzer Dämonenschnitter)`n`n`n\n`&4. Kann ich nur \\\"mixen\\\"?`n\n`^Nein! Einzelne Runen haben auch bestimmte Fähigkeiten.`n\nz.B. Wiedererweckung, Weg zur Orkburg, Beim Kämpfen als \\\"Aktion\\\", Runden auffüllen, Entzaubern, Rüstung umbenennen...\n`n`n\n`&5. Welche Runenränge gibt es?`n\n`^-`qUnwissende(r)`n\n`^-`qLehrling`n\n`^-`qForscher(in)`n\n`^-`qWissende(r)`n\n`^-`qEingeweihte(r)`n\n`^-`qSeneschall`n\n`^-`qMatriarchin `^(w) `qPatriarch `^(m)`n','runen_hilfe','Administratives','',''),('su_extended_text_manual','`c`bAnleitung`b`c`n`n\n`^\nExtended Text soll dazu verwendet werden lange Texte aus den Dateien in die Datenbank auszulagern, um sie dadurch schneller mal ändern zu können.`nPrädestiniert hierfür sind z.B. die Regeln/F.A.Q. die GPL usw.`n`n\n`bVerwendung`b`n\nJeder Text muss eine eindeutige ID erhalten, damit diese in der Datenbank schnell gefundenn werden kann. Es dürfen dabei keine Zeichen außer Buchstaben, Ziffern, Binde- und Unterstrich verwendet werden (für die Progger: es wird der reguläre Ausdruck [^w-_] angewendet).`n\nDer Text darf frei nach Schnauze editiert werden und darf sowohl bekannte `^L`2O`3T`4G`5D`^ Tags ``1 usw. aber auch <b>HTML<b/> <i>Tags</i> enthalten.`n`n\n`n\n`bVerwendung von PHP Code (Für Progger)`b`n\nEs ist möglich PHP Quelltext, Variablen o.ä. innerhalb der Texte zu verwenden. Alles was sich zwischen den Zeichen { { und } } befindet wird als PHP Code interpretiert und evaluiert! Dabei gelten die für evaluierten Code gültigen Regeln:\n`n1. Der Quellcode muss eine gültige Syntax haben, es müssen auch Semikolon gesetzt werden.\n`n2. Evaluierter Code verhält sich wie eine Funktion, alle Variablen müssen ggf. mit \\\"global\\\" eingebunden werden.\n`n3. Evaluierter Code muss einen returnwert haben, der weiterverarbeitet werden kann`n`n\n`bBeispiel:`b`n\n{ { return $session[\\\'user\\\'][\\\'login\\\']; } } ergibt => `2NICHTS`^`n\nWarum? Die Variable $session existiert nicht im aktuelen Scope, sie muss erst eingebunden werden`n\n{ { global $session; return $session[\\\'user\\\'][\\\'login\\\']; } } ergibt => \n`2{{\nglobal $session;\nreturn $session[\\\'user\\\'][\\\'login\\\'];\n}}`^`n`n\n\n`bVerwendung im Code`b`nDer Code enthält zwei Funktionen:`n`n\n`2\n/**`n\n * Get an extended string from the database`n\n * @param string $str_text_id contains the id of the text`n\n * @param string $str_category contains the optional category, set to \\\"*\\\" to search in any category`n\n * @param bool $bool_get_as_array Set true to receive an array, else receive just the text`n\n * @return mixed - array or string (false if an error ocurred)`n\n */\n`n`3function get_extended_text($str_text_id = false,$str_category = \\\'\\\'standard\\\'\\\',$bool_get_as_array = false)`n`n\n\n`2/**`n\n * Write an extended text to the database`n\n * @param string $str_text_id contains the id of the text`n\n * @param string $str_text contains the text`n\n * @param string $str_category contains the optional category`n\n * @return bool`n\n */\n`n`3function set_extended_text($str_text_id = false,$str_text = false, $str_category = \\\'\\\'standard\\\'\\\')`n`^\n`nDiese Funktionen können dann in jeder Datei verwendet werden, um einen entsprechenden Text aus der DB abzurufen!`n`n\nViel Spass damit `nDragonslayer','su_manuals','','','');
/*!40000 ALTER TABLE `extended_text` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `faillog`
--

DROP TABLE IF EXISTS `faillog`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `faillog` (
  `eventid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post` tinytext NOT NULL,
  `ip` varchar(40) NOT NULL DEFAULT '',
  `acctid` int(11) unsigned DEFAULT NULL,
  `id` varchar(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`eventid`),
  KEY `date` (`date`),
  KEY `acctid` (`acctid`),
  KEY `ip` (`ip`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `faillog`
--

LOCK TABLES `faillog` WRITE;
/*!40000 ALTER TABLE `faillog` DISABLE KEYS */;
/*!40000 ALTER TABLE `faillog` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `flirts`
--

DROP TABLE IF EXISTS `flirts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `flirts` (
  `flirtid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `acctid1` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `acctid2` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `flirtcount` smallint(5) unsigned NOT NULL DEFAULT '0',
  `flirtstate` smallint(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`flirtid`),
  KEY `acctids` (`acctid1`,`acctid2`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Speichert Flirts / Beziehungsstatus zwischen zwei Spielern.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `flirts`
--

LOCK TABLES `flirts` WRITE;
/*!40000 ALTER TABLE `flirts` DISABLE KEYS */;
/*!40000 ALTER TABLE `flirts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `goldpartner`
--

DROP TABLE IF EXISTS `goldpartner`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `goldpartner` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `acctid` int(10) unsigned NOT NULL DEFAULT '0',
  `Name` varchar(50) NOT NULL DEFAULT '',
  `lookingfor` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `sex` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `description` varchar(200) DEFAULT NULL,
  `quest1` smallint(5) unsigned NOT NULL DEFAULT '0',
  `quest2` smallint(5) unsigned NOT NULL DEFAULT '0',
  `quest3` smallint(5) unsigned NOT NULL DEFAULT '0',
  `quest4` smallint(5) unsigned NOT NULL DEFAULT '0',
  `quest5` smallint(5) unsigned NOT NULL DEFAULT '0',
  `quest6` smallint(5) unsigned NOT NULL DEFAULT '0',
  `quest7` smallint(5) unsigned NOT NULL DEFAULT '0',
  `quest8` smallint(5) unsigned NOT NULL DEFAULT '0',
  `quest9` smallint(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `acctid` (`acctid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `goldpartner`
--

LOCK TABLES `goldpartner` WRITE;
/*!40000 ALTER TABLE `goldpartner` DISABLE KEYS */;
/*!40000 ALTER TABLE `goldpartner` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `history`
--

DROP TABLE IF EXISTS `history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `history` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `gamedate` varchar(25) NOT NULL DEFAULT '0000-00-00',
  `msg` text NOT NULL,
  `text` text NOT NULL,
  `acctid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `guildid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `hidden` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `acctid` (`acctid`),
  KEY `guildid` (`guildid`),
  KEY `hidden` (`hidden`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `history`
--

LOCK TABLES `history` WRITE;
/*!40000 ALTER TABLE `history` DISABLE KEYS */;
/*!40000 ALTER TABLE `history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `house_extensions`
--

DROP TABLE IF EXISTS `house_extensions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `house_extensions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(150) DEFAULT NULL,
  `houseid` int(10) unsigned NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `owner` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `level` smallint(5) unsigned NOT NULL DEFAULT '0',
  `loc` tinyint(3) unsigned DEFAULT NULL COMMENT 'Nur für Gemächer. Gibt Stockwerk an',
  `val` int(11) NOT NULL COMMENT 'Temporäres Flag',
  `content` text,
  PRIMARY KEY (`id`),
  KEY `loc` (`loc`),
  KEY `houseid` (`houseid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `house_extensions`
--

LOCK TABLES `house_extensions` WRITE;
/*!40000 ALTER TABLE `house_extensions` DISABLE KEYS */;
/*!40000 ALTER TABLE `house_extensions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `houses`
--

DROP TABLE IF EXISTS `houses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `houses` (
  `houseid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `number` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `owner` int(11) unsigned NOT NULL DEFAULT '0',
  `status` smallint(5) unsigned NOT NULL DEFAULT '0',
  `c_max_length` int(10) unsigned NOT NULL DEFAULT '0',
  `gold` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `gems` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `housename` varchar(60) DEFAULT NULL,
  `description` text NOT NULL,
  `cornerstone` text NOT NULL,
  `private_description` text NOT NULL,
  `attacked` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `pvpflag_houses` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `extension` int(10) unsigned NOT NULL DEFAULT '0',
  `trick` varchar(255) DEFAULT NULL,
  `build_state` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `lastchange` datetime NOT NULL,
  `dmg` smallint(5) unsigned NOT NULL,
  `dmg_info` varchar(255) NOT NULL,
  PRIMARY KEY (`houseid`),
  KEY `owner` (`owner`),
  KEY `status` (`status`),
  KEY `number` (`number`),
  KEY `attacked` (`attacked`),
  KEY `pvpflag_houses` (`pvpflag_houses`),
  KEY `extension` (`extension`),
  KEY `trick` (`trick`),
  KEY `build_state` (`build_state`),
  KEY `lastchange` (`lastchange`),
  KEY `dmg` (`dmg`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 PACK_KEYS=0;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `houses`
--

LOCK TABLES `houses` WRITE;
/*!40000 ALTER TABLE `houses` DISABLE KEYS */;
/*!40000 ALTER TABLE `houses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ipsperre`
--

DROP TABLE IF EXISTS `ipsperre`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ipsperre` (
  `ip` varchar(15) DEFAULT NULL,
  `search` int(11) DEFAULT NULL,
  `timelimit` double DEFAULT NULL,
  UNIQUE KEY `ip` (`ip`),
  KEY `timelimit` (`timelimit`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ipsperre`
--

LOCK TABLES `ipsperre` WRITE;
/*!40000 ALTER TABLE `ipsperre` DISABLE KEYS */;
/*!40000 ALTER TABLE `ipsperre` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `items`
--

DROP TABLE IF EXISTS `items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `items` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `owner` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `value1` int(11) NOT NULL DEFAULT '0',
  `value2` int(11) NOT NULL DEFAULT '0',
  `gold` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `gems` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `description` text NOT NULL,
  `hvalue` int(11) NOT NULL DEFAULT '0',
  `hvalue2` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `deposit1` int(255) unsigned NOT NULL,
  `deposit2` int(255) unsigned NOT NULL,
  `tpl_id` varchar(30) NOT NULL,
  `special_info` varchar(80) NOT NULL,
  `weight` smallint(5) unsigned NOT NULL DEFAULT '0',
  `item_count` int(10) unsigned DEFAULT '0',
  `sort_order` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `content` text COMMENT 'Feld für beliebige Informationen, am besten serialisierten Array',
  `hide` tinyint(1) NOT NULL DEFAULT '0',
  `usershopsowner` mediumtext NOT NULL,
  PRIMARY KEY (`id`),
  KEY `value1` (`value1`),
  KEY `owner` (`owner`),
  KEY `hvalue` (`hvalue`),
  KEY `value2` (`value2`),
  KEY `deposit2` (`deposit2`),
  KEY `tpl_id` (`tpl_id`),
  KEY `deposit` (`deposit1`,`deposit2`),
  KEY `item_count` (`item_count`),
  KEY `deposit1` (`deposit1`),
  KEY `deposit2_2` (`deposit2`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `items`
--

LOCK TABLES `items` WRITE;
/*!40000 ALTER TABLE `items` DISABLE KEYS */;
/*!40000 ALTER TABLE `items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `items_buffs`
--

DROP TABLE IF EXISTS `items_buffs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `items_buffs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `buff_name` varchar(30) NOT NULL,
  `type` tinyint(4) NOT NULL DEFAULT '0',
  `buff` text,
  `name` varchar(120) NOT NULL,
  `roundmsg` varchar(255) NOT NULL,
  `wearoff` varchar(255) NOT NULL,
  `effectmsg` varchar(255) NOT NULL,
  `effectnodmgmsg` varchar(255) NOT NULL,
  `effectfailmsg` varchar(255) NOT NULL,
  `rounds` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `atkmod` float unsigned NOT NULL DEFAULT '1',
  `defmod` float unsigned NOT NULL DEFAULT '1',
  `regen` mediumint(8) NOT NULL DEFAULT '0',
  `minioncount` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `minbadguydamage` varchar(255) NOT NULL,
  `maxbadguydamage` varchar(255) NOT NULL,
  `lifetap` float unsigned NOT NULL DEFAULT '0',
  `damageshield` float unsigned NOT NULL DEFAULT '1',
  `badguydmgmod` float unsigned NOT NULL DEFAULT '1',
  `badguyatkmod` float unsigned NOT NULL DEFAULT '1',
  `badguydefmod` float unsigned NOT NULL DEFAULT '1',
  `activate` varchar(100) NOT NULL,
  `plus_charm` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `survive_death` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `type` (`type`)
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `items_buffs`
--

LOCK TABLES `items_buffs` WRITE;
/*!40000 ALTER TABLE `items_buffs` DISABLE KEYS */;
INSERT INTO `items_buffs` VALUES (2,'Tempelfluch',1,'','Fluch der Tempelpriester','Der Fluch behindert dich!','Der Fluch lässt nach!','','','',500,0.8,0.8,0,0,'','',0,1,1,1,1,'offense,defense',0,0),(3,'Schlimmer Tempelfluch',1,'','Schlimmer Fluch der Tempelpriester','Der Fluch behindert dich!','Der Fluch lässt nach!','','','',500,0.5,0.5,0,0,'','',0,1,1,1,1,'offense,defense',0,0),(4,'Tempelsegen',1,'','Segen der Tempelpriester','Der Segen gibt dir Kraft.','Die Wirkung des Segens lässt nach.','','','',100,1.1,1.1,0,0,'','',0,1,1,1,1,'offense,defense',0,0),(5,'Magiebarriere',2,'','`!Magiebarriere`0','`!Ein hellblauer Energieschild blitzt auf und blockt den Angriff deines Gegners komplett ab.`0','`!Nach dem letzten Treffer verschwindet der Schild mit einem leisen Zischen..`0','','','',10,1,1,0,0,'','',0,1,0,0,1,'offense,defense',0,0),(6,'Verstärkter Schlag',2,'','`8Verstärkter Schlag`0','`8Dein Angriffswert ist für diesen Schlag leicht erhöht.','','','','',1,1.5,1,0,0,'','',0,1,1,1,0.5,'offense',0,0),(7,'Funkenregen',2,'','`gFunkenregen`0','','','`gEin Funken landet auf {badguy} und verursacht {damage} Schaden.`0','','',3,1,1,0,3,'$badguy[\'creaturehealth\']*0.02','$badguy[\'creaturehealth\']*0.05',0,0,1,1,1,'roundstart',0,0),(8,'Blitzschlag',2,'','`#Blitzschlag`0','','','`#Ein Blitz schlägt aus deiner Hand auf {badguy} und verursacht {damage} Schaden.`0','','',1,1,1,0,1,'$session[user][level]*3','$session[user][level]*8',0,1,1,1,1,'roundstart',0,0),(9,'Kleiner Heiltrank',2,'','`rKleiner Heiltrank`0','','','`rDu wirst um {damage} Punkte geheilt.`0','`rDer Heilzauber war wohl schon schlecht.`0','`rDer Heilzauber war wohl schon schlecht.`0',1,1,1,25,0,'','',0,1,1,1,1,'roundstart',0,0),(10,'Golem',2,'','`TGolem`0','','TDein Golem zerfällt zu Staub.`0','`TDein Golem trifft mit {damage} Schadenspunkten`0','`TDein Golem trifft, macht aber keinen Schaden`0','`TDein Golem ist zu langsam und schlägt daneben.`0',25,1,1,0,1,'$session[user][level]*5','$session[user][level]*10',0,0,1,1,1,'offense',0,0),(11,'Raserei',2,'','`^Raserei`0','`^Du führst in blinder Raserei einen besonders heftigen Angriff aus.`0','','','','',1,2,0.2,0,0,'','',0,1,1,1,1,'roundstart',0,0),(12,'Feuerball',2,'','`QFeuerball`0','','','`QDu schleuderst deinen Feuerball auf {badguy} und triffst ihn mit {damage} Schaden.`0','','',1,1,1,0,1,'$session[user][level]*4','$session[user][level]*10',0,1,1,1,1,'offense',0,0),(13,'Trank der Genesung',2,'','`&Zauber der Genesung`0','','','`tDu regenerierst 1000 Lebenspunkte!`0','','',1,1,1,1000,0,'','',0,1,1,1,1,'roundstart',0,0),(14,'Heldentrank',2,'','`%Heldentrank`0','','`%All deine Wunden verheilen auf der Stelle!`0','`%All deine Wunden verheilen auf der Stelle!`0','','',1,1,1,25000,0,'','',0,1,1,1,1,'roundstart',0,0),(15,'Großer Heiltrank',2,'','`tGroßer Heiltrank`0','','','`tDu regenerierst 100 Lebenspunkte.`0','`tDu kannst nicht noch mehr regenerieren.','',0,1,1,100,0,'','',0,1,1,1,1,'roundstart',0,0),(18,'Wachdackel',8,'','Lautes Kläffen','','','','','',0,30,30,150,0,'','',0,1,1,1,1,'',0,0),(19,'Haushund',8,'','Bissattacke','','','','','',0,50,50,200,0,'','',0,1,1,1,1,'',0,0),(20,'Kampfhund',8,'','Wütendes Zerfleischen','','','','','',0,75,75,300,0,'','',0,1,1,1,1,'',0,0),(21,'Heilkräuter',1,'','Heilkräuter','`1Die Heilkräuter heilen deine Wunden.`0','`1Die Heilkräuter verlieren ihre Wirkung!`0','','','',20,1,1,1,0,'','',0,1,1,1,1,'defense',0,0),(22,'Amulettaura',1,'','Amulettaura','`rDie Aura des Amuletts beschützt dich.`0','`rDie Aura des Amuletts verschwindet.`0','','','',10,1,1.1,0,0,'','',0,1,1,1,1,'roundstart',0,0),(23,'Schwächen-Fluch',1,'','`GSchwächen','`GEin mächtiger Fluch schwächt dich.','`GDer Fluch hat für heute seine Wirkung verloren.','','','',10,0.9,0.9,0,0,'','',0,1,1,1,1,'offense',0,0),(24,'Todesfluch',1,'','Todesfluch','`TDer Todesfluch verursacht dir höllische Schmerzen.`0','`TDer Todesfluch lässt für heute von dir ab.`0','','','',20,1,1,-1,0,'','',0,1,1,1,1,'roundstart',0,0),(25,'Blindheitsfluch',1,'','Blindheit','`1Du kannst kaum etwas sehen.`0','`1Deine Sehkraft kehrt für heute zurück.`0','','','',20,0.9,1,0,0,'','',0,1,1,1,1,'offense',0,0),(26,'Vampirfluch',1,'','Vampirfluch','`TEin Fluch saugt dich aus.`0','','','','',12,1,1,0,0,'','',0,1,1,1,1,'roundstart',0,0),(27,'Höllengestank',1,'','Höllengestank','`QDer verfluchte Höllengestank an dir macht deinen Gegner besonders aggressiv`0','`QDas Blut deines Gegners überdeckt den Höllengestank.`0','','','',10,1,1,0,0,'','',0,1,1,1.08,1,'offense',0,0),(28,'Antiserum',4,'','Antiserum','`QDu schluckst den Trank, würgst zuerst etwas.. dann jedoch spürst du das Bollwerk gegen Gift in dir wachsen!','','','','',1,1,1,0,0,'','',0,1,1,1,1,'',0,0),(29,'Zaubertrank für Angriff',2,'','Angriffszauber','','Die Wirkung des Zaubertranks lässt nach!','Der Zaubertrank erfüllt seine Wirkung!','','',20,1,0,0,0,'','',0,1,1,1,1,'offense,defense',0,0),(31,'Zaubertrank für Verteidigun',2,'','Verteidigungszauber','','Die Wirkung des Zaubertranks lässt nach!','Der Zaubertrank erfüllt seine Wirkung!','','',20,0,1,0,0,'','',0,1,1,1,1,'offense,defense',0,0),(32,'Hexenfluch',1,'','Fluch der Hexen','Der Fluch behindert dich!','Der Fluch lässt nach!','','','',250,0.6,0.6,0,0,'','',0,1,1,1,1,'offense,defense',0,0),(33,'Schlimmer Hexenfluch',1,'','Schlimmer Fluch der Hexen','Der Fluch behindert dich!','Der Fluch lässt nach!','','','',250,0.3,0.3,0,0,'','',0,1,1,1,1,'offense,defense',0,0),(34,'Segen der Hexen',1,'','Segen der Hexen','Der Segen gibt dir Kraft.','Die Wirkung des Segens lässt nach.','','','',75,1.2,1.2,0,0,'','',0,1,1,1,1,'offense,defense',0,0),(36,'Drachenreliquie - Kopfkissen',1,'','','','','','','',40,1,1,0,0,'','',0,1,1,1,1,'',0,0),(38,'`qUruz - Runenkraft',2,'','`qUruz - Runenkraft','Die Kraft der Uruzrune verleiht dir mehr Angriff!','Die Kraft der Uruzrune hat ihre Wirkung verloren!','','','',15,1.1,1,0,0,'','',0,1,1,1,1,'roundstart',0,0),(39,'`qThurisaz - Runenkraft',2,'','`qThurisaz - Runenkraft','Die Kraft der Thurisazrune verleiht dir mehr Verteidigung!','Die Kraft der Thurisazrune hat ihre Wirkung verloren!','','','',15,1,1.1,0,0,'','',0,1,1,1,1,'roundstart',0,0),(40,'`qIsa - Runenkraft',2,'','`qIsa - Runenkraft','Die Kraft der Isa-Rune lässt deinen Gegner leicht einfrieren','Die Kraft der Isa-Rune ist aufgebraucht.','','','',15,1,1,0,0,'','',0,1,1,0.66,0.66,'roundstart',0,0),(41,'`qFehu - Runenkraft',2,'','`qFehu - Runenkraft','','Die Kraft der Fehurune hat ihre Wirkung verloren!','','','',10,1,1,0,0,'','',0,1,1,1,1,'',0,0),(42,'Hauskatze',8,'','scharfe Krallen','','','','','',0,15,15,100,0,'','',0,1,1,1,1,'',0,0),(43,'`4Heiltrank A',2,'','`4Heiltrank A`0','','','`4Du wirst um {damage} Punkte geheilt.`0','`4Der Heiltrank war wohl schon abgelaufen.`0','`4Der Heiltrank war wohl schon abgelaufen.`0',1,1,1,10,0,'','',0,1,1,1,1,'roundstart',0,0),(44,'`4Heiltrank B',2,'','`4Heiltrank B`0','','','`4Du wirst um {damage} Punkte geheilt.`0','`4Der Heiltrank war wohl schon abgelaufen.`0','`4Der Heiltrank war wohl schon abgelaufen.`0',1,1,1,15,0,'','',0,1,1,1,1,'roundstart',0,0),(45,'`4Heiltrank C',2,'','`4Heiltrank C`0','','','`4Du wirst um {damage} Punkte geheilt.`0','`4Der Heiltrank war wohl schon abgelaufen.`0','`4Der Heiltrank war wohl schon abgelaufen.`0',1,1,1,25,0,'','',0,1,1,1,1,'roundstart',0,0),(46,'`4Heiltrank D',2,'','`4Heiltrank D`0','','','`4Du wirst um {damage} Punkte geheilt.`0','`4Der Heiltrank war wohl schon abgelaufen.`0','`4Der Heiltrank war wohl schon abgelaufen.`0',1,1,1,35,0,'','',0,1,1,1,1,'roundstart',0,0),(47,'`4Heiltrank E',2,'','`4Heiltrank E`0','','','`4Du wirst um {damage} Punkte geheilt.`0','`4Der Heiltrank war wohl schon abgelaufen.`0','`4Der Heiltrank war wohl schon abgelaufen.`0',1,1,1,50,0,'','',0,1,1,1,1,'roundstart',0,0),(48,'`@Einfaches Waffengift',2,'','`@Einfaches Waffengift','','`@Das Gift ist leider getrocknet.','`@Du bestreichst deine Waffe mit dem Gift.','','',10,1.05,1,0,0,'','',0,1,1,1,1,'offense, defense',0,0),(49,'`@Starkes Waffengift',2,'','`@Starkes Waffengift','','`@Das Gift ist leider getrocknet.','`@Du bestreichst deine Waffe mit dem Gift.','','',10,1.1,1,0,0,'','',0,1,1,1,1,'offense, defense',0,0),(50,'`yKleine Ninjakugel',2,'','`yKleine Ninjakugel','','','`yDu schleuderst deine Ninjakugel auf {badguy} und triffst ihn mit {damage} Schaden.','','',1,1,1,0,1,'12','15',0,1,1,1,1,'offense',0,0),(51,'`yGroße Ninjakugel',2,'','`yGroße Ninjakugel','','','`yDu schleuderst deine Ninjakugel auf {badguy} und triffst ihn mit {damage} Schaden.','','',1,1,1,0,1,'15','25',0,1,1,1,1,'offense',0,0),(52,'`yNinja-Gaskugel',2,'','`yNinja-Gaskugel','`yDas schwere Gas lässt deinen Gegner husten und keuchen.','`yDas Gas hat sich verzogen.','`yDu schleuderst deine Ninja-Gaskugel auf {badguy}.','','',4,1,1,0,1,'','',0,1,1,0.9,0.9,'offense',0,0),(54,'Hausgolem',8,'','Stampfen und Quetschen!','','','','','',0,100,100,1000,0,'','',0,1,1,1,1,'roundstart',0,0),(55,'`(Das glimmende Juwel`0',2,'','Das glimmende Juwel','Das Juwel stärkt deine Achtsamkeit','Das Juwel verglimmt innerlich.','Das Juwel beginnt in sich zu glimmen.','','Das Juwel verglimmt innerlich.',5,1,1.1,0,0,'','',0,1,1,1,1,'roundstart',0,0),(56,'Guter Hausgolem',8,'','Stampfen und Quetschen!','','','','','',0,200,200,1000,0,'','',0,1,1,1,1,'roundstart',0,0),(57,'`)Kraft der Sünde',2,'','`)Die Kraft der Sünden ist in dir.','`)Die Kraft der Sünden stärkt dich.','`)Die Kraft der Sünden verfällt.','','','',2,3,1,500,0,'','',0,1,1,1,1,'roundstart',0,0),(58,'`&Kraft der Tugend',2,'','`&Die Kraft der Tugend ist in dir.','`&Die Kraft der Tugend stärkt dich.','`&Die Kraft der Tugend verfällt.','','','',2,3,1,500,0,'','',0,1,1,1,1,'roundstart',0,0),(59,'Grottenpower',2,NULL,'`&G`yr`&o`yt`&t`ye`&n`yp`&o`yw`&e`yr`0','Die magische Kraft der Grottenolme lässt eine nie gekannte Kraft durch dich fliessen.','Die magische Kraft der Grottenolme versiegt.','','','',300,1,1,0,1,'','',0,1,1,1,1,'offense',0,0),(60,'Grottenpower',2,NULL,'`&G`yr`&o`yt`&t`ye`&n`yp`&o`yw`&e`yr`0','Die magische Kraft der Grottenolme lässt eine nie gekannte Kraft durch dich fliessen.','Die magische Kraft der Grottenolme versiegt.','','','',300,1,1,0,1,'','',0,1,1,1,1,'offense',0,0);
/*!40000 ALTER TABLE `items_buffs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `items_classes`
--

DROP TABLE IF EXISTS `items_classes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `items_classes` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `class_name` varchar(140) NOT NULL,
  `class_description` varchar(255) NOT NULL,
  `class_value1` varchar(60) NOT NULL,
  `class_value2` varchar(60) NOT NULL,
  `class_hvalue` varchar(40) NOT NULL,
  `class_hvalue2` varchar(40) NOT NULL,
  `class_gold` varchar(50) NOT NULL,
  `class_gems` varchar(50) NOT NULL,
  `class_special_info` varchar(255) NOT NULL,
  `class_order` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8 COMMENT='Speichert Kategorien für Items und Namen der Spalten.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `items_classes`
--

LOCK TABLES `items_classes` WRITE;
/*!40000 ALTER TABLE `items_classes` DISABLE KEYS */;
INSERT INTO `items_classes` VALUES (3,'Beute','Lala','','','','','Gold','Edelsteine','',90),(4,'Geschenk','','','','','','Gold','Edelsteine','',30),(7,'Möbel','','','','','','Gold','Edelsteine','',2),(8,'Waffen','','Angriff','','','','Gold','Edelsteine','',20),(9,'Fluch','','','','Verbleibende Tage','','Gold für Entfernung','Edelsteine für Entfernung','',0),(10,'Rüstungen','','Verteidigung','','','','Gold','Edelsteine','',20),(11,'Segen','','','','Verbleibende Tage','','Gold','Edelsteine','',0),(12,'Dokumente','','','','','','Gold','Edelsteine','',1),(13,'Seltenheit','','','','','','Gold','Edelsteine','',50),(14,'Zauber','','Verbleibende Portionen','','','','Gold','Edelsteine','',16),(15,'Haustiere','','','','','','Gold','Edelsteine','',0),(16,'Trophäe','','Drachenkills','','','','Gold','Edelsteine','',0),(17,'Tränke','','','','','','Gold','Edelsteine','',15),(18,'Apparatur','','','','','','Gold','Edelsteine','',2),(19,'Rune','','','','','','Gold','Edelsteine','',40),(20,'Runen Zwischenergebnis','','','','','','Gold','Edelsteine','',0),(21,'Kleintiere','','','','','','','','Geschlecht',0),(22,'Test-Items','','','','','','Gold','Edelsteine','',99),(24,'Zutaten','Kräuter und sonstige Zutaten für den alchemistischen Gebrauch.','','','','','Gold','Edelsteine','',1),(25,'Nahrungsmittel','','','','','','Gold','Edelsteine','',5),(26,'Rohstoffe','','','','','','Gold','Edelsteine','',5),(27,'Abfälle','','','','','','Gold','','',0),(28,'Sternensteine','Einzigartige Sternensteine','','','','','Gold','Edelsteine','',0),(29,'Saatgut','Dinge die man einpflanzen oder an die Vögel verfüttern kann','','','','','Gold','Edelsteine','',1),(30,'Kleidung','Beschreibung','','','','','Gold','Edelsteine','Designer',19),(32,'Sammlerstücke','Dinge die man sammeln und tauschen kann.','','','','','Gold','Edelsteine','',0),(33,'Erinnerungsstück','','','','','','Gold','Edelsteine','',2);
/*!40000 ALTER TABLE `items_classes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `items_combos`
--

DROP TABLE IF EXISTS `items_combos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `items_combos` (
  `combo_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `combo_name` varchar(50) NOT NULL,
  `id1` varchar(30) NOT NULL DEFAULT '0',
  `id2` varchar(30) NOT NULL DEFAULT '0',
  `id3` varchar(30) NOT NULL DEFAULT '0',
  `result` varchar(30) NOT NULL DEFAULT '',
  `buff` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `chance` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `no_order` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `no_entry` tinyint(1) unsigned NOT NULL COMMENT 'Kein Eintrag in Rezeptbuch?',
  `hook` varchar(20) NOT NULL,
  `hookcode` text NOT NULL,
  PRIMARY KEY (`combo_id`),
  KEY `type` (`type`),
  KEY `ids` (`id1`,`id2`,`id3`)
) ENGINE=InnoDB AUTO_INCREMENT=103 DEFAULT CHARSET=utf8 PACK_KEYS=0;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `items_combos`
--

LOCK TABLES `items_combos` WRITE;
/*!40000 ALTER TABLE `items_combos` DISABLE KEYS */;
INSERT INTO `items_combos` VALUES (3,'Heiltrank','fldrmsflgl','drnschuppe','','klnrhltrnk',0,2,255,0,0,'',''),(4,'Heldentrank-Rezept','drnschuppe','antiserum','ogerzahn','hldntrnk',0,2,100,0,0,'',''),(6,'Schutzstab','glasperle','abgnkno','frstein','magiebarr',0,2,110,0,0,'',''),(7,'Blitzring','lichtring','billring','','bltzschlg',0,2,250,0,0,'',''),(8,'Flammenwurf','metestaub','fackel','','feuerball',0,2,180,0,0,'',''),(10,'Bärenruf','brnfell','antiserum','hldntrnk','golem',0,2,90,0,0,'',''),(12,'Starkbier','klfale','klfale','klfale','strgale',0,2,255,0,0,'_codehook_','global $session,$item_hook_info,$combo;\r\n\r\nif($item_hook_info[\'min_chance\'] <= $combo[\'chance\']) {\r\n$session[\'user\'][\'drunkenness\'] += 5;\r\n\r\n$item_hook_info[\'victory_msg\'] = \'Das wird mal n heftiges Starkbier! Allein die Dämpfe der Prozedur rufen in dir ein heftiges Gefühl der Trunkenheit hervor.\';\r\n\r\n}'),(13,'Bierkonzentrat','strgale','strgale','strgale','concale',0,2,255,0,0,'',''),(14,'Drachenreliquienset','drrel_gld','drrel_ksn','','',5,1,0,0,0,'_codehook_','global $c,$item_hook_info;\r\n\r\noutput(\'`n`n`^`bDie vereinte Kraft der Drachenreliquien verleiht dir zusätzlichen magischen Schutz!`b`n`0\');\r\n\r\n$str_buffs = \',\'.$c[\'buff\'];\r\nitem_set_buffs(ITEM_BUFF_NEWDAY & ITEM_BUFF_FIGHT,$str_buffs);\r\n\r\n$item_hook_info[\'hookstop\'] = true;'),(16,'Hausbar','brschrnk','fssale','','vlbrschr',0,2,255,1,0,'',''),(17,'Suff','hsrcht','klfale','','',0,2,150,1,0,'_codehook_','// Item-Ergebnis kommt nicht zur Anwendung, dort können wir angeben was wir wollen\r\n\r\nglobal $session,$item_hook_info;\r\n\r\n$item_hook_info[\'hookstop\'] = true;\r\n\r\n$buff = array(\"name\"=>\"`1Besoffener Amoklauf`0\", \"rounds\"=>50, \"wearoff\"=>\"`1Du wirst allmählich wieder nüchtern!`0\", \"defmod\"=>0.5,\"atkmod\"=>2, \"roundmsg\"=>\"In deinem Suff prügelst du alles nieder was dir in den Weg kommt!\", \"activate\"=>\"defense,offense\");\r\n\r\n$session[\'bufflist\'][\'amok\']=$buff;\r\n\r\n$session[\'user\'][\'drunkenness\']=90;\r\n\r\noutput(\'`3Nach einigem wildem Herumgepansche bringst du eine etwas zähe Flüssigkeit zustande, die noch dazu bestialisch stinkt. Dennoch nimmst du todesmutig einen Schluck!`n`nAls du aus deiner Ohnmacht erwachst, verschwimmt alles vor deinen Augen. Doch bemerkenswerterweise hast du auf einmal eine immense Lust, irgendetwas zu zerstören! Da kommt dir die Keule gerade recht... \');\r\n\r\naddnews(\"`^Heute holten die Frauen schleunigst ihre Kinder ins Haus als `@\".$session[\'user\'][\'name\'].\"`^ in einem volltrunkenen Amoklauf die Stadt unsicher machte.`0\");\r\n'),(19,'Minotaurus-Ex','abgnkno','altknochen','ogerzahn','minvernelx',0,2,150,1,0,'',''),(20,'Blumenstrauß','blume','blume','blume','blmstrss',0,2,255,0,0,'_codehook_',''),(21,'Insigniensplitter','zbrtafel','drnei','frstein','insgnteil',0,2,25,0,0,' ',''),(22,'r_mix_Rezept der Stärke','r_fehu','r_fehu','r_fehu','r_uruz',0,4,255,0,0,' ',''),(23,'r_mix_Rezept der Autorität','r_uruz','r_uruz','r_uruz','r_thurisaz',0,4,255,0,0,' ',''),(24,'r_mix_schwarz_schnitter','r_gebo','r_berkana','r_ingwaz','r_mix_nr1',0,4,255,0,0,'',''),(25,'r_mix_Rezept des Dämonenschwertes','r_mix_nr1','waffedummy','','dmons',0,4,0,0,0,' ',''),(26,'r_mix_thbth_amor4','r_thurisaz','r_algiz','r_thurisaz','r_mix_amr4',0,4,0,0,0,'',''),(27,'r_mix_Rezept der schützenden Autorität','r_mix_amr4','rstdummy','','r_amrup_4',0,4,0,0,0,' ',''),(28,'r_mix_teihaur_weapon4','r_teiwaz','r_hagalaz','r_uruz','r_mix_wpn4',0,4,255,0,0,'',''),(29,'r_mix_Rezept der starken Herausforderung','r_mix_wpn4','waffedummy','','r_wpnup_4',0,4,255,0,0,' ',''),(30,'r_mix_Rezept des Schönlings','r_raidho','r_thurisaz','r_wunjo','r_cmup_5',0,4,255,0,0,' ',''),(31,'r_mix_Rezept des Wortes','r_thurisaz','r_thurisaz','r_thurisaz','r_ansuz',0,4,0,0,0,' ',''),(32,'r_mix_Rezept des Weltenzyklus','r_ansuz','r_ansuz','r_ansuz','r_raidho',0,4,255,0,0,' ',''),(33,'r_mix_Rezept der Erleuchtung','r_raidho','r_raidho','r_raidho','r_kenaz',0,4,255,0,0,' ',''),(34,'Genesung','fshkpf','fldrmsflgl','drnschuppe','zbrdgnsg',0,2,155,0,0,'',''),(35,'r_mix_Rezept des Geschenks','r_kenaz','r_kenaz','r_kenaz','r_gebo',0,4,255,0,0,' ',''),(36,'r_mix_Rezept der Ausgewogenheit','r_gebo','r_gebo','r_gebo','r_wunjo',0,4,255,0,0,' ',''),(37,'r_mix_Rezept der Herausforderung','r_wunjo','r_wunjo','r_wunjo','r_hagalaz',0,4,255,0,0,' ',''),(38,'r_mix_Rezept des Bedürfnisses','r_hagalaz','r_hagalaz','r_hagalaz','r_naudiz',0,4,255,0,0,' ',''),(39,'r_mix_Rezept des Stillstands','r_naudiz','r_naudiz','r_naudiz','r_isa',0,4,255,0,0,' ',''),(40,'r_mix_Rezept der Ernte','r_isa','r_isa','r_isa','r_jera',0,4,255,0,0,' ',''),(41,'r_mix_Rezept der Transformation','r_jera','r_jera','r_jera','r_eiwaz',0,4,255,0,0,' ',''),(42,'r_mix_Rezept der Entscheidung','r_eiwaz','r_eiwaz','r_eiwaz','r_pethro',0,4,255,0,0,' ',''),(43,'r_mix_Rezept des Schutzes','r_pethro','r_pethro','r_pethro','r_algiz',0,4,255,0,0,' ',''),(44,'r_mix_Rezept des Glücks','r_algiz','r_algiz','r_algiz','r_sowilo',0,4,255,0,0,' ',''),(45,'r_mix_Rezept der Einweihung','r_sowilo','r_sowilo','r_sowilo','r_teiwaz',0,4,255,0,0,' ',''),(46,'r_mix_Rezept des Neubeginns','r_teiwaz','r_teiwaz','r_teiwaz','r_berkana',0,4,255,0,0,' ',''),(47,'r_mix_Rezept des Fortschritts','r_berkana','r_berkana','r_berkana','r_ehwaz',0,4,255,0,0,' ',''),(48,'r_mix_Rezept des Lebenslaufs','r_ehwaz','r_ehwaz','r_ehwaz','r_mannaz',0,4,255,0,0,' ',''),(49,'r_mix_Rezept des Einklangs','r_mannaz','r_mannaz','r_mannaz','r_laguz',0,4,255,0,0,' ',''),(50,'r_mix_Rezept der inneren Kraft','r_laguz','r_laguz','r_laguz','r_ingwaz',0,4,255,0,0,' ',''),(51,'r_mix_Rezept des Lichts','r_ingwaz','r_ingwaz','r_ingwaz','r_dagaz',0,4,255,0,0,' ',''),(52,'r_mix_Rezept der Konzentration','r_dagaz','r_dagaz','r_dagaz','r_othala',0,4,255,0,0,' ',''),(53,'r_mix_Rezept der mächtigen Lebenskraft','r_othala','r_othala','r_othala','r_lpup_100',0,4,0,0,0,' ',''),(54,'Killereichhörnchen','squirr','erdnuss','','squirra',0,2,250,0,0,'_codehook_','global $item_hook_info;\r\n\r\n$bool_female = (bool)e_rand(0,1);\r\n$str_sex = ($bool_female ? \'Weibchen\' : \'Männchen\');\r\n$item_hook_info[\'product\'][\'tpl_special_info\']\r\n= $str_sex;'),(55,'Todeshörnchen','ogerzahn','squirra','','squirrb',0,2,225,0,0,'_codehook_','global $item_hook_info;\r\n\r\n$item_hook_info[\'product\'][\'tpl_special_info\']\r\n= $item_hook_info[\'items_in\'][1][\'special_info\'];\r\n\r\nif (strpos($item_hook_info[\'items_in\'][1][\'name\'],\"(\"))\r\n{\r\n$oldname=substr($item_hook_info[\'items_in\'][1][\'name\'],0,strrpos($item_hook_info[\'items_in\'][1][\'name\'],\"(\"));\r\ntrim($oldname);\r\n$item_hook_info[\'product\'][\'tpl_name\']=$oldname.\" `&(`4Todes`thörnchen`&)`0\";\r\n}\r\n'),(57,'Antiserum','gftph','klnrhltrnk','','antiserum',0,2,255,0,0,'',''),(58,'Partyhörnchen','squirra','strgale','vllaschbch','squirrf',0,2,255,0,0,'_codehook_','global $item_hook_info;\r\n\r\n$item_hook_info[\'product\'][\'tpl_special_info\']\r\n= $item_hook_info[\'items_in\'][0][\'special_info\'];\r\n\r\nif (strpos($item_hook_info[\'items_in\'][0][\'name\'],\"(\"))\r\n{\r\n$oldname=substr($item_hook_info[\'items_in\'][0][\'name\'],0,strrpos($item_hook_info[\'items_in\'][0][\'name\'],\"(\"));\r\ntrim($oldname);\r\n$item_hook_info[\'product\'][\'tpl_name\']=$oldname.\" `&(`%P`!a`@r`^t`4y`thörnchen`&)`0\";\r\n}\r\n'),(62,'\"Wolle Petry\"-Gedächtnisplakette','frndbnd','frndbnd','frndbnd','wlpplak',0,2,255,0,0,'',''),(63,'Mahlzeit','fleischbr','gewuerze','squirrd','',0,8,100,1,0,'_codehook_','global $session;\r\n\r\noutput(\"`&Dir mundet deine Mahlzeit sehr und du erhältst `@einen permanenten Lebenspunkt`& dazu und regenerierst voll!`n`n\");\r\n\r\n$session[user][maxhitpoints]++;\r\n$session[user][hitpoints]=$session[user][maxhitpoints];\r\n'),(64,'Knappenrettung','elfknst','trph','zbrtafel','',0,2,127,1,0,'_codehook_','global $session;\r\n\r\n$sql = \"SELECT name,state,oldstate FROM disciples WHERE state>=0 AND master=\".$session[\'user\'][\'acctid\'];\r\n$result = db_query($sql) or die(db_error(LINK));\r\n$amount=db_num_rows($result);\r\nif ($amount<1)\r\n{\r\noutput(\"`@Der Zauber gelingt und vor dir steht ein junger Knabe, den du noch nie in deinem Leben gesehen hast!`n`n`&Du beschliesst den Jungen schnellstmöglich vor die Tür zu setzen, bevor man dich noch wegen Kindesentführung einsperrt!`n`0\");\r\n}\r\nelse\r\n{ \r\n$row = db_fetch_assoc($result);\r\nif ($row[\'state\']>0)\r\n{\r\noutput(\"`@Der Zauber gelingt und vor dir steht \".$row[\'name\'].\"`@ mit hochrotem Kopf und heruntergelassenen Hosen.`nDu hast den ärmsten wohl direkt von der Latrine zu dir gezaubert!`n`nDa euch beiden dieses Ereignis überaus peinlich ist versprecht ihr euch gegenseitig, nie wieder ein Wort darüber zu verlieren.`n`0\");\r\n}\r\nelse\r\n{\r\noutput(\"`@Der Zauber gelingt und vor dir steht \".$row[\'name\'].\"`@, sichtlich froh darüber, dich wiederzusehen.`nWohin er verschleppt wurde vermagst du nicht in Erfahrung zu bringen, aber er ist in bester Verfassung und scheint keinen bleibenden Schaden davon getragen zu haben.`n`nAllerding ist er von den Strapazen so sehr erschöpft, dass er dir wohl erst morgen wieder aktiv zur Seite stehen kann.`n`0\");\r\ndebuglog(\'rettete seinen Knappen durch Alchemie.\');\r\n$sql = \"UPDATE disciples SET state=$row[oldstate] WHERE master=\".$session[\'user\'][\'acctid\'];\r\ndb_query($sql);\r\n$sql = \'UPDATE account_extra_info SET disciples_spoiled=disciples_spoiled-1 WHERE acctid = \'.$session[\'user\'][\'acctid\'];\r\ndb_query($sql) or die(sql_error($sql));\r\n\r\n} \r\n}'),(65,'Terrorhörnchen','squirra','wndsbl','','squirrg',0,2,200,0,0,'_codehook_','global $item_hook_info;\r\n\r\n$item_hook_info[\'product\'][\'tpl_special_info\']\r\n= $item_hook_info[\'items_in\'][0][\'special_info\'];\r\n\r\nif (strrpos($item_hook_info[\'items_in\'][0][\'name\'],\" `&(\"))\r\n{\r\n$oldname=substr($item_hook_info[\'items_in\'][0][\'name\'],0,strrpos($item_hook_info[\'items_in\'][0][\'name\'],\" `&(\"));\r\ntrim($oldname);\r\n$item_hook_info[\'product\'][\'tpl_name\']=$oldname.\" `&(`&T`)e`&r`)r`&o`)r`thörnchen`&)`0\";\r\n}\r\n'),(66,'Großer Heiltrank','fshkpf','hlkrter','','grsshltrnk',0,2,190,0,0,'',''),(67,'`4Heiltrank A','wasser','gaense','halit','heilA',0,2,250,0,0,'',''),(68,'`4Heiltrank B','wasser','knoblauch','kastanie','heilB',0,2,245,0,0,'',''),(69,'`4Heiltrank C','wasser','hopfen','halit','heilC',0,2,240,0,0,'',''),(70,'`4Heiltrank D','wasser','thymian','wermut','heilD',0,2,240,0,0,'',''),(71,'`4Heiltrank E','wasser','silberdis','mistel','heilE',0,2,235,0,0,'',''),(72,'`9Kleiner Zaubertrank','alkohol','Kalmus','metestaub','zauberkl',0,2,150,0,0,'',''),(73,'`9Großer Zaubertrank','alkohol','dragblood','lichtstein','zaubergr',0,2,100,0,0,'',''),(74,'`@Einfaches Waffengift','alkohol','tollkirsch','halit','giftlei',0,2,200,0,0,'',''),(75,'`@Starkes Waffengift','schwefel','mohn','nachtsch','giftschw',0,2,175,0,0,'',''),(76,'`yGroße Ninjakugel','salpeter','salzsaeure','alkohol','ninjagro',0,2,150,0,0,'',''),(77,'`yKleine Ninjakugel','salpeter','schwefel','wasser','ninjaklei',0,2,180,0,0,'',''),(78,'`yNinja-Gaskugel','alkohol','salzsaeure','schwefel','ninjagas',0,2,190,0,0,'',''),(80,'Stinkbombe','socken','wasser','','stnkbmb',0,2,255,1,0,'_codehook_','global $session;\r\n\r\nif(\r\n($item_hook_info[\'items_in\'][0][\'tpl_id\'] == \'socken\' && false === strpos($item_hook_info[\'items_in\'][0][\'special_info\'],\'Stinkend\'))\r\n||\r\n($item_hook_info[\'items_in\'][1][\'tpl_id\'] == \'socken\' && false === strpos($item_hook_info[\'items_in\'][1][\'special_info\'],\'Stinkend\'))\r\n)\r\n { \r\n\r\noutput(\'`n`n`&Was auch immer du damit bezweckst, dieses Paar Socken mit destilliertem Wasser zu gießen: Weder sprießen kleine Sockenkinder, noch erhältst du etwas, das man entfernt als Ergebnis bezeichnen könnte. Vielleicht sind diese Socken einfach noch zu neu..`n`n\');\r\n\r\n$item_hook_info[\'hookstop\'] = true;\r\n\r\n}\r\nelse {\r\n\r\n}\r\n\r\n '),(81,'Absinth','alkohol','Anis','wermut','absinth',0,2,190,0,0,'',''),(82,'Met','honig','hefe','wasser','met',0,2,230,0,0,' ',''),(84,'Yin-Yang','glckskeks','glckskeks','glckskeks','yinyang',0,2,255,0,0,'',''),(85,'Zaubertafel','kltonschbe','lichtstein','','zbrtafel',0,2,60,0,0,' ',''),(86,'Trophäenkombo','trph','trph','trph','',0,2,255,0,0,'stock',''),(87,'mahlzeit2','*','halit','zwiebel','kochkunst',0,8,240,0,0,'mittagessen',''),(89,'Ei eines grünen Drachen','b_gd_shell','b_gd_shell','b_gd_shell','b_gd_egg',0,2,255,0,0,' ',''),(90,'Eine seltsam grün leuchtende Sphäre','b_gd_egg','b_gd_egg','b_gd_egg','b_gd_res',0,2,255,0,0,' ',''),(91,'Ei eines schwarzen Drachen','b_bd_shell','b_bd_shell','b_bd_shell','b_bd_egg',0,2,255,0,0,' ',''),(92,'Eine seltsam schwarz leuchtende Sphäre','b_bd_egg','b_bd_egg','b_bd_egg','b_bd_res',0,2,255,0,0,' ',''),(93,'Holzkreuz','sthlbn','sthlbn','rstngl','holzkreuz',0,2,255,0,0,' ',''),(94,'Jesus-Figur','holzkreuz','trph','rstngl','jesuskreuz',0,2,150,0,0,' ',''),(95,'Beliebiges Gericht 1','*','*','*','mhlzt_res1',0,8,255,0,1,'kitchen',''),(96,'Beliebiges Gericht 2','*','*','','mhlzt_res1',0,8,255,0,1,'kitchen',''),(97,'Trophäenlanze','sthlbn','trph','','trphlanze',0,2,255,1,0,'_codehook_','global $item_hook_info;\r\n\r\nif ($item_hook_info[\'items_in\'][1][\'value2\']==7)\r\n{\r\n$oldname=substr($item_hook_info[\'items_in\'][1][\'name\'],13);\r\ntrim($oldname);\r\n$item_hook_info[\'product\'][\'tpl_name\']=\'Trophäenlanze `&(`4\'.$oldname.\'`&)`0\';\r\n$item_hook_info[\'product\'][\'tpl_gold\']=$item_hook_info[\'items_in\'][1][\'gold\']+5;\r\n$item_hook_info[\'product\'][\'tpl_value1\']=$item_hook_info[\'items_in\'][1][\'value1\'];\r\n$item_hook_info[\'product\'][\'tpl_hvalue\']=$item_hook_info[\'items_in\'][1][\'hvalue\'];\r\n}\r\nelseif ($item_hook_info[\'items_in\'][0][\'value2\']==7)\r\n{\r\n$oldname=substr($item_hook_info[\'items_in\'][0][\'name\'],13);\r\ntrim($oldname);\r\n$item_hook_info[\'product\'][\'tpl_name\']=\'Trophäenlanze `&(`4\'.$oldname.\'`&)`0\';\r\n$item_hook_info[\'product\'][\'tpl_gold\']=$item_hook_info[\'items_in\'][0][\'gold\']+5;\r\n$item_hook_info[\'product\'][\'tpl_value1\']=$item_hook_info[\'items_in\'][0][\'value1\'];\r\n$item_hook_info[\'product\'][\'tpl_hvalue\']=$item_hook_info[\'items_in\'][0][\'hvalue\'];\r\n}\r\nelse\r\n{\r\noutput(\'Das Ergebnis sieht aber nicht gerade aus wie das, was du erwartet hast. Ein Kopf wäre sicherlich besseres Ausgangsmaterial gewesen...\');\r\n$item_hook_info[\'product\'][\'tpl_name\']=\'Gammelfleisch am Spieß\';\r\n$item_hook_info[\'product\'][\'tpl_description\']=\'Dies ist das Ergebnis eines offensichtlich fehlgeschlagenen Alchemie-Experiments. \'.$item_hook_info[\'items_in\'][0][\'name\'].\'`0 und \'.$item_hook_info[\'items_in\'][1][\'name\'].\'`0, das kann ja nur Mist werden...\';\r\n$item_hook_info[\'product\'][\'tpl_gold\']=1;\r\n}\r\n'),(99,'Magischer Vogelkäfig','grssvglkfg','dragblood','erz','magvglkfg',0,2,240,0,0,' ',''),(100,'Feder Hugins','blackquill','lichtring','billring','r_fdr_hgn',0,2,255,0,0,' ',''),(101,'Feder Munins','blackquill','metestaub','fackel','r_fdr_mnn',0,2,255,0,0,' ',''),(102,'Wurzelbier','common_root','strgale','','common_rootbeer',0,2,255,1,0,' ','');
/*!40000 ALTER TABLE `items_combos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `items_tpl`
--

DROP TABLE IF EXISTS `items_tpl`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `items_tpl` (
  `tpl_id` varchar(30) NOT NULL,
  `tpl_name` varchar(255) NOT NULL,
  `tpl_class` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `tpl_description` text NOT NULL,
  `tpl_gold` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `tpl_gems` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `tpl_value1` int(11) NOT NULL DEFAULT '0',
  `tpl_value2` int(11) NOT NULL DEFAULT '0',
  `tpl_hvalue` int(11) NOT NULL DEFAULT '0',
  `tpl_hvalue2` mediumint(9) NOT NULL DEFAULT '0',
  `tpl_special_info` varchar(128) NOT NULL,
  `tpl_weight` smallint(5) unsigned NOT NULL DEFAULT '0',
  `tpl_stackable` tinyint(3) unsigned DEFAULT '1',
  `find_forest` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `loose_forest_death` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `loose_dragon` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `loose_dragon_death` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `maxcount` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `deposit` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `deposit_private` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `deposit_guild` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `deposit_show` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `throw` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `distributor` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `showinvent` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `guildinvent` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `battle` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `curse` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `vendor` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `vendor_new` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `spellshop` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `giftshop` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `alchemy` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `stables_pet` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `battle_mode` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `hot_item` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `equip` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `prisonescloose` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `newday_del` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `newday_hook` varchar(20) DEFAULT NULL,
  `newday_furniture_hook` varchar(20) DEFAULT NULL,
  `pvp_defeat_hook` varchar(20) DEFAULT NULL,
  `pvp_victory_hook` varchar(20) DEFAULT NULL,
  `forest_death_hook` varchar(20) DEFAULT NULL,
  `gift_hook` varchar(20) DEFAULT NULL,
  `furniture_hook` varchar(20) DEFAULT NULL,
  `furniture_private_hook` varchar(20) DEFAULT NULL,
  `furniture_privateinvited_hook` varchar(20) DEFAULT NULL,
  `furniture_guild_hook` varchar(20) DEFAULT NULL,
  `use_hook` varchar(20) DEFAULT NULL,
  `find_forest_hook` varchar(20) DEFAULT NULL,
  `battle_hook` varchar(20) DEFAULT NULL,
  `send_hook` varchar(20) DEFAULT NULL,
  `equip_hook` varchar(20) DEFAULT NULL,
  `trade_hook` varchar(20) DEFAULT NULL,
  `buff1` int(10) unsigned NOT NULL DEFAULT '0',
  `buff2` int(10) unsigned NOT NULL DEFAULT '0',
  `hookcode` text NOT NULL,
  `cooking` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `tpl_content` text,
  `auction` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT 'Im Auktionshaus versteigerbar?',
  `maxcount_per_user` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT 'Maximale Anzahl pro Account',
  `usershops` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT 'In den Usershops (Markt) anbietbar?',
  PRIMARY KEY (`tpl_id`),
  KEY `showinvent` (`showinvent`),
  KEY `find_forest` (`find_forest`),
  KEY `giftshop` (`giftshop`),
  KEY `vendor_new` (`vendor_new`),
  KEY `alchemy` (`alchemy`),
  KEY `hot_item` (`hot_item`),
  KEY `tpl_class` (`tpl_class`),
  KEY `tpl_name` (`tpl_name`),
  KEY `tpl_gold` (`tpl_gold`),
  KEY `tpl_gems` (`tpl_gems`),
  KEY `tpl_value1` (`tpl_value1`),
  KEY `tpl_value2` (`tpl_value2`),
  KEY `tpl_hvalue` (`tpl_hvalue`),
  KEY `tpl_special_info` (`tpl_special_info`),
  KEY `tpl_weight` (`tpl_weight`),
  KEY `tpl_stackable` (`tpl_stackable`),
  KEY `loose_forest_death` (`loose_forest_death`),
  KEY `loose_dragon` (`loose_dragon`),
  KEY `loose_dragon_death` (`loose_dragon_death`),
  KEY `maxcount` (`maxcount`),
  KEY `deposit` (`deposit`),
  KEY `deposit_private` (`deposit_private`),
  KEY `deposit_guild` (`deposit_guild`),
  KEY `deposit_show` (`deposit_show`),
  KEY `throw` (`throw`),
  KEY `distributor` (`distributor`),
  KEY `guildinvent` (`guildinvent`),
  KEY `battle` (`battle`),
  KEY `curse` (`curse`),
  KEY `vendor` (`vendor`),
  KEY `spellshop` (`spellshop`),
  KEY `stables_pet` (`stables_pet`),
  KEY `battle_mode` (`battle_mode`),
  KEY `equip` (`equip`),
  KEY `prisonescloose` (`prisonescloose`),
  KEY `newday_del` (`newday_del`),
  KEY `newday_hook` (`newday_hook`),
  KEY `newday_furniture_hook` (`newday_furniture_hook`),
  KEY `pvp_defeat_hook` (`pvp_defeat_hook`),
  KEY `pvp_victory_hook` (`pvp_victory_hook`),
  KEY `forest_death_hook` (`forest_death_hook`),
  KEY `gift_hook` (`gift_hook`),
  KEY `furniture_hook` (`furniture_hook`),
  KEY `furniture_private_hook` (`furniture_private_hook`),
  KEY `furniture_privateinvited_hook` (`furniture_privateinvited_hook`),
  KEY `furniture_guild_hook` (`furniture_guild_hook`),
  KEY `use_hook` (`use_hook`),
  KEY `find_forest_hook` (`find_forest_hook`),
  KEY `battle_hook` (`battle_hook`),
  KEY `send_hook` (`send_hook`),
  KEY `equip_hook` (`equip_hook`),
  KEY `trade_hook` (`trade_hook`),
  KEY `buff1` (`buff1`),
  KEY `buff2` (`buff2`),
  KEY `auction` (`auction`),
  KEY `maxcount_per_user` (`maxcount_per_user`),
  KEY `usershops` (`usershops`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Enthält Schablonen für Items nebst deren Eigenschaften.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `items_tpl`
--

LOCK TABLES `items_tpl` WRITE;
/*!40000 ALTER TABLE `items_tpl` DISABLE KEYS */;
INSERT INTO `items_tpl` VALUES ('6667','`#Diamantring',4,'`7Ein Ring aus Weißgold mit einem reinen, fein geschliffenen, glitzernden Diamanten. Der Traum aller Frauen, ein wertvolles Exemplar. Ein Geschenk von {name}`7!',2000,5,0,0,0,0,'',0,0,0,0,0,0,0,10,15,0,1,1,1,1,0,0,0,0,0,0,1,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,1),('abakus','Abakus',7,'Man kann damit rechnen - man kann es aber auch sein lassen!',1000,5,0,0,0,0,'',0,0,0,0,0,0,0,10,10,1,1,0,0,1,0,0,0,2,1,0,0,1,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,'abakus','abakus',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,1,0,0),('abartigk','zufällige Abartigkeit',4,'`n{name} `^hat dich mit diesem... tollen... Etwas... beglückt.`0 ',1000,0,0,0,0,0,'',0,0,0,0,0,0,0,100,100,100,0,1,0,1,1,0,0,0,0,0,1,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,'_codehook_',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'$number=e_rand(1,16);\r\nswitch ($number)\r\n{\r\n	case 1:\r\n	$str_name=\'Nietenbesetztes Lederhalsband\';\r\n	$str_dsc=\'Ein schreckliches Lederhalsband mit verrosteten Nieten. Das muss wohl irgendwo unter dem neulich zusammengebrochenen Billigbordell hervorgekramt worden sein.\';\r\n	break;\r\n\r\n	case 2:\r\n	$str_name=\'Tuch voll grünem Glibber\';\r\n	$str_dsc = \'Buäh, grüner Glibber mit Bröckchen. Du möchtest gar nicht wissen, aus welcher verseuchten Nase das entfleucht ist.\';\r\n	break;\r\n\r\n	case 3:\r\n	$str_name=\'T-Shirt mit dem Gesicht von \'.getsetting(\'newplayer\',\'\');\r\n	$str_dsc = \'Das T-Shirt ist so ziemlich das dämlichste, was du dir vorstellen kannst... Auf der Rückseite steht: Der neueste Bewohner von Atrahor - ich war dabei!\';\r\n	break;\r\n\r\n	case 4:\r\n	$str_name=\'Poster von Fürst \'.getsetting(\'fuerst\',\'\');\r\n	$str_dsc = \'Ein überlebengroßes Poster mit Starbiographie. Leicht vergilbt und riecht muffig.\';\r\n	break;\r\n\r\n	case 5:\r\n	$str_name=\'Eine Hand voll Nutztierdung\';\r\n	$str_dsc = \'Frisch, duftend und saftig, solch frischen Mist kann man immer gebrauchen.\';\r\n	break;\r\n\r\n	case 6:\r\n	$str_name=\'Eine leere Urne mit deinem Namen\';\r\n	$str_dsc = \'Praktisch denken, Särge schenken sagte schon immer der alte Ramius. Aber das ist absolut geschmacklos!\';\r\n	break;\r\n\r\n	case 7:\r\n	$str_name=\'Tütchen geröstete Fußnägel\';\r\n	$str_dsc = \'Vergilbte, dreckige Fußnägel, bis zur Perfektion fritiert, ein Leckerbissen für jeden Abfalleimer.\';\r\n	break;\r\n\r\n	case 8:\r\n	$str_name=\'Ein Liebesbrief auf Haut geschrieben\';\r\n	$str_dsc = \'Ein romantischer Liebesbrief, stilecht geschrieben mit einer Schwanenfeder und königsblauer Tinte...auf frisch abgepulter Haut...\';\r\n	break;\r\n\r\n	case 9:\r\n	$str_name=\'Gutschein für eine Darmspiegelung\';\r\n	$str_dsc = \'Ein Gutschein für eine Darmspiegelung beim kautzigen Heiler im Wald... mit kostenlosem erfrischendem Aderlass.\';\r\n	break;\r\n\r\n	case 10:\r\n	$str_name=\'Einladung zu einem Abendessen\';\r\n	$str_dsc = \'Eine Einladung zu einem Abendessen, wie nett... In Kleinbuchstaben steht da geschrieben:\"Mit dem Minotaurus im Schloss\".\';\r\n	break;\r\n\r\n	case 11:\r\n	$str_name=\'Drachenkampf erprobtes Schild\';\r\n	$str_dsc = \'Ein angeblich absolut feuerresistentes Drachenkampfschild - aus Styropor... was auch immer das sein mag.\';\r\n	break;\r\n\r\n	case 12:\r\n	$str_name=\'Ein Bungeesprung\';\r\n	$str_dsc = \'Ein Sprung von Bellerophontes Turm an einem Gummiseil... Moment... Gummi wird doch erst in ein paar hundert Jahren erfunden?\';\r\n	break;\r\n\r\n        case 13:\r\n	$str_name=\'Sehr alte Banane\';\r\n	$str_dsc = \'Auf den ersten Blick hättest du sie für eine Gurke gehalten, wäre da nicht das Fell.\';\r\n	break;\r\n\r\n        case 14:\r\n	$str_name=\'Portion Labskaus\';\r\n	$str_dsc = \'Schmeckt genauso wie es klingt. Nur schlimmer.\';\r\n	break;\r\n\r\n        case 15:\r\n	$str_name=\'Hübsche Dose\';\r\n	$str_dsc = \'Diese Pandora wird es dir schon nicht übel nehmen, wenn du mal kurz reinsiehst, bevor du sie ihr zurück gibst.\';\r\n	break;\r\n\r\n         case 16:\r\n	$str_name=\'`&Ein Pfund `^Elf am Spieß`0\';\r\n	$str_dsc = \'Das sind 500 gute Gründe, endlich einmal den Metzger zu wechseln.\';\r\n	break;\r\n\r\n}\r\n\r\n$hook_item[\'tpl_name\'] = $str_name;\r\n\r\n$hook_item[\'tpl_description\'] = $str_dsc.$hook_item[\'tpl_description\'];',0,NULL,0,0,0),('abgnkno','Abgenagter Knochen',3,'Ein alter abgenagter Knochen. Viel lässt sich damit nicht anfangen - genau genommen gar nichts...',1,0,0,0,0,0,'',0,1,4,0,1,0,0,0,0,0,0,1,1,1,1,0,0,3,0,0,0,1,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,1,0,1),('absinth','Flasche `2A`@bs`sin`@t`2h`0',25,'Eine Flasche gefüllt mit dem grünen Teufelszeug, dessen Geruch schon allein wilde Halluzinationen hervorrufen kann. Dieser Version der Grünen Fee spricht man 70% Umdrehungen zu.\r\n`4Von Kindern und Frauen fernhalten!`s ',150,0,1,0,0,0,'',0,1,0,0,1,0,0,2,2,0,0,1,1,1,1,0,0,0,0,2,0,1,0,3,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'_codehook_',NULL,'_codehook_',NULL,NULL,NULL,0,0,'global $session,$badguy;\r\n\r\nif (e_rand(1,4)==2)\r\n{\r\n  output(\"`&Du hältst zunächst skeptisch deine Nase über die Flaschenöffnung, der liebliche Anis-Geruch steigt dir zu Kopf und du denkst dir:\\\"`@ Hey so schlimm kanns ja nicht werden.\\\"`& So nimmst du einen kräftigen Schluck, der wie Feuer in deinem Hals brennt. Taumelnd glaubst du bei all deinen Sinnen schwören zu können, das du eine XXL Version der Grünen Fee an deiner Seite sehen kannst. Übelkeit überkommt dich und bei allem was dir heilig ist schwörst du, dieses Teufelszeug nie wieder anzurühren.\");\r\n  $session[\'bufflist\'][\'greenxxl\']=array(\"name\"=>\"`2Übelkeit \",\"rounds\"=>15, \"wearoff\"=>\"Die Wirkung lässt nach, die XXL Fee verlässt dich.\", \"atkmod\"=>0.2,\"defmod\"=>0.2,\"roundmsg\"=>\"Die Übelkeit behindert dich beim Kampf! \",\"activate\"=>\"offense,defense\");\r\n}\r\nelse\r\n{\r\n  output(\"`&Du hältst zunächst skeptisch deine Nase über die Flaschenöffnung, der liebliche Anis-Geruch steigt dir zu Kopf und du denkst dir:\\\"`@ Hey so schlimm kanns ja nicht werden.\\\"`& So nimmst du einen kräftigen Schluck, der wie Feuer in deinem Hals brennt. Taumelnd glaubst du bei all deinen Sinnen schwören zu können, dass du die Grüne Fee an deiner Seite sehen kannst.\");\r\n  if (isset($session[\'bufflist\'][\'greenfairy\']))\r\n  {                              \r\n     $session[\'bufflist\'][\'greenfairy\'][\'rounds\'] += 20;\r\n  }\r\n  else\r\n  {                                 \r\n     $session[\'bufflist\'][\'greenfairy\']=array(\"name\"=>\"`@Grüne Fee\",\"rounds\"=>20, \"wearoff\"=>\"Die Wirkung lässt nach, die Grüne Fee verlässt dich.\", \"atkmod\"=>1.5, \"defmod\"=>1.25, \"roundmsg\"=>\"Du hast die Kraft der Grünen Fee! \",\"activate\"=>\"offense,defense\");\r\n  }\r\n}\r\n\r\n$session[\'user\'][\'drunkenness\']+=49;\r\nitem_delete(\' id=\'.$item[\'id\']);\r\nif (!$badguy[\'creaturehealth\']>0)\r\n{\r\n  addnav(\'Zum Inventar\',$item_hook_info[\'ret\']);\r\n}',0,NULL,0,0,1),('acofutter','Acolytenfutter',3,'Enthält 5 Sorten Nüsse und Rosinen. Was will der strebsame Jüngling mehr?',55,0,0,0,0,0,'',0,1,3,0,1,0,0,100,100,0,0,1,1,1,1,0,0,3,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,1,0,1),('alchemtgl','Alchemistischer Schmelztiegel',18,'`5Bei diesem exquisiten Stück handelt es sich um einen magischen Schmelztiegel. Die darin enthaltene Kraft unterstützt allerlei alchemistische Prozeduren und ermöglicht die Herstellung neuer Dinge aus verschiedenen Zutaten.`0',10000,50,0,0,0,0,'',0,1,0,0,0,0,0,1,1,1,1,1,0,1,0,0,0,2,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,'alchemie','alchemie',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,1,0,0),('alkohol','Alkohol',24,'Reiner Alkohol. Nicht für den Verzehr geeignet.',25,0,0,0,0,0,'',0,1,0,0,1,0,0,0,0,0,0,1,1,1,1,0,0,0,0,3,0,1,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,1),('alrfrcht','Alraunenfrucht',24,'Die seltene Frucht schmeckt süßbitter und soll stark aphrodisierend wirken. Da sie allerdings auch in hoher Dosis tödlich ist willst du es gar nicht erst versuchen.',50,0,0,0,0,0,'',0,1,0,0,1,0,0,0,0,0,0,1,1,1,1,0,0,3,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'_codehook_',NULL,NULL,NULL,NULL,NULL,0,0,'global $item_hook_info,$session,$item;\r\n\r\noutput(\'Du öffnest die Frucht und findest 3 Kerne!\');\r\n\r\nfor ($i=1;$i<=3;$i++)\r\n{\r\nitem_add($session[\'user\'][\'acctid\'],\'alrsaat\');\r\n}\r\n\r\naddnav(\'Zum Inventar\',$item_hook_info[\'ret\']);\r\n\r\nitem_delete(\' id=\'.$item[\'id\']);\r\n\r\nreturn(true);',0,NULL,1,0,1),('alrsaat','Alraunensamen',29,'Saatgut für eine Alraune',30,0,8,0,0,0,'',0,1,0,0,1,0,0,0,0,0,0,1,1,1,1,0,0,0,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,1),('alrwrzl','Alraunenwurzel',24,'Diese magische Wurzel ähnelt in ihrer Form tatsächlich einem Menschen - und du glaubst sogar ihn zu kennen.',100,0,0,0,0,0,'',0,1,0,0,1,0,0,10,5,0,0,1,1,1,1,0,0,0,0,0,0,1,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,1),('altknochen','Alter Knochen',3,'Wertloser Plunder',2,0,0,0,0,0,'',0,1,3,0,1,0,0,0,0,0,0,1,1,1,1,0,0,3,0,0,0,1,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,1,0,1),('altstiefel','`qAlter Stiefel',7,'',1,0,0,0,0,0,'',0,0,0,0,1,0,0,0,0,0,0,1,1,1,1,0,0,2,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,'','',NULL,NULL,NULL,NULL,NULL,'send_as_gift',NULL,NULL,0,0,'',0,NULL,1,0,1),('analloni_f','Anallôni Blumen',13,'Die Anallôni Blumen wirken unscheinbar, doch dies mag nicht über ihre magische Kraft hinweg täuschen. Man spricht ihrem Sud besondere Kräfte zu. Allerdings treten sie nur relativ selten auf.',100,1,0,0,0,0,'',0,1,0,0,0,0,0,1,1,0,0,1,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,0),('analloni_s','Anallôni Harzstein',13,'Ein Harzstein, gewonnen aus dem Sud der zwölf Anallôni Blüten. Der Stein enthält das Bild eines Klosters und ist in einen kleinen Rahmen mit Kettchen eingefasst.',1000,5,0,0,0,0,'',0,1,0,0,1,0,0,1,0,0,0,1,0,1,0,0,0,2,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,1,0,0),('analphplak','Analphabetikerplakette',7,'Ein großes Schild, das über der Tür an der Aussenwand des Hauses angebracht wird. Trägt die Aufschrift \"`^Wer Rechtschreibfeeler findet, kann sie behalten!`0\"',500,0,0,0,0,0,'',0,0,0,0,0,0,0,10,10,1,1,0,1,1,0,0,0,2,1,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift',NULL,NULL,0,0,'',0,NULL,1,0,1),('angel','Angelausrüstung',18,'Naja, genau betrachtest hast du noch keine Angel. Aber der Wille zählt.',0,0,0,0,0,0,'',0,1,0,0,0,0,0,0,0,0,0,0,0,1,0,0,0,2,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,1,0,0),('Anis','Anis',24,'Anis ist eine Gewürz- und Heilpflanze. Nicht  zu verwechseln mit Dill.',25,0,0,0,0,0,'',0,1,0,0,1,0,0,0,0,0,0,1,1,1,1,0,0,0,0,3,0,1,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,1),('anrcht','Anrichte',7,'Eine glatt polierte Arbeitsfläche bedeckt einen kleinen Schrank, sodass man davorstehend ohne Probleme Essen zubereiten kann.',5000,30,0,0,0,0,'',0,0,0,0,0,0,0,1,1,1,1,0,1,1,0,0,0,2,1,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift',NULL,NULL,0,0,'',0,NULL,1,0,1),('antieplak','Anti-Elfenplakette',7,'Ein großes Schild, das über der Tür an der Aussenwand des Hauses angebracht wird. Trägt die Aufschrift \"`2Elfen haben doofe Ohren.`0\"',500,0,0,0,0,0,'',0,0,0,0,0,0,0,10,10,0,1,0,1,1,0,0,0,2,1,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift',NULL,NULL,0,0,'',0,NULL,1,0,1),('antiserum','Antiserum',3,'Dieses Mittelchen wirkt garantiert gegen jede Form der Truhenfalle. Allerdings nur einmal.',200,0,1,0,0,0,'',0,1,1,1,1,0,0,0,0,0,0,1,1,1,1,0,0,3,0,0,0,1,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'zauber',NULL,NULL,NULL,NULL,NULL,28,0,'',0,NULL,1,0,1),('apocal','`lApokalypse-`@Drache',4,'`lEin Plüschdrache mit nur noch einem Auge, an verschiedenen Stellen angekokelt. Die Atrahor-Apokalypse hat sichtlich Spuren hinterlassen, so fehlt diesem Plüschdrachen etwa sein Blumenbeet, weswegen er aus seinem noch verbliebenen Glasauge besonders traurig dreinblickt. Verwaschen und verwittert ist inmitten all der Brandlöcher ein Schriftzug zu erkennen: `$\"Apokalypse in Atrahor - JA, auch ich bin ein Opfer! Wir werden alle sterben, besonders {name}`$, hat mich nämlich als billiges Geschenk missbrau..\"`l Der Rest ist unleserlich..',500,0,0,0,0,0,'',0,0,0,0,0,0,0,10,5,10,1,1,1,1,0,0,0,0,0,0,1,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,1),('apple','Apfel',25,'Ein kleiner, schmackhafter Apfel',15,0,0,0,100,0,'',0,1,0,0,1,0,0,0,0,0,0,1,1,1,1,0,0,3,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'_codehook_',NULL,NULL,NULL,NULL,'kitchen',0,0,'global $item_hook_info,$session,$item;\r\n\r\noutput(\'Du verspeist den leckeren Apfel.`nDie \');\r\n\r\n$amount = (e_rand(2,4));\r\n \r\noutput($amount.\' Kerne, die du findest, steckst du dir ein.\');\r\n\r\nfor ($i=1;$i<=$amount;$i++)\r\n{\r\nitem_add($session[\'user\'][\'acctid\'],\'appletree\');\r\n}\r\n\r\naddnav(\'Zum Inventar\',$item_hook_info[\'ret\']);\r\n\r\nitem_delete(\' id=\'.$item[\'id\']);\r\n\r\nreturn(true);',1,NULL,1,0,1),('appletree','Apfelkerne',29,'Daraus kann einmal ein großer Apfelbaum werden.',10,0,4,0,0,0,'',0,1,0,0,1,0,0,0,0,0,0,1,1,1,1,0,0,0,1,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,1),('apron_01','Leinenschürze',30,'Eine einfache Schürze, wie man sie in der Küche oder bei der Arbeit trägt, um die Kleidung vor Schmutz zu schützen.',500,0,0,0,1,1,'Flavius Nadelflink',0,1,0,0,0,0,0,0,0,0,0,1,1,1,0,0,0,0,0,0,0,0,0,0,0,2,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift','_codehook_',NULL,0,0,'if($item[value1]>0) \r\n{\r\n$item[\'value1\']=0;\r\n}',0,NULL,0,0,1),('apron_02','Lederschürze',30,'Eine Schürze aus dicker Schweinehaut mit festem Nacken- und Bauchgurt. Ideal für die Schmiede und andere schweißtreibende Arbeit.',2000,0,0,0,0,1,'Flavius Nadelflink',0,1,0,0,0,0,0,0,0,0,0,1,1,1,0,0,0,0,0,0,0,0,0,0,0,2,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift','_codehook_',NULL,0,0,'if($item[value1]>0) \r\n{\r\n$item[\'value1\']=0;\r\n}',0,NULL,0,0,1),('Armband','`rArmband',4,'`)Ein Armband aus Weißgold, mit schönen, bunten Edelsteinen besetzt. `$Rot`), `1Blau`), `2Grün`), `^Gelb`), `&Weiß`), - alles, was das Herz begehrt. Von {name}.',4000,3,0,0,0,0,'',0,0,0,0,0,0,0,10,10,0,1,1,0,1,0,0,0,2,0,0,1,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,1,0,0),('assplak','Assassinenplakette',7,'Ein großes Schild, das über der Tür an der Aussenwand des Hauses angebracht wird. Trägt die Aufschrift \"`4Schnell, sauber und diskret.`0\"',500,0,0,0,0,0,'',0,0,0,0,0,0,0,10,10,1,1,0,1,1,0,0,0,2,1,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift',NULL,NULL,0,0,'',0,NULL,1,0,1),('atrahorium','Atrahorium',7,'Ein glasklarer Kristall, der laut einer Legende Atrahor in seinem Inneren abbildet.',2500,1,0,0,0,0,'',0,0,0,0,0,0,0,5,5,0,1,1,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,0),('ausweider','`4Ausweider`0',8,'Das legendäre Schwert `4Ausweider`0, welches das Goldene Ei für dich aus dem See geholt hat!',25000,0,25,0,0,0,'',0,1,0,0,1,0,0,0,0,0,0,0,0,1,0,0,0,0,0,0,0,0,0,0,0,1,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,0),('auszeichng','Auszeichnung',7,'',0,0,0,0,0,0,'',0,0,0,0,0,0,0,100,100,0,1,1,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,0),('awkmpfhnd','Ausgewachsener Kampfhund',15,'Ein riesiger Kampfhund, der bereit ist, jeden Eindringling sofort zu zerfleischen. Laufende Kosten: 500 Gold, 1 Edelstein.',25000,45,500,1,0,0,'',0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,20,0,'',0,NULL,0,0,0),('axt','`THolzfälleraxt',8,'Eine grobe Holzfälleraxt, mit der gewiss jeder Baum ein wenig Feuerholz lassen wird.',2000,5,0,0,0,0,'',0,1,0,0,0,0,0,0,0,0,0,1,0,1,0,0,0,0,1,0,0,0,0,0,0,1,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'_codehook_',NULL,0,0,'if($item[value1]>0) \r\n{\r\n$item[\'value1\']=0;\r\n}',0,NULL,1,0,0),('Badeessenz','`rBadeessenz',4,'Eine große Phiole wohlduftende Badeessenz. Einfach in den Badezuber geben und genießen! Alleine, oder auch mit {name}. ',1000,0,0,0,0,0,'',0,0,0,0,0,0,0,10,10,0,1,1,0,1,0,0,0,0,0,0,1,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,0),('Ballmaske','`(Ballmaske (Mann)',4,'`)Geschaffen für den Mann, es glitzert nicht, weist keine Prunkvollen Verzierungen auf und ist auch sonst schlicht gehalten. In deiner Lieblingsfarbe gewählt, hat {name}`) gehofft, dir eine Freude zu bereiten. ',1000,5,0,0,0,0,'',0,0,0,0,0,0,0,10,10,0,1,1,0,1,0,0,0,0,0,0,1,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,0),('bchrrgl','Bücherregal',7,'Ein großes, mehrstöckiges Bücherregal aus dunklem Holz, gefüllt mit allen bekannten Büchern und Nachschlagewerken. Ein Muss für den vornehmen Hausherrn!',30000,20,0,0,0,0,'',0,0,0,0,0,0,0,1,1,1,1,0,0,1,0,0,0,2,1,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,'buecher','buecher',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,1,0,0),('bdwnn','Badewanne',7,'Eine Badewanne aus Messing, die auf kleinen Füßen steht. Halterungen für Handtuch und Seife sind an der Seite angebracht.',2000,15,0,0,0,0,'',0,0,0,0,0,0,0,2,2,1,1,0,1,1,0,0,0,2,1,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift',NULL,NULL,0,0,'',0,NULL,1,0,1),('bdzber','`TB`wad`sez`wube`Tr',7,'Aus Holz und groß genug für 2.',0,10,0,0,0,0,'',0,0,0,0,0,0,0,10,10,0,1,0,1,1,0,0,0,2,1,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift',NULL,NULL,0,0,'',0,NULL,1,0,1),('beet','Blumenbeet',13,'Ein Blumenbeet mit wunderschönen Blüten.',1,0,0,0,0,0,'',0,1,0,0,0,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1,0,0,0,0,0,1,'beet',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,0),('bett','`&Bett`0',7,'Ein einfaches Bett für einfache Leute. ',500,1,0,0,0,0,'',0,0,0,0,0,0,0,10,10,0,1,0,1,1,0,0,0,2,1,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift',NULL,NULL,0,0,'',0,NULL,1,0,1),('beutdummy','Beutedummy',3,'',1,0,0,0,0,0,'',0,0,0,0,1,0,0,5,5,0,0,1,1,1,1,0,0,3,0,0,0,1,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,1,0,1),('bigchest','Große Truhe',7,'Groß genug um allerlei Krempel zu verstauen. Wird mit einem Schloss geliefert, um neugierige Mitbewohner fern zu halten.',10000,50,0,0,0,0,'',0,0,0,0,0,0,0,1,1,0,1,0,0,1,0,0,0,2,0,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,'chest','chest',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,1,0,0),('billring','Billiger Ring',3,'Ein Ring aus einfachem Metall. Hässlich, aber dafür immerhin völlig nutzlos.',5,0,0,0,0,0,'',0,1,4,0,1,0,0,0,0,0,0,1,1,1,1,0,0,3,0,0,0,1,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,1,0,1),('blackjewel','Juwel',13,'Eines der farbigen Juwelen, welches du von Hexe aus dem Wald erhalten hast.',1000,0,1,1,1,0,'',0,1,0,0,0,0,0,0,0,0,0,0,0,1,0,0,0,0,0,0,0,0,0,1,0,0,0,1,'_codehook_',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'_codehook_',NULL,NULL,NULL,55,0,'global $session, $item;\r\nswitch ($hook_type)\r\n{\r\n	case \'newday\':\r\n		{\r\n			$arr_content = unserialize($hook_item[\'content\']);\r\n			$arr_content[\'level\'] = (int)$arr_content[\'level\'];\r\n			if($arr_content[\'level\']*10<$session[\'user\'][\'dragonkills\'] && $arr_content[\'level\'] <= 10 ||\r\n			($session[\'user\'][\'dragonkills\']>149 && $arr_content[\'level\']==11) ||\r\n			($session[\'user\'][\'dragonkills\']>199 && $arr_content[\'level\']==12))\r\n			{\r\n				output(\'`n`(Das Juwel von Hexe löst sich auf, du bedarfst dessen Stärke nicht mehr.`0\');\r\n				item_delete(\'id=\'.$hook_item[\'id\']);\r\n			}\r\n			else \r\n			{\r\n				$hook_item[\'value1\'] = (int)$arr_content[\'level\'];\r\n				item_set(\'id=\'.$hook_item[\'id\'],$hook_item);\r\n			}\r\n			break;\r\n		}\r\n	case \'battle\':\r\n		{\r\n			//Der Buff darf x mal durchgeführt werden, danach wird er nicht mehr ausgeführt,\r\n			//aber nicht gelöscht, es füllt sich beim newday wieder \r\n			if($hook_item[\'value1\'] == 1)\r\n			{\r\n				$hook_item[\'value1\']--;\r\n				item_set_buffs(0,$item[\'buff1\']);\r\n				output(\'Die Kraft des Juwels ist für heute verbraucht.\');\r\n				item_set(\'id=\'.$hook_item[\'id\'],$hook_item);\r\n				\r\n				$item_hook_info[\'hookstop\'] = true;\r\n			}\r\n			break;\r\n		}\r\n}',0,NULL,0,0,0),('blackquill','`~schwarze `7Feder',13,'`sEine der `~schwarzen `7Federn`s, welche der `7mysteriöse Mann`s in der `7verlassenen Waldkirche `shinterlassen hat. Durch ihren `7einzigartigen Glanz`s sieht sie `7äußerst wertvoll `saus.',0,10,0,0,0,0,'',0,1,0,0,0,0,0,1,1,0,0,1,0,1,0,0,0,2,0,0,0,1,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,1,0,0),('blinklicht','ein {farbiges} Blinklicht`0',3,'Ein magisches Licht. Mal brennt es und mal brennt es nicht.',250,0,0,0,0,0,'Christbaumschmuck',0,1,0,7,1,7,0,10,10,0,0,1,1,1,0,0,0,0,0,0,0,0,0,0,0,0,5,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'_codehook_',NULL,NULL,NULL,NULL,0,0,'$number=e_rand(1,7);\r\nswitch ($number)\r\n{\r\ncase 1:\r\n$str_name=\'`#blaues\';\r\nbreak;\r\n\r\ncase 2:\r\n$str_name=\'`$rotes\';\r\nbreak;\r\n\r\ncase 3:\r\n$str_name=\'`@grünes\';\r\nbreak;\r\n\r\ncase 4:\r\n$str_name=\'`fsilbernes\';\r\nbreak;\r\n\r\ncase 5:\r\n$str_name=\'`^goldenes\';\r\nbreak;\r\n\r\ncase 6:\r\n$str_name=\'`&weißes\';\r\nbreak;\r\n\r\ncase 7:\r\n$str_name=\'`%pinkes\';\r\nbreak;\r\n\r\n}\r\n\r\n$hook_item[\'tpl_name\'] = str_replace(\'{farbiges}\',$str_name,$hook_item[\'tpl_name\']);\r\n',0,NULL,0,0,1),('blmnstrss','Großer Strauß bunter Blumen',4,'Großer Strauß bunter Blumen.',1,0,0,0,0,0,'',0,0,0,0,0,0,0,10,10,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'_codehook_',NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,0),('blmntpf','Blumentopf',13,'`3Dies ist ein besonderes Exemplar eines Blumentopfes. Aus schmutzig-rötlichem Ton gefertigt, eher grob in der Beschaffenheit, doch wie geschaffen für eine Petunie... letztere ist leider nicht enthalten.',50,1,0,0,0,0,'',0,1,0,2,1,2,0,1,1,0,1,1,0,1,1,0,0,2,0,0,0,1,0,0,0,0,2,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,1,0,0),('blmstrss','`^Bl`yum`&enst`rra`xuß',4,'Ein kleiner Strauß frischer, bunter Frühlingsblumen, mit einem Seidenband zusammengehalten. Das richtige Geschenk, wenn eine Rose nicht angemessen erscheint.',1,0,0,0,0,0,'',0,0,0,0,0,0,0,10,10,1,1,1,1,1,1,0,0,0,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,1),('blmvs','Blumenvase',7,'Eine kostbare, bemalte Vase aus feinstem Porzellan, die auch ohne Inhalt ein echter Blickfang ist.',0,5,0,0,0,0,'',0,0,0,0,0,0,0,2,2,1,1,0,1,1,0,0,0,2,1,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift',NULL,NULL,0,0,'',0,NULL,1,0,1),('blsmtvorh','`9blaue `&Samtvorhänge`0',7,'Lange und schwere Vorhänge aus `9blauem`& Samt`0.',5000,10,0,0,0,0,'',0,0,0,0,0,0,0,10,10,5,1,0,1,1,0,0,0,2,1,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift',NULL,NULL,0,0,'',0,NULL,1,0,1),('bltgttstat','`$Blutgott `^Statue`0',7,'Zum Anbeten, als Briefbeschwerer oder einfach nur zum Hinstellen.',1000,75,0,0,0,0,'',0,0,0,0,0,0,0,10,10,1,1,0,1,1,0,0,0,2,1,0,0,1,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift',NULL,NULL,0,0,'',0,NULL,1,0,1),('bltzschlg','`#Blitzschlag`0',14,'Diese Spruchrolle lässt einen mächtigen Blitz aus deiner Hand in den Gegner fahren.',500,0,1,1,0,0,'',0,1,0,0,1,0,0,0,0,0,0,0,1,1,1,0,0,0,0,1,0,0,0,3,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,8,0,'',0,NULL,0,0,1),('blume','Bunte Blume',4,'Ein wahnsinnig buntes Blütenblatt.',1,0,0,0,0,0,'',0,0,0,0,0,0,0,10,10,0,0,1,1,1,1,0,0,2,0,0,0,1,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,1,0,1),('Blumengest','Blumengesteck',4,'Zu besonderen Anlässen, wie zum Beispiel eine Hochzeit, oder eine Beerdigung. Den hat {name} dir zukommen lassen.',5000,0,0,0,0,0,'',0,0,0,0,0,0,0,10,10,0,1,1,1,1,0,0,0,0,0,0,1,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,1),('bohnen','Bohnen',25,'Bohnen, frisch aus dem Garten, gepflückt von biologisch abbaubaren Erntehelfern.',20,0,40,0,40,0,'',0,1,0,0,0,0,0,0,0,0,0,1,1,1,1,0,0,3,1,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'kitchen',0,0,'',1,NULL,1,0,1),('brnfell','`TBär`ten`Tfell',7,'Setz dich allein oder zu zweit drauf und genieße nicht nur das knistern im Kamin.',6000,4,0,0,0,0,'',0,0,0,0,0,0,0,10,10,0,1,0,1,1,0,0,0,2,1,0,0,1,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift',NULL,NULL,0,0,'',0,NULL,1,0,1),('Brosche','`QB`qro`ts`qch`Qe',4,'`7Eine Brosche aus Weißgold, mit funkelnden Diamanten in Form einer Lilie. Ein entzückendes Geschenk von {name}`7.',3000,3,0,0,0,0,'',0,0,0,0,0,0,0,10,10,0,1,1,1,1,0,0,0,2,0,0,1,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,1,0,1),('brot','Brot',25,'Ein gebackenes Getreidererzeugnis, das zumeist in Scheiben geschnitten und mit anderen Nahrungsmittel belegt verzehrt wird.',50,0,0,0,0,0,'',0,1,0,0,1,0,0,0,1,0,1,1,1,1,0,0,0,3,1,0,0,0,0,0,0,0,0,1,'kitchen','kitchen',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'kitchen',NULL,NULL,NULL,NULL,NULL,0,0,'',1,NULL,0,0,1),('brschrnk','Barschrank',7,'Ein großer breiter verspiegelter Schrank, der große Mengen von Alkohol fassen kann. Wird natürlich ohne Inhalt verkauft.',5000,10,0,0,0,0,'',0,0,0,0,0,0,0,10,10,1,1,0,1,1,0,0,0,2,1,0,0,1,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift',NULL,NULL,0,0,'',0,NULL,1,0,1),('bttvorl','`tBe`yttvorle`tger`0',7,'Flauschig weich und rutschfest für den gefahrlosen Sprung aus dem Bett.',500,1,0,0,0,0,'',0,0,0,0,0,0,0,10,10,0,1,0,1,1,0,0,0,2,1,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift',NULL,NULL,0,0,'',0,NULL,1,0,1),('Butter','Butter',25,'Ein Pfund frisch geschlagener Butter.',20,0,0,0,0,0,'',0,1,0,0,2,0,0,0,0,0,0,1,0,1,0,0,0,3,1,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',1,NULL,0,0,0),('b_bd_egg','`yEi eines schwarzen Drachen',16,'Das Ei eines schwarzen Drachens',0,50,0,0,0,0,'',0,1,0,0,0,0,0,0,0,0,0,1,0,1,0,0,0,2,0,2,0,1,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'_codehook_',NULL,NULL,NULL,NULL,NULL,0,0,'global $session, $item, $item_hook_info;\r\noutput (\r\nget_title(\'Das Drachenei\').\'Du beschließt dir das Ei nochmal ein wenig genauer anzuschauen, doch als du in deinem Inventar danach suchst, musst du schweren Herzens feststellen, dass es bei deinen Abenteuern zu Bruche gegangen ist. Du hast gelernt, dass du vorsichtiger mit solchen empfindlichen Dingen umgehen musst.`n\r\nDeine Erfahrung steigt um 35%. Vielleicht kann man ja mehrere dieser Eier kombinieren...\r\n\');\r\n$session[\'user\'][\'experience\']*=1.35;\r\ndebuglog(\'Benutzte schwarzes Ei: EXP*1.35\');\r\n\r\naddnav(\'Zurück\',$item_hook_info[\'ret\']);\r\n\r\nitem_delete(\' id=\'.$item[\'id\']);',0,NULL,1,0,0),('b_bd_res','`yEine seltsam schwarz leuchtende Sphäre',16,'Eine etwa Drachenei-große von innen heraus schwarz leuchtende Sphäre',1,0,0,0,0,0,'',0,1,0,0,0,0,0,0,0,0,0,0,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'black_dragon_eggs',NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,0),('b_bd_shell','`ySchale eines schwarzen Dracheneis',16,'Die Schale eines Dracheneis aus der Höhle des schwarzen Drachens',0,15,0,0,0,0,'',0,1,0,0,0,0,0,0,0,0,0,1,0,1,0,0,0,2,0,2,0,1,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'_codehook_',NULL,NULL,NULL,NULL,NULL,0,0,'global $session, $item, $item_hook_info;\r\noutput (\r\nget_title(\'Die Dracheneischale\').\'Du beschließt dir die Schale nochmal ein wenig genauer anzuschauen, doch als du in deinem Inventar danach suchst, musst du schweren Herzens feststellen, dass sie bei deinen Abenteuern zu Bruch gegangen ist. Du hast gelernt, dass du vorsichtiger mit solch empfindlichen Dingen umgehen musst.`n\r\nDeine Erfahrung steigt um 10%. Vielleicht kann man ja mehrere dieser Schalen kombinieren...\r\n\');\r\n$session[\'user\'][\'experience\']*=1.1;\r\ndebuglog(\'Benutzte schwarze Schale: EXP*1.1\');\r\n\r\naddnav(\'Zurück\',$item_hook_info[\'ret\']);\r\n\r\nitem_delete(\' id=\'.$item[\'id\']);',0,NULL,1,0,0),('b_gd_egg','`yEi eines grünen Drachen',16,'Das Ei eines grünen Drachens',0,10,0,0,0,0,'',0,1,0,0,0,0,0,0,0,0,0,1,0,1,0,0,0,2,0,2,0,1,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'_codehook_',NULL,NULL,NULL,NULL,NULL,0,0,'global $session, $item, $item_hook_info;\r\noutput (\r\nget_title(\'Das Drachenei\').\'Du beschließt dir das Ei nochmal ein wenig genauer anzuschauen, doch als du in deinem Inventar danach suchst, musst du schweren Herzens feststellen, dass es bei deinen Abenteuern zu Bruche gegangen ist. Du hast gelernt, dass du vorsichtiger mit solchen empfindlichen Dingen umgehen musst.`n\r\nDeine Erfahrung steigt um 10%. Vielleicht kann man ja mehrere dieser Eier kombinieren...\r\n\');\r\n$session[\'user\'][\'experience\']*=1.1;\r\ndebuglog(\'Benutzte grünes Drachenei: EXP*1.1\');\r\n\r\naddnav(\'Zurück\',$item_hook_info[\'ret\']);\r\n\r\nitem_delete(\' id=\'.$item[\'id\']);',0,NULL,1,0,0),('b_gd_res','`yEine seltsam grün leuchtende Sphäre',16,'Eine etwa Drachenei-große von innen heraus grün leuchtende Sphäre',1,0,0,0,0,0,'',0,1,0,0,0,0,0,0,0,0,0,0,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'green_dragon_eggs',NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,0),('b_gd_shell','`ySchale eines grünen Dracheneis',16,'Die Schale eines Dracheneis aus der Höhle des grünen Drachens',0,3,0,0,0,0,'',0,1,0,0,0,0,0,0,0,0,0,1,0,1,0,0,0,2,0,2,0,1,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'_codehook_',NULL,NULL,NULL,NULL,NULL,0,0,'global $session, $item, $item_hook_info;\r\noutput (\r\nget_title(\'Die Dracheneischale\').\'Du beschließt dir die Schale nochmal ein wenig genauer anzuschauen, doch als du in deinem Inventar danach suchst, musst du schweren Herzens feststellen, dass sie bei deinen Abenteuern zu Bruch gegangen ist. Du hast gelernt, dass du vorsichtiger mit solch empfindlichen Dingen umgehen musst.`n\r\nDeine Erfahrung steigt um 3%. Vielleicht kann man ja mehrere dieser Schalen kombinieren...\r\n\');\r\n$session[\'user\'][\'experience\']*=1.03;\r\ndebuglog(\'Benutzte grüne Schale: EXP*1.03\');\r\n\r\naddnav(\'Zurück\',$item_hook_info[\'ret\']);\r\n\r\nitem_delete(\' id=\'.$item[\'id\']);',0,NULL,1,0,0),('candycane','`$Z`&uc`4k`&er`$s`&ta`4n`&ge`0',4,'Eine süße Nascherei, die du in deinem Weihnachtspäckchen gefunden hast.',50,0,0,0,0,0,'',0,0,0,0,0,0,0,1,1,0,0,0,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'_codehook_',NULL,NULL,NULL,NULL,NULL,0,0,'global $item_hook_info,$session,$item;\r\n\r\noutput(\'Du lässt dir die Zuckerstange aus dem Weihnachtspaket schmecken.\r\n`nEs muss eine besondere Zuckerstange sein, denn deine Lebenspunkte erhöhen sich permanent um 1!\');\r\n\r\n$session[\'user\'][\'hitpoints\']++;\r\n$session[\'user\'][\'maxhitpoints\']++;\r\n\r\naddnav(\'Zum Inventar\',$item_hook_info[\'ret\']);\r\n\r\nitem_delete(\' id=\'.$item[\'id\']);\r\n\r\nreturn(true);',0,NULL,0,0,0),('cap_01','Haube',30,'Eine Bundhaube aus einfachem Leinenstoff, die unter dem Kinn geschnürt wird.',100,0,0,0,1,1,'Flavius Nadelflink',0,1,0,0,0,0,0,0,0,0,0,1,1,1,0,0,0,0,0,0,0,0,0,0,0,2,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift','_codehook_',NULL,0,0,'if($item[value1]>0) \r\n{\r\n$item[\'value1\']=0;\r\n}',0,NULL,0,0,1),('cap_02','Kopftuch',30,'Ein Kopftuch aus feinem Leinenstoff mit breiter, hübsch bestickter Zierborte.',500,0,0,0,1,2,'Flavius Nadelflink',0,1,0,0,0,0,0,0,0,0,0,1,1,1,0,0,0,0,0,0,0,0,0,0,0,2,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift','_codehook_',NULL,0,0,'if($item[value1]>0) \r\n{\r\n$item[\'value1\']=0;\r\n}',0,NULL,0,0,1),('cap_03','Schleierhaube',30,'Eine feine, samtene Haube mit schmaler Borte und breiten Schleierbändern aus Seidenorganza, die für Halt sorgen und das Haar sittsam verhüllen.',0,1,0,0,1,1,'Flavius Nadelflink',0,1,0,0,0,0,0,0,0,0,0,1,1,1,0,0,0,0,0,0,0,0,0,0,0,2,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift','_codehook_',NULL,0,0,'if($item[value1]>0) \r\n{\r\n$item[\'value1\']=0;\r\n}',0,NULL,0,0,1),('cap_04','Filzhut',30,'Ein einfacher Hut aus gefilzter Schafswolle.',200,0,0,0,0,0,'Flavius Nadelflink',0,1,0,0,0,0,0,0,0,0,0,1,1,1,0,0,0,0,0,0,0,0,0,0,0,2,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift','_codehook_',NULL,0,0,'if($item[value1]>0) \r\n{\r\n$item[\'value1\']=0;\r\n}',0,NULL,0,0,1),('cap_05','Gugel',30,'Eine wollene Kopfbedeckung, bestehend aus Kapuze und Schultertuch, die gut gegen Regen und Wind schützt. Der lange, spitze Kapuzenzipfel kann auf verschiedene Arten modisch um den Kopf geschlungen werden.',1000,0,0,0,0,1,'Flavius Nadelflink',0,1,0,0,0,0,0,0,0,0,0,1,1,1,0,0,0,0,0,0,0,0,0,0,0,2,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift','_codehook_',NULL,0,0,'if($item[value1]>0) \r\n{\r\n$item[\'value1\']=0;\r\n}',0,NULL,0,0,1),('cherrypad','Kirschkernkissen',24,'Ein Leinensäckchen, in dem sich 2 Kirschkerne befinden. Viel kann man damit nicht anfangen, außer vielleicht, weitere Kirschkerne reintun.',502,0,2,0,0,0,'',0,1,0,0,0,0,0,0,0,0,0,1,1,1,0,0,0,0,0,0,0,1,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,1),('cherrytree','Kirschkern',29,'Daraus kann einmal ein Baum werden, alternativ kannst du auch sammeln und ein Kirschkernkissen herstellen.',1,0,7,0,0,0,'',0,1,0,0,1,0,0,0,0,0,0,1,1,1,1,0,0,0,1,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,1),('chess_pyramid','`^Goldene Pyramide',3,'Eine kleine Pyramide aus schimmerndem Kristall. Vermutlich ein magischer Gegenstand.',1,0,0,0,0,0,'',0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,0),('chocolate','Schokolade',25,'Eine kleine Tafel feinster Vollmilch-Schokolade.',250,0,0,0,40,0,'',0,1,0,0,1,0,0,0,0,0,0,1,1,1,1,0,0,3,1,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',NULL,NULL,NULL,NULL,'kitchen',0,0,'',1,NULL,1,0,1),('christmasstar','`,We`Aih`4na`Ich`ttss`ytern`0',4,'`,We`Aih`4na`Ich`ttss`yterne`0 erwachsen aus den Weihnachtssternsamen die an Weihnachten verteilt werden.',1,0,0,0,0,0,'',0,1,0,0,0,0,0,0,0,0,1,1,1,1,1,0,0,0,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,1),('christmasstar_seed','`,We`Aih`4na`Ich`ttss`ytern`&saat',29,'`,We`Aih`4na`Ich`ttss`ytern`&samen`0 lassen die roten Weihnachtssterne sprießen.',1,0,14,0,0,0,'',0,0,0,0,1,0,0,0,0,0,0,1,1,1,1,0,0,0,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,1),('christmas_christstar','³`AChriststern`J³',4,'`AEiner der wohl weihnachtlichsten Sträucher, dessen silbernen Topf sogar eine Schleife ziert. So wünscht dir {name}`A frohe und besinnliche Festtage.`0',1000,0,0,0,0,0,'',0,0,0,0,0,0,0,10,10,0,1,1,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,0),('cloak_01','Schultertuch',30,'Ein dreieckiges, grob gefärbtes Schultertuch aus recht derber Schafswolle, das an kalten Tagen ein wenig Wärme spenden kann.',300,0,0,0,1,2,'Flavius Nadelflink',0,1,0,0,0,0,0,0,0,0,0,1,1,1,0,0,0,0,0,0,0,0,0,0,0,2,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift','_codehook_',NULL,0,0,'if($item[value1]>0) \r\n{\r\n$item[\'value1\']=0;\r\n}',0,NULL,0,0,1),('cloak_02','Edles Schultertuch',30,'Ein dreieckiges Schultertuch aus der feinen Wolle des Seidenkaninchens. Sorgsam gefärbt und mit einer hübschen Borte eingefasst, wärmt es nicht nur, sondern sieht auch noch gut aus.',0,2,0,0,1,2,'Flavius Nadelflink',0,1,0,0,0,0,0,0,0,0,0,1,1,1,0,0,0,0,0,0,0,0,0,0,0,2,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift','_codehook_',NULL,0,0,'if($item[value1]>0) \r\n{\r\n$item[\'value1\']=0;\r\n}',0,NULL,0,0,1),('cloak_06','Edler Radmantel',30,'Ein weiter, langer Umhang aus feinem Seidenbatist, zu verschließen mit einer filigranen Goldfibel. Kragen- und Bodensäume sind mit geheimnisvollen, vielleicht sogar magischen Zeichen in goldenen Fäden bestickt.',0,20,0,0,1,0,'Flavius Nadelflink',0,1,0,0,0,0,0,0,0,0,0,1,1,1,0,0,0,0,0,0,0,0,0,0,0,2,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift','_codehook_',NULL,0,0,'if($item[value1]>0) \r\n{\r\n$item[\'value1\']=0;\r\n}',0,NULL,0,0,1),('cloak_09','Fellumhang',30,'Ein zottiger Überwurf aus dem dicken Fell eines Schafes. Sieht zwar nicht besonders schön aus, hält aber im Winter richtig warm und ist nicht allzu teuer.',700,0,0,0,0,0,'Flavius Nadelflink',0,1,0,0,0,0,0,0,0,0,0,1,1,1,0,0,0,0,0,0,0,0,0,0,0,2,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift','_codehook_',NULL,0,0,'if($item[value1]>0) \r\n{\r\n$item[\'value1\']=0;\r\n}',0,NULL,0,0,1),('cloak_10','Wolljacke',30,'Eine warme, dicke Jacke aus grob gewebtem Schafsloden, die auch vor frostigeren Temperaturen zuverlässig schützt.',1000,0,0,0,0,1,'Flavius Nadelflink',0,1,0,0,0,0,0,0,0,0,0,1,1,1,0,0,0,0,0,0,0,0,0,0,0,2,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift','_codehook_',NULL,0,0,'if($item[value1]>0) \r\n{\r\n$item[\'value1\']=0;\r\n}',0,NULL,0,0,1),('cloak_11','Reiseumhang',30,'Ein knöchellanger Umhang aus robustem Baumwollstoff, der vor Wind und Wetter schützt. Leicht zu reinigen, widerstandsfähig und wärmend, kann dieses Kleidungsstück auf Reisen auch als Sitzunterlage oder Decke dienen.',2000,0,0,0,0,0,'Flavius Nadelflink',0,1,0,0,0,0,0,0,0,0,0,1,1,1,0,0,0,0,0,0,0,0,0,0,0,2,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift','_codehook_',NULL,0,0,'if($item[value1]>0) \r\n{\r\n$item[\'value1\']=0;\r\n}',0,NULL,0,0,1),('cloak_12','Stoffmantel',30,'Ein einfacher Mantel aus mehrlagigem Baumwollflanell, nicht ganz so hübsch wie Pelz, aber dennoch wärmend und leichter zu tragen. Die gerundeten Säume verzieren den schlichten Schnitt ein wenig, ebenso wie die glänzende Knopfleiste.',3000,0,0,0,0,0,'Flavius Nadelflink',0,1,0,0,0,0,0,0,0,0,0,1,1,1,0,0,0,0,0,0,0,0,0,0,0,2,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift','_codehook_',NULL,0,0,'if($item[value1]>0) \r\n{\r\n$item[\'value1\']=0;\r\n}',0,NULL,0,0,1),('cloak_13','Ledermantel',30,'Ein langer Mantel aus leichtem Lammleder, wind- und wetterfest, dafür nicht ganz so warm. Zahlreiche sichtbare und unsichtbare Taschen können allerlei kleinere Gegenstände fassen.',0,1,0,0,0,0,'Flavius Nadelflink',0,1,0,0,0,0,0,0,0,0,0,1,1,1,0,0,0,0,0,0,0,0,0,0,0,2,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift','_codehook_',NULL,0,0,'if($item[value1]>0) \r\n{\r\n$item[\'value1\']=0;\r\n}',0,NULL,0,0,1),('cloverfour','`pVier`Gblättriges Kleeblatt',32,'Wow, ein vierblättriges Kleeblatt, das war ein richtiger Glücksgriff. Fortuna scheint dich anzulächeln.',200,0,0,0,0,0,'',0,0,0,0,0,0,0,0,0,0,0,0,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,0),('cloversix','`2Sechs`Gblättriges Kleeblatt',32,'Eins, zwei, drei, vier... seltsam, normalerweise hören die Kleeblätter hier auf. Liegt vermutlich an der Urstrahlung.',600,0,0,0,0,0,'',0,0,0,0,0,0,0,0,0,0,0,0,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,0),('combi_05','Festlicher Anzug',30,'Eine maßgefertigte Kombination bestehend aus Hemd und Hose aus matter Seide mit eingewirkten Metallfäden. Ein über die Schulter drapierter samtener Überwurf rundet die elegante Aufmachung ab.',0,25,0,0,0,0,'Flavius Nadelflink',0,1,0,0,0,0,0,0,0,0,0,1,1,1,0,0,0,0,0,0,0,0,0,0,0,2,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift','_codehook_',NULL,0,0,'if($item[value1]>0) \r\n{\r\n$item[\'value1\']=0;\r\n}',0,NULL,0,0,1),('combi_08','Gardistenuniform',30,'Hemd und Hose von bequemem und zugleich ansprechendem Schnitt, dazu ein Überwurf in den Farben Atrahors. Die auf den Leib geschneiderte Baumwolle ist mit Wildleder verstärkt und verziert, ebenso wie der breite Gürtel, der auch als Waffengurt dienen kann.',0,3,0,0,0,1,'Flavius Nadelflink',0,1,0,0,0,0,0,0,0,0,0,1,1,1,0,0,0,0,0,0,0,0,0,0,0,2,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift','_codehook_',NULL,0,0,'if($item[value1]>0) \r\n{\r\n$item[\'value1\']=0;\r\n}',0,NULL,0,0,1),('common_root','`tEine Wurzel`0',25,'`tEine Wurzel. Daraus kann man tolle Eintöpfe oder anderes Gebräu zaubern.`0',1,0,0,0,0,0,'',0,1,0,0,0,0,0,0,0,0,0,1,0,1,0,0,0,0,0,0,0,1,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,0),('common_rootbeer','`tWurzelbier`0',25,'`tEin dunkles Getränk, das aus einer Wurzel gebraut wurde.`0',500,0,0,0,0,0,'',0,1,0,0,0,0,0,0,0,0,0,1,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,0),('concale','Starkbierkonzentrat',3,'Gebraut aus Cedriks edelstem Haus-Ale. Das Konzentrat ist derart stark, dass es sogar Drachen umhaut. Für andere Wesen ist es aber ungeniessbar.',3000,0,1,0,0,0,'',0,1,0,0,1,0,0,0,0,0,0,1,1,1,1,0,0,0,0,0,0,1,0,1,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'_codehook_',NULL,NULL,NULL,0,0,'global $badguy;\r\n\r\noutput(\'`n`b`5Du öffnest dein Starkbierfass und reichst es deinem Gegner.`b\');\r\n\r\nif($badguy[\'boss\']) {\r\n\r\noutput(\'`n`b\'.$badguy[\'creaturename\'].\'`5 nimmt einen tiefen Zug und rollt sich anschliessend sturzbesoffen auf dem Boden zusammen.`b`n`n\');\r\n\r\n$badguy[\'diddamage\'] = 0;\r\n$badguy[\'creaturehealth\'] = 0;\r\nitem_delete(\' id = \'.$item[\'id\']);\r\n}\r\n\r\nelse {\r\noutput(\'`n`b\'.$badguy[\'creaturename\'].\'`5 wird aber allein vom Geruch schon übel und lehnt dankend ab.`b`n\');\r\n}\r\n\r\n\r\n',0,NULL,0,0,1),('countdown','Sanduhr',4,'',1,0,0,0,0,0,'',0,0,0,0,0,0,2,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,'_codehook_',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'global $session;\r\n\r\n$zieltime = mktime(13,15,0,8,7,2007);\r\n$fromtime = mktime(20,0,0,1,23,2007);\r\n\r\n$time_left = $zieltime - time();\r\n$since = floor((time() - $fromtime) / 86400);\r\n\r\n$days = floor($time_left / 86400);\r\n$time_left %= 86400;\r\n$hours = floor($time_left / 3600);\r\n$time_left %= 3600;\r\n$minutes = floor($time_left / 60);\r\n$time_left %= 60;\r\n$secs = $time_left;\r\n\r\nrawoutput(\'<script>\r\nLOTGD.m_on_document_loaded.push(\r\nfunction () {MessageBox.show(\"<h1>Huhu \'.($session[\'user\'][\'acctid\'] == 2310 ? \'Tass\':\'Kimmi\').\'!</h1><br />Nur noch \'.$days.\' Tage, \'.$hours.\' Stunden, \'.$minutes.\' Minuten und \'.$secs.\' Sekunden!<br /><br />Insgesamt sind seit dem 23.1.2007 \'.$since.\' Tage vergangen.\",\"~~~\");});\r\n</script>\');',0,NULL,0,0,0),('curry','Currypulver',25,'Ein Beutel fein gemahlenes, scharfes Currypulver aus dem fernen Osten.',50,0,0,0,80,0,'',0,1,0,0,2,0,0,0,0,0,0,1,1,1,0,0,0,2,1,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',1,NULL,0,0,1),('dasbrot','`TM`tarzipanbro`Tt`0',3,'Eine süße Nascherei in Form eines Brotes, bestehend aus mit Schokolade überzogenem Marzipan',25,0,0,0,0,0,'Christbaumschmuck',0,1,0,0,1,0,0,100,100,100,0,1,1,1,1,0,0,0,0,0,0,1,0,0,0,0,5,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,1),('dcklcht','`TDeck`^enleuc`Thter`0',7,'Prunkvoll und riesig. Kann bis zu 50 Kerzen fassen.',1000,12,0,0,0,0,'',0,0,0,0,0,0,0,10,10,1,1,0,1,1,0,0,0,2,1,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift',NULL,NULL,0,0,'',0,NULL,1,0,1),('deadanml','Totes Tier',4,'`vEin Geschenk der besonderen Art, gern auch als dezente Drohung verwendet. Diesen stinkenden Kadaver hat dir `&{name}`v mit freundlichen Grüßen zukommen lassen.`0',1500,0,0,0,0,0,'',0,0,0,0,0,0,0,10,10,20,1,1,0,1,1,0,0,0,0,0,1,1,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,0),('diaamulett','`*Diamantverziertes Amulett',4,'Reich geschmücktes Amulett mit wertvollen Diamanten.',1000,1,0,0,0,0,'',0,0,0,0,0,0,0,10,10,0,1,1,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,0),('dineinl','Einladung zum Essen',4,'Eine Einladung zu einem romantischen Abendessen mit deine[r|m] Geliebten in der Schenke \"Zum Eberkopf\".',1500,0,0,0,0,0,'',0,0,0,0,0,0,0,0,0,0,0,1,0,1,0,0,0,0,0,0,1,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,'dinner',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,0),('dmons','`~Schwarzer `$Dämonenschnitter`0',8,'`$Ein fremdartig geformter, `~nachtschwarzer`$ Zweihänder mit Widerhaken, die ein leises, aber beständiges unheimliches Seufzen und Stöhnen von sich geben. Scheint, als könntest du damit so einiges niederreißen!',15000,0,25,0,0,0,'',0,1,0,0,1,0,0,0,0,0,0,0,0,1,0,0,0,0,0,0,0,0,0,0,0,1,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,0),('dmschrsrt','Daumenschraubensortiment',7,'20 Daumenschrauben der Größe nach sortiert auf einem dunklen Holzbrett.',1000,10,0,0,0,0,'',0,0,0,0,0,0,0,2,2,1,1,0,1,1,0,0,0,2,1,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift',NULL,NULL,0,0,'',0,NULL,1,0,1),('dnrgrgl','`$Donnergurgler',18,'`4Die Herkunft des `$Donnergurglers`4 liegt in den Wirren des letzten Krieges gegen die Dunklen Lande verborgen. Angeblich haben ihn befreundete Truppen beim Versuch aufgegriffen, durch das Kochen der Paprika des Todes Verderben über alle Geschmacksnerven Atrahors zu bringen und anschließend mit seinen Brokkoli-Söldnern das Fürstenamt zu übernehmen.\r\nAllerdings würde dies höhere Intelligenz voraussetzen - die Kochtöpfen im Allgemeinen abgesprochen wird. Seit damals hat `$Donnergurgler`4 auch kein Wort mehr gesprochen, kann dafür aber bemerkenswerterweise Quecksilber schmelzen.',10000,1000,0,0,1,0,'Küchenutensilien - Topf',0,1,0,0,0,0,1,0,1,0,1,0,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,0),('dodoplak','`5Dodo`s-`tPlakette`0',7,'Ein großes Schild, das über der Tür an der Aussenwand des Hauses angebracht wird. Trägt die Aufschrift \"`5Ich liebe Dodo!`0\"',500,0,0,0,0,0,'',0,0,0,0,0,0,0,10,10,0,1,0,1,1,0,0,0,2,1,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift',NULL,NULL,0,0,'',0,NULL,1,0,1),('Dolch','Verzierter Dolch',4,'Verziert mit Runen, die sich die gesamte Länge der Klinge entlang ziehen. Die Klinge ist jedoch nicht scharf und dient nur zur Dekoration. Dieses tolle Exemplar hat dir {name} geschickt!',8000,0,0,0,0,0,'',0,0,0,0,0,0,0,10,10,0,1,1,1,1,0,0,0,2,0,0,1,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,1,0,1),('dragblood','Drachenblut',24,'Ein einzelner Tropfen Drachenblut, eingelassen in ein kleines Stück Glas.',0,2,0,0,0,0,'',0,1,0,0,1,0,0,0,0,0,0,1,1,1,1,0,0,0,0,3,0,1,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,1),('draupnir','Draupnir',13,'Der Ring, den dir Odin für deinen Sieg über Fenris geschenkt hat. Es ist einer der Ringe, die in jeder neunten Nacht aus dem richtigen Draupnir, einem Symbol für Reichtum und Überfluss, heraus entstehen. Nutze ihn weise.',0,20,0,0,0,0,'',0,1,0,0,0,0,0,1,1,0,0,0,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'_codehook_',NULL,NULL,NULL,NULL,NULL,0,0,'$gems = mt_rand(25,35);\r\n$str_out .= \'Du nimmst Draupnir genauer in Augenschein und merkst, wie er unter deiner Aufmerksamkeit immer stärker zu leuchten beginnt. Als das Leuchten so stark ist, dass du den Blick abwenden musst, spürst du, wie der Ring sich auflöst und etwas schweres in deiner Hand zurücklässt.`n`nDu erhältst \'.$gems.\' Edelsteine.\';\r\noutput($str_out);\r\n$Char->gems += $gems;\r\nitem_delete(\' id=\'.$item[\'id\']);\r\naddnav(\'Zum Inventar\',$item_hook_info[\'ret\']);',0,NULL,0,0,0),('drbild','`^wertvolles `@Drachengemälde`0',7,'Gemälde eines Kampfes gegen den grünen Drachen, mit einem goldenen Rahmen.',100000,100,0,0,0,0,'',0,0,0,0,0,0,0,10,10,1,1,0,1,1,0,0,0,2,1,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift',NULL,NULL,0,0,'',0,NULL,1,0,1),('drchamulett','Drachenamulett',3,'Ein Schutzamulett, welches fast unzerstörbar ist.',1000,0,0,0,0,0,'',0,0,0,0,1,0,0,5,5,0,1,1,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,0),('drchanhaenger','Drachenanhänger',3,'Ein Anhänger der es in sich hat, doch was ist seine eigene Magie?',1000,0,0,0,0,0,'',0,0,0,0,1,0,0,5,5,0,1,1,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,0),('drchleder','Drachenleder',3,'Gut für schützende Kleidung geeignet.',500,0,0,0,0,0,'',0,0,0,0,1,0,0,5,5,0,1,1,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,0),('drchstein','Drachenstein',7,'Ein Stein der Freude bringt... er wechselt regelmäßig seine Farbe.',1000,0,0,0,0,0,'',0,0,0,0,0,0,0,5,5,0,1,1,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,0),('drchzahn','Drachenzahn',27,'Ein echter Zahn eines Drachen, es heißt man hätte durch ihn magische Fähigkeiten.',500,0,0,0,0,0,'',0,0,0,0,1,0,0,5,5,0,1,1,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,0),('dress_01','Bastkleid',30,'Ein wadenlanges Kleid aus den robusten Fasern der Ramie. Widerstandsfähig bei jeder Witterung, aber da der Stoff in Verarbeitung und Färbung etwas grob ist, wird er durch Fransen an den Säumen ein wenig aufgehübscht.',2000,0,0,0,1,2,'Flavius Nadelflink',0,1,0,0,0,0,0,0,0,0,0,1,1,1,0,0,0,0,0,0,0,0,0,0,0,2,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift','_codehook_',NULL,0,0,'if($item[value1]>0) \r\n{\r\n$item[\'value1\']=0;\r\n}',0,NULL,0,0,1),('dress_02','Kurzes Kleid',30,'Ein knielanges, schlichtes Kleid mit kurzen Ärmeln. Der Leinenstoff ist dezent gefärbt und an den Säumen mit unauffälligen Stickereien verziert.',3000,0,0,0,1,2,'Flavius Nadelflink',0,1,0,0,0,0,0,0,0,0,0,1,1,1,0,0,0,0,0,0,0,0,0,0,0,2,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift','_codehook_',NULL,0,0,'if($item[value1]>0) \r\n{\r\n$item[\'value1\']=0;\r\n}',0,NULL,0,0,1),('dress_04','Langes Kleid',30,'Ein knöchellanges, schlichtes Kleid mit langen Ärmeln. Der Leinenstoff ist dezent gefärbt und an den Säumen und der Hüfte mit unauffälligen Stickereien verziert.',5000,0,0,0,1,2,'Flavius Nadelflink',0,1,0,0,0,0,0,0,0,0,0,1,1,1,0,0,0,0,0,0,0,0,0,0,0,2,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift','_codehook_',NULL,0,0,'if($item[value1]>0) \r\n{\r\n$item[\'value1\']=0;\r\n}',0,NULL,0,0,1),('dress_07','Seidenkleid',30,'Ein langes Kleid aus edler, sorgfältig gefärbter Seide, die über und über mit kunstvollen Stickereien verziehrt ist. Ein ebenfalls bestickter Ypsilongürtel bringt das Gewand in Form.',0,12,0,0,1,2,'Flavius Nadelflink',0,1,0,0,0,0,0,0,0,0,0,1,1,1,0,0,0,0,0,0,0,0,0,0,0,2,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift','_codehook_',NULL,0,0,'if($item[value1]>0) \r\n{\r\n$item[\'value1\']=0;\r\n}',0,NULL,0,0,1),('drnei','Drachenei',4,'Mit diesem Drachenei will dir `gDrachentöter {shortname}`0 einen Beweis [seiner|ihrer] Tapferkeit liefern. Der \"{date}\"-Stempel auf der Unterseite verrät dir jedoch, dass es sich um ein Ei aus der Zucht handelt.',1500,0,0,0,0,0,'',0,0,0,0,1,0,0,5,5,0,0,1,0,1,0,0,0,2,0,0,1,1,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,1,0,0),('drnschuppe','Drachenschuppe',3,'Komponente für magische Tränke',100,0,0,0,0,0,'',0,1,5,0,1,0,0,0,0,0,0,1,1,1,1,0,0,3,0,0,0,1,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,1,0,1),('drplak','`@Drachen`tplakette`0',7,'Ein großes Schild, das über der Tür an der Aussenwand des Hauses angebracht wird. Trägt die Aufschrift \"`@Grün ist schün!`0\"',500,0,0,0,0,0,'',0,0,0,0,0,0,0,10,10,0,1,0,1,1,0,0,0,2,1,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift',NULL,NULL,0,0,'',0,NULL,1,0,1),('drrel','Drachenreliquie',13,'',1,0,0,0,0,0,'',0,1,0,0,0,0,2,0,0,0,0,1,1,1,0,0,0,0,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,1),('drrel_gld','Horn des `~schwarzen`0 Drachen',13,'In einem Land jenseits der dunklen Ebenen soll es, so geht die Legende, nachtschwarze Drachen geben, die an Grausamkeit den grünen Drachen bei Weitem übersteigen! Dies ist angeblich ein Horn eines solchen Wesens. Auf jeden Fall besitzt es magische Kraft.',20000,100,30000,0,0,0,'',0,1,0,0,0,0,1,0,0,0,0,0,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,1,'_codehook_',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'global $i,$session;\r\n\r\n$str_what = \'\';\r\n\r\nif($session[\'user\'][\'dragonkills\'] < 10) {\r\n$str_what = \'Fünf zusätzliche Waldkämpfe\';\r\n$session[\'user\'][\'turns\'] += 5;\r\n}\r\nelse {\r\n$str_what = \'eine weitere Schlossrunde\';\r\n$session[\'user\'][\'castleturns\']++;\r\n}\r\n\r\noutput(\'`n`n`^\'.$i[\'name\'].\'`^ stärkt mit unglaublichen magischen Fähigkeiten deine Ausdauer, wodurch du \'.$str_what.\' erhältst\');\r\n\r\n$i[\'hvalue\']++;\r\n\r\nif(e_rand($i[\'hvalue\'],30) >= 30) {\r\n\r\noutput(\', verschwindet dann jedoch mit einem Mal in einer seltsamen Nebelwolke. Du suchst und suchst, kannst \'.$i[\'name\'].\'`^ jedoch nicht mehr finden.. Verdammt\');\r\n\r\naddnews($session[\'user\'][\'name\'].\'`^ vermisst seit heute \'.$i[\'name\'].\'`^!\');\r\n\r\nitem_delete(\' id=\'.$i[\'id\']);\r\n\r\nunset($arr_playeritems[$i[\'tpl_id\']]);\r\n\r\n} \r\nelse {\r\nitem_set(\' id=\'.$i[\'id\'],$i);\r\n}\r\n\r\noutput(\'!\');\r\n',0,NULL,0,0,0),('drrel_ksn','Schuppe des `~schwarzen`0 Drachen',13,'In einem Land jenseits der dunklen Ebenen soll es, so geht die Legende, nachtschwarze Drachen geben, die an Grausamkeit den grünen Drachen bei Weitem übersteigen! Dies ist angeblich die Schwanzschuppe eines solchen Wesens. Auf jeden Fall besitzt sie magische Kraft.',20000,100,1000,0,0,0,'',0,1,0,0,0,0,1,0,0,0,0,0,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,1,'_codehook_',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'global $i,$session;\r\n\r\n$session[\'user\'][\'hitpoints\'] = round($session[\'user\'][\'maxhitpoints\'] * 1.5);\r\n\r\noutput(\'`n`n`^\'.$i[\'name\'].\'`^ stärkt mit unglaublichen magischen Fähigkeiten deine Lebenskraft\');\r\n\r\n$i[\'hvalue\']++;\r\n\r\nif(e_rand($i[\'hvalue\'],30) >= 30) {\r\n\r\noutput(\', verschwindet dann jedoch mit einem Mal in einer seltsamen Nebelwolke. Du suchst und suchst, kannst \'.$i[\'name\'].\'`^ jedoch nicht mehr finden.. Verdammt\');\r\n\r\naddnews($session[\'user\'][\'name\'].\'`^ vermisst seit heute \'.$i[\'name\'].\'`^!\');\r\n\r\nitem_delete(\' id=\'.$i[\'id\']);\r\n\r\nunset($arr_playeritems[$i[\'tpl_id\']]);\r\n\r\n} \r\nelse {\r\nitem_set(\' id=\'.$i[\'id\'],$i);\r\n}\r\n\r\noutput(\'!\');\r\n',0,NULL,0,0,0),('drstb','Steckbrief einer Drachenreliquie',12,'Verwittertes Stück Pergament, Fettfinger haben ihre deutlichen Abdrücke auf den zerfledderten Rändern hinterlassen. Es ist von mysteriösen, legendenumwobenen schwarzen Drachen die Rede, welche in fernen Landen hausen sollen.. Der Besitzer dieser Urkunde hat ein exklusives Anrecht auf irgendwo in Atrahor zu findende Reliquien dieser Bestien.',0,0,0,0,0,0,'',0,1,0,0,2,0,0,0,0,0,0,1,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,0),('dummplak','Dummheitsplakette',7,'Ein großes Schild, das über der Tür an der Aussenwand des Hauses angebracht wird. Trägt die Aufschrift \"`5Zentrum der Dummheit.`0\"',500,0,0,0,0,0,'',0,0,0,0,0,0,0,10,10,1,1,0,1,1,0,0,0,2,1,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift',NULL,NULL,0,0,'',0,NULL,1,0,1),('easter-hasi1','`ÌDü`öst`Nerhäs`öch`Ìen`0',4,'`ÌSein Motto lautet `ö`iblack is beautiful`i`Ì, wie könnte es auch anders sein, bei dem seidig schwarzem Fell und den großen dunklen Knopfaugen? Heimlich, wenn tiefste Nacht herrscht, krabbelt er zu dir ins Bett um zu kuscheln, aber psst, das ist euer Geheimnis.`n`ÌSo wünscht dir {name} `Ìschrecklich schöne Ostern im Jahr `(2013.`0',900,0,0,0,0,0,'',0,0,0,0,0,0,0,10,10,0,0,1,1,1,0,0,0,0,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,1),('easter-hasi2','`4R`$e`Qg`qe`^n`@b`2o`9g`!e`Vn`}h`Ia`ts`yi`0',4,'`tDieser Hasi hoppelt über grüne Wiesen, genießt die ersten Sonnenstrahlen und ganz besonders gern mag er `4R`$e`Qg`qe`^n`@b`2ö`9g`!e`Vn`t. Wer weiß also, vielleicht zeigt er dir sogar den Weg zum Topf voll Gold, wenn du ihn ganz lieb streichelst? `nSo wünscht dir {name} `t(farben)frohe Ostern im Jahr `y2013.`0',900,0,0,0,0,0,'',0,0,0,0,0,0,0,10,10,0,0,1,1,1,0,0,0,0,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,1),('easter_big','`jOsterüberraschung',4,'`n{name} `8hat dich mit dieser Osterüberraschung bedacht.`0',850,0,0,0,0,0,'',0,0,0,0,0,0,0,100,100,100,0,1,0,1,1,0,0,0,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,'_codehook_',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'$number=e_rand(1,10);\r\nswitch ($number)\r\n{\r\n	case 1:\r\n	$str_name=\'³`ISpie`&gel-Ei`I³\';\r\n	$str_dsc=\'`sSo was hat die Welt noch nicht gesehen! Statt artig auf dem Frühstücksteller zu landen, zieht dieser Geselle es vor, sich stundenlang vor dem Spiegel selbst zu begutachten und die neuesten Modefarben im Eierland auszuprobieren. Wenn er am Ende doch auf dem Teller landet, nun, wenigstens isst dann das Auge mit. So ein eitler Geck!`0\';\r\n	break;\r\n\r\n	case 2:\r\n	$str_name=\'³`SPiraten`&hörnchen`m³\';\r\n	$str_dsc = \'`:Mit abgeknabbertem Holzbein, schwarzem Fell, Augenklappe und auf der Schulter mit einem Kolibri ausstaffiert, ein durch und durch raubeiniger Geselle. Vor seinem frisch angespitzten Holzdegen sollte man sich in Acht nehmen, ebenso wie auch vor seinem Schiff und der schrecklich pelzigen Crew der `iWalnuss`i.`0\';\r\n	break;\r\n\r\n	case 3:\r\n	$str_name=\'³`hAuste`&rhase`h³\';\r\n	$str_dsc = \'`hGeh lieber aus dem Weg, wenn dieses überaus schleimige Exemplar an dir vorbei hoppelt und dabei seine Schale einmal weit genug geöffnet hat, damit die darin transportierten Austereier auch genug Luft bekommen und nicht anfangen zu faul herumzuliegen. Ebenso ist es besser, über die Tatsache hinweg zu sehen, dass der arme Auster seinen Job nur bekommen hat, weil man ihn mit seinem Vetter Oster Hase verwechselt hat.`0\';\r\n	break;\r\n\r\n	case 4:\r\n	$str_name=\'³`PKrokokuss`&³\';\r\n	$str_dsc = \'`PFrühlingsgefühle erwachen irgendwann in jedem Lebewesen und auch dieses überaus anhängliche Krokodil macht da keine Ausnahme. Kaum aus dem Ei geschlüpft, möchte es seiner Mama oder seinem Papa doch nur ein kleines Küsschen geben. Und du kannst diesem strahlend weißem Lächeln doch ohnehin nicht widerstehen, nicht wahr?`0\';\r\n	break;\r\n\r\n	case 5:\r\n	$str_name=\'³`GWaldfisch`Á³\';\r\n	$str_dsc = \'`GEin Haufen Bäume, Gestrüpp und Blumen auf dem Buckel und der Energiebedarf wird tatsächlich durch Fotosynthese gedeckt. Viel zu groß für das heimische Aquarium oder sogar den Gartenteich und so bleibt wohl nur der nächstgelegene See übrig um als Heimstatt zu dienen.`0\';\r\n	break;\r\n\r\n	case 6:\r\n	$str_name=\'³`OOstergras `k³\';\r\n	$str_dsc = \'`kGrün, eine Konsistenz wie weichgespültes Stroh und schrecklich kratzend, wenn man es in die Kleider bekommt, windet sich das Gras vom Ei fort auf dem Boden, immer bemüht, möglichst bald ein Nest zu finden, wo es sich seinerseits wieder um andere Eier drapieren und diese bis zum Schlüpfen ausbrüten kann, sofern es nicht unterwegs verloren geht.`0\';\r\n	break;\r\n\r\n	case 7:\r\n	$str_name=\'³`&Osterl`oämpchen`&³\';\r\n	$str_dsc = \'`7Geboren an Ostern strahlt dieses Lämpchen besonders helle, ob Tag oder Nacht, obwohl es trotz allem nicht gerade die hellste Leuchte am Himmel ist. Und das beständige Blöken, wann immer man Ein- und Ausschaltknopf betätigt, ist auch nicht jedermanns Sache.`0\';\r\n	break;\r\n\r\n	case 8:\r\n	$str_name=\'³`ÊWalplakat`F³\';\r\n	$str_dsc = \'`fKaum frisch aus dem Ei geschlüpft, schon wird herum posaunt und mit Plakaten für sich geworben: `äWählt mich! Ich bin der schönste Wal für diese Wahl!`f `nNicht gerade sehr originell, aber wenigstens ist das Plakat hübsch geworden, das besagten Wal im Wahlkampf zeigt.`0 \';\r\n	break;\r\n\r\n	case 9:\r\n	$str_name=\'³`GHeimle`&uchte`t³\';\r\n	$str_dsc = \' `tFür alle, die gerne mal im Nebel nächtlicher Trunkenheit den Weg zu ihren eigenen vier Wänden nicht mehr zu finden vermögen, ist hiermit endlich vorgesorgt. Beim nächsten Verirren auf Irrwegen leuchtet einen diese Leuchte sicher heim. Wahrscheinlich strahlt sie sogar heller als man selbst.`0\';\r\n	break;\r\n\r\n	case 10:\r\n	$str_name=\'³`&Glashalm`v³\';\r\n	$str_dsc = \'`&Ja, er ist durchsichtig, dünn und wird vom Wind hin und her gepustet! Das allerneuste Produkt für jene, denen der Spaß am Rasenmähen ohne futuristische Mittel schnell vergeht. Und zu dieser Jahreszeit sogar als aktuelles Sonderangebot der Alchemistengilde: Im Dutzend billiger!`0\';\r\n	break;\r\n\r\n}\r\n\r\n$hook_item[\'tpl_name\'] = $str_name;\r\n\r\n$hook_item[\'tpl_description\'] = $str_dsc.$hook_item[\'tpl_description\'];',0,NULL,0,0,0),('easter_small','`jKleine Osterüberraschung',4,'`n{name} `8hat dich mit dieser kleinen Osterüberraschungen beschenkt.`0',500,0,0,0,0,0,'',0,0,0,0,0,0,0,100,100,100,0,1,0,1,1,0,0,0,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,'_codehook_',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'$number=e_rand(1,15);\r\nswitch ($number)\r\n{\r\n	case 1:\r\n	$str_name=\'³`^Oster-Küken`y³\';\r\n	$str_dsc=\'`yBereits fertig gebrüht und bereit zum Verzehr, ein Muss auf jeder echsischen Ostertafel!`0.\';\r\n	break;\r\n\r\n	case 2:\r\n	$str_name=\'³`rpuffendes Häschen`l³ \';\r\n	$str_dsc = \'`lEin flauschiges, weißes Häschen, dass sich aller paar Sekunden mit einem lauten Knall in einer nach Rosen duftenden rosa Wolke auflöst und an einem anderen Ort im Zimmer wieder auftaucht.`0\';\r\n	break;\r\n\r\n	case 3:\r\n	$str_name=\'³`uSchokol`laden-Drache`u³\';\r\n	$str_dsc = \'`uAus 100g Vollmilch, gefüllt mit Drachenmilchcreme. Für den Kenner eine echte Delikatesse unter den Süßigkeiten! Man sollte sich jedoch vor der giftigen Nebenwirkung in Acht nehmen.`0\';\r\n	break;\r\n\r\n	case 4:\r\n	$str_name=\'³`^Ost`oern`gest`3³\';\r\n	$str_dsc = \'`gEin geflochtenes Körbchen, gefüllt mit Dutzenden kleiner Pralinés, echtem Ostergras und einem lebenden Küken.`0\';\r\n	break;\r\n\r\n	case 5:\r\n	$str_name=\'³`&Oste`4rmann`&³\';\r\n	$str_dsc = \'`&Eigentlich nur ein verkleideter Weihnachtsmann, der letztes Jahr, nach Trunkenheit am Schlittensteuer, seinen Job verlor und daraufhin umschulte. Da er keine Federn und kein Plüschfell hat, posiert er nun mit einem Küken auf der Schulter und einem Häschen auf dem Arm.`0\';\r\n	break;\r\n\r\n	case 6:\r\n	$str_name=\'³`lWeihn`rachtsma`&nn im Bunn`rykostüm`l³\';\r\n	$str_dsc = \'`lNicht gerade eine Augenweide mit den Häschenohren und dem Hasenschwänzchen. Da wünscht man sich den armen Kerl wieder dick verpackt mit einem Sack aus Jute.`0\';\r\n	break;\r\n\r\n	case 7:\r\n	$str_name=\'³`DKarotten`^schneider`D³\';\r\n	$str_dsc = \'`DIn Form einer Mini-Guillotine. Man schiebt die Karotte ganz vorsichtig hinein und lässt das Fallbeil dann fallen - wird bevorzugt von Frauen verwendet, natürlich ausschließlich für Karotten.`0\';\r\n	break;\r\n\r\n	case 8:\r\n	$str_name=\'³`|Faul`Tes Ei`|³\';\r\n	$str_dsc = \'`|Oh je ein Fehlgriff. Das war kein Osterei sondern ein Alienpräsent. Kaum geöffnet, hüpft dir ein Parasit ins Gesicht, der versucht dir ein Alienembryo zu implantieren. Da hilft nur ganz viel Schokolade essen, davon sterben die Viecher ab.`0\';\r\n	break;\r\n\r\n	case 9:\r\n	$str_name=\'³`N100jähr`jiges Ei`N³\';\r\n	$str_dsc = \' `NEin stinkendes, schwarzgrünes Glibberding. Soll in einem weit, weit entfernten Land eine Delikatesse sein... Die müssen wirklich alles essen, wenn das essbar sein soll. Angeblich verursacht der Verzehr riesige Augen, grellfarbige Haare und eine magersüchtige Statur.`0\';\r\n	break;\r\n\r\n	case 10:\r\n	$str_name=\'³`/Zauber: Eischicht`7³\';\r\n	$str_dsc = \'`7Eine Zauberanleitung, die den gewünschten Gegenstand mit einer feinen Eis... äh Eischicht überzieht. Was auch immer das für einen Sinn haben soll. `0\';\r\n	break;\r\n\r\n	case 11:\r\n	$str_name=\'³`hOster`4gesc`jhichte`o³\';\r\n	$str_dsc = \'`oEin kleines Büchlein, das die erstaunliche Geschichte eines Kükens erzählt, das angeblich ein paar Tage nach seinem Tod wiederauferstand und predigte, dass für ewig diese Zeit mit viel Schokolade, hergestellt von Kaninchen, zu feiern sei. `0\';\r\n	break;\r\n\r\n	case 12:\r\n	$str_name=\'³`TDrac`Dhenei`T³\';\r\n	$str_dsc = \'`TDer Lieferant muss sich daran beinahe einen Bruch gehoben haben, aber die Mühe war es wert. Kaum das die Schale aufbricht, öffnet ein kleines Drachenbaby die Augen und nennt dich Mama. Stellt sich nur die Frage, wieso es dich dabei so hungrig ansieht.`0\';\r\n	break;\r\n\r\n        case 13:\r\n	$str_name=\'³`FBlau`wer Sch`1mette`wrling`F³\';\r\n	$str_dsc = \'`FEin wunderschöner Schmetterling, der dir, einmal freigelassen, leider die ganze Zeit nervtötend um den Kopf schwirrt, an deinem Ohr vorbei summt und gegen deine Stirn flattert. `0\';\r\n	break;\r\n\r\n        case 14:\r\n	$str_name=\'³`yOsterpy`Uramide`y³\';\r\n	$str_dsc = \'`yDas Osterpendant zur Weihnachtspyramide, mit kleinen, leichten Flügeln, die sich in jedem Windzug drehen und osterlichen Figuren, sehr beliebt und handgefertigt. Made im Arzgebirg. `0\';\r\n	break;\r\n\r\n         case 15:\r\n	$str_name=\'`³`yOster`?lamm`y³\';\r\n	$str_dsc = \'`yAbsolut lecker! Ein mit Puderzucker bestreutes Lamm. Wenn man sich nicht am Fell zwischen den Zähnen und den Mäh-Geräuschen stört, eine echte Delikatesse. Für alle, die nicht auf Lebendfutter aus sind, auch im Garten hübsch anzusehen. `0\';\r\n	break;\r\n\r\n}\r\n\r\n$hook_item[\'tpl_name\'] = $str_name;\r\n\r\n$hook_item[\'tpl_description\'] = $str_dsc.$hook_item[\'tpl_description\'];',0,NULL,0,0,0),('Edelmaske','`EEdelmaske (Frau)',4,'`)Geschmückt mit glitzernden Steinchen, schwungvollen Federn an den Seiten, ziert diese Maske das Augenpaar. Allein die Lippen sollten zu erkennen sein und das Herz einer Frau erfreuen – das dachte sich zumindest {name}`), als [er|sie] Dir dieses Geschenk zukommen ließ.',1000,5,0,0,0,0,'',0,0,0,0,0,0,0,10,10,0,1,1,0,1,0,0,0,0,0,0,1,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,0),('edlhlzthrn','`yedler `tHolzthron`0',7,'Thron aus Eichenholz.',2500,10,0,0,0,0,'',0,0,0,0,0,0,0,10,10,1,1,0,1,1,0,0,0,2,1,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift',NULL,NULL,0,0,'',0,NULL,1,0,1),('ehering','`^Ehering',4,'Ein Ring aus reinem Platin mit einem wunderschönen Edelstein! Darauf eingraviert ist der Name {name}`0 und das Datum `7{date}`0.',2500,0,0,0,0,0,'',0,0,0,0,0,0,0,10,10,0,0,1,1,1,0,0,0,2,0,0,1,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,1,0,1),('eiersch','Schachtel mit Eiern',25,'Eine kleine Schachtel mit Eiern aus eigener Zucht.',1,0,0,0,0,0,'',0,1,0,0,1,0,0,10,10,10,0,1,1,1,1,0,0,2,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',1,NULL,1,0,1),('einfvor','Einfache Vorhänge',7,'Einfache Vorhänge aus glattem, einfarbigen Stoff. Zugezogen halten sie Sonnenlicht und neugierige Blicke ab.',1000,4,0,0,0,0,'',0,0,0,0,0,0,0,2,2,1,1,0,1,1,0,0,0,2,1,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift',NULL,NULL,0,0,'',0,NULL,1,0,1),('elfenbeinsklpt','Elfenbeinskulptur',7,'Prächtige Skulptur, für den Kaminsims geeignet.',2500,1,0,0,0,0,'',0,0,0,0,0,0,0,5,5,0,1,1,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,0),('elfknst','Elfenkunst',13,'Ein wunderschönes nutzloses Dings.',1,0,0,0,0,0,'',0,1,0,0,1,0,0,1,0,0,0,1,0,1,0,0,0,2,0,0,0,1,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,1,0,0),('erdbeere','Erdbeeren',25,'Ein Körbchen mit frisch gepflückten Erdbeeren.',25,0,0,0,5,0,'',0,1,0,0,1,0,0,0,0,0,0,1,1,1,1,0,0,3,1,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',0,0,'',1,NULL,1,0,1),('erdnuss','Eine Hand voll Erdnüsse',3,'Ein ganze Hand voll Erdnüsse. Du fragst dich immer noch was das für eine seltsame Blume sein muss, die sowas produziert.',10,0,0,0,0,0,'',0,1,0,0,1,0,0,100,100,100,0,1,1,1,1,0,0,3,0,0,0,1,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,1,0,1),('erdnusssm','Erdnuss-Saat',29,'Überreste von Erdnüssen, die bestimmt gut gedeihen..',100,1,10,0,0,0,'',0,1,0,0,1,0,0,0,0,0,0,1,1,1,1,0,0,0,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,1,0,1),('erz','`vEisenerz`0',26,'Ein Brocken Eisenerz aus der Mine.',750,0,0,0,0,0,'',0,1,0,0,0,0,0,3,0,0,0,1,1,1,0,0,0,3,0,2,0,1,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,1,0,0),('esnschll','`)Eisen`7s`schel`)len`0',7,'Mit einer kurzen Kette an der Wand zu befestigen. Für ungehobelte Gäste.',800,2,0,0,0,0,'',0,0,0,0,0,0,0,10,10,1,1,0,1,1,0,0,0,2,1,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift',NULL,NULL,0,0,'',0,NULL,1,0,1),('esrjngfr','`)Eiserne `&Jungfrau`0',7,'Nur zu Deko-Zwecken. Keine Haftung bei Verletzungen!',1250,5,0,0,0,0,'',0,0,0,0,0,0,0,10,10,1,1,0,1,1,0,0,0,2,1,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift',NULL,NULL,0,0,'',0,NULL,1,0,1),('eulenstatue','Eulenstatue',7,'Eine unheimliche Statue... sie kann alles und jeden sehen.',1000,0,0,0,0,0,'',0,0,0,0,0,0,0,5,5,0,1,1,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,0),('exchngdmmy','Tauschquest-Dummy',4,'Wenn du diesen Text lesen kannst ist dein Tausch-Item verlorengegangen und wurde automatisch ersetzt. Das ist nicht weiter schlimm, es funktioniert auch ohne korrekte Beschreibung.',1,0,0,0,0,0,'',0,0,0,0,0,0,0,0,0,0,0,0,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,0),('exchngtrnk','Phiole mit Wasser',14,'unsichtbares Hilfs-Item für den Tauschquest',1,0,1,0,0,0,'',0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'_codehook_',NULL,NULL,NULL,0,0,'global $badguy,$session;\r\n\r\noutput(\'`n`^Du trinkst das Wasser in dem Fläschchen mit einem Zug aus und fühlst dich so stark wie nie zuvor. `n`&`bDu holst zu einem <font size=\"+1\">MEGA</font> Powerschlag aus und triffst \'.$badguy[\'creaturename\'].\'`& mit einem vernichtenden Schlag!!!`b`n`QBeim Durchsuchen von \'.$badguy[\'creaturename\'].\'`Q findest du eine `%Donneraxt`Q!`n`n\');\r\n$badguy[\'creaturehealth\'] = 0;\r\n$session[\'user\'][\'exchangequest\']++;\r\n\r\n$itemnew=item_get(\'tpl_id=\"exchngdmmy\" AND owner=\'.$session[\'user\'][\'acctid\']);\r\n$itemnew[\'name\'] = \'1 `^Donneraxt`0\';\r\n$itemnew[\'description\'] = \'Eine echte zwergische Kampfaxt.\';\r\n$itemnew[\'gold\'] = 7654;\r\n$itemnew[\'gems\'] = 0;\r\nitem_set(\'id=\'.$itemnew[\'id\'],$itemnew);\r\n\r\nitem_delete(\' id = \'.$item[\'id\']);\r\n',0,NULL,0,0,0),('exot_07','Wappenrock',30,'Ein weites, ärmelloses Obergewand mit weit geschlitztem Bodensaum, das über der Rüstung getragen wird. Der Seidendamast zeigt auf Brust, Rücken und Schultern in aufwendigen Applikationen die individuell gefertigten Zeichen des Trägers.',0,40,0,0,0,0,'Flavius Nadelflink',0,1,0,0,0,0,0,0,0,0,0,1,1,1,0,0,0,0,0,0,0,0,0,0,0,2,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift','_codehook_',NULL,0,0,'if($item[value1]>0) \r\n{\r\n$item[\'value1\']=0;\r\n}',0,NULL,0,0,1),('expreset','Anti-Meister-Nerv-Plakette',18,'Dient dazu, Entwicklern ein Leben ohne Auto-Herausforderung durch die Meister zu ermöglichen (ergo: setzt Erfahrung bei jedem Newday auf 0 zurück).',1,0,0,0,0,0,'',0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,'_codehook_',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'global $session;\r\n$session[\'user\'][\'experience\'] = 0;\r\n$session[\'user\'][\'age\'] = 1;\r\noutput(\'`n`b`cEntwickler-Tool (Exp = 0) aktiv!`c`b`n\');\r\nsaveuser();',0,NULL,0,0,0),('farbkrist','ein farbiger Kristall`0',3,'Ein bunter Kristall`0, welchen du aus der Felsenhöhle in den östlichen Wäldern mitgenommen hast. Er sieht zwar schön aus, scheint aber sonst zu nichts nütze.',50,0,0,0,0,0,'',0,1,0,7,1,7,0,10,10,0,0,1,1,1,0,0,0,0,0,0,0,0,0,0,0,0,7,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,1),('feedcoupon','Futter-Gutschein',12,'Auf dezentem Plüschdrachen-Hintergrund steht geschrieben, dass der Besitzer dieses Gutscheins eine kostenlose Fütterung für sein Tier bei Merick erhält.',1,0,0,0,0,0,'',0,1,4,0,0,0,0,0,0,0,0,0,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,0),('feenkristall','Feenkristall',3,'Es handelt sich um kleine Kristalle, die du von der Fee erhalten hast. Sie funkeln im Sonnenlicht mit unterschiedlicher Reinheit.',1,0,0,0,0,0,'',0,1,0,0,0,0,0,0,0,0,0,0,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,0),('feenstb','`^Feenstaub`0',4,'Dieser Feenstaub wird beim Beschenkten sofort nach Erhalt seine Wirkung entfalten.',2000,2,0,0,0,0,'',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,'feenstaub',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,0),('Fesseln','Fesseln',4,'`7100% robustes Leder. Von {name}.`7 Das Geschenk spricht für sich. Für Kinder unter 16 Jahren nicht geeignet.',1000,0,0,0,0,0,'',0,0,0,0,0,0,0,10,10,0,1,1,1,1,0,0,0,2,0,0,1,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,1,0,1),('fetisch_gloeckchen','`ÍGl`°öck`Ûche`Nn-F`Ûet`°isc`Íh',4,'`ûE`Ûi`°n kleines Silberglöckchen, welches einer Legende nach aus dem Schild des ersten Paladins gegossen wurde und somit einen besonderen Schutz gegen das Übernatürliche zu bieten verspricht.\r\nMit diesem Geschenk möchte dich {name} vor bösen Geistern gut beschützt wiss`Ûe`ûn.',1000,0,0,0,0,0,'',0,0,0,0,0,0,0,10,10,0,1,1,0,1,0,0,0,0,0,0,1,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,0),('fetisch_zahn','`ND`Ìr`öa`(chenzahn-Feti`ös`Ìc`Nh',4,'`(An einer schlichten Kordel prangt dieser Zahn eines schwarzen Drachens. Unter welchen Umständen jener gezogen wurde, ist eine andere Geschichte, denn wichtig ist doch nur, dass sich selbst die Toten vor der Macht der Drachen fürchten.\r\nMit diesem Geschenk möchte dich {name} `(also vor bösen Geistern gut geschützt wissen.\r\n',1000,0,0,0,0,0,'',0,0,0,0,0,0,0,10,10,0,1,1,0,1,0,0,0,0,0,0,1,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,0),('feuerball','`QFeuerball`0',14,'Ein Schriftrolle des klassischen und hochexplosiven Feuerballes, welcher den Gegner versengt.',600,0,1,1,0,0,'',0,1,0,0,1,0,0,0,0,0,0,0,1,1,1,0,0,0,0,1,0,0,0,3,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,12,0,'',0,NULL,0,0,1),('feuerholz','`TFeuerholz',26,'`TEinige Scheite Feuerholz.`0',10,0,0,0,0,0,'',0,1,0,0,0,0,0,5,0,0,0,0,0,1,1,0,0,3,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,1,15,0),('fgrorc','`2Ork`0-Trophäe',4,'`0Täuschend echte Imitation eines blutrünstigen, monströsen, 2 Meter großen `2Orks`0; nur für `bechte Männer`b. Am besten eignet sich dieses sündhaft überteuerte Wachsmodell als repräsentative, männlich-herbe Begrüßung im Eingangsbereich des Hauses. Beigefügt ist eine beglaubigte Urkunde, die bestätigt, dass dieser Ork am {date} von {recipient_name}`0 in einem heldenhaften Kampf bezwungen wurde. Damit noch nicht genug, kann die Axt des Orks sogar als Flaschenöffner dienen! `2ORK`0. Denn  Plüschdrachen sind was für Mädchen.',50000,10,0,0,0,0,'',0,0,0,0,0,0,0,10,10,0,1,1,1,1,0,0,0,2,0,0,1,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,'_codehook_',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'if($item_hook_info[\'rec_sex\'] == 1) {\r\n\r\n  output($item_hook_info[\'rec_name\'].\'`r solltest du vielleicht lieber mit einem Plüschdrachen beglücken. Dieses Geschenk ist nämlich nur was für `bechte Männer`b..\');\r\n\r\n  $item_hook_info[\'hookstop\'] = true;\r\n  $item_hook_info[\'check\'] = 1;\r\n\r\n}',0,NULL,1,0,1),('fldblindh','Fluch der Blindheit',9,'Dieser alte Fluch schwächt deine Sehkraft und senkt somit deinen Angriffwert für 4 Tage.',100,2,0,0,4,0,'',0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,3,0,0,0,0,0,0,0,0,0,0,1,'fluch',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,25,0,'',0,NULL,0,0,0),('fldgestank','Höllengestank',9,'Dieser Fluch lässt einen Geruch an dir haften, der die Gegner besonders aggressiv macht.',50,1,0,0,5,0,'',0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,3,0,0,0,0,0,0,0,0,0,0,1,'fluch',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,27,0,'',0,NULL,0,0,0),('fldrmsflgl','Fledermausflügel',3,'Komponente für magische Tränke',150,0,0,0,0,0,'',0,1,4,0,1,0,0,0,0,0,0,1,1,1,1,0,0,3,0,0,0,1,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,1,0,1),('fldschwche','Fluch der Schwäche',9,'Ein mächtiger Fluch, der dich für mehrere Tage schwächt.',10,3,0,0,4,0,'',0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,3,0,0,0,0,0,0,0,0,0,0,1,'fluch',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,23,0,'',0,NULL,0,0,0),('fldtoten','Fluch der Toten',9,'Ein mächtiger, permanenter Fluch, der dein Sterben beschleunigt.',50,5,0,0,0,0,'',0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,3,0,0,0,0,0,0,0,0,0,0,1,'fluch',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,24,0,'',0,NULL,0,0,0),('fldvampir','Fluch des Vampirs',9,'Dieser Fluch saugt für 5 Tage an deiner Lebensenergie.',100,3,0,0,5,0,'',0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,3,0,0,0,0,0,0,0,0,0,0,1,'fluch',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,26,0,'',0,NULL,0,0,0),('fleischbr','Kleiner Brocken Fleisch',3,'Roh, aber dennoch geniessbar, wenn man ihn richtig zubereitet.',30,0,0,0,0,0,'',0,1,3,0,1,0,0,100,100,100,0,1,1,1,1,0,0,1,0,0,0,1,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',1,NULL,0,0,1),('fleischreh','Rehfleisch',25,'Süßes putziges großäugiges tapsiges totes Reh. Sehr zart.',100,0,0,0,35,0,'',0,1,0,0,1,0,0,0,0,0,0,1,1,1,1,0,0,3,1,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'kitchen',0,0,'',1,NULL,1,0,1),('fleischrnd','Rindfleisch',25,'Von der glücklichen Kuh auf der Weide nebenan.',70,0,0,0,110,0,'',0,1,0,0,1,0,0,0,0,0,0,1,1,1,1,0,0,3,1,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'kitchen',0,0,'',1,NULL,1,0,1),('flterbnk','`$F`4olt`7erba`4n`$k (`lbenutzt`$)`0',7,'Eine große Bank aus grobem Eichenholz mit Schellen für Hand- und Fußgelenke. Versehen mit angetrockneten Flecken und tiefen Kerben.',7500,15,0,0,0,0,'',0,0,0,0,0,0,0,10,10,1,1,0,1,1,0,0,0,2,1,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift',NULL,NULL,0,0,'',0,NULL,1,0,1),('flterneu','`4Folterbank`& (nagelneu)',7,'Eine schöne, große Bank aus poliertem Eichenholz mit Schellen für Hand- und Fußgelenke, sowie Gurten für die Fixierung des Benutzers. Die Stützen für Kopf und Beine sind höhenverstellbar.',15000,30,0,0,0,0,'',0,0,0,0,0,0,0,10,10,1,1,0,1,1,0,0,0,2,1,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift',NULL,NULL,0,0,'',0,NULL,1,0,1),('fmnplak','`RFeminismus`tplakette`0',7,'Ein großes Schild, das über der Tür an der Aussenwand des Hauses angebracht wird. Trägt die Aufschrift \"`5Frauen an die Macht!`0\"',500,0,0,0,0,0,'',0,0,0,0,0,0,0,10,10,0,1,0,1,1,0,0,0,2,1,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift',NULL,NULL,0,0,'',0,NULL,1,0,1),('fngrhtsort','Fingerhutsortiment',7,'20 Fingerhüte der Größe nach sortiert auf einem dunklen Holzbrett.',200,0,0,0,0,0,'',0,0,0,0,0,0,0,10,10,0,1,0,1,1,0,0,0,2,1,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift',NULL,NULL,0,0,'',0,NULL,1,0,1),('fnknrgn','`gFunkenregen`0',14,'Dieser Zauber erzeugt einen Regen aus brennenden Funken, die deinen Gegner schädigen. Er kann 5x verwendet werden.',2500,2,5,5,0,0,'',0,1,0,0,1,0,0,0,0,0,0,0,1,1,1,0,0,0,0,1,0,0,0,3,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,7,0,'',0,NULL,0,0,1),('fnstgtt','Fenstergitter',7,'Massive Eisengitter, die vor dem Fenster angebracht werden. Halten ungebetene Gäste draußen und Gefangene drinnen. Nichteinmal ein Halbling könnte sich zwischen den Stäben durchzwängen. ',8000,20,0,0,0,0,'',0,0,0,0,0,0,0,5,5,1,1,0,1,1,0,0,0,2,1,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift',NULL,NULL,0,0,'',0,NULL,1,0,1),('frbrf','Freibrief',4,'`&Ein Brief der dir einmalig Straffreiheit gewährt und dich einmal aus dem Kerker befreien kann. Hebe ihn gut auf und setze ihn weise ein. Der ist von {name}`&.',10000,10,0,0,0,0,'',0,0,0,0,1,0,0,0,0,0,0,0,1,1,0,0,0,2,0,0,1,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,1,0,1),('frieplak','Friedensplakette',7,'Ein großes Schild, das über der Tür an der Aussenwand des Hauses angebracht wird. Trägt die Aufschrift \"`4Feldermord - ohne mich!`0\"',500,0,0,0,0,0,'',0,0,0,0,0,0,0,10,10,1,1,0,1,1,0,0,0,2,1,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift',NULL,NULL,0,0,'',0,NULL,1,0,1),('frndanh','Freundschaftsanhänger',4,'Ein Freundschaftsanhänger von {name}`0.',100,0,0,0,0,0,'',0,0,0,0,0,0,0,100,100,0,0,0,1,1,0,0,0,0,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,1),('frndbnd','Freundschaftsbändchen',4,'Ein Freundschaftsbändchen von {name}.',60,0,0,0,0,0,'',0,0,0,0,0,0,0,100,100,0,0,1,1,1,0,0,0,0,0,0,1,1,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,1),('frnvrsplak','Frauenversteherplakette',7,'Ein großes Schild, das über der Tür an der Aussenwand des Hauses angebracht wird. Trägt die Aufschrift \"`7Ja, Liebling. Du hast ja Recht!`0\"',500,0,0,0,0,0,'',0,0,0,0,0,0,0,10,10,0,1,0,1,1,0,0,0,2,1,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift',NULL,NULL,0,0,'',0,NULL,1,0,1),('frstein','Feuerstein',3,'Wertloser Plunder',25,0,0,0,0,0,'',0,1,4,0,1,0,0,0,0,0,0,1,1,1,1,0,0,3,0,0,0,1,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,1,0,1),('fshkpf','Fischkopf',3,'Ein alter, bestialisch stinkender Fischkopf, der dich aus leeren Augen anblickt. Bemerkenswert schwer..',5,0,0,0,0,0,'',0,1,4,0,1,0,0,0,0,0,0,1,1,1,1,0,0,3,0,0,0,1,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'_codehook_',NULL,NULL,NULL,NULL,NULL,0,0,'global $item_hook_info,$session,$item;\r\n\r\noutput(\'Du hast es doch geahnt.. als du den Fischkopf aufschneidest und hereingreifst, kommt neben Fischinnereien \');\r\n\r\nif(e_rand(1,3) == 1) {\r\n  output(\'eine exquisite Glasfigur zum Vorschein!\');\r\n    item_add($session[\'user\'][\'acctid\'],\'glasfigur\');\r\n}\r\nelse {\r\n  output(\'eine billige Glasperle zum Vorschein!\');\r\n    item_add($session[\'user\'][\'acctid\'],\'glasperle\');\r\n}\r\n\r\naddnav(\'Zum Inventar\',$item_hook_info[\'ret\']);\r\n\r\nitem_delete(\' id=\'.$item[\'id\']);\r\n\r\nreturn(true);',0,NULL,1,0,1),('fsh_aal','Aal',25,'Ein glitschiger Aal, frisch gefangen am Angelsee. Nanu, wie hat sich der nur an den Haken verirrt?',50,0,0,0,50,0,'',0,1,0,0,1,0,0,1,0,0,0,1,1,1,1,0,0,3,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'kitchen',0,0,'',1,NULL,1,0,1),('fsh_fld','Flunder',25,'Eine flache Flunder, frisch gefangen am Angelsee. Da bist du platt!',50,0,0,0,65,0,'',0,1,0,0,1,0,0,1,0,0,0,1,1,1,1,0,0,3,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'kitchen',0,0,'',1,NULL,1,0,1),('fsh_frl','Forelle',25,'Eine schöne Forelle, frisch gefangen am Angelsee.',50,0,0,0,80,0,'',0,1,0,0,1,0,0,1,0,0,0,1,1,1,1,0,0,3,0,0,0,1,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'kitchen',0,0,'',1,NULL,1,0,1),('fsh_gld','Goldfisch',25,'Eine kleiner Goldfisch, frisch gefangen am Angelsee. Wie der hier wohl so lange überlebt hat?',100,0,0,0,120,0,'',0,1,0,0,1,0,0,1,0,0,0,1,1,1,1,0,0,3,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'kitchen',0,0,'',1,NULL,1,0,1),('fsh_krp','Karpfen',25,'Ein prächtiger Karpfen, frisch gefangen am Angelsee. Damit kann man richtig prahlen!',50,0,0,0,40,0,'',0,1,0,0,1,0,0,1,0,0,0,1,1,1,1,0,0,3,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'kitchen',0,0,'',1,NULL,1,0,1),('fsh_lax','Lachs',25,'Ein herrlich großer Lachs, frisch gefangen am Angelsee.',250,0,0,0,35,0,'',0,1,0,0,1,0,0,1,0,0,0,1,1,1,1,0,0,3,0,0,0,1,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'kitchen',0,0,'',1,NULL,1,0,1),('fsh_rat','Bisamratte',3,'Eine fette Bisamratte, frisch gefangen am Angelsee. Mit voller Absicht natürlich. ',20,0,0,0,0,0,'',0,1,0,0,1,0,0,1,0,0,0,1,1,1,1,0,0,3,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',1,NULL,1,0,1),('fsh_tng','Tang',25,'Ein Ballen Süßwassertang, frisch... gefangen am Angelsee. Soll gesund sein.',50,0,0,0,100,0,'',0,1,0,0,1,0,0,1,0,0,0,1,1,1,1,0,0,3,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'kitchen',0,0,'',1,NULL,1,0,1),('fsh_topf','Verbeulter Topf',3,'Ein verbeulter Topf mit großem Loch, der aus dem Angelsee gezogen wurde.',25,0,3,0,97,0,'Küchenutensilien - Topf',0,1,0,0,1,0,0,0,1,0,0,1,0,1,0,0,0,2,0,0,0,1,0,0,0,2,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'_codehook_',NULL,0,0,'global $session;\r\nif($item[value1]>3)\r\n{\r\n $item[\'value1\']=3;\r\n output(\'`b KNACK! `b\');\r\n}\r\n',0,NULL,1,0,0),('fssale','Ein Fass voll Ale',7,'1000 Liter bestes Ale in einem riesigen Eichenfass. Verdammt, wie geht das auf?',2500,4,0,0,0,0,'',0,0,0,0,1,0,0,10,10,0,1,0,0,1,0,0,0,2,1,0,0,1,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'switch($_GET[\'act\']) {\r\n\r\n  case \'zapf\':\r\n    if($item[\'value1\'] <= 0) {\r\n      item_delete(\' id=\'.$item[\'id\']);\r\n    }\r\n    else {\r\n      item_set(\' id=\'.$item[\'id\'], array(\'value1\'=>$item[\'value1\']-1) );\r\n    }\r\n\r\n    \r\n\r\n  break;\r\n\r\n  default:\r\n    addnav(\'Ale zapfen\',$item_hook_info[\'link\'].\'&act=zapf\');\r\n\r\naddnav($item_hook_info[\'back_msg\'],$item_hook_info[\'back_link\']);\r\n  break;\r\n\r\n}\r\n',0,NULL,1,0,0),('fssmtte','`TF`tuß`Tm`tatt`Te`0',7,'Fest und robust. Damit kann niemand mehr mit schmutzigen Schuhen ins Haus.',100,0,0,0,0,0,'',0,0,0,0,0,0,0,10,10,1,1,0,1,1,0,0,0,2,1,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift',NULL,NULL,0,0,'',0,NULL,1,0,1),('fttrsack','Futtersack',4,'Vollwertkost für den tierischen Begleiter; stellt die Ausdauer zum großen Teil wieder her.',100,0,0,0,0,0,'',0,1,0,0,0,0,0,3,1,0,1,1,1,1,1,0,0,2,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'_codehook_',NULL,NULL,NULL,NULL,NULL,0,0,'global $playermount,$session,$item;\r\n\r\n\r\nif($session[\'user\'][\'hashorse\'])\r\n{\r\n $buff = unserialize($playermount[\'mountbuff\']);\r\n\r\n $rounds = $buff[\'rounds\'];\r\n $bonus = $rounds>>1;\r\n $gain = ($rounds-$session[\'bufflist\'][\'mount\'][\'rounds\']);\r\n if ($gain>$bonus) $gain=$bonus;\r\n $rowm = user_get_aei(\'hasxmount,xmountname\');\r\n\r\n if ($session[\'bufflist\'][\'mount\'][\'rounds\'] >= $rounds)\r\n {\r\n  output(\'`5Dein/e \'.$playermount[\'mountname\'].\'`5 ist leider noch gar nicht hungrig.\');\r\n }\r\n else\r\n {\r\n if ($session[\'bufflist\'][\'mount\'][\'rounds\']<1)\r\n {\r\n $buff = unserialize($playermount[\'mountbuff\']);\r\n $session[\'bufflist\'][\'mount\']=$buff;\r\n $session[\'bufflist\'][\'mount\'][\'rounds\']=0;\r\n if ($rowm[\'hasxmount\']==1)\r\n {\r\n $session[\'bufflist\'][\'mount\'][\'name\'] = $rowm[\'xmountname\'] .\' `&(\'.$session[\'bufflist\'][\'mount\'][\'name\'].\'`&)\';\r\n }\r\n }\r\n\r\n output(\'`5Dein/e \'.$playermount[\'mountname\'].\'`5 stürzt sich auf das nahrhafte Futter und schlingt es schneller hinunter als du dir vorstellen kannst!`n`nDein Tier erhält \'.$gain.\' Runden dazu.\');\r\n $session[\'bufflist\'][\'mount\'][\'rounds\']+=$gain;\r\n\r\n item_delete(\' id=\'.$item[\'id\']);\r\n }\r\n}\r\nelse\r\n{\r\n output(\'`5Dein Tier würde sich sicher über das Futter freuen... wenn du eins hättest!\');\r\n}\r\n\r\naddnav(\'Zum Inventar\',$item_hook_info[\'ret\']);\r\n',0,NULL,1,0,1),('futtermat','Futtermittel-Automat',18,'Eine große, klapprige Vorrichtung, die unter sachgemäßer Verwendung in der Lage ist Futtermittel für Nagetiere herzustellen. Leider ist die Apparatur sehr sperrig und macht einen höchst empfindlichen Eindruck. Ein kleines Schild warnt vor gelegentlichen Fehlfunktionen.',45000,75,100,1,0,0,'',0,1,0,0,0,0,0,1,1,0,1,1,0,1,0,0,0,2,1,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,'futtermat','futtermat',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,1,0,0),('gaense','Gänseblümchen',24,'Sind wir nicht alle ein Gänseblümchen im Sonnenschein?',5,0,0,0,0,0,'',0,1,0,0,1,0,0,1,1,0,0,1,1,1,1,0,0,0,0,3,0,1,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,1),('gamulett','`6Goldenes Amulett',4,'Als du das Amulett anlegst, hüllt es dich in eine merkwürdige, schützende Aura. Der Beipackzettel verrät dir, dass das Amulett nach 3 Tagen seine Wirkung verliert und zu Staub zerfallen wird.`0',1,0,0,0,3,0,'',0,0,0,0,2,0,0,0,0,0,0,1,0,1,0,0,0,2,0,0,0,1,0,0,0,0,0,1,'segen',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,22,0,'',0,NULL,1,0,0),('garderobe','Kleiderschrank',7,'Ein großer Schrank aus Eichenholz. Der perfekte Aufbewahrungsort für deine Gewänder.',15000,50,0,0,0,0,'',0,0,0,0,0,0,0,10,10,0,1,1,0,1,0,0,0,2,1,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,'garderobe','garderobe','garderobe',NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,1,0,0),('garn','`eGarn',26,'Eine kleine Rolle feines Garn.',1,0,0,0,0,0,'',0,1,0,0,1,0,0,5,5,0,1,1,1,1,0,0,0,3,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,30,1),('geflvor','Geflickte Vorhänge',7,'Ein paar Vorhänge, die ihre besten Tage lange hinter sich haben. Löcher und Risse wurden mit bunten Stoffstücken zugenäht, sodass ein ungleichmäßiges Muster entstanden ist.',500,2,0,0,0,0,'',0,0,0,0,0,0,0,2,2,1,1,0,1,1,0,0,0,2,1,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift',NULL,NULL,0,0,'',0,NULL,1,0,1),('gemmajor','`%reiner Rohedelstein`0',26,'Ein reiner in Fels eingeschlossener Rohedelstein aus der Mine.',0,2,0,0,0,0,'',0,1,0,0,0,0,0,0,0,0,0,1,0,1,0,0,0,3,0,2,0,1,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,1,10,0),('gemminor','`5unreiner Rohedelstein`0',26,'Ein unreiner in Fels eingeschlossener Rohedelstein aus der Mine.',0,1,0,0,0,0,'',0,1,0,0,0,0,0,0,0,0,0,1,0,1,0,0,0,3,0,2,0,1,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,1,15,0),('gemprss','Edelsteinpresse',7,'Die kleine Version des tollen Gerätes, das auch Cedrik in seiner Schenke benutzt. ',10000,50,0,0,0,0,'',0,0,0,0,0,0,0,1,1,0,1,0,0,1,0,0,0,2,1,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,'presse','presse',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,1,0,0),('gesmlskt','zufällige Geschmacklosigkeit',4,'`n{name} `^hat dich mit diesem... tollen... Geschenk beglückt.`0 ',500,0,0,0,0,0,'',0,0,0,0,0,0,0,100,100,100,0,1,0,1,1,0,0,0,0,0,1,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,'_codehook_',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'$number=e_rand(1,21);\r\nswitch ($number)\r\n{\r\ncase 1:\r\n$str_name=\'Pummeliger Gartenzwerg\';\r\n$str_dsc=\'Ein schrecklich kitschiger Gartenzwerg, stilecht mit Harke und Dickbauch. Seine Mütze ist mindestens genauso rot wie seine Wangen.\';\r\nbreak;\r\n\r\ncase 2:\r\n$str_name=\'Silbernes Banjo\';\r\n$str_dsc=\'Dieses ominöse Musikinstrument wird dir sicher auf dem Dorffest viel Freude bereiten - vorausgesetzt du kannst dem aufgebrachten Mob entkommen.\';\r\nbreak;\r\n\r\ncase 3:\r\n$str_name=\'Magischer Wackel-Dackel\';\r\n$str_dsc=\'Endlich keine Widerworte mehr! Dieses Meisterwerk nickt nicht nur bei allem was du sagst, sondern kann auch durch lautstarkes Bellen seine Zustimmung zeigen.\';\r\nbreak;\r\n\r\ncase 4:\r\n$str_name=\'Rosa Rüschenkissen\';\r\n$str_dsc=\'Ein prunkvolles Paradekissen mit der aufwändigen Stickerei `4Mutti ist die Beste!`0\';\r\nbreak;\r\n\r\ncase 5:\r\n$str_name=\'Eierwärmer\';\r\n$str_dsc=\'Mit diesem feinen handgehäkelten Accessoire wird das Frühstücksei nun nicht mehr so schnell kalt.\';\r\nbreak;\r\n\r\ncase 6:\r\n$str_name=\'Kuckucksuhr\';\r\n$str_dsc=\'Der Kuckuck in dieser Uhr ist besonders fleißig und zeigt sich jede halbe Stunde!\';\r\nbreak;\r\n\r\ncase 7:\r\n$str_name=\'Miniatur-Alphorn\';\r\n$str_dsc=\'Montiert auf einem hübschen Brett aus hellem Eichenholz. Töne gibt es keine von sich - zum Glück!\';\r\nbreak;\r\n\r\ncase 8:\r\n$str_name=\'Handgefertigter Topflappen\';\r\n$str_dsc=\'Das Ergebnis dieser aufwändigen Arbeit lässt wohl nichts mehr anbrennen - vor allem deine Finger.\';\r\nbreak;\r\n\r\ncase 9:\r\n$str_name=\'Ein Bild von der Heimat\';\r\n$str_dsc=\'Ein tolles Gemälde mit Bergen, Wäldern, Seen und einem röhrenden Hirsch.\';\r\nbreak;\r\n\r\ncase 10:\r\n$str_name=\'Opa-Spazierstock\';\r\n$str_dsc=\'Ein morscher Stock, versehen mit kleinen und großen Plaketten von mehr oder minder berühmten Wanderrouten in Atrahor.\';\r\nbreak;\r\n\r\ncase 11:\r\n$str_name=\'Orkische Schamkapsel\';\r\n$str_dsc=\'Kultur hin oder her, dieses Ding zeigt man besser keinem - allein schon wegen der geringen Größe.\';\r\nbreak;\r\n\r\ncase 12:\r\n$str_name=\'Ehren-Bronzeteller\';\r\n$str_dsc=\'Für 50 Jahre treue Mitgliedschaft im ansässigen Brieftaubenfütterverein.\';\r\nbreak;\r\n\r\ncase 13:\r\n$str_name=\'Ein Kanister `tUschi Aas`0 Hautcreme\';\r\n$str_dsc=\'Hilft gegen Pickel, Falten, Ekzeme und Orangenhaut. Du hast allerdings keine Ahnung was man dir damit sagen will...\';\r\nbreak;\r\n\r\ncase 14:\r\n$str_name=\'Handgeknüpfter kleiner Teppich\';\r\n$str_dsc=\'Dein Beitrag im Kampf gegen die Kinderarbeitslosigkeit in \'.getsetting(\'townname\',\'Atrahor\');\r\nbreak;\r\n\r\ncase 15:\r\n$str_name=\'Packung Pfadfinderkekse\';\r\n$str_dsc=\'Trocken, hart, geschmacksneutral und maßlos überteuert - so wie Pfadfinderkekse nunmal sind.\';\r\nbreak;\r\n\r\ncase 16:\r\n$str_name=\'Alte Socke (mit Loch)\';\r\n$str_dsc=\'Sie stinkt dermaßen, dass um sie herum alle Pflanzen eingehen.\';\r\nbreak;\r\n\r\ncase 17:\r\n$str_name=\'`@Drachengrüner Wimpel`0\';\r\n$str_dsc=\'Trägt die verschnörkelte Aufschrift: Du bist \'.getsetting(\'townname\',\'Atrahor\').\'!\';\r\nbreak;\r\n\r\ncase 18:\r\n$str_name=\'Schäferhund aus Holz\';\r\n$str_dsc=\'Hüfthoch, mit heraushängender Zunge und hochstehendem Schwanz.\';\r\nbreak;\r\n\r\ncase 19:\r\n$str_name=\'Bemalte Holzpantoffeln\';\r\n$str_dsc=\'Mit buntem Rosettenmuster und erhöhtem Absatz.\';\r\nbreak;\r\n\r\ncase 20:\r\n$str_name=\'Alter Schaukelstuhl\';\r\n$str_dsc=\'Groß, sperrig, platzraubend und fällt bei der kleinsten Berührung in sich zusammen.\';\r\nbreak;\r\n\r\ncase 21:\r\n$str_name=\'`tAusgestopftes Eichhörnchen`0\';\r\n$str_dsc=\'Auf einem Brett aus glattem Kiefernholz. In großen Lettern steht geschrieben: `4Vorsicht! Nicht mit Erdnüssen füttern!`0\';\r\n$hook_item[\'tpl_id\'] = \'squirr\'; \r\nbreak;\r\n\r\n}\r\n\r\n$hook_item[\'tpl_name\'] = \r\n$str_name;\r\n\r\n$hook_item[\'tpl_description\'] = $str_dsc.$hook_item[\'tpl_description\'];',0,NULL,0,0,0),('gewuerze','Gewürze',13,'Frisch importiert aus fernen Landen. Diese Gewürze verfeinern jedes Mahl und sind unerlässlich für die Zubereitung von gutem Essen.',0,1,0,0,0,0,'',0,1,1,0,1,0,0,100,100,100,1,1,1,1,1,0,0,2,1,0,0,1,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',1,NULL,1,0,1),('gftcurse','Kleiner Fluch',9,'',350,0,0,0,0,0,'',0,1,0,0,1,0,0,10,15,5,1,0,1,1,0,0,0,0,0,0,1,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,'gftcurse',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,1),('gftph','Giftphiole',3,'Ein kleines Fläschchen mit fiesem Gift für die Truhenfalle. Reicht für 3 Ladungen.',0,0,0,0,0,0,'',0,1,1,0,1,0,0,0,0,0,0,1,1,1,1,0,0,0,0,0,0,1,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,1),('ghgolem','guter Hausgolem',15,'Hart wie Fels steht er still da, bereit jeden Eindringling deines Hauses zu zerquetschen. Laufende Kosten: 750 Gold, 3 Edelsteine.',75000,100,1,0,0,0,'',0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,56,0,'',0,NULL,0,0,0),('giftforagrinch','Kuhschwanzlaminellenwärmer',4,'Ein kleines Geschenk vom alten Zausel. Es handelt sich dabei um ein paar Kuhschwanzlaminellenwärmer. Du hast absolut keine Ahnung was das sein soll und so hässlich wie es ist auch nicht wirklich Lust es jemandem zu geben den du magst...',1,0,0,0,0,0,'',0,0,0,0,0,0,0,0,0,0,0,1,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,0),('giftlei','`@Einfaches Waffengift',14,'Ein einfaches Waffen-Kontaktgift.',250,0,1,1,0,0,'',0,1,0,0,1,0,0,0,0,0,0,1,1,1,1,0,0,0,0,0,0,0,0,3,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'_codehook_',NULL,NULL,NULL,48,0,'global $session,$badguy;\r\n\r\noutput(\"`@Du schmierst das Gift auf deine Waffe und grinst deinem Gegner böse entgegen.`n\");',0,NULL,0,0,1),('giftschw','`@Starkes Waffengift',14,'Ein starkes Waffen-Kontaktgift.',500,0,1,1,0,0,'',0,1,0,0,1,0,0,0,0,0,0,1,1,1,1,0,0,0,0,0,0,0,0,3,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'_codehook_',NULL,NULL,NULL,49,0,'global $session,$badguy;\r\n\r\noutput(\"`@Du schmierst das Gift auf deine Waffe und grinst deinem Gegner böse entgegen.`n\");',0,NULL,0,0,1),('gift_star','`9Ein Stern',4,'`!Ein `9Stern`!! Er wurde Dir geschenkt und leuchtet nur für Dich!.',1,0,0,0,0,0,'',0,0,0,0,0,0,0,10,15,0,0,1,1,1,0,0,0,0,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,1),('glasfigur','Glasfigur',3,'Eine wertvolle Tierfigur aus geschliffenem Glas.',0,1,0,0,0,0,'',0,1,3,0,1,0,0,0,0,0,0,1,1,1,1,0,0,3,0,0,0,1,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,1,0,1),('glaskugel','eine {farbige} Glaskugel`0',3,'Eine mundgeblasene und eingefärbte Kugel aus hauchdünnem Glas. Vorsicht! Leicht zerbrechlich!',250,0,0,0,0,0,'Christbaumschmuck',0,1,0,7,1,7,0,10,10,0,0,1,1,1,0,0,0,0,0,0,0,0,0,0,0,0,5,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'_codehook_',NULL,NULL,NULL,NULL,0,0,'$number=e_rand(1,7);\r\nswitch ($number)\r\n{\r\ncase 1:\r\n$str_name=\'`#blaue\';\r\nbreak;\r\n\r\ncase 2:\r\n$str_name=\'`$rote\';\r\nbreak;\r\n\r\ncase 3:\r\n$str_name=\'`@grüne\';\r\nbreak;\r\n\r\ncase 4:\r\n$str_name=\'`ssilberne\';\r\nbreak;\r\n\r\ncase 5:\r\n$str_name=\'`^goldene\';\r\nbreak;\r\n\r\ncase 6:\r\n$str_name=\'`&weiße\';\r\nbreak;\r\n\r\ncase 7:\r\n$str_name=\'`!dunkelblaue\';\r\nbreak;\r\n\r\ncase 8:\r\n$str_name=\'`5violette\';\r\nbreak;\r\n\r\ncase 9:\r\n$str_name=\'`Adunkelrote\';\r\nbreak;\r\n\r\ncase 10:\r\n$str_name=\'`/gelbe\';\r\nbreak;\r\n\r\n}\r\n\r\n$hook_item[\'tpl_name\'] = str_replace(\'{farbige}\',$str_name,$hook_item[\'tpl_name\']);\r\n',0,NULL,0,0,0),('glasperle','Glasperle',3,'Wertloser Plunder',10,0,0,0,0,0,'',0,1,6,0,1,0,0,0,0,0,0,1,1,1,1,0,0,3,0,0,0,1,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,1,0,1),('glckskeks','Glückskeks',4,'`n`5 - Gez. Dein Schicksalsbote {name}`5, {date}',250,0,0,0,0,0,'',0,0,0,0,0,0,0,10,10,0,1,1,1,1,1,0,0,2,0,0,1,1,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,'_codehook_',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'// Schema: Imperativ -  Adjektiv - Objekt\r\n\r\n$arr_verbs = array(\r\n\'Fliege\',\'Laufe\',\'Liebe\',\'Sei\',\'Hüpfe\',\'Hause\',\'Klage\',\'Tunke\',\'Singe\'\r\n);\r\n\r\n$arr_adject = array(\r\n\'blond\',\'blauäugig\',\'blöd\',\'groß, ganz groß\',\'hübsch\',\'magisch\',\'transzendent\',\'superb\',\'enthusiasmiert\',\'elitär\',\'dünkelhaft\',\'verrucht\',\'verdorben\',\'erstunken\'\r\n);\r\n\r\n$arr_object = array(\r\n\'Kuh\',\'Schweinsfisch\',\'Magier\',\'Zwischendimension\',\'Waschzuber\',\'Mut\',\'Freiheit\',\'Frosch\',\'Flauschihase\',\'Kuscheldämon\',\'Stapel Hartholz\',\'grüner Drache\',\'Lebensweisheit\',\'Troll\',\'Hindernis\',\'Elixier des Glücks\'\r\n);\r\n\r\n$str_msg .= \'Der Keks enthält ein Zettelchen, auf welchem in verschnörkelten Lettern geschrieben steht:`^\" \';\r\n\r\n$str_msg .= $arr_verbs[ e_rand ( 0 , sizeof($arr_verbs) - 1 ) ];\r\n\r\n$str_msg .= \' \'.$arr_adject[ e_rand ( 0 , sizeof($arr_adject) - 1 ) ];\r\n\r\n$str_msg .= \', \'.$arr_object[ e_rand ( 0 , sizeof($arr_object) - 1 ) ];\r\n\r\n$str_msg .= \'! \"\';\r\n\r\n$hook_item[\'tpl_description\'] = $str_msg.$hook_item[\'tpl_description\'];',0,NULL,1,0,1),('glcksmnze','Glücksmünze',3,'Trage sie stets bei dir. Sie trägt ihren Namen sicher nicht zu unrecht.',500,0,0,0,0,0,'',0,0,0,0,1,0,0,5,5,0,1,1,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,0),('gldinsgn','Insignie',13,'Eine Insignie, wie sie beim König sehr begehrt ist. Reich verziert, goldüberzogen und eine filigrane Form.',10000,100,0,0,0,0,'',0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,0),('gldnamltt','`6Goldenes Amulett',4,'Als du das Amulett anlegst, hüllt es dich in eine merkwürdige, schützende Aura. Der Beipackzettel verrät dir, dass das Amulett nach 3 Tagen seine Wirkung verliert und zu Staub zerfallen wird. Trotzdem ist ein Name eingraviert: {name}`0',2000,0,0,0,3,0,'',0,0,0,0,1,0,0,10,10,0,0,1,0,1,0,0,0,2,0,0,1,1,0,0,0,0,0,1,'segen',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,22,0,'',0,NULL,1,0,0),('gldndrache','Goldene Drachenstatue',7,'Eine prachtvolle Statue.',2500,1,0,0,0,0,'',0,0,0,0,0,0,0,5,5,0,1,1,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,0),('gldndrchschuppe','Vergoldete Drachenschuppe',7,'Ein seltener Fund, der dem Besitzer Glück bringen soll.',2500,1,0,0,0,0,'',0,0,0,0,0,0,0,5,5,0,1,1,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,0),('gldnstern','`IGold`tener S`Itern',4,'`tEin Goldener Stern, der wunderbar sanftes Licht ausstrahlt.',1000,1,0,0,0,0,'',0,0,0,0,0,0,0,10,10,0,1,1,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,0),('gldprive','Einladung der Gildenleitung',12,'',0,0,0,0,0,0,'',0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,0),('gldthrn','`^goldener `sThron`0',7,'Thron aus Eichenholz mit Schrauben und Beschlägen aus reinstem Gold.',10000,40,0,0,0,0,'',0,0,0,0,0,0,0,10,10,1,1,0,1,1,0,0,0,2,1,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift',NULL,NULL,0,0,'',0,NULL,1,0,1),('goldenegg','`^Goldenes Ei`0',13,'Das legendäre `^Goldene Ei.`0 ',0,0,0,0,0,0,'',0,1,0,0,0,0,0,0,0,0,0,0,0,1,0,0,0,0,0,0,0,0,0,0,2,0,0,0,'goldenegg',NULL,NULL,'goldenegg',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,0),('goldklmp','`^Goldklumpen',3,'Ein kleiner Klumpen reines `^Gold`0. Mit Sicherheit sehr wertvoll.',500,0,0,0,0,0,'',0,1,0,0,1,0,0,0,0,0,0,1,0,1,0,0,0,3,0,0,0,1,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,1,0,0),('goldring','Goldring',3,'Ein schlichter Goldring ohne Gravur oder Stein. Vielleicht wird sich irgendein Halbling dafür interessieren...',1000,0,0,0,0,0,'',0,1,4,0,1,0,0,0,0,0,0,1,1,1,1,0,0,3,0,0,0,1,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,1,0,1),('golem','`TGolem`0',14,'Dieser mächtige Zauber - bestehend aus einem Erdklumpen - erschafft ein magisches Wesen, das 25 Runden auf deiner Seite kämpft.',8000,4,1,1,0,0,'',0,1,0,0,1,0,0,0,0,0,0,0,1,1,1,0,0,0,0,1,0,0,0,3,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,10,0,'',0,NULL,0,0,1),('grnsmtvorh','`2grüne `&Samtvorhänge`0',7,'Lange und schwere Vorhänge aus `2grünem `&Samt.',5000,10,0,0,0,0,'',0,0,0,0,0,0,0,10,10,5,1,0,1,1,0,0,0,2,1,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift',NULL,NULL,0,0,'',0,NULL,1,0,1),('grottosouvenir','Souvenir aus der Grotte',3,'Dies ist ein Dummytemplate für ein Souvenir aus der Grotte.',1,0,0,0,0,0,'',0,1,0,0,0,0,0,3,5,0,1,1,0,1,0,0,0,0,0,0,0,0,0,3,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,0),('grssgem','Großes Gemälde',7,'Es zeigt einen alten Mann mit dicker Nase.',10000,30,0,0,0,0,'',0,0,0,0,0,0,0,10,10,1,1,0,1,1,0,0,0,2,1,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift',NULL,NULL,0,0,'',0,NULL,1,0,1),('grsshltrnk','`tGroßer Heiltrank`0',14,'Regeneriert ein paar mehr Lebenspunkte. Für den anspruchsvollen Krieger.',1250,0,1,1,0,0,'',0,1,0,0,1,0,0,0,0,0,0,0,1,1,1,0,0,0,0,1,1,0,0,3,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,15,0,'',0,NULL,0,0,1),('grsshshnd','Großer Haushund',15,'Ein großer Wachhund, der Haus und Herrchen zu schützen weiß. Laufende Kosten: 500 Gold, keine Edelsteine.',15000,30,500,0,0,0,'',0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,19,0,'',0,NULL,0,0,0),('grsspgl','Großer Spiegel',7,'Ein großer, hochwertiger  Wandspiegel aus poliertem Metall.',3000,6,0,0,0,0,'',0,0,0,0,0,0,0,1,1,0,1,0,0,1,0,0,0,2,1,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,'spiegel','spiegel',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,1,0,0),('grssvglkfg','Großer Vogelkäfig',7,'Aus dünnem Draht und leicht verbeult.',100,1,0,0,0,0,'',0,0,0,0,0,0,0,10,10,0,1,0,1,1,0,0,0,2,1,0,0,1,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift',NULL,NULL,0,0,'',0,NULL,1,0,1),('grteppch','`mGr`4oß`$er Tep`4pi`mch',7,'Ein großer, weicher Teppich, der jeden Raum sofort wohnlicher macht.',5000,10,0,0,0,0,'',0,0,0,0,0,0,0,5,5,1,1,0,1,1,0,0,0,2,1,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift',NULL,NULL,0,0,'',0,NULL,1,0,1),('gtlschtzzb','Göttlicher Schutzzauber',14,'Ein göttlicher Zauber, der Deine Verteidigung stärkt.',0,0,1,0,0,0,'',0,1,0,0,1,0,0,0,0,0,0,1,0,1,1,0,0,2,0,2,0,0,0,3,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,4,0,'',0,NULL,1,0,0),('guildinvitation','Gildeneinladung',12,'Eine Einladung, um an den Gesprächen in einer Gildenhalle teilzunehmen.',1,0,0,0,0,0,'',0,0,0,0,0,0,0,0,0,0,0,1,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'_codehook_',NULL,NULL,NULL,NULL,NULL,0,0,'//Gildeneinladungen by Takehon for Atrahor\r\naddnav(\'Zurück\');\r\naddnav(\'Zum Gildenviertel\',\'dg_main.php\');\r\nunset($accesskeys[\'d\']); unset($accesskeys[\'m\']);\r\naddnav(\'D?Zum Dorfplatz\',\'village.php\');\r\naddnav(\'M?Zum Marktplatz\',\'market.php\');\r\naddnav(\'Zum Inventar\',$item_hook_info[\'ret\']);\r\n$content = unserialize($item[\'content\']);\r\nif ($row_guild = db_fetch_assoc(db_query(\"SELECT * FROM `dg_guilds` WHERE `guildid` = \".$content[\'guildid\'].\" LIMIT 1\")))\r\n{\r\n	if ($content[\'valid\']==false)\r\n	{\r\n		output(\"`qDu gehst ins Gildenviertel und zeigst dem Torwächter der Gilde deine Einladung, doch er meint nur:`I \\\"Pah, schau doch mal, die ist doch `bentwertet worden`b!\\\"`n`qNun hat dieses Dokument wohl nur noch einen symbolischen Wert für dich. Du beschließt, es vorerst noch aufzuheben.`0\");\r\n	}\r\n	elseif ($content[\'start\']>time())\r\n	{\r\n		output(\"`&Du gehst ins Gildenviertel und zeigst dem Torwächter der Gilde deine Einladung, doch er meint nur:`I \\\"Pah, schau doch mal auf das Datum, die ist doch noch gar nicht gültig!\\\"`n`&Tja, da musst du dich wohl `bnoch eine Weile gedulden`b.`0\");\r\n	}\r\n	elseif ($content[\'expire\']>=time())\r\n	{\r\n		addcommentary();\r\n		output(\"Du gehst ins Gildenviertel und zeigst dem Torwächter der Gilde deine Einladung woraufhin er dich zunächst noch einmal ermahnt, dass du dich ausschließlich in der Gildenhalle aufhalten darfst, und dann passieren lässt.`n`n`c`b\".$row_guild[\'name\'].\"`8 - Gildenhalle`b`c`n\");\r\n		viewcommentary(\"guild-\".$content[\'guildid\'],\"Mit den Gildenmitgliedern sprechen: \",25,\"spricht\",false,true,false,getsetting(\"chat_post_len_long\",1500),true,true,2);\r\n	}\r\n	else\r\n	{\r\n		output(\"`qDu gehst ins Gildenviertel und zeigst dem Torwächter der Gilde deine Einladung, doch er meint nur:`I \\\"Pah, schau doch mal auf das Datum, die ist doch `bgar nicht mehr gültig`b!\\\"`n`qNun hat dieses Dokument wohl nur noch einen symbolischen Wert für dich. Du beschließt, es vorerst noch aufzuheben.`0\");\r\n	}\r\n}\r\nelse\r\n{\r\n	output(\"`qAuf der Suche nach der Gilde sprichst du einen Wächter an und zeigst ihm deine Einladung.`I \\\"Tut mir leid für dich\\\"`q, meint er nur,`I \\\"aber diese Gilde gibt es nicht mehr!\\\"`n`qNun hat dieses Dokument wohl nur noch einen symbolischen Wert für dich. Du beschließt, es vorerst noch aufzuheben.`0\");\r\n}',0,NULL,0,0,0),('gurke','Gurke',25,'Ein Gemüse mit dunkelgrüner Haut und hellgrünem Inneren, dass zu großen Teilen aus Wasser zu bestehen scheint.',30,0,0,0,0,0,'',0,1,0,0,2,0,0,0,0,0,0,1,0,1,0,0,0,3,1,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',1,NULL,0,0,0),('Haarband','Haarband',4,'Ein `rrosa`0 Haarband, dreißig Zentimeter lang, aus feinster Seide. Dieses Geschenk hat dir {name} gemacht!',300,0,0,0,0,0,'',0,0,0,0,0,0,0,10,10,0,1,1,1,1,0,0,0,0,0,0,1,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,1),('halit','Halit',24,'Einfaches Speise- oder auch Kochsalz.',15,0,0,0,0,0,'',0,1,0,0,1,0,0,0,0,0,0,1,1,1,1,0,0,0,0,3,0,1,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,1),('Halloween_mask','³`NGrusel`,maske`N³',4,'`NEine von diesen gruseligen Masken, die sich leicht aufsetzen lassen um damit Kinder oder ängstliche Erwachsene zu erschrecken.`n\r\nWenn du genau darüber nachdenkst, erinnert dich das Gesicht der Maske doch wirklich an {name}.`0\r\n',1200,0,0,0,0,0,'',0,0,0,0,0,0,0,10,10,0,1,1,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,0),('Handspgl','`eHandspiegel',4,'Ein Handspiegel aus Weißgold. Zwanzig Zentimeter lang. In den Griff sind Diamanten eingearbeitet, und an der Rückseite befindet sich eine filigran eingearbeitete Lilie. Den hat {name} dir geschickt!',1000,3,0,0,0,0,'',0,0,0,0,0,0,0,10,10,0,1,1,1,1,0,0,0,0,0,0,1,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,1),('hanf','Hanf',26,'Hanfblätter und Stengel. Daraus kann man Seile und Kleidung machen - an etwas anderes würdest du doch wohl nie denken, oder?',50,0,0,0,0,0,'',0,1,0,0,1,0,0,0,5,0,0,1,1,1,1,0,0,0,0,0,0,1,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'_codehook_',NULL,NULL,NULL,NULL,NULL,0,0,'global $item_hook_info,$session,$item;\r\n\r\noutput(\'`tWenn es schon zu sonst nichts nutze ist, dann kann man es doch sicher rauchen...`nDenkst du dir und bereitest dir eine nette Portion \"`THalblingskraut`t\" zu, die du in deinem Beutel verschwinden lässt.`n`nLass dich bloß nicht damit erwischen!`n \');\r\n\r\nitem_add($session[\'user\'][\'acctid\'],\'hlblkraut\');\r\n\r\naddnav(\'Zum Inventar\',$item_hook_info[\'ret\']);\r\n\r\nitem_delete(\' id=\'.$item[\'id\']);\r\n\r\nreturn(true);',0,NULL,0,0,1),('hauchlux','`&Ein `RHauch `&von `rLuxus`0',7,'Jawohl! Dieses Haus hat einen Hauch von Luxus!',0,200,0,0,0,0,'',0,0,0,0,0,0,0,10,10,0,1,0,0,1,0,0,0,2,1,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,1,0,0),('hefe','Hefe',24,'Ein Pilz, der üblicherweise als Treibmittel beim Backen oder zum Vergären verwendet wird.',10,0,0,0,0,0,'',0,1,0,0,1,0,0,0,0,0,0,1,1,1,1,0,0,0,0,3,0,1,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',1,NULL,0,0,1),('heilA','`4Heiltrank A',14,'Ein einfacher Heiltrank der Klasse A.',50,0,1,1,0,0,'',0,1,0,0,1,0,0,0,0,0,0,1,1,1,1,0,0,0,0,0,0,0,0,3,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'zauber',NULL,NULL,NULL,43,0,'',0,NULL,0,0,1),('heilB','`4Heiltrank B',14,'Ein einfacher Heiltrank der Klasse B.',60,0,1,1,0,0,'',0,1,0,0,1,0,0,0,0,0,0,1,1,1,1,0,0,0,0,0,0,0,0,3,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'zauber',NULL,NULL,NULL,44,0,'',0,NULL,0,0,1),('heilC','`4Heiltrank C',14,'Ein einfacher Heiltrank der Klasse C.',70,0,1,1,0,0,'',0,1,0,0,1,0,0,0,0,0,0,1,1,1,1,0,0,0,0,0,0,0,0,3,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'zauber',NULL,NULL,NULL,45,0,'',0,NULL,0,0,1),('heilD','`4Heiltrank D',14,'Ein einfacher Heiltrank der Klasse D.',80,0,1,1,0,0,'',0,1,0,0,1,0,0,0,0,0,0,1,1,1,1,0,0,0,0,0,0,0,0,3,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'zauber',NULL,NULL,NULL,46,0,'',0,NULL,0,0,1),('heilE','`4Heiltrank E',14,'Ein einfacher Heiltrank der Klasse E.',100,0,1,1,0,0,'',0,1,0,0,1,0,0,0,0,0,0,1,1,1,1,0,0,0,0,0,0,0,0,3,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'zauber',NULL,NULL,NULL,47,0,'',0,NULL,0,0,1),('herzkissen','`$Herzkissen',4,'Ein Kissen in Herzform.',500,0,0,0,0,0,'',0,0,0,0,0,0,0,10,10,0,1,1,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,0),('hgolem','Hausgolem',15,'Hart wie Fels steht er still da, bereit jeden Eindringling deines Hauses zu zerquetschen. Laufende Kosten: 750 Gold, 3 Edelsteine.',75000,100,750,3,0,0,'',0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,54,0,'',0,NULL,0,0,0),('hintdoc','Zerfallenes Pergament',12,'Ein Pergament in sehr schlechtem Zustand. Du hoffst ihm noch irgendwelche Informationen entnehmen zu können, bevor es in deinen Händen zerfällt.',1,0,0,0,0,0,'',0,1,3,4,2,4,0,0,0,0,0,1,1,1,0,0,0,0,0,0,0,0,0,0,0,0,7,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'hintdoc',NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,1),('hlblkraut','`tHalblingskraut`0',13,'Du ahnst warum die kleinen Pelzfüße immer so munter sind...',350,0,0,0,0,0,'',0,1,0,0,0,0,0,5,5,0,0,1,1,1,1,0,0,0,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'_codehook_',NULL,NULL,NULL,NULL,NULL,0,0,'global $session;\r\n\r\n$amount=round($session[\'user\'][\'experience\']*0.02+100);\r\noutput(\"`tDu steckst dir ein Pfeifchen an und rauchst das lustige Halblingskraut.`n\");\r\n\r\n$chance = e_rand(1,2);\r\nif ($chance==1)\r\n{\r\noutput(\"`1Du `2siehst `3die `4Welt `5in `6so `8bunten `9Farben, `!wie `\\$du `%sie `&noch `tnie `)zuvor `1erlebt `2hast.`n`n`&Dir öffnen sich ganz neue Horizonte und du erhälst `^\".$amount.\"`& Erfahrungspunkte dazu!\");\r\n$session[\'user\'][\'experience\']+=$amount;\r\n}\r\nelse\r\n{\r\nif ($amount>$session[\'user\'][\'experience\']) \r\n{\r\n$amount=$session[\'user\'][\'experience\'];\r\n}\r\noutput(\"`tHat dir eigentlich noch niemand gesagt, dass kiffen dumm macht?`n`n`&Du verlierst `4\".$amount.\"`& Erfahrungspunkte!\");\r\n$session[\'user\'][\'experience\']-=$amount;\r\n}\r\n\r\nitem_delete(\' id=\'.$item[\'id\']);\r\naddnav(\'Zum Inventar\',$item_hook_info[\'ret\']);\r\n\r\n',0,NULL,0,0,1),('hldntrnk','`%Heldentrank`0',14,'Der stärkste unter den Heiltränken stellt sämtliche Lebenspunkte wieder her!',7500,2,1,1,0,0,'',0,1,0,0,1,0,0,0,0,0,0,0,1,1,1,0,0,0,0,1,0,1,0,3,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,14,0,'',0,NULL,0,0,1),('hlkrter','Beutel Heilkräuter',4,'Diese Heilkräuter heilen Wunden, bleiben aber nicht lange frisch.',500,0,0,0,1,0,'',0,0,0,0,1,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,1,1,0,0,0,0,0,1,'segen',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,21,0,'',0,NULL,0,0,1),('hlsktte','Halskette',4,'Diese Halskette hat dir {name} geschenkt.',200,0,0,0,0,0,'',0,0,0,0,0,0,0,100,100,0,0,1,1,1,0,0,0,0,0,0,1,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,1),('hlzbnk','Holzbank',7,'Eine einfach gebaute, hölzerne Bank. Rücken- und Armlehnen sind mit unauffälligen Schnitzereien verziert.',1000,5,0,0,0,0,'',0,0,0,0,0,0,0,2,2,1,1,0,1,1,0,0,0,2,1,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift',NULL,NULL,0,0,'',0,NULL,1,0,1),('hlzrgl','Holzregal',7,'Ein stabiles Regal aus dunklem Holz. ',1000,2,0,0,0,0,'',0,0,0,0,0,0,0,2,2,1,1,0,1,1,0,0,0,2,1,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift',NULL,NULL,0,0,'',0,NULL,1,0,1),('hlzsthl4','4 `THolzstühle',7,'4 massive Stühle aus Holz, wie man sie auch in der Dark Horse Taverne finden kann.',1500,4,0,0,0,0,'',0,0,0,0,0,0,0,10,10,1,1,0,1,1,0,0,0,2,1,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift',NULL,NULL,0,0,'',0,NULL,1,0,1),('hlzthrn','`THolz`sthron`0',7,'Ein Thron aus Holz.',1500,5,0,0,0,0,'',0,0,0,0,0,0,0,10,10,1,1,0,1,1,0,0,0,2,1,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift',NULL,NULL,0,0,'',0,NULL,1,0,1),('hlztsch','Holztisch',7,'Ein massiver Tisch aus Holz, wie er auch in der Dark Horse Tavern zu finden ist.',2000,4,0,0,0,0,'',0,0,0,0,0,0,0,10,10,1,1,0,1,1,0,0,0,2,1,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift',NULL,NULL,0,0,'',0,NULL,1,0,1),('hlzwge','Holzwiege',7,'Klein und fein, mit weichem Bettzeug und einem bestickten Baldachin. ',200,1,0,0,0,0,'',0,0,0,0,0,0,0,10,10,0,1,0,1,1,0,0,0,2,1,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift',NULL,NULL,0,0,'',0,NULL,1,0,1),('hmlbtt','`wH`simm`wel`sbett`0',7,'Ein großes Bett aus Eichenholz mit schweren Samtvorhängen.',30000,15,0,0,0,0,'',0,0,0,0,0,0,0,10,10,0,1,0,1,1,0,0,0,2,1,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift',NULL,NULL,0,0,'',0,NULL,1,0,1),('hndhtte','Hundehütte',7,'Eine Hütte aus Holz, bietet genug Platz selbst für den größten Hund.',3000,5,0,0,0,0,'',0,0,0,0,0,0,0,10,10,0,1,0,1,1,0,0,0,2,1,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift',NULL,NULL,0,0,'',0,NULL,1,0,1),('hnfsaat','Hanfsaat',29,'Pflanz es ein und züchte dir eine tolle Pflanze, die nicht jeder in seinem Garten hat!',75,0,9,0,0,0,'',0,1,0,0,1,0,0,0,5,0,0,1,1,1,1,0,0,0,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,1),('hngmtt','Hängematte',7,'Eine Hängematte, die mit zwei Seilen irgendwo befestigt werden muss, um einen mehr oder weniger gemütlichen Schlafplatz zu bieten. Sieht zwar bedenklich aus, trägt aber auch kräftigere Gesellen.',2000,5,0,0,0,0,'',0,0,0,0,0,0,0,5,5,0,1,0,1,1,0,0,0,2,1,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift',NULL,NULL,0,0,'',0,NULL,1,0,1),('hnwplak','Hinweisplakette',7,'Ein großes Schild, das über der Tür an der Aussenwand des Hauses angebracht wird. Trägt die Aufschrift \"`4Bettler und Hausierer werden gefoltert.`0\"',500,0,0,0,0,0,'',0,0,0,0,0,0,0,10,10,1,1,0,1,1,0,0,0,2,1,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift',NULL,NULL,0,0,'',0,NULL,1,0,1),('holzkreuz','Holzkreuz',7,'Diese aus 2 Balken zusammengenagelte Konstruktion kann böse Geister beschwören oder abwehren, je nach dem, wie man es aufhängt.',100,0,2,0,0,0,'',0,0,0,0,1,0,0,1,1,1,1,1,0,1,0,0,0,0,0,0,0,1,0,0,0,1,0,1,NULL,'woodencross',NULL,NULL,NULL,NULL,'woodencross','woodencross',NULL,NULL,'woodencross',NULL,NULL,NULL,'woodencross',NULL,0,0,'',0,NULL,0,0,0),('holzsarg','`TEin`Yfac`Iher Ho`Ylzs`Targ',7,'Ein einfach gezimmerter Holzsarg, weich gepolstert mit absolut dicht schließendem Deckel. Genau das Richtige für diejenigen, die dem Tageslicht eher abgeneigt sind.',2000,2,0,0,0,0,'',0,0,0,0,0,0,0,5,5,1,1,0,1,1,0,0,0,2,1,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift',NULL,NULL,0,0,'',0,NULL,1,0,1),('honig','Honig',25,'Leckerer Honig vom eigenen Bienenstock.',1,0,0,0,60,0,'',0,1,0,0,1,0,0,10,10,10,0,1,1,1,1,0,0,2,0,0,0,1,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'kitchen',0,0,'',1,NULL,1,0,1),('hopfen','Hopfen',24,'Eine Kletterpflanze, der diverse psychoaktive Wirkungen zugeschrieben werden.',10,0,0,0,0,0,'',0,1,0,0,1,0,0,0,0,0,0,1,1,1,1,0,0,0,0,3,0,1,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,1),('hsrcht','Das Hausrecht',7,'Eine große, schwere, mit Nägeln gespickte Keule aus poliertem Wurzelholz.',500,1,0,0,0,0,'',0,0,0,0,0,0,0,10,10,1,1,0,1,1,0,0,0,2,1,0,0,1,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift',NULL,NULL,0,0,'',0,NULL,1,0,1),('huntweapon','Jagdspeer',8,'Töte deinen Gegner mit dieser Waffe und nimm dir ein Andenken mit. Denke jedoch immer daran, dass du diese Waffe nur bei Wildtieren einsetzt, die zur Jagd freigegeben sind.',500,5,5,5,40,2,'',0,1,0,0,0,0,0,0,0,0,0,0,0,1,0,0,0,0,0,0,0,0,0,1,0,0,0,1,'huntweapon',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'huntweapon',NULL,NULL,NULL,0,0,'',0,NULL,0,0,0),('hxflch1','Fluch der Hexen',9,'Die Hexen haben dich wegen deines Verhaltens verflucht.',0,0,0,0,3,0,'',0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,'fluch',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,32,0,'',0,NULL,0,0,0),('hxflch2','Schlimmer Fluch der Hexen',9,'Die Hexen haben dich wegen deines Verhaltens verflucht.',0,0,0,0,3,0,'',0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,'fluch',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,33,0,'',0,NULL,0,0,0),('hxsgn','Segen der Hexen',11,'Die Hexen haben dich gesegnet.',0,0,0,0,3,0,'',0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,'segen',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,34,0,'',0,NULL,0,0,0),('iceflower','`#Ei`Fs`*bl`fum`se`0',4,' `#Ei`Fs`*bl`fum`sen `0 erwachsen aus den Eisblumensamen, die an Weihnachten verteilt werden',1,0,0,0,0,0,'',0,1,0,0,0,0,0,0,0,0,1,1,1,1,1,0,0,0,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,1),('iceflower_seed','`#Ei`Fsb`*lu`fme`sn`&saat`0',29,'`#Ei`Fsb`*lu`fme`sn`&samen`0 lassen die überaus seltenen Eisblumen sprießen.',1,0,13,0,0,0,'',0,0,0,0,1,0,0,0,0,0,0,1,1,1,1,0,0,0,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,1),('idoldead','`&Idol des Totenbeschwörers',13,'Eine kleine Statuette, die ihren Träger auf magische Weise unterstützt.',0,0,0,0,0,0,'`iunbezahlbar`i',0,1,0,0,0,0,1,0,0,0,0,1,1,1,0,0,0,0,0,0,0,0,0,0,2,0,7,1,'idol',NULL,NULL,'idol',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,0),('idolfish','`2Idol des Anglers',13,'Eine kleine Statuette, die ihren Träger auf magische Weise unterstützt.',0,0,0,0,0,0,'`iunbezahlbar`i',0,1,0,0,0,0,1,0,0,0,0,1,1,1,0,0,0,0,0,0,0,0,0,0,2,0,7,1,'idol',NULL,NULL,'idol',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,0),('idolgnie','`!Idol des Genies',13,'Eine kleine Statuette, die ihren Träger auf magische Weise unterstützt.',0,0,0,0,0,0,'`iunbezahlbar`i',0,1,0,0,0,0,1,0,0,0,0,1,1,1,0,0,0,0,0,0,0,0,0,0,2,0,7,1,'idol',NULL,NULL,'idol',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,0),('idolkmpf','`4Idol des Kriegers',13,'Eine kleine Statuette, die ihren Träger auf magische Weise unterstützt.',0,0,0,0,0,0,'`iunbezahlbar`i',0,1,0,0,0,0,1,0,0,0,0,1,1,1,0,0,0,0,0,0,0,0,0,0,2,0,7,1,'idol',NULL,NULL,'idol',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,0),('idolrnds','`^Idol des Waldläufers',13,'Eine kleine Statuette, die ihren Träger auf magische Weise unterstützt.',0,0,0,0,0,0,'`iunbezahlbar`i',0,1,0,0,0,0,1,0,0,0,0,1,1,1,0,0,0,0,0,0,0,0,0,0,2,0,7,1,'idol',NULL,NULL,'idol',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,0),('insgnteil','Insigniensplitter',13,'Kantig, aus hartem, matt glänzenden Metall. Besitzt eine pyramidenartige Form.',0,0,0,0,0,0,'',0,1,0,2,1,2,0,0,0,0,0,1,0,1,1,0,0,0,0,0,0,0,0,0,0,0,1,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,0),('jesuskreuz','Götzenbildnis',7,'Diese Götzenfigur zeigt einen männlichen humanoiden Körper, der auf ein Kreuz genagelt wurde. `nJe nach Glaubensrichtung kann man sie anbeten, oder im Kamin verfeuern.',1000,0,3,0,0,0,'',0,0,0,0,1,0,0,1,1,1,1,1,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,1,NULL,'woodencross',NULL,NULL,NULL,NULL,'woodencross','woodencross',NULL,NULL,'woodencross',NULL,NULL,NULL,'woodencross',NULL,0,0,'',0,NULL,0,0,0),('kaktus','`2Kaktus`0',4,'`2Aus fernen, fernen, fernen, weit entfernten und natürlich auch fremden (vielleicht auch sandigen [definitiv aber heißen]) Landen importiert, ist dieser stachlige, scheußlich-grüne autotrophe Organismus ein Musterbeispiel an extravaganter, etwas spitzfindiger Wohnlichkeit. Ein Geschenk von {name}`2.`0',3000,0,0,0,0,0,'',0,0,0,0,0,0,0,10,10,0,0,1,1,1,1,0,0,2,0,0,1,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,1,0,1),('Kalmus','Kalmus',24,'Eine schilfartige Pflanze, zu finden an Seeufern und ruhigen Flussläufen. Der Pflanze wird eine kräftigende Wirkung nachgesagt.',20,0,0,0,0,0,'',0,1,0,0,1,0,0,0,0,0,0,1,1,1,1,0,0,0,0,3,0,1,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,1),('karotte','Karotte',25,'Eine Karotte, die nicht nur Hasen schmeckt.',25,0,0,0,0,0,'',0,1,0,0,2,0,0,0,0,0,0,1,0,1,0,0,0,3,1,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',1,NULL,0,0,0),('kastanie','Kastanie',24,'Die nussartige Frucht eines Laubbaumes unter einer stachelig-fleischigen Hülle. Richtig zubereitet essbar.',10,0,0,0,0,0,'',0,1,0,0,1,0,0,0,0,0,0,1,1,1,1,0,0,0,0,3,0,1,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',1,NULL,0,0,1),('katzengold','Katzengold',3,'Wertloses Gold. Schade!',75,0,1,0,0,0,'',0,1,0,0,1,0,0,0,0,0,0,1,1,1,1,0,0,2,0,0,0,1,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,1,0,1),('katzenring','Katzenring',3,'Ein Ring mit einem grünen Stein in der Fassung. Dieser leuchtet immer leicht auf und strahlt eine unbekannte Wärme ab.',1500,1,0,0,0,0,'',0,1,0,0,1,0,0,1,1,0,0,1,1,1,1,0,0,3,0,0,0,1,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,1,0,1),('kchtpfset','`9K`so`7chtopfs`se`9t`0',7,'5 Töpfe verschiedener Größe aus feinem Edelstahl. Gehört in jeden guten Haushalt.',500,5,0,0,30,0,'Küchenutensilien - Topf',0,0,0,0,0,0,0,10,10,0,1,0,1,1,0,0,0,2,1,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift',NULL,NULL,0,0,'',0,NULL,1,0,1),('kerzenstd','Kerzenständer',4,'Ein edler Kerzenständer aus purem Gold, der genau drei Kerzen fasst. Das Gold ist mit einem Drachen versehen, der auf dem Ständer prangt. Dies ist ein Geschenk von {name}.',3500,0,0,0,0,0,'',0,0,0,0,0,0,0,10,10,0,1,1,1,1,0,0,0,0,0,0,1,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,1),('khlbck','`4K`$o`7hlebeck`$e`4n`0',7,'Hält die Glut über lange Zeit. Zum Kochen, braten und für ... andere Dinge.',50,1,0,0,0,0,'',0,0,0,0,0,0,0,10,10,0,1,0,1,1,0,0,0,2,1,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift',NULL,NULL,0,0,'',0,NULL,1,0,1),('kirsche','Kirsche',25,'Saftig und rot - da bekommst du richtig Hunger.',5,0,0,0,110,0,'',0,1,0,0,1,0,0,0,0,0,0,1,1,1,1,0,0,3,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'_codehook_',NULL,NULL,NULL,NULL,'kitchen',0,0,'global $item_hook_info,$session,$item;\r\n\r\noutput(\'Die Kirsche ist wirklich lecker!`nDen Kern hebst du dir auf.\');\r\n\r\nitem_add($session[\'user\'][\'acctid\'],\'cherrytree\');\r\n\r\naddnav(\'Zum Inventar\',$item_hook_info[\'ret\']);\r\n\r\nitem_delete(\' id=\'.$item[\'id\']);\r\n\r\nreturn(true);',1,NULL,1,0,1),('kldrpuppe','`rSprechende Kleiderpuppe`0',4,'`rJaqueline`0 - die beste Freundin der Frau! Direkt aus der Hauptstadt des Reiches bringt sie den neuesten Klatsch, die modisch ausgefeilteste Brustpanzerkollektion und Rezepte der wirksamsten Diätzauber. `rJaqueline`0 repräsentiert wahrhaftig den aktuellsten Stand der Magie auf dem Gebiet der sprechenden Kleiderpuppen. Auch als sensible Zuhörerin gibt sie eine ausgezeichnete Figur ab. Ärger mit überdimensionierten `2ORK`0statuen, \"Problemzonen\" oder gar {name}`0? `rJaqueline`0 erträgt jedes Gesprächsthema!',50000,10,0,0,0,0,'',0,0,0,0,0,0,0,10,10,0,1,1,0,1,0,0,0,0,0,0,1,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,'_codehook_',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'if($item_hook_info[\'rec_sex\'] == 0) {\r\n\r\n  output(\'`rDeine gesunde sadistische Neigung in Ehren, aber \'.$item_hook_info[\'rec_name\'].\'`r solltest du lieber eine `2ORK`r-Trophäe zum Geschenk machen..\');\r\n\r\n $item_hook_info[\'hookstop\'] = true;\r\n $item_hook_info[\'check\'] = 1;\r\n\r\n}',0,NULL,1,0,0),('Klee','`PG`kl`Gü`gc`pks`gk`Gl`ke`Pe',4,'`gDieses kleine Töpfchen mit vierblättrigem Glücksklee von {name} `gsoll dir ganz viel Glück für das Jahr `a2013 `gbringen.\r\n',1000,0,0,0,0,0,'',0,0,0,0,0,0,0,10,10,0,1,1,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,0),('kleiddummy','Maßgeschneidertes',30,'HWert1: Unterkategorie\r\ngerade=für ihn\r\nungerade=für sie\r\nHWert2: für die Grammatik\r\n0=männlich (der Mantel)\r\n1=weiblich (die Hose)\r\n2=sächlich(das Kleid)',0,10,0,0,1,2,'Name des Designers',0,1,0,0,0,0,0,5,10,0,0,1,1,1,0,0,0,2,0,0,0,0,0,0,0,2,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift','_codehook_',NULL,0,0,'if($item[value1]>0) \r\n{\r\n$item[\'value1\']=0;\r\n}',0,NULL,0,0,0),('klfale','Kleines Bierfass',3,'Cedriks hausgebrautes Bier. Eine Köstlichkeit. So gut, dass er es nie freiwillig rausrücken würde...',250,0,0,0,0,0,'',0,1,0,0,1,0,0,0,0,0,0,1,1,1,1,0,0,0,0,0,0,1,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,1),('klnrhltrnk','`rKleiner Heiltrank`0',14,'Ein schwacher Heiltrank, der aber durchaus Leben retten kann. Er enthält nur einen Schluck und heilt 25 Punkte.',250,0,1,1,0,0,'',0,1,4,0,1,0,0,0,0,0,0,0,1,1,1,0,0,0,0,1,0,1,0,3,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,9,0,'',0,NULL,0,0,1),('klpst','10 `)Kla`tpps`)tühle`0',7,'Eine improvisierte Sitzgelegenheit.',100,0,0,0,0,0,'',0,0,0,0,0,0,0,10,10,1,1,0,1,1,0,0,0,2,1,0,0,1,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift',NULL,NULL,0,0,'',0,NULL,1,0,1),('kltonschbe','Kleine Tonscheibe',3,'Wertloser Plunder',5,0,0,0,0,0,'',0,1,6,0,1,0,0,0,0,0,0,1,1,1,1,0,0,3,0,0,0,1,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,1,0,1),('klwdckl','Kleiner Wachdackel',15,'Ein niedlicher kleiner Dackel, der Einbrecher erschreckt. Laufende Kosten: 250 Gold, keine Edelsteine.',5000,15,250,0,0,0,'',0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,18,0,'',0,NULL,0,0,0),('kmin','`TK`xa`xmi`Tn`0',7,'Ein muß für kalte Wintertage aber auch für romantische Stunden.',12000,24,0,0,0,0,'',0,0,0,0,0,0,0,10,10,1,1,0,1,1,0,0,0,2,1,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift',NULL,NULL,0,0,'',0,NULL,1,0,1),('Knallbonbon','`qK`dn`Qa`Dl`$l`4b`Aon`lb`Lo`Xn',4,'`yEin (mehr oder weniger) lustiges `qK`dn`Qa`Dl`$l`4b`Ao`,n`lb`Lo`Xn `xm`ri`Et `Rg`?a`%n`=z `5v`Mi`We`1l `!s`9e`Ch`Kr `{b`wu`#n`Ft`*e`pm `gK`ao`Gn`kf`Pe`Jt`2t`ji `ydrin. `nDiesen Spaß hast du {name}`y zu verdanken..',500,0,0,0,0,0,'',0,0,0,0,0,0,0,10,10,0,1,1,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,0),('kndbett','`vK`rind`serbet`rtche`vn`0',7,'Aus edlem Holz mit feinen Malereien verziert.',2500,5,0,0,0,0,'',0,0,0,0,0,0,0,10,10,0,1,0,1,1,0,0,0,2,1,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift',NULL,NULL,0,0,'',0,NULL,1,0,1),('knethorn','`xK`rn`&ethörnch`re`xn',4,'`&Ein `xEichhörnchen`& aus Plüsch, zum Würgen und Kneten. `^Achtung, beim übermäßigen Gebrauch kann es kratzen und beißen - `eTollwut nicht ausgeschlossen!`& Das ist von {name}`&.',1000,0,0,0,0,0,'',0,0,0,0,0,0,0,10,10,0,1,1,1,1,0,0,0,2,0,0,1,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,1,0,1),('knoblauch','Knoblauch',24,'Eine zwiebelartige Frucht, oftmals als Gewürz (oder auch gegen Vampire) verwendet.',10,0,0,0,0,0,'',0,1,0,0,1,0,0,0,0,0,0,1,1,1,1,0,0,0,0,3,0,1,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',1,NULL,0,0,1),('Kochbuch','Kochbuch für Junggesellen',4,'Ein Kochbuch, für alle Junggesellen, die sich selbst versorgen müssen. Das hier hat dir {name} geschickt. Guten Hunger. ',500,0,0,0,0,0,'',0,0,0,0,0,0,0,10,10,0,1,1,1,1,0,0,0,2,0,0,1,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,1,0,1),('kochkunst','Mittagessen',25,'',1,0,0,0,0,0,'',0,1,0,0,0,0,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1,'mittagessen','mittagessen',NULL,NULL,NULL,NULL,'mittagessen',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,0),('kompass','Kompass',3,'Ein alter, relativ gut erhaltener Kompass. Leider zeigt die Nadel stets nur nach Osten.',0,1,0,0,0,0,'',0,1,2,0,1,0,0,1,1,0,0,1,1,1,1,0,0,3,0,0,0,1,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,1,0,1),('kpuppe','Kadaverpuppe',13,'',1,0,0,0,0,0,'',0,1,0,0,0,0,0,1,1,0,1,1,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,'kpuppe','kpuppe',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,0),('krbchen','Körbchen',7,'Für kleine Hunde und für Katzen bestens geeignet.',50,0,0,0,0,0,'',0,0,0,0,0,0,0,10,10,0,1,1,1,1,0,0,0,2,1,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift',NULL,NULL,0,0,'',0,NULL,1,0,1),('kreisel','`gDrehkreisel`0',4,'Dreh dich kleiner Kreisel...\r\nEin nettes, sinnloses Spielzeug!',10,0,0,0,0,0,'',0,0,0,0,0,0,0,10,10,3,1,1,1,1,1,0,0,0,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'_codehook_',NULL,NULL,NULL,NULL,NULL,0,0,'global $session,$badguy;\r\n\r\noutput(\"`&Du drehst den Kreisel,\");\r\nif (e_rand(1,6)==2)\r\n{\r\noutput(\"`& und bekommst eine weitere Runde für heute!`n\");\r\n$session[\'user\'][\'turns\']++;\r\n}\r\nelse\r\n{\r\noutput(\"`& und er verschwindet plötzlich - seltsam...`n\");\r\nitem_delete(\' id=\'.$item[\'id\']);\r\n}\r\n\r\nif (!$badguy[\'creaturehealth\']>0)\r\n{\r\naddnav(\'Zum Inventar\',$item_hook_info[\'ret\']);\r\n}',0,NULL,0,0,1),('kristring','`#Kristallring',4,'Ein bezaubernder Ring für den oder die Liebste gut geeignet.',1000,0,0,0,0,0,'',0,0,0,0,0,0,0,10,10,0,1,1,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,0),('krnlcht','Kronleuchter',7,'Ein riesieger, prunkvoller, goldener Kronleuchter, von dem kristallene Tropfen herabhängen. Zwei dutzend Kerzen haben hierrauf Platz, die auf magische Weise niemals niederbrennen und somit auch kein Wachs hinuntertropfen lassen.',10000,50,0,0,0,0,'',0,0,0,0,0,0,0,1,1,1,1,0,1,1,0,0,0,2,1,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift',NULL,NULL,0,0,'',0,NULL,1,0,1),('krschhlz','Kirschbaumholz',26,'Edelstes Holz vom Stamm eines ausgewachsenen Kirschbaumes. Eignet sich hervorragend für edle Schreinerkunst!',1000,0,0,0,0,0,'',0,1,0,0,0,0,0,25,25,0,0,1,1,1,1,0,0,3,0,0,0,1,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,1,0,1),('krsllkgl','Kristallkugel',7,'Eine glasklare Kugel aus feinstem Kristall. Während sie für den einen nicht mehr als ein hübscher Briefbeschwerer ist, können magisch begabte Wesen sicherlich mehr damit anfangen.',0,40,0,0,0,0,'',0,0,0,0,0,0,0,1,1,1,1,0,1,1,0,0,0,2,1,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift',NULL,NULL,0,0,'',0,NULL,1,0,1),('krstlspgl','`wK`srist`7alls`spiege`wl`0',7,'Großer, edler Spiegel aus reinstem Kristallglas!',15000,50,0,0,0,0,'',0,0,0,0,0,0,0,1,1,0,1,0,0,1,0,0,0,2,1,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,'spiegel','spiegel',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,1,0,0),('krzn','Kerzen',7,'Einfache, klobige Wachskerzen, die nur den Sinn haben, etwas Licht zu spenden.',500,0,0,0,0,0,'',0,0,0,0,0,0,0,5,5,1,1,0,1,1,0,0,0,2,1,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift',NULL,NULL,0,0,'',0,NULL,1,0,1),('kschlblrg','`rKuschel`$balrog`0',4,'`&Eine äußerst `rputzige`& Miniatur eines Balrogs, bei Bedarf auch als Wäscheständer oder Gartenzwergersatz nutzbar. Mit kuschelweichem Fell, anziehend glühendroten Augen und umwerfend heißem Feueratemimitat. Um den Hals trägt er ein Schildchen: `7\"Ich bin ein Geschenk von {name}`7!\"',750,0,0,0,0,0,'',0,0,0,0,0,0,0,10,10,0,1,1,1,1,0,0,0,2,0,0,1,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,1,0,1),('kschldck','Kuscheldecke',7,'Eine ganz besonders warme und weiche Decke in die man sich immer und überall einwickeln kann. Ob auf dem Sofa oder im Bett, hier fühlt man sich sicher und geborgen.',500,5,0,0,0,0,'',0,0,0,0,0,0,0,5,5,1,1,0,1,1,0,0,0,2,1,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift',NULL,NULL,0,0,'',0,NULL,1,0,1),('ksersthl','Kaiserstuhl',7,'Toilettenschüssel aus purem Gold. Mit gepolsterter Rückenlehne und Armablage.',10000,20,0,0,0,0,'',0,0,0,0,0,0,0,10,10,0,1,0,1,1,0,0,0,2,1,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift',NULL,NULL,0,0,'',0,NULL,1,0,1),('kstsgkt','`5K`&iste `vvoller `%S`&üßi`&gkeiten`0',7,'Randvoll mit allem was dick macht.',200,0,0,0,0,0,'',0,0,0,0,0,0,0,10,10,0,1,0,1,1,0,0,0,2,1,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift',NULL,NULL,0,0,'',0,NULL,1,0,1),('kuerbis','`DK`Qü`drb`Qi`Ds',4,'`dEiner dieser orangen Gesellen, bereits ausgehöhlt und mit einem Gesicht verziert, stellst du ihn entweder mit eine Kerze vor der Tür oder versucht doch noch ein paar Reste für eine leckere Kürbissuppe aus ihm rauszukratzen.`nSein Grinsen erinnert sich ein bisschen an `0{name}`d.\r\n',1000,0,0,0,0,0,'',0,0,0,0,0,0,0,10,10,0,1,1,0,1,0,0,0,0,0,0,1,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,0),('lametta','`7Engelshaar`0',3,'Eine handvoll dünne, silbergraue Fäden. Sie glitzern wunderbar.',50,0,0,0,0,0,'Christbaumschmuck',0,1,0,7,1,7,0,10,10,0,0,1,0,1,0,0,0,0,0,0,0,0,0,0,0,0,5,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,0),('ldrbuch','Almanach der tausend Lieder',4,'',1,0,0,0,0,0,'',0,0,0,0,0,0,0,0,0,0,0,0,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'_codehook_',NULL,NULL,NULL,NULL,NULL,0,0,'global $item_hook_info;\r\n\r\noutput (\'`^Vor dir offenbart sich ein unendlich reichhaltiger Schatz an Hymnen und Liedern. Besonders gut geeignet zum Vorsingen!`n`n\');\r\n\r\noutput ( get_extended_text ( \'hymne_pap\' ) );\r\n\r\noutput ( \'`n\'. get_extended_text ( \'gedanken_frei\' ) );\r\n\r\naddnav(\'Zurück zum Inventar\', $item_hook_info[\'ret\']);',0,NULL,0,0,0),('ldrsfa','Ledersofa',7,'Ein edles Sofa aus Drachenleder.',5000,12,0,0,0,0,'',0,0,0,0,0,0,0,10,10,1,1,0,1,1,0,0,0,2,1,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift',NULL,NULL,0,0,'',0,NULL,1,0,1),('lichtring','Ring des Lichts',3,'Als du den Ring in die Hand nimmst, beginnt er auf magische Weise zu leuchten und erhellt deine unmittelbare Umgebung.',200,0,0,0,0,0,'',0,1,2,0,1,0,0,0,0,0,0,1,1,1,1,0,0,3,0,0,0,1,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,1,0,1),('lichtstein','Lichtstein-Splitter',24,'Ein winziger Splitter eines der legendären Lichtsteine von Daymos. Du kannst deutlich das starke magische Kribbeln spüren, selbst wenn du ihn nicht in der Hand hältst.',0,2,0,0,0,0,'',0,1,0,0,1,0,0,0,0,0,0,1,1,1,1,0,0,3,1,0,0,1,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,1,0,1),('Liebesbrf','`RL`riebesbrie`Rf',4,'`r„..denn wie ein Blitz schlug sie ein, die wunderschöne Liebe mein..“ `7Die schönsten Anekdoten aufs Papier gebracht, ließ {name}`7 dir diesen Brief [seiner|ihrer] Gefühle zukommen. ',50,0,0,0,0,0,'',0,0,0,0,0,0,0,10,10,0,1,1,0,1,0,0,0,0,0,0,1,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,0),('lovedoll','Teddybär',7,'',1,0,0,0,0,0,'',0,0,0,0,0,0,0,1,1,1,0,1,1,1,0,0,0,2,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,'teddy','teddy',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,1),('macanut','Macadamia-Nüsse',13,'Ein paar Macadamia-Nüsse. Lecker und nahrhaft. Aber viel zu schade um sie einfach so wegzunaschen.',20,0,0,0,0,0,'',0,1,0,0,1,0,0,100,100,100,0,1,1,1,1,0,0,1,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,1),('magblmnvse','Phantastisch-Magische Blumenvase',14,'Diese Blumenvase ist etwas Besonderes: Aus ihr sprießen wunderbar bunt blühende Blumen, ohne etwas dafür tun zu müssen. Etwas Wasser und gelegentlich ein liebes Lächeln ist ausreichend.',4000,1,5,0,0,0,'',0,1,0,0,0,0,0,0,0,0,0,1,0,1,0,0,0,2,0,0,1,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'_codehook_',NULL,NULL,NULL,NULL,NULL,0,0,'global $item,$session,$item_hook_info;\r\n\r\n$rec = $session[\'user\'][\'acctid\'];\r\n\r\n$name = \'\';\r\n\r\n$name .= \'`\'.e_rand(1,9).\'Bl\'.\'`\'.e_rand(1,9).\'üt\'.\'`\'.e_rand(1,9).\'en\'.\'`\'.e_rand(1,9).\'bla\'.\'`\'.e_rand(1,9).\'tt\';\r\n\r\n$name .= \' `7 von \'.$session[\'user\'][\'login\'];\r\n\r\n$flower = array(\'tpl_name\'=>$name);\r\n\r\nitem_add($rec,\'blume\',$flower);\r\n\r\n$item[\'value1\']--;\r\n\r\noutput(\'`rIn deiner magischen Blumenvase wächst ein zartes Blütlein, das etwa so aussieht: \'.$name.\'`r!\');\r\n\r\nif($item[\'value1\'] <= 0) {\r\n\r\noutput(\'`^Deine magische Blumenvase blubbert nur noch schwach.\');\r\n\r\nitem_delete(\' id=\'.$item[\'id\']);\r\n\r\n}\r\nelse {\r\n\r\nitem_set(\' id=\'.$item[\'id\'],$item);\r\n\r\n}\r\n\r\naddnav(\'Zurück zum Inventar\',$item_hook_info[\'ret\']);\r\n\r\n\r\n',0,NULL,1,0,0),('magicmirror','Magischer Spiegel',7,'Ein sprechender Spiegel, der dir etwas über deine Schönheit sagen kann.',10000,20,0,0,0,0,'',0,0,0,0,0,0,0,1,1,0,1,1,0,1,0,0,0,0,1,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,'magicmirror','magicmirror',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,0),('magiebarr','`!Magiebarriere`0',14,'Ein magischer Schutzschild, der dich 10 Runden lang vor allen Angriffen schützt.',7500,5,1,1,0,0,'',0,1,0,0,1,0,0,0,0,0,0,0,1,1,1,0,0,0,0,1,0,0,0,3,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,5,0,'',0,NULL,0,0,1),('magvglkfg','Magischer Vogelkäfig',7,'Ein großer, schlichter Vogelkäfig aus dickem, rötlichen Draht. Es handelt sich nicht um einen normalen Käfig, sondern um einen mit unbekannten magischen Eigenschaften.',100,5,0,0,0,0,'',0,0,0,0,0,0,0,1,1,0,1,1,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,0),('makbaer','`WMaka Bär`0',4,'`&Er ist finster, er ist schwarz, er ist der niveaulose Bodensatz des guten Geschmacks. Du kannst ihn nur lieben oder hassen, dazwischen gibt es nichts. Den hat dir {name}`& geschenkt.',750,0,0,0,0,0,'',0,0,0,0,0,0,0,10,10,0,1,1,1,1,0,0,0,2,0,0,1,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,1,0,1),('mapt','Schatzkartenteil',13,'Eindeutig ein Teil einer Schatzkarte! Nur allein bringt der wohl nichts..',3000,4,0,0,0,0,'',0,1,3,4,0,4,7,0,0,0,0,1,1,1,0,0,0,3,0,0,0,0,0,0,0,0,5,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,1,0,1),('mapw','Wertloser Schatzkartenteil',13,'Der Schatz wurde wohl schon gehoben. So erzählt man sich zumindest in der Schenke. Also wohl wertlos, das Teil..',1,0,0,0,1,0,'',0,1,0,7,1,7,0,0,0,0,0,0,0,1,0,0,0,3,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,1,0,0),('marmorsplit','`eMa`Nr`(m`eorsp`(l`Ni`etter',4,'Ein kostbares Stück Marmor, glänzend und kostbar.',1000,1,0,0,0,0,'',0,0,0,0,0,0,0,10,10,0,1,1,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,0),('mbl','Möbeldummy',7,'',0,0,0,0,0,0,'',0,0,0,0,0,0,0,20,20,0,1,1,0,1,1,0,0,2,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,1,0,0),('Medaillon','`)Medaillon',4,'Ein silbernes Medallion, mit Edelsteinen besetzt, an einer filigranen Kette. Trägt im Inneren das Bildnis von {recipient_name} und {name}. ',2500,5,0,0,0,0,'',0,0,0,0,0,0,0,10,10,0,1,1,1,1,0,0,0,2,0,0,1,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,1,0,1),('medal','Orden',4,'',0,0,0,0,0,0,'',0,0,0,0,0,0,0,10,10,0,1,1,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,0),('mehl','Ein Beutel Mehl',25,'Ein kleiner Beutel frisch gemahlenes Weizenmehl.',35,0,0,0,10,0,'',0,1,0,0,1,0,0,0,0,0,0,1,1,1,1,0,0,3,1,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',0,0,'',1,NULL,1,0,1),('met','`&Flasche`^ M`yet',25,'Eine mit einem dicken Korken verschlossene Flasche von dem süßen Gesöff. Der Inhalt ist eine klare, gelbe Flüssigkeit und wenn man den Korken öffnet steigt einem der unverwechselbare, betörende Geruch in die Nase.',200,0,1,0,40,0,'',0,0,0,0,1,0,0,2,2,0,0,1,1,1,1,0,0,3,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'_codehook_',NULL,NULL,NULL,NULL,NULL,0,0,'global $session,$item_hook_info;\r\n\r\nif (e_rand(1,5)==2)\r\n{\r\noutput(\"`&Gierig schüttest du dir den Met hinter die Binde. Etwas benommen bemerkst du, dass du deine Trinkfestigkeit falsch eingeschätzt hast. Taumelnd und lallend torkelst du durch die Gegend und machst schließlich eine unsanfte Bekanntschaft\r\nmit einer Wand.`nAu weia, das gibt eine hässliche Beule!\r\n`4`nDu hast einen Charmepunkt verloren!`0\");\r\nif ($session[\'user\'][\'charm\']>0)\r\n\r\n{\r\n$session[\'user\'][\'charm\']-=1;\r\n}\r\n}\r\nelse\r\n{\r\noutput(\"`&Genüsslich lässt du den Met deine Kehle hinunter rinnen. Du fühlst dich fast wie eine Gottheit und bemerkst, dass das Lächeln auf deinen Lippen dich hübscher macht!`n`@Du erhälst einen Charmepunkt!`0\");\r\n$session[\'user\'][\'charm\']+=1;\r\n}\r\n\r\nitem_delete(\' id=\'.$item[\'id\']);\r\n\r\naddnav(\'Zum Inventar\',$item_hook_info[\'ret\']);\r\n',1,NULL,1,0,1),('metestaub','Meteorstaub',24,'Eine kleine Menge feinen Eisenstaubes, durch magische Rituale aus den Splittern kleiner Meteoriten extrahiert.',150,0,0,0,0,0,'',0,1,0,0,1,0,0,0,0,0,0,1,1,1,1,0,0,3,1,0,0,1,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,1,0,1),('mhlzt_res1','Mahlzeit',25,'',0,0,0,0,0,0,'',0,1,0,0,1,0,0,0,1,0,0,1,1,1,1,0,0,2,0,0,0,0,0,0,0,0,0,1,'kitchen','kitchen',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'kitchen',NULL,NULL,NULL,NULL,NULL,0,0,'',1,NULL,1,0,1),('mhlzt_res2','Mahlzeit',25,'',0,0,0,0,0,0,'',0,1,0,0,1,0,0,0,1,0,0,1,1,1,1,0,0,2,0,0,0,0,0,0,0,0,0,1,'kitchen','kitchen',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'kitchen',NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,1,0,1),('mice','`&Weiße Maus`0',21,'Mit weißen Mäusen kann man Experimente machen. Doch Vorsicht: Die Sache könnte seltsame Folgen haben!',700,0,3,3,0,2,'',0,1,0,0,0,0,0,100,100,0,1,1,0,1,1,0,0,0,0,0,0,1,0,3,0,0,0,1,'','','','','','','','','','','','','_codehook_','','','',0,0,'global $badguy,$session;\r\n\r\nif ($badguy[\'creaturename\']==\'\'){\r\noutput(\"`tDu packst \".$item[\'name\'].\" mit spitzen Fingern im Nacken und schleuderst es `&\".$badguy[\'name\'].\" `tentgegen.`n\");}\r\nelse{output(\"`tDu packst \".$item[\'name\'].\" mit spitzen Fingern im Nacken und schleuderst es `&\".$badguy[\'creaturename\'].\" `tentgegen.`n\");}\r\n\r\n$damage=round(20+e_rand(1,30)+e_rand(1,$session[\'user\'][\'dragonkills\']*0.5));\r\n$damage+=($item[hvalue]*10);\r\n\r\noutput(\"`tKratzend und beißend verursacht \".$item[\'name\'].\" `4\".$damage.\"`t Schaden.\");\r\n\r\n$badguy[\'creaturehealth\']-=$damage;\r\n\r\nif ($item[\'value1\'] <= 1)\r\n{\r\n$res = item_tpl_list_get( \'tpl_name=\"`&Frustrierte weiße Maus`0\" LIMIT 1\' );\r\n\r\nif( db_num_rows($res) )\r\n{\r\n$itemnew = db_fetch_assoc($res);\r\n$itemnew[\'tpl_hvalue\']=$item[\'hvalue\'];\r\n$itemnew[\'tpl_hvalue2\']=$item[\'hvalue2\'];\r\n$itemnew[\'tpl_special_info\']=$item[\'special_info\'];\r\nif (strpos($item[\'name\'],\"(`&We\"))\r\n{\r\n$oldname=substr($item[\'name\'],0,strrpos($item[\'name\'],\"(`&We\"));\r\ntrim($oldname);\r\n$itemnew[\'tpl_name\']=$oldname.\"(`&Frustrierte weiße Maus`&)\";\r\n}\r\nitem_add($session[\'user\'][\'acctid\'],0,$itemnew);\r\nitem_delete( \' id=\'.$item[\'id\']);\r\n}\r\n}',0,'',0,0,0),('micefru','`&Frustrierte weiße Maus`0',21,'Sichtlich beleidigt über die Art wie du sie ständig durch die Gegend wirfst, wird sie erstmal gar nichts mehr tun! Höchstens dir in den Finger beissen...',350,0,0,2,0,0,'',0,1,0,0,0,0,0,100,100,0,1,1,0,1,1,0,0,0,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'_codehook_',NULL,NULL,NULL,NULL,NULL,0,0,'global $session,$item_hook_info;\r\n\r\noutput(\"`&Du versuchst die Maus zu streicheln, doch sie beisst dich!`nDu verlierst ein paar Lebenspunkte.`0\");\r\n\r\n$session[\'user\'][\'hitpoints\']*=0.95;\r\n\r\naddnav(\'Zum Inventar\',$item_hook_info[\'ret\']);',0,NULL,0,0,0),('milchkn','Milchkanne',25,'Eine Kanne gefüllt mit frischer Milch.',1,0,0,0,0,0,'',0,1,0,0,1,0,0,10,10,10,0,1,1,1,1,0,0,2,0,0,0,1,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',1,NULL,1,0,1),('minikrga','`gminiatur `@Kräuter`2garten',7,'Besteht aus einem großräumigen, rechteckigen Tontopf in dem viele  Kräuterpflanzen wachsen die in keiner Küche fehlen sollten.',100,0,1,0,0,0,'',0,0,0,0,0,0,0,1,1,1,0,1,1,1,1,0,0,2,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'_codehook_',NULL,NULL,NULL,NULL,NULL,0,0,'global $session,$item_hook_info;\r\n\r\nif (e_rand(1,4)==2)\r\n{\r\noutput(\"`&Mit dem Kampfschrei`@\'Kocheeeen!\'`0 rupfst du herzlos die Blätter der zarten Planzen einfach ab. Ohne groß darauf zu achten was du da alles in den Topf wirfst, köchelst du dir eine stinkende Pampe zusammen. Als du schließlich mutig wie du nun einmal bist, etwas davon kostest, bleibt dir das Essen im Halse stecken und dir wird ganz anders. Damit wirst du Niemanden beeindrucken können.\r\n`4`nDu hast einen Charmepunkt verloren!`0\");\r\nif ($session[\'user\'][\'charm\']>0)\r\n\r\n{\r\n$session[\'user\'][\'charm\']-=1;\r\n}\r\n}\r\nelse\r\n{\r\noutput(\"`&`&Da du nun eine ausgeziechnete Auswahl an exotischen Kräutern besitzt, sind auch deine Fähigkeiten als Koch schmackhafter geworden. Das schindet natürlich Eindruck bei deinen Gästen, was dich attraktiver macht als du eigentlich bist!`n`@Du erhälst einen Charmepunkt!`0\");\r\n$session[\'user\'][\'charm\']+=1;\r\n}\r\n\r\nitem_delete(\' id=\'.$item[\'id\']);\r\n\r\naddnav(\'Zum Inventar\',$item_hook_info[\'ret\']);',0,NULL,1,0,1),('minvernelx','Minotauren-Vernichtungselixier',14,'Ein mächtiges Elixier, das Minotauren zu vernichten vermag.',500,0,1,0,0,0,'',0,1,0,0,1,0,0,0,0,0,0,1,1,1,1,0,0,0,0,0,0,0,0,1,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'_codehook_',NULL,NULL,NULL,0,0,'global $badguy,$session;\r\n\r\noutput(\'`n`b`^Du schleuderst das Fläschchen auf deinen Gegner,`n`b\');\r\n\r\nif(strpos($badguy[\'creaturename\'],\'Minotaurus\')) {\r\n\r\noutput(\'`^`bund \'.$badguy[\'creaturename\'].\'`^ windet sich unter Qualen und löst sich plötzlich in einem\r\n stinkenden Wölkchen auf.`b`n`n\');\r\n$badguy[\'creaturehealth\'] = 0;\r\n}\r\nelse{\r\noutput(\'`^`baber außer einem hübschen Fleck hat es keinen Effekt auf \'.$badguy[\'creaturename\'].\'`^.`b`n\');\r\n}\r\n\r\nitem_delete(\' id = \'.$item[\'id\']);\r\n',0,NULL,0,0,1),('mistel','Mistel',24,'Ein kleines, vertrocknetes Exemplar des parasitären Mistelgewächses.',15,0,0,0,0,0,'',0,1,0,0,1,0,0,1,1,0,1,1,1,1,1,0,0,0,0,3,0,1,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,1),('mistelzwg','`2Mistelzweig für Verliebte',4,'Ein Mistelzweig der die Liebe noch großer werden lässt.',500,0,0,0,0,0,'',0,0,0,0,0,0,0,10,10,0,1,1,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,0),('mitld','`RJede Menge Mitleid`0',4,'Einfach nur so, zum Trost, oder als Rache für Bettelaktionen jeglicher Art:\r\n{name} findet dich äußerst mitleiderregend!',50,0,0,0,0,0,'',0,0,0,0,0,0,0,10,10,0,1,0,0,1,0,0,0,0,0,0,1,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,0),('mlleimer','Mülleimer',7,'Kleiner Bastkorb, der allen möglichen Unrat aufnehmen kann.',10,0,0,0,0,0,'',0,0,0,0,0,0,0,10,10,1,1,0,1,1,0,0,0,2,1,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift',NULL,NULL,0,0,'',0,NULL,1,0,1),('mohn','Mohn',24,'Eine orangerot blühende Pflanze. Es sind noch einige Samenkapseln in der Blüte vorhanden.',25,0,0,0,0,0,'',0,1,0,0,1,0,0,0,0,0,0,1,1,1,1,0,0,0,0,3,0,1,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,1),('mountfttr','Tierfutter',4,'Besonders nahrhaftes Futter für nahezu jeden tierischen Begleiter; steigert die Ausdauer.',250,0,15,0,0,0,'',0,0,0,0,1,0,0,0,0,0,0,1,1,1,1,0,0,2,0,0,0,1,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'_codehook_',NULL,NULL,NULL,NULL,NULL,0,0,'global $playermount,$session,$item;\r\n\r\nif($session[\'bufflist\'][\'mount\'][\'rounds\']) {\r\n\r\noutput(\'`5Dein/e \'.$playermount[\'mountname\'].\'`5 reißt dir das Futter beinahe aus den Händen, als du es ihm entgegenstreckst! Schmatzend und würgend verschwindet das Zeug im Rachen deines Begleiters, der dir nun bedeutend gestärkt vorkommt!`n`nDein Tier erhält \'.$item[\'value1\'].\' zusätzliche Runden.\');\r\n\r\n$session[\'bufflist\'][\'mount\'][\'rounds\'] += $item[\'value1\'];\r\n\r\nitem_delete(\' id=\'.$item[\'id\']);\r\n\r\n}\r\nelse {\r\n\r\noutput(\'`5Zwar würdest du deinem/r \'.$playermount[\'mountname\'].\' liebend gern das Spezialfutter anbieten, doch kannst du deinen Begleiter nirgends entdecken..`nDu wirst wohl bis morgen warten müssen.\');\r\n\r\n}\r\n\r\naddnav(\'Zum Inventar\',$item_hook_info[\'ret\']);',0,NULL,1,0,1),('mthrnstnd','`tMet`Thorn`tständer`0',7,'Kreisrundes Eisengeflecht, das 12 Methörner aufnehmen kann.',250,1,0,0,0,0,'',0,0,0,0,0,0,0,10,10,0,1,0,1,1,0,0,0,2,1,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift',NULL,NULL,0,0,'',0,NULL,1,0,1),('mueckfalle','Mückenfalle',3,'Ein Glas mit einer klebrigen gelblichen Substanz darin. Es zieht Mücken magisch an.',25,0,1,0,0,0,'',0,0,0,0,1,0,0,1,1,0,0,1,1,1,1,0,0,2,0,0,0,1,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,1,0,1),('muenze','Alte Münze',3,'Eine imperiale Münze. Sicher einige hundert Jahre alt.',0,1,0,0,0,0,'',0,1,2,0,1,0,0,0,0,0,0,1,1,1,1,0,0,3,0,0,0,1,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,1,0,1),('muschel','Eine farbige Muschel',3,'Eine bunte Muschel, welche du am Strand gefunden hast. Die Außenseite ist leicht gewellt, die Innenseite schimmert in wunderschönen Perlmuttfarben.',50,0,0,0,0,0,'',0,1,0,7,1,7,0,10,10,0,0,1,1,1,0,0,0,0,0,0,0,0,0,0,0,0,7,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,1),('muschelkette','Eine Muschelkette',4,'Eine kleine Kette die aus einzelnen Muscheln zusammengesetzt wurde. Jede Muschel wurde mit einem Buchstaben beschrieben.',1,0,0,0,0,0,'',0,0,0,0,0,0,0,15,15,0,0,1,1,1,0,0,0,0,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,1),('nachtsch','Nachtschatten',24,'Ein giftiges Nachtschattengewächs mit rosaweißen Blüten.',20,0,0,0,0,0,'',0,1,0,0,1,0,0,0,0,0,0,1,1,1,1,0,0,0,0,3,0,1,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,1),('nadelundfaden','`sNadel `&und `;Faden',18,'Ein kleines Nähset bestehend aus Nadel und Faden.',50,1,0,0,0,0,'',0,1,0,0,0,0,0,0,0,0,0,1,1,1,0,0,0,3,1,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,1),('navikopf','Der Kopf des Navigators',16,'Der Kopf des Navigators, erworben in einem wortreichen Kampf.',1,0,0,0,0,0,'',0,1,0,0,1,0,0,0,0,0,0,1,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,0),('nchttpf','Nachttopf',7,'\r\nEin schlichter Nachttopf. Mehr gibt es dazu nicht zu sagen.',1000,1,0,0,0,0,'',0,0,0,0,0,0,0,5,5,0,1,0,1,1,0,0,0,2,1,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift',NULL,NULL,0,0,'',0,NULL,1,0,1),('nessie','`9N`3e`#ss`3i`9e`0',7,'Das sagenumwobene Ungeheuer aus dem Waldsee, besiegt nach einem harten Kampf.',15000,20,0,0,0,0,'',0,0,0,0,0,0,0,1,1,1,1,1,0,1,0,0,0,2,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,1,0,0),('nichts','Nichts',3,'Es ist nichts wert, du kannst nichts damit anfangen, du kannst es nicht wegwerfen, aber es schadet auch nichts, Nichts dabei zu haben.',0,0,0,0,0,0,'',0,1,0,0,1,0,0,0,0,0,0,0,1,1,1,0,0,3,0,0,0,1,0,0,0,2,5,1,NULL,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'_codehook_',NULL,0,0,'if($item[value1]>0) \r\n{\r\n$item[\'value1\']=0;\r\n}\r\n',0,NULL,1,0,1),('nightie_01','Nachtgewand',30,'Ein schlichtes, kurzärmeliges Nachtgewand aus leichtem Leinenstoff, das gerade übers Knie reicht.',1000,0,0,0,1,2,'Flavius Nadelflink',0,1,0,0,0,0,0,0,0,0,0,1,1,1,0,0,0,0,0,0,0,0,0,0,0,2,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift','_codehook_',NULL,0,0,'if($item[value1]>0) \r\n{\r\n$item[\'value1\']=0;\r\n}',0,NULL,0,0,1),('nightie_03','Nachthemd',30,'Ein leinenes, langärmeliges Nachthemd, das etwa bis zu den Waden reicht.',1500,0,0,0,1,2,'Flavius Nadelflink',0,1,0,0,0,0,0,0,0,0,0,1,1,1,0,0,0,0,0,0,0,0,0,0,0,2,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift','_codehook_',NULL,0,0,'if($item[value1]>0) \r\n{\r\n$item[\'value1\']=0;\r\n}',0,NULL,0,0,1),('Nikolaus','`4Schokonikolaus',4,'`IEin kleiner, in buntes Papier gewickelter Nikolaus aus leckerer Schokolade. Ein kleine Aufmerksamkeit zum Nikolaustag von {name}`I.\r\n',1000,0,0,0,0,0,'',0,0,0,0,0,0,0,10,10,0,1,1,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,0),('ninjagas','`yRauchbombe',14,'Eine Kugel aus schwarzem Glas, gefüllt mit verschiedenen, bei Kontakt mit der Luft starken Rauch entwickelnden Flüssigkeiten. ',150,0,1,1,0,0,'',0,1,0,0,1,0,0,0,0,0,0,1,1,1,1,0,0,0,0,0,0,0,0,3,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'zauber',NULL,NULL,NULL,52,0,'',0,NULL,0,0,1),('ninjagro','`yGroße Gaskugel',14,'Eine große Kugel aus schwarzem Glas. Vorsicht! Zerbrechlich und Explosiv!',200,0,1,1,0,0,'',0,1,0,0,1,0,0,0,0,0,0,1,1,1,1,0,0,0,0,0,0,0,0,3,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'zauber',NULL,NULL,NULL,51,0,'',0,NULL,0,0,1),('ninjaklei','`yKleine Gaskugel',14,'Eine kleine Kugel aus schwarzem Glas. Vorsicht! Zerbrechlich und Explosiv!',100,0,1,1,0,0,'',0,1,0,0,1,0,0,0,0,0,0,1,1,1,1,0,0,0,0,0,0,0,0,3,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'zauber',NULL,NULL,NULL,50,0,'',0,NULL,0,0,1),('nixbrot','`TBrot mit `&Nichts`T drauf',3,'Ein Brot mit Nichts ist immer noch besser als eins ohne alles. Aber eine kümmerliche Mahlzeit stellt es allemal dar.',26,0,0,0,0,0,'',0,1,0,0,0,0,0,100,100,100,0,1,1,1,1,0,0,3,0,0,0,1,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,1,0,1),('noobschutz','Anfängerglück',13,'Anfängerglück schützt arme Bauernkinder, die sich in der gefährlichen Welt nicht behaupten können.',2500,5,1,0,0,0,'',0,1,0,0,1,0,0,0,0,0,0,1,0,0,0,0,0,0,0,0,1,0,0,1,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'_codehook_',NULL,NULL,NULL,0,0,'global $session,$badguy;\r\n\r\nif ($session[\'user\'][\'dragonkills\']>0)\r\n{\r\noutput(\"`&Da du den Drachen bereits besiegt hast bist du kein Anfänger mehr und das Glück ist leider nicht auf deiner Seite!\");\r\n}\r\nelse\r\n{\r\noutput(\"`&Du umgibst dich mit der schützenden Aura des Anfängerglücks.\");\r\n                            \r\nif (isset($session[\'bufflist\'][\'nprotection\']))\r\n{                              $session[\'bufflist\'][\'nprotection\'][\'rounds\'] += 25;\r\n}\r\nelse\r\n{                               $session[\'bufflist\'][\'nprotection\']=array(\"name\"=>\"`5Anfängerglück`0\",\r\n\"rounds\"=>50, \"wearoff\"=>\"Das Anfängerglück verlässt dich.\", \"atkmod\"=>1.5, \"defmod\"=>2, \"roundmsg\"=>\"Anfängerglück schützt dich! \",\"activate\"=>\"offense,defense\");\r\n}\r\n}\r\nitem_delete(\' id=\'.$item[\'id\']);\r\n',0,NULL,0,0,0),('nugget','`^kleines Goldnugget`0',26,'Ein kleines Goldnugget aus der Mine.',800,0,0,0,0,0,'',0,1,0,0,0,0,0,3,0,0,0,1,1,1,0,0,0,2,0,2,0,1,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,1,0,0),('nuggetbig','`^großes Goldnugget`0',26,'Ein großes Goldnugget aus der Mine.',1600,0,0,0,0,0,'',0,1,0,0,0,0,0,3,0,0,0,1,1,1,0,0,0,2,0,2,0,1,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,1,0,0),('oel','Öl',25,'Eine kleine Phiole mit feinstem, goldgelbem Olivenöl.',10,0,0,0,20,0,'',0,0,0,0,1,0,0,0,0,0,0,1,1,1,0,0,0,3,1,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',1,NULL,0,0,1),('ogerzahn','Ogerzahn',3,'Wertloser Plunder',10,0,0,0,0,0,'',0,1,3,0,1,0,0,0,0,0,0,1,1,1,1,0,0,3,0,0,0,1,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,1,0,1),('ohrnssl','Ohrensessel',7,'Ein gemütlicher Sessel mit guter Polsterung und strapazierfähigem Büffelleder. ',2000,1,0,0,0,0,'',0,0,0,0,0,0,0,10,10,1,1,0,1,1,1,0,0,2,1,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift',NULL,NULL,0,0,'',0,NULL,1,0,1),('ollmp','Öllampe',7,'Eine kleine Öllampe die brennend ein warmes, mäßig helles, gelbes Licht ausstrahlt.',0,5,0,0,0,0,'',0,0,0,0,0,0,0,5,5,2,1,0,1,1,0,0,0,2,1,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift',NULL,NULL,0,0,'',0,NULL,1,0,1),('orange','Orange',25,'Eine frische, saftige Orange.',25,0,0,0,60,0,'',0,1,0,0,1,0,0,0,0,0,0,1,1,1,1,0,0,3,1,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',NULL,NULL,NULL,NULL,'kitchen',0,0,'',1,NULL,1,0,1),('osterglocke','`8O`/st`-er`^gl`-o`/ck`8e`0',3,'`/Die gelbe Osterglocke von der Blumenwiese, eine Erinnerung an die seltsame Begegnung mit der Frühlingsgöttin.',100,1,0,0,0,0,'',0,0,0,0,0,0,0,0,0,0,0,0,0,1,0,0,0,2,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,0),('Osterhäschen-Emo','Emohäschen',4,'`y',700,0,0,0,0,0,'',0,0,0,0,0,0,0,10,10,0,0,1,1,1,0,0,0,0,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,1),('Osterhase','`US`uchokohas`Ue',4,'`IEin kleiner, in buntes Papier gewickelter Osterhase aus leckerer Schokolade. Ein kleine Aufmerksamkeit zu Ostern von {name}`I.\r\n',1000,0,0,0,0,0,'',0,0,0,0,0,0,0,10,10,0,1,1,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,0),('osternest','`$O`Ds`Qt`de`qr`^e`-i`/e`yr`8n`pe`gs`Gt`0',4,'`yEin ganzes Nest nur für dich allein mit vielen `-b`^u`qn`Qt `Db`$e`4m`Aa`,l`lt`Le`Xn `xO`rs`Et`Rer`?e`%i`=e`5r`Mn `y`nSo wünscht dir {name} `yfrohe Ostern.',1500,0,0,0,0,0,'',0,0,0,0,0,0,0,10,10,0,0,1,1,1,0,0,0,0,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,1),('pants_01','Leinenhose',30,'Eine einfache Hose aus dickem Leinenstoff, die mit einer Kordel geschnürt wird.',2500,0,0,0,0,1,'Flavius Nadelflink',0,1,0,0,0,0,0,0,0,0,0,1,1,1,0,0,0,0,0,0,0,0,0,0,0,2,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift','_codehook_',NULL,0,0,'if($item[value1]>0) \r\n{\r\n$item[\'value1\']=0;\r\n}',0,NULL,0,0,1),('pants_03','Lederhose',30,'Eine einfache Hose aus weichem Kalbsleder, strapazierfähig und bequem, mit einem Knopf zum verschließen.',0,1,0,0,0,1,'Flavius Nadelflink',0,1,0,0,0,0,0,0,0,0,0,1,1,1,0,0,0,0,0,0,0,0,0,0,0,2,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift','_codehook_',NULL,0,0,'if($item[value1]>0) \r\n{\r\n$item[\'value1\']=0;\r\n}',0,NULL,0,0,1),('pants_04','Beinkleid',30,'Ein Paar lange Beinlinge aus samtartigem Kalbsnubuk mit Lederschnüren zum Befestigen an Gürtel und Knöcheln. Ideal unter Rüstungen oder zum Ausreiten, aber auch im Alltag gut zu tragen.',0,2,0,0,0,2,'Flavius Nadelflink',0,1,0,0,0,0,0,0,0,0,0,1,1,1,0,0,0,0,0,0,0,0,0,0,0,2,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift','_codehook_',NULL,0,0,'if($item[value1]>0) \r\n{\r\n$item[\'value1\']=0;\r\n}',0,NULL,0,0,1),('Parfuemf','`3Parfüm (frisch)',4,'Eau de Parfum in einer 50 ml Flasche. Ein belebend frischer Duft, für den Alltag. {name} hat ihn für dich ausgesucht.',1000,1,0,0,0,0,'',0,0,0,0,0,0,0,10,10,0,1,1,1,1,0,0,0,0,0,0,1,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,1),('ParfuemW','`4Parfüm (würzig)',4,'Eau de Parfum in einer 50 ml Flasche. Ein vollmundig würziger Duft, für heiße Augenblicke. {name} hat ihn für dich ausgesucht.',1000,1,0,0,0,0,'',0,0,0,0,0,0,0,10,10,0,1,1,1,1,0,0,0,0,0,0,1,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,1),('parfums','`rParfüm (süß)',4,'Eau de Parfum in einer 50 ml Flasche. Ein hinreißend süßlicher Duft, für sinnliche Augenblicke. {name} hat ihn für dich ausgesucht.',1000,1,0,0,0,0,'',0,0,0,0,0,0,0,10,10,0,1,1,0,1,0,0,0,0,0,0,1,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,0),('partycap','Feiertagsmütze',10,'Eine Feiertagsmütze',10,0,0,0,0,0,'',0,1,0,0,1,0,0,4,4,0,1,1,0,1,0,0,0,2,0,0,0,0,0,0,0,2,0,1,NULL,'_codehook_',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'global $session;\r\nif($item[value1]>2) item_set(\'id=\'.$item[\'id\'],array(\'value1\'=>\'2\'));\r\n',0,NULL,1,0,0),('Pelzmantel','`TPelzmantel',4,'`7Aus wunderschönem Minotaurusfell. Nichts für Tierschutzvereine, nichts für Greenpeace! Ein todschickes, wertvolles Geschenk von {name}`7.',10000,10,0,0,0,0,'',0,0,0,0,0,0,0,10,10,0,1,1,0,1,0,0,0,2,0,0,1,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,1,0,0),('perl','`fPerle',16,'`f Eine bläulich schimmernde Perle, die du vermutlich am Strand gefunden hast.`0 ',0,6,0,0,0,0,'',0,1,0,0,0,0,0,0,0,0,0,1,0,1,0,0,0,2,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,0),('pestwurz','Pestwurz',24,'Eine krautige Pflanze, deren Extrakte in Form von ätherischen Ölen, angeblich die Pest heilen können.',15,0,0,0,0,0,'',0,1,0,0,1,0,0,0,0,0,0,1,1,1,1,0,0,0,0,3,0,1,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,1),('petcat','Hauskatze',15,'Eine niedliche kleine getigerte Katze, die ihr Revier -deinen Hof- verteidigt. Laufende Kosten: 100 Gold, keine Edelsteine.',3750,12,100,0,0,0,'',0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,42,0,'',0,NULL,0,0,0),('pflaster','`RTrostpflaster`0',4,'Ein kleines Pflaster zum Trost. Du kannst es anschauen oder irgendwo hinkleben - deine Wahl.',5,0,0,0,0,0,'',0,0,0,0,0,0,0,10,10,5,1,1,1,1,1,0,0,3,0,0,0,0,0,3,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'_codehook_',NULL,'_codehook_',NULL,NULL,NULL,0,0,'global $session,$badguy;\r\n\r\nif ($session[\'user\'][\'hitpoints\']>0)\r\n{\r\noutput(\'`&Du klebst dir das Pflaster auf eine Wunde und heilst um ganze 5 Lebenspunkte!\');\r\n$session[\'user\'][\'hitpoints\']+=5;\r\n}\r\nelse\r\n{\r\noutput(\'`&Du klebst dir das Pflaster mitten auf deine Geisterstirn, aber es passiert... nichts...\');\r\n}\r\nitem_delete(\' id=\'.$item[\'id\']);\r\n\r\nif (!$badguy[\'creaturehealth\']>0)\r\n{\r\naddnav(\'Zum Inventar\',$item_hook_info[\'ret\']);\r\n}',0,NULL,1,0,1),('piginapoke','Die Katze im Sack',27,'Ein Leinenbeutel mit unbekanntem Inhalt. Er stinkt fürchterlich.',1,0,0,0,0,0,'',0,1,0,0,1,0,0,1,1,0,0,1,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'_codehook_',NULL,NULL,NULL,NULL,NULL,0,0,'global $item_hook_info,$session,$item;\r\n\r\noutput(\'Du hältst dir mit einer Hand die Nase zu und öffnest mit der anderen diesen widerlich stinkenden Beutel. Und sieheda, es befindet sich \');\r\n$rnd=e_rand(1,7);\r\nif($rnd == 1) {\r\n  output(\'ein totes Eichhörnchen darin!\');\r\n    item_add($session[\'user\'][\'acctid\'],\'squirr\');\r\n}\r\nelseif($rnd == 2) {\r\n  output(\'ein alter Fischkopf darin!\');\r\n    item_add($session[\'user\'][\'acctid\'],\'fshkpf\');\r\n}\r\nelseif($rnd == 3) {\r\n  output(\'ein altes vergammeltes Brot darin!\');\r\n    item_add($session[\'user\'][\'acctid\'],\'dasbrot\');\r\n}\r\nelseif($rnd == 4) {\r\n  output(\'Pferdemist darin!\');\r\n    item_add($session[\'user\'][\'acctid\'],\'thedung\');\r\n}\r\nelseif($rnd == 5) {\r\n  output(\'ein rötlich schimmerndes Pulver darin! Doch oh weh, ein Windstoß verteilt das Pulver auf dir, du wirst die nächsten Tage stinken wie ein Iltis...\');\r\n    item_add($session[\'user\'][\'acctid\'],\'fldgestank\');\r\n}\r\nelseif($rnd == 6) {\r\n  output(\'ein Stück Papier darin! Quer über das Papier ist ein brauner Streifen. Was das wohl sein mag?\');\r\n    item_add($session[\'user\'][\'acctid\'],\'mapw\');\r\n}\r\nelseif($rnd == 7) {\r\n  output(\'ein Aschenbecher und Tabakreste darin!\');\r\n    item_add($session[\'user\'][\'acctid\'],\'vllaschbch\');\r\n}\r\nelse {\r\n  output(\'- NICHTS - darin!\');\r\n    item_add($session[\'user\'][\'acctid\'],\'nichts\');\r\n}\r\n\r\naddnav(\'Zum Inventar\',$item_hook_info[\'ret\']);\r\n\r\nitem_delete(\' id=\'.$item[\'id\']);\r\n\r\nreturn(true);',0,NULL,0,0,0),('pilze','Pilze',25,'Eine bunte Mischung an Waldpilzen, frisch gepflückt. Hoffentlich wusste der Sammler, welche Pilze giftig sind..',25,0,0,0,0,0,'',0,1,0,0,2,0,0,0,0,0,0,1,0,1,0,0,0,3,1,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',1,NULL,0,0,0),('plakettebest','³`r\"I am the best!\"`}³`y-Plakette`0',7,'`yEin großes Schild, das über der Tür an der Außenwand des Hauses angebracht wird. Trägt die Aufschrift ³\"`rI am the best!`}\"³`0',500,0,0,0,0,0,'',0,0,0,0,0,0,0,10,10,1,1,0,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,0),('plschdrn','`2Plüschdrache`0',4,'`REin `@Grüner Drache`R aus Plüsch zum Kuscheln. Der ist von {name}`R und ja sooooooo süß!!',500,0,0,0,0,0,'',0,0,0,0,0,0,0,100,100,0,0,1,1,1,0,0,0,0,0,0,1,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,1),('Plschherz','`%Plüschherz',4,'`rHandgemacht und aus feinstem Plüsch, steckt viel Arbeit in dem kleinen Herz, welches ein Geschenk von {name}`r ist. Für ein ganz besonderes Wesen.',150,0,0,0,0,0,'',0,0,0,0,0,0,0,15,15,0,1,1,0,1,0,0,0,0,0,0,1,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,0),('plumpgold','`^plumpe Sprüche in Gold`0',16,'',1,0,0,0,0,0,'',0,1,0,0,0,0,0,5,30,5,1,1,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,0),('potato','Ein Sack Kartoffeln',25,'Ein kleiner Sack mit frisch geernteten Kartoffeln.',200,0,0,0,15,0,'',0,1,0,0,1,0,0,0,0,0,0,1,1,1,1,0,0,3,1,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',0,0,'',1,NULL,1,0,1),('privb','Besitzurkunde für Privatraum',12,'',1,0,0,0,0,0,'',0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,0),('prive','Zugang zu Privatraum',12,'',1,0,0,0,0,0,'',0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,0),('ptsch','Peitsche',7,'Eine gefährlich aussehende, lederne Peitsche die auf einem dunklen Holzbrett befestigt werden kann. Wird nur für Dekozwecke verkauft.',0,10,0,0,0,0,'',0,0,0,0,0,0,0,5,5,1,1,0,1,1,0,0,0,2,1,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift',NULL,NULL,0,0,'',0,NULL,1,0,1),('pudding','Vanillepudding',25,'Cremiger Vanillepudding. Einfach ein Traum!',1000,0,0,0,55,0,'',0,1,0,0,1,0,0,0,0,0,0,1,1,1,1,0,0,3,1,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'kitchen',0,0,'',1,NULL,1,0,1),('pumpkin','`QKürbis`0',24,'Ein `QKürbis`0 schmeckt lecker als Suppe, kann aber auch ausgehöhlt vor die Tür gestellt werden.',1,0,0,0,0,0,'',0,0,0,0,1,0,0,0,0,0,0,1,1,1,1,0,0,0,1,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,1),('pumpkin_seed','Kürbiskerne',29,'`QKürbiskerne`0 geben jedem Salat die perfekte Note. Alternativ kann man sie auch aushöhlen und vor die Tür stellen ...',1,0,12,0,0,0,'',0,0,0,0,1,0,0,0,0,0,0,1,1,1,1,0,0,0,1,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,1),('qtip','`8Ohrenstäbchen`0',3,'Ein übergroßes Ohrenstäbchen.',1,0,0,0,0,0,'',0,1,0,0,0,0,1,0,0,0,0,0,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,0),('qtschbtt','Quietschendes Bett',7,'Knarrt und quietscht bei jeder Bewegung. Ausschliesslich zum Ausruhen geeignet!',100,0,0,0,0,0,'',0,0,0,0,0,0,0,10,10,0,1,0,1,1,0,0,0,2,1,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift',NULL,NULL,0,0,'',0,NULL,1,0,1),('quizarm','`5Adamantium-Harnisch`0',10,'Härter als alles andere in dieser Welt ist das Material, aus dem diese Rüstung besteht, nur von den letzten geheimen Meistern der Schmiedekunst formbar!',22000,0,32,0,0,0,'',0,1,0,0,1,0,0,0,0,0,0,1,0,1,0,0,0,2,0,0,0,0,0,0,0,2,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,1,0,0),('quizram','Die Ramius-Tour',13,'Einmal Ramius und zurück!\r\nMit 5 Extra-Grabkämpfen und 100 Gefallen für die sichere Rückkehr!\r\n(Fotografieren verboten!)',500,0,1,0,0,0,'',0,1,0,0,0,0,0,0,0,0,0,1,1,1,1,0,0,0,0,0,0,0,0,1,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'_codehook_',NULL,'_codehook_',NULL,NULL,NULL,0,0,'global $session,$badguy;\r\nunset($session[\'user\'][\'badguy\']);\r\n\r\n$session[\'user\'][\'hitpoints\']=0;\r\n$session[\'user\'][\'deathpower\']+=100;\r\n$session[\'user\'][\'gravefights\']+=5;\r\naddnews($session[\'user\'][\'name\'].\'`# macht jetzt Urlaub im Totenreich.\');\r\n\r\nitem_delete(\' id=\'.$item[\'id\']);\r\nredirect(\"shades.php\");\r\n',0,NULL,0,0,1),('quizweap','`4rote Höllenklinge`0',8,'Nur die Hölle selbst kann eine so schreckliche Waffe hervorbringen.\r\nMan sagt, die Wunden die sie reißt würden nie wieder richtig verheilen!',20000,0,30,0,0,0,'',0,1,0,0,1,0,0,0,0,0,0,1,0,1,0,0,0,2,0,0,0,0,0,0,0,1,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,0),('ramiuszpt','`NZep`(ter `)des `(Ram`Nius',4,'Ramius Zepter. Ob einem damit die Toten gehorchen?',1000,1,0,0,0,0,'',0,0,0,0,0,0,0,10,10,0,1,1,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,0),('ranfcrst','`^Kristall vom tiefsten Punkt der Mine`0',13,'Eine wahre Kostbarkeit, deren Inbesitznahme mit unsagbaren Gefahren und Risiken verbunden war.',0,10,0,0,0,0,'',0,1,0,0,0,0,0,1,0,0,1,1,0,1,1,0,0,2,0,2,0,1,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,1,1,0),('raserei','`^Raserei`0',14,'Dies ist mehr eine Kampftechnik, als es mit Magie zu tun hat. Dein Angriffswert steigt, deine Verteidigung leidet allerdings unter dieser blinden Raserei. Kann 3 Tage lang 2x eingesetzt werden.',800,0,2,2,3,0,'',0,1,0,0,1,0,0,0,0,0,0,0,1,1,1,0,0,0,0,1,0,0,0,3,0,0,0,1,'zauber',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',NULL,NULL,NULL,11,0,'',0,NULL,0,0,1),('rberplak','`TR`4äuberplakette`0',7,'Ein großes Schild, das über der Tür an der Aussenwand des Hauses angebracht wird. Trägt die Aufschrift \"`2Alle, die hier wohnen wollen müssen Männer mit Bärten sein.`0\"',500,0,0,0,0,0,'',0,0,0,0,0,0,0,10,10,0,1,0,1,1,0,0,0,2,1,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift',NULL,NULL,0,0,'',0,NULL,1,0,1),('rchnbrtt','Rechenbrett',7,'Ein kleines, aber sehr nützliches Hilfsmittel, zur Verwaltung des Hausschatzes. Wird geliefert mit dem Sicherheitsschloss \"E-Z-Lock\" - bewährter Schutz gegen lange Finger.',75000,50,0,0,0,0,'',0,0,0,0,0,0,0,1,1,0,1,0,0,1,0,0,0,2,1,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,'rechenbrett','rechenbrett',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,1,0,0),('regenende','`&Ende des `$R`Qe`qg`^en`@b`jo`wg`9e`Mn`vs',4,'Eine kleine Skulptur, die einen Teil des Regenbogens zeigt.',1000,0,0,0,0,0,'',0,0,0,0,0,0,0,10,10,0,1,1,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,0),('regenstb','`ORe`Ige`/nbo`Ggen`wsta`vub',4,'Er glitzert schön und ist fein anzusehen.',500,0,0,0,0,0,'',0,0,0,0,0,0,0,10,10,0,1,1,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,0),('reis','Ein Beutel Reis',25,'In dem Beutel befinden sich viele kleine harte längliche Körner, die in Wasser getaucht langsam weich und essbar werden.',50,0,0,0,0,0,'',0,1,0,0,1,0,0,0,0,0,0,1,0,1,0,0,0,3,1,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',1,NULL,0,0,0),('rglwnd','`TS`tchrankwand`0',7,'Eine riesige Schrankwand aus feinstem Ebenholz gefertigt. Mit unzähligen geräumigen Fächern und reich verzierten Türen ist dieses Möbelstück eine Zierde für jeden Haushalt.',30000,20,0,0,0,0,'',0,0,0,0,0,0,0,1,1,1,1,0,1,1,0,0,0,2,1,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift',NULL,NULL,0,0,'',0,NULL,1,0,1),('Ring','`7Platin Ring',4,'Ein schlichter Platin Ring, ideal für Männer. Auch für Frauen geeignet, die sich jenen an einer Kette um den Hals hängen wollen. Der ist von {name}.',4000,0,0,0,0,0,'',0,0,0,0,0,0,0,10,15,0,1,1,1,1,0,0,0,2,0,0,1,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,1,0,1),('robe_01','Alltagskittel',30,'Ein robuster, knapp knielanger Kittel aus Loden von grober Schafswolle, gehalten von einem Strick aus geflochtenem Flachswerg. Durch Färbung kann dem rohen Stoff etwas seiner Einfachheit genommen werden.',500,0,0,0,0,0,'Flavius Nadelflink',0,1,0,0,0,0,0,0,0,0,0,1,1,1,0,0,0,0,0,0,0,0,0,0,0,2,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift','_codehook_',NULL,0,0,'if($item[value1]>0) \r\n{\r\n$item[\'value1\']=0;\r\n}',0,NULL,0,0,1),('robe_02','Sonntagskittel',30,'Ein schlichter, knapp knielanger Leinenkittel, der an der Schulter von hübsch polierten Holzknöpfen geschlossen wird. Ein gemustertes Tuch um die Hüften sorgt für besseren Sitz und ein wenig Dekoration.',1000,0,0,0,0,0,'Flavius Nadelflink',0,1,0,0,0,0,0,0,0,0,0,1,1,1,0,0,0,0,0,0,0,0,0,0,0,2,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift','_codehook_',NULL,0,0,'if($item[value1]>0) \r\n{\r\n$item[\'value1\']=0;\r\n}',0,NULL,0,0,1),('robe_03','Sommertunika',30,'Ein knielanges, ärmelloses Gewand aus leichter Baumwolle. Der Schnitt ist schlicht, aber die Nähte sind mit feinen Zierfäden verschönert, die auch auf dem Rücken eine dekorative ornamentale Stickerei bilden.',2500,0,0,0,0,1,'Flavius Nadelflink',0,1,0,0,0,0,0,0,0,0,0,1,1,1,0,0,0,0,0,0,0,0,0,0,0,2,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift','_codehook_',NULL,0,0,'if($item[value1]>0) \r\n{\r\n$item[\'value1\']=0;\r\n}',0,NULL,0,0,1),('robe_04','Wollkutte',30,'Eine bescheidene, doch angenehm zu tragende Kluft mit Kapuze und weiten Ärmelaufschlägen. Um die Taille kann sie von einem Strick aus Flachs ein wenig in Form gebracht werden.',3000,0,0,0,0,1,'Flavius Nadelflink',0,1,0,0,0,0,0,0,0,0,0,1,1,1,0,0,0,0,0,0,0,0,0,0,0,2,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift','_codehook_',NULL,0,0,'if($item[value1]>0) \r\n{\r\n$item[\'value1\']=0;\r\n}',0,NULL,0,0,1),('robe_05','Schreibertalar',30,'Ein wadenlanger, baumwollener Überwurf von mantelartigem Schnitt mit weiten Ärmeln und verborgenen Taschen, in denen Schreibzeug und kleinere Bücher gut transportiert werden können. Die Säume sind mit breitem Samtbesatz verziert.',0,3,0,0,0,0,'Flavius Nadelflink',0,1,0,0,0,0,0,0,0,0,0,1,1,1,0,0,0,0,0,0,0,0,0,0,0,2,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift','_codehook_',NULL,0,0,'if($item[value1]>0) \r\n{\r\n$item[\'value1\']=0;\r\n}',0,NULL,0,0,1),('robe_06','Albe der Tempeldiener',30,'Ein knöchellanges Leinenkleid, gehalten von einem schmalen Gürtelband. Der Saum der leicht ausgestellten Ärmel ist mit spiritistischen Zeichen bestickt und den kurzen Stehkragen ziert dezent das Symbol der verehrten Gottheit.',0,3,0,0,0,1,'Flavius Nadelflink',0,1,0,0,0,0,0,0,0,0,0,1,1,1,0,0,0,0,0,0,0,0,0,0,0,2,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift','_codehook_',NULL,0,0,'if($item[value1]>0) \r\n{\r\n$item[\'value1\']=0;\r\n}',0,NULL,0,0,1),('robe_07','Druidenkotta',30,'Ein bodenlanges Gewand aus einfachem Wollstoff mit breitem, rundem Ausschnitt und weiten Ärmeln. Der Überwurf mit Kapuze, floralen Saumstickereien und dezent eingewebten Runenzeichen, verleiht der Kluft eine zugleich würdevolle und bescheidene Ausstrahlung.',0,3,0,0,0,1,'Flavius Nadelflink',0,1,0,0,0,0,0,0,0,0,0,1,1,1,0,0,0,0,0,0,0,0,0,0,0,2,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift','_codehook_',NULL,0,0,'if($item[value1]>0) \r\n{\r\n$item[\'value1\']=0;\r\n}',0,NULL,0,0,1),('robe_09','Magierrobe',30,'Eine extravagant geschnittene Robe wie sie gern von Magiern oder Ritenmeistern getragen wird. Der schwere, von glänzenden Edelmetallfäden durchwirkte Brokatstoff wirkt etwas unbequem, aber auch sehr kostbar. Die Säume zeigen arkane Symbole.',0,15,0,0,0,1,'Flavius Nadelflink',0,1,0,0,0,0,0,0,0,0,0,1,1,1,0,0,0,0,0,0,0,0,0,0,0,2,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift','_codehook_',NULL,0,0,'if($item[value1]>0) \r\n{\r\n$item[\'value1\']=0;\r\n}',0,NULL,0,0,1),('rohei','Hühnerei',25,'Ein rohes Ei von durchschnittlicher Größe',15,0,0,0,90,0,'',0,1,4,0,1,0,0,1,1,0,0,1,1,1,0,0,0,3,0,0,0,1,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'kitchen',0,0,'',1,NULL,1,0,1),('rohstahl','Rohstahl',26,'Ein wirklich schwerer Block Rohstahl.',1500,0,0,0,0,0,'',0,1,0,0,1,0,0,0,0,0,0,1,1,1,1,0,0,0,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,1),('rose','`$Rose',4,'`REine wohlduftende rote Rose. Die ist von {name}`R.',10,0,0,0,0,0,'',0,0,0,0,0,0,0,10,10,0,0,1,1,1,0,0,0,0,0,0,1,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,1),('rosekey','`TRosenschlüssel`0',3,'Ein kleiner, seltsamer, alter Schlüssel voll Rost und Dreck auf dem `%eine Rose`0 eingeritzt ist.',123,0,0,0,0,0,'',0,1,0,0,1,0,0,1,1,0,0,1,1,1,1,0,0,3,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,1,0,1),('rstdummy','Rüstungs-Dummy',10,'',1,0,0,0,0,0,'',0,1,0,0,1,0,0,0,0,0,0,1,0,1,0,0,0,2,0,0,0,0,0,0,0,2,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,1,0,0),('rstngl','Rostiger Nagel',3,'Wertloser Plunder',1,0,0,0,0,0,'',0,1,6,0,1,0,0,0,0,0,0,1,1,1,1,0,0,3,0,0,0,1,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,1,0,1),('rtsmtvorh','`4rote `&Samtvorhänge`0',7,'Lange und schwere Vorhänge aus `4rotem `&Samt.',5000,10,0,0,0,0,'',0,0,0,0,0,0,0,10,10,0,1,0,1,1,0,0,0,2,1,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift',NULL,NULL,0,0,'',0,NULL,1,0,1),('rubinring',' `4Rubinring',4,'Ein Ring, welcher mit einem funkelndem roten Rubin geschmückt ist.',2500,1,0,0,0,0,'',0,0,0,0,0,0,0,10,10,0,1,1,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,0),('runenstein','Runenstein',3,'Ein mystischer Stein auf dem Druiden geheimnisvolle Symbole verewigten.',1000,0,0,0,0,0,'',0,0,0,0,1,0,0,5,5,0,1,1,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,0),('runschkstn','Runenvitrine',7,'Eine große Vitrine aus Eichenholz und durchsichtigem Glas. Runen kommen hier besonders zur Geltung. So macht Angeben Spass!',15000,50,0,0,0,0,'',0,0,0,0,0,0,0,10,10,0,1,0,0,1,0,0,0,2,1,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,'runen','runen','runen',NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,1,0,0),('r_algiz','Algiz - Rune',19,'',1800,1,1,15,0,0,'',0,1,0,0,0,0,0,0,0,0,0,1,1,1,0,0,0,2,0,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'runen',NULL,NULL,0,0,'',0,NULL,0,0,1),('r_amrup_4','Runen Amorsteigerung + 4',20,'Ergebnis von Runenmixen zum steigern der Verteidigung',1,0,0,0,0,0,'',0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,0),('r_ansuz','Ansuz - Rune',19,'',400,0,1,4,0,0,'',0,1,0,0,0,0,0,0,0,0,0,1,1,1,0,0,0,2,0,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'runen',NULL,NULL,0,0,'',0,NULL,0,0,1),('r_berkana','Berkana - Rune',19,'',2600,2,1,18,0,0,'',0,1,0,0,0,0,0,0,0,0,0,1,1,1,0,0,0,2,0,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'runen',NULL,NULL,0,0,'',0,NULL,0,0,1),('r_cmup_5','Runen Charmesteigerung +5',20,'Steigerung von charme um 5 Punkte!',1,0,0,0,0,0,'',0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,0),('r_dagaz','Dagaz - Rune',19,'',6000,6,1,23,0,0,'',0,1,0,0,0,0,0,0,0,0,0,1,1,1,0,0,0,2,0,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'runen',NULL,NULL,0,0,'',0,NULL,0,0,1),('r_dummy','Unbekannte Rune',19,'Eine Steinplatte, die mit einem Zeichen verziert ist, das du nicht kennst.',1,0,0,0,0,0,'',0,1,5,0,0,0,0,0,0,0,0,1,1,1,0,0,0,2,0,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'runen',NULL,'runen',NULL,NULL,0,0,'',0,NULL,0,0,1),('r_ehwaz','Ehwaz - Rune',19,'',2900,2,1,19,0,0,'',0,1,0,0,0,0,0,0,0,0,0,1,1,1,0,0,0,2,0,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'runen',NULL,NULL,0,0,'',0,NULL,0,0,1),('r_eiwaz','Eiwaz - Rune',19,'',1400,1,1,13,0,0,'',0,1,0,0,0,0,0,0,0,0,0,1,1,1,0,0,0,2,0,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'runen',NULL,NULL,0,0,'',0,NULL,0,0,1),('r_fdr_hgn','`~Feder `Sdes `~Hugin',19,'`tEine `~pechschwarze `t sehr seltene Feder des Raben Hugin.',1000,10,0,0,0,0,'',0,1,0,0,0,0,0,0,0,0,0,0,0,1,0,0,0,2,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,0),('r_fdr_mnn','`~Feder `Sdes `~Munin',19,'`tEine `~pechschwarze `tsehr seltene Feder des Raben Munin.',1000,10,0,0,0,0,'',0,1,0,0,0,0,0,0,0,0,0,0,0,1,0,0,0,2,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,0),('r_fehu','Fehu - Rune',19,'',100,0,1,1,0,0,'',0,1,0,0,0,0,0,0,0,0,0,1,1,1,0,0,0,2,0,0,0,0,0,1,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'runen',NULL,NULL,41,0,'',0,NULL,0,0,1),('r_gebo','Gebo - Rune',19,'',700,0,1,7,0,0,'',0,1,0,0,0,0,0,0,0,0,0,1,1,1,0,0,0,2,0,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'runen',NULL,NULL,0,0,'',0,NULL,0,0,1),('r_hagalaz','Hagalaz - Rune',19,'',900,0,1,9,0,0,'',0,1,0,0,0,0,0,0,0,0,0,1,1,1,0,0,0,2,0,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'runen',NULL,NULL,0,0,'',0,NULL,0,0,1),('r_ingwaz','Ingwaz - Rune',19,'',5000,5,1,22,0,0,'',0,1,0,0,0,0,0,0,0,0,0,1,1,1,0,0,0,2,0,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'runen',NULL,NULL,0,0,'',0,NULL,0,0,1),('r_isa','Isa - Rune',19,'',1000,1,1,11,0,0,'',0,1,0,0,0,0,0,0,0,0,0,1,1,1,0,0,0,2,0,0,0,0,0,3,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'runen',NULL,NULL,40,0,'',0,NULL,0,0,1),('r_jera','Jera - Rune',19,'',1200,1,1,12,0,0,'',0,1,0,0,0,0,0,0,0,0,0,1,1,1,0,0,0,2,0,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'runen',NULL,NULL,0,0,'',0,NULL,0,0,1),('r_kenaz','Kenaz - Rune',19,'',600,0,1,6,0,0,'',0,1,0,0,0,0,0,0,0,0,0,1,1,1,0,0,0,2,0,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'runen',NULL,NULL,0,0,'',0,NULL,0,0,1),('r_laguz','Laguz - Rune',19,'',4000,4,1,21,0,0,'',0,1,0,0,0,0,0,0,0,0,0,1,1,1,0,0,0,2,0,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'runen',NULL,NULL,0,0,'',0,NULL,0,0,1),('r_lpup_100','Runen LP steigerung +100',20,'',1,0,0,0,0,0,'',0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,0),('r_lpup_3','Runen LP steigerung +3',20,'',1,0,0,0,0,0,'',0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,0),('r_mannaz','Mannaz - Rune',19,'',3300,3,1,20,0,0,'',0,1,0,0,0,0,0,0,0,0,0,1,1,1,0,0,0,2,0,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'runen',NULL,NULL,0,0,'',0,NULL,0,0,1),('r_mix_amr4','RZWE Amorup +4',20,'Zwischenergebnis für Amorup +4',1,0,0,0,0,0,'RZWE',0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,0),('r_mix_nr1','RZWE schnitter',20,'',1,0,0,0,0,0,'RZWE',0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,0),('r_mix_wpn4','RZWE Weaponup +4',20,'',1,0,0,0,0,0,'RZWE',0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,0),('r_naudiz','Naudiz - Rune',19,'',1000,0,1,10,0,0,'',0,1,0,0,0,0,0,0,0,0,0,1,1,1,0,0,0,2,0,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'runen',NULL,NULL,0,0,'',0,NULL,0,0,1),('r_othala','Othala - Rune',19,'',8000,8,1,24,0,0,'',0,1,0,0,0,0,0,0,0,0,0,1,1,1,0,0,0,2,0,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'runen',NULL,NULL,0,0,'',0,NULL,0,0,1),('r_pethro','Pethro - Rune',19,'',1600,1,1,14,0,0,'',0,1,0,0,0,0,0,0,0,0,0,1,1,1,0,0,0,2,0,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'runen',NULL,NULL,0,0,'',0,NULL,0,0,1),('r_raidho','Raidho - Rune',19,'',500,0,1,5,0,0,'',0,1,0,0,0,0,0,0,0,0,0,1,1,1,0,0,0,2,0,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'runen',NULL,NULL,0,0,'',0,NULL,0,0,1),('r_sowilo','Sowilo - Rune',19,'',2000,2,1,16,0,0,'',0,1,0,0,0,0,0,0,0,0,0,1,1,1,0,0,0,2,0,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'runen',NULL,NULL,0,0,'',0,NULL,0,0,1),('r_teiwaz','Teiwaz - Rune',19,'',2300,2,1,17,0,0,'',0,1,0,0,0,0,0,0,0,0,0,1,1,1,0,0,0,2,0,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'runen',NULL,NULL,0,0,'',0,NULL,0,0,1),('r_thurisaz','Thurisaz - Rune',19,'',300,0,1,3,0,0,'',0,1,0,0,0,0,0,0,0,0,0,1,1,1,0,0,0,2,0,0,0,0,0,3,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'runen',NULL,NULL,39,0,'',0,NULL,0,0,1),('r_uruz','Uruz - Rune',19,'',200,0,1,2,0,0,'',0,1,0,0,0,0,0,0,0,0,0,1,1,1,0,0,0,2,0,0,0,0,0,3,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'runen',NULL,NULL,38,0,'',0,NULL,0,0,1),('r_wpnup_4','Runen Waffensteigerung + 4',20,'Ergebnis von Runenmixen zum steigern der Waffe',1,0,0,0,0,0,'',0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,0),('r_wunjo','Wunjo - Rune',19,'',800,0,1,8,0,0,'',0,1,0,0,0,0,0,0,0,0,0,1,1,1,0,0,0,2,0,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'runen',NULL,NULL,0,0,'',0,NULL,0,0,1),('sackstei','Sack Steine',3,'Seltsame, magische Steine, die stets eine wohlige und angenehme Wärme ausstrahlen.',0,2,0,0,0,0,'',0,1,1,0,1,0,0,1,1,0,0,1,1,1,1,0,0,3,0,0,0,1,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,1,0,1),('salpeter','Salpeter',24,'Eine kristalline, rotbraune salzig riechende Substanz.',25,0,0,0,0,0,'',0,1,0,0,1,0,0,0,0,0,0,1,1,1,1,0,0,0,0,3,0,1,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,1),('salzsaeure','Salzsäure',24,'Vorsicht! Ätzend!',50,0,0,0,0,0,'',0,1,0,0,1,0,0,0,0,0,0,1,1,1,1,0,0,0,0,3,0,1,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,1),('Samtkis','`4Samtkissen (rot)',4,'Ein rotes Samtkissen, mit goldenen Stickereien. Macht sich gut auf dem Ledersofa oder auch auf dem Himmelbett. Ein Geschenk von {name}.',1500,0,0,0,0,0,'',0,0,0,0,0,0,0,10,10,0,1,1,1,1,0,0,0,0,0,0,1,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,1),('Samtkisbl','`!Samtkissen (blau)',4,'Ein blaues Samtkissen, mit silbernen Stickereien. Macht sich gut auf dem Ledersofa oder auch auf dem Himmelbett. Ein Geschenk von {name}.',1500,0,0,0,0,0,'',0,0,0,0,0,0,0,10,10,0,1,1,1,1,0,0,0,0,0,0,1,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,1),('sandsack','`^S`ta`Tnd`tsac`^k`0',7,'',1,0,0,0,0,0,'',0,0,0,0,0,0,0,1,1,1,0,1,1,1,1,0,0,2,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,'sack','sack',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,1,0,1),('schattenpilz','`(Schattenpilz',25,'`)Die schmackhaften Pilze aus dem `(Schattenwald',1,0,0,0,0,0,'',0,1,0,0,0,0,0,0,0,0,0,1,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,0),('schklpfd','Schaukelpferd',7,'Ein hölzernes Pferd, groß genug, dass die lieben kleinen lange Spaß daran haben werden. Kopf, Mähne und Hufe sind liebevoll geschnitzt, unter den Beinen sind zwei lange Holzschienen angebracht, sodass das Tier zum Schaukeln gebracht werden kann.',7500,15,0,0,0,0,'',0,0,0,0,0,0,0,3,3,1,1,0,1,1,0,0,0,2,1,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift',NULL,NULL,0,0,'',0,NULL,1,0,1),('schkstn','Schaukasten',7,'Eine große Vitrine aus Eichenholz und durchsichtigem Glas. So macht Angeben Spass!',15000,50,0,0,0,0,'',0,0,0,0,0,0,0,10,10,0,1,0,0,1,0,0,0,2,1,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,'schaukasten','schaukasten','schaukasten',NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,1,0,0),('schldwch','Schild: Hier wache ich!',7,'Es zeigt einen grimmig blickenden Ork.',50,0,0,0,0,0,'',0,0,0,0,0,0,0,10,10,1,1,0,1,1,0,0,0,2,1,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift',NULL,NULL,0,0,'',0,NULL,1,0,1),('schmckkst','Schmuckkästchen',7,'Groß genug um Einiges zu verstauen. Bietet mit der patentierten \"Finger dran-Finger ab\"-Sicherung optimalen Schutz gegen Diebstahl',10000,50,0,0,0,0,'',0,0,0,0,0,0,0,1,1,0,1,0,0,1,0,0,0,2,1,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,'schmuck','schmuck',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,1,6,0),('schmino','`&Schmuse-Minotaurus`0',4,'`&Eine handgefertigte, harmlose Variante des Minotaurus. Mit echtem Büffelfell und Hörnern aus feinstem Plüsch. Den hat dir {name}`& geschenkt.',1000,0,0,0,0,0,'',0,0,0,0,0,0,0,10,10,0,1,1,1,1,0,0,0,2,0,0,1,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,1,0,1),('schml','Schemel',7,'Ein kleines, schmuckloses Sitzmöbel, das leicht in jede Ecke passt. ',100,1,0,0,0,0,'',0,0,0,0,0,0,0,5,5,2,1,0,1,1,0,0,0,2,1,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift',NULL,NULL,0,0,'',0,NULL,1,0,1),('schneekug','`sS`&chn`eee`&kuge`sl`0',7,'Sie zeigt den Dorfbrunnen, darunter ist eingraviert `4Brunnenmonster - ich war dabei!`0',200,50,0,0,0,0,'',0,0,0,0,0,0,0,10,10,0,1,0,1,1,0,0,0,2,1,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift',NULL,NULL,0,0,'',0,NULL,1,0,1),('schntzhlze','Schnitzerei aus Holz',3,'Ein sauberes Stück unbekannter Herkunft.',0,10,0,0,0,0,'',0,1,1,0,1,0,0,0,0,0,0,1,0,1,0,0,0,3,0,0,0,1,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,1,0,0),('schntzhlzg','Schnitzerei aus Holz',3,'Nutzloses Dings.',20,0,0,0,0,0,'',0,1,6,0,1,0,0,0,0,0,0,1,0,1,0,0,0,3,0,0,0,1,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,1,0,0),('schnupftabak','`4Ein Döschen Schnupftabak 30g',3,'`4Ein Döschen voller Schnupftabak. Der Tabak ist so fein gestossen, dass man ihn definitiv nicht mehr rauchen kann, ohne an einer Staublunge zu verrecken. Aber zum Schnupfen eignet er sich hervorragend.',10,0,0,0,0,0,'',0,1,4,4,0,4,0,0,0,0,0,1,1,1,1,0,0,3,1,0,0,1,0,0,0,0,4,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,1,0),('Schokoherz','`SSc`Tho`Ykola`;de`:n`4h`Aerz',4,'`yEin kleines, in glänzendes, `Arotes`y Papier eingepacktes `4H`Aer`,z`y aus feinster `SSc`Tho`Ykola`;de`y. Eine süße Aufmerksamkeit zum Valentinstag von {name}`y.\r\n',1000,0,0,0,0,0,'',0,0,0,0,0,0,0,10,10,0,1,1,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,0),('schokoweihnachtsmann','`uSchoko `UWeihnachtsmann `T(Zartbitter)',25,'`uDiese authentische Nachbildung eines `UWeihnachtsmanns `uist beinahe lebengroß und aus feinster `TZartbitter-Schokolade`u gegossen. Kinder und alle Leckermäuler würden dafür morden!',1,0,0,0,0,0,'',0,1,0,0,0,0,0,2,2,0,1,1,1,1,0,0,0,3,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',1,NULL,0,0,1),('Schreibst','Schreiber-Set',4,'Eine kleine Truhe mit Pergamenten, verschiedenen Federn und königsblauer, sowie schwarzer Tinte. Das perfekte Geschenk für alle Schreibwütigen. Von {name}.',3000,0,0,0,0,0,'',0,0,0,0,0,0,0,10,10,0,1,1,1,1,0,0,0,0,0,0,1,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,1),('schtzaura','`%Schutzaura`0',10,'Eine mächtige, langanhaltende Schutzaura, die dir das Goldene Ei geliefert hat!',25000,0,25,0,0,0,'',0,1,0,0,1,0,0,0,0,0,0,0,0,1,0,0,0,0,0,0,0,0,0,0,0,2,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,0),('schuhrgl','Schuhregal',7,'Eine kleine hölzerne Vorrichtung, die etwa 10 Paar Schuhe und Stiefel aufnehmen kann. Ein massiver Unterboden fängt Schlamm und Feuchtigkeit auf.',500,0,0,0,0,0,'',0,0,0,0,0,0,0,10,10,0,1,0,1,1,0,0,0,2,1,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift',NULL,NULL,0,0,'',0,NULL,1,0,1),('schwbrtt','Schwarzes Brett',7,'Ein kleines, schwarzes Brett, wie es auch in der Schenke zu finden ist. Es fasst nicht so viele Nachrichten, passt dafür aber in jede Baracke. Ein Muss für Schwarzseher oder Anhänger des schwarzen Humors!',8000,40,0,0,0,0,'',0,0,0,0,0,0,0,1,1,0,0,1,0,1,0,0,0,2,1,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,'brett','brett','brett',NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,1,0,0),('schwefel','Schwefel',24,'Ein kleiner, gelblicher Kristall und Rückstand von vulkanischen Gasen. Vorsicht! Kann Hautreizungen hervorrufen.',30,0,0,0,0,0,'',0,1,0,0,1,0,0,0,0,0,0,1,1,1,1,0,0,0,0,3,0,1,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,1),('scklstuhl','`6S`7ch`6auk`7elst`6uhl`0',7,'Ein äußerst robustes und bequemes Exemplar, welches selbst den schwersten Ork in den Schlaf wiegen kann.',12000,8,0,0,0,0,'',0,0,0,0,0,0,0,10,10,1,1,0,1,1,0,0,0,2,1,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift',NULL,NULL,0,0,'',0,NULL,1,0,1),('scumm_logo','`IDas Logo der S.C.U.M.M. Bar',3,'`IDas Logo der S.C.U.M.M. Bar. Ist bestimmt einiges Wert...',1,0,0,0,0,0,'',0,0,0,0,0,0,0,0,0,0,0,0,0,1,0,0,0,2,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,0),('seelensplitter','`dSe`qele`^nspl`qitt`der',4,'Ein Splitter der heller leuchtet als die Sonne.',1000,1,0,0,0,0,'',0,0,0,0,0,0,0,10,10,0,1,1,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,0),('seelenstein','Seelenstein',3,'Hunderte von Seelen wohnen in ihm, die dich nun beschützen.',1000,0,0,0,0,0,'',0,0,0,0,1,0,0,5,5,0,1,1,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,0),('seidenrobe','Spinnenseidenkokon',3,'Ein makellos schöner Kokon einer seltenen Spinnenart. Sicher wertvoll, doch leider kannst du damit nichts anfangen.',0,3,0,0,0,0,'',0,1,1,0,1,0,0,0,0,0,0,1,1,1,1,0,0,3,0,0,0,1,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,1,0,1),('sferplak','Säuferplakette',7,'Ein großes Schild, das über der Tür an der Aussenwand des Hauses angebracht wird. Trägt die Aufschrift \"`@Bier und Wein, das schmeckt fein.`0\"',500,0,0,0,0,0,'',0,0,0,0,0,0,0,10,10,0,1,0,1,1,0,0,0,2,1,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift',NULL,NULL,0,0,'',0,NULL,1,0,1),('sfkssn','Sofakissen',7,'Ein kleines, recht hartes Kissen, wie man es auf dem Sofa finden kann.',100,1,0,0,0,0,'',0,0,0,0,0,0,0,5,5,1,1,0,1,1,0,0,0,2,1,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift',NULL,NULL,0,0,'',0,NULL,1,0,1),('shirt_01','Kurzärmelige Bluse',30,'Eine einfache, schmucklose Leinenbluse, die am Ausschnitt geschnürt werden kann. Die kurzen Ärmel enden knapp an der Schulter in einem ebenfalls schnürbaren Bund.',1500,0,0,0,1,1,'Flavius Nadelflink',0,1,0,0,0,0,0,0,0,0,0,1,1,1,0,0,0,0,0,0,0,0,0,0,0,2,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift','_codehook_',NULL,0,0,'if($item[value1]>0) \r\n{\r\n$item[\'value1\']=0;\r\n}',0,NULL,0,0,1),('shirt_02','Langärmelige Bluse',30,'Eine einfache, schmucklose Leinenbluse, die am Ausschnitt geschnürt werden kann. Die langen Ärmel sind am Ellbogen ein wenig gerafft und zu den Handgelenken hin wieder etwas weiter ausgestellt.',2000,0,0,0,1,1,'Flavius Nadelflink',0,1,0,0,0,0,0,0,0,0,0,1,1,1,0,0,0,0,0,0,0,0,0,0,0,2,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift','_codehook_',NULL,0,0,'if($item[value1]>0) \r\n{\r\n$item[\'value1\']=0;\r\n}',0,NULL,0,0,1),('shirt_06','Leinenhemd',30,'Ein einfaches Hemd aus etwas leichterem Leinenstoff, das mit Kordeln an Ausschnitt und Ärmeln geschnürt werden kann.',1500,0,0,0,0,2,'Flavius Nadelflink',0,1,0,0,0,0,0,0,0,0,0,1,1,1,0,0,0,0,0,0,0,0,0,0,0,2,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift','_codehook_',NULL,0,0,'if($item[value1]>0) \r\n{\r\n$item[\'value1\']=0;\r\n}',0,NULL,0,0,1),('shirt_07','Lederweste',30,'Eine einfache Weste aus gegerbtem Rindsleder mit Wolfsfellbesatz und zwei kleinen Taschen. Wird an der Vorderseite mit einigen Knöpfen aus Bein geschlossen.',0,1,0,0,0,1,'Flavius Nadelflink',0,1,0,0,0,0,0,0,0,0,0,1,1,1,0,0,0,0,0,0,0,0,0,0,0,2,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift','_codehook_',NULL,0,0,'if($item[value1]>0) \r\n{\r\n$item[\'value1\']=0;\r\n}',0,NULL,0,0,1),('shirt_08','Gambeson',30,'Ein dick gefüttertes, knielanges Gewand aus mehreren Lagen gesteppter Woll- und Leinenstoffe. Unter der Rüstung getragen dämmt es Kettenkleid und Panzer, kann aber auch allein als Rüstschutz dienen.',0,2,0,0,0,2,'Flavius Nadelflink',0,1,0,0,0,0,0,0,0,0,0,1,1,1,0,0,0,0,0,0,0,0,0,0,0,2,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift','_codehook_',NULL,0,0,'if($item[value1]>0) \r\n{\r\n$item[\'value1\']=0;\r\n}',0,NULL,0,0,1),('shrubbery','`JEin Gebüsch',3,'`JEin Gebüsch des fahrenden Händlers `pNIE`asbert Nevernoh. `JEs ist schön buschig, dicht und trägt komische Früchte und einige Raupen.',1,20,0,0,0,0,'',0,1,0,0,0,0,0,0,0,0,1,1,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,1,0),('silberdis','Silberdistel',24,'Eine krautige, stängellose Pflanze mit stachelig geformter, silberfarbener Blüte. Wird in südlichen Landen zur Herstellung ätherischer Öle verwendet.',10,0,0,0,0,0,'',0,1,0,0,1,0,0,0,0,0,0,1,1,1,1,0,0,0,0,3,0,1,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,1),('silbkreuz','Silbernes Kreuz',3,'Ein Anhänger für eine Kette. Es schützt vor Vampiren.',1000,0,0,0,0,0,'',0,0,0,0,1,0,0,5,5,0,1,1,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,0),('skelett','`fZ`&ucker`fs`&kelet`ft',4,'`&Aus Zucker geformte Knochen, welche kunstfertig durch Bindfäden zusammengehalten werden und so wirklich ein ganzes Skelett formen. Es kann sowohl als makaberes Windspiel sowie als süße Leckerei herhalten.`nHappy Halloween wünscht dir so `0{name}`&.\r\n',1000,0,0,0,0,0,'',0,0,0,0,0,0,0,10,10,0,1,1,0,1,0,0,0,0,0,0,1,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,0),('skirt_01','Kurzer Rock',30,'Ein etwa knielanger, glatt fallender Rock aus Leinen für die wärmeren Tage. Mit einem um die Hüfte geschlungenen Schnürband kann man ihm eine kleine Zierde verleihen.',1500,0,0,0,1,0,'Flavius Nadelflink',0,1,0,0,0,0,0,0,0,0,0,1,1,1,0,0,0,0,0,0,0,0,0,0,0,2,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift','_codehook_',NULL,0,0,'if($item[value1]>0) \r\n{\r\n$item[\'value1\']=0;\r\n}',0,NULL,0,0,1),('skirt_02','Langer Rock',30,'Ein einfach geschnittener Rock aus glatt gewebtem Barchent, der in leichten Wellen bis auf die Knöchel fällt und an der Hüfte geschnürt wird.',3000,0,0,0,1,0,'Flavius Nadelflink',0,1,0,0,0,0,0,0,0,0,0,1,1,1,0,0,0,0,0,0,0,0,0,0,0,2,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift','_codehook_',NULL,0,0,'if($item[value1]>0) \r\n{\r\n$item[\'value1\']=0;\r\n}',0,NULL,0,0,1),('skirt_04','Samtrock',30,'Ein bodenlanger Rock aus feinstem, schwerem Samt, der im Licht geheimnisvoll schimmert.',0,7,0,0,1,0,'Flavius Nadelflink',0,1,0,0,0,0,0,0,0,0,0,1,1,1,0,0,0,0,0,0,0,0,0,0,0,2,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift','_codehook_',NULL,0,0,'if($item[value1]>0) \r\n{\r\n$item[\'value1\']=0;\r\n}',0,NULL,0,0,1),('slbthrn','`7silberner `sThron`0',7,'Thron aus Eichenholz mit Schrauben und Beschlägen aus Silber.',5000,20,0,0,0,0,'',0,0,0,0,0,0,0,10,10,1,1,0,1,1,0,0,0,2,1,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift',NULL,NULL,0,0,'',0,NULL,1,0,1),('sltschdl','Seltsamer Schädel',4,'Du kannst nicht beurteilen, ob der echt ist. Aber aus seinen Augen dringt ein seltsames rotes Schimmern.',3000,0,0,0,0,0,'',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,'schaedel',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,0),('socken','`6Paar Socken',4,'Ein Paar warme Wollsocken für den Mann von Welt, den so schnell nichts kratzt. Bei diesem Geschenk handelt es sich um ein Unikat, das wirklich nur zu besonderen Anlässen verschenkt werden sollte; etwa Namenstagen, Geburtstagen, Feiertagen und Tagen, die eine ungerade Quersumme besitzen. {date} ist zum Beispiel so ein besonderer Anlass. Oder zumindest das, was {name} darunter versteht.',100,0,1,0,0,0,'`@neu!',0,0,0,0,0,0,0,10,15,0,1,1,1,1,0,0,0,0,0,0,1,1,0,0,0,2,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'_codehook_',NULL,0,0,'if($item_hook_info[\'op\'] == \'ausr\') {\r\n\r\n  if($item[\'special_info\'] == \'`6Stinkend.\') {\r\n    output(\'`$`bPfui!`b`n`n`&Du willst doch wohl diese stinkenden Wollsocken nicht schon wieder anziehen?! Wasch sie erstmal.. (oder lass sie waschen, am besten von der Person, die dir die Dinger geschenkt hat!)`n`n\r\nAlternativ könntest du natürlich auch etwas Lustiges daraus basteln, wenn du über Zugang zu einem Schmelztiegel verfügst..\');\r\n    $item_hook_info[\'hookstop\'] = true;\r\n  }\r\nelse {\r\n\r\n  item_set(\'id=\'.$hook_item[\'id\'],array(\'special_info\'=>\'`6Stinkend.\'));\r\n\r\n  }\r\n\r\n}',0,NULL,0,0,1),('spgl','`7S`spiege`7l`0',7,'Wunderschöner, großer Spiegel aus einfachem Glas.',5000,10,0,0,0,0,'',0,0,0,0,0,0,0,1,1,0,1,0,0,1,0,0,0,2,1,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,'spiegel','spiegel',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,1,0,0),('splzgkst','`qSpiel`yzeug`qkiste`0',7,'Gefüllt mit sämtlichen Spielzeugen aller Art.',100,5,0,0,0,0,'',0,0,0,0,0,0,0,10,10,1,1,0,1,1,0,0,0,2,1,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift',NULL,NULL,0,0,'',0,NULL,1,0,1),('spnnrd','Spinnrad',7,'Ein hölzernes Spinnrad. Hier kann man lange Winterabende damit verbringen, die Wolle zu Garn zu spinnen.',6000,12,0,0,0,0,'',0,0,0,0,0,0,0,1,1,0,1,0,0,1,0,0,0,2,1,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,'spinnrad','spinnrad',NULL,NULL,NULL,NULL,NULL,'send_as_gift',NULL,NULL,0,0,'',0,NULL,1,0,0),('spnnwb','`&S`)pin`7nwebe`en`0',7,'Machen sich gut in Ecken und an der Decke. Geben jedem Wohnraum eine gemütlich gruselige Atmosphäre.',5,0,0,0,0,0,'',0,0,0,0,0,0,0,10,10,1,1,0,1,1,0,0,0,2,1,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift',NULL,NULL,0,0,'',0,NULL,1,0,1),('spstfl','Speisetafel',7,'10 Schritt lange Speisetafel für 22 Personen aus Eichenholz.',15000,20,0,0,0,0,'',0,0,0,0,0,0,0,10,10,1,1,0,1,1,0,0,0,2,1,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift',NULL,NULL,0,0,'',0,NULL,1,0,1),('squirr','`tAusgestopftes Eichhörnchen`0',3,'Auf einem Brett aus glattem Kiefernholz. In großen Lettern steht geschrieben: `4Vorsicht! Nicht mit Erdnüssen füttern!`0 ',500,0,0,0,0,0,'',0,1,1,0,1,0,0,100,100,100,1,1,1,1,1,0,0,1,0,0,0,1,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,1),('squirra','`tKiller-Eichhörnchen`0',21,'Mit irrem Blick und viel Schaum vor dem Mund. Das packst du am besten nur mit entsprechender Schutzkleidung an!',700,0,3,3,0,2,'',0,1,0,0,0,0,0,100,100,0,1,1,1,1,1,0,0,0,0,0,0,1,0,3,0,0,0,1,'','','','','','','','','','','','','_codehook_','','','',0,0,'global $badguy,$session;\r\n\r\nif ($badguy[\'creaturename\']==\'\'){\r\noutput(\"`tDu packst \".$item[\'name\'].\" mit spitzen Fingern im Nacken und schleuderst es `&\".$badguy[\'name\'].\" `tentgegen.`n\");}\r\nelse{output(\"`tDu packst \".$item[\'name\'].\" mit spitzen Fingern im Nacken und schleuderst es `&\".$badguy[\'creaturename\'].\" `tentgegen.`n\");}\r\n\r\n$damage=round(20+e_rand(1,30)+e_rand(1,$session[\'user\'][\'dragonkills\']*0.5));\r\n$damage+=($item[hvalue]*10);\r\n\r\noutput(\"`tKratzend und beißend verursacht \".$item[\'name\'].\" `4\".$damage.\"`t Schaden.\");\r\n\r\n$badguy[\'creaturehealth\']-=$damage;\r\n\r\nif ($item[\'value1\'] <= 1)\r\n{\r\n$res = item_tpl_list_get( \'tpl_name=\"`&Frustriertes `tKiller-Eichhörnchen`0\" LIMIT 1\' );\r\n\r\nif( db_num_rows($res) )\r\n{\r\n$itemnew = db_fetch_assoc($res);\r\n$itemnew[\'tpl_hvalue\']=$item[\'hvalue\'];\r\n$itemnew[\'tpl_hvalue2\']=$item[\'hvalue2\'];\r\n$itemnew[\'tpl_special_info\']=$item[\'special_info\'];\r\nif (strpos($item[\'name\'],\"(`tKill\"))\r\n{\r\n$oldname=substr($item[\'name\'],0,strrpos($item[\'name\'],\"(`tKill\"));\r\ntrim($oldname);\r\n$itemnew[\'tpl_name\']=$oldname.\"(`&Frustriertes `tKiller-Eichhörnchen`&)\";\r\n}\r\nitem_add($session[\'user\'][\'acctid\'],0,$itemnew);\r\nitem_delete( \' id=\'.$item[\'id\']);\r\n}\r\n}',0,'',0,0,1),('squirrafru','`&Frustriertes `tKiller-Eichhörnchen`0',21,'Sichtlich beleidigt über die Art wie du es ständig durch die Gegend wirfst, wird es erstmal gar nichts mehr tun! Höchstens dir in den Finger beissen...',350,0,0,2,0,0,'',0,1,0,0,0,0,0,100,100,0,1,1,1,1,1,0,0,0,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'_codehook_',NULL,NULL,NULL,NULL,NULL,0,0,'global $session,$item_hook_info;\r\n\r\noutput(\"`&Du versuchst das Eichhörchen zu streicheln, doch es beisst dich!`nDu verlierst ein paar Lebenspunkte.`0\");\r\n\r\n$session[\'user\'][\'hitpoints\']*=0.95;\r\n\r\naddnav(\'Zum Inventar\',$item_hook_info[\'ret\']);',0,NULL,0,0,1),('squirrb','`4Todes`thörnchen`0',21,'Prägnant an diesem Eichhörnchen ist neben dem Schaum vor dem Mund noch das spitze Horn auf seiner Stirn. Das tut sicher arg weh!',1000,0,2,2,0,4,'',0,1,0,0,0,0,0,100,100,0,1,1,1,1,1,0,0,0,0,0,0,0,0,3,0,0,0,1,'','','','','','','','','','','','','_codehook_','','','',0,0,'global $badguy,$session;\r\n\r\noutput(\"`tDu packst dein Killereichhörnchen mit spitzen Fingern im Nacken und wirfst es in hohem Bogen auf `&\".$badguy[\'creaturename\'].\" `t.`n\");\r\n\r\n$damage=round(50+e_rand(1,$session[\'user\'][\'dragonkills\']*0.75));\r\n$damage+=($item[hvalue]*10);\r\n\r\noutput(\"`tDas Hörnchen schlägt ein und verursacht `4\".$damage.\"`t Schaden.\");\r\n\r\n$badguy[\'creaturehealth\']-=$damage;\r\n\r\nif ($item[\'value1\'] <= 1)\r\n{\r\n$res = item_tpl_list_get( \'tpl_name=\"`&Frustriertes `4Todes`thörnchen`0\" LIMIT 1\' );\r\n\r\nif( db_num_rows($res) )\r\n{\r\n$itemnew = db_fetch_assoc($res);                                                $itemnew[\'tpl_hvalue\']=$item[\'hvalue\'];                         $itemnew[\'tpl_hvalue2\']=$item[\'hvalue2\'];\r\n$itemnew[\'tpl_special_info\']=$item[\'special_info\'];\r\nif (strpos($item[\'name\'],\"(`4Tod\"))\r\n{                            $oldname=substr($item[\'name\'],0,strrpos($item[\'name\'],\"(`4Tod\"));\r\ntrim($oldname);                            $itemnew[\'tpl_name\']=$oldname.\"(`&Frustriertes `4Todes`thörnchen`&)\";\r\n}\r\nitem_add($session[\'user\'][\'acctid\'],0,$itemnew);\r\nitem_delete( \' id=\'.$item[\'id\']);\r\n}\r\n}',0,'',0,0,1),('squirrbfru','`&Frustriertes `4Todes`thörnchen`0',21,'Sichtlich beleidigt über die Art wie du es ständig durch die Gegend wirfst, wird es erstmal gar nichts mehr tun! Höchstens dir in den Finger beissen...',500,0,0,2,0,0,'',0,1,0,0,0,0,0,100,100,0,1,1,1,1,1,0,0,0,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'_codehook_',NULL,NULL,NULL,NULL,NULL,0,0,'global $session,$item_hook_info;\r\n\r\noutput(\"`&Du versuchst das Eichhörchen zu streicheln, doch es beisst dich!`nDu verlierst ein paar Lebenspunkte.`0\");\r\n\r\n$session[\'user\'][\'hitpoints\']*=0.90;\r\n\r\naddnav(\'Zum Inventar\',$item_hook_info[\'ret\']);',0,NULL,0,0,1),('squirrc','`8Vampir`thörnchen`0',21,'Seine Augen leuchten sogar im Dunkeln und seine Zähne sind noch spitzer und länger als sonst!',900,0,3,3,0,5,'',0,1,0,0,0,0,0,100,100,0,1,1,1,1,1,0,0,0,0,0,0,1,0,3,0,0,0,1,'','','','','','','','','','','','','_codehook_','','','',0,0,'global $badguy,$session;\r\n\r\noutput(\"`tDu packst dein Vampirhörnchen mit spitzen Fingern im Nacken und schleuderst es `&\".$badguy[\'creaturename\'].\" `tentgegen.`n\");\r\n\r\n$damage=round(20+e_rand(1,20)+e_rand(1,$session[\'user\'][\'dragonkills\']*0.4));\r\n$damage+=($item[hvalue]*10);\r\n$gain=round($damage*0.35);\r\n\r\noutput(\"`tBeissend verursacht es `4\".$damage.\"`t Schaden.`n`@Davon überträgt es `4\".$gain.\"`@ Punkte auf dich!\");\r\n\r\n$badguy[\'creaturehealth\']-=$damage;\r\n$session[\'user\'][\'hitpoints\']+=$gain;\r\n\r\nif ($item[\'value1\'] <= 1)\r\n{\r\n$res = item_tpl_list_get( \'tpl_name=\"`&Frustriertes `8Vampir`thörnchen`0\" LIMIT 1\' );\r\n\r\nif( db_num_rows($res) )\r\n{\r\n$itemnew = db_fetch_assoc($res);                                                $itemnew[\'tpl_hvalue\']=$item[\'hvalue\'];                         $itemnew[\'tpl_hvalue2\']=$item[\'hvalue2\'];\r\n$itemnew[\'tpl_special_info\']=$item[\'special_info\'];\r\nif (strpos($item[\'name\'],\"(`8Vam\"))\r\n{                            $oldname=substr($item[\'name\'],0,strrpos($item[\'name\'],\"(`8Vam\"));\r\ntrim($oldname);                            $itemnew[\'tpl_name\']=$oldname.\"(`&Frustriertes `8Vampir`thörnchen`&)\";\r\n}\r\nitem_add($session[\'user\'][\'acctid\'],0,$itemnew);\r\nitem_delete( \' id=\'.$item[\'id\']);\r\n}\r\n}',0,'',0,0,1),('squirrcfru','`&Frustriertes `8Vampir`thörnchen`0',21,'Sichtlich beleidigt über die Art wie du es ständig durch die Gegend wirfst, wird es erstmal gar nichts mehr tun! Höchstens dir in den Finger beissen...',450,0,0,3,0,0,'',0,1,0,0,0,0,0,100,100,0,1,1,1,1,1,0,0,0,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'_codehook_',NULL,NULL,NULL,NULL,NULL,0,0,'global $session,$item_hook_info;\r\n\r\noutput(\"`&Du versuchst das Eichhörchen zu streicheln, doch es beisst dich!`nDu verlierst ein paar Lebenspunkte.`0\");\r\n\r\n$session[\'user\'][\'hitpoints\']*=0.95;\r\n\r\naddnav(\'Zum Inventar\',$item_hook_info[\'ret\']);',0,NULL,0,0,1),('squirrd','`&Baby`thörnchen`0',21,'Wie könnte man meinen, dass so etwas putzig pelziges einmal so gemein und gefährlich werden kann?',100,0,1,3,0,0,'',0,1,0,0,0,0,0,100,100,0,1,1,1,1,1,0,0,0,0,0,0,1,0,3,0,0,0,1,'','','','','','','','','','','','','_codehook_','','','',0,0,'global $badguy,$session;\r\n\r\noutput(\"`tDu holst aus um dein `&Baby`thörnchen `&\".$badguy[\'creaturename\'].\" `tentgegen zu werfen, doch es krallt sich ängstlich an deiner Hand fest und klettert deinen Arm hinauf.`n\");\r\n\r\nif ($item[\'value1\'] <= 1)\r\n{\r\n$res = item_tpl_list_get( \'tpl_name=\"`&Frustriertes `&Baby`thörnchen`0\" LIMIT 1\' );\r\n\r\nif( db_num_rows($res) )\r\n{\r\n$itemnew = db_fetch_assoc($res);                                                $itemnew[\'hvalue\']=$item[\'hvalue\'];                         $itemnew[\'hvalue2\']=$item[\'hvalue2\'];\r\n$itemnew[\'tpl_special_info\']=$item[\'special_info\'];\r\nif (strpos($item[\'name\'],\"(`&Baby\"))\r\n{                            $oldname=substr($item[\'name\'],0,strrpos($item[\'name\'],\"(`&Baby\"));\r\ntrim($oldname);                            $itemnew[\'tpl_name\']=$oldname.\"(`&Frustriertes `&Baby`thörnchen`0`&)\";\r\n}\r\nitem_add($session[\'user\'][\'acctid\'],0,$itemnew);\r\nitem_delete( \' id=\'.$item[\'id\']);\r\n}\r\n}',0,'',0,0,1),('squirrdfru','`&Frustriertes `&Baby`thörnchen`0',21,'Sichtlich beleidigt über die Art wie du es ständig durch die Gegend wirfst, wird es erstmal gar nichts mehr tun! Höchstens dir in den Finger beissen...',50,0,0,1,0,0,'',0,1,0,0,0,0,0,100,100,0,1,1,1,1,1,0,0,0,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'_codehook_',NULL,NULL,NULL,NULL,NULL,0,0,'global $session,$item_hook_info;\r\n\r\noutput(\"`&Du versuchst das Eichhörchen zu streicheln, doch es beisst dich!`nDu verlierst ein paar Lebenspunkte.`0\");\r\n\r\n$session[\'user\'][\'hitpoints\']*=0.95;\r\n\r\naddnav(\'Zum Inventar\',$item_hook_info[\'ret\']);',0,NULL,0,0,1),('squirre','`TWer`thörnchen`0',21,'Ein kleiner Fellball mit scharfen Zähnen und unglaublich viel Appetit. Wo der sich festbeißt wächst nichts mehr!',900,0,3,4,0,3,'',0,1,0,0,0,0,0,100,100,0,1,1,1,1,1,0,0,0,0,0,0,1,0,3,0,0,0,1,'','','','','','','','','','','','','_codehook_','','','',0,0,'global $badguy,$session;\r\n\r\noutput(\"`tDu packst dein Werhörnchen wie ein Wollknäuel und wirfst es `&\".$badguy[\'creaturename\'].\" `tzu.`n\");\r\n\r\n$damage=round(50+e_rand(1,$session[\'user\'][\'dragonkills\']*0.6));\r\n$damage+=($item[hvalue]*10);\r\n\r\noutput(\"`tEs verbeißt sich an deinem Gegner und macht `4\".$damage.\"`t Schaden, bevor es zu dir zurück kommt.\");\r\n\r\n$badguy[\'creaturehealth\']-=$damage;\r\n\r\nif ($item[\'value1\'] <= 1)\r\n{\r\n$itemnew = item_get_tpl(\' tpl_id=\"squirrefru\" \');\r\n\r\nif( false !== $itemnew )\r\n{\r\n$itemnew[\'tpl_hvalue\']=$item[\'hvalue\'];                         $itemnew[\'tpl_hvalue2\']=$item[\'hvalue2\'];\r\n$itemnew[\'tpl_special_info\']=$item[\'special_info\'];\r\nif (strpos($item[\'name\'],\"(`TWer\"))\r\n{                            $oldname=substr($item[\'name\'],0,strrpos($item[\'name\'],\"(`TWer\"));\r\ntrim($oldname);                          $itemnew[\'tpl_name\']=$oldname.\"(`&Frustriertes `TWer`thörnchen`&)\";\r\n}\r\nitem_add($session[\'user\'][\'acctid\'],0,$itemnew);\r\nitem_delete( \' id=\'.$item[\'id\']);\r\n}\r\n}',0,'',0,0,1),('squirrefru','`&Frustriertes `TWer`thörnchen`0',21,'Sichtlich beleidigt über die Art wie du es ständig durch die Gegend wirfst, wird es erstmal gar nichts mehr tun! Höchstens dir in den Finger beissen...',450,0,0,3,0,0,'',0,1,0,0,0,0,0,100,100,0,1,1,1,1,1,0,0,0,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'_codehook_',NULL,NULL,NULL,NULL,NULL,0,0,'global $session,$item_hook_info;\r\n\r\noutput(\"`&Du versuchst das Eichhörchen zu streicheln, doch es beisst dich!`nDu verlierst ein paar Lebenspunkte.`0\");\r\n\r\n$session[\'user\'][\'hitpoints\']*=0.75;\r\n\r\naddnav(\'Zum Inventar\',$item_hook_info[\'ret\']);',0,NULL,0,0,1),('squirrf','`%P`!a`@r`^t`4y`thörnchen`0',21,'Eine wahre Stimmungskanone! Dieses Eichhörnchen lebt für die Unterhaltung und kann sogar tanzen! Perfekt geeignet um mürrische Hörnchen aufzumuntern.',1100,0,5,5,0,0,'',0,1,0,0,0,0,0,100,100,0,1,1,1,1,1,0,0,0,0,0,0,0,0,3,0,0,0,1,'','','','','','','','','','','','','_codehook_','','','',0,0,'global $badguy,$session;\r\n\r\noutput(\"`tDu packst dein Partyhörnchen und schleuderst es `&\".$badguy[\'creaturename\'].\" `tentgegen.`n\");\r\n\r\noutput(\"`tEs führt einen lockeren Tanz auf und denkt gar nicht daran deinen Gegner zu verletzen.\");\r\n\r\nif ($item[\'value1\'] <= 1)\r\n{\r\n$itemnew = item_get_tpl( \'tpl_name=\"`&Frustriertes `%P`!a`@r`^t`4y`thörnchen`0\" \' );\r\n\r\nif( false !== $itemnew )\r\n{\r\n$itemnew[\'tpl_hvalue\']=$item[\'hvalue\'];                         $itemnew[\'tpl_hvalue2\']=$item[\'hvalue2\'];\r\n$itemnew[\'tpl_special_info\']=$item[\'special_info\'];\r\nif (strpos($item[\'name\'],\"(`%P`!a\"))\r\n{                            $oldname=substr($item[\'name\'],0,strrpos($item[\'name\'],\"(`%P`!a\"));\r\ntrim($oldname);                          $itemnew[\'tpl_name\']=$oldname.\"(`&Frustriertes `%P`!a`@r`^t`4y`thörnchen`0`&)\";\r\n}\r\nitem_add($session[\'user\'][\'acctid\'],0,$itemnew);\r\nitem_delete( \' id=\'.$item[\'id\']);\r\n}\r\n}',0,'',0,0,1),('squirrfarm','Eichhörnchenzuchtfarm',18,'Ein aus mehreren Käfigen und Höhlen bestehendes Areal mit vielen Rückzugsmöglichkeiten. Hier fühlt sich jedes Eichhörnchen wohl!',100000,100,0,0,0,0,'',0,1,0,0,0,0,0,1,0,0,1,1,0,1,0,0,0,2,1,0,0,0,0,0,0,0,0,0,NULL,'squirrelfarm',NULL,NULL,NULL,NULL,'squirrelfarm','squirrelfarm',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,1,0,0),('squirrffru','`&Frustriertes `%P`!a`@r`^t`4y`thörnchen`0',21,'Ein Leben voller Spaß und Feiern hat dein Partyhörnchen endgültig in den Alkoholismus und die Drogensucht getrieben. Tut mir leid, der kleine Nager ist wohl hinüber!',550,0,0,2,0,0,'',0,1,0,0,0,0,0,100,100,0,1,1,1,1,1,0,0,0,0,0,0,1,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'_codehook_',NULL,NULL,NULL,NULL,NULL,0,0,'global $session,$item_hook_info;\r\n\r\noutput(\"`&Du kraulst das Eichhörchen sanft am Bauch, doch es ist zu sehr weggetreten um etwas zu bemerken!`n`0\");\r\n\r\naddnav(\'Zum Inventar\',$item_hook_info[\'ret\']);',0,NULL,0,0,1),('squirrg','`&T`)e`&r`)r`&o`)r`thörnchen`0',21,'Stilecht mit Turban, zotteligem Bart und fanatischem Blick.',2000,0,5,5,0,5,'',0,1,0,0,0,0,0,0,0,0,1,1,0,1,0,0,0,0,0,0,0,0,0,3,0,0,0,1,'terror','terror','','','','','','','','','terror','','_codehook_','','','',0,0,'global $badguy,$session;\r\n\r\noutput(\"`tDu packst dein Terrorhörnchen und hältst es `&\".$badguy[\'creaturename\'].\" `tentgegen.`n\");\r\n\r\noutput(\"`tDa es, wie alle subversiven Elemente, die direkte Konfrontation meidet und lieber aus dem Verborgenen agiert, tut es absolut nichts.\");\r\n\r\nif ($item[\'value1\'] <= 1)\r\n{\r\n$itemnew = item_get_tpl( \'tpl_name=\"`&Frustriertes `&T`)e`&r`)r`&o`)r`thörnchen`0\" \' );\r\n\r\nif( false !== $itemnew )\r\n{\r\n$itemnew[\'tpl_hvalue\']=$item[\'hvalue\'];                         $itemnew[\'tpl_hvalue2\']=$item[\'hvalue2\'];\r\n$itemnew[\'tpl_special_info\']=$item[\'special_info\'];\r\nif (strpos($item[\'name\'],\"(`&T`)e`&r\"))\r\n{                            $oldname=substr($item[\'name\'],0,strrpos($item[\'name\'],\"(`&T`)e`&r\"));\r\ntrim($oldname);                          $itemnew[\'tpl_name\']=$oldname.\"(`&Frustriertes `&T`)e`&r`)r`&o`)r`thörnchen`&)\";\r\n}\r\nitem_add($session[\'user\'][\'acctid\'],0,$itemnew);\r\nitem_delete( \' id=\'.$item[\'id\']);\r\n}\r\n}',0,'',0,0,0),('squirrgfru','`&Frustriertes `&T`)e`&r`)r`&o`)r`thörnchen`0',21,'Nun schaut es dich sehr, sehr böse an!',1000,0,0,2,0,0,'',0,1,0,0,0,0,0,0,0,0,1,1,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'_codehook_',NULL,NULL,NULL,NULL,NULL,0,0,'global $session,$item_hook_info;\r\n\r\noutput(\"`&Du versuchst dem Terrorhörchen Anweisungen für seinen nächsten Auftrag zu geben, doch es beisst dich!`nDu verlierst ein paar Lebenspunkte.`0\");\r\n\r\n$session[\'user\'][\'hitpoints\']*=0.95;\r\n\r\naddnav(\'Zum Inventar\',$item_hook_info[\'ret\']);',0,NULL,0,0,0),('squirrh','`&^Engelshörnchen^`0',21,'Es ist nicht sichtbar, aber doch da. Von den Göttern wurde es auf die Erde geschickt um seine lebenden Artgenossen auf den rechten Weg zu bringen.',100,0,5,5,0,0,'Engel',0,1,0,0,0,0,0,2,2,0,0,1,1,1,1,0,0,0,0,0,0,0,0,1,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'_codehook_',NULL,NULL,NULL,0,0,'global $session;\r\n\r\nif($session[\'user\'][\'hitpoints\']>$session[\'user\'][\'maxhitpoints\']>>2)\r\n{\r\n  output(\'Du rufst dein `&Engelshörnchen`t, doch nichts passiert. Offenbar bist du nicht in der Situation, wo du einen Schutzengel brauchst.`n\');\r\n}\r\nelse\r\n{\r\n  output(\"`tIn letzter Verzweiflung rufst du dein `&Engelshörnchen`t und spürst neue Lebensenergie in dir.`n\");\r\n  $session[\'user\'][\'hitpoints\']=$session[\'user\'][\'maxhitpoints\']>>2;\r\n}\r\n',0,NULL,0,0,1),('ss_androme','Stein des Sirrah',28,'Der matt glänzende und sehr glatte  Stein fühlt sich dunkel und kühl an. Doch in seinem Innern meinst du ein Glimmen zu spüren, wie von einem weit entfernten Stern - Sirrah!`n\r\nDieser Stein verwehrt dir einen Waldkampf pro Tag.',1,0,0,0,0,0,'',0,1,0,0,0,0,1,0,0,0,0,0,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,'_codehook_',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'global $session;\r\n$session[\'user\'][\'turns\']--;',0,NULL,0,0,0),('ss_aquila','Stein des Altair',28,'Der matt glänzende und sehr glatte  Stein fühlt sich dunkel und kühl an. Doch in seinem Innern meinst du ein Glimmen zu spüren, wie von einem weit entfernten Stern - Altair!`n\r\nDieser Stein gewährt dir einen zusätzlichen Waldkampf pro Tag.',1,0,0,0,0,0,'',0,1,0,0,0,0,1,0,0,0,0,0,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,'_codehook_',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'global $session;\r\n$session[\'user\'][\'turns\']++;',0,NULL,0,0,0),('ss_atair','Stein des Albireo',28,'Der matt glänzende und sehr glatte  Stein fühlt sich dunkel und kühl an. Doch in seinem Innern meinst du ein Glimmen zu spüren, wie von einem weit entfernten Stern - Albireo!`n\r\nDieser Stein gewährt dir einen zusätzlichen Waldkampf pro Tag.',1,0,0,0,0,0,'',0,1,0,0,0,0,1,0,0,0,0,0,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,'_codehook_',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'global $session;\r\n$session[\'user\'][\'turns\']++;',0,NULL,0,0,0),('ss_aurigae','Stein der Capella',28,'Der matt glänzende und sehr glatte  Stein ist dunkel und kühl. Doch in seinem Innern meinst du ein glimmen zu spüren, wie von einem weit entfernten Stern - Capella!`n\r\nDieser Stein verwehrt dir einen Waldkampf pro Tag.',1,0,0,0,0,0,'',0,1,0,0,0,0,1,0,0,0,0,0,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,'_codehook_',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'global $session;\r\n$session[\'user\'][\'turns\']--;',0,NULL,0,0,0),('ss_aurora','Stein des Prokyon',28,'Der matt glänzende und sehr glatte  Stein fühlt sich dunkel und kühl an. Doch in seinem Innern meinst du ein Glimmen zu spüren, wie von einem weit entfernten Stern - Prokyon!`n\r\nDieser Stein gewährt dir einen zusätzlichen Waldkampf pro Tag.',1,0,0,0,0,0,'',0,1,0,0,0,0,1,0,0,0,0,0,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,'_codehook_',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'global $session;\r\n$session[\'user\'][\'turns\']++;',0,NULL,0,0,0),('ss_boreali','Stein des Regulus',28,'Der matt glänzende und sehr glatte  Stein ist dunkel und kühl. Doch in seinem Innern meinst du ein glimmen zu spüren, wie von einem weit entfernten Stern - Regulus!`n\r\nDieser Stein verwehrt dir einen Waldkampf pro Tag.',1,0,0,0,0,0,'',0,1,0,0,0,0,1,0,0,0,0,0,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,'_codehook_',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'global $session;\r\n$session[\'user\'][\'turns\']--;',0,NULL,0,0,0),('ss_centaur','Stein des Antares',28,'Der matt glänzende und sehr glatte  Stein fühlt sich dunkel und kühl an. Doch in seinem Innern meinst du ein Glimmen zu spüren, wie von einem weit entfernten Stern - Antares!`n\r\nDieser Stein gewährt dir einen Waldkampf pro Tag.',1,0,0,0,0,0,'',0,1,0,0,0,0,1,0,0,0,0,0,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,'_codehook_',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'global $session;\r\n$session[\'user\'][\'turns\']++;',0,NULL,0,0,0),('ss_cetus','Stein der Mira',28,'Der matt glänzende und sehr glatte  Stein fühlt sich dunkel und kühl an. Doch in seinem Innern meinst du ein Glimmen zu spüren, wie von einem weit entfernten Stern - Mira!`n\r\nDieser Stein gewährt dir einen zusätzlichen Waldkampf pro Tag.',1,0,0,0,0,0,'',0,1,0,0,0,0,1,0,0,0,0,0,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,'_codehook_',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'global $session;\r\n$session[\'user\'][\'turns\']++;',0,NULL,0,0,0),('ss_draco','Stein des Thuban',28,'Der matt glänzende und sehr glatte  Stein fühlt sich dunkel und kühl an. Doch in seinem Innern meinst du ein Glimmen zu spüren, wie von einem weit entfernten Stern - Thuban!`n\r\nDieser Stein verwehrt dir einen Waldkampf pro Tag.',1,0,0,0,0,0,'',0,1,0,0,0,0,1,0,0,0,0,0,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,'_codehook_',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'global $session;\r\n$session[\'user\'][\'turns\']--;',0,NULL,0,0,0),('ss_furioni','Stein des Sirius',28,'Der matt glänzende und sehr glatte  Stein fühlt sich dunkel und kühl an. Doch in seinem Innern meinst du ein Glimmen zu spüren, wie von einem weit entfernten Stern - Sirius!`n\r\nDieser Stein gewährt dir einen Waldkampf pro Tag.',1,0,0,0,0,0,'',0,1,0,0,0,0,1,0,0,0,0,0,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,'_codehook_',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'global $session;\r\n$session[\'user\'][\'turns\']++;',0,NULL,0,0,0),('ss_gemini','Stein des Castor',28,'Der matt glänzende und sehr glatte  Stein fühlt sich dunkel und kühl an. Doch in seinem Innern meinst du ein Glimmen zu spüren, wie von einem weit entfernten Stern - Castor!`n\r\nDieser Stein verwehrt dir einen Waldkampf pro Tag.',1,0,0,0,0,0,'',0,1,0,0,0,0,1,0,0,0,0,0,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,'_codehook_',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'global $session;\r\n$session[\'user\'][\'turns\']--;',0,NULL,0,0,0),('ss_gorgone','Stein des Acubens',28,'Der matt glänzende und sehr glatte  Stein fühlt sich dunkel und kühl an. Doch in seinem Innern meinst du ein Glimmen zu spüren, wie von einem weit entfernten Stern - Acubens!`n\r\nDieser Stein gewährt dir einen Waldkampf pro Tag.',1,0,0,0,0,0,'',0,1,0,0,0,0,1,0,0,0,0,0,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,'_codehook_',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'global $session;\r\n$session[\'user\'][\'turns\']++;',0,NULL,0,0,0),('ss_hydra','Stein des Alphard',28,'Der matt glänzende und sehr glatte  Stein fühlt sich dunkel und kühl an. Doch in seinem Innern meinst du ein Glimmen zu spüren, wie von einem weit entfernten Stern - Alphard!`n\r\nDieser Stein verwehrt dir einen Waldkampf pro Tag.',1,0,0,0,0,0,'',0,1,0,0,0,0,1,0,0,0,0,0,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,'_codehook_',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'global $session;\r\n$session[\'user\'][\'turns\']--;',0,NULL,0,0,0),('ss_lynx','Stein der Schedir',28,'Der matt glänzende und sehr glatte  Stein fühlt sich dunkel und kühl an. Doch in seinem Innern meinst du ein Glimmen zu spüren, wie von einem weit entfernten Stern - Schedir!`n\r\nDieser Stein verwehrt dir einen Waldkampf pro Tag.',1,0,0,0,0,0,'',0,1,0,0,0,0,1,0,0,0,0,0,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,'_codehook_',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'global $session;\r\n$session[\'user\'][\'turns\']--;',0,NULL,0,0,0),('ss_monocer','Stein der Lukida',28,'Der matt glänzende und sehr glatte  Stein fühlt sich dunkel und kühl an. Doch in seinem Innern meinst du ein Glimmen zu spüren, wie von einem weit entfernten Stern - Lukida!`n\r\nDieser Stein gewährt dir einen Waldkampf pro Tag.',1,0,0,0,0,0,'',0,1,0,0,0,0,1,0,0,0,0,0,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,'_codehook_',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'global $session;\r\n$session[\'user\'][\'turns\']++;',0,NULL,0,0,0),('ss_orion','Stein der Beteigeuze',28,'Der matt glänzende und sehr glatte  Stein fühlt sich dunkel und kühl an. Doch in seinem Innern meinst du ein Glimmen zu spüren, wie von einem weit entfernten Stern - Beteigeuze!`n\r\nDieser Stein verwehrt dir einen Waldkampf pro Tag.',1,0,0,0,0,0,'',0,1,0,0,0,0,1,0,0,0,0,0,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,'_codehook_',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'global $session;\r\n$session[\'user\'][\'turns\']--;',0,NULL,0,0,0),('ss_pisces','Stein der Alrischa',28,'Der matt glänzende und sehr glatte  Stein fühlt sich dunkel und kühl an. Doch in seinem Innern meinst du ein Glimmen zu spüren, wie von einem weit entfernten Stern - Alrischa!`n\r\nDieser Stein gewährt dir einen zusätzlichen Waldkampf pro Tag.',1,0,0,0,0,0,'',0,1,0,0,0,0,1,0,0,0,0,0,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,'_codehook_',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'global $session;\r\n$session[\'user\'][\'turns\']++;',0,NULL,0,0,0),('ss_serpens','Stein der Spica',28,'Der matt glänzende und sehr glatte  Stein fühlt sich dunkel und kühl an. Doch in seinem Innern meinst du ein Glimmen zu spüren, wie von einem weit entfernten Stern - Spica!`n\r\nDieser Stein verwehrt dir einen Waldkampf pro Tag.',1,0,0,0,0,0,'',0,1,0,0,0,0,1,0,0,0,0,0,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,'_codehook_',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'global $session;\r\n$session[\'user\'][\'turns\']--;',0,NULL,0,0,0),('ss_taurus','Stein des Aldebaran',28,'Der matt glänzende und sehr glatte  Stein fühlt sich dunkel und kühl an. Doch in seinem Innern meinst du ein Glimmen zu spüren, wie von einem weit entfernten Stern - Aldebaran!`n\r\nDieser Stein verwehrt dir einen Waldkampf pro Tag.',1,0,0,0,0,0,'',0,1,0,0,0,0,1,0,0,0,0,0,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,'_codehook_',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'global $session;\r\n$session[\'user\'][\'turns\']--;',0,NULL,0,0,0),('ss_vega','Stein der Wega',28,'Der matt glänzende und sehr glatte  Stein fühlt sich dunkel und kühl an. Doch in seinem Innern meinst du ein Glimmen zu spüren, wie von einem weit entfernten Stern - Wega!`n\r\nDieser Stein gewährt dir einen zusätzlichen Waldkampf pro Tag.',1,0,0,0,0,0,'',0,1,0,0,0,0,1,0,0,0,0,0,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,'_codehook_',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'global $session;\r\n$session[\'user\'][\'turns\']++;',0,NULL,0,0,0),('staub','`)Staub`0',7,'Hier wurde lange nicht mehr sauber gemacht.',1,0,0,0,0,0,'',0,0,0,0,0,0,0,10,10,1,1,0,1,1,0,0,0,2,1,0,0,1,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift',NULL,NULL,0,0,'',0,NULL,1,0,1),('sternschnuppe','`/Sternschnuppe',4,'Wünsch Dir was und es wird erfüllt werden.',1000,0,0,0,0,0,'',0,0,0,0,0,0,0,10,10,0,1,1,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,0),('sternstb','`ySternenstaub',4,'Der Sternenstaub lässt Dich sehr hübsch aussehen.',500,0,0,0,0,0,'',0,0,0,0,0,0,0,10,10,0,1,1,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,0),('sthl22','22 `tEichenholzstühle`0',7,'22 Stühle aus massivem Eichenholz.',2000,20,0,0,0,0,'',0,0,0,0,0,0,0,10,10,1,1,0,1,1,0,0,0,2,1,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift',NULL,NULL,0,0,'',0,NULL,1,0,1),('sthlbn','Stuhlbein',3,'Wertloser Plunder',5,0,0,0,0,0,'',0,1,4,0,1,0,0,1,1,0,0,1,1,1,1,0,0,3,0,0,0,1,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,1,0,1),('sthstndfig','`^Seth `yStandfigur`0',7,'In Lebensgröße. Spielt 6 verschiedene Melodien. Der Gipfel des schlechten Geschmacks.',500,12,0,0,0,0,'',0,0,0,0,0,0,0,10,10,0,1,1,0,1,0,0,0,2,1,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,'seth','seth',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,1,0,0),('stil','`&Stil`0',7,'Jawohl! Dieses Haus hat Stil!',0,100,0,0,0,0,'',0,0,0,0,0,0,0,10,10,0,1,0,0,1,0,0,0,2,1,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',NULL,NULL,0,0,'',0,NULL,1,0,0),('stndhrf','`^St`yandharf`^e`0',7,'Reicht einem ausgewachsenen Mann bis etwa zur Brust.',5000,40,0,0,0,0,'',0,0,0,0,0,0,0,10,10,1,1,0,1,1,0,0,0,2,1,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift',NULL,NULL,0,0,'',0,NULL,1,0,1),('stnduhr','`TS`dtanduh`Tr`0',7,'Eine wunderschöne antike Standuhr aus Eichenholz. ',10000,30,0,0,0,0,'',0,0,0,0,0,0,0,10,10,1,1,0,1,1,0,0,0,2,1,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift',NULL,NULL,0,0,'',0,NULL,1,0,1),('stnkblm','`tStinkblume`0',4,'Sag es durch die Blume - und lass den Boten die Prügel kassieren!',15,0,0,0,0,0,'',0,0,0,0,0,0,0,10,10,0,1,1,0,1,0,0,0,0,0,0,1,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,0),('stnkbmb','`6Stink`$bombe',18,'Gefertigt aus etwas so furchtbar stinkendem, dass du diese \"Apparatur\" nur äußerst widerwillig berührst. ',1,0,0,0,0,0,'`6Stinkt bestialisch.',0,1,0,0,0,0,0,0,0,0,0,1,1,1,1,0,0,0,0,0,0,0,0,0,0,0,0,1,'stinkbombe',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'stinkbombe',NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,1),('stockalt','`tD`de`qr `th`dä`Ts`ds`tl`Ti`tc`Th`qe `tS`dt`to`Tc`qk',8,'Der hässliche Stock des alten Mannes. Dass du ihn nun in deinem Besitz hast heißt aber noch lange nicht, dass sich der Alte nicht schon längst einen neuen Stock besorgt hat und damit weiter sein Unwesen treibt.',0,0,10,0,0,0,'',0,1,0,0,1,0,1,0,0,0,0,1,0,1,0,0,0,0,0,0,0,0,0,0,2,1,0,1,NULL,NULL,NULL,'_codehook_',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'global $session, $badguy, $item_hook_info;\r\n\r\nif ($item[\'deposit1\'] == 9999999)\r\n{\r\n\r\n$theft = e_rand(2,3);\r\n\r\noutput(\"`n`^Nach deinem Sieg brätst du \".$badguy[\'creaturename\'].\"`^ nochmal eins mit deinem hässlichen Stock über.`n\".$badguy[\'creaturename\'].\"`^ verliert \".$theft.\" Charmepunkte und du erhältst \".$theft.\" dazu!`n`0\");\r\n\r\n$session[\'user\'][\'charm\']+=$theft;\r\n\r\n$item_hook_info[\'loose_str\'].=\"`4`n`nDer hässliche Stock von \".$session[\'user\'][\'name\'].\"`4 raubte dir \".$theft.\" Charmepunkte!`n`0\";\r\n\r\n$sql = \"UPDATE accounts SET charm=charm-\".$theft.\" WHERE acctid=\".$badguy[\'acctid\'];\r\ndb_query($sql);\r\n\r\n}',0,NULL,0,0,0),('stoff','`*Stoff',26,'Ein Stück feiner Stoff.',1,0,0,0,0,0,'',0,1,0,0,1,0,0,5,5,0,1,1,1,1,0,0,0,2,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,1,30,1),('strgale','Starkbier',3,'Ein deftiges Gebräu, hergestellt aus Cedriks feinstem Haus-Ale. Das macht den stärksten Hühnen besoffen!',900,0,1,0,0,0,'',0,1,0,0,1,0,0,0,0,0,0,1,1,1,1,0,0,0,0,0,0,1,0,1,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'_codehook_',NULL,NULL,NULL,0,0,'global $badguy,$session;\r\n\r\noutput(\'`n`b`5Du öffnest dein Starkbierfass und reichst es deinem Gegner.`b\');\r\n\r\nif(!$badguy[\'boss\'] && $badguy[\'creaturename\'] != \'Dragonslayer\') {\r\n\r\noutput(\'`n`b\'.$badguy[\'creaturename\'].\'`5 nimmt einen tiefen Zug und rollt sich anschliessend sturzbesoffen auf dem Boden zusammen.`b`n`n\');\r\n\r\n$badguy[\'diddamage\'] = 0;\r\n$badguy[\'creaturehealth\'] = 0;\r\n}\r\nelse{\r\noutput(\'`n`b\'.$badguy[\'creaturename\'].\'`5 trinkt es in einem Zug aus, zeigt sich allerdings kein Bisschen beeindruckt.`b`n\');\r\n}\r\n\r\nitem_delete(\' id = \'.$item[\'id\']);\r\nitem_add($session[\'user\'][\'acctid\'],\'strgalehlf\');\r\n\r\n',0,NULL,0,0,1),('strgalehlf','Starkbier - halbvoll',3,'Ein deftiges Gebräu, hergestellt aus Cedriks feinstem Haus-Ale. Das macht den stärksten Hühnen besoffen! Das Fass ist nur noch halbvoll.',450,0,1,0,0,0,'',0,1,0,0,1,0,0,0,0,0,0,1,1,1,1,0,0,0,0,0,0,1,0,1,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'_codehook_',NULL,NULL,NULL,0,0,'global $badguy;\r\n\r\noutput(\'`n`b`5Du öffnest dein Starkbierfass und reichst es deinem Gegner.`b\');\r\n\r\nif(!$badguy[\'boss\'] && $badguy[\'creaturename\'] != \'Dragonslayer\') {\r\n\r\noutput(\'`n`b\'.$badguy[\'creaturename\'].\'`5 nimmt einen tiefen Zug und rollt sich anschliessend sturzbesoffen auf dem Boden zusammen.`b`n`n\');\r\n\r\n$badguy[\'diddamage\'] = 0;\r\n$badguy[\'creaturehealth\'] = 0;\r\n}\r\nelse{\r\noutput(\'`n`b\'.$badguy[\'creaturename\'].\'`5 trinkt es in einem Zug aus, zeigt sich allerdings kein Bisschen beeindruckt.`b`n\');\r\n}\r\n\r\nitem_delete(\' id = \'.$item[\'id\']);\r\n\r\n\r\n',0,NULL,0,0,1),('strplak','Steuerzahlerplakette',7,'Ein großes Schild, das über der Tür an der Aussenwand des Hauses angebracht wird. Trägt die Aufschrift \"`2Wir zahlen Steuern!`0\"',500,0,0,0,0,0,'',0,0,0,0,0,0,0,10,10,0,1,0,1,1,0,0,0,2,1,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift',NULL,NULL,0,0,'',0,NULL,1,0,1),('strpuppe','`yStrohpuppe`0',7,'',1,0,0,0,0,0,'',0,0,0,0,0,0,0,1,1,1,0,1,1,1,0,0,0,2,0,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,'puppe','puppe',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,1,0,1),('strssrosen','`$Strauß Rosen',4,'`REin ganzer Strauß aus wohlduftenden roten Rosen. Der ist von {name}`R.',20,0,0,0,0,0,'',0,0,0,0,0,0,0,10,10,0,0,1,1,1,0,0,0,0,0,0,1,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,1),('sunflower','Sonnenblumenkerne',29,'Feinste Sonnenblumenkerne',10,0,3,0,0,0,'',0,1,0,0,1,0,0,0,0,0,0,1,1,1,1,0,0,0,1,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,1),('teleskop','`&Teleskop`0',18,'Eine Röhre mit optischen Linsen. Wenn man durchguckt sieht man alles viel näher. Seeleute benutzen sowas häufig.',0,10,0,0,0,0,'',0,1,0,0,0,0,0,10,10,0,1,1,0,1,0,0,0,2,1,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,1,0,0),('theater_ticket','`tTheather Ticket`0',12,'Ein Theatherticket für das Theater im Nobelviertel.',1,0,0,0,0,0,'',0,1,0,0,0,0,0,0,0,0,0,1,1,1,0,0,0,0,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,1),('thedung','Dung',27,'Stinkt furchtbar!\r\nHierfür hat sich jemand ganz besonders viel Mühe gegeben!',1,0,0,0,0,0,'',0,1,0,0,1,0,0,10,10,10,1,1,1,1,1,0,0,2,0,0,0,1,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,1,0,1),('thorhammer','Thors-Hammer-Amulett',3,'Ein Amulett welches den Donner ruft wenn man an ihm reibt.',1000,0,0,0,0,0,'',0,0,0,0,1,0,0,5,5,0,1,1,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,0),('thymian','Thymian',24,'Ein blassrosafarbenes Lippenblütengewächs, als Heil- und Gewürzpflanze bekannt.',15,0,0,0,0,0,'',0,1,0,0,1,0,0,0,0,0,0,1,1,1,1,0,0,0,0,3,0,1,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',1,NULL,0,0,1),('tmplflch1','Fluch der Tempelpriester',9,'',3000,50,0,0,4,0,'',0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,'fluch',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,0,'',0,NULL,0,0,0),('tmplflch2','Schlimmer Fluch der Tempelpriester',9,'',5000,75,0,0,4,0,'',0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,'fluch',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,0,'',0,NULL,0,0,0),('tmplsgn','Segen der Tempelpriester',11,'Die Priester haben dich gesegnet.',1000,1,0,0,0,0,'',0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,'segen',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,4,0,'',0,NULL,0,0,0),('tollkirsch','Tollkirsche',24,'Ein ausgesprochen giftiges Nachtschattengewächs. Für Kinder und Jugendliche unzugänglich aufbewahren!',25,0,0,0,0,0,'',0,1,0,0,1,0,0,0,0,0,0,1,1,1,1,0,0,0,0,3,0,1,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,1),('tolplak','`TT`tol`ger`8an`&z`t-Plakette `0',7,'Ein großes Schild, das über der Tür an der Aussenwand des Hauses angebracht wird. Trägt die Aufschrift \"`^Mein Freund ist Halbling!`0\"',500,0,0,0,0,0,'',0,0,0,0,0,0,0,10,10,0,1,0,1,1,0,0,0,2,1,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift',NULL,NULL,0,0,'',0,NULL,1,0,1),('tomaten','Tomaten',25,'Schöne rote Tomaten.',20,0,0,0,75,0,'',0,1,0,0,0,0,0,0,0,0,0,1,1,1,1,0,0,3,1,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'kitchen',0,0,'',1,NULL,1,0,1),('toterhase','Hase',25,'Ein süßes, kleines, totes Häschen. Hmm, tot ist es vielleicht eher nahrhaft als süß. Auf jeden Fall ist es ein paar Goldstücke wert.',50,0,0,0,40,0,'',0,1,0,0,1,0,0,1,0,0,0,1,1,1,1,0,0,3,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'kitchen',0,0,'',1,NULL,0,0,1),('totplak','Totenreichplakette',7,'Ein großes Schild, das über der Tür an der Aussenwand des Hauses angebracht wird. Trägt die Aufschrift \"`7Schickt mir Schädel!`0\"',500,0,0,0,0,0,'',0,0,0,0,0,0,0,10,10,0,1,0,1,1,0,0,0,2,1,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift',NULL,NULL,0,0,'',0,NULL,1,0,1),('towel','Badehandtuch',4,'Ein großes Handtuch, mit dem du dich nach dem Bad trockenrubbeln kannst. Oder lass dich von {name} abtrocknen. ',1000,0,0,0,0,0,'',0,0,0,0,0,0,0,10,10,0,1,1,0,1,0,0,0,0,0,0,1,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,0),('tpfkupfer','Einfacher Kupfertopf',18,'Großer, aber gewöhnlicher Topf aus Kupfer.',500,2,0,0,70,0,'Küchenutensilien - Topf',0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',0,0,'',0,NULL,0,0,0),('trfal','Truhenfalle',7,'Ein raffinierter Zusatzmechanismus für den Hausschatz, der Langfingern über eine versteckte Kanüle ein tödliches Gift injeziert. Muss mit Giftphiolen befüllt werden!',10000,50,0,0,0,0,'',0,0,0,0,0,0,0,1,0,0,0,0,0,1,0,0,0,2,1,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,'truhenfalle',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,1,0,0),('trinkwasser','Trinkwasser',25,'Ein Becher frisches Wasser, klar und köstlich.',0,0,0,0,0,0,'',0,1,0,0,1,0,0,0,0,0,0,1,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',1,NULL,0,0,0),('trkalk','Unbekannter Trank',17,'Eine mit einem dicken Korken verschlossene Phiole. Der Inhalt ist eine trüb-rote Flüssigkeit. Was dieser Trank genau bewirkt vermagst du nicht zu sagen.',100,0,1,0,0,0,'',0,0,3,0,0,0,0,0,0,0,0,1,1,1,1,0,0,3,0,0,0,0,0,1,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'_codehook_',NULL,'_codehook_',NULL,NULL,NULL,0,0,'global $session,$badguy;\r\n\r\nif ($session[\'user\'][\'drunkenness\']>60)\r\n{\r\noutput(\"`&Du setzt den Trank an und merkst, dass es sich um reinen Drachenschnapps handelt. Um dich nicht ins Jenseits zu befördern spuckst du ihn schnell wieder aus, doch einen Teil davon hast du bereits geschluckt.\");\r\n}\r\nelse\r\n{\r\noutput(\"`&Du leerst den Trank in einem Zug und stellst fest, dass es reiner Drachenschnapps war! Mann, bist du besoffen!\");\r\n}\r\n$session[\'user\'][\'drunkenness\']=90;\r\nitem_delete(\' id=\'.$item[\'id\']);\r\n\r\nif (!$badguy[\'creaturehealth\']>0)\r\n{\r\naddnav(\'Zum Inventar\',$item_hook_info[\'ret\']);\r\n}',0,NULL,1,0,1),('trkalt','Unbekannter Trank',17,'Eine mit einem dicken Korken verschlossene Phiole. Der Inhalt ist eine trüb-rote Flüssigkeit. Was dieser Trank genau bewirkt vermagst du nicht zu sagen.',100,0,1,0,0,0,'',0,0,3,0,0,0,0,0,0,0,0,1,1,1,1,0,0,3,0,0,0,0,0,1,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'_codehook_',NULL,'_codehook_',NULL,NULL,NULL,0,0,'global $session,$badguy;\r\n\r\noutput(\"`&Du leerst den Trank in einem Zug und stellst fest, dass du körperlich um 3 Tage gealtert bist!\");\r\n$session[\'user\'][\'age\']+=3;\r\n\r\nitem_delete(\' id=\'.$item[\'id\']);\r\n\r\nif (!$badguy[\'creaturehealth\']>0)\r\n{\r\naddnav(\'Zum Inventar\',$item_hook_info[\'ret\']);\r\n}',0,NULL,1,0,1),('trkcharm','Unbekannter Trank',17,'Eine mit einem dicken Korken verschlossene Phiole. Der Inhalt ist eine trüb-rote Flüssigkeit. Was dieser Trank genau bewirkt vermagst du nicht zu sagen.',100,0,1,0,0,0,'',0,0,3,0,0,0,0,0,0,0,0,1,1,1,1,0,0,3,0,0,0,0,0,1,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'_codehook_',NULL,'_codehook_',NULL,NULL,NULL,0,0,'global $session,$badguy;\r\n\r\noutput(\"`&Du leerst den Trank in einem Zug und bemerkst, dass du hübscher geworden bist!`nDu erhältst einen Charmepunkt!\");\r\n$session[\'user\'][\'charm\']+=1;\r\n\r\nitem_delete(\' id=\'.$item[\'id\']);\r\n\r\nif (!$badguy[\'creaturehealth\']>0)\r\n{\r\naddnav(\'Zum Inventar\',$item_hook_info[\'ret\']);\r\n}',0,NULL,1,0,1),('trkgef','Unbekannter Trank',17,'Eine mit einem dicken Korken verschlossene Phiole. Der Inhalt ist eine trüb-rote Flüssigkeit. Was dieser Trank genau bewirkt vermagst du nicht zu sagen.',100,0,1,0,0,0,'',0,0,0,0,0,0,0,0,0,0,0,1,1,1,1,0,0,3,0,0,0,0,0,1,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'_codehook_',NULL,'_codehook_',NULL,NULL,NULL,0,0,'global $session,$badguy;\r\n\r\noutput(\"`&Du leerst den Trank in einem Zug und bekommst seltsame Visionen von einem Leben nach dem Tode!\");\r\n$session[user][deathpower] +=80;\r\nitem_delete(\' id=\'.$item[\'id\']);\r\n\r\nif (!$badguy[\'creaturehealth\']>0)\r\n{\r\naddnav(\'Zum Inventar\',$item_hook_info[\'ret\']);\r\n}',0,NULL,1,0,1),('trkgem','Unbekannter Trank',17,'Eine mit einem dicken Korken verschlossene Phiole. Der Inhalt ist eine trüb-rote Flüssigkeit. Was dieser Trank genau bewirkt vermagst du nicht zu sagen.',100,0,1,0,0,0,'',0,0,2,0,0,0,0,0,0,0,0,1,1,1,1,0,0,3,0,0,0,0,0,1,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'_codehook_',NULL,'_codehook_',NULL,NULL,NULL,0,0,'global $session,$badguy;\r\n\r\noutput(\"`&Du leerst den Trank in einem Zug und bemerkst einen Edelstein, der sich in der Phiole befand!\");\r\n$session[\'user\'][\'gems\']++;\r\nitem_delete(\' id=\'.$item[\'id\']);\r\n\r\nif (!$badguy[\'creaturehealth\']>0)\r\n{\r\naddnav(\'Zum Inventar\',$item_hook_info[\'ret\']);\r\n}',0,NULL,1,0,1),('trkgiant','Unbekannter Trank',17,'Eine mit einem dicken Korken verschlossene Phiole. Der Inhalt ist eine trüb-rote Flüssigkeit. Was dieser Trank genau bewirkt vermagst du nicht zu sagen.',100,0,1,0,0,0,'',0,0,3,0,0,0,0,0,0,0,0,1,1,1,1,0,0,3,0,0,0,0,0,1,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'_codehook_',NULL,'_codehook_',NULL,NULL,NULL,0,0,'global $session,$badguy;\r\n\r\noutput(\"`&Du leerst den Trank in einem Zug und gewaltige Kräfte durchströmen dich.\");\r\n                            \r\nif (isset($session[\'bufflist\'][\'giantpower\']))\r\n{                              $session[\'bufflist\'][\'giantpower\'][\'rounds\'] += 25;\r\n}\r\nelse\r\n{                               $session[\'bufflist\'][\'giantpower\']=array(\"name\"=>\"`#Riesenstärke\",\r\n\"rounds\"=>25, \"wearoff\"=>\"Die Stärke verlässt dich.\", \"atkmod\"=>3, \"defmod\"=>3, \"roundmsg\"=>\"Du hast die Kraft eines Riesen! \",\"activate\"=>\"offense,defense\");\r\n}\r\n\r\nitem_delete(\' id=\'.$item[\'id\']);\r\n\r\nif (!$badguy[\'creaturehealth\']>0)\r\n{\r\naddnav(\'Zum Inventar\',$item_hook_info[\'ret\']);\r\n}',0,NULL,1,0,1),('trkgift','Unbekannter Trank',17,'Eine mit einem dicken Korken verschlossene Phiole. Der Inhalt ist eine trüb-rote Flüssigkeit. Was dieser Trank genau bewirkt vermagst du nicht zu sagen.',100,0,1,0,0,0,'',0,0,3,0,0,0,0,0,0,0,0,1,1,1,1,0,0,3,0,0,0,0,0,1,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'_codehook_',NULL,'_codehook_',NULL,NULL,NULL,0,0,'global $session,$badguy;\r\noutput(\'`&Du leerst den Trank in einem Zug und das Gift, das du gerade zu dir genommen hast, befördert dich direkt zu Ramius!\');\r\nkillplayer(100,0,0,\'\');\r\naddnews($session[\'user\'][\'name\'].\' hat \'.($session[\'user\'][\'sex\']?\'ihrem\':\'seinem\').\' Leben mit Gift ein Ende bereitet.\');\r\nif ($badguy[\'creaturehealth\']>0)\r\n{\r\n$session[\'user\'][\'badguy\']=\'\';\r\nclearnav();\r\nitem_delete(\' id=\'.$item[\'id\']);\r\naddnav(\'Sterben\',\'shades.php\');\r\npage_footer();\r\n}\r\nelse\r\n{\r\naddnav(\'Sterben\',\'shades.php\');\r\nitem_delete(\' id=\'.$item[\'id\']);\r\n}',0,NULL,1,0,1),('trkjung','Unbekannter Trank',17,'Eine mit einem dicken Korken verschlossene Phiole. Der Inhalt ist eine trüb-rote Flüssigkeit. Was dieser Trank genau bewirkt vermagst du nicht zu sagen.',100,0,1,0,0,0,'',0,0,3,0,0,0,0,0,0,0,0,1,1,1,1,0,0,3,0,0,0,0,0,1,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'_codehook_',NULL,'_codehook_',NULL,NULL,NULL,0,0,'global $session,$badguy;\r\n\r\nif ($session[\'user\'][\'age\']>5)\r\n{\r\noutput(\"`&Du leerst den Trank in einem Zug und dein Körper verjüngt sich um 5 Tage!\");\r\n$session[\'user\'][\'age\']-=5;\r\n}\r\nelse\r\n{\r\noutput(\"`&Du leerst den Trank in einem Zug und spürst einen Effekt der Verjüngung.\");\r\n$session[\'user\'][\'age\']=1;\r\n}\r\nitem_delete(\' id=\'.$item[\'id\']);\r\n\r\nif (!$badguy[\'creaturehealth\']>0)\r\n{\r\naddnav(\'Zum Inventar\',$item_hook_info[\'ret\']);\r\n}',0,NULL,1,0,1),('trkklo','Unbekannter Trank',17,'Eine mit einem dicken Korken verschlossene Phiole. Der Inhalt ist eine trüb-rote Flüssigkeit. Was dieser Trank genau bewirkt vermagst du nicht zu sagen.',100,0,1,0,0,0,'',0,0,4,0,0,0,0,0,0,0,0,1,1,1,1,0,0,3,0,0,0,0,0,1,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'_codehook_',NULL,'_codehook_',NULL,NULL,NULL,0,0,'global $session,$badguy;\r\n\r\noutput(\"`&Du leerst den Trank in einem Zug und merkst, dass es Abführmittel war!`nAuweia, und gerade jetzt ist das Klopapier aus.\");\r\n                            \r\nif (isset($session[\'bufflist\'][\'durchfall\']))\r\n{                              $session[\'bufflist\'][\'durchfall\'][\'rounds\'] += 50;\r\n}\r\nelse\r\n{                               $session[\'bufflist\'][\'durchfall\']=array(\"name\"=>\"`tDünnpfiff\",\r\n\"rounds\"=>50, \"wearoff\"=>\"Das Gluckern in deinem Bauch hat nun ein Ende.\", \"atkmod\"=>0.50, \"defmod\"=>0.50, \"roundmsg\"=>\"Du machst dir fast in die Hose, allerdings nicht vor Angst. \",\"activate\"=>\"offense,defense\");\r\n}\r\n\r\nitem_delete(\' id=\'.$item[\'id\']);\r\n\r\nif (!$badguy[\'creaturehealth\']>0)\r\n{\r\naddnav(\'Zum Inventar\',$item_hook_info[\'ret\']);\r\n}',0,NULL,1,0,1),('trklp','Unbekannter Trank',17,'Eine mit einem dicken Korken verschlossene Phiole. Der Inhalt ist eine trüb-rote Flüssigkeit. Was dieser Trank genau bewirkt vermagst du nicht zu sagen.',100,0,1,0,0,0,'',0,0,3,0,0,0,0,0,0,0,0,1,1,1,1,0,0,3,0,0,0,0,0,1,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'_codehook_',NULL,'_codehook_',NULL,NULL,NULL,0,0,'global $session,$badguy;\r\n\r\nif ($session[\'user\'][\'hitpoints\']>0)\r\n{\r\noutput(\'`&Du leerst den Trank in einem Zug und heilst um 100 Lebenspunkte!\');\r\n$session[\'user\'][\'hitpoints\']+=100;\r\n}\r\nelse\r\n{\r\noutput(\'`&Du leerst den Trank in einem Zug, doch seine Heilkraft reicht nicht aus, um dich vor dem Tode zu retten.\');\r\n}\r\nitem_delete(\' id=\'.$item[\'id\']);\r\n\r\nif (!$badguy[\'creaturehealth\']>0)\r\n{\r\naddnav(\'Zum Inventar\',$item_hook_info[\'ret\']);\r\n}',0,NULL,1,0,1),('trknix','Unbekannter Trank',17,'Eine mit einem dicken Korken verschlossene Phiole. Der Inhalt ist eine trüb-rote Flüssigkeit. Was dieser Trank genau bewirkt vermagst du nicht zu sagen.',100,0,1,0,0,0,'',0,0,4,0,0,0,0,0,0,0,0,1,1,1,1,0,0,3,0,0,0,0,0,1,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'_codehook_',NULL,'_codehook_',NULL,NULL,NULL,0,0,'global $session,$badguy;\r\n\r\noutput(\"`&Du leerst den Trank in einem Zug und findest, dass er lecker schmeckt.`nWie schön: deine Lieblingslimonade!`n`nAber sonst geschieht nichts weiter...`n\");\r\n\r\nitem_delete(\' id=\'.$item[\'id\']);\r\n\r\nif (!$badguy[\'creaturehealth\']>0)\r\n{\r\naddnav(\'Zum Inventar\',$item_hook_info[\'ret\']);\r\n}',0,NULL,1,0,1),('trkrace','Unbekannter Trank',17,'Eine mit einem dicken Korken verschlossene Phiole. Der Inhalt ist eine trüb-rote Flüssigkeit. Was dieser Trank genau bewirkt vermagst du nicht zu sagen.',100,0,1,0,0,0,'',0,0,1,0,0,0,0,0,0,0,0,1,1,1,1,0,0,3,0,0,0,0,0,1,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'_codehook_',NULL,'_codehook_',NULL,NULL,NULL,0,0,'global $session,$badguy;\r\n\r\n$session[\'user\'][\'race\']=\'\';\r\noutput(\"`&Du leerst den Trank in einem Zug und spürst wie deine Knochen weich wie Gummi werden!`n`^(Deine Rasse wurde zurückgesetzt. Du kannst morgen eine neue wählen.\");\r\n                            \r\nif (isset($session[\'bufflist\'][\'transmute\']))\r\n{                              $session[\'bufflist\'][\'transmute\'][\'rounds\'] += 10;\r\n}\r\nelse\r\n{                               $session[\'bufflist\'][\'transmute\']=array(\"name\"=>\"`6Transmutationskrankheit\",\r\n\"rounds\"=>10, \"wearoff\"=>\"Du hörst auf, deine Därme auszukotzen. Im wahrsten Sinne des Wortes.\", \"atkmod\"=>0.75, \"defmod\"=>0.75, \"roundmsg\"=>\"Teile deiner Haut und deiner Knochen verformen sich wie Wachs.\", \"survivenewday\"=>1,                              \"newdaymessage\"=>\"`6Durch die Auswirkungen des Transmutationstranks fühlst du dich immer noch `2krank`6.\",                               \"activate\"=>\"offense,defense\");\r\n}\r\n\r\n// Rassenboni abnehmen\r\nrace_set_boni(true,true,$session[\'user\']);\r\n\r\nitem_delete(\' id=\'.$item[\'id\']);\r\n\r\nif (!$badguy[\'creaturehealth\']>0)\r\n{\r\naddnav(\'Zum Inventar\',$item_hook_info[\'ret\']);\r\n}\r\n\r\ndebuglog(\"Hat mit einem Trank die Rasse gewechselt.\");',0,NULL,1,0,1),('trkugly','Unbekannter Trank',17,'Eine mit einem dicken Korken verschlossene Phiole. Der Inhalt ist eine trüb-rote Flüssigkeit. Was dieser Trank genau bewirkt vermagst du nicht zu sagen.',100,0,1,0,0,0,'',0,0,4,0,0,0,0,0,0,0,0,1,1,1,1,0,0,3,0,0,0,0,0,1,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'_codehook_',NULL,'_codehook_',NULL,NULL,NULL,0,0,'global $session,$badguy;\r\n\r\noutput(\"`&Du leerst den Trank in einem Zug und bemerkst, dass dir ein dicker Pickel auf der Nase wächst!`nDu hast einen Charmepunkt verloren!\");\r\nif ($session[\'user\'][\'charm\']<1)\r\n{\r\n$session[\'user\'][\'charm\']=0;\r\n}\r\nelse\r\n{\r\n$session[\'user\'][\'charm\']-=1;\r\n}\r\nitem_delete(\' id=\'.$item[\'id\']);\r\n\r\nif (!$badguy[\'creaturehealth\']>0)\r\n{\r\naddnav(\'Zum Inventar\',$item_hook_info[\'ret\']);\r\n}',0,NULL,1,0,1),('trkvit','Unbekannter Trank',17,'Eine mit einem dicken Korken verschlossene Phiole. Der Inhalt ist eine trüb-rote Flüssigkeit. Was dieser Trank genau bewirkt vermagst du nicht zu sagen.',100,0,1,0,0,0,'',0,0,2,0,0,0,0,0,0,0,0,1,1,1,1,0,0,3,0,0,0,0,0,1,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'_codehook_',NULL,'_codehook_',NULL,NULL,NULL,0,0,'global $session,$badguy;\r\n\r\noutput(\"`&Du leerst den Trank in einem Zug und fühl dich erfrischt um heute einen weiteren Kampf bestreiten zu können!\");\r\n$session[\'user\'][\'turns\']++;\r\n\r\nitem_delete(\' id=\'.$item[\'id\']);\r\n\r\nif (!$badguy[\'creaturehealth\']>0)\r\n{\r\naddnav(\'Zum Inventar\',$item_hook_info[\'ret\']);\r\n}',0,NULL,1,0,1),('trkxp','Unbekannter Trank',17,'Eine mit einem dicken Korken verschlossene Phiole. Der Inhalt ist eine trüb-rote Flüssigkeit. Was dieser Trank genau bewirkt vermagst du nicht zu sagen.',100,0,1,0,0,0,'',0,0,2,0,0,0,0,0,0,0,0,1,1,1,1,0,0,3,0,0,0,0,0,1,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'_codehook_',NULL,'_codehook_',NULL,NULL,NULL,0,0,'global $session,$badguy;\r\n\r\n$gain=round($session[\'user\'][\'experience\']*0.15+100);\r\noutput(\"`&Du leerst den Trank in einem Zug und tiefe Weisheit durchflutet dich.`nDu erhälst `@\".$gain.\"`& Erfahrungspunkte dazu!\");\r\n$session[\'user\'][\'experience\']+=$gain;\r\nitem_delete(\' id=\'.$item[\'id\']);\r\n\r\nif (!$badguy[\'creaturehealth\']>0)\r\n{\r\naddnav(\'Zum Inventar\',$item_hook_info[\'ret\']);\r\n}',0,NULL,1,0,1),('trkxpm','Unbekannter Trank',17,'Eine mit einem dicken Korken verschlossene Phiole. Der Inhalt ist eine trüb-rote Flüssigkeit. Was dieser Trank genau bewirkt vermagst du nicht zu sagen.',100,0,1,0,0,0,'',0,0,3,0,0,0,0,0,0,0,0,1,1,1,1,0,0,3,0,0,0,0,0,1,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'_codehook_',NULL,'_codehook_',NULL,NULL,NULL,0,0,'global $session,$badguy;\r\n\r\n$lose=round($session[\'user\'][\'experience\']*0.1);\r\noutput(\'`&Du leerst den Trank in einem Zug und spürst wie dich die totale Verdummung einnebelt.`nDu verlierst `$\'.$lose.\'`& Erfahrungspunkte!\');\r\n$session[\'user\'][\'experience\']-=$lose;\r\nitem_delete(\' id=\'.$item[\'id\']);\r\n\r\nif (!$badguy[\'creaturehealth\']>0)\r\n{\r\naddnav(\'Zum Inventar\',$item_hook_info[\'ret\']);\r\n}',0,NULL,1,0,1),('trnkangr','Zaubertrank für Angriff',14,'',1,0,0,0,0,0,'',0,1,0,0,1,0,0,0,0,0,0,1,1,1,1,0,0,0,0,0,0,0,0,3,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'zaubertrnk_guild',NULL,NULL,NULL,29,0,'',0,NULL,0,0,1),('trnkdef','Zaubertrank für Verteidigung',14,'',1,0,0,0,0,0,'',0,1,0,0,1,0,0,0,0,0,0,1,1,1,1,0,0,0,0,0,0,0,0,3,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'zaubertrnk_guild',NULL,NULL,NULL,31,0,'',0,NULL,0,0,1),('trostbon','`rTrostbonbon`0',4,'Ein leckeres, kleines Bonbon, dass dir den \"nicht-Sieg\" versüßen soll.',1,0,0,0,0,0,'',0,0,0,0,0,0,0,10,10,5,1,1,1,1,1,0,0,0,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,1),('trph','Trophäe',16,'',1,0,0,0,0,0,'',0,1,0,0,0,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,0,1,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'_codehook_',NULL,NULL,0,0,'if($hook_item[\'hvalue\'] == $item_hook_info[\'recipient\'][\'acctid\']) {\r\n	output(\"`&Also bitte! Du wirst \".$item_hook_info[\'recipient\'][\'name\'].\"`& doch nicht seine eigenen Körperteile schicken wollen?!\");\r\n	page_footer();\r\n	exit;\r\n}\r\nif ( item_count(\"owner=\".$item_hook_info[\'recipient\'][\'acctid\'].\" AND tpl_id=\'trph\' AND hvalue=\".$hook_item[\'hvalue\']) >=5) {\r\n	output(\"`&Etwas Abwechslung könnte nicht schaden, \".$item_hook_info[\'recipient\'][\'name\'].\"`& hat schon genug von diesen Teilen.\");\r\n	page_footer();\r\n	exit;\r\n}\r\n',0,NULL,0,0,1),('trphlanze','Trophäenlanze',16,'Auf dieser Lanze wurde ein Kopf aufgespießt. Im Vorgarten aufgestellt lässt sich das Teil sicher als Vogelscheuche verwenden. Oder zur Abschreckung von Bauernkindern.',5,0,0,0,0,0,'',0,1,0,0,0,0,0,6,0,0,1,1,1,1,0,0,0,0,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,1),('tulpe','`8Tulpe',4,'`REine schöne Tulpe. Die ist von {name}`R.',5,0,0,0,0,0,'',0,0,0,0,0,0,0,10,10,0,0,1,1,1,0,0,0,0,0,0,1,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,1),('unikat','Unikat',7,'',0,10,0,0,0,0,'',0,0,0,0,0,0,0,50,50,50,1,1,1,1,0,0,0,2,0,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,1),('unrat','`TTonne`2 voller `6Unrat`0',7,'Man kann sie anzünden und hat somit ein wärmendes Feuer an kalten Tagen. Ebenso könnte man auch mal das Dach reparieren...',25,0,0,0,0,0,'',0,0,0,0,0,0,0,10,10,0,1,0,1,1,0,0,0,2,1,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift',NULL,NULL,0,0,'',0,NULL,1,0,1),('valentinskeks','`}V`Ia`tl`ye`rn`Rtin`rs`yk`te`Ik`}s`0',4,'`rEin leckerer Keks mit cremiger Füllung, speziell für diesen Valentinstag von Bäckermeister Karlon kreiert. \r\n Garantiert eine Sünde wert, dieser Meinung ist zumindest `0{name}`r.',750,0,0,0,0,0,'',0,0,0,0,0,0,0,10,10,0,0,1,1,1,0,0,0,0,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,1),('valentinsrose','`AV`Aal`4en`$tin`4sr`Aos`Ae`0',4,'`ADas perfekte Geschenk zum Valentinstag, das Symbol der ewigen Liebe: Eine tiefrote `,R`Ao`4s`$e`A, mit ein bisschen `JG`2r`jü`@n`A verziert und sorgfältig in Folie eingepackt. Ein kleines Kärtchen steckt mit dabei, auf dem der Absender steht:\r\n{name}`A .',500,0,0,0,0,0,'',0,0,0,0,0,0,0,10,10,0,0,1,1,1,0,0,0,0,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,1),('verbplak','`$Verbots`tplakette`0',7,'Ein großes Schild, das über der Tür an der Aussenwand des Hauses angebracht wird. Trägt die Aufschrift \"`3Abstellen von Pferden und Drachen verboten.`0\"',500,0,0,0,0,0,'',0,0,0,0,0,0,0,10,10,0,1,0,1,1,0,0,0,2,1,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift',NULL,NULL,0,0,'',0,NULL,1,0,1),('vitsmtvorh','`Vviolette `&Samtvorhänge`0',7,'Lange und schwere Vorhänge aus `Vviolettem `&Samt.',5000,10,0,0,0,0,'',0,0,0,0,0,0,0,10,10,0,1,0,1,1,0,0,0,3,1,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift',NULL,NULL,0,0,'',0,NULL,1,0,1),('vlbrschr','Gefüllter Barschrank',7,'`@Ein Barschrank gefüllt mit allen möglichen Sorten Alkohol und einem `^nimmerleeren`@ Bierfass!`0',5000,10,0,0,0,0,'',0,0,0,0,0,0,0,1,1,1,1,1,1,1,1,0,0,2,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift',NULL,NULL,0,0,'',0,NULL,1,0,1),('vllaschbch','`)Vo`7ll`eer `sAs`&ch`sen`ebe`7ch`)er`0',7,'Undefinierbare Reste kleben darin. Er stinkt furchtbar.',50,0,0,0,0,0,'',0,0,0,0,0,0,0,10,10,0,1,0,1,1,0,0,0,2,1,0,0,1,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift',NULL,NULL,0,0,'',0,NULL,1,0,1),('vorl','Vorladung',12,'',1,0,0,0,0,0,'',0,1,0,0,0,0,0,0,0,0,0,0,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,0),('vrftpp','Verfilzter Teppich',7,'Er ist alt, staubig und stinkt. Und wenn man mit nackten Füßen darüber läuft bleiben diese leicht kleben.',100,0,0,0,0,0,'',0,0,0,0,0,0,0,10,10,1,1,0,1,1,0,0,0,2,1,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift',NULL,NULL,0,0,'',0,NULL,1,0,1),('vrstschlg','`8Verstärkter Schlag`0',14,'Dieser einfache Zauber lässt den Anwender einen Schlag mit leicht erhöhtem Angriff ausführen.',300,0,1,1,0,0,'',0,1,0,0,1,0,0,0,0,0,0,0,1,1,1,0,0,0,0,1,0,0,0,3,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,6,0,'',0,NULL,0,0,1),('vrzrtschrb','Verzierter Schreibtisch',7,'Eindeutig elfische Handwerkskunst.',3000,8,0,0,0,0,'',0,0,0,0,0,0,0,10,10,1,1,0,1,1,0,0,0,2,1,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift',NULL,NULL,0,0,'',0,NULL,1,0,1),('wachskl','Wachsklumpen',26,'Ein Klumpen Wachs aus dem Bienenstock.',1,0,0,0,0,0,'',0,1,0,0,1,0,0,5,0,0,0,1,1,1,1,0,0,2,0,0,0,1,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,1,15,1),('waffedummy','Waffen-Dummy',8,'',1,0,0,0,0,0,'',0,1,0,0,1,0,0,0,0,0,0,1,0,1,0,0,0,2,0,0,0,0,0,0,0,1,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,1,0,0),('wasser','Destilliertes Wasser',24,'Destilliertes Wasser, absolut rein.',10,0,0,0,0,0,'',0,1,0,0,1,0,0,0,0,0,0,1,1,1,1,0,0,0,0,3,0,1,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,1),('wassschlau','Wasserschlauch',3,'Ein lederner Wasserschlauch, der sich niemals zu leeren scheint.',100,1,0,0,0,0,'',0,1,1,0,1,0,0,0,0,0,0,1,1,1,1,0,0,3,0,0,0,1,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,1,0,1),('wbsthl','Webstuhl',7,'Ein kompakter Webstuhl, der klein genug ist, in jedem etwas geräumigereren Wohnzimmer seinen Platz zu finden. ',6000,12,0,0,0,0,'',0,0,0,0,0,0,0,1,1,0,1,0,0,1,0,0,0,2,1,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,'webstuhl','webstuhl',NULL,NULL,NULL,NULL,NULL,'send_as_gift',NULL,NULL,0,0,'',0,NULL,1,0,0),('wcklpddg','`GWackelpudding',4,'`gWer auch immer diesen Wackelpudding verbrochen hat - Essen kann man das glibbrige Ding nicht mehr. Dafür aber an die Wand klatschen (Vorsicht: Gibt Flecken) oder an das (schlachtreife) Vieh verfüttern. Oder an Leute, denen man mal was Böses gönnt. Zum Beispiel {name}`g. Aber bitte schnell, ehe das Innenleben des Wackelpuddings Füße bekommt.',347,0,0,0,0,0,'',0,0,0,0,0,0,0,10,10,0,1,1,1,1,0,0,0,0,0,0,1,1,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,1),('wckltsch','Wackeliger Tisch',7,'Hochwertige Lehrlingsarbeit. Taugt aber allemal zum Verheizen.',25,0,0,0,0,0,'',0,0,0,0,0,0,0,10,10,1,1,0,1,1,0,0,0,2,1,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,'',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift',NULL,NULL,0,0,'',0,NULL,1,0,1),('wckrgl','Wackliges Regal',7,'Ein nicht gerade stabil aussehendes Holzregal. Eigentlich erweckt es den Eindruck, es würde beim leisesten Windhauch auseinanderfallen, aber etwas Kleines (und vor allem Leichtes) wird es schon tragen. Vermutlich.',475,0,0,0,0,0,'',0,0,0,0,0,0,0,2,2,1,1,0,1,1,0,0,0,2,1,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift',NULL,NULL,0,0,'',0,NULL,1,0,1),('wein','Flasche Wein',13,'`4Eine eingestaubte Flasche eines sehr alten `zWeines. `4Für besondere Anlässe geeignet. ',500,1,0,0,0,0,'',0,1,0,0,0,0,0,10,10,0,0,0,0,1,0,0,0,2,1,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,1,0,0),('wermut','Wermut',24,'Wermutkraut schmeckt bitter, soll aber (in kleinen Mengen) beruhigend auf den Magen einwirken.',20,0,0,0,0,0,'',0,1,0,0,1,0,0,0,0,0,0,1,1,1,1,0,0,3,0,3,0,1,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,1,0,1),('werose','`&weiße R`ro`&se`0',4,'`rEine wohlduftende, wunderschöne weiße Rose, welche jedoch ein grausames Versprechen mit sich trägt. Die ist von {name}`r.',25,0,0,0,0,0,'',0,0,0,0,0,0,0,10,10,0,0,1,1,1,0,0,0,2,0,0,1,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,1,0,1),('wffnsrnk','Waffenschrank',7,'Ein großer Schrank aus dunklem Holz mit einer Glastür. Hier können die Andenken aus den Kämpfen im Wald stilecht präsentiert werden.',5000,30,0,0,0,0,'',0,0,0,0,0,0,0,1,1,0,1,0,0,1,0,0,0,2,1,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,'waffenschrank','waffenschrank',NULL,NULL,NULL,NULL,NULL,'',NULL,NULL,0,0,'',0,NULL,0,0,0),('wildpig','Wildschwein',25,'Ein frisch erlegtes Wildschwein. Auf dem Spieß gegrillt wird es wunderbar schmecken und sehr nahrhaft sein.',500,0,0,0,35,0,'',0,1,0,0,1,0,0,0,0,0,0,1,1,1,1,0,0,3,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'kitchen',0,0,'',1,NULL,0,0,1),('wlpplak','\"`wWo`vlle Pet`wry\"-`tGedächtnisplakette`0',7,'Ein großes Schild, dass über der Tür an der Aussenwand des Hauses angebracht wird. Trägt die Aufschrift \"`4Hölle! `^Hölle! `2Hölle!`0\"',500,0,0,0,0,0,'',0,0,0,0,0,0,0,10,10,1,1,0,0,1,0,0,0,2,0,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,1,0,0),('wndsbl','`TW`san`Td`ssäbe`Tl`0',7,'Zwei gekreuzte Säbel auf einer dunklen Holztafel.',200,4,0,0,0,0,'',0,0,0,0,0,0,0,10,10,1,1,0,1,1,0,0,0,2,1,0,0,1,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift',NULL,NULL,0,0,'',0,NULL,1,0,1),('wolle','Schurwolle',26,'Feinste Wolle vom nun nackten Schaf.',1,0,0,0,0,0,'',0,1,0,0,1,0,0,5,5,0,0,1,1,1,1,0,0,3,0,0,0,1,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,1,40,1),('woodchest','Große Holztruhe',7,'Eine schwere, große hölzerne Truhe mit einem ebenso schweren, großen Schloss. Einige Dinge lassen sich gewiss hier drin verstauen...',20000,50,0,0,0,0,'',0,0,0,0,0,0,0,1,1,0,1,0,0,1,0,0,0,2,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,'woodenchest','woodenchest',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,1,0,0),('wrkbnk','Werkbank',7,'Eine hölzerne Werkbank mit einer Vorrichtung in der sich Materialien festspannen lassen. Dabei sind alle Werkzeugen, die man brauchen kann: Hammer, Säge, Bohrer, Feile und Hobel liegen immer bereit. ',8000,25,0,0,0,0,'',0,0,0,0,0,0,0,1,1,1,1,0,1,1,0,0,0,2,1,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift',NULL,NULL,0,0,'',0,NULL,1,0,1),('wrnplak','Warnplakette',7,'Ein großes Schild, das über der Tür an der Aussenwand des Hauses angebracht wird. Trägt die Aufschrift \"`5Gib Orks keine Chance.`0\"',500,0,0,0,0,0,'',0,0,0,0,0,0,0,10,10,1,1,0,1,1,0,0,0,2,1,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift',NULL,NULL,0,0,'',0,NULL,1,0,1),('wschkrb','Wäschekorb',7,'Ein runder Bastkorb mit passendem Deckel, in dem die schmutzige Wäsche ihren Platz findet.',200,2,0,0,0,0,'',0,0,0,0,0,0,0,2,2,1,1,0,1,1,0,0,0,2,1,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift',NULL,NULL,0,0,'',0,NULL,1,0,1),('wuerstchen','Saftiges Würstchen',3,'Der kleine Snack für Zwischendurch. Lecker und kräftigend.',50,0,0,0,0,0,'',0,1,0,0,0,0,0,100,100,100,0,1,1,1,1,0,0,2,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,1,0,1),('wursthm','Hausmacherwurst',25,'Wurst aus eigener Produktion.',1,0,0,0,70,0,'',0,1,0,0,1,0,0,10,10,10,0,1,1,1,1,0,0,2,0,0,0,1,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'kitchen',0,0,'',1,NULL,1,0,1),('w_vinegar','`GWeinessig',25,'Dieser Weinessig ist aus feinem atrahor\'schem Wein durch langen Reifeprozess entstanden. Er eignet sich sowohl zum Kochen als auch zum Reinigen hervorragend.',300,1,0,0,0,0,'',0,1,0,0,0,0,0,10,10,0,0,1,1,1,1,0,0,3,1,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',1,NULL,1,0,1),('xmastree_mini','³`pMini`kat`jur-`kTanne`p³`0',4,'`pEine hübsche Miniatur-Tanne, perfekt bei wenig platz und um später mit einer guten Begründung sehr winzige Geschenke drunter zu legen.`nFür diesen Teil des Waldsterbens ist {name} `pverantwortlich.`0',1000,0,0,0,0,0,'',0,0,0,0,0,0,0,10,10,0,0,0,1,1,0,0,0,0,0,0,1,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,1),('xmas_card','`IEine Weihnachtskarte',4,'Eine Weihnachtskarte aus dem Geschenkladen',1,0,0,0,0,0,'',0,1,0,0,0,0,0,0,0,0,0,1,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'xmas_card',NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,0,0,0),('yamba','`4Yamba `^Glockenspiel `&des Monats',4,'{name} hat dir dieses Abo eingebrockt!',500,0,0,0,0,0,'',0,0,0,0,0,0,0,10,10,0,1,0,1,1,1,0,2,0,0,0,1,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,'_codehook_',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'$str_dsc = \'Dieses Mal:`n`^\';\r\nswitch (e_rand(1,35))\r\n{\r\ncase 1:\r\n$str1 = \'Der \';\r\n$str3 = \'Elch!\';\r\nbreak;\r\n\r\ncase 2:\r\n$str1 = \'Die \';\r\n$str3 = \'Ratte!\';\r\nbreak;\r\n\r\ncase 3:\r\n$str1 = \'Das \';\r\n$str3 = \'Kamel!\';\r\nbreak;\r\n\r\ncase 4:\r\n$str1 = \'Der \';\r\n$str3 = \'Biber!\';\r\nbreak;\r\n\r\ncase 5:\r\n$str1 = \'Die \';\r\n$str3 = \'Biene!\';\r\nbreak;\r\n\r\ncase 6:\r\n$str1 = \'Der \';\r\n$str3 = \'Zwerg!\';\r\nbreak;\r\n\r\ncase 7:\r\n$str1 = \'Der \';\r\n$str3 = \'Frosch!\';\r\nbreak;\r\n\r\ncase 8:\r\n$str1 = \'Das \';\r\n$str3 = \'Opossum!\';\r\nbreak;\r\n\r\ncase 9:\r\n$str1 = \'Die \';\r\n$str3 = \'Magen-Darm Grippe!\';\r\nbreak;\r\n\r\ncase 10:\r\n$str1 = \'Das \';\r\n$str3 = \'Frettchen!\';\r\nbreak;\r\n\r\ncase 11:\r\n$str1 = \'Der \';\r\n$str3 = \'Drache!\';\r\nbreak;\r\n\r\ncase 12:\r\n$str1 = \'Die \';\r\n$str3 = \'Kleiderpuppe!\';\r\nbreak;\r\n\r\ncase 13:\r\n$str1 = \'Der \';\r\n$str3 = \'Lurch!\';\r\nbreak;\r\n\r\ncase 14:\r\n$str1 = \'Das \';\r\n$str3 = \'Wiesel!\';\r\nbreak;\r\n\r\ncase 15:\r\n$str1 = \'Die \';\r\n$str3 = \'Bordsteinschwalbe!\';\r\nbreak;\r\n\r\ncase 16:\r\n$str1 = \'Die \';\r\n$str3 = \'Damspülung!\';\r\nbreak;\r\n\r\ncase 17:\r\n$str1 = \'Der \';\r\n$str3 = \'Bierkrug!\';\r\nbreak;\r\n\r\ncase 18:\r\n$str1 = \'Die \';\r\n$str3 = \'Heublume!\';\r\nbreak;\r\n\r\ncase 19:\r\n$str1 = \'Das \';\r\n$str3 = \'Eichhörnchen!\';\r\nbreak;\r\n\r\ncase 20:\r\n$str1 = \'Der \';\r\n$str3 = \'Pudel!\';\r\nbreak;\r\n\r\ncase 21:\r\n$str1 = \'Der \';\r\n$str3 = \'Halbling!\';\r\nbreak;\r\n\r\ncase 22:\r\n$str1 = \'Der \';\r\n$str3 = \'NmuN!\';\r\nbreak;\r\n\r\ncase 23:\r\n$str1 = \'Der \';\r\n$str3 = \'Hundehaufen!\';\r\nbreak;\r\n\r\ncase 24:\r\n$str1 = \'Der \';\r\n$str3 = \'Yuppie!\';\r\nbreak;\r\n\r\ncase 25:\r\n$str1 = \'Der \';\r\n$str3 = \'Bettvorleger!\';\r\nbreak;\r\n\r\ncase 26:\r\n$str1 = \'Das \';\r\n$str3 = \'Küken!\';\r\nbreak;\r\n\r\ncase 27:\r\n$str1 = \'Die \';\r\n$str3 = \'Unterhose!\';\r\nbreak;\r\n\r\ncase 28:\r\n$str1 = \'Der \';\r\n$str3 = \'Zwieback!\';\r\nbreak;\r\n\r\ncase 29:\r\n$str1 = \'Die \';\r\n$str3 = \'Superblondine!\';\r\nbreak;\r\n\r\ncase 30:\r\n$str1 = \'Das \';\r\n$str3 = \'Wiener Würstchen!\';\r\nbreak;\r\n\r\ncase 31:\r\n$str1 = \'Der \';\r\n$str3 = \'Aschenbecher!\';\r\nbreak;\r\n\r\ncase 32:\r\n$str1 = \'Das \';\r\n$str3 = \'Pisa-Opfer!\';\r\nbreak;\r\n\r\ncase 33:\r\n$str1 = \'Die \';\r\n$str3 = \'Melone!\';\r\nbreak;\r\n\r\ncase 34:\r\n$str1 = \'Die \';\r\n$str3 = \'Vampirette!\';\r\nbreak;\r\n\r\ncase 35:\r\n$str1 = \'Der \';\r\n$str3 = \'Teddy!\';\r\nbreak;\r\n}\r\n\r\nswitch (e_rand(1,35))\r\n{\r\ncase 1:\r\n$str2 = \'rülpsende \';\r\nbreak;\r\n\r\ncase 2:\r\n$str2 = \'singende \';\r\nbreak;\r\n\r\ncase 3:\r\n$str2 = \'tanzende \';\r\nbreak;\r\n\r\ncase 4:\r\n$str2 = \'spuckende \';\r\nbreak;\r\n\r\ncase 5:\r\n$str2 = \'kotzende \';\r\nbreak;\r\n\r\ncase 6:\r\n$str2 = \'fliegende \';\r\nbreak;\r\n\r\ncase 7:\r\n$str2 = \'besoffene \';\r\nbreak;\r\n\r\ncase 8:\r\n$str2 = \'hustende \';\r\nbreak;\r\n\r\ncase 9:\r\n$str2 = \'gähnende \';\r\nbreak;\r\n\r\ncase 10:\r\n$str2 = \'kichernde \';\r\nbreak;\r\n\r\ncase 11:\r\n$str2 = \'grunzende \';\r\nbreak;\r\n\r\ncase 12:\r\n$str2 = \'blähende \';\r\nbreak;\r\n\r\ncase 13:\r\n$str2 = \'total abgefahrene\';\r\nbreak;\r\n\r\ncase 14:\r\n$str2 = \'traurige \';\r\nbreak;\r\n\r\ncase 15:\r\n$str2 = \'fröhliche \';\r\nbreak;\r\n\r\ncase 16:\r\n$str2 = \'jubelnde \';\r\nbreak;\r\n\r\ncase 17:\r\n$str2 = \'frivole \';\r\nbreak;\r\n\r\ncase 18:\r\n$str2 = \'rappende \';\r\nbreak;\r\n\r\ncase 19:\r\n$str2 = \'jodelnde \';\r\nbreak;\r\n\r\ncase 20:\r\n$str2 = \'korpulierende \';\r\nbreak;\r\n\r\ncase 21:\r\n$str2 = \'heulende \';\r\nbreak;\r\n\r\ncase 22:\r\n$str2 = \'bekiffte \';\r\nbreak;\r\n\r\ncase 23:\r\n$str2 = \'explodierende \';\r\nbreak;\r\n\r\ncase 24:\r\n$str2 = \'megakrasse \';\r\nbreak;\r\n\r\ncase 25:\r\n$str2 = \'verliebte \';\r\nbreak;\r\n\r\ncase 26:\r\n$str2 = \'lästige \';\r\nbreak;\r\n\r\ncase 27:\r\n$str2 = \'nervtötende \';\r\nbreak;\r\n\r\ncase 28:\r\n$str2 = \'steppende \';\r\nbreak;\r\n\r\ncase 29:\r\n$str2 = \'fiese \';\r\nbreak;\r\n\r\ncase 30:\r\n$str2 = \'verprügelte \';\r\nbreak;\r\n\r\ncase 31:\r\n$str2 = \'verpeilte \';\r\nbreak;\r\n\r\ncase 32:\r\n$str2 = \'dralle \';\r\nbreak;\r\n\r\ncase 33:\r\n$str2 = \'gähnende \';\r\nbreak;\r\n\r\ncase 34:\r\n$str2 = \'obszöne \';\r\nbreak;\r\n\r\ncase 35:\r\n$str2 = \'lüsterne \';\r\nbreak;\r\n}\r\n\r\n$str_dsc.=$str1.$str2.$str3;\r\n\r\n$hook_item[\'tpl_description\'] = $str_dsc.\'`n`n`&\'.$hook_item[\'tpl_description\'];',0,NULL,0,0,1),('yinyang','`~Yin-`&Yang',4,'Ein kleines Yin-Yang Amulett welches einem alten weisen Mann zufolge Gegensätzlichkeiten symbolisiert.',200,0,1,0,0,0,'',0,0,0,0,0,0,0,10,10,0,0,1,1,1,1,0,0,2,0,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'_codehook_',NULL,NULL,NULL,NULL,NULL,0,0,'global $session,$item_hook_info;\r\n\r\nif (e_rand(1,4)<=2)\r\n{\r\noutput(\"`&Neugierig drehst du das Amulett einige Male in deinen Fingern hin und her als es plötzlich zu leuchten beginnt. Es ist ein dunkles Leuchten, die Yin Seite scheint zu überwiegen. `)Stillstand`&, jetzt fällt dir die Bedeutung ein.`nDu verlierst einen Waldkampf!`0\");\r\nif ($session[\'user\'][\'turns\']>0) {\r\n$session[\'user\'][\'turns\']--;\r\n}\r\n} else {\r\noutput(\"`&Neugierig drehst du das Amulett einige Male in deinen Fingern hin und her als es plötzlich zu leuchten beginnt. Es ist ein dunkles Leuchten, die Yin Seite scheint zu überwiegen. `^Bewegung`&, jetzt fällt dir die Bedeutung ein.`nDu bekommst einen Waldkampf!`0\");\r\n$session[\'user\'][\'turns\']++;\r\n}\r\n\r\nitem_delete(\' id=\'.$item[\'id\']);\r\n\r\naddnav(\'Zum Inventar\',$item_hook_info[\'ret\']);\r\n',0,NULL,1,0,1),('zaubergr','`9Großer Zaubertrank',14,'Ein Zaubertrank',1000,0,1,1,0,0,'',0,1,0,0,1,0,0,0,0,0,0,1,1,1,1,0,0,0,0,0,0,0,0,3,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'_codehook_',NULL,'_codehook_',NULL,NULL,NULL,0,0,'global $session,$badguy; \r\n  \r\noutput(\"`9Du trinkst deinen Trank in schnellem Zug.\"); \r\n$session[\'user\'][\'specialtyuses\'][\'magicuses\']+=4; \r\n  \r\nitem_delete(\' id=\'.$item[\'id\']); \r\n  \r\nif (!$badguy[\'creaturehealth\']>0) \r\n{ \r\naddnav(\'Zum Inventar\',$item_hook_info[\'ret\']); \r\n}',0,NULL,0,0,1),('zauberkl','`9Kleiner Zaubertrank',14,'Ein Zaubertrank',250,0,1,1,0,0,'',0,1,0,0,1,0,0,0,0,0,0,1,1,1,1,0,0,0,0,0,0,0,0,3,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'_codehook_',NULL,'_codehook_',NULL,NULL,NULL,0,0,'global $session,$badguy; \r\n  \r\noutput(\"`9Du trinkst deinen Trank in schnellem Zug.\"); \r\n$session[\'user\'][\'specialtyuses\'][\'magicuses\']+=2; \r\n  \r\nitem_delete(\' id=\'.$item[\'id\']); \r\n  \r\nif (!$badguy[\'creaturehealth\']>0) \r\n{ \r\naddnav(\'Zum Inventar\',$item_hook_info[\'ret\']); \r\n}',0,NULL,0,0,1),('zbrdgnsg','`&Trank der Genesung`0',14,'Ein wirklich starkes Gebräu, welches selbst schwere Wunden zu heilen vermag.',2500,2,1,1,0,0,'',0,1,0,0,1,0,0,0,0,0,0,0,1,1,1,0,0,0,0,1,0,0,0,3,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,13,0,'',0,NULL,0,0,1),('zbrtafel','Zaubertafel',3,'Ein Fragment einer mit magischen Zeichen verzierten Steintafel. Allerdings scheint ihr keine Magie mehr inne zu wohnen.',200,1,0,0,0,0,'',0,1,2,1,1,1,0,0,0,0,0,1,1,1,1,0,0,3,0,0,0,1,0,0,0,0,1,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,1,0,1),('zerdolch','Zeremoniendolch',3,'Ein Dolch aus Vulkanglas wie ihn Druiden oft verwenden. Gut erhalten und sicher wertvoll.',0,5,0,0,0,0,'',0,1,1,0,1,0,0,1,1,0,0,1,1,1,1,0,0,3,0,0,0,1,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,1,0,1),('zielsch','Zielscheibe',7,'',1,0,0,0,0,0,'',0,0,0,0,0,0,0,1,1,1,0,1,1,1,0,0,0,2,0,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,'zielscheibe','zielscheibe',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',0,NULL,1,0,1),('zimt','Zimt',25,'Eine Stange Zimt, zum Würzen von Süßspeisen und anderen Dingen.',75,0,0,0,30,0,'',0,1,0,0,1,0,0,0,0,0,0,1,1,1,1,0,0,3,1,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'kitchen',0,0,'',1,NULL,1,0,1),('zmmrpflnze','`2Zim`@me`srp`@fla`2nze',7,'Grün und fast zu groß für eine Zimmerpflanze. Allerdings nicht für Trolle.',500,2,0,0,0,0,'',0,0,0,0,0,0,0,10,10,1,1,0,1,1,0,0,0,2,1,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift',NULL,NULL,0,0,'',0,NULL,1,0,1),('zucchini','Zucchini',25,'Den hellen Punkten auf der Schale und dem Geschmack nach zu urteilen handelt es sich bei Zuccini um eine kranke Gurke.',20,0,0,0,0,0,'',0,1,0,0,2,0,0,0,0,0,0,1,0,1,0,0,0,3,1,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',1,NULL,0,0,0),('zucker','Ein Beutel Zucker',25,'Ein Beutel voll mit kleinen, weißen, süßen Kristallen. Wenn man nicht genau hinsieht, ist Zucker leicht mit Salz zu verwechseln.',10,0,0,0,0,0,'',0,1,0,0,2,0,0,0,0,0,0,1,0,1,0,0,0,3,1,0,0,0,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',1,NULL,0,0,0),('zwgsjck','`vZ`Rw`sangs`rj`va`scke`0',7,'Mit extra langen Ärmeln und sehr robust. Gibt es in den Größen G, O, M und T.',500,2,0,0,0,0,'',0,0,0,0,0,0,0,10,10,0,1,0,1,1,0,0,0,2,1,0,0,0,0,0,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'send_as_gift',NULL,NULL,0,0,'',0,NULL,1,0,1),('zwiebel','Zwiebel',24,'Eine gewöhnliche mittelgroße Zwiebel, oftmals als Gewürz verwendet.',10,0,0,0,0,0,'',0,1,0,0,1,0,0,0,0,0,0,1,1,1,1,0,0,3,0,3,0,1,0,0,0,0,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0,'',1,NULL,1,0,1);
/*!40000 ALTER TABLE `items_tpl` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `keylist`
--

DROP TABLE IF EXISTS `keylist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `keylist` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `owner` int(11) unsigned NOT NULL DEFAULT '0',
  `value1` int(11) NOT NULL DEFAULT '0',
  `value2` int(11) NOT NULL DEFAULT '0',
  `value3` int(11) NOT NULL DEFAULT '0',
  `value4` int(11) NOT NULL DEFAULT '0',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `gold` int(11) NOT NULL DEFAULT '0',
  `gems` int(11) NOT NULL DEFAULT '0',
  `chestlock` tinyint(4) NOT NULL DEFAULT '0',
  `description` text,
  `hvalue` int(11) NOT NULL DEFAULT '0',
  `sort_order` tinyint(4) unsigned DEFAULT '0',
  `house_sort_order` tinyint(4) unsigned DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `owner` (`owner`),
  KEY `value1` (`value1`),
  KEY `hvalue` (`hvalue`),
  KEY `type` (`type`),
  KEY `value2` (`value2`),
  KEY `value3` (`value3`),
  KEY `value4` (`value4`),
  KEY `chestlock` (`chestlock`),
  KEY `sort_order` (`sort_order`),
  KEY `house_sort_order` (`house_sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `keylist`
--

LOCK TABLES `keylist` WRITE;
/*!40000 ALTER TABLE `keylist` DISABLE KEYS */;
/*!40000 ALTER TABLE `keylist` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lib_books`
--

DROP TABLE IF EXISTS `lib_books`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lib_books` (
  `bookid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `themeid` int(10) DEFAULT NULL,
  `acctid` int(10) unsigned NOT NULL DEFAULT '0',
  `author` varchar(60) NOT NULL DEFAULT '',
  `title` varchar(100) DEFAULT NULL,
  `book` text,
  `activated` enum('0','1','2') NOT NULL DEFAULT '0',
  `listorder` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `recommended` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `views` int(10) unsigned NOT NULL DEFAULT '0',
  `seen` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `show_author` tinyint(3) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`bookid`),
  KEY `themeid` (`themeid`),
  KEY `activated` (`activated`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lib_books`
--

LOCK TABLES `lib_books` WRITE;
/*!40000 ALTER TABLE `lib_books` DISABLE KEYS */;
/*!40000 ALTER TABLE `lib_books` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lib_themes`
--

DROP TABLE IF EXISTS `lib_themes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lib_themes` (
  `themeid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `theme` varchar(100) DEFAULT NULL,
  `description` text NOT NULL,
  `listorder` int(10) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`themeid`),
  KEY `listorder` (`listorder`),
  KEY `theme` (`theme`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lib_themes`
--

LOCK TABLES `lib_themes` WRITE;
/*!40000 ALTER TABLE `lib_themes` DISABLE KEYS */;
/*!40000 ALTER TABLE `lib_themes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mail`
--

DROP TABLE IF EXISTS `mail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mail` (
  `messageid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `msgfrom` int(11) unsigned NOT NULL DEFAULT '0',
  `msgto` int(11) unsigned NOT NULL DEFAULT '0',
  `subject` varchar(255) NOT NULL DEFAULT '',
  `body` text NOT NULL,
  `sent` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `seen` tinyint(1) NOT NULL DEFAULT '0',
  `archived` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `ip` varchar(15) NOT NULL DEFAULT '0.0.0.0',
  `crypted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`messageid`),
  KEY `msgto` (`msgto`),
  KEY `seen` (`seen`),
  KEY `ip` (`ip`),
  KEY `archived` (`archived`),
  KEY `crypted` (`crypted`),
  KEY `sent` (`sent`),
  KEY `subject` (`subject`),
  KEY `msgfrom` (`msgfrom`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mail`
--

LOCK TABLES `mail` WRITE;
/*!40000 ALTER TABLE `mail` DISABLE KEYS */;
/*!40000 ALTER TABLE `mail` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `masters`
--

DROP TABLE IF EXISTS `masters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `masters` (
  `creatureid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `creaturename` varchar(50) DEFAULT NULL,
  `creaturelevel` int(11) DEFAULT NULL,
  `creatureweapon` varchar(50) DEFAULT NULL,
  `creaturelose` varchar(120) DEFAULT NULL,
  `creaturewin` varchar(120) DEFAULT NULL,
  `creaturegold` int(11) DEFAULT NULL,
  `creatureexp` int(11) DEFAULT NULL,
  `creaturehealth` int(11) DEFAULT NULL,
  `creatureattack` int(11) DEFAULT NULL,
  `creaturedefense` int(11) DEFAULT NULL,
  PRIMARY KEY (`creatureid`),
  KEY `creaturelevel` (`creaturelevel`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `masters`
--

LOCK TABLES `masters` WRITE;
/*!40000 ALTER TABLE `masters` DISABLE KEYS */;
INSERT INTO `masters` VALUES (1,'Mireraband',1,'Dolch','Gut gemacht %W`&, ich hätte wissen sollen, dass du etwas gewachsen bist.','Wie ich es mir gedacht habe, %w`^, dein Können reicht an das meine nicht heran!',NULL,NULL,11,2,2),(2,'Fie',2,'Kurzschwert','Gut gemacht %W`&, du weisst wirklich etwas anzufangen mit %X.','Du hättest wissen müssen, dass du keine Chance hast gegen mein %X',NULL,NULL,22,4,4),(3,'Glynyc',3,'Riesiger nagelgespickter Streitkolben','Aah! Besiegt von jemandem wie dir! Als nächstes wird mich wohl Mireraband fertigmachen.','Haha, vielleicht solltest du zurück in die Klasse von Mireraband.',NULL,NULL,33,6,6),(4,'Guth',4,'Nagelgespickte Keule','Ha! Hahaha, exzellenter Kampf %W`&!  Hab so einen Kampf schon nicht mehr erlebt, seit ich damals in der Armee war!','Damals in der Armee haben wir deinesgleichen lebend gefrühstückt. Geh an deinen Fertigkeiten arbeiten, alter Knabe',NULL,NULL,44,8,8),(5,'Unélith',5,'Gedankenkontrolle','Dein Geist ist stärker als meiner. Ich gebe auf.','Deine mentalen Kräfte haben noch Schwächen. Meditiere über dein Versagen und du wirst mich eines Tages besiegen',NULL,NULL,55,10,10),(6,'Adwares',6,'Zwergische Kampfaxt','Ach!  Du führst dein %X`& mit Können!','Har! Du brauchst noch viel Übung!',NULL,NULL,66,12,12),(7,'Gerrard',7,'Kampfbogen','Hmm, eventunnel hab ich dich unterschätzt.','Wie ich es mir gedacht habe.',NULL,NULL,77,14,14),(8,'Ceiloth',8,'Orkos Breitschwert','Gut gemacht %W`&, Ich sehe grosse Taten in deiner Zukunft liegen.','Du wirst zwar mächtig, aber nicht so mächtig bis jetzt.',NULL,NULL,88,16,16),(9,'Dwiredan',9,'Zwillingsschwerter','Vielleicht hätte ich etwas nehmen sollen wie dein %X`&...','Vielleicht solltest du mal über ein Zwillingsschwert nachdenken, bevor du es nochmal versuchst.',NULL,NULL,99,18,18),(10,'Sensei Noetha',10,'Asiatische Kampfkünste','Dein Stil war überlegen, deine Verfassung besser. Ich verneige mich vor dir.','Lerne deinen Stil anzupassen und du könntest siegen.',NULL,NULL,110,20,20),(11,'Celith',11,'Geworfene Halos','Wow, wie konntest du all diese Halos austricksen?','Vorsicht vor diesem letzten Halo, er kommt hierher zurück!',NULL,NULL,121,22,22),(12,'Gadriel the Elven Ranger',12,'Elbischer Langbogen','Ich akzeptiere, dass du mich besiegt hast, weil Elfen unsterblich sind, du aber nicht. So ist der Sieg am Ende doch mein','Vergiss nicht, dass Elfen unsterblich sind. Sterbliche werden wahrscheinlich niemals einen von uns besiegen.',NULL,NULL,132,24,24),(13,'Adoawyr',13,'Gargantuan Breitschwert','Wenn ich dieses Schwert hätte aufheben können, wäre ich wahrscheinlich besser gewesen.','Haha, ich konnte nichtmal das Schwert aufheben und hab trotzdem gewonnen!',NULL,NULL,143,26,26),(14,'Yoresh',14,'Tödliche Berührung','Nun, du konntest meiner Berührung ausweichen. Ich gratuliere dir.','Hüte dich das nächste Mal vor meiner Berührung!',NULL,NULL,154,28,28);
/*!40000 ALTER TABLE `masters` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mazes`
--

DROP TABLE IF EXISTS `mazes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mazes` (
  `mazeid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `mazetitle` varchar(100) NOT NULL DEFAULT '',
  `maze` text NOT NULL,
  `mazeauthor` varchar(80) NOT NULL DEFAULT '',
  `mazechance` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `mazegold` smallint(5) unsigned NOT NULL DEFAULT '0',
  `mazegems` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `mazeturns` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`mazeid`),
  KEY `mazechance` (`mazechance`)
) ENGINE=InnoDB AUTO_INCREMENT=87 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mazes`
--

LOCK TABLES `mazes` WRITE;
/*!40000 ALTER TABLE `mazes` DISABLE KEYS */;
INSERT INTO `mazes` VALUES (33,'[OLD] uno','j,d,d,d,b,c,k,o,d,d,k,f,d,b,d,a,n,i,d,b,d,e,i,d,c,k,m,j,d,p,g,o,e,o,b,k,i,k,g,j,n,f,k,g,o,e,g,j,e,i,a,b,a,h,g,j,h,i,h,i,n,g,f,e,j,e,i,d,k,j,s,d,h,p,i,h,g,j,d,h,g,o,b,d,c,d,d,h,i,b,d,c,k,g,j,d,b,d,k,j,c,q,d,e,i,a,d,a,d,e,i,d,d,n,i,r,i,k,i,d,e,o,d,d,b,b,a,k,i,b,d,h,o,d,d,h,m,z,i,d,c,d,n','Alucard',1,3000,2,30),(34,'[OLD] Kain\'s Klub','j,d,b,d,n,g,j,d,k,j,k,f,k,i,d,b,a,a,k,i,e,g,g,i,k,j,h,g,i,a,n,g,m,g,l,i,h,o,c,k,f,d,h,l,i,a,k,j,b,z,g,g,j,d,e,j,h,i,e,r,s,e,i,c,d,e,i,d,k,m,i,b,c,d,d,n,g,j,d,c,d,k,g,j,d,d,b,h,i,k,j,k,g,g,g,o,k,i,k,j,h,g,f,h,g,f,d,c,n,g,i,d,h,g,j,e,i,d,d,d,e,j,d,d,h,g,f,d,k,j,k,g,i,d,d,d,c,c,n,i,h,i,h','Alucard',1,1800,1,18),(35,'[OLD] woof','o,b,k,j,k,f,d,d,b,d,k,j,e,f,c,c,a,d,k,f,k,m,g,g,f,n,l,f,n,f,e,f,k,g,g,i,k,f,a,b,h,i,h,g,i,h,l,f,p,m,f,d,d,n,g,j,b,c,e,i,d,a,b,d,k,g,g,i,b,c,b,k,g,i,d,h,g,i,k,q,r,h,g,i,b,b,k,m,l,i,d,e,j,h,j,e,i,c,k,f,b,q,z,g,j,h,f,b,k,g,g,g,j,s,h,g,j,c,e,g,g,g,i,h,o,k,m,m,j,c,e,g,i,d,d,d,c,d,d,c,d,h,m','Alucard',1,1900,1,19),(36,'[OLD] easy 1','j,d,d,d,b,c,b,b,k,j,k,i,d,d,d,a,d,c,h,i,h,g,j,d,d,n,f,d,d,d,d,d,h,f,d,d,d,a,d,d,d,d,d,k,f,d,d,d,e,j,d,d,d,d,e,f,d,d,d,a,a,d,d,d,d,h,i,d,d,d,a,c,d,d,n,o,k,j,d,d,n,g,j,d,d,d,d,e,i,d,d,d,a,a,b,b,b,b,h,o,d,d,d,e,g,f,e,f,a,n,j,b,d,d,e,g,g,f,a,a,n,f,p,d,d,e,i,e,g,g,f,n,z,d,d,s,h,o,c,c,c,c,n','Alucard',1,1700,1,17),(37,'[OLD] woof woof','j,b,b,k,j,a,b,b,b,d,k,f,a,a,h,g,g,f,c,e,j,e,f,a,e,j,h,g,f,d,h,g,g,f,c,h,g,o,h,i,d,k,g,m,g,j,d,h,j,d,d,k,g,i,k,g,i,d,k,f,d,k,g,g,j,h,f,b,k,g,i,k,g,g,g,i,k,f,e,f,c,k,g,g,g,g,j,h,f,a,h,l,i,h,g,r,g,i,k,f,a,d,a,d,d,h,z,g,j,h,f,a,k,g,j,b,b,e,g,i,k,g,i,e,f,c,a,e,q,m,j,e,m,o,h,i,d,c,h,i,d,h,m','Alucard',1,2700,1,27),(38,'[OLD] eZ','j,d,d,d,d,e,z,d,d,d,k,i,d,d,d,k,m,j,b,d,k,g,j,d,d,k,i,d,h,i,b,p,e,g,j,k,i,d,d,d,d,e,q,g,g,g,i,d,d,d,d,k,f,r,e,g,g,j,d,d,d,k,g,g,q,g,g,g,g,j,d,k,g,g,f,s,e,g,g,g,g,l,g,g,g,g,q,g,g,g,g,g,i,h,g,g,f,p,e,g,g,g,i,d,d,h,g,g,g,g,g,g,i,d,d,d,d,h,f,a,e,g,i,d,d,d,d,d,d,h,g,g,i,d,d,d,d,d,d,d,d,h,m','Alucard',1,4100,2,41),(39,'[OLD] deuce','j,d,b,k,j,a,d,k,j,d,k,i,k,g,f,e,r,j,h,f,d,e,j,c,e,g,s,c,c,b,a,b,h,i,b,s,s,a,z,o,h,i,a,n,j,c,s,s,e,p,j,k,o,a,n,g,j,a,n,g,i,h,g,l,i,k,f,h,i,n,f,d,n,g,f,d,e,i,b,d,q,a,d,n,g,i,d,e,j,h,o,d,e,p,k,f,d,k,g,f,b,b,d,c,n,g,i,n,g,g,g,g,g,o,d,k,f,n,j,h,g,g,i,e,j,k,g,g,j,h,j,e,i,q,i,h,i,c,c,h,o,h,m','Alucard',1,3100,2,31),(40,'[OLD] MegaG','j,b,b,b,b,c,b,b,b,b,k,i,h,g,m,g,j,a,a,a,a,e,j,d,c,n,g,g,g,g,g,g,g,g,j,d,d,h,g,g,g,g,g,g,g,i,d,b,k,g,g,g,g,g,g,g,j,k,g,g,g,g,g,g,g,m,i,h,f,e,g,g,g,g,g,i,k,j,d,h,g,g,g,g,g,i,k,g,g,j,d,h,p,g,g,i,k,g,g,g,g,o,b,e,g,f,k,g,g,g,g,i,d,e,s,r,r,g,g,g,g,g,j,k,i,d,z,s,h,g,g,g,i,h,i,d,d,q,q,d,h,m,m','Alucard',1,2300,1,23),(41,'[OLD] MegaD','j,d,d,d,d,c,d,d,d,d,k,f,d,d,d,d,d,d,d,d,k,g,f,d,d,d,d,d,d,d,d,h,g,f,d,d,d,d,d,d,d,d,k,g,f,d,d,d,d,d,d,d,k,g,g,f,d,d,d,d,d,d,n,g,g,g,f,d,d,d,d,d,n,j,h,m,q,f,d,d,d,d,d,d,h,j,d,z,f,d,d,d,d,d,d,d,h,s,r,f,d,d,d,d,d,d,d,d,h,g,f,d,d,d,d,d,d,d,d,d,h,f,d,d,d,d,d,d,d,d,d,n,i,d,d,d,d,d,d,d,d,d,n','Alucard',1,2400,1,24),(42,'[OLD] Deadend City','o,b,b,b,b,a,b,b,b,b,n,o,a,e,m,m,g,g,m,f,a,n,o,a,e,j,k,g,i,k,f,a,n,o,a,e,g,g,i,k,g,f,a,n,o,a,e,g,i,k,g,g,f,a,n,o,a,e,i,k,g,g,g,f,a,n,o,a,e,l,g,g,g,g,f,a,n,o,a,e,g,g,g,g,g,f,a,n,o,a,e,g,g,g,g,g,f,a,n,o,a,e,f,e,g,g,g,f,a,n,o,a,a,a,a,a,a,q,s,a,n,j,a,a,a,a,a,p,a,a,a,k,m,m,m,m,m,i,h,m,m,r,z','Alucard',1,1700,1,17),(43,'[OLD] Hot','j,b,b,k,j,e,j,b,b,d,k,g,i,p,g,g,g,m,m,i,k,g,i,k,g,g,g,f,d,k,j,h,g,j,h,g,g,g,f,k,i,c,n,g,i,k,f,h,g,g,g,j,d,k,g,j,h,i,k,g,g,g,i,k,i,h,i,k,j,h,g,g,i,k,i,d,k,j,h,i,d,h,g,o,a,d,d,h,i,d,b,d,d,s,d,c,d,b,n,j,q,g,j,n,j,k,j,k,i,k,g,z,g,f,d,h,g,g,g,j,h,g,i,c,c,d,d,h,g,g,i,k,i,d,d,d,d,d,d,h,i,d,h','Alucard',1,4400,2,44),(44,'[OLD] Kain\'s Krypt','j,n,l,j,d,c,k,j,k,o,k,i,b,a,c,b,b,c,h,f,k,g,j,e,f,d,h,i,k,j,h,f,e,m,g,i,k,l,j,c,e,l,g,g,j,c,k,p,f,c,b,c,h,f,h,f,n,f,d,c,d,e,j,d,c,k,i,b,h,o,b,z,i,c,d,k,g,o,a,b,k,f,k,q,d,d,a,h,j,h,p,e,g,i,d,k,l,i,k,i,b,c,c,c,k,j,s,c,k,g,l,g,l,j,d,h,g,g,j,e,m,f,h,f,e,j,d,a,c,e,i,k,i,d,h,i,c,n,i,n,i,n,m','Alucard',1,2200,1,22),(45,'[OLD] dizzy','j,k,j,b,s,g,j,d,d,d,k,g,g,g,g,j,a,h,j,d,k,g,g,g,g,g,g,g,l,g,l,g,g,m,i,h,i,h,g,g,g,i,h,g,j,b,d,b,d,e,i,c,d,d,h,f,a,d,a,k,f,d,d,d,d,k,i,h,q,c,h,g,o,d,d,d,h,j,p,d,d,d,a,d,d,d,d,k,g,j,d,d,k,g,j,d,d,k,g,g,g,j,k,g,g,g,j,k,g,g,g,g,z,g,g,g,g,g,m,g,g,g,i,d,h,g,q,g,i,d,h,g,i,d,d,d,c,c,c,d,d,d,h','Alucard',1,3900,2,39),(46,'[OLD] Kain\'s Korner','j,d,d,d,d,c,d,d,d,d,k,i,d,k,j,p,d,k,j,d,d,h,j,k,g,g,j,d,e,i,d,d,k,g,i,h,g,g,l,i,d,d,d,h,i,d,d,h,g,g,j,d,d,d,k,j,d,b,d,c,c,c,d,b,d,h,g,o,e,j,d,d,d,d,e,j,k,g,o,e,f,d,q,d,d,e,m,g,g,o,e,g,j,b,b,z,f,d,h,i,d,h,g,g,g,f,p,i,d,k,j,d,d,h,g,g,p,g,j,k,g,f,d,d,d,h,s,g,g,g,g,g,i,d,d,d,d,h,i,c,h,i,h','Alucard',1,5200,2,52),(47,'[OLD] Kain\'s Konfusion','j,d,d,k,j,c,d,d,d,d,k,i,d,k,g,g,l,j,d,d,k,g,j,d,e,i,h,i,c,d,k,i,h,g,j,e,p,b,b,d,n,g,j,k,g,g,i,h,g,i,k,j,h,g,g,g,i,k,j,h,j,h,i,d,h,g,g,o,h,g,j,h,j,b,k,j,h,i,k,j,h,i,b,e,z,p,i,k,j,h,g,q,k,s,q,q,e,j,h,g,j,e,z,g,f,d,n,g,i,k,g,g,g,j,h,g,j,d,c,k,g,f,h,g,g,j,e,g,o,d,h,g,i,d,h,i,h,i,c,d,d,d,h','Alucard',1,5300,2,53),(48,'[OLD] jump down turnaround','j,d,d,d,d,c,d,d,d,d,k,i,d,d,d,d,b,d,d,d,d,h,j,d,d,d,d,c,d,d,d,d,k,g,j,b,d,d,d,d,d,d,d,h,g,g,g,j,k,j,k,j,k,j,k,g,g,i,h,i,h,i,h,i,h,g,g,i,d,d,d,d,k,j,d,d,h,i,d,d,d,d,k,g,i,d,d,k,j,b,b,b,b,h,g,j,d,d,h,q,a,a,a,q,j,h,f,b,d,p,f,a,r,a,e,g,j,a,e,j,k,f,a,a,s,s,p,i,c,h,g,g,i,p,c,c,c,d,d,d,d,h,z','Alucard',1,4900,2,49),(49,'[OLD] Into the Vortex','j,b,b,b,b,c,b,b,b,b,k,f,p,m,m,i,p,h,m,m,p,e,f,d,d,d,d,d,d,d,d,d,e,f,n,j,d,d,d,d,d,k,o,e,f,n,g,j,d,d,b,k,g,o,e,f,n,g,g,j,b,p,e,g,o,e,f,n,g,g,g,z,p,e,g,o,e,f,n,g,g,i,d,c,h,g,o,e,f,n,g,i,d,d,d,d,h,o,e,f,n,i,d,d,d,d,d,k,o,e,f,n,p,b,d,d,d,d,h,o,e,f,p,g,g,l,l,l,l,l,p,e,i,c,c,c,c,c,c,c,c,c,h','Alucard',1,7400,4,74),(51,'[OLD] Halls of Konfusion','j,b,d,d,k,f,d,d,k,j,k,g,i,d,q,i,c,d,k,i,h,g,g,j,d,c,k,j,d,h,j,k,g,g,g,j,d,h,p,j,d,e,i,h,g,g,f,d,d,k,i,k,i,d,k,g,g,g,j,d,h,j,h,j,n,g,i,e,g,i,k,o,c,k,g,l,g,j,h,i,k,i,d,k,g,i,a,e,g,l,j,h,o,k,z,i,k,f,e,g,g,s,j,n,i,d,k,g,f,h,f,h,g,i,k,j,d,c,h,i,k,g,o,c,k,g,i,d,d,d,d,e,i,d,n,i,c,d,d,d,d,d,h','Alucard',1,3700,2,37),(52,'[OLD] Twisted Dead End','j,d,d,d,k,g,o,d,d,d,k,g,j,b,n,i,a,d,d,d,d,h,g,g,f,d,k,i,b,b,d,d,k,g,g,f,d,a,n,g,i,d,d,e,g,g,g,l,g,p,a,d,d,n,g,g,g,i,h,g,f,p,o,d,d,e,g,g,o,d,a,h,o,d,d,d,e,g,g,j,k,f,d,d,d,d,k,g,g,g,g,i,a,k,l,j,k,g,g,g,g,p,d,h,f,h,i,c,e,g,g,g,g,j,k,z,s,d,k,g,g,g,f,e,g,g,r,d,k,g,m,g,i,h,i,h,i,d,d,h,i,d,h','Alucard',1,4400,2,44),(53,'[OLD] nothing special','z,b,s,k,j,c,d,d,d,d,k,q,i,d,e,i,d,b,d,d,k,g,i,d,k,g,o,d,c,d,k,g,g,j,k,g,i,k,j,d,d,h,g,g,g,f,e,j,h,i,d,k,j,h,g,g,g,g,i,k,j,d,h,g,j,e,g,g,g,j,h,g,j,b,h,g,g,g,g,g,i,d,c,h,g,j,e,g,i,h,g,j,d,n,j,h,g,i,e,j,d,h,f,d,d,h,o,c,n,g,i,d,k,m,j,d,b,d,b,d,e,j,k,i,d,h,l,g,j,c,s,g,m,i,d,d,d,c,h,i,d,d,h','Alucard',1,3500,2,35),(54,'[OLD] chamber of secrets','j,k,j,b,d,a,b,b,b,n,z,g,g,f,e,j,s,e,m,f,k,g,g,g,f,e,i,d,c,n,s,h,g,g,g,f,r,b,d,d,b,d,k,g,g,g,f,e,g,j,d,a,n,i,e,g,g,f,e,i,h,o,a,b,k,q,g,g,f,e,l,j,k,m,m,f,e,f,a,a,a,a,h,i,d,d,h,q,g,g,f,e,i,d,d,d,d,k,g,g,g,f,e,j,d,d,d,d,h,g,g,g,f,e,i,d,d,d,d,k,g,f,a,c,h,j,d,d,d,d,h,g,i,c,n,o,c,d,d,d,d,d,h','Alucard',1,3300,2,33),(55,'[OLD] Crossroads','j,d,d,d,d,a,d,d,d,d,k,i,d,d,d,d,a,d,d,d,d,e,j,d,d,s,d,a,d,d,d,d,h,f,d,b,b,b,a,b,b,b,b,k,g,j,a,a,a,a,a,a,a,s,e,f,r,c,c,c,c,c,c,c,c,h,f,d,d,d,d,b,d,d,d,d,k,g,j,d,d,d,a,d,d,d,k,g,g,g,j,b,d,a,d,d,k,g,s,g,g,f,p,d,a,d,d,e,f,z,g,g,i,c,d,a,d,d,h,g,s,g,i,d,d,d,c,d,d,d,h,g,i,d,d,d,d,d,d,d,d,d,h','Alucard',1,2400,1,24),(56,'[OLD] So near so far','j,d,d,d,d,c,d,d,b,d,k,g,j,k,j,b,z,q,k,g,l,g,g,g,g,g,i,h,g,f,a,c,h,g,g,i,h,j,r,f,e,i,d,k,g,i,d,d,c,k,g,i,b,d,h,g,j,d,d,d,h,f,p,g,j,k,g,i,d,d,d,b,c,k,i,h,g,g,o,d,d,d,h,j,h,j,k,g,g,j,d,d,d,k,g,j,h,f,e,g,i,d,d,k,f,h,i,k,g,g,i,d,d,d,p,r,b,d,h,g,g,j,d,s,d,c,c,c,d,d,h,g,i,d,d,d,d,d,d,d,d,d,h','Alucard',1,3700,2,37),(57,'[OLD] The damned Hydra','j,d,k,j,k,g,j,d,d,b,k,f,k,i,h,i,c,h,o,d,c,e,g,g,o,b,d,d,k,l,j,d,e,g,i,d,c,d,d,e,i,e,s,e,f,k,o,d,d,d,a,b,a,d,e,g,g,j,d,d,d,a,a,c,n,g,m,g,g,j,k,j,e,g,j,b,h,l,p,m,g,m,g,g,g,g,f,k,g,g,o,c,d,a,q,g,f,e,g,f,a,d,d,k,g,i,a,a,h,m,g,i,d,k,g,f,n,g,g,j,k,i,k,j,h,i,e,s,e,g,m,g,o,h,i,d,n,z,p,h,i,d,h','Alucard',1,2800,1,28),(58,'[OLD] circle2','j,d,d,d,d,a,d,d,d,d,k,f,d,p,b,d,c,d,b,r,d,e,f,d,d,c,n,z,o,c,d,d,e,g,j,d,k,s,c,k,j,d,k,g,g,g,j,h,f,b,h,i,k,g,g,g,g,i,d,h,i,p,d,h,g,g,g,i,d,d,d,b,d,d,d,h,g,f,d,d,d,d,a,d,d,d,d,e,g,l,j,b,k,g,j,d,d,k,g,g,i,h,s,m,f,h,j,k,g,g,g,o,b,h,j,e,j,h,m,g,g,g,o,c,d,h,g,i,d,d,h,g,i,d,d,d,d,c,d,d,d,d,h','Alucard',1,3800,2,38),(59,'[OLD] Many rows','j,d,d,d,z,f,d,d,d,d,k,g,j,d,d,k,f,d,d,d,d,h,g,g,j,k,i,c,b,b,d,d,k,g,g,g,p,d,d,c,a,d,d,h,g,f,e,g,j,b,b,a,b,k,l,f,p,g,g,g,g,g,g,g,g,g,g,q,g,g,g,g,g,g,g,g,g,g,f,h,g,g,g,g,g,g,g,g,g,g,j,h,g,g,g,g,g,g,g,g,g,i,k,g,g,g,g,g,g,g,g,g,j,h,g,g,g,g,g,g,g,g,i,c,k,g,m,m,m,m,m,g,i,d,d,h,i,d,d,d,d,d,h','Alucard',1,4100,2,41),(61,'[OLD] Aris 1','j,b,b,b,k,f,b,b,b,b,k,g,g,i,h,i,a,a,a,c,c,h,g,f,d,k,j,a,e,g,j,n,l,g,i,k,m,f,a,e,g,f,k,g,f,n,g,l,i,c,e,g,g,i,e,g,j,a,a,d,k,i,h,f,k,g,g,g,f,a,b,a,d,b,h,g,g,g,f,a,a,h,i,d,c,d,h,g,g,i,a,a,k,j,n,o,d,d,e,f,k,i,a,h,i,k,j,b,b,e,f,a,z,q,o,d,e,i,c,c,e,m,g,j,d,p,b,c,d,d,b,h,o,h,i,d,d,c,n,o,d,c,n','Alucard',1,1900,1,19),(64,'[OLD] really bastard','j,d,d,d,d,c,d,d,d,d,k,g,j,d,n,o,b,d,d,d,k,g,f,e,j,d,d,c,b,d,k,f,e,g,g,g,l,o,d,c,k,g,g,g,g,g,f,e,j,k,l,g,g,g,g,g,g,g,g,g,s,g,g,g,g,g,g,g,g,g,g,z,g,g,g,m,g,g,g,g,g,g,f,p,g,m,j,e,g,g,f,e,f,c,h,g,l,g,g,g,g,g,i,c,d,d,h,g,g,g,g,g,i,d,d,b,d,d,h,g,g,g,i,b,n,o,c,d,d,d,h,g,i,d,c,d,n,o,d,d,d,d,h','Alucard',1,3000,2,30),(65,'[OLD] many traps','j,b,b,b,b,a,b,b,b,b,k,f,a,a,a,a,a,a,a,a,a,e,f,a,a,a,a,a,a,a,a,a,e,f,a,a,a,a,a,a,a,a,a,e,f,a,a,a,a,a,a,a,a,a,e,f,a,a,a,a,a,a,a,a,a,e,p,a,a,a,r,a,a,a,a,a,e,f,a,a,a,a,q,a,s,a,a,e,f,a,a,r,a,a,a,a,a,p,e,f,a,p,a,a,z,a,a,a,a,e,f,a,a,a,a,a,a,a,a,a,s,f,a,a,a,s,a,a,a,a,a,e,i,c,c,c,c,c,c,c,c,c,h','Alucard',1,1100,0,11),(68,'[OLD] Circle','j,d,d,d,d,a,d,d,d,d,k,g,j,d,d,d,c,d,d,d,k,g,g,g,j,d,q,b,d,d,k,g,g,g,g,g,j,d,c,d,k,g,g,g,g,g,g,g,j,d,k,g,g,g,g,g,g,g,g,f,k,g,g,p,g,g,g,g,g,f,e,z,g,g,f,e,g,g,g,g,p,f,p,e,g,p,g,g,g,g,g,g,i,d,h,f,e,g,g,g,g,g,i,d,d,d,h,g,g,g,g,g,i,d,d,b,d,d,h,g,g,g,i,d,d,d,a,d,d,d,h,g,i,d,d,d,d,c,d,d,d,d,h','Alucard',1,4200,2,42),(69,'[OLD] Attention','j,d,d,z,o,c,b,d,d,d,k,g,j,d,b,b,d,c,d,d,d,e,g,i,b,e,g,j,b,d,d,d,e,g,j,s,c,c,c,c,d,d,d,e,g,g,f,d,d,d,d,d,d,d,e,g,g,f,d,d,d,d,d,d,d,e,g,g,f,d,d,d,d,d,d,d,e,g,g,p,d,d,d,d,d,d,r,e,g,g,f,d,d,d,d,d,d,d,e,g,g,f,d,q,d,d,d,d,d,s,i,c,a,d,d,d,d,d,d,d,e,j,b,a,d,d,d,d,d,d,d,e,i,c,c,d,d,d,d,d,d,d,h','Alucard',1,3800,2,38),(70,'[OLD] Are you Lucky ?','j,b,k,j,k,g,j,b,b,b,r,i,a,a,e,g,g,f,a,a,a,e,j,a,a,e,g,g,f,a,a,q,e,i,a,a,e,g,g,f,a,a,a,e,j,a,a,e,g,g,f,a,a,q,e,i,a,a,e,g,g,f,a,a,q,e,j,a,a,e,g,g,f,a,a,q,e,i,a,a,e,g,g,f,a,a,q,e,j,c,a,e,g,g,f,a,a,q,e,i,b,a,e,g,g,f,a,a,e,g,j,h,i,e,g,g,i,h,g,g,g,g,j,k,p,i,c,d,d,h,p,g,i,h,i,c,n,z,d,d,d,d,h','Alucard',1,3800,2,38),(71,'[OLD] Three white roses','j,d,b,b,b,c,d,d,b,d,k,g,l,g,m,f,b,k,l,i,k,g,g,g,i,k,g,g,g,g,j,h,g,g,g,j,e,g,g,g,f,a,k,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,f,h,g,g,g,g,g,m,g,g,g,g,j,h,g,m,g,g,j,h,m,g,m,i,k,g,j,h,g,g,j,d,e,o,b,h,g,m,j,h,g,m,j,h,j,c,k,g,j,c,k,g,j,c,k,g,z,p,g,g,z,g,g,g,z,g,i,d,h,m,i,d,h,m,i,c,h','Alucard',1,2100,1,21),(72,'[OLD] Three red roses','j,d,b,b,b,c,d,d,b,d,k,g,l,g,m,f,b,k,l,i,k,g,g,g,i,k,g,g,g,g,j,h,g,g,g,j,e,g,g,g,f,a,k,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,f,h,g,g,g,g,g,m,g,g,g,g,j,h,g,m,g,g,j,h,m,g,m,i,k,g,j,h,g,g,j,d,e,o,b,h,g,m,j,h,g,m,j,h,j,c,k,g,j,c,k,g,j,c,k,g,z,g,g,f,z,g,g,g,z,g,i,d,h,m,i,d,h,m,i,p,h','Alucard',1,1700,1,17),(73,'[OLD] Three black roses','j,d,b,b,b,c,d,d,b,d,k,g,l,g,m,f,b,k,l,i,k,g,g,g,i,k,g,g,g,g,j,h,g,g,g,j,e,g,g,g,f,a,k,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,f,h,g,g,g,g,g,m,g,g,g,g,j,h,g,m,g,g,j,h,m,g,m,i,k,g,j,h,g,g,j,d,e,o,b,h,g,m,j,h,g,m,j,h,j,c,k,g,j,c,k,g,j,c,k,g,z,e,g,p,z,g,g,g,z,g,i,d,h,m,i,d,h,m,i,d,h','Alucard',1,2100,1,21),(86,'[OLD] be carefull','j,d,d,d,d,a,d,d,d,d,k,r,j,d,d,k,i,k,j,d,d,h,j,h,j,d,h,s,e,g,o,d,k,g,j,h,j,d,d,e,i,d,k,g,z,g,j,h,j,k,i,d,d,h,g,g,g,i,d,h,i,d,d,d,b,e,g,i,s,b,b,b,b,b,b,a,q,g,p,g,f,e,f,e,f,e,f,e,g,g,f,a,e,f,e,f,e,f,e,g,g,g,f,e,f,e,f,e,f,e,g,g,g,f,e,f,e,f,e,f,e,g,g,g,f,e,f,e,f,e,f,e,i,c,h,i,h,i,h,i,h,i,h','Alucard',1,4300,2,43);
/*!40000 ALTER TABLE `mazes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `motd`
--

DROP TABLE IF EXISTS `motd`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `motd` (
  `motditem` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `motdtitle` varchar(200) DEFAULT NULL,
  `motdbody` text,
  `motddate` datetime DEFAULT NULL,
  `motdtype` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `motdgroup` tinyint(3) unsigned DEFAULT '0',
  `motdauthor` mediumint(8) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`motditem`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `motd`
--

LOCK TABLES `motd` WRITE;
/*!40000 ALTER TABLE `motd` DISABLE KEYS */;
INSERT INTO `motd` VALUES (1,'DS3.5 Installiert!','Yuhu!','2016-02-20 18:17:58',0,0,1);
/*!40000 ALTER TABLE `motd` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `motd_coding`
--

DROP TABLE IF EXISTS `motd_coding`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `motd_coding` (
  `id` int(7) NOT NULL AUTO_INCREMENT COMMENT 'Mit zählende Zahl',
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `type` int(2) NOT NULL DEFAULT '1' COMMENT 'Welche Art von Typ?',
  `headline` text NOT NULL COMMENT 'Überschrift',
  `body` text NOT NULL COMMENT 'Textkörper',
  `body_team` text NOT NULL COMMENT 'Bei public-Einträgen kann hier noch zusätzlich etwas fürs Team vermerkt werden!',
  `acctid` int(7) NOT NULL COMMENT 'Autor',
  `public` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'ob öffentlich...oder nicht',
  PRIMARY KEY (`id`),
  KEY `type` (`type`),
  KEY `public` (`public`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='Coding- MoTD';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `motd_coding`
--

LOCK TABLES `motd_coding` WRITE;
/*!40000 ALTER TABLE `motd_coding` DISABLE KEYS */;
INSERT INTO `motd_coding` VALUES (1,'2016-02-20 17:18:16',2,'DS3.5 Installiert!','Yuhu!','',1,1);
/*!40000 ALTER TABLE `motd_coding` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mounts`
--

DROP TABLE IF EXISTS `mounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mounts` (
  `mountid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `creator` int(10) unsigned DEFAULT '0',
  `mountname` varchar(70) NOT NULL DEFAULT '',
  `mountproduct` varchar(70) NOT NULL DEFAULT '',
  `mountdesc` text,
  `mountcategory` varchar(50) NOT NULL DEFAULT '',
  `mountbuff` text,
  `mountcostgems` smallint(5) unsigned NOT NULL DEFAULT '0',
  `mountcostgold` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `mountactive` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `mountforestfights` tinyint(4) NOT NULL DEFAULT '0',
  `tavern` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `newday` tinytext NOT NULL,
  `recharge` text NOT NULL,
  `partrecharge` text NOT NULL,
  `mine_canenter` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `mine_cansave` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `mine_bag` smallint(6) NOT NULL DEFAULT '0',
  `mindk` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `trainingcost` float unsigned NOT NULL DEFAULT '0',
  `mount_sausage` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `claimed` tinyint(4) DEFAULT '0',
  `createcostgems` smallint(5) NOT NULL DEFAULT '0',
  `createcostgold` mediumint(8) NOT NULL DEFAULT '0',
  PRIMARY KEY (`mountid`),
  KEY `mountactive` (`mountactive`)
) ENGINE=InnoDB AUTO_INCREMENT=76 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mounts`
--

LOCK TABLES `mounts` WRITE;
/*!40000 ALTER TABLE `mounts` DISABLE KEYS */;
INSERT INTO `mounts` VALUES (1,0,'`yP`to`In`}y`0','`yP`to`In`}y`0','Es ist nicht unbedingt ein Schlachtross, außer natürlich man ist ein Halbling, aber noch immer günstig für ein Reittier. Treuer als der Esel und ausgesprochen gutmütig ist es alle Mal.','Reittiere','{\"name\":\"`yP`to`In`}y`0\",\"msg_round\":\"\",\"msg_no_effect\":\"\",\"msg_wearoff\":\"`7Dein Tier zieht sich ersch\\u00f6pft aus dem Kampf zur\\u00fcck.\",\"msg_lifetap_success\":\"\",\"msg_lifetap_fail\":\"\",\"msg_regen_success\":\"\",\"msg_regen_fail\":\"\",\"msg_effect_success\":\"\",\"msg_effect_fail\":\"\",\"rounds\":\"80\",\"atkmod\":\"1.05\",\"defmod\":\"1.05\",\"regen\":\"\",\"minioncount\":\"1\",\"minbadguydamage\":\"\",\"maxbadguydamage\":\"\",\"lifetap\":\"\",\"damageshield\":\"\",\"badguydmgmod\":\"1\",\"badguyatkmod\":\"1\",\"badguydefmod\":\"1\",\"activate\":\"roundstart\"}',10,200,1,1,1,'`wDu steckst dein(e/n) {weapon}`w in die Satteltasche deines Tieres und machst dich auf ins Abenteuer!','','',100,33,1,0,1.14,500,0,0,0),(2,0,'`TW`Ya`tlla`Yc`Th`0','`TW`Ya`tlla`Yc`Th`0','Das typische Arbeitspferd. Kräftig und ausdauernd, auch wenn ein Ritt auf ihm eher weniger bequem ist. Man kommt damit zwar schneller voran als mit einem Pony, aber ein Rennpferd ist es noch lange nicht.','Reittiere','{\"name\":\"`TW`Ya`tlla`Yc`Th`0\",\"msg_round\":\"\",\"msg_no_effect\":\"\",\"msg_wearoff\":\"`7Dein Tier zieht sich ersch\\u00f6pft aus dem Kampf zur\\u00fcck.\",\"msg_lifetap_success\":\"\",\"msg_lifetap_fail\":\"\",\"msg_regen_success\":\"\",\"msg_regen_fail\":\"\",\"msg_effect_success\":\"\",\"msg_effect_fail\":\"\",\"rounds\":\"100\",\"atkmod\":\"1.1\",\"defmod\":\"1.05\",\"regen\":\"\",\"minioncount\":\"1\",\"minbadguydamage\":\"\",\"maxbadguydamage\":\"\",\"lifetap\":\"\",\"damageshield\":\"\",\"badguydmgmod\":\"1\",\"badguyatkmod\":\"1\",\"badguydefmod\":\"1\",\"activate\":\"roundstart\"}',20,400,1,1,1,'`wDu steckst dein(e/n) {weapon}`w in die Satteltasche deines Tieres und machst dich auf ins Abenteuer!','','',100,45,1,0,1.14,300,0,0,0),(3,0,'`SH`;e`Yng`;s`St`0','`SH`;e`Yng`;s`St`0','Beliebt bei Boten und Kurieren ist der Hengst das günstigste, wirkliche Reitpferd. Zwar ist es noch immer kein Schlachtross, aber dafür edel, ausdauernd und schnell.','Reittiere','{\"name\":\"`SH`;e`Yng`;s`St`0\",\"msg_round\":\"\",\"msg_no_effect\":\"\",\"msg_wearoff\":\"`7Dein Tier zieht sich ersch\\u00f6pft aus dem Kampf zur\\u00fcck.\",\"msg_lifetap_success\":\"\",\"msg_lifetap_fail\":\"\",\"msg_regen_success\":\"\",\"msg_regen_fail\":\"\",\"msg_effect_success\":\"\",\"msg_effect_fail\":\"\",\"rounds\":\"120\",\"atkmod\":\"1.15\",\"defmod\":\"1.05\",\"regen\":\"\",\"minioncount\":\"1\",\"minbadguydamage\":\"\",\"maxbadguydamage\":\"\",\"lifetap\":\"\",\"damageshield\":\"\",\"badguydmgmod\":\"1\",\"badguyatkmod\":\"1\",\"badguydefmod\":\"1\",\"activate\":\"roundstart\"}',30,600,1,2,1,'`wDu steckst dein(e/n) {weapon}`w in die Satteltasche deines Tieres und machst dich auf ins Abenteuer!','','',0,0,0,5,1.14,1500,0,0,0),(4,0,'`mH`Uu`un`td`0','`mH`Uu`un`td`0','Der treueste aller Gefährten und noch dazu die passende Größe, um nirgends zu stören. Ja, sogar die meisten Tavernen haben sich auf den Besuch von Abenteurern mit ihren Hunden vorbereitet, ist der Abkomme der Wölfe kaum mehr aus dem Leben vieler Menschen wegzudenken. Ein hervorragender Wachposten, eine ideale Schulter zum Ausheulen oder eine passende Hilfe bei der Jagd: Der Hund darf nicht fehlen.','Haustiere','{\"name\":\"`mH`Uu`un`td`0\",\"msg_round\":\"\",\"msg_no_effect\":\"\",\"msg_wearoff\":\"`wDein Hund zieht sich ersch\\u00f6pft aus dem Kampf zur\\u00fcck!\",\"msg_lifetap_success\":\"\",\"msg_lifetap_fail\":\"\",\"msg_regen_success\":\"\",\"msg_regen_fail\":\"\",\"msg_effect_success\":\"`wDein Hund trifft deinen Gegner mit {damage} Schadenspunkten!\",\"msg_effect_fail\":\"\",\"rounds\":\"40\",\"atkmod\":\"1\",\"defmod\":\"1\",\"regen\":\"\",\"minioncount\":\"1\",\"minbadguydamage\":\"3\",\"maxbadguydamage\":\"8\",\"lifetap\":\"\",\"damageshield\":\"\",\"badguydmgmod\":\"1\",\"badguyatkmod\":\"1\",\"badguydefmod\":\"1\",\"activate\":\"offense\"}',0,300,1,0,0,'`wSchwanzwedelnd sitzt dein Hund vor dir und wartet schon auf neue Abenteuer.','','',100,50,0,0,1.14,50,0,0,0),(5,0,'`(M`)a`eu`ss`0','`(M`)ä`eu`sse`0','Ein verhasster Feind der Taverneninhaber oder Zimmervermieter aller Schichten: Mäuse kommen überall hin oder durch und nagen alles an, was einem lieb ist. Gerade so groß, das ein jeder sie mit der Hand umschließen kann, versetzt sie manchmal ganze Häuser in Panik oder Wut. Gefräßige Haustiere wie diese können einem Mann helfen, schnell unliebsame Begegnungen mit Frauen zu unterbinden oder die ehemalige Frau zu verjagen. Am besten geeignet ist eine Maus jedoch zum Herumtragen und Kuscheln. Außerdem gibt es sie in verschiedenen Farben wie schwarz, weiß, braun oder grau.','Haustiere','{\"name\":\"`(M`)a`eu`ss`0\",\"msg_round\":\"\",\"msg_no_effect\":\"\",\"msg_wearoff\":\"`wDeine Maus verkr\\u00fcmelt sich irgendwo in deiner R\\u00fcstung...\",\"msg_lifetap_success\":\"\",\"msg_lifetap_fail\":\"\",\"msg_regen_success\":\"\",\"msg_regen_fail\":\"\",\"msg_effect_success\":\"`wDeine Maus trifft deinen Gegner mit {damage} Schadenspunkten!\",\"msg_effect_fail\":\"\",\"rounds\":\"15\",\"atkmod\":\"1\",\"defmod\":\"1\",\"regen\":\"\",\"minioncount\":\"1\",\"minbadguydamage\":\"1\",\"maxbadguydamage\":\"2\",\"lifetap\":\"\",\"damageshield\":\"\",\"badguydmgmod\":\"1\",\"badguyatkmod\":\"1\",\"badguydefmod\":\"1\",\"activate\":\"offense\"}',0,10,1,0,0,'`wMit einem Stück Käse kommt deine Maus aus einem Loch gekrochen und verspeist dieses, ehe sie aufgeregt piepst.','','',100,0,0,0,1.14,2,0,0,0),(6,0,'`NSc`(hw`)arzer Dr`(ac`Nhe`0','`NSc`(hw`)arzer Dr`(ac`Nhen`0','Wahrscheinlich einer der am gefährlichsten wirkenden Drachlinge die ich im Angebot habe und mit ihren 6 Metern Spannweits auch die größten noch zähmbaren. Sie besitzen keinen gefährlichen Odem, sind aber ungewöhnlich geschickt im Kampf und ihre langen messerscharfen Zähne sind in allen Reichen gefürchtet. Die Farbe ihrer Schuppen reicht von hellem grau, bis hin zu tiefstem Schwarz.','Drachlinge','{\"name\":\"`NSc`(hw`)arzer Dr`(ac`Nhe`0\",\"msg_round\":\"\",\"msg_no_effect\":\"\",\"msg_wearoff\":\"`wDein Schwarzer Drache zieht sich ersch\\u00f6pft aus dem Kampf zur\\u00fcck!\",\"msg_lifetap_success\":\"\",\"msg_lifetap_fail\":\"\",\"msg_regen_success\":\"\",\"msg_regen_fail\":\"\",\"msg_effect_success\":\"`wDein Schwarzer Drache trifft deinen Gegner mit {damage} Schadenspunkten!\",\"msg_effect_fail\":\"\",\"rounds\":\"200\",\"atkmod\":\"1.2\",\"defmod\":\"1.2\",\"regen\":\"\",\"minioncount\":\"1\",\"minbadguydamage\":\"23\",\"maxbadguydamage\":\"30\",\"lifetap\":\"\",\"damageshield\":\"0\",\"badguydmgmod\":\"1\",\"badguyatkmod\":\"1\",\"badguydefmod\":\"1\",\"activate\":\"offense\"}',90,25000,1,0,0,'`wDer Schwanz deines schwarzen Drachen peitscht ungeduldig und energisch auf den Boden. Er will endlich kämpfen und natürlich fressen.','','',0,0,0,15,1.14,2500,0,0,0),(7,0,'`SFl`Nöh`Se`0','`7F`&l`7o`&h`0','Jeder kennt sie oder hat zumindest von ihnen gehört: Flöhe. Bei Abenteurern, die Gefährten mit Fell besitzen oder sogar selbst welches tragen, sind sie verhasst, bei den Feinden dieser Partei umso beliebter. Die kaum mehr größer als 1,5 mm großen Flöhe sind ein Muss für jeden, der sich dauerhaft Tiere vom Leibe halten oder andere zur Weißglut bringen will, da sie auch auf einer Distanz von 1 m noch ihr Ziel erreichen.','Haustiere','{\"name\":\"`7F`\\u0026l`7\\u00f6`\\u0026h`7e`0\",\"msg_round\":\"\",\"msg_no_effect\":\"\",\"msg_wearoff\":\"`wDeine Fl\\u00f6he sind satt!\",\"msg_lifetap_success\":\"\",\"msg_lifetap_fail\":\"\",\"msg_regen_success\":\"\",\"msg_regen_fail\":\"\",\"msg_effect_success\":\"\",\"msg_effect_fail\":\"\",\"rounds\":\"10\",\"atkmod\":\"1\",\"defmod\":\"1\",\"regen\":\"\",\"minioncount\":\"1\",\"minbadguydamage\":\"\",\"maxbadguydamage\":\"\",\"lifetap\":\"\",\"damageshield\":\"\",\"badguydmgmod\":\"1\",\"badguyatkmod\":\"0.9\",\"badguydefmod\":\"0.9\",\"activate\":\"roundstart\"}',1,100,1,0,0,'`wDu wachst morgens auf und musst dich überall kratzen. Sofort weißt du, dass deine Flöhe schon lange nicht mehr schlafen.','','',100,0,0,0,1.14,1,0,0,0),(8,0,'`*E`&i`snho`&r`*n`0','`*E`&i`snho`&r`*n`0','Jeder kennt es, noch mehr wollen es: Das Einhorn. Von edlem Gemüt und ruhiger Natur ist dieses weise Geschöpf der Traum vieler Frauen, aber auch Männer. Sagenumwoben ist das Horn dieser Tiere, das auf der Stirn ihres Hauptes thront. Etwas kleiner als ein gewöhnliches Reitpferd, ist das Einhorn diesem sehr ähnlich, doch gespaltene Hufe und bei männlichen Einhörnern ein Bärtchen am Kinn unterscheiden das Einhorn deutlich von den anderen Pferden, vor allem aber, da sein Horn eine heilende Wirkung besitzt.','Reittiere','{\"name\":\"`*E`\\u0026i`snho`\\u0026r`*n`0\",\"msg_round\":\"\",\"msg_no_effect\":\"\",\"msg_wearoff\":\"`wDein Einhorn zieht sich ersch\\u00f6pft zur\\u00fcck!\",\"msg_lifetap_success\":\"\",\"msg_lifetap_fail\":\"\",\"msg_regen_success\":\"\",\"msg_regen_fail\":\"\",\"msg_effect_success\":\"`wDie reine Aura deines Einhorns l\\u00e4sst dich {damage} Lebenspunkte regenerieren!\",\"msg_effect_fail\":\"\",\"rounds\":\"200\",\"atkmod\":\"1.1\",\"defmod\":\"1\",\"regen\":\"\",\"minioncount\":\"1\",\"minbadguydamage\":\"\",\"maxbadguydamage\":\"\",\"lifetap\":\"0.5\",\"damageshield\":\"\",\"badguydmgmod\":\"1\",\"badguyatkmod\":\"1\",\"badguydefmod\":\"1\",\"activate\":\"roundstart\"}',75,10000,1,2,1,'`wDu steckst dein(e/n) {weapon}`w in die Satteltasche deines Tieres und machst dich auf ins Abenteuer!','','',0,0,0,10,1.14,10000,0,0,0),(9,0,'`NSc`Wha`Mtt`5en`Mdr`Wac`Nhe`0','`NSc`Wha`Mtt`5en`Mdr`Wac`Nhen`0','Rauch und Nebel. Keine greifbare Form und nichts als schemenhafte Erscheinungen um ihre zornigen und rot glühenden Augen. Eigentlich sind Schattendrachen keine wirklichen Drachen, sondern eine Art Dämon aus einer anderen Welt jenseits unserer Vorstellungskraft. Erst kurz vor ihrem todbringenden Biss, nehmen sie die feste Gestalt eines über 6 Meter langen, gewaltig wirkenden schwarzen Drachen ein.','Drachlinge','{\"name\":\"`NSc`Wha`Mtt`5en`Mdr`Wac`Nhe`0\",\"msg_round\":\"\",\"msg_no_effect\":\"\",\"msg_wearoff\":\"`wDein Schattendrache zieht sich ersch\\u00f6pft aus dem Kampf zur\\u00fcck!\",\"msg_lifetap_success\":\"\",\"msg_lifetap_fail\":\"\",\"msg_regen_success\":\"\",\"msg_regen_fail\":\"\",\"msg_effect_success\":\"`wDein Schattendrache trifft deinen Gegner mit {damage} Schadenspunkten!\",\"msg_effect_fail\":\"\",\"rounds\":\"100\",\"atkmod\":\"1.2\",\"defmod\":\"1.1\",\"regen\":\"\",\"minioncount\":\"1\",\"minbadguydamage\":\"20\",\"maxbadguydamage\":\"60\",\"lifetap\":\"\",\"damageshield\":\"\",\"badguydmgmod\":\"1\",\"badguyatkmod\":\"1\",\"badguydefmod\":\"0.9\",\"activate\":\"offense\"}',125,250000,1,0,0,'`wSchon als du deinen Schlafplatz verlässt, merkst du, dass du nicht mehr allein bist. Dein Drache folgt dir in den Schatten und nur seine glühenden Augen kannst du sehen.','','',0,0,0,30,1.14,5000,0,0,0),(10,0,'`SSc`Nhw`(e`)r`ee`&s K`ea`)m`(pf`Nro`Sss`0','`&K`ea`)m`(pf`Nro`Sss`0','Genau das richtige Reittier für die großen Krieger unter euch. Seine Ausdauer ist nahezu unübertrefflich und seine Ausbildung bringt euch gute Vorteile in jedem Kampf.','Reittiere','{\"name\":\"`SSc`Nhw`(e`)r`ee`\\u0026s K`ea`)m`(pf`Nro`Sss`0\",\"msg_round\":\"\",\"msg_no_effect\":\"\",\"msg_wearoff\":\"`7Dein Tier zieht sich ersch\\u00f6pft aus dem Kampf zur\\u00fcck.\",\"msg_lifetap_success\":\"\",\"msg_lifetap_fail\":\"\",\"msg_regen_success\":\"\",\"msg_regen_fail\":\"\",\"msg_effect_success\":\"\",\"msg_effect_fail\":\"\",\"rounds\":\"200\",\"atkmod\":\"1.15\",\"defmod\":\"1.1\",\"regen\":\"\",\"minioncount\":\"1\",\"minbadguydamage\":\"\",\"maxbadguydamage\":\"\",\"lifetap\":\"\",\"damageshield\":\"\",\"badguydmgmod\":\"1\",\"badguyatkmod\":\"1\",\"badguydefmod\":\"1\",\"activate\":\"roundstart\"}',50,1000,1,2,1,'`wDu steckst dein(e/n) {weapon}`w in die Satteltasche deines Tieres und machst dich auf ins Abenteuer!','','',0,0,0,5,1.14,1500,0,0,0),(11,0,'`&S`sp`eect`se`&r`0','`&S`sp`eect`se`&r`0','Geisterhafter Schemen aus den Tiefen des verlassenen Schlosses herauf beschworen. Sie lassen sich nicht lange in unserer Welt halten, wodurch ihre Kräfte schnell schwinden. Trotzdem ist allein ihr Anblick bereits für viele Gegner Grund genug, das Weite zu suchen.','Geister und Dämonen','{\"name\":\"`\\u0026S`sp`eect`se`\\u0026r`0\",\"msg_round\":\"\",\"msg_no_effect\":\"\",\"msg_wearoff\":\"`wDein Specter zieht sich zur\\u00fcck in seine eigene Sph\\u00e4re!\",\"msg_lifetap_success\":\"\",\"msg_lifetap_fail\":\"\",\"msg_regen_success\":\"\",\"msg_regen_fail\":\"\",\"msg_effect_success\":\"\",\"msg_effect_fail\":\"\",\"rounds\":\"45\",\"atkmod\":\"1\",\"defmod\":\"0.8\",\"regen\":\"\",\"minioncount\":\"1\",\"minbadguydamage\":\"\",\"maxbadguydamage\":\"\",\"lifetap\":\"\",\"damageshield\":\"\",\"badguydmgmod\":\"1\",\"badguyatkmod\":\"0.8\",\"badguydefmod\":\"0.9\",\"activate\":\"roundstart\"}',10,100,1,-1,0,'`wVom Schloss her kommt dein Specter auf dich zu. Wie viele Wesen er heute schon mit seinem Anblick verschreckt hat?','','',0,0,0,0,1.14,1,0,0,0),(12,0,'`4P`$h`Do`Qe`qn`^i`&x`0','`4P`$h`qo`6e`^n`7i`&x`0','Ein unglaublicher Vogel. Nicht nur schön anzusehen, wegen seines leuchtenden Gefieders, sondern auch unsterblich. Wenn seine Zeit gekommen ist, geht er in Flammen auf und aus seiner Asche steigt ein neuer, kleiner Phoenix. Und Vorsicht vor dem Schnabel. Der ist schärfer, als man glaubt.','Magische Wesen','{\"name\":\"`4P`$h`qo`6e`^n`7i`\\u0026x`0\",\"msg_round\":\"\",\"msg_no_effect\":\"\",\"msg_wearoff\":\"`w Dein Phoenix zieht sich ersch\\u00f6pft zur\\u00fcck!\",\"msg_lifetap_success\":\"\",\"msg_lifetap_fail\":\"\",\"msg_regen_success\":\"\",\"msg_regen_fail\":\"\",\"msg_effect_success\":\"`wDu regenerierst {damage} Lebenspunkte!\",\"msg_effect_fail\":\"\",\"rounds\":\"110\",\"atkmod\":\"1\",\"defmod\":\"1\",\"regen\":\"5\",\"minioncount\":\"1\",\"minbadguydamage\":\"\",\"maxbadguydamage\":\"\",\"lifetap\":\"\",\"damageshield\":\"\",\"badguydmgmod\":\"1\",\"badguyatkmod\":\"1\",\"badguydefmod\":\"1\",\"activate\":\"roundstart\"}',50,8000,1,0,0,'`wAnmutig schwebt dein Phoenix an dir vorbei, als du dich schon auf den Weg in den Wald machst. Beruhigt stellst du fest, dass der Vogel dir auch heute wieder seine treuen Dienste zur Verfügung stellt.','','',0,0,0,10,1.14,50,0,0,0),(13,0,'`AN`,a`mc`Nhtm`ma`,h`Ar`0','`AN`,a`mc`Nhtm`ma`,h`Ar`0','Ein finsteres, schwarzes Pferd mit einem ebenso schwarzen Horn. Sehr temperamentvoll wenn man nicht Acht gibt und nicht ungefährlich. Nachtmahre sind schwer zu zähmen und spöttischer als ein Geier, aber es wird mit einer grausamen Mordslust an deiner Seite kämpfen, wenn es merkt, dass du weißt, wie man kämpft. ','Reittiere','{\"name\":\"`AN`,a`mc`Nhtm`ma`,h`Ar`0\",\"msg_round\":\"\",\"msg_no_effect\":\"\",\"msg_wearoff\":\"`wDein Nachtmahr zieht sich ersch\\u00f6pft zur\\u00fcck!\",\"msg_lifetap_success\":\"\",\"msg_lifetap_fail\":\"\",\"msg_regen_success\":\"\",\"msg_regen_fail\":\"\",\"msg_effect_success\":\"`wDie finstere Aura deines Nachtmahrs l\\u00e4sst dich {damage} Lebenspunkte regenerieren!\",\"msg_effect_fail\":\"\",\"rounds\":\"200\",\"atkmod\":\"1.2\",\"defmod\":\"1\",\"regen\":\"\",\"minioncount\":\"1\",\"minbadguydamage\":\"\",\"maxbadguydamage\":\"\",\"lifetap\":\"0.2\",\"damageshield\":\"\",\"badguydmgmod\":\"1\",\"badguyatkmod\":\"1\",\"badguydefmod\":\"1\",\"activate\":\"roundstart\"}',60,10000,1,2,1,'`wDu steckst dein(e/n) {weapon}`w in die Satteltasche deines Tieres und machst dich auf ins Abenteuer!','','',0,0,0,10,1.14,500,0,0,0),(14,0,'`]W`:y`Lv`:e`]r`0','`]W`:y`Lv`:e`]rn`0','Eine gut 3 Meter lange, schlangenähnliche Kreatur. Knapp einen Meter unterhalb des Kopfes, der mehr dem eines Drachen ähnelt, besitzt die Wyver kleine, kaum brauchbare Flügel, welche meist eng an ihren schlanken Körper angelegt sind.','Drachlinge','{\"name\":\"`]W`:y`Lv`:e`]r`0\",\"msg_round\":\"\",\"msg_no_effect\":\"\",\"msg_wearoff\":\"`wDeine Wyver zieht sich ersch\\u00f6pft aus dem Kampf zur\\u00fcck!\",\"msg_lifetap_success\":\"\",\"msg_lifetap_fail\":\"\",\"msg_regen_success\":\"\",\"msg_regen_fail\":\"\",\"msg_effect_success\":\"`wDeine Wyver trifft deinen Gegner mit {damage} Schadenspunkten!\",\"msg_effect_fail\":\"\",\"rounds\":\"110\",\"atkmod\":\"1\",\"defmod\":\"1\",\"regen\":\"\",\"minioncount\":\"1\",\"minbadguydamage\":\"2\",\"maxbadguydamage\":\"12\",\"lifetap\":\"\",\"damageshield\":\"\",\"badguydmgmod\":\"1\",\"badguyatkmod\":\"1\",\"badguydefmod\":\"1\",\"activate\":\"offense\"}',10,1000,1,0,0,'`wMühsam schlängelt sich deine Wyver aus ihrem Erdloch und versucht, mit ihren verkümmerten Flügeln zu schlagen. Sie ist bereit für die Jagd.','','',0,0,0,5,1.14,100,0,0,0),(15,0,'`NF`Sa`Yl`tk`/e`0','`NF`Sa`Yl`tk`/en`0','Prächtig und stolz ist dieser kleine Greifvogel mit den langen Schwanzfedern und spitzen Flügeln. Schnell wie kein anderer durchzieht der Falke den Himmel; und noch schneller ist seine Beute ergriffen und getötet. Da dieser Greifvogel bis zu 50 Jahre alt werden kann, ist er ein passender Begleiter für Menschen, die an ihren Gefährten hängen, zudem ist auch der Falke sehr anhänglich und ein treuer Begleiter in jeder Umgebung.','Wildtiere','{\"name\":\"`NF`Sa`Yl`tk`\\/e`0\",\"msg_round\":\"\",\"msg_no_effect\":\"\",\"msg_wearoff\":\"`wDein Falke zieht sich ersch\\u00f6pft aus dem Kampf zur\\u00fcck!\",\"msg_lifetap_success\":\"\",\"msg_lifetap_fail\":\"\",\"msg_regen_success\":\"\",\"msg_regen_fail\":\"\",\"msg_effect_success\":\"`wDein Falke trifft deinen Gegner mit {damage} Schadenspunkten!\",\"msg_effect_fail\":\"\",\"rounds\":\"50\",\"atkmod\":\"1\",\"defmod\":\"1\",\"regen\":\"\",\"minioncount\":\"2\",\"minbadguydamage\":\"1\",\"maxbadguydamage\":\"3\",\"lifetap\":\"\",\"damageshield\":\"\",\"badguydmgmod\":\"1\",\"badguyatkmod\":\"1\",\"badguydefmod\":\"1\",\"activate\":\"offense\"}',0,800,1,0,0,'`wDein Falke zieht schon eine Weile über dir seine Kreise und wartet auf neue Abenteuer.','','',100,0,1,0,1.14,100,0,0,0),(16,0,'`8Sk`eel`)ettkri`eeg`8er`0','`8Sk`eel`)ettkri`eeg`8er`0','Mit Hilfe einer kurzen Zauberformel aus einer Handvoll alter Knochen beschworen, folgt euch der Skelettkrieger ohne zu fragen bis in den Tod. Sehr flink, aber dafür mit nicht einmal dem gerinsten Maß an Intelligenz gesegnet, sind sie nicht unbedingt die besten, aber dafür relativ günstige Gefährten.','Geister und Dämonen','{\"name\":\"`8Sk`eel`)ettkri`eeg`8er`0\",\"msg_round\":\"\",\"msg_no_effect\":\"\",\"msg_wearoff\":\"`wDein Skelettkrieger zerf\\u00e4llt in etwa 200 Teile!\",\"msg_lifetap_success\":\"\",\"msg_lifetap_fail\":\"\",\"msg_regen_success\":\"\",\"msg_regen_fail\":\"\",\"msg_effect_success\":\"`wDein Skelettkrieger trifft deinen Gegner mit {damage} Schadenspunkten!\",\"msg_effect_fail\":\"\",\"rounds\":\"80\",\"atkmod\":\"1\",\"defmod\":\"1\",\"regen\":\"\",\"minioncount\":\"2\",\"minbadguydamage\":\"1\",\"maxbadguydamage\":\"5\",\"lifetap\":\"\",\"damageshield\":\"\",\"badguydmgmod\":\"1\",\"badguyatkmod\":\"0.95\",\"badguydefmod\":\"1\",\"activate\":\"offense\"}',0,2000,1,-1,0,'`wVon draußen hörst du das Knacken von Knochen und siehst kurz darauf, wie dein Skelettkrieger mit seinem Kopf murmelt. Es ist an der Zeit, mit ihm in den Wald kämpfen zu gehen.','','',0,0,0,0,1.14,5,0,0,0),(20,0,'`1C`!e`9r`3a`#p`&t`!e`1r`0','`1C`!e`9r`3a`#p`&t`!e`1r`0','Kaum größer als ein einfaches Pony ist ein Cerapter der ideale Ersatz für einen Pegasus, wenn der Besitzer zu klein für ein solch großes und edles Tier ist. Gutmütig und stets friedlich ist es der hervorragende Gefährte für Abenteurer mit schwachen Nerven, aber auch für Zwerge sind sie gut geeignet, da die Größe hier gut angepasst ist. Es sollte jedoch auch berücksichtigt werden, das auch Cerapter gerne einen Dickkopf haben; wie viele Ponys.','Reittiere','{\"name\":\"`1C`!e`9r`3a`#p`\\u0026t`!e`1r`0\",\"msg_round\":\"\",\"msg_no_effect\":\"\",\"msg_wearoff\":\"`wDein Cerapter zieht sich ersch\\u00f6pft aus dem Kampf zur\\u00fcck.\",\"msg_lifetap_success\":\"\",\"msg_lifetap_fail\":\"\",\"msg_regen_success\":\"\",\"msg_regen_fail\":\"\",\"msg_effect_success\":\"`wDurch die reine Aura deines Cerapters regenerierst du {damage} Lebenspunkte!\",\"msg_effect_fail\":\"\",\"rounds\":\"200\",\"atkmod\":\"1.2\",\"defmod\":\"1.1\",\"regen\":\"\",\"minioncount\":\"1\",\"minbadguydamage\":\"\",\"maxbadguydamage\":\"\",\"lifetap\":\"0.2\",\"damageshield\":\"\",\"badguydmgmod\":\"1\",\"badguyatkmod\":\"1\",\"badguydefmod\":\"1\",\"activate\":\"roundstart\"}',75,5000,1,2,1,'`wDu steckst dein(e/n) {weapon}`w in die Satteltasche deines Tieres und machst dich auf ins Abenteuer!','','',0,0,0,10,1.14,500,0,0,0),(21,0,'`UK`ua`/t`uz`Ue`0','`UK`ua`/t`uz`Uen`0','Alle kennen sie, alle lieben sie. Das treue Haustier, das beständig um die Beine streift und quälend miaut, immer auf das Essen erpicht, was der Besitzer grad zu sich nimmt. Alle Variationen, von fleckig über gestreift bis hin zu einfarbig, sind bei der Katze vertreten. Sie reicht kaum bis über die Hälfte der Wade und dennoch hat das intelligente Haustier den Besitzer schnell eingelullt. Eine Katze ist ein Muss für die, die ein Problem mit Mäusen haben, oder es erst gar nicht kriegen wollen.','Haustiere','{\"name\":\"`UK`ua`\\/t`uz`Ue`0\",\"msg_round\":\"\",\"msg_no_effect\":\"\",\"msg_wearoff\":\"`wDeine Katze zieht sich ersch\\u00f6pft aus dem Kampf zur\\u00fcck!\",\"msg_lifetap_success\":\"\",\"msg_lifetap_fail\":\"\",\"msg_regen_success\":\"\",\"msg_regen_fail\":\"\",\"msg_effect_success\":\"`wDeine Katze trifft deinen Gegner mit {damage} Schadenspunkten!\",\"msg_effect_fail\":\"\",\"rounds\":\"25\",\"atkmod\":\"1\",\"defmod\":\"1\",\"regen\":\"\",\"minioncount\":\"1\",\"minbadguydamage\":\"2\",\"maxbadguydamage\":\"4\",\"lifetap\":\"\",\"damageshield\":\"\",\"badguydmgmod\":\"1\",\"badguyatkmod\":\"1\",\"badguydefmod\":\"1\",\"activate\":\"offense\"}',0,150,1,0,0,'`wMit einem erwartungsvollen Schnurren streicht deine Katze um deine Beine herum.','','',100,0,0,0,1.14,25,0,0,0),(23,0,'`SH`Ti`Yp`}p`/og`}r`Yy`Tp`Sh`0','`SH`Ti`Yp`}p`/og`}r`Yy`Tp`Sh`0','Im ersten Augenblick denkt man, einen Greifen vor sich zu haben, aber kaum ist der Moment vorüber, fallen die Unterschiede auf. Vogelähnliche Klauen bilden seine Vorderbeine, während Hufen und Pferdebeine seine Hinterhand bilden. Der Schweif glänzt verführerisch ordentlich, so wie der Rest des weichen Felles. Sehr abschätzend wirkt die Kreatur, die jeden aus ihren Falkenaugen heraus genau anblickt und stets ruhig mit den Flügeln, die denen der Falken ähneln, raschelt.','Reittiere','{\"name\":\"`SH`Ti`Yp`}p`\\/og`}r`Yy`Tp`Sh`0\",\"msg_round\":\"\",\"msg_no_effect\":\"\",\"msg_wearoff\":\"`7Dein Tier zieht sich ersch\\u00f6pft aus dem Kampf zur\\u00fcck.\",\"msg_lifetap_success\":\"\",\"msg_lifetap_fail\":\"\",\"msg_regen_success\":\"\",\"msg_regen_fail\":\"\",\"msg_effect_success\":\"\",\"msg_effect_fail\":\"\",\"rounds\":\"120\",\"atkmod\":\"1.1\",\"defmod\":\"1.1\",\"regen\":\"\",\"minioncount\":\"1\",\"minbadguydamage\":\"\",\"maxbadguydamage\":\"\",\"lifetap\":\"\",\"damageshield\":\"\",\"badguydmgmod\":\"1\",\"badguyatkmod\":\"1\",\"badguydefmod\":\"1\",\"activate\":\"roundstart\"}',40,2500,1,1,1,'`wDu steckst dein(e/n) {weapon}`w in die Satteltasche deines Tieres und machst dich auf ins Abenteuer!','','',0,0,0,5,1.14,250,0,0,0),(24,0,'`NW`(ol`)f`0','`NW`(ol`)fs`0','Ein Rudeltier mit beißend hellen Augen, in denen ständig Skepsis steht. Man fand ihn, als er noch klein war und dressierte ihn ordentlich, doch ob es geholfen hat, wissen wir noch nicht so wirklich! Ein dennoch prachtvolles Jagdtier, nicht?','Wildtiere','{\"name\":\"`NW`(ol`)f`0\",\"msg_round\":\"\",\"msg_no_effect\":\"\",\"msg_wearoff\":\"`wDein Wolf zieht sich ersch\\u00f6pft aus dem Kampf zur\\u00fcck!\",\"msg_lifetap_success\":\"\",\"msg_lifetap_fail\":\"\",\"msg_regen_success\":\"\",\"msg_regen_fail\":\"\",\"msg_effect_success\":\"`wDein Wolf trifft deinen Gegner mit {damage} Schadenspunkten!\",\"msg_effect_fail\":\"\",\"rounds\":\"80\",\"atkmod\":\"1\",\"defmod\":\"1\",\"regen\":\"\",\"minioncount\":\"1\",\"minbadguydamage\":\"4\",\"maxbadguydamage\":\"12\",\"lifetap\":\"\",\"damageshield\":\"\",\"badguydmgmod\":\"1\",\"badguyatkmod\":\"1\",\"badguydefmod\":\"1\",\"activate\":\"offense\"}',0,4500,1,0,0,'`wMit funkelnden Augen sieht dir dein Wolf schon von weitem entgegen. Er scheint ungeduldig und hungrig zu sein.','','',100,40,0,0,1.14,150,0,0,0),(25,0,'`NR`(a`)b`ee`0','`NR`(a`)b`een`0','Ein mysteriöser Vogel, mit samtschwarzem Gefieder. Geheimnisvoll, still, hinterlistig und Augen eines Adlers, wenn es um die Suche nach glitzernder Beute geht.','Wildtiere','{\"name\":\"`NR`(a`)b`ee`0\",\"msg_round\":\"\",\"msg_no_effect\":\"\",\"msg_wearoff\":\"`wDein Rabe zieht sich ersch\\u00f6pft aus dem Kampf zur\\u00fcck!\",\"msg_lifetap_success\":\"\",\"msg_lifetap_fail\":\"\",\"msg_regen_success\":\"\",\"msg_regen_fail\":\"\",\"msg_effect_success\":\"`wDein Rabe trifft deinen Gegner mit {damage} Schadenspunkten!\",\"msg_effect_fail\":\"\",\"rounds\":\"50\",\"atkmod\":\"1\",\"defmod\":\"1\",\"regen\":\"\",\"minioncount\":\"2\",\"minbadguydamage\":\"0\",\"maxbadguydamage\":\"4\",\"lifetap\":\"\",\"damageshield\":\"\",\"badguydmgmod\":\"1\",\"badguyatkmod\":\"1\",\"badguydefmod\":\"1\",\"activate\":\"offense\"}',0,600,1,0,0,'`wDein Rabe hockt auf einem Ast vor deinem Fenster und starrt dich an.','','',100,0,1,0,1.14,10,0,0,0),(28,0,'`JF`je`Ge`andra`Gc`jh`Je`0','`JF`je`Ge`andra`Gc`jh`Jen`0','Eine sehr seltene, in den verschiedensten Grüntönen schimmernde Kreatur aus den Elfenreichen im Westen. Feendrachen werden nicht einmal 2 Meter lang und anstatt der üblichen Schwingen besitzen sie Flügel, die denen einer übergroßen Libelle gleichen. Sie sind weder gute Kämpfer, noch besitzen sie einen magischen Odem, doch munkelt man sie hätten einen regenerativen Einfluss auf ihre Umgebung.','Drachlinge','{\"name\":\"`JF`je`Ge`andra`Gc`jh`Je`0\",\"msg_round\":\"\",\"msg_no_effect\":\"\",\"msg_wearoff\":\"`wDein Feendrache zieht sich ersch\\u00f6pft aus dem Kampf zur\\u00fcck!\",\"msg_lifetap_success\":\"\",\"msg_lifetap_fail\":\"\",\"msg_regen_success\":\"\",\"msg_regen_fail\":\"\",\"msg_effect_success\":\"`wDein Feendrache trifft deinen Gegner mit {damage} Schadenspunkten!\",\"msg_effect_fail\":\"\",\"rounds\":\"150\",\"atkmod\":\"1\",\"defmod\":\"1\",\"regen\":\"2\",\"minioncount\":\"1\",\"minbadguydamage\":\"10\",\"maxbadguydamage\":\"15\",\"lifetap\":\"\",\"damageshield\":\"\",\"badguydmgmod\":\"1\",\"badguyatkmod\":\"1\",\"badguydefmod\":\"1\",\"activate\":\"offense\"}',50,25000,1,0,0,'`wDein Feendrache schwirrt ungeduldig durch die Baumkronen im Wald. Anscheinend kann er es nicht abwarten, bis du ihm folgst, um gemeinsam zu kämpfen.','','',0,0,0,0,1.14,2500,0,0,0),(29,0,'`:Va`Smp`Nirfleder`Sma`:us`0','`$Va`4m`$pi`4rfle`$de`4rma`$us`0','Anders als normale Fledermäuse ist diese Fledermaus für jedermann gefährlich, da sie auch Menschen, Elfen oder ähnliches attackiert. Ganz wie ihre Namensvetter saugt die Vampirfledermaus ihren Opfern einiges an Blut aus, um zu überleben. Insbesondere Vampire wünschen die Gegenwart der pelzigen Fledermäuse mit ihren Leder ähnlichen Flügeln und ihrer graubraunen Farbe.','Magische Wesen','{\"name\":\"`$Va`4m`$pi`4rfle`$de`4rma`$us`0\",\"msg_round\":\"\",\"msg_no_effect\":\"\",\"msg_wearoff\":\"`wDeine Fledermaus ist satt!\",\"msg_lifetap_success\":\"\",\"msg_lifetap_fail\":\"\",\"msg_regen_success\":\"\",\"msg_regen_fail\":\"\",\"msg_effect_success\":\"`wDank deiner Fledermaus regenerierst du {damage} Lebenspunkte!\",\"msg_effect_fail\":\"\",\"rounds\":\"100\",\"atkmod\":\"1\",\"defmod\":\"1\",\"regen\":\"\",\"minioncount\":\"1\",\"minbadguydamage\":\"\",\"maxbadguydamage\":\"\",\"lifetap\":\"0.25\",\"damageshield\":\"\",\"badguydmgmod\":\"1\",\"badguyatkmod\":\"1\",\"badguydefmod\":\"1\",\"activate\":\"roundstart\"}',25,1000,1,0,0,'`wKurz nachdem die Nacht hereingebrochen ist, öffnet deine Fledermaus schlagartig ihre wachsamen Augen. Es ist nicht schwer zu erkennen, dass es sie nach Blut dürstet.','','',100,0,0,5,1.14,250,0,0,0),(30,0,'`&H`ya`Yr`;p`Sye`0','`1H`9a`&rp`9y`1en`0','Fast wunderschön ist die Frau anzusehen mit den Federschwingen, doch bei genauerem Hinsehen fallen die groben Unterschiede auf: Die prachtvollen Flügel sind fest mit den gelenken Armen verwachsen, die Füße sind nicht mehr als Vogelklauen. Eine unterhaltsame Begleitung für jeden Mann und auch als Kampfgefährtin wirklich nützlich.','Magische Wesen','{\"name\":\"`1H`9a`\\u0026rp`9y`1e`0\",\"msg_round\":\"\",\"msg_no_effect\":\"\",\"msg_wearoff\":\"`wDeine Harpye zieht sich ersch\\u00f6pft aus dem Kampf zur\\u00fcck!\",\"msg_lifetap_success\":\"\",\"msg_lifetap_fail\":\"\",\"msg_regen_success\":\"\",\"msg_regen_fail\":\"\",\"msg_effect_success\":\"`wDeine Harpye trifft deinen Gegner mit {damage} Schadenspunkten!\",\"msg_effect_fail\":\"\",\"rounds\":\"80\",\"atkmod\":\"1\",\"defmod\":\"0.95\",\"regen\":\"\",\"minioncount\":\"2\",\"minbadguydamage\":\"5\",\"maxbadguydamage\":\"12\",\"lifetap\":\"\",\"damageshield\":\"\",\"badguydmgmod\":\"1\",\"badguyatkmod\":\"1\",\"badguydefmod\":\"1\",\"activate\":\"offense\"}',15,1000,1,0,0,'`wDie Flügel der Kreatur umspielen dich sanft, doch sobald du den hinterlistigen Gesichtsausdruck siehst, merkst du, dass deine Harpye nur endlich auf Jagd gehen will.','','',0,0,0,5,1.14,100,0,0,0),(31,0,'`TG`Sole`Tm`0','`TGolem`0','Der Golem, erschaffen aus den Elementen Erde, Feuer, Wasser und Luft, dient als Schutz vor Feinden, die sich unberechtigt Zugang zum Besitz seines Herren erschleichen wollen. Jegliche Mythen, ihm einen Zettel unter die Zunge zu legen und ihn so Jagd auf seine Feinde machen zu lassen, sind bei diesem Modell nicht inbegriffen.','Magische Wesen','{\"name\":\"`TGolem`0\",\"msg_round\":\"\",\"msg_no_effect\":\"\",\"msg_wearoff\":\"`wDein Golem zerf\\u00e4llt wieder!\",\"msg_lifetap_success\":\"\",\"msg_lifetap_fail\":\"\",\"msg_regen_success\":\"\",\"msg_regen_fail\":\"\",\"msg_effect_success\":\"`wDein Golem trifft den Gegner mit {damage} Schadenspunkten!\",\"msg_effect_fail\":\"\",\"rounds\":\"80\",\"atkmod\":\"1\",\"defmod\":\"1\",\"regen\":\"\",\"minioncount\":\"1\",\"minbadguydamage\":\"1\",\"maxbadguydamage\":\"35\",\"lifetap\":\"\",\"damageshield\":\"\",\"badguydmgmod\":\"1\",\"badguyatkmod\":\"1\",\"badguydefmod\":\"1\",\"activate\":\"offense\"}',30,2500,1,-1,0,'`wDu entdeckst deinen Golem hinter dem Haus, wo er sich einfach hingehockt hat und wohl die ganze Nacht so unsinnig in die Luft gestarrt hat, um auf dich zu warten.','','',0,0,0,5,1.14,100,0,0,0),(32,0,'`6Sk`^a`/r`ya`/b`^ä`6us`0','`6Sk`^a`/r`ya`/b`^ä`6en`0','Ein goldener Skarabäus, glänzt wie 24-Karat reines Gold. Ein wenig größer als die Handfläche wirkt dieses Exemplar besonders prächtig. Ein Tier aus dem fernen Süden, nicht einfach zu finden und – natürlich - wertvoll!','Magische Wesen','{\"name\":\"`6Sk`^a`\\/r`ya`\\/b`^\\u00e4`6us`0\",\"msg_round\":\"\",\"msg_no_effect\":\"\",\"msg_wearoff\":\"`wDie Kraft deines Skarab\\u00e4us ist verbraucht!\",\"msg_lifetap_success\":\"\",\"msg_lifetap_fail\":\"\",\"msg_regen_success\":\"\",\"msg_regen_fail\":\"\",\"msg_effect_success\":\"`wDie magische Aura deines Skarab\\u00e4us l\\u00e4sst dich {damage} Lebenspunkte regenerieren!\",\"msg_effect_fail\":\"\",\"rounds\":\"50\",\"atkmod\":\"1\",\"defmod\":\"1\",\"regen\":\"7\",\"minioncount\":\"1\",\"minbadguydamage\":\"\",\"maxbadguydamage\":\"\",\"lifetap\":\"\",\"damageshield\":\"\",\"badguydmgmod\":\"1\",\"badguyatkmod\":\"1\",\"badguydefmod\":\"1\",\"activate\":\"roundstart\"}',30,0,1,-1,0,'`wKaum ist dein Skarabäus munter, hast du schon alle Mühe ihn in der Hand zu halten und vor allem vor neidischen Blicken zu schützen, denn der kleine Käfer krabbelt aufgeweckt umher.','','',100,100,0,5,1.14,2,0,0,0),(33,0,'`QG`qo`tl`&d`tf`qi`Qsch`0','`QG`qo`tl`&d`tf`qi`Qsch`0','Eines der wahrscheinlich am leichtesten sauber zu haltenden Tiere, die ein Abenteurer sich wünschen kann. Ein Goldfisch ist nicht nur nett anzusehen - nein! - er ist auch der ideale Gefährte für einen Abenteurer, der nicht sehr redselig ist, aber auch nicht alleine sein will. Außer einem gelegentlichen Blubb wird er von dem Goldfisch nichts zu hören bekommen, geschweige denn Unterschützung bekommen.','Haustiere','{\"name\":\"`QG`qo`tl`\\u0026d`tf`qi`Qsch`0\",\"msg_round\":\"\",\"msg_no_effect\":\"\",\"msg_wearoff\":\"\",\"msg_lifetap_success\":\"\",\"msg_lifetap_fail\":\"\",\"msg_regen_success\":\"\",\"msg_regen_fail\":\"\",\"msg_effect_success\":\"\",\"msg_effect_fail\":\"\",\"rounds\":\"42\",\"atkmod\":\"1\",\"defmod\":\"1\",\"regen\":\"\",\"minioncount\":\"1\",\"minbadguydamage\":\"\",\"maxbadguydamage\":\"\",\"lifetap\":\"\",\"damageshield\":\"\",\"badguydmgmod\":\"1\",\"badguyatkmod\":\"1\",\"badguydefmod\":\"1\",\"activate\":\"roundstart\"}',0,5,1,0,0,'`wDein Goldfisch schwimmt einige Runden in seinem Glas und blubbert vor sich her.','','',100,0,1,0,1.14,1,0,0,0),(34,0,'`NSch`(wa`)rz`Ber P`)an`(th`Ner`0','`NSch`(wa`)rz`Ber P`)an`(th`Ner`0','Ein schwarzer Panther mit rabenschwarzem, glänzendem, seidenem Fell. Er hat eine beeindruckende Ausstrahlung mit den hellblauen Augen. Er ist Meister des Schleichens – da können nicht mal Diebe mithalten!','Wildtiere','{\"name\":\"`NSch`(wa`)rz`Ber P`)an`(th`Ner`0\",\"msg_round\":\"\",\"msg_no_effect\":\"\",\"msg_wearoff\":\"`wDein schwarzer Panther zieht sich ersch\\u00f6pft aus dem Kampf zur\\u00fcck!\",\"msg_lifetap_success\":\"\",\"msg_lifetap_fail\":\"\",\"msg_regen_success\":\"\",\"msg_regen_fail\":\"\",\"msg_effect_success\":\"`wDein schwarzer Panther trifft deinen Gegner mit {damage} Schadenspunkten! \",\"msg_effect_fail\":\"\",\"rounds\":\"120\",\"atkmod\":\"1\",\"defmod\":\"1\",\"regen\":\"\",\"minioncount\":\"1\",\"minbadguydamage\":\"10\",\"maxbadguydamage\":\"15\",\"lifetap\":\"\",\"damageshield\":\"\",\"badguydmgmod\":\"1\",\"badguyatkmod\":\"1\",\"badguydefmod\":\"1\",\"activate\":\"offense\"}',12,5000,1,0,0,'`wDein schwarzer Panther streift ungeduldig durch den Wald. Er scheint bereits auf dich zu warten.','','',0,0,0,5,1.14,1000,0,0,0),(36,0,'`}R`Ie`/n`yn`&sc`yh`/w`Ie`}in`0','`}R`Ie`/n`yn`&sc`yh`/w`Ie`}in`0','Ein sehr schnelles Schwein, vielleicht sogar schneller als jedes Einhorn und jeder Nachtmahr. Also beim Reiten gut festhalten, damit du nicht herunterfällst. Mit dem Tier spart man viel Zeit, aber nicht seine Knochen.','Reittiere','{\"name\":\"`}R`Ie`\\/n`yn`\\u0026sc`yh`\\/w`Ie`}in`0\",\"msg_round\":\"\",\"msg_no_effect\":\"\",\"msg_wearoff\":\"`7Dein Tier zieht sich ersch\\u00f6pft aus dem Kampf zur\\u00fcck.\",\"msg_lifetap_success\":\"\",\"msg_lifetap_fail\":\"\",\"msg_regen_success\":\"\",\"msg_regen_fail\":\"\",\"msg_effect_success\":\"\",\"msg_effect_fail\":\"\",\"rounds\":\"60\",\"atkmod\":\"0.9\",\"defmod\":\"0.9\",\"regen\":\"\",\"minioncount\":\"1\",\"minbadguydamage\":\"\",\"maxbadguydamage\":\"\",\"lifetap\":\"\",\"damageshield\":\"\",\"badguydmgmod\":\"1\",\"badguyatkmod\":\"0.75\",\"badguydefmod\":\"1\",\"activate\":\"roundstart\"}',12,500,1,5,1,'`wDu steckst dein(e/n) {weapon}`w in die Satteltasche deines Tieres und machst dich auf ins Abenteuer!','','',0,40,0,5,1.14,500,0,0,0),(37,0,'`SB`;a`ju`2m`Jdr`2a`jc`;h`Se`0','`SB`;a`ju`2m`Jdr`2a`jc`;h`Sen`0','Baumdrachen sind eine kleine und weit weniger intelligente Version der großen grünen Drachen. Ihr Feueratem ist nicht wirklich beeindruckend, aber auf kurze Distanz dennoch sehr schmerzhaft. Baumdrachen erreichen eine Spannweite von bis zu 3 Metern.','Drachlinge','{\"name\":\"`SB`;a`ju`2m`Jdr`2a`jc`;h`Se`0\",\"msg_round\":\"\",\"msg_no_effect\":\"\",\"msg_wearoff\":\"`wDein Baumdrache zieht sich ersch\\u00f6pft aus dem Kampf zur\\u00fcck!\",\"msg_lifetap_success\":\"\",\"msg_lifetap_fail\":\"\",\"msg_regen_success\":\"\",\"msg_regen_fail\":\"\",\"msg_effect_success\":\"`wDein Baumdrache trifft deinen Gegner mit {damage} Schadenspunkten!\",\"msg_effect_fail\":\"\",\"rounds\":\"90\",\"atkmod\":\"1\",\"defmod\":\"1\",\"regen\":\"\",\"minioncount\":\"1\",\"minbadguydamage\":\"5\",\"maxbadguydamage\":\"15\",\"lifetap\":\"\",\"damageshield\":\"\",\"badguydmgmod\":\"1\",\"badguyatkmod\":\"1\",\"badguydefmod\":\"1\",\"activate\":\"offense\"}',20,2500,1,0,0,'`wDein Baumdrache fliegt vom Wald her zu dir herüber. Hat er vielleicht versucht, den grünen Drachen ohne dich zu finden?','','',0,0,0,5,1.14,250,0,0,0),(38,0,'`&P`fe`*r`Fldra`*c`fh`&e`0','`&P`fe`*r`Fldra`*c`fh`&en`0','Wahrscheinlich einer der elegantesten Drachen im ganzen Kaiserreich. Ihr Körper erreicht eine Länge von annähernd 5 Metern und ist beinahe so schlank wie der einer Wyver. In ihrer Heimat an den westlichen Küsten, stürzen sie sich aus großer Höhe in die Fluten um ihre Lieblingsnahrung, frischen Fisch zu jagen, wobei sie sich unter Wasser nicht minder elegant bewegen, wie in der Luft.','Drachlinge','{\"name\":\"`\\u0026P`fe`*r`Fldra`*c`fh`\\u0026e`0\",\"msg_round\":\"\",\"msg_no_effect\":\"\",\"msg_wearoff\":\"`wDein Perldrache zieht sich ersch\\u00f6pft aus dem Kampf zur\\u00fcck!\",\"msg_lifetap_success\":\"\",\"msg_lifetap_fail\":\"\",\"msg_regen_success\":\"\",\"msg_regen_fail\":\"\",\"msg_effect_success\":\"`wDein Perldrache trifft deinen Gegner mit {damage} Schadenspunkten!\",\"msg_effect_fail\":\"\",\"rounds\":\"120\",\"atkmod\":\"1\",\"defmod\":\"1\",\"regen\":\"\",\"minioncount\":\"1\",\"minbadguydamage\":\"8\",\"maxbadguydamage\":\"12\",\"lifetap\":\"\",\"damageshield\":\"\",\"badguydmgmod\":\"1\",\"badguyatkmod\":\"1\",\"badguydefmod\":\"1\",\"activate\":\"offense\"}',30,5000,1,0,0,'`wDein Perldrache liegt entspannt auf der Wiese und blickt dich erwartungsvoll an, um vielleicht einen Fisch von dir zu bekommen.','','',0,0,0,5,1.14,500,0,0,0),(39,0,'`3E`#i`Fs`*dra`Fc`#h`3e`0','`3E`#i`Fs`*dra`Fc`#h`3en`0','Eigentlich mehr ein Drachling und kein voll ausgewachsener Drache. Der eisige Atem dieser bis zu 5 Meter langen, silbrig schimmernden Kreaturen ist aber dennoch absolut tödlich für all jene, welche sich zu nahe an sie heran wagen.','Drachlinge','{\"name\":\"`3E`#i`Fs`*dra`Fc`#h`3e`0\",\"msg_round\":\"\",\"msg_no_effect\":\"\",\"msg_wearoff\":\"`wDein Eisdrache zieht sich ersch\\u00f6pft aus dem Kampf zur\\u00fcck!\",\"msg_lifetap_success\":\"\",\"msg_lifetap_fail\":\"\",\"msg_regen_success\":\"\",\"msg_regen_fail\":\"\",\"msg_effect_success\":\"`wDein Eisdrache trifft deinen Gegner mit {damage} Schadenspunkten!\",\"msg_effect_fail\":\"\",\"rounds\":\"150\",\"atkmod\":\"1.1\",\"defmod\":\"1\",\"regen\":\"\",\"minioncount\":\"1\",\"minbadguydamage\":\"10\",\"maxbadguydamage\":\"30\",\"lifetap\":\"\",\"damageshield\":\"\",\"badguydmgmod\":\"1\",\"badguyatkmod\":\"1\",\"badguydefmod\":\"1\",\"activate\":\"offense\"}',75,10000,1,0,0,'`wDein Drache hat es sich im dunkelsten und kühlsten Abschnitt des Waldes gemütlich gemacht, wo er sich die Zeit, bis du kommst, mit kleinen Eisspielchen vertreibt.','','',0,0,0,15,1.14,1000,0,0,0),(40,0,'`,R`Ao`4t`$er Dra`4c`Ah`,e`0','`,R`Ao`4t`$er Dra`4c`Ah`,en`0','Ebenfalls nur ein Drachling. Eine kleinere Version ihrer großen Verwandten aus dem fernen Süden, deren feuriger Atem ganze Landstriche verheeren konnte. Diese Exemplare hier, sind mit ihren 5-6 Metern Spannweite noch lange nicht ausgewachsen, doch kann ihr Atem dennoch jeden Gegner ohne große Probleme garen lassen.','Drachlinge','{\"name\":\"`,R`Ao`4t`$er Dra`4c`Ah`,e`0\",\"msg_round\":\"\",\"msg_no_effect\":\"\",\"msg_wearoff\":\"`wDein Roter Drache zieht sich ersch\\u00f6pft aus dem Kampf zur\\u00fcck!\",\"msg_lifetap_success\":\"\",\"msg_lifetap_fail\":\"\",\"msg_regen_success\":\"\",\"msg_regen_fail\":\"\",\"msg_effect_success\":\"`wDein Roter Drache trifft deinen Gegner mit {damage} Schadenspunkten!\",\"msg_effect_fail\":\"\",\"rounds\":\"150\",\"atkmod\":\"1.1\",\"defmod\":\"1\",\"regen\":\"\",\"minioncount\":\"1\",\"minbadguydamage\":\"10\",\"maxbadguydamage\":\"30\",\"lifetap\":\"\",\"damageshield\":\"\",\"badguydmgmod\":\"1\",\"badguyatkmod\":\"1\",\"badguydefmod\":\"1\",\"activate\":\"offense\"}',75,10000,1,0,0,'`wDein roter Drache kreist schon lange über dir, in der Hoffnung endlich jagen zu gehen.','','',0,0,0,15,1.14,1000,0,0,0),(41,0,'`UG`:h`]u`Sl`0','`UG`:h`]u`Sl`0','Jene unter euch, denen Ästhetik eher weniger wichtig ist, mögen den Ghul einem Skelettkrieger vorziehen. Sie sind ausdauernder und kräftiger, doch ist der stinkende und permanent verwesende, ehemals humanoide Körper nicht für jeden zu ertragen.','Geister und Dämonen','{\"name\":\"`UG`:h`]u`Sl`0\",\"msg_round\":\"\",\"msg_no_effect\":\"\",\"msg_wearoff\":\"`wDein Ghul zieht sich ersch\\u00f6pft aus dem Kampf zur\\u00fcck!\",\"msg_lifetap_success\":\"\",\"msg_lifetap_fail\":\"\",\"msg_regen_success\":\"\",\"msg_regen_fail\":\"\",\"msg_effect_success\":\"`wDein Ghul trifft deinen Gegner mit {damage} Schadenspunkten!\",\"msg_effect_fail\":\"\",\"rounds\":\"100\",\"atkmod\":\"1\",\"defmod\":\"0.9\",\"regen\":\"\",\"minioncount\":\"1\",\"minbadguydamage\":\"2\",\"maxbadguydamage\":\"8\",\"lifetap\":\"\",\"damageshield\":\"\",\"badguydmgmod\":\"1\",\"badguyatkmod\":\"0.9\",\"badguydefmod\":\"1\",\"activate\":\"offense\"}',0,2200,1,-1,0,'`wAls du den Gestank von Verwesung riechst und eine Schleimspur vor deinem Haus entdeckst, weißt du, dass dein Ghul nicht weit sein kann.','','',0,0,0,0,1.14,10,0,0,0),(42,0,'`%S`5u`Mc`Ncu`Mb`5u`%s`0','`%S`5u`Mc`Ncu`Mb`5u`%s`0','Gebt auf euch Acht, solltet ihr euch wahrhaftig eines dieser verderbten und diabolischen Wesen erwerben wollen. Sie sind zwar sehr gute und ausdauernde Kämpfer, doch soll schon so mancher Möchtegern-Abenteurer ihnen in tiefer Nacht erlegen sein. Nicht ihren scharfen Klingen, sondern ihrer unbeschreiblichen Schönheit. Letztenendes, führt aber beides bei Succubi zum Tode.','Geister und Dämonen','{\"name\":\"`%S`5u`Mc`Ncu`Mb`5u`%s`0\",\"msg_round\":\"\",\"msg_no_effect\":\"\",\"msg_wearoff\":\"`wDein Succubus zieht sich ersch\\u00f6pft aus dem Kampf zur\\u00fcck!\",\"msg_lifetap_success\":\"\",\"msg_lifetap_fail\":\"\",\"msg_regen_success\":\"\",\"msg_regen_fail\":\"\",\"msg_effect_success\":\"`wDein Succubus trifft deinen Gegner mit {damage} Schadenspunkten!\",\"msg_effect_fail\":\"\",\"rounds\":\"100\",\"atkmod\":\"1\",\"defmod\":\"1\",\"regen\":\"\",\"minioncount\":\"2\",\"minbadguydamage\":\"10\",\"maxbadguydamage\":\"15\",\"lifetap\":\"\",\"damageshield\":\"\",\"badguydmgmod\":\"1\",\"badguyatkmod\":\"0.8\",\"badguydefmod\":\"1\",\"activate\":\"offense\"}',40,10000,1,0,0,'`wEine Weile suchst du deinen Succubus, bis du ihn im Nebenzimmer mit einem schlafenden Mann findest. Allerdings musst du ihn erst davon überzeugen, nun mit dir jagen zu gehen.','','',0,0,0,10,1.14,100,0,0,0),(43,0,'`1K`9o`&bo`4l`$d`0','`1K`9o`&bo`4l`$d`0','Ein Exemplar auf das ich besonders stolz bin. Kobolde sind noch etwas kleiner aus Goblins und werden vielleicht einen halben Meter groß. Aber diese hier, sind nicht nur einfache Kobolde, sondern ihre magisch begabten Verwandten aus den Unterwelten. Sie können eure Gegner verfluchen und selbst aus geschickten Kriegern einen Tollpatsch machen.','Geister und Dämonen','{\"name\":\"`1K`9o`\\u0026bo`4l`$d`0\",\"msg_round\":\"\",\"msg_no_effect\":\"\",\"msg_wearoff\":\"`wPl\\u00f6tzlich ist dein Kobold verschwunden!\",\"msg_lifetap_success\":\"\",\"msg_lifetap_fail\":\"\",\"msg_regen_success\":\"\",\"msg_regen_fail\":\"\",\"msg_effect_success\":\"\",\"msg_effect_fail\":\"\",\"rounds\":\"35\",\"atkmod\":\"1\",\"defmod\":\"1\",\"regen\":\"\",\"minioncount\":\"1\",\"minbadguydamage\":\"\",\"maxbadguydamage\":\"\",\"lifetap\":\"\",\"damageshield\":\"\",\"badguydmgmod\":\"1\",\"badguyatkmod\":\"0.6\",\"badguydefmod\":\"0.9\",\"activate\":\"roundstart\"}',20,1000,1,0,0,'`wNeben dem Bett sitzt dein Kobold und sieht zu dir hoch. An seinem Blick kannst du erkennen, dass er schon wieder etwas ausgeheckt hat.','','',0,0,0,0,1.14,75,0,0,0),(44,0,'`EP`oi`Ox`oi`Ee`0','`RP`ri`&x`ri`re`0','Auch oft Waldfee genannt. Handgroße, humanoid erscheinende Kreaturen mit libellenartigen Flügeln. Sie sind weder gefährlich, noch in irgendeiner Weise aggressiv. Merricks Exemplare können zwar nicht sprechen, doch besitzen sie die gleichen magischen Fähigkeiten und die gleiche, alles regenerierende Aura, wie ihre Feenbrüder und Schwestern aus den Wäldern um Atrahor.','Magische Wesen','{\"name\":\"`RP`ri`\\u0026x`ri`re`0\",\"msg_round\":\"\",\"msg_no_effect\":\"\",\"msg_wearoff\":\"`wDeine Pixie zieht sich ersch\\u00f6pft zur\\u00fcck!\",\"msg_lifetap_success\":\"\",\"msg_lifetap_fail\":\"\",\"msg_regen_success\":\"\",\"msg_regen_fail\":\"\",\"msg_effect_success\":\"`wDie magische Aura deiner Pixie l\\u00e4sst dich {damage} Lebenspunkte regenerieren!\",\"msg_effect_fail\":\"\",\"rounds\":\"100\",\"atkmod\":\"1\",\"defmod\":\"1\",\"regen\":\"2\",\"minioncount\":\"1\",\"minbadguydamage\":\"\",\"maxbadguydamage\":\"\",\"lifetap\":\"\",\"damageshield\":\"\",\"badguydmgmod\":\"1\",\"badguyatkmod\":\"1\",\"badguydefmod\":\"1\",\"activate\":\"roundstart\"}',30,5000,1,1,0,'`wKaum bist du munter, schwirrt auch schon deine Pixie aufgeregt um dich herum und bereitet dir dadurch noch mehr Kopfschmerzen. Sie scheint ungeduldig auf neue Erlebnisse zu warten.','','',100,25,1,5,1.14,1,0,0,0),(45,0,'`§T`3o`wd`{es`wf`3e`§e`0','`§T`3o`wd`{es`wf`3e`§en`0','Die Banshee, oder auch Todesfee, tritt in Form einer alten, verwelkten Elfe mit eisig blasser Haut in Erscheinung. Beständig  scheint sie zu jammern und zu klagen, aber erst bei der Gefahr eines nahenden Gegners beginnt sie ihr wahres, Mark und Bein durchdringendes Klagelied, welches selbst gestandenen Helden den Mut auf alle Hoffnung zu nehmen vermag.','Geister und Dämonen','{\"name\":\"`\\u00a7T`3o`wd`{es`wf`3e`\\u00a7e`0\",\"msg_round\":\"\",\"msg_no_effect\":\"\",\"msg_wearoff\":\"`wTheatralisch l\\u00e4sst sich deine Todesfee zu Boden gleiten!\",\"msg_lifetap_success\":\"\",\"msg_lifetap_fail\":\"\",\"msg_regen_success\":\"\",\"msg_regen_fail\":\"\",\"msg_effect_success\":\"\",\"msg_effect_fail\":\"\",\"rounds\":\"70\",\"atkmod\":\"0.95\",\"defmod\":\"0.95\",\"regen\":\"\",\"minioncount\":\"1\",\"minbadguydamage\":\"\",\"maxbadguydamage\":\"\",\"lifetap\":\"\",\"damageshield\":\"\",\"badguydmgmod\":\"1\",\"badguyatkmod\":\"0.7\",\"badguydefmod\":\"0.95\",\"activate\":\"roundstart\"}',35,7500,1,0,0,'`wVom Wald her hörst du wehleidiges Jammern und Klagen. Sofort weißt du, dass deine Todesfee sich schon ohne dich auf den Weg gemacht hat.','','',0,0,0,0,1.14,200,0,0,0),(46,0,'`6Kr`aö`8te`0','`6Kr`aö`8ten`0','Warzige kleine Viecher, die Merrick einem örtlichen Hexenzirkel abschwatzen konnte. Viel gibt es zu diesen faustgroßen Tieren nicht zu sagen. Haltet sie stets feucht, denn ihre Sekrete verursachen nicht nur starken Juckreiz, sondern vermögen auch kleinere Wunden zu heilen.','Haustiere','{\"name\":\"`6Kr`a\\u00f6`8te`0\",\"msg_round\":\"\",\"msg_no_effect\":\"\",\"msg_wearoff\":\"`wDeine Kr\\u00f6te braucht dringend etwas Wasser!\",\"msg_lifetap_success\":\"\",\"msg_lifetap_fail\":\"\",\"msg_regen_success\":\"\",\"msg_regen_fail\":\"\",\"msg_effect_success\":\"\",\"msg_effect_fail\":\"\",\"rounds\":\"10\",\"atkmod\":\"1\",\"defmod\":\"1\",\"regen\":\"1\",\"minioncount\":\"\",\"minbadguydamage\":\"\",\"maxbadguydamage\":\"\",\"lifetap\":\"\",\"damageshield\":\"\",\"badguydmgmod\":\"1\",\"badguyatkmod\":\"1\",\"badguydefmod\":\"1\",\"activate\":\"roundstart\"}',1,50,1,0,0,'`wVom Teich vor deinem Haus hörst du schon deine Kröte quaken.','','',0,0,0,0,1.14,5,0,0,0),(47,0,'`4P`Da`qp`^a`/g`@e`Ji`0','`$P`qa`^p`$a`^g`qe`$i`^en`0','Ein bunter Vogel mit großem Schnabel...leider aber auch einem großen Mundwerk. Früher einmal von Piraten an den westlichen Küsten gehalten, kennt dieses Tier Schimpfworte und Begriffe, die noch jedem Schamesröte ins Gesicht treiben konnten. Merrick wird dir einen besonders guten Preis machen müssen...','Haustiere','{\"name\":\"`$P`qa`^p`$a`^g`qe`$i`0\",\"msg_round\":\"\",\"msg_no_effect\":\"\",\"msg_wearoff\":\"`wDein Papagei h\\u00e4lt den Schnabel!\",\"msg_lifetap_success\":\"\",\"msg_lifetap_fail\":\"\",\"msg_regen_success\":\"\",\"msg_regen_fail\":\"\",\"msg_effect_success\":\"\",\"msg_effect_fail\":\"\",\"rounds\":\"50\",\"atkmod\":\"1\",\"defmod\":\"1\",\"regen\":\"\",\"minioncount\":\"1\",\"minbadguydamage\":\"\",\"maxbadguydamage\":\"\",\"lifetap\":\"\",\"damageshield\":\"\",\"badguydmgmod\":\"1\",\"badguyatkmod\":\"0.85\",\"badguydefmod\":\"1\",\"activate\":\"roundstart\"}',5,500,1,0,0,'`wAuf einer Stange vor dir sitzt dein Papagei und krächzt immer wieder deinen Namen.','','',100,0,0,0,1.14,25,0,0,0),(48,0,'`tF`Yr`;e`Sttc`;h`Ye`tn`0','`tF`Yr`;e`Sttc`;h`Ye`tn`0','Klein, niedlich und hübsch anzusehen, liegen die Vorteile des Frettchens in seiner Statur. Es gibt keine Rüstung welche derart lückenlos ist, als dass nicht ein Frettchen noch hinein schlüpfen und einen wunden Punkt finden könnte...und ihre Bisse sind alles andere als angenehm.','Haustiere','{\"name\":\"`tF`Yr`;e`Sttc`;h`Ye`tn`0\",\"msg_round\":\"\",\"msg_no_effect\":\"\",\"msg_wearoff\":\"`wDein Frettchen zieht sich ersch\\u00f6pft aus dem Kampf zur\\u00fcck!\",\"msg_lifetap_success\":\"\",\"msg_lifetap_fail\":\"\",\"msg_regen_success\":\"\",\"msg_regen_fail\":\"\",\"msg_effect_success\":\"`wDein Frettchen trifft deinen Gegner mit {damage} Schadenspunkten!\",\"msg_effect_fail\":\"\",\"rounds\":\"30\",\"atkmod\":\"1\",\"defmod\":\"1\",\"regen\":\"\",\"minioncount\":\"1\",\"minbadguydamage\":\"1\",\"maxbadguydamage\":\"4\",\"lifetap\":\"\",\"damageshield\":\"\",\"badguydmgmod\":\"1\",\"badguyatkmod\":\"1\",\"badguydefmod\":\"1\",\"activate\":\"offense\"}',0,200,1,0,0,'`wDein Frettchen beißt dir auffordernd in die Wade.','','',0,0,0,0,1.14,15,0,0,0),(49,0,'`7E`es`)e`(l`0','`7E`es`)e`(ls`0','Ein störrisches und nicht besonders flinkes Ding. Aber immerhin lässt es sich (zumindest manchmal) reiten und ist zudem noch ausgesprochen günstig. Genau das Richtige für den weniger betuchten Abenteurer.','Reittiere','{\"name\":\"`7E`es`)e`(l`0\",\"msg_round\":\"\",\"msg_no_effect\":\"\",\"msg_wearoff\":\"Dein Tier zieht sich ersch\\u00f6pft aus dem Kampf zur\\u00fcck.\",\"msg_lifetap_success\":\"\",\"msg_lifetap_fail\":\"\",\"msg_regen_success\":\"\",\"msg_regen_fail\":\"\",\"msg_effect_success\":\"\",\"msg_effect_fail\":\"\",\"rounds\":\"60\",\"atkmod\":\"1.05\",\"defmod\":\"1\",\"regen\":\"\",\"minioncount\":\"1\",\"minbadguydamage\":\"\",\"maxbadguydamage\":\"\",\"lifetap\":\"\",\"damageshield\":\"\",\"badguydmgmod\":\"1\",\"badguyatkmod\":\"1\",\"badguydefmod\":\"1\",\"activate\":\"roundstart\"}',5,10,1,0,1,'`wDu steckst dein(e/n) {weapon}`w in die Satteltasche deines Tieres und machst dich auf ins Abenteuer!','','',100,25,2,0,1.14,100,0,0,0),(50,0,'`NSk`(el`)et`et-K`7am`spf`&ro`fss`0','`NSk`(el`)et`et-K`7am`spf`&ro`fss`0','Einigen Totenbeschwörern ist es endlich gelungen, in Schlachten gefallene Schlachtrösser neu zu beseelen. Zwar sind sie weniger stark als ihre Lebenden Vorgänger, doch erfüllt der Anblick ihrer blanken Knochen so manchen Gegner mit Schrecken.','Reittiere','{\"name\":\"`NSk`(el`)et`et-K`7am`spf`\\u0026ro`fss`0\",\"msg_round\":\"\",\"msg_no_effect\":\"\",\"msg_wearoff\":\"`7Dein Tier zieht sich ersch\\u00f6pft aus dem Kampf zur\\u00fcck.\",\"msg_lifetap_success\":\"\",\"msg_lifetap_fail\":\"\",\"msg_regen_success\":\"\",\"msg_regen_fail\":\"\",\"msg_effect_success\":\"\",\"msg_effect_fail\":\"\",\"rounds\":\"220\",\"atkmod\":\"1.1\",\"defmod\":\"1.1\",\"regen\":\"\",\"minioncount\":\"1\",\"minbadguydamage\":\"\",\"maxbadguydamage\":\"\",\"lifetap\":\"\",\"damageshield\":\"\",\"badguydmgmod\":\"1\",\"badguyatkmod\":\"0.9\",\"badguydefmod\":\"1\",\"activate\":\"roundstart\"}',50,1000,1,1,1,'`wDu steckst dein(e/n) {weapon}`w in die Satteltasche deines Tieres und machst dich auf ins Abenteuer!','','',0,0,0,5,1.14,0,0,0,0),(51,0,'`zRi`Ze`:s`Nenspi`:n`Zn`ze`0','`zRi`Ze`:s`Nenspi`:n`Zn`zen`0','Die großen Waldspinnen der östlichen Wälder. Ihr Biss ist nicht sehr giftig, weshalb es einigen Goblinstämmen gelungen ist diese 4-5 Meter großen Kreaturen zu zähmen. Haltet euch gut im Sattel fest, denn diese Biester können auch problemlos an Wänden oder Decken entlang laufen.','Reittiere','{\"name\":\"`zRi`Ze`:s`Nenspi`:n`Zn`ze`0\",\"msg_round\":\"\",\"msg_no_effect\":\"\",\"msg_wearoff\":\"`7Dein Tier zieht sich ersch\\u00f6pft aus dem Kampf zur\\u00fcck.\",\"msg_lifetap_success\":\"\",\"msg_lifetap_fail\":\"\",\"msg_regen_success\":\"\",\"msg_regen_fail\":\"\",\"msg_effect_success\":\"\",\"msg_effect_fail\":\"\",\"rounds\":\"120\",\"atkmod\":\"1.1\",\"defmod\":\"1.15\",\"regen\":\"\",\"minioncount\":\"1\",\"minbadguydamage\":\"\",\"maxbadguydamage\":\"\",\"lifetap\":\"\",\"damageshield\":\"\",\"badguydmgmod\":\"1\",\"badguyatkmod\":\"0.9\",\"badguydefmod\":\"1\",\"activate\":\"roundstart\"}',55,2500,1,2,1,'`wDu steckst dein(e/n) {weapon}`w in die Satteltasche deines Tieres und machst dich auf ins Abenteuer!','','',100,50,0,10,1.14,25,0,0,0),(52,0,'`JRe`2it`jei`@d`Ge`ach`8se`0','`JRe`2it`jei`@d`Ge`ach`8sen`0','Eigentlich ein nachtaktives und eher harmloses Tier von etwa 5 Metern Länge, dass sich von kleineren Säugern  und Amphibien ernährt. Im Kampf sollte man diese ungewöhnlich schnellen Kreaturen allerdings nicht unterschätzen. Ihre mit Saugnäpfen versehenen Füße lassen sie sich auf jedem Untergrund und sogar an Wänden und Decken gleichermaßen behende bewegen.','Reittiere','{\"name\":\"`JRe`2it`jei`@d`Ge`ach`8se`0\",\"msg_round\":\"\",\"msg_no_effect\":\"\",\"msg_wearoff\":\"`7Dein Tier zieht sich ersch\\u00f6pft aus dem Kampf zur\\u00fcck.\",\"msg_lifetap_success\":\"\",\"msg_lifetap_fail\":\"\",\"msg_regen_success\":\"\",\"msg_regen_fail\":\"\",\"msg_effect_success\":\"\",\"msg_effect_fail\":\"\",\"rounds\":\"150\",\"atkmod\":\"1.05\",\"defmod\":\"1.1\",\"regen\":\"\",\"minioncount\":\"1\",\"minbadguydamage\":\"\",\"maxbadguydamage\":\"\",\"lifetap\":\"\",\"damageshield\":\"\",\"badguydmgmod\":\"1\",\"badguyatkmod\":\"0.95\",\"badguydefmod\":\"1\",\"activate\":\"roundstart\"}',40,800,1,3,1,'`wDu steckst dein(e/n) {weapon}`w in die Satteltasche deines Tieres und machst dich auf ins Abenteuer!','','',0,0,0,5,1.14,150,0,0,0),(53,0,' `NS`(t`)e`ein-Garg`)o`(yl`Ne`0',' `NS`(t`)e`ein-Garg`)o`(yl`Ne`0','Bei dieser Kreatur handelt es sich nicht nur um ein beeindruckendes Stück zwergischer Handwerkskunst, sondern auch um ein Meisterwerk der Beschwörungskunst. Zwar sind sie nicht intelligent und auch nicht besonders flink, aber dafür kommt einer ihrer Schläge schon gut dem Biss eines kleinen Drachen gleich.','Magische Wesen','{\"name\":\" `NS`(t`)e`ein-Garg`)o`(yl`Ne`0\",\"msg_round\":\"\",\"msg_no_effect\":\"\",\"msg_wearoff\":\"`wDein Gargoyle zieht sich ersch\\u00f6pft aus dem Kampf zur\\u00fcck!\",\"msg_lifetap_success\":\"\",\"msg_lifetap_fail\":\"\",\"msg_regen_success\":\"\",\"msg_regen_fail\":\"\",\"msg_effect_success\":\"`wDein Gargoyle trifft deinen Gegner mit {damage} Schadenspunkten!\",\"msg_effect_fail\":\"\",\"rounds\":\"90\",\"atkmod\":\"1\",\"defmod\":\"1\",\"regen\":\"\",\"minioncount\":\"1\",\"minbadguydamage\":\"8\",\"maxbadguydamage\":\"16\",\"lifetap\":\"\",\"damageshield\":\"1\",\"badguydmgmod\":\"1\",\"badguyatkmod\":\"1\",\"badguydefmod\":\"1\",\"activate\":\"offense\"}',10,500,1,0,0,'`wWie eine Statue hockt dein Stein-Gargoyle nahe des Hauses und erwacht erst zum Leben, als er das Wort „Kampf“ hört.','','',0,0,0,5,1.14,5,0,0,0),(54,0,'`qF`De`$u`4e`Ar`,-Gar`Ag`4o`$y`Dl`qe`0','`4F`$e`Qu`qe`^r`q-`QG`$a`4rg`$o`Qy`ql`^en`0','Aus einem haselnussgroßen Rubin beschworen, handelt es sich hierbei um eine Kreatur aus reinstem Feuer, die ihre Gegner mit Feuerbällen, Flammenlanzen und anderen hitzigen Spielereien in die Knie zwingt. Aber Vorsicht! Schon so mancher Held hat sich selbst an diesen Biestern mehr als nur die Finger verbrannt.','Magische Wesen','{\"name\":\"`4F`$e`Qu`qe`^r`q-`QG`$a`4rg`$o`Qy`ql`^e`0\",\"msg_round\":\"\",\"msg_no_effect\":\"\",\"msg_wearoff\":\"`wDein Gargoyle zerf\\u00e4llt und nur ein kleiner Rubin bleibt zur\\u00fcck!\",\"msg_lifetap_success\":\"\",\"msg_lifetap_fail\":\"\",\"msg_regen_success\":\"\",\"msg_regen_fail\":\"\",\"msg_effect_success\":\"`wDein x trifft deinen Gegner mit {damage} Schadenspunkten!\",\"msg_effect_fail\":\"\",\"rounds\":\"100\",\"atkmod\":\"1\",\"defmod\":\"0.95\",\"regen\":\"-1\",\"minioncount\":\"1\",\"minbadguydamage\":\"15\",\"maxbadguydamage\":\"20\",\"lifetap\":\"\",\"damageshield\":\"1\",\"badguydmgmod\":\"1\",\"badguyatkmod\":\"1\",\"badguydefmod\":\"1\",\"activate\":\"offense\"}',10,500,1,0,0,'`wAls du von der Hitze geweckt wirst, wird dir sofort klar, dass dein Feuer-Gargoyle deine Umgebung bereits in Brand gesteckt hat und dass er dringend eine Beschäftigung benötigt.','','',0,0,0,5,1.14,25,0,0,0),(55,0,'`ND`Sr`;i`)d`ee`&r`0','`TDr`)i`7d`&er`0','Eine groteske und hässliche Kreatur, bestehend aus einem nackten Spinnenkörper, der mit Beinen einen Durchmesser von 3-4 Metern erreicht und an dessen Kopfende ein aufgedunsener und unwirklich erscheindender Drow-Oberkörper aufragt. So abscheulich diese Kreatur aber anzusehen ist, so gefährlich ist sie auch im Kampf, denn sie vermag zwei Waffen in einem harmonischen und präzisen Einklag zu führen.','Magische Wesen','{\"name\":\"`ND`Sr`;i`)d`ee`\\u0026r`0\",\"msg_round\":\"\",\"msg_no_effect\":\"\",\"msg_wearoff\":\"`wDein Drider zieht sich ersch\\u00f6pft aus dem Kampf zur\\u00fcck!\",\"msg_lifetap_success\":\"\",\"msg_lifetap_fail\":\"\",\"msg_regen_success\":\"\",\"msg_regen_fail\":\"\",\"msg_effect_success\":\"`wDein Drider trifft deinen Gegner mit {damage} Schadenspunkten!\",\"msg_effect_fail\":\"\",\"rounds\":\"120\",\"atkmod\":\"1\",\"defmod\":\"1\",\"regen\":\"\",\"minioncount\":\"2\",\"minbadguydamage\":\"3\",\"maxbadguydamage\":\"25\",\"lifetap\":\"\",\"damageshield\":\"\",\"badguydmgmod\":\"1\",\"badguyatkmod\":\"0.9\",\"badguydefmod\":\"1\",\"activate\":\"offense\"}',60,7500,1,0,0,'`wAngriffslustig schwingt der Drider seine Waffen, als er die ersten Anzeichen bemerkt, dass er bald kämpfen kann. Deshalb musst du schnell in Deckung gehen, um nicht selbst zu seinem Opfer zu werden.','','',0,0,0,10,1.14,100,0,0,0),(56,0,'`eE`su`&l`fe`0','`eE`su`&l`fen`0','Nur etwa 20-30cm messen diese nachtaktiven und eher harmlos erscheinenden Kreaturen. Bleibt abzuwarten was eure Gegner zu ihren ausdauernden und sturen Angriffen sagen werden...','Wildtiere','{\"name\":\"`eE`su`\\u0026l`fe`0\",\"msg_round\":\"\",\"msg_no_effect\":\"\",\"msg_wearoff\":\"`wDeine Eule zieht sich ersch\\u00f6pft aus dem Kampf zur\\u00fcck!\",\"msg_lifetap_success\":\"\",\"msg_lifetap_fail\":\"\",\"msg_regen_success\":\"\",\"msg_regen_fail\":\"\",\"msg_effect_success\":\"`wDeine Eule trifft deinen Gegner mit {damage} Schadenspunkten!\",\"msg_effect_fail\":\"\",\"rounds\":\"50\",\"atkmod\":\"1\",\"defmod\":\"1\",\"regen\":\"\",\"minioncount\":\"2\",\"minbadguydamage\":\"1\",\"maxbadguydamage\":\"5\",\"lifetap\":\"\",\"damageshield\":\"\",\"badguydmgmod\":\"1\",\"badguyatkmod\":\"1\",\"badguydefmod\":\"1\",\"activate\":\"offense\"}',0,1200,1,0,0,'`wHoch oben im Baum sitz deine Eule und fixiert geduldig ihre Beute.','','',100,0,1,0,1.14,50,0,0,0),(57,0,'`8K`&o`)b`(r`Na`0','`8K`&o`)b`(r`Na`0','Unscheinbar in grau-braunen Farbtönen gehalten, ist dies eine der gefährlichsten Giftschlangen rund um Atrahor. Keine Rüstung ist lückenlos genug, um diese windige, bis zu einem halben Meter lange Kreatur abzuwehren. Ihr Gift selbst verursacht Krämpfe, Übelkeit und vorübergehende Sehschwäche. Also gebt Acht das ihr sie nicht reizt!','Wildtiere','{\"name\":\"`8K`\\u0026o`)b`(r`Na`0\",\"msg_round\":\"\",\"msg_no_effect\":\"\",\"msg_wearoff\":\"`wDeine Kobra zieht sich ersch\\u00f6pft aus dem Kampf zur\\u00fcck!\",\"msg_lifetap_success\":\"\",\"msg_lifetap_fail\":\"\",\"msg_regen_success\":\"\",\"msg_regen_fail\":\"\",\"msg_effect_success\":\"\",\"msg_effect_fail\":\"\",\"rounds\":\"15\",\"atkmod\":\"1\",\"defmod\":\"0.7\",\"regen\":\"\",\"minioncount\":\"1\",\"minbadguydamage\":\"\",\"maxbadguydamage\":\"\",\"lifetap\":\"\",\"damageshield\":\"\",\"badguydmgmod\":\"1\",\"badguyatkmod\":\"0.7\",\"badguydefmod\":\"0.7\",\"activate\":\"roundstart\"}',5,100,1,0,0,'`wDurch einen Biss tötet deine Kobra ein Beutetier und verschlingt es anschließend, bevor sie sich zu dir schlängelt.','','',100,0,0,0,1.14,15,0,0,0),(58,0,'`mA`un`ta`/co`tn`ud`ma`0','`mA`un`ta`/co`tn`ud`ma`0','Tödliche Stille herrscht um diese Schlange, die problemlos bis zu 9 Metern Größe erreicht. Deutlich zu unterscheiden von den anderen Schlangen ist sie durch ihre olivgrüne Färbung und dunklen Flecken, die wie Augen auf ihr nächstes Opfer stieren. Schneller als man es diesem Kriechtier zutraut, hat es schon zugebissen und ihr hilfloses Opfer im Würgegriff.','Wildtiere','{\"name\":\"`mA`un`ta`\\/co`tn`ud`ma`0\",\"msg_round\":\"\",\"msg_no_effect\":\"\",\"msg_wearoff\":\"`wDeine Anaconda zieht sich ersch\\u00f6pft aus dem Kampf zur\\u00fcck!\",\"msg_lifetap_success\":\"\",\"msg_lifetap_fail\":\"\",\"msg_regen_success\":\"\",\"msg_regen_fail\":\"\",\"msg_effect_success\":\"`wDeine Anaconda trifft deinen Gegner mit {damage} Schadenspunkten!\",\"msg_effect_fail\":\"\",\"rounds\":\"35\",\"atkmod\":\"1\",\"defmod\":\"0.9\",\"regen\":\"\",\"minioncount\":\"1\",\"minbadguydamage\":\"0\",\"maxbadguydamage\":\"30\",\"lifetap\":\"\",\"damageshield\":\"\",\"badguydmgmod\":\"1\",\"badguyatkmod\":\"1\",\"badguydefmod\":\"1\",\"activate\":\"offense\"}',2,2500,1,0,0,'`wZischelnd schlängelt sich deine Anakonda durch ihr Terrarium, wobei sie jede deiner Bewegungen verfolgt.','','',100,0,0,0,1.14,25,0,0,0),(59,0,'`mF`Du`qc`/h`&s`0','`mF`Du`qc`/h`&s`0','Der gemeine Rot- oder Waldfuchs. Klein, listig und flink. Gut zum Übermitteln von Botschaften (Fuchsen), aber, sofern ihr ihn gut behandelt, auch ein treuer Gefährte im Kampf.','Wildtiere','{\"name\":\"`mF`Du`qc`\\/h`\\u0026s`0\",\"msg_round\":\"\",\"msg_no_effect\":\"\",\"msg_wearoff\":\"`wDein Fuchs zieht sich ersch\\u00f6pft aus dem Kampf zur\\u00fcck!\",\"msg_lifetap_success\":\"\",\"msg_lifetap_fail\":\"\",\"msg_regen_success\":\"\",\"msg_regen_fail\":\"\",\"msg_effect_success\":\"`wDein Fuchs trifft deinen Gegner mit {damage} Schadenspunkten!\",\"msg_effect_fail\":\"\",\"rounds\":\"60\",\"atkmod\":\"1\",\"defmod\":\"1\",\"regen\":\"\",\"minioncount\":\"1\",\"minbadguydamage\":\"3\",\"maxbadguydamage\":\"5\",\"lifetap\":\"\",\"damageshield\":\"\",\"badguydmgmod\":\"1\",\"badguyatkmod\":\"1\",\"badguydefmod\":\"1\",\"activate\":\"offense\"}',0,1500,1,0,0,'`wFür neue Abenteuer bereit, sitzt dein Fuchs schon vor seinem Bau und starrt hinüber zum Wald.','','',100,30,0,0,1.14,50,0,0,0),(60,0,'`NH`Sy`Yä`tn`&e`0','`NH`Sy`Yä`tn`&e`0','Gefangen in den weiten Steppen der dunklen Lande, nördlich von Atrahor. Selbst mit guter Abrichtung sind sie noch immer feige und hinterhältig. Doch gerade diese Hinterhältigkeit macht sie zu so gefährlichen Gegnern und mit ihren starken und kräftigen Kiefer sind nicht zu unterschätzen.','Wildtiere','{\"name\":\"`NH`Sy`Y\\u00e4`tn`\\u0026e`0\",\"msg_round\":\"\",\"msg_no_effect\":\"\",\"msg_wearoff\":\"`wDeine Hy\\u00e4ne zieht sich winselnd zur\\u00fcck!\",\"msg_lifetap_success\":\"\",\"msg_lifetap_fail\":\"\",\"msg_regen_success\":\"\",\"msg_regen_fail\":\"\",\"msg_effect_success\":\"`wDeine Hy\\u00e4ne trifft deinen Gegner mit {damage} Schadenspunkten!\",\"msg_effect_fail\":\"\",\"rounds\":\"50\",\"atkmod\":\"1\",\"defmod\":\"1\",\"regen\":\"\",\"minioncount\":\"1\",\"minbadguydamage\":\"3\",\"maxbadguydamage\":\"9\",\"lifetap\":\"\",\"damageshield\":\"\",\"badguydmgmod\":\"1\",\"badguyatkmod\":\"1\",\"badguydefmod\":\"1\",\"activate\":\"offense\"}',0,2000,1,0,0,'`wAls du aus dem Haus trittst, bemerkst du deine Hyäne, wie sie sich gerade über die Überreste eines Vogels hermacht. Allerdings ist sie auch danach noch hungrig!','','',100,25,0,0,1.14,100,0,0,0),(61,0,'`DJ`qa`/g`tu`ya`&r`0','`DJ`qa`/g`tu`ya`&r`0','Zu einer der größten, aber dazu auch schnellsten Katze gehörend, ist der Jaguar der beste Partner für Jäger der freien Felder. Mit einer Gesamtlänge von bis zu 2 Metern zeugt diese große Raubkatze nicht nur von tödlicher Geduld und Anmut, sondern vielmehr, das sie schnell, effektiv und vor allem aber unberechenbar ist - nicht nur für Feinde. Wer einen Jaguar als Gefährten hat, muss sich darauf einstellen, das dieser, so wie jede Katze, seinen eigenen, sturen Willen besitzt und sich ungern herumkommandieren lässt. Im Übrigen: Jaguar sind keine guten Kuschelfreunde. Wenn sie sich schon mal auf ihren Gefährten legen, wird es bei ungefähr 50 Pfund Gewicht etwas erdrückend.','Wildtiere','{\"name\":\"`DJ`qa`\\/g`tu`ya`\\u0026r`0\",\"msg_round\":\"\",\"msg_no_effect\":\"\",\"msg_wearoff\":\"`wDein Jaguar zieht sich ersch\\u00f6pft aus dem Kampf zur\\u00fcck!\",\"msg_lifetap_success\":\"\",\"msg_lifetap_fail\":\"\",\"msg_regen_success\":\"\",\"msg_regen_fail\":\"\",\"msg_effect_success\":\"`wDein Jaguar trifft deinen Gegner mit {damage} Schadenspunkten!\",\"msg_effect_fail\":\"\",\"rounds\":\"100\",\"atkmod\":\"1\",\"defmod\":\"1\",\"regen\":\"\",\"minioncount\":\"1\",\"minbadguydamage\":\"4\",\"maxbadguydamage\":\"14\",\"lifetap\":\"\",\"damageshield\":\"\",\"badguydmgmod\":\"1\",\"badguyatkmod\":\"1\",\"badguydefmod\":\"1\",\"activate\":\"offense\"}',6,1000,1,0,0,'`wRuhend liegt dein Jaguar vor deinen Füssen, nur darauf wartend, dass die Jagd beginnt.','','',0,0,0,5,1.14,150,0,0,0),(62,0,'`ST`Ui`ug`/e`yr`0','`ST`Ui`ug`/e`yr`0','Auch diese Tiere sind relativ zahm. Gut für den Schutz von Personen solange sie gut gefüttert sind. Denn eines vergesst nie: Tiger sind und bleiben Raubtiere.','Wildtiere','{\"name\":\"`ST`Ui`ug`\\/e`yr`0\",\"msg_round\":\"\",\"msg_no_effect\":\"\",\"msg_wearoff\":\"`wDein Tiger zieht sich ersch\\u00f6pft aus dem Kampf zur\\u00fcck!\",\"msg_lifetap_success\":\"\",\"msg_lifetap_fail\":\"\",\"msg_regen_success\":\"\",\"msg_regen_fail\":\"\",\"msg_effect_success\":\"`wDein Tiger trifft deinen mit {damage} Schadenspunkten!\",\"msg_effect_fail\":\"\",\"rounds\":\"100\",\"atkmod\":\"1\",\"defmod\":\"1\",\"regen\":\"\",\"minioncount\":\"1\",\"minbadguydamage\":\"5\",\"maxbadguydamage\":\"14\",\"lifetap\":\"\",\"damageshield\":\"\",\"badguydmgmod\":\"1\",\"badguyatkmod\":\"1\",\"badguydefmod\":\"1\",\"activate\":\"offense\"}',8,2000,1,0,0,'`wDein Tiger schleicht, auf dich wartend, am Waldrand auf und ab. Schon längst hat er seine Beute erspäht.','','',0,0,0,5,1.14,500,0,0,0),(63,0,'`SBr`;au`Ynbär`0','`SBr`;au`Ynbären`0','Kraftvoll und gefährlich ist er, der massige Braunbär mit seinen riesigen Pranken. Eine Gefahr für jedes unachtsame Wesen, eine Freude für jeden, der ihn an seiner Seite weiß. Mit einer Größe von 3 Fuß ist er zwar nicht das größte, aber mit das schwerste der Wildtiere. Wer sich nicht vor seinen ständig wechselnden Launen hütet und ihm regelmäßig Fisch zu fressen gibt, wird böse Überraschungen erleben.','Wildtiere','{\"name\":\"`SBr`;au`Ynb\\u00e4r`0\",\"msg_round\":\"\",\"msg_no_effect\":\"\",\"msg_wearoff\":\"`wDein Braunb\\u00e4r zieht sich ersch\\u00f6pft aus dem Kampf zur\\u00fcck!\",\"msg_lifetap_success\":\"\",\"msg_lifetap_fail\":\"\",\"msg_regen_success\":\"\",\"msg_regen_fail\":\"\",\"msg_effect_success\":\"`wDein Braunb\\u00e4r trifft deinen Gegner mit {damage} Schadenspunkten!\",\"msg_effect_fail\":\"\",\"rounds\":\"100\",\"atkmod\":\"1\",\"defmod\":\"1\",\"regen\":\"\",\"minioncount\":\"1\",\"minbadguydamage\":\"10\",\"maxbadguydamage\":\"12\",\"lifetap\":\"\",\"damageshield\":\"\",\"badguydmgmod\":\"1\",\"badguyatkmod\":\"1\",\"badguydefmod\":\"1\",\"activate\":\"offense\"}',10,5000,1,0,0,'`wUnruhig geht dein Bär hin und her. Er knurrt vor Hunger und Ungeduld, denn er wollte bald ein paar Fische im Fluss erhaschen.','','',0,0,0,5,1.14,2500,0,0,0),(67,0,'`YM`ti`&lchk`tu`Yh`0','`YM`ti`&lchk`tu`Yh`0','Ein Prachtexemplar einer Kuh! Hervorragend gezüchtet, ein ganz edles Tier!','Nutztiere','{\"name\":\"`YM`ti`\\u0026lchk`tu`Yh`0\",\"msg_round\":\"\",\"msg_no_effect\":\"\",\"msg_wearoff\":\"\",\"msg_lifetap_success\":\"\",\"msg_lifetap_fail\":\"\",\"msg_regen_success\":\"\",\"msg_regen_fail\":\"\",\"msg_effect_success\":\"\",\"msg_effect_fail\":\"\",\"rounds\":\"500\",\"atkmod\":\"1\",\"defmod\":\"1.01\",\"regen\":\"\",\"minioncount\":\"\",\"minbadguydamage\":\"\",\"maxbadguydamage\":\"\",\"lifetap\":\"\",\"damageshield\":\"\",\"badguydmgmod\":\"1\",\"badguyatkmod\":\"1\",\"badguydefmod\":\"1\",\"activate\":\"roundstart\"}',10,5000,1,0,1,'Ein lautes \\\"Muuuuh!\\\" direkt neben deinem Ohr lässt dich feststellen, dass du gestern vergessen hast die `YM`ti`&lchk`tu`Yh`0 in den Stall zu bringen.','','',0,0,0,0,0,2500,0,0,0),(68,0,'`qH`/u`&hn`0','`QHühnchen`0','Eine prächtige Legehenne.','Nutztiere','{\"name\":\"`QH`quh`tn`0\",\"msg_round\":\"\",\"msg_no_effect\":\"\",\"msg_wearoff\":\"Nun hast du kein Huhn mehr auf dem Kopf.\",\"msg_lifetap_success\":\"\",\"msg_lifetap_fail\":\"\",\"msg_regen_success\":\"\",\"msg_regen_fail\":\"\",\"msg_effect_success\":\"\",\"msg_effect_fail\":\"\",\"rounds\":\"150\",\"atkmod\":\"0.99\",\"defmod\":\"0.99\",\"regen\":\"\",\"minioncount\":\"\",\"minbadguydamage\":\"\",\"maxbadguydamage\":\"\",\"lifetap\":\"\",\"damageshield\":\"\",\"badguydmgmod\":\"0.9\",\"badguyatkmod\":\"1\",\"badguydefmod\":\"1\",\"activate\":\"roundstart\"}',1,500,1,0,0,'Dein `QH`quh`tn`0 flattert aufgeregt herum und weckt dich für dein neuen Tag.','','',0,0,0,0,0,50,0,0,0),(69,0,'`^Hon`qigb`dien`^en`0','`^Hon`qigb`dien`^en`0','Ein ganzer Stock voll Bienen, geh da besser nur mit Schutzkleidung dran!','Nutztiere','{\"name\":\"`^Hon`qigb`dien`^en`0\",\"msg_round\":\"\",\"msg_no_effect\":\"\",\"msg_wearoff\":\"Die Bienen ziehen sich in den Stock zur\\u00fcck.\",\"msg_lifetap_success\":\"\",\"msg_lifetap_fail\":\"\",\"msg_regen_success\":\"\",\"msg_regen_fail\":\"\",\"msg_effect_success\":\"Bienen stechen deinen Gegner f\\u00fcr {damage} Schadenspunkte.\",\"msg_effect_fail\":\"\",\"rounds\":\"25\",\"atkmod\":\"1\",\"defmod\":\"1\",\"regen\":\"\",\"minioncount\":\"10\",\"minbadguydamage\":\"0\",\"maxbadguydamage\":\"2\",\"lifetap\":\"\",\"damageshield\":\"\",\"badguydmgmod\":\"1\",\"badguyatkmod\":\"1\",\"badguydefmod\":\"1\",\"activate\":\"roundstart\"}',3,1500,1,0,0,'Du erwachst, als sich eine Biene auf deine Nase setzt und dich dort kitzelt.','','',100,0,0,5,0,1,0,0,0),(70,0,'`&S`sc`eh`)af`0','`&Schafs`0','Das ist ein Prachtschaf! Bei dem wächst die Wolle so schnell, dass man dabei zusehen kann!','Nutztiere','{\"name\":\"`\\u0026S`pch`7af`0\",\"msg_round\":\"\",\"msg_no_effect\":\"\",\"msg_wearoff\":\"\",\"msg_lifetap_success\":\"\",\"msg_lifetap_fail\":\"\",\"msg_regen_success\":\"\",\"msg_regen_fail\":\"\",\"msg_effect_success\":\"\",\"msg_effect_fail\":\"\",\"rounds\":\"100\",\"atkmod\":\"1\",\"defmod\":\"1\",\"regen\":\"\",\"minioncount\":\"\",\"minbadguydamage\":\"\",\"maxbadguydamage\":\"\",\"lifetap\":\"\",\"damageshield\":\"\",\"badguydmgmod\":\"1\",\"badguyatkmod\":\"0.99\",\"badguydefmod\":\"0.99\",\"activate\":\"roundstart\"}',5,1000,1,0,1,'Dein `&S`pch`7af`0 weckt dich schon früh am Morgen mit lautem Blöken.','','',0,0,0,0,0,500,0,0,0),(71,0,'`zM`Oa`ostschw`Oe`zin`0','`xSch`rwe`xine`0','Na das is mal n dickes Schwein... fütter es gut und sorg dafür, dann kannst du damit nen ganzen Winter satt sein!','Nutztiere','{\"name\":\"`xMas`rtschw`xein`0\",\"msg_round\":\"\",\"msg_no_effect\":\"\",\"msg_wearoff\":\"Dein Schwein ist eingeschlafen\",\"msg_lifetap_success\":\"\",\"msg_lifetap_fail\":\"\",\"msg_regen_success\":\"\",\"msg_regen_fail\":\"\",\"msg_effect_success\":\"\",\"msg_effect_fail\":\"\",\"rounds\":\"50\",\"atkmod\":\"1\",\"defmod\":\"1\",\"regen\":\"\",\"minioncount\":\"\",\"minbadguydamage\":\"\",\"maxbadguydamage\":\"\",\"lifetap\":\"\",\"damageshield\":\"\",\"badguydmgmod\":\"1\",\"badguyatkmod\":\"1\",\"badguydefmod\":\"1\",\"activate\":\"roundstart\"}',0,1000,1,0,1,'Ein lautes \\\\\\\"Oink!\\\\\\\" gibt dir in der Früh zu verstehen, dass dein Schwein wieder Hunger hat.','','',0,0,0,0,0,500,0,0,0),(72,0,'`mZu`;ch`Ytb`;ul`mle`0','`mZu`Tcht`tbullen`0','N klasse Tier. Groß und stark. Gut durchgefüttert kann der dir jede Menge bestes Fleisch abwerfen.','Nutztiere','{\"name\":\"`mZu`Tcht`tbulle`0\",\"msg_round\":\"\",\"msg_no_effect\":\"\",\"msg_wearoff\":\"Dein Zuchtbulle sucht sich ein Fleckchen zum Grasen.\",\"msg_lifetap_success\":\"\",\"msg_lifetap_fail\":\"\",\"msg_regen_success\":\"\",\"msg_regen_fail\":\"\",\"msg_effect_success\":\"Dein Zuchtbulle nimmt deinen Gegner auf die H\\u00f6rner und trifft f\\u00fcr `4{damage}`7 Schadenspunkte!\",\"msg_effect_fail\":\"\",\"rounds\":\"10\",\"atkmod\":\"1\",\"defmod\":\"1\",\"regen\":\"\",\"minioncount\":\"1\",\"minbadguydamage\":\"0\",\"maxbadguydamage\":\"50\",\"lifetap\":\"\",\"damageshield\":\"\",\"badguydmgmod\":\"1\",\"badguyatkmod\":\"1\",\"badguydefmod\":\"1\",\"activate\":\"offense\"}',0,2500,1,0,1,'Dein Zuchtbulle schubst dich unsanft aus deinem Schlaflager.','','',0,0,0,0,3,2000,0,0,0),(73,0,'`4S`Qc`dh`ql`ta`yu`tf`qu`dc`Qh`4s`0','`4S`Qc`dh`ql`ta`yu`tf`qu`dc`Qh`4s`0','','Besondere','{\"name\":\"`4S`Qc`dh`ql`ta`yu`tf`qu`dc`Qh`4s`0\",\"msg_round\":\"\",\"msg_no_effect\":\"\",\"msg_wearoff\":\"`tDer `4S`Qc`dh`ql`ta`yu`tf`qu`dc`Qh`4s `tzieht sich zur\\u00fcck.\",\"msg_lifetap_success\":\"\",\"msg_lifetap_fail\":\"\",\"msg_regen_success\":\"\",\"msg_regen_fail\":\"\",\"msg_effect_success\":\"\",\"msg_effect_fail\":\"\",\"rounds\":\"250\",\"atkmod\":\"1.15\",\"defmod\":\"1.15\",\"regen\":\"\",\"minioncount\":\"1\",\"minbadguydamage\":\"\",\"maxbadguydamage\":\"\",\"lifetap\":\"0.7\",\"damageshield\":\"\",\"badguydmgmod\":\"1\",\"badguyatkmod\":\"1\",\"badguydefmod\":\"1\",\"activate\":\"roundstart\"}',42,42424,0,5,1,'Mit sanftem Lecken an deiner Hand weckt dich dein `4S`Qc`dh`ql`ta`yu`tf`qu`dc`Qh`4s`0 und begrüßt dich zu einem neuen Tag.','Mit wedelnder Rute streift der `4S`Qc`dh`ql`ta`yu`tf`qu`dc`Qh`4s`0 um deine Beine.','`#Der `4S`Qc`dh`ql`ta`yu`tf`qu`dc`Qh`4s`# frisst nur soviel, wie er auch Hunger hat.',100,100,3,0,1.1,4242,0,0,0),(74,0,'`SM`(u`)li`0','`)Muli`0','Feines treudoofes Tier. Wer sich keen Pferd leisten kann ist damit bestens versorgt. Ein klasse Arbeitstier ists obendrein!','Nutztiere','{\"name\":\"`)Muli`0\",\"msg_round\":\"\",\"msg_no_effect\":\"\",\"msg_wearoff\":\"\",\"msg_lifetap_success\":\"\",\"msg_lifetap_fail\":\"\",\"msg_regen_success\":\"\",\"msg_regen_fail\":\"\",\"msg_effect_success\":\"\",\"msg_effect_fail\":\"\",\"rounds\":\"150\",\"atkmod\":\"1.025\",\"defmod\":\"1.025\",\"regen\":\"\",\"minioncount\":\"\",\"minbadguydamage\":\"\",\"maxbadguydamage\":\"\",\"lifetap\":\"\",\"damageshield\":\"\",\"badguydmgmod\":\"1\",\"badguyatkmod\":\"1\",\"badguydefmod\":\"1\",\"activate\":\"roundstart\"}',5,1500,1,1,1,'Das `)Muli`0 steht noch genau da wo du es gelassen hast, fast so als hätte es sich die ganze Nacht kein Stück bewegt.','Dein `)Muli`0 frisst sich satt und schaut dich dankbar an.','Dein `)Muli`0 frisst ein wenig, scheint aber keinen so großen Hunger zu haben.',100,100,5,3,1.05,250,0,0,0),(75,0,'`KI`Cn`Wc`Nu`Wb`Cu`Ks`0','`KI`Cn`Wc`Nu`Wb`Cu`Ks`0','Gebt auf euch Acht, solltet ihr euch wahrhaftig eines dieser verderbten und diabolischen Wesen erwerben wollen. Sie sind zwar sehr gute und ausdauernde Kämpfer, doch soll schon so mancher Möchtegern-Abenteurer ihnen in tiefer Nacht erlegen sein. Nicht ihren scharfen Klingen, sondern ihrer unbeschreiblichen Schönheit. Letztenendes, führt aber beides bei einem Incubus zum Tode. Was der Unterschied zu einem Succubus ist fragt ihr euch? Nun, ich verrate Euch ein Geheimnis: Es gibt keinen. Incubi sind das männliche Gegenstück zu weiblichen Succubi!','Geister und Dämonen','{\"name\":\"`KI`Cn`Wc`Nu`Wb`Cu`Ks`0\",\"msg_round\":\"\",\"msg_no_effect\":\"\",\"msg_wearoff\":\"`wDein Incubus zieht sich ersch\\u00f6pft aus dem Kampf zur\\u00fcck!\",\"msg_lifetap_success\":\"\",\"msg_lifetap_fail\":\"\",\"msg_regen_success\":\"\",\"msg_regen_fail\":\"\",\"msg_effect_success\":\"`wDein Incubus trifft deinen Gegner mit {damage} Schadenspunkten!\",\"msg_effect_fail\":\"\",\"rounds\":\"100\",\"atkmod\":\"1\",\"defmod\":\"1\",\"regen\":\"\",\"minioncount\":\"2\",\"minbadguydamage\":\"10\",\"maxbadguydamage\":\"15\",\"lifetap\":\"\",\"damageshield\":\"\",\"badguydmgmod\":\"1\",\"badguyatkmod\":\"0.8\",\"badguydefmod\":\"1\",\"activate\":\"offense\"}',40,10000,1,0,0,'`wEine Weile suchst du deinen `wI`{n`Fc`*u`wb`{u`Fs`0, bis du ihn im Nebenzimmer mit einer schlafenden Frau findest. Allerdings musst du ihn erst davon überzeugen, nun mit dir jagen zu gehen.','','',0,0,0,10,1.14,100,0,0,0);
/*!40000 ALTER TABLE `mounts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `museum_guilds`
--

DROP TABLE IF EXISTS `museum_guilds`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `museum_guilds` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `founder` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `bio` text,
  `leaders` text,
  `date_founded` varchar(40) DEFAULT NULL,
  `date_deleted` varchar(40) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `museum_guilds`
--

LOCK TABLES `museum_guilds` WRITE;
/*!40000 ALTER TABLE `museum_guilds` DISABLE KEYS */;
/*!40000 ALTER TABLE `museum_guilds` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `museum_useritems`
--

DROP TABLE IF EXISTS `museum_useritems`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `museum_useritems` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `owner` int(10) unsigned DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` text,
  `comment` text,
  PRIMARY KEY (`id`),
  KEY `owner` (`owner`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `museum_useritems`
--

LOCK TABLES `museum_useritems` WRITE;
/*!40000 ALTER TABLE `museum_useritems` DISABLE KEYS */;
/*!40000 ALTER TABLE `museum_useritems` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `nastywords`
--

DROP TABLE IF EXISTS `nastywords`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `nastywords` (
  `words` longtext
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `nastywords`
--

LOCK TABLES `nastywords` WRITE;
/*!40000 ALTER TABLE `nastywords` DISABLE KEYS */;
INSERT INTO `nastywords` VALUES ('Arschloch');
/*!40000 ALTER TABLE `nastywords` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `news`
--

DROP TABLE IF EXISTS `news`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `news` (
  `newsid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `newstext` text NOT NULL,
  `newsdate` date NOT NULL DEFAULT '0000-00-00',
  `accountid` int(11) unsigned NOT NULL DEFAULT '0',
  `guildid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `onlyuser` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`newsid`,`newsdate`),
  KEY `accountid` (`accountid`),
  KEY `guildid` (`guildid`),
  KEY `newsdate` (`newsdate`),
  KEY `onlyuser` (`onlyuser`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `news`
--

LOCK TABLES `news` WRITE;
/*!40000 ALTER TABLE `news` DISABLE KEYS */;
/*!40000 ALTER TABLE `news` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `petitionmail`
--

DROP TABLE IF EXISTS `petitionmail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `petitionmail` (
  `petitionid` int(11) NOT NULL DEFAULT '0',
  `messageid` int(11) NOT NULL DEFAULT '0',
  `msgfrom` int(11) NOT NULL DEFAULT '0',
  `msgto` int(11) NOT NULL DEFAULT '0',
  `subject` text NOT NULL,
  `body` text NOT NULL,
  `sent` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `seen` tinyint(1) NOT NULL DEFAULT '0',
  KEY `petitionid_messageid` (`petitionid`,`messageid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `petitionmail`
--

LOCK TABLES `petitionmail` WRITE;
/*!40000 ALTER TABLE `petitionmail` DISABLE KEYS */;
/*!40000 ALTER TABLE `petitionmail` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `petitions`
--

DROP TABLE IF EXISTS `petitions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `petitions` (
  `petitionid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `author` int(11) unsigned NOT NULL DEFAULT '0',
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `kat` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `charname` varchar(255) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
  `body` text,
  `pageinfo` mediumtext,
  `lastact` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `IP` varchar(15) NOT NULL DEFAULT '0.0.0.0',
  `ID` varchar(32) NOT NULL DEFAULT '',
  `comments` text NOT NULL,
  `commentcount` int(10) unsigned NOT NULL DEFAULT '0',
  `prio` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `connected` text NOT NULL,
  `p_for` varchar(100) NOT NULL DEFAULT '',
  `short_desc` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`petitionid`),
  KEY `date` (`date`),
  KEY `status` (`status`),
  KEY `lastact` (`lastact`),
  KEY `prio` (`prio`),
  KEY `author` (`author`),
  KEY `kat` (`kat`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `petitions`
--

LOCK TABLES `petitions` WRITE;
/*!40000 ALTER TABLE `petitions` DISABLE KEYS */;
/*!40000 ALTER TABLE `petitions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pollresults`
--

DROP TABLE IF EXISTS `pollresults`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pollresults` (
  `resultid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `choice` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `account` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `motditem` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `pollid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`resultid`),
  KEY `motditem` (`motditem`),
  KEY `pollid` (`pollid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pollresults`
--

LOCK TABLES `pollresults` WRITE;
/*!40000 ALTER TABLE `pollresults` DISABLE KEYS */;
/*!40000 ALTER TABLE `pollresults` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `polls`
--

DROP TABLE IF EXISTS `polls`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `polls` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `question` varchar(255) NOT NULL DEFAULT '',
  `description` varchar(255) NOT NULL DEFAULT '',
  `author` int(10) unsigned NOT NULL DEFAULT '0',
  `postdate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `section` varchar(30) NOT NULL DEFAULT '',
  `expire` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `option1` varchar(120) NOT NULL DEFAULT '',
  `option2` varchar(120) NOT NULL DEFAULT '',
  `option3` varchar(120) NOT NULL DEFAULT '',
  `option4` varchar(120) NOT NULL DEFAULT '',
  `option5` varchar(120) NOT NULL DEFAULT '',
  `option6` varchar(120) NOT NULL DEFAULT '',
  `closed` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `section` (`section`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Enthält Umfragen';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `polls`
--

LOCK TABLES `polls` WRITE;
/*!40000 ALTER TABLE `polls` DISABLE KEYS */;
/*!40000 ALTER TABLE `polls` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pvp`
--

DROP TABLE IF EXISTS `pvp`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pvp` (
  `acctid1` int(11) NOT NULL DEFAULT '0',
  `acctid2` int(11) NOT NULL DEFAULT '0',
  `name1` varchar(80) NOT NULL,
  `name2` varchar(80) NOT NULL,
  `lvl1` int(11) NOT NULL DEFAULT '0',
  `lvl2` int(11) NOT NULL DEFAULT '0',
  `hp1` int(11) NOT NULL DEFAULT '1',
  `hp2` int(11) NOT NULL DEFAULT '1',
  `maxhp1` int(11) NOT NULL DEFAULT '1',
  `maxhp2` int(11) NOT NULL DEFAULT '1',
  `att1` int(11) NOT NULL DEFAULT '1',
  `att2` int(11) NOT NULL DEFAULT '1',
  `def1` int(11) NOT NULL DEFAULT '1',
  `def2` int(11) NOT NULL DEFAULT '1',
  `weapon1` varchar(50) NOT NULL DEFAULT '',
  `weapon2` varchar(50) NOT NULL DEFAULT '',
  `armor1` varchar(50) NOT NULL DEFAULT '',
  `armor2` varchar(50) NOT NULL DEFAULT '',
  `specialtyuses1` text,
  `specialtyuses2` text,
  `bufflist1` text,
  `bufflist2` text,
  `turn` tinyint(1) NOT NULL DEFAULT '2',
  `lastmsg` text,
  `nospecials` tinyint(3) unsigned NOT NULL DEFAULT '0',
  KEY `acctid2` (`acctid2`),
  KEY `acctid` (`acctid1`),
  KEY `acctid1_acctid2` (`acctid1`,`acctid2`),
  KEY `turn` (`turn`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pvp`
--

LOCK TABLES `pvp` WRITE;
/*!40000 ALTER TABLE `pvp` DISABLE KEYS */;
/*!40000 ALTER TABLE `pvp` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `quest_bedingung`
--

DROP TABLE IF EXISTS `quest_bedingung`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `quest_bedingung` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `activ` tinyint(1) NOT NULL DEFAULT '0',
  `sort` int(255) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `zaehlerid` int(255) NOT NULL DEFAULT '0',
  `zaehler_bedingung` int(255) NOT NULL DEFAULT '0',
  `zaehler_bedingung_wert` varchar(255) NOT NULL,
  `zaehler_bedingung_zahler` int(255) NOT NULL DEFAULT '0',
  `zufall` int(255) NOT NULL DEFAULT '0',
  `item_id` varchar(255) NOT NULL,
  `item_cls` varchar(255) NOT NULL,
  `item_anz_bedingung` int(255) NOT NULL DEFAULT '0',
  `item_anz_bedingung_wert` varchar(255) NOT NULL,
  `item_anz_bedingung_zahler` int(255) NOT NULL DEFAULT '0',
  `gold_bedingung` int(255) NOT NULL DEFAULT '0',
  `gold_bedingung_wert` varchar(255) NOT NULL,
  `goldinbank_bedingung` int(255) NOT NULL DEFAULT '0',
  `goldinbank_bedingung_wert` varchar(255) NOT NULL,
  `gems_bedingung` int(255) NOT NULL DEFAULT '0',
  `gems_bedingung_wert` varchar(255) NOT NULL,
  `gemsinbank_bedingung` int(255) NOT NULL DEFAULT '0',
  `gemsinbank_bedingung_wert` varchar(255) NOT NULL,
  `level_bedingung` int(255) NOT NULL DEFAULT '0',
  `level_bedingung_wert` varchar(255) NOT NULL,
  `dk_bedingung` int(255) NOT NULL DEFAULT '0',
  `dk_bedingung_wert` varchar(255) NOT NULL,
  `wks_bedingung` int(255) NOT NULL DEFAULT '0',
  `wks_bedingung_wert` varchar(255) NOT NULL,
  `gf_bedingung` int(255) NOT NULL DEFAULT '0',
  `gf_bedingung_wert` varchar(255) NOT NULL,
  `rune_ident` int(255) NOT NULL DEFAULT '0',
  `implode_wther` text NOT NULL,
  `implode_monat` text NOT NULL,
  `implode_tag` text NOT NULL,
  `minstd` text NOT NULL,
  `maxstd` text NOT NULL,
  `must_questid` int(255) NOT NULL DEFAULT '0',
  `is_drunk` int(255) NOT NULL DEFAULT '0',
  `implode_male` text NOT NULL,
  `has_house` int(255) NOT NULL DEFAULT '0',
  `has_disc` int(255) NOT NULL DEFAULT '0',
  `is_health` int(255) NOT NULL DEFAULT '0',
  `has_horse` tinyint(1) NOT NULL DEFAULT '0',
  `titel_bedingung` varchar(255) NOT NULL,
  `has_bathi` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `activ` (`activ`),
  KEY `sort` (`sort`),
  KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `quest_bedingung`
--

LOCK TABLES `quest_bedingung` WRITE;
/*!40000 ALTER TABLE `quest_bedingung` DISABLE KEYS */;
/*!40000 ALTER TABLE `quest_bedingung` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `quest_effekte`
--

DROP TABLE IF EXISTS `quest_effekte`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `quest_effekte` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `activ` tinyint(1) NOT NULL DEFAULT '0',
  `sort` int(255) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `is_teleport` tinyint(1) NOT NULL DEFAULT '0',
  `teleport_ort` int(255) NOT NULL DEFAULT '0',
  `buff_buff_name` varchar(255) NOT NULL,
  `buff_roundmsg` varchar(255) NOT NULL,
  `buff_wearoff` varchar(255) NOT NULL,
  `buff_effectmsg` varchar(255) NOT NULL,
  `buff_effectnodmgmsg` varchar(255) NOT NULL,
  `buff_effectfailmsg` varchar(255) NOT NULL,
  `buff_rounds` int(255) NOT NULL DEFAULT '0',
  `buff_atkmod` double NOT NULL,
  `buff_defmod` double NOT NULL,
  `buff_regen` double NOT NULL,
  `buff_minioncount` double NOT NULL,
  `buff_minbadguydamage` varchar(255) NOT NULL,
  `buff_maxbadguydamage` varchar(255) NOT NULL,
  `buff_lifetap` double NOT NULL,
  `buff_damageshield` double NOT NULL,
  `buff_badguydmgmod` double NOT NULL,
  `buff_badguyatkmod` double NOT NULL,
  `buff_badguydefmod` double NOT NULL,
  `buff_activate` varchar(255) NOT NULL,
  `buff_survive_death` int(255) NOT NULL DEFAULT '0',
  `zaehlerid` int(255) NOT NULL DEFAULT '0',
  `zaehler_bedingung` int(255) NOT NULL DEFAULT '0',
  `zaehler_bedingung_wert` varchar(255) NOT NULL,
  `zaehler_bedingung_zahler` int(255) NOT NULL DEFAULT '0',
  `item_give_id` varchar(255) NOT NULL,
  `item_give_anz` int(255) NOT NULL DEFAULT '0',
  `item_take_id` varchar(255) NOT NULL,
  `item_take_anz` int(255) NOT NULL DEFAULT '0',
  `is_death` tinyint(1) NOT NULL DEFAULT '0',
  `reputation_bedingung` int(255) NOT NULL,
  `reputation_bedingung_wert` varchar(255) NOT NULL,
  `gold_bedingung` int(255) NOT NULL DEFAULT '0',
  `gold_bedingung_wert` varchar(255) NOT NULL,
  `goldinbank_bedingung` int(255) NOT NULL DEFAULT '0',
  `goldinbank_bedingung_wert` varchar(255) NOT NULL,
  `gems_bedingung` int(255) NOT NULL DEFAULT '0',
  `gems_bedingung_wert` varchar(255) NOT NULL,
  `gemsinbank_bedingung` int(255) NOT NULL DEFAULT '0',
  `gemsinbank_bedingung_wert` varchar(255) NOT NULL,
  `charm_bedingung` int(255) NOT NULL DEFAULT '0',
  `charm_bedingung_wert` varchar(255) NOT NULL,
  `turns_bedingung` int(255) NOT NULL DEFAULT '0',
  `turns_bedingung_wert` varchar(255) NOT NULL,
  `gravefights_bedingung` int(255) NOT NULL DEFAULT '0',
  `gravefights_bedingung_wert` varchar(255) NOT NULL,
  `drunkenness_bedingung` int(255) NOT NULL DEFAULT '0',
  `drunkenness_bedingung_wert` varchar(255) NOT NULL,
  `playerfights_bedingung` int(255) NOT NULL DEFAULT '0',
  `playerfights_bedingung_wert` varchar(255) NOT NULL,
  `hitpoints_bedingung` int(255) NOT NULL DEFAULT '0',
  `hitpoints_bedingung_wert` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `activ` (`activ`),
  KEY `sort` (`sort`),
  KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `quest_effekte`
--

LOCK TABLES `quest_effekte` WRITE;
/*!40000 ALTER TABLE `quest_effekte` DISABLE KEYS */;
/*!40000 ALTER TABLE `quest_effekte` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `quest_events_interact`
--

DROP TABLE IF EXISTS `quest_events_interact`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `quest_events_interact` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `activ` tinyint(1) NOT NULL DEFAULT '0',
  `sort` int(255) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL,
  `link` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `ort` int(255) NOT NULL DEFAULT '0',
  `typ` int(255) NOT NULL DEFAULT '0',
  `start_out` text NOT NULL,
  `middle_out` text NOT NULL,
  `end_out` text NOT NULL,
  `is_kampf_person` tinyint(1) NOT NULL DEFAULT '0',
  `kampf_personid_name` text NOT NULL,
  `kampf_personid_level` int(255) NOT NULL DEFAULT '0',
  `is_kampf_monster` tinyint(1) NOT NULL DEFAULT '0',
  `kampf_monsterid_level` int(255) NOT NULL DEFAULT '0',
  `kampf_aus_erfolg` text NOT NULL,
  `kampf_aus_not_erfolg` text NOT NULL,
  `implode_sehen_bedingung` text NOT NULL,
  `implode_efk_start` text NOT NULL,
  `implode_efk_end` text NOT NULL,
  `implode_kampf_monsterid` text NOT NULL,
  `implode_kampf_erfolg_efkuz` text NOT NULL,
  `implode_kampf_not_erfolg_efkuz` text NOT NULL,
  `questid` int(255) NOT NULL DEFAULT '0',
  `interactid` int(255) NOT NULL DEFAULT '0',
  `questname` varchar(255) NOT NULL,
  `is_kampf_eigen` tinyint(1) NOT NULL DEFAULT '0',
  `kampf_eigen_creaturename` varchar(255) NOT NULL,
  `kampf_eigen_creaturelevel` varchar(255) NOT NULL,
  `kampf_eigen_creatureweapon` varchar(255) NOT NULL,
  `kampf_eigen_creatureattack` varchar(255) NOT NULL,
  `kampf_eigen_creaturedefense` varchar(255) NOT NULL,
  `kampf_eigen_creaturehealth` varchar(255) NOT NULL,
  `kampf_eigen_creatureanz` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `activ` (`activ`),
  KEY `sort` (`sort`),
  KEY `ort` (`ort`),
  KEY `typ` (`typ`),
  KEY `questid` (`questid`),
  KEY `interactid` (`interactid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `quest_events_interact`
--

LOCK TABLES `quest_events_interact` WRITE;
/*!40000 ALTER TABLE `quest_events_interact` DISABLE KEYS */;
/*!40000 ALTER TABLE `quest_events_interact` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `quest_events_orte`
--

DROP TABLE IF EXISTS `quest_events_orte`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `quest_events_orte` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `activ` tinyint(1) NOT NULL DEFAULT '0',
  `sort` int(255) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL,
  `nav` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `ort` int(255) NOT NULL DEFAULT '0',
  `dificulty` int(255) NOT NULL DEFAULT '0',
  `verfall` int(255) NOT NULL DEFAULT '0',
  `start_out` text NOT NULL,
  `middle_out` text NOT NULL,
  `end_out` text NOT NULL,
  `gold` varchar(255) NOT NULL,
  `gems` varchar(255) NOT NULL,
  `charme` varchar(255) NOT NULL,
  `dps` varchar(255) NOT NULL,
  `plp` varchar(255) NOT NULL,
  `wks` varchar(255) NOT NULL,
  `implode_start_effekt` text NOT NULL,
  `implode_end_effekt` text NOT NULL,
  `gfs` varchar(255) NOT NULL,
  `gefal` varchar(255) NOT NULL,
  `exp` varchar(255) NOT NULL,
  `implode_sehen_bedingung` text NOT NULL,
  `implode_belohnung_bedingung` text NOT NULL,
  `implode_items` text NOT NULL,
  `questname` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `activ` (`activ`),
  KEY `sort` (`sort`),
  KEY `ort` (`ort`),
  KEY `dificulty` (`dificulty`),
  KEY `verfall` (`verfall`),
  KEY `questname` (`questname`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `quest_events_orte`
--

LOCK TABLES `quest_events_orte` WRITE;
/*!40000 ALTER TABLE `quest_events_orte` DISABLE KEYS */;
/*!40000 ALTER TABLE `quest_events_orte` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `quest_orte`
--

DROP TABLE IF EXISTS `quest_orte`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `quest_orte` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `activ` tinyint(1) NOT NULL DEFAULT '0',
  `sort` int(255) NOT NULL DEFAULT '0',
  `name` text NOT NULL,
  `link` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `activ` (`activ`),
  KEY `sort` (`sort`),
  KEY `link` (`link`)
) ENGINE=InnoDB AUTO_INCREMENT=126 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `quest_orte`
--

LOCK TABLES `quest_orte` WRITE;
/*!40000 ALTER TABLE `quest_orte` DISABLE KEYS */;
INSERT INTO `quest_orte` VALUES (1,1,0,'Stadtzentrum','village.php'),(2,1,0,'Marktplatz','market.php'),(3,1,0,'Schenke zum Eberkopf','inn.php'),(4,1,0,'Rosengarten','gardens.php'),(5,1,0,'Krankenlager','krankenlager.php'),(6,1,0,'Bank','bank.php'),(7,1,0,'Johannas Kräuterlädchen','herbalist.php'),(8,1,0,'Vessas Zelt','gypsy.php'),(9,1,0,'Nadelflinks Nähstube	','dressmaker.php'),(10,1,0,'Bücherladen','bookstore.php'),(11,1,0,'Parfümerie','perfume.php'),(12,1,0,'Bäcker','bakery.php'),(13,1,0,'Metzger','butcher.php'),(14,1,0,'Barbier','barber.php'),(15,1,0,'Patisserie','coffeehouse.php'),(16,1,0,'Stadttor','dorftor.php'),(17,1,0,'Festwiese ','dorffest.php?op=meadow'),(18,1,0,'Friedhof','friedhof.php'),(19,1,0,'Waldsee','pool.php'),(20,1,0,'Froschteich','frogs.php'),(21,1,0,'Steg Waldsee','fish.php'),(22,1,0,'Nebelpfad','nebelgebirge.php'),(23,1,0,'Nebeltal','nebelgebirge.php?op=tal'),(24,1,0,'Gebirgsbach','nebelgebirge.php?op=river'),(25,1,0,'Berghang','nebelgebirge.php?op=berg'),(26,1,0,'Dunkler Wald','nebelgebirge.php?op=wald'),(27,1,0,'Große Eiche','greatoaktree.php'),(28,1,0,'Runenmeister','runemaster.php'),(29,1,0,'Seltsamer Felsen','rock.php'),(30,1,0,'Jägerhütte','lodge.php'),(31,1,0,'Gladiatorenschule','gladiator.php'),(32,1,0,'Kerker','prison.php'),(33,1,0,'Marducs Akademie','academy.php'),(34,1,0,'Trainingslager','train.php'),(35,1,0,'Gildenviertel','dg_main.php'),(36,1,0,'Vergnügungsviertel','nobelviertel.php'),(37,1,0,'Caesars Badehaus','badehaus.php'),(38,1,0,'Stadtfest Tanzfläche','dorffest.php?op=dance'),(39,1,0,'Ballsaal','nobelviertel.php?op=tanz'),(40,1,0,'Pavillon','nobelviertel.php?op=pavillon'),(41,1,0,'Park','nobelviertel.php?op=park'),(42,1,0,'Schloss','snobelviertel.php?op=palace'),(43,1,0,'Wohnviertel','houses.php'),(44,1,0,'Spielplatz','spielplatz.php'),(45,1,0,'Dorfbrunnen','well.php'),(46,1,0,'Dunkle Gasse','slums.php'),(47,1,0,'Verlassenes Haus','slums.php?op=oldhouse'),(48,1,0,'Gerber','tanner.php'),(49,1,0,'Irgendwo in der dunklen Gasse','slums.php?op=stat'),(50,1,0,'Josés Taverne','tittytwister.php'),(51,1,0,'Katakomben','lowercity.php?op=katakomben'),(52,1,0,'Gruft','lowercity.php?op=gruft'),(53,1,0,'Höhle','lowercity.php?op=hoehle'),(54,1,0,'Unterirdischer See','lowercity.php?op=see'),(55,1,0,'Kreuzung','forest_rpg_places.php'),(56,1,0,'Hafen','hafen.php'),(57,1,0,'Leuchtturm','hafen.php?op=turm'),(58,1,0,'Strand','hafen.php?op=strand'),(59,1,0,'Piratennest ','pirates.php'),(60,1,0,'Tiefer dunkler Wald','forest_rpg_places.php?op=deepforest'),(61,1,0,'Seilbrücke','ropeway.php'),(62,1,0,'Ritualplatz','forest_rpg_places.php?op=ritualplace'),(63,1,0,'Moor','forest_rpg_places.php?op=moor'),(64,1,0,'Kloster','forest_rpg_places.php?op=abbey'),(65,1,0,'Bettelstein','beggar.php'),(66,1,0,'Goldschrein ','downthedrain.php'),(67,1,0,'Drachenbücherei ','library.php'),(68,1,0,'Drachenmuseum','dragonmuseum.php'),(69,1,0,'Zigeunerlager ','gypsys.php'),(70,1,0,'Mitternachtskarneval ','gypsys.php?op=zirkus'),(71,1,0,'Marktstände','usershops.php?op=list&subop=showintro'),(72,1,0,'Rathaus','dorfamt.php'),(73,1,0,'Plumpsklo','outhouse.php'),(74,1,0,'Wald','forest.php'),(75,1,0,'Totenreich Halle der Geister','halle_der_geister.php'),(76,1,0,'Totenreich Friedhof der Seelen','graveyard.php'),(77,1,0,'Totenreich Alter Geist','halle_der_geister.php?op=oldspirit'),(78,1,0,'Totenreich Ahnenschrein','graveyard.php?op=shrine'),(79,1,0,'Totenreich Schatten','shades.php'),(80,1,0,'Cedrik','inn.php?op=bartender'),(81,1,0,'Wolkeninsel','wolkeninsel.php?op=insel'),(82,1,0,'Tempel','tempel.php'),(83,1,0,'Weihnachtsmarkt','weihnachtsmarkt.php'),(84,1,0,'Weihnachtsmarkt Schneemannbauen','weihnachtsmarkt.php?op=snowman'),(85,1,0,'Weihnachtsmarkt Glühweinstand','weihnachtsmarkt.php?op=gluh'),(86,1,0,'Weihnachtsmarkt Eislaufsee','weihnachtsmarkt.php?op=schlittschuh'),(87,1,0,'Weihnachtsmarkt Schneeballschlacht','weihnachtsmarkt.php?op=snowball'),(88,1,0,'Weihnachtsmarkt Gebäckstand','weihnachtsmarkt.php?op=geback'),(89,1,0,'Weihnachtsmarkt Weihnachtsbaum','weihnachtsmarkt.php?op=xmastree'),(90,1,0,'Weihnachtsmarkt Weihnachtskalender','weihnachtskalender.php?op=showpic'),(91,1,0,'Thorims Waffen','weapons.php'),(92,1,0,'Phaedras Rüstungen','armor.php'),(93,1,0,'Mericks Ställe','stables.php'),(94,1,0,'Goldenes-Ei-Raum','rock.php?op=egg'),(95,1,0,'Wanderhändler','vendor.php'),(96,1,0,'Hexenhaus','hexe.php'),(97,1,0,'Salon','coffeehouse.php?op=salon'),(98,1,0,'Lotterie','lottery.php'),(99,1,0,'Wanderdruide','tittytwister.php?op=seeddealer'),(100,1,0,'Barkeeper José','tittytwister.php?op=bartender'),(101,1,0,'Dunkler Gang','tittytwister.php?op=vampire'),(102,1,0,'Séparé','bordello.php'),(103,1,0,'Bergspitze','nebelgebirge.php?op=mountaintop'),(104,1,0,'TEXTORT Grotte','superuser.php'),(105,1,0,'Lagerschuppen','pirates.php?op=lager'),(106,1,0,'Mausoleum','friedhof.php?op=temple'),(107,1,0,'Goldmiene','paths.php?ziel=goldmine&pass=conf'),(108,1,0,'Wunschbrunnen','forest.php?op=well'),(109,1,0,'Zoxs Grill','forest.php?op=grill'),(110,1,0,'leeer','----'),(111,1,0,'Spelunke ','pirates.php?op=spelunke'),(112,1,0,'Weihnachtsmarkt Weihnachtsbaum - ','weihnachtsmarkt.php?op=xmastree'),(113,1,0,'Amphitheater','nobelviertel.php?op=theater'),(114,1,0,'Schnapper','schnapper.php'),(115,1,0,'Der alte Herr ','dragonslayerthetweeter.php'),(116,1,0,'Brunnen der Urd','well_of_urd.php'),(117,1,0,'Liste der Richter','court.php?op=listj'),(118,1,0,'Gerichtshof ','court.php'),(119,1,0,'Steuern bezahlen','dorfamt.php?op=steuernzahlen'),(120,1,0,'Kurbereich','badehaus.php?op=wellness'),(121,1,0,'Vorzimmerdame ','dorfamt.php?op=dame1'),(122,1,0,'Fürstliches Büro','dorfamt.php?op=office_entry'),(123,1,0,'Lagerfeuer','dorffest.php?op=fire&action=gossip'),(124,1,0,'Turnierplatz','pvparena.php'),(125,1,0,'Schaukel','gardens.php?op=swing');
/*!40000 ALTER TABLE `quest_orte` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `quest_user`
--

DROP TABLE IF EXISTS `quest_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `quest_user` (
  `id` int(255) unsigned NOT NULL AUTO_INCREMENT,
  `acctid` int(255) NOT NULL,
  `questid` int(11) NOT NULL,
  `step` int(255) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `age` int(255) NOT NULL DEFAULT '0',
  `date_start` datetime NOT NULL,
  `date_end` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `acctid` (`acctid`),
  KEY `questid` (`questid`),
  KEY `step` (`step`),
  KEY `status` (`status`),
  KEY `age` (`age`),
  KEY `date_start` (`date_start`),
  KEY `date_end` (`date_end`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `quest_user`
--

LOCK TABLES `quest_user` WRITE;
/*!40000 ALTER TABLE `quest_user` DISABLE KEYS */;
/*!40000 ALTER TABLE `quest_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `quest_user_zaehler`
--

DROP TABLE IF EXISTS `quest_user_zaehler`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `quest_user_zaehler` (
  `id` int(255) unsigned NOT NULL AUTO_INCREMENT,
  `acctid` int(255) NOT NULL,
  `zaehler` int(255) NOT NULL,
  `value` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `acctid` (`acctid`),
  KEY `zaehler` (`zaehler`),
  KEY `value` (`value`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `quest_user_zaehler`
--

LOCK TABLES `quest_user_zaehler` WRITE;
/*!40000 ALTER TABLE `quest_user_zaehler` DISABLE KEYS */;
/*!40000 ALTER TABLE `quest_user_zaehler` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `quest_zaehler`
--

DROP TABLE IF EXISTS `quest_zaehler`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `quest_zaehler` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `activ` tinyint(1) NOT NULL DEFAULT '0',
  `sort` int(255) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `up_fight_win` int(255) NOT NULL DEFAULT '0',
  `up_fight_loose` int(255) NOT NULL DEFAULT '0',
  `up_heal` int(255) NOT NULL DEFAULT '0',
  `up_die` int(255) NOT NULL DEFAULT '0',
  `up_nd` int(255) NOT NULL DEFAULT '0',
  `up_wb` int(255) NOT NULL DEFAULT '0',
  `rs_fight_win` int(255) NOT NULL DEFAULT '0',
  `rs_fight_loose` int(255) NOT NULL DEFAULT '0',
  `rs_heal` int(255) NOT NULL DEFAULT '0',
  `rs_die` int(255) NOT NULL DEFAULT '0',
  `rs_nd` int(255) NOT NULL DEFAULT '0',
  `rs_wb` int(255) NOT NULL DEFAULT '0',
  `up_ort` int(255) NOT NULL DEFAULT '0',
  `rs_ort` int(255) NOT NULL DEFAULT '0',
  `name_book` varchar(255) NOT NULL,
  `up_wfight_win` tinyint(1) NOT NULL DEFAULT '0',
  `up_wfight_loose` tinyint(1) NOT NULL DEFAULT '0',
  `up_gfight_win` tinyint(1) NOT NULL DEFAULT '0',
  `up_gfight_loose` tinyint(1) NOT NULL DEFAULT '0',
  `rs_wfight_win` tinyint(1) NOT NULL DEFAULT '0',
  `rs_wfight_loose` tinyint(1) NOT NULL DEFAULT '0',
  `rs_gfight_win` tinyint(1) NOT NULL DEFAULT '0',
  `rs_gfight_loose` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `activ` (`activ`),
  KEY `sort` (`sort`),
  KEY `up_fight_win` (`up_fight_win`),
  KEY `up_fight_loose` (`up_fight_loose`),
  KEY `up_heal` (`up_heal`),
  KEY `up_die` (`up_die`),
  KEY `up_nd` (`up_nd`),
  KEY `up_wb` (`up_wb`),
  KEY `rs_fight_win` (`rs_fight_win`),
  KEY `rs_fight_loose` (`rs_fight_loose`),
  KEY `rs_heal` (`rs_heal`),
  KEY `rs_die` (`rs_die`),
  KEY `rs_nd` (`rs_nd`),
  KEY `rs_wb` (`rs_wb`),
  KEY `up_ort` (`up_ort`),
  KEY `rs_ort` (`rs_ort`),
  KEY `up_wfight_win` (`up_wfight_win`),
  KEY `up_wfight_loose` (`up_wfight_loose`),
  KEY `up_gfight_win` (`up_gfight_win`),
  KEY `up_gfight_loose` (`up_gfight_loose`),
  KEY `rs_wfight_win` (`rs_wfight_win`),
  KEY `rs_wfight_loose` (`rs_wfight_loose`),
  KEY `rs_gfight_win` (`rs_gfight_win`),
  KEY `rs_gfight_loose` (`rs_gfight_loose`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `quest_zaehler`
--

LOCK TABLES `quest_zaehler` WRITE;
/*!40000 ALTER TABLE `quest_zaehler` DISABLE KEYS */;
/*!40000 ALTER TABLE `quest_zaehler` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `races`
--

DROP TABLE IF EXISTS `races`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `races` (
  `id` char(3) NOT NULL DEFAULT '',
  `name` varchar(40) NOT NULL DEFAULT '',
  `colname` varchar(60) NOT NULL DEFAULT '',
  `name_plur` varchar(44) NOT NULL DEFAULT '',
  `colname_plur` varchar(64) NOT NULL DEFAULT '',
  `active` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `loc_desc` varchar(255) NOT NULL DEFAULT '',
  `chosen_msg` text NOT NULL,
  `long_desc` text NOT NULL,
  `raceroom` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `raceroom_name` varchar(80) NOT NULL DEFAULT '',
  `raceroom_nav` varchar(80) NOT NULL DEFAULT '',
  `raceroom_desc` text NOT NULL,
  `raceroom_all` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `superuser` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `mindk` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `newday_msg` varchar(255) NOT NULL DEFAULT '',
  `boni` text NOT NULL,
  `specboni` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `active` (`active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Speichert Rassen.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `races`
--

LOCK TABLES `races` WRITE;
/*!40000 ALTER TABLE `races` DISABLE KEYS */;
INSERT INTO `races` VALUES ('avt','Avatar','`&Av`eat`)ar','Avatare','`&Av`eat`)are',1,'','Als `&Av`eat`)ar`0 bist du ein Wesen, dass außerhalb von Gut und Böse, von Licht und Schatten steht. Es gibt nur Einen, der noch über dir steht, und das ist dein Gott, dem du die ewige Treue geschworen hast. Neben dir gibt es noch Andere deinesgleichen, doch du hast das Gefühl, dass du ihm der Liebste von Allen bist.`nDeshalb hat er dich wohl auch in die Welt der Sterblichen geschickt, wo du als seine Inkarnation in seinem Namen unterwegs bist.`nDu bist zwar nun an die Gesetze dieser Welt gebunden, doch stehst du über Leben und Tod.','Die `&Av`eat`)are`0 gelten als die Wächter und das Bindeglied zwischen der Welt der Sterblichen und dem Übernatürlichen. Neutralität ist ihr oberstes Gebot und als Mitglied dieses elitären Kreises bist du nur dir und deinesgleichen Rechenschaft schuldig. Aber sei gewarnt! Wer sich in die Geschehnisse einmischt, kann schnell selbst zwischen die Fronten geraten. ',1,'`&L`ee`)e`er`&e`0','In die Leere','`&D`eu `)blickst dich kurz um, damit sicherstellend, dass dich niemand beobachtet und streckst dann deine Hände aus um das dünne Gewebe der Wirklichkeit zu zerreißen.\r\n`nDahinter ist nichts, die Leere, und so trittst du ein in das schwarzes Wabern. Die Grenzen von Zeit und Raum hinter dir lassend, kehrst zum Ursprung zurück. Langsam kehren deine Kräfte vollends zu dir zurückkehren und du spürst die Präsenz anderer Avatare.`n\r\nHier seid ihr nun unter euch, da dieser Ort außerhalb jeglicher Vorstellungskraft anderer Wesen existie`er`&t.',1,0,50,'','{\"attack\":\"-1\",\"defence\":\"3\"}','{}'),('dkl','Dunkelelf','`WDu`Cnk`Kele`hlf','Dunkelelfen','`WDu`Cnk`Kelel`hfen',1,'','`hDunkelelfen kennen Zeit ihres Lebens nichts anderes als Schmerz und Leid. Durch deine Agressivität startest du mit einem Angriffspunkte mehr!','Unbarmherzigkeit und ein wankelmütiges Temperament wird deinem Volk angedichtet. Viele `WDu`Cnk`Kelel`hfen`0 haben sich weit außerhalb der uralten Wälder im Gebirge und manche wenige sogar in der Nähe der Dörfer angesiedelt. Während helles Haar und dunkle Haut die Abstammung der Dunkelelfen des Unterreiches verrät, so kann ein hochgewachsener Elf mit fahler Haut und schwarzem Haar sich ebenfalls als Vertreter dieser finsteren Rasse entpuppen. Eines aber verbindet alle Arten der Dunkelelfen und das ist ihre volkstypische Heimtücke. Aber vielleicht unterscheidest du dich sogar von deinen unredlichen Brüdern und Schwestern...',1,'`WF`Ci`Kns`hter`Kwa`Cl`Wd','Zum Finsterwald','`WD`Cu `Kv`herlässt die festen Wege und gehst geradewegs in den Wald hinein. Anfangs ist der noch dünn bewachsen und recht hell. Schnellen Schrittes huschst du durch diesen Ort der Lichtkriecher und allmählich gelangst du immer tiefer in den Wald. Die Bäume scheinen hier größer und älter, ihr Blätterdach dichter. Du gehst weiter. Es wird immer dunkler und deine Augen leuchten violett auf. Du kannst natürlich auch bei dieser Dunkelheit hervorragend sehen und weißt auch genau wo du hin musst. Dann erreichst du dein Ziel, den Ort, an dem du deine Kindheit verbracht hast. Und du bist hier nicht allein, viele andere Dunkelelfen haben sich ebenfalls eingefunden. Du weißt, dass es nur Dunkelelfen möglich ist diesen Ort zu betreten und dass du die nächste Zeit Ruhe vor den unwürdigen Geschöpfen der Welt haben wirst. Leise Stimmen und hämisches Kichern dringen an dein Ohr. Du beschließt erstmal hier zu verweilen und Zeit mit deinesgleichen zu verbri`hn`Kg`Ce`Wn.',0,0,0,'','{\"attack\":\"2\",\"defence\":\"1\"}','{}'),('dmn','Dämon','`,Dä`Amo`4n','Dämonen','`,Däm`Aon`4en',1,'','Als niederer `,Dä`Amo`4n `0führst du ein Doppelleben in der Gesellschaft der Menschen, immer darum bemüht, dass du nie ungewollt als das erkannt wirst, was du bist. Deine ungeheure Stärke und Zähigkeit machen dich zu einem hervorragenden Kämpfer und Jäger. Du erhältst jeweils 1 Punkt in Angriff und Verteidigung, jedoch hast du drei Waldkämpfe weniger zur Verfügung!','Als `,Dä`Amo`4n`0 hast du in dieser Welt viele Gesichter, aber jedes davon birgt für die Normalsterblichen Kummer und Leid. Mit deinen außergewöhnlichen Fähigkeiten ist es ein Leichtes für dich, weitestgehend unerkannt unter den Menschen zu leben. Das ist auch nötig, denn wer versucht, die Waagschale zwischen Gut und Böse ins Wanken zu bringen, zieht viele Feinde an.',1,'`,S`Ac`4hwefelquell`Ae`,n','Zu den Schwefelquellen','`,D`Au s`4chreitest durch den tiefen Wald, knickst dünne Bäume und Sträucher um und schlägst eine Schneise durch den dichten Wuchs. Du weißt genau wo du hin willst und plötzlich vernimmst du auch diesen wohlig stechenden Geruch in deiner Nase. Nur ein paar Schritte später liegen sie vor dir: die blubbernden heißen Quellen. Der beißende Schwefelgeruch ist so stark, dass kein Mensch und auch kein anderes diesseitiges Wesen hier überleben würde.`n\r\nDu kannst dir ganz sicher sein, dass du an diesem Ort nicht gestört wirst und erblickst auch schon ein paar andere Dämonen, die sich ebenfalls hierhin zurückgezogen hab`Ae`,n.',0,0,0,'`&Weil Du ein Dämon bist hast du `^3`& Waldkämpfe weniger!','{\"attack\":\"2\",\"defence\":\"2\",\"turns\":\"-3\"}','{}'),('ecs','Echsenwesen','`PEch`kse`Gnw`ges`aen','Echsenwesen','`PEch`kse`Gnw`ges`aen',1,'','`PAls Echsenwesen hast du durch deine Andersartigkeit bereits viele schlechte Erfahrungen gemacht, und bist deswegen besonders vorsichtig`n`^Du startest mit einem Bonus auf deine Verteidigung!','Dein exotisches Volk soll ursprünglich in den öden Landschaften weit außerhalb der menschlichen Siedlungen beheimatet sein. Aufgrund deiner unverkennbaren Andersartigkeit, hast du es nicht leicht in dieser Welt und kämpfst oft mit den Vorurteilen der bäuerlichen Dörfler. Trotzdem erlaubt es dir dein ungebrochener Stolz als Kaltblüter von Zeit zu Zeit die Annehmlichkeiten der Städte zu beanspruchen.',1,'`PE`kc`Ghs`ge`ansü`gm`Gp`kf`Pe','In die Echsensümpfe','`PD`ku `Gb`ge`agibst dich in die tiefen Sümpfe. Der Boden unter deinen Füßen wird schließlich feuchter und gibt immer mehr nach. Dank den feinen Häuten zwischen deinen Zehen gehst du jedoch nicht unter, sondern kannst dich sicher weiter und tiefer in die Sümpfe begeben. Jeder Andere, so bist du dir sicher, wäre schon längst versunken. Dann, nach einer Weile des sumpfigen Marsches, erblickst du die erste Behausung, die aus dem Sumpfboden ragt. An diesem Ort bist du aufgewachsen und hierhin zieht es dich auch immer wieder zurück. Du weißt, dass nur Echsen diesen Ort betreten können und schaust dich nach deinen Freunden und Bekannten um. Dann beschließt du für eine Weile hier zu bleiben und es dir gut gehen zu lassen. Der süßlich faulige Geruch der Sümpfe, den du in der Stadt so sehr vermisst hast, dringt in deine Nase und du hast das schöne Gefühl Zuhause zu `gs`Ge`ki`Pn.',0,0,0,'','{\"attack\":\"1\",\"defence\":\"2\"}','{}'),('elf','Elf','`IE`}l`uf','Elfen','`IEl`}fe`un',1,'','`IAls Elf bist du dir immer allem bewusst, was um dich herum passiert. Nur sehr wenig kann dich überraschen.`nDu bekommst einen zusätzlichen Punkt auf deinen Verteidigungswert!','Um die scheinbar unvergängliche Anmut und Schönheit wird das Volk der `IEl`}fe`un`0 vielerorts beneidet. Den Menschen nicht unähnlich, blickt deine Rasse aber oft mit zuviel Hochmut auf den Rest dieser Welt hinab. Angehörigen deines Volkes wird eine große Naturverbundenheit nachgesagt, aber auch überragende Fähigkeiten in der Kampf- und Schmiedekunst.',1,'`IE`}l`ufenha`}i`In','Zum Elfenhain','`ID`}u `ufolgst deinen feinen Sinnen in der Gewissheit, dass sie dich zu dem geheimen, verborgenen Ort führen mögen. Und dann erblickst du auch schon die kleinen Baumhäuser, die so elegant anmuten, so als seien sie natürlich in den Baum gewachsen. Ein wohliges Kribbeln durchfährt deinen Körper, als du die ersten Elfen erblickst, die dir freudig aus den Höhen der Bäume entgegen winken. Du bist froh hier zu sein, keine stinkenden Orks, keine lauten Trolle. Du genießt die Stille und die Gesellschaft deiner Freunde, in der ruhigen Gewissheit, dass es nur Elfen möglich ist diesen Ort zu find`}e`In.',0,0,0,'','{\"attack\":\"1\",\"defence\":\"2\"}','{}'),('eng','Engel','`&En`yg`/el','Engel','`&En`yg`/el',1,'','Als `&En`yg`/el`0 warst du einst ein reines Wesen, geboren aus Licht und mit der Macht die Heerscharen der Himmel zu befehligen.`nNun aber bist du in die Welt der Sterblichen vorgedrungen, und obwohl deine Kräfte noch immer vorhanden sind, sind sie hier lediglich eine Spur, verglichen mit dem was sie einst waren; deine Flügel nur sichtbar für Wesen von reinem Herzen.`nWas dich hierher trieb, weißt nur du selbst, doch du spürst, dass die Boshaftigkeit hier dein reines Wesen verdirbt und dich in die Schatten zu ziehen droht.`nDu erhältst 2 Verteidigungspunkte, die du aber in deiner Angriffsstärke einbüßt.','Von Gläubigen wirst du verehrt und oftmals um Beistand gebeten. Doch als himmlisches Wesen auf Erden ist die Geheimhaltung deiner Besonderheit oberstes Gebot, schließlich gibt es auch für dich Feinde in dieser Welt! Mutmaßungen zufolge wandeln einige Wenige von euch unter den Menschen, welche einst in der Gunst eures Herrn sanken und nun in seinem Reich nicht mehr willkommen sind.',1,'`&W`yo`/lkenfestu`yn`&g','In die Wolkenfestung','`&D`yu `/schreitest durch den Wald, frei und unbekümmert. An einer Lichtung holst du dann tief Luft und löst dich von der beklemmenden Hülle, die dir dein Körper ist.\r\nDu schwebst hinauf wie du es gewohnt bist, hoch zu den Wolken. Die Welt unter dir wird immer kleiner und unwirklicher. Als du das Wolkendach durchbrochen hast, stellst du fest, dass du nicht allein bist. Andere haben sich wie du hierher zurückgezogen, um sich von den Strapazen der Sterblichkeit zu erholen. Du weißt, dass euch hier niemand stören kann und so verweilst ein wenig bei Deinesgleich`ye`&n.',0,0,0,'','{\"attack\":\"-1\",\"defence\":\"3\"}','{}'),('gbl','Goblin','`aGo`gbli`yn','Goblins','`aGo`gbli`yns',1,'','`aAls Goblin ist die Welt ein gefährlicher Ort für dich. `n Du bist stark und kannst dich verteidigen. `n `aDu erhältst einen Angriffs- und Verteidigungspunkt, aber du hast weniger Waldkämpfe!','Als `aGo`gbli`yn`0 genießt du häufig kein sehr hohes Anwesen in dieser Welt. Ihr geltet als kleine, schwache aber hinterlistige Kreaturen, mit einem Hang zu boshaften Machenschaften. Oft gehalten in Sklaverei, lebt dein Volk lieber versteckt in den Wäldern rund um die Siedlungen der Menschen und pflegt nur selten Kontakt zu diesen.',2,'`aG`gob`ylin`gba`au','In den Goblinbau','`aD`gu `y flitzt durch das Wohnviertel, huschst an Häuserecken vorbei und verlässt das Wohnviertel um zum dem hügeligen Gebiet abseits der Stadt zu gelangen. Eine ganze Weile bist du unterwegs, bis du die gut versteckten Hügelhäuser entdeckst, die deine Sippe errichtet hat. Du schlüpfst durch einen der schmalen kleinen Eingänge und weißt, dass nicht einmal ein Menschenkind diese schmale Pforte passieren kann und dass sich hier nur Goblins aufhalten. Du begibst dich tief in das halbunterirdische Gewölbe und triffst viele andere deiner Art. Ein wohl bekanntes Geschrei tritt an dein Ohr und du fühlst dich daheim. Hier bist du mit deinen Freunden allein und niemand wird eure Zusammenkunft stören können. Du beschließt eine Weile hier zu bleiben, bevor du dich wieder in die gefährliche Welt der Menschen begib`gs`at.',0,0,0,'`&Weil Du ein Goblin bist, erhältst du `^1`& Waldkampf weniger!','{\"attack\":\"2\",\"defence\":\"2\",\"turns\":\"-1\"}','{}'),('hbl','Halbling','`yHa`tlb`}li`qng','Halblinge','`yHa`tlbl`}in`qge',1,'','Als `yHa`tlb`}li`qng`0 bist du klein und unscheinbar. Du bist zwar schwach, aber geschickt und weisst genau wie du die Leute um ihre Taschen erleichtern kannst. Immer zum Feiern aufgelegt ist schlechte Laune für dich ein Fremdwort.','In den abgelegenen Dörfern der `yHa`tlbl`}in`qge`0 hätte ein ruhiges und beschauliches Leben auf dich gewartet. Viele wissen nicht mal von der Existenz deines Volkes, dessen Tage erfüllt sind von zahlreichen Mahlzeiten und großen Festen. Manche eurer Art hat es aber in die Welt hinaus gezogen, die von Wesen bevölkert wird, die so viel größer und kampfeslustiger sind als du.',2,'`yH`tü`}g`qelhäu`}s`te`yr','Zu den Hügelhäusern','`yD`tu `}s`qchlenderst durch das Wohnviertel und suchst die Weiten der grünen Ebenen weit abseits der dicht besiedelten Straßen. Dort ist es hügelig und die Natur ist unberührt.`n\r\nSchließlich gelangst du an einen großen hohlen Baum. Dort gibst du das geheime Klopfzeichen, dass dich deine Eltern einst gelehrt haben und wenig später fällt dir eine winzig kleine Tür in einem der Hügel auf. Gerade groß genug, dass du hindurchschlüpfen kannst.`n\r\nDu betrittst das kleine Häuschen und entdeckst, dass sich viele andere Halblinge hier versammelt haben, die dich mit einem guten Becher Ale und einer frisch gestopften Pfeife begrüßen. Du bist dir ganz sicher, dass ihr hier unter euch seid und entschließt ein wenig mit deinen Artgenossen zu fei`}e`tr`yn.',0,0,0,'','{\"attack\":\"1\",\"defence\":\"1\",\"spirits\":\"4\"}','{}'),('men','Mensch','`&Me`fn`*sc`Fh','Menschen','`&Me`fns`*ch`Fen',1,'','`&Deine Größe und Stärke als Mensch erlaubt es dir, Waffen ohne große Anstrengungen zu führen und dadurch länger durchzuhalten, als andere Rassen.`n`^Du hast jeden Tag einen zusätzlichen Waldkampf!','Als gewöhnlicher `&Me`fn`*sc`Fh`0 stellst du die meistvertretene Rasse in dieser Welt dar. Für manche bist du Jäger, für andere Gejagter, denn dir ist kein ungewöhnlich langes Leben oder besondere Stärke vorbestimmt. Ob nun König oder Bettler, gut oder böse, deine Rasse hat dennoch Großes vollbracht und über Jahrhunderte Königreiche, Städte und Dörfer errichtet. ',2,'`&V`fe`*r`Fsammlungsr`*a`fu`&m','In den Versammlungsraum','`&D`fu `*g`Fehst schnellen Schrittes durch die Straßen, in der Hoffnung dieses eine Haus zu finden. Hier sehen alle Gebäude gleich aus und wüsstest du nicht wo du hin musst, wäre es dir unmöglich dich zurechtzufinden. Dann plötzlich stehst du vor einem Gutshaus, von dem du glaubst, dass es das Richtige ist. Du schlägst den wuchtigen Türklopfer gegen die beschlagene Eichtür - zweimal kurz, einmal lang, dreimal kurz. Eine kleine Klappe schiebt sich auf und ein Augenpaar mustert dich. Dann wird die Tür geöffnet und du wirst steinerne Stufen in ein großes Gewölbe geführt. Hier haben sich Menschen zusammen gefunden. Sie essen, trinken, amüsieren sich. Du wirst freudig begrüßt und man weist dir einen Platz zu. Die Gewissheit, dass nur Menschen Zugang zu dieser Halle haben, erlaubt dir dich einmal zu entspannen und mit deinen Freunden zu fei`*e`fr`&n.',0,0,0,'`&Weil du ein Mensch bist, bekommst du `^1`& Waldkampf zusätzlich!','{\"attack\":\"1\",\"defence\":\"1\",\"turns\":\"1\"}','{}'),('mwn','Meerwesen','`§Me`3er`#wes`Fen','Meerwesen','`§Me`3er`#wes`Fen',1,'','Als Geschöpf des Meeres fühlst du dich an Land nicht besonders wohl. Du verlierst deswegen einen Waldkampf. Da du deiner Umgebung allerdings besondere Aufmerksamkeit entgegenbringst, wirkt sich das positiv auf deinen Verteidigungswert aus. Du erhälst zwei Bonuspunkte!','Du bist ein Geschöpf des Meeres und fühlst eine tiefe Verbundenheit zum angrenzenden Ozean sowie anderen Gewässern - Jene sind dein wahres Element.  Vielen Meeresbewohnern werden mystische Kräfte nachgesagt, aber warum du dich an Land begabst, um unter Menschen zu sein, bleibt dein Geheimnis.',1,'`§M`3e`#e`Fresgro`#t`3t`§e','Zur Meeresgrotte','`§D`3u `#b`Fegibst dich durch den Wald bis zum Strand und wanderst die Brandung entlang, begleitet vom Geschrei einiger Möwen. Der Weg zur Grotte ist dir wohlbekannt und so tauchst du zielgerecht in die Tiefe des Meeres, bis du schließlich die Untergrundhöhle erreichst. Ein buntes Muster an verschiedenfarbigen Muscheln kennzeichnet den Rückzugsort deiner Artgenossen und du fühlst dich sogleich wie zuhause und dementsprechend wohl. Die Grotte besteht aus einem großen und mehreren kleinen Räumen, deren steinernde Wände und Decken mit Tropfsteinen, Muscheln und glitzernden Kristallen, welche das wenige Licht reflektieren, verziert sind. Du bist nicht allein in dieser Höhle, auch andere Meerwesen halten sich hier auf, spielen miteinander im Wasser, sammeln bunte Muscheln und Steine oder unterhalten sich einfach. Hier seid ihr unter euch und in Sicherheit, denn kein anderes Wesen, welches nicht dem Meer entsprungen ist, könnte diese Grotte entdec`#k`3e`§n.',0,0,0,'','{\"defence\":\"+2\",\"turns\":\"-1\"}','{}'),('npc','Besucher','Besucher','Besucher','Besucher',0,'','Schon als Kind hast du dich nirgends zu hause gefühlt. Du bist der ewige Fremdling, ein Besucher.','...du dich schon als Kind nirgends zu hause gefühlt hast. Du bist der ewige Fremdling, ein Besucher.',0,'','','',0,1,0,'Wenn du kein Besucher wärst könntest du an dieser Stelle einen Bonus bekommen.','{}','{}'),('ork','Ork','`JO`2r`jk','Orks','`JO`2r`jks',1,'','`jAls Ork `0 führst du ein einsames, nomadisches Lebens und bist ein ausgezeichneter Kämpfer. `JDu erhältst 2 Angriffspunkte und einen Verteidigungspunkt, aber weniger Waldkämpfe!','Für einen stämmigen `JO`2r`jk`0 sind eigentlich die Sümpfe und Wälder der bevorzugte Lebensraum, denn vielerorts wirst du wegen deiner beachtlichen Größe und Stärke gefürchtet. Deinem Volk eilt kein sonderlich guter Ruf voraus, aber das hält dich nicht davon ab, die Siedlungen der Menschen zu betreten.\r\n',1,'`JO`2r`jkfes`2t`Je','In die Orkfeste','`JD`2u `jholst die alte Karte hervor und folgst den verschlungenen Waldwegen, dein Ziel stets vor Augen. Obwohl es schwierig ist diesen Ort zu finden, erreichst du mit Hilfe der Karte endlich die Orkfeste. Du stellst dich vor das mächtige Eingangsportal und stößt einen markerschütternden Schrei aus. Schon öffnet sich die Türe und du wirst herein gelassen. Die Atmosphäre im Inneren der Orkfeste gefällt dir sehr. Viele andere Orks haben sich hier eingefunden und sind mit Speis und Trank, dem Erzählen wilder Kriegsgeschichten und Raufereien beschäftigt. Kaum hast du dich auf einer Holzbank niedergelassen drückt dir ein anderer Ork schon einen riesigen Krug Ale und eine Menschenkeule in die Hand. Hier kannst du es dir nun so richtig gut gehen lassen bevor du wieder zurück ins Dorf geh`2s`Jt.',0,0,0,'`&Weil Du ein Ork bist, erhältst du `^2`& Waldkämpfe weniger!','{\"attack\":\"3\",\"defence\":\"2\",\"turns\":\"-2\"}','{}'),('slm','Schelm','`wSc`{he`9lm','Schelme','`wSc`{he`9lme',1,'','`{Als Schelm`0 ist dir so ziemlich alles egal, was deine stets gute Laune trüben könnte. Denn bei den Feen hast du gelernt, was es bedeutet, Spass zu haben. Und so bist du für jede Albernheit zu haben. Jedoch solltest du aufpassen wem du einen Streich spielst, denn du bist recht schwach im Kampf. Durch deinen Frohmut und dein hohes Geschick erhältst du 3 zusätzliche Anwendungen in Gaukelei! Deine Stimmung wird nie schlecht sein!`nAber du verlierst jeweils 3 Punkte in Angriff und Verteidigung!','Geboren wurdest du als Mensch, aufgewachsen bist du aber in der Obhut der Feenwesen. Du verstehst dich bestens auf den Schabernack, den sie dir in den Jahren eures Zusammenlebens beigebracht haben. Mit zunehmendem Alter verloren sie aber das Interesse an dir und verbannten dich aus ihrem Reich. Jetzt streifst du umher, immer auf der Flucht vor den Opfern deiner letzten Scherze.',2,'`wS`{c`9helmenra`{u`wm','In den Schelmenraum','`wD`{u `9tänzelst durch das Wohnviertel auf der Suche nach einem ganz bestimmten Haus. Groß und seriös wirkend nimmt es seinen Platz zwischen den anderen Bauten ein. Du kletterst durch ein Kellerfenster hinein, steigst eine Treppe hinauf und öffnest eine schwere Eichentüre. Da sind sie versammelt, Andere, die so sind wie du, füllen den großen Raum. Lärm und Gelächter schlägt dir entgegen und du fühlst dich ein wenig an deine Kindheit bei den Feen erinnert. Du lauschst den Gesprächen, die sich für dich schnell als totaler Unsinn oder Berichte über Scherze mit argwöhnischen Menschen und Elfen herausstellen.`n\r\nHier fühlst du dich wohl, denn du weißt, dass niemand, der kein Schelm ist auch nur für eine Minute in diesem Raum verweilen könn`{t`we.',0,0,0,'`&Als Schelm erhältst du 3 Anwendungen in Gaukelei zusätzlich!','{\"attack\":\"-2\",\"defence\":\"-2\",\"spirits\":\"4\"}','{\"jugglery\":\"3\"}'),('trl','Troll','`|Tr`.o`hll','Trolle','`|Tr`.ol`hle',1,'','`.Als Troll warst du immer auf dich alleine gestellt. Die Möglichkeiten des Kampfs sind dir nicht fremd.`n`^Du erhältst einen zusätzlichen Punkt auf deinen Angriffswert!','In Höhlen, Sümpfen und den hohen Gebirgen sollen verschiedenste Arten deiner Rasse beheimatet sein. Trollen wird wenig List und Tücke nachgesagt, dafür gelten sie aber als unbarmherzige, starke Kämpfer mit einer Haut so fest wie Leder. Wundere dich also nicht, wenn die meisten Wesen dir mit Vorsicht und Misstrauen begegnen. ',1,'`|T`.r`hollfes`.t`|e','Zur Trollfeste','`|D`.u `hbetrittst die mächtige Trollfeste und endlich  kannst du dich wieder einmal frei bewegen, ohne die Angst bei jedem Schritt wogegen zu stoßen oder dir beim Durchschreiten einer Türschwelle den Kopf anzuschlagen.`n\r\nHier in der Trollfeste bist du unter deinesgleichen. Nur Trolle, wohin du auch blickst, kein menschliches Gewürm oder sonstiges störendes Getier.`n\r\nHier fühlst du dich wohl und an diesem Ort sind auch die meisten deiner Freunde anzutreffen. Das Ale fließt in Strömen und es gibt so viel Elf am Spieß wie du magst. Die Stimmung ist herrlich und du wirst sicherlich einige Zeit verweil`.e`|n.\r\n',0,0,0,'','{\"attack\":\"2\",\"defence\":\"1\"}','{}'),('vmp','Vampir','`$Va`4mp`Air','Vampire','`$Va`4mp`Aire',1,'','`4Als Vampir `0 wandelst du unter den Lebenden und bist ein Vertreter der dunklen Künste. Du erhältst einen Bonus im Angriff, verlierst aber einen Waldkampf.','Viele Mythen und Legenden ranken sich um dich. Zum `$Va`4mp`Air`0 wurdest du durch den Biss eines älteren Unsterblichen und kannst die Welt jetzt mit anderen Augen sehen. Dein unstillbarer Blutdurst bindet dich jedoch an die gewöhnlichen Sterblichen und so musst du auf der Hut sein, denn sollte einer von ihnen deine wahre Identität entdecken, könntest du schnell zum Gejagten werden.',2,'`$M`4a`Ausole`4u`$m','Zum Mausoleum','`$D`4u `Aschleichst durchs Wohnviertel und näherst dich zielsicher dem Friedhof. Vorbei an unzähligen Gräbern und Gruften bahnst du dir deinen Weg bis hin zu einem ganz bestimmten Bau. Du legst deine Hände auf das steinerne Türportal und es bewegt sich langsam zur Seite, um kurz nachdem du das Mausoleum betreten hast, wieder hinter dir zuzuschlagen. Du gehst einen langen Gang entlang und steigst eine Treppe hinab. So gelangst du in einen großen Raum, in dem sich schon andere Vampire eingefunden haben. Viele von ihnen kennst du bereits. Sie hängen an der Decke, ruhen oder unterhalten sich. Ein paar der Vampire haben eine junge, vor Schrecken starre Menschenfrau mitgebracht, die sie der Reihe nach durchreichen um von ihr zu trinken. Du weißt, dass sich hier nur Vampire aufhalten und das verleiht dir ein Gefühl von Sicherheit. Du setzt dich auf einen Sarg und winkst den anderen Vampiren, die du kennst, `4z`$u.',0,0,0,'Du spürst die immerwährende Macht der Sonne, die dich schwächt. Du verlierst einen Waldkampf.','{\"attack\":\"2\",\"defence\":\"1\",\"turns\":\"-1\"}','{}'),('wwf','Wertier','`YWe`Trt`;i`Se`Nr','Wertiere','`YWe`Trt`;i`Ser`Ne',1,'','`YWertiere`0 sind augenscheinlich gewöhnliche Menschen, die sich aber bei Mondlicht in gefährliche Kreaturen verwandeln. Sie sind unwahrscheinlich flink und aggressiv im Angriff.`n`SDu erhältst 2 Angriffspunkte, verlierst aber 3 Verteidigungspunkte.','Der Biss eines Artgenossen hat deinem Leben als gewöhnlichen Menschen eine entscheidende Wende gegeben. Deine animalische Seite, für manche Wertiere geknüpft an den Mondzyklus, hat dich mit empfindlichen Sinnen und besonderen Kräften ausgestattet. Deine Unberechenbarkeit macht dir aber nicht überall Freunde.',1,'`YW`Te`;r`St`Nierlich`St`;u`Tn`Yg','Zur Wertierlichtung','`YD`Tu `;f`So`Nlgst den Waldwegen ein kleines Stück und lässt dich anschließend von deinen Instinkten leiten. So wird der Wald immer dichter und die Sicht dadurch schlechter, doch irgendwie scheinst es dir nichts auszumachen. Du hast eine Witterung aufgenommen und eilst zielstrebig durch das dichte Unterholz, bis du die kleine Lichtung mitten im tiefen Wald findest.`n\r\nNeugierig blickst du dich um und entdeckst weitere Wesen, die dir ähnlich scheinen.\r\nDu ahnst, dass sie wohl dasselbe Schicksal wie du erleiden mussten und obwohl einige von ihnen Furcht einflößend aussehen, überkommt dich keine Angst.`n\r\nDieser Ort ist nur für deinesgleichen Zugang, also setzt du dich für ein Weilchen auf einen Stein und suchst das Gespräch mit den And`Se`;r`Te`Yn.',0,0,0,'','{\"attack\":\"3\",\"defence\":\"-2\"}','{}'),('zwg','Zwerg','`NZ`(we`)rg','Zwerge','`NZ`(we`)rge',1,'','`)Als Zwerg fällt es dir leicht, den Wert bestimmter Güter besser einzuschätzen.`n`(Du bekommst mehr Gold durch Waldkämpfe!','Als `NZ`(we`)rg`0 wird dir oft ein besonderes Verlangen nach Besitz und Reichtum unterstellt, das in keinem Verhältnis zu deiner Körpergröße steht. Eigentlich sind die Gebirge mit ihren unterirdischen Festungen und Stollen die Heimat deiner stolzen Rasse, doch seit dem Ende der großen Ära des Zwergenvolks, meidet ihr die Städte der Menschen nicht mehr.',2,'`NZ`(w`)ergengebir`(g`Ne','Zur Zwergenbinge','`ND`(u `)eilst durch das Wohnviertel, vorbei an den Wohnhäusern und Lagerhallen, bis du in immer dünner besiedeltes Gebiet kommst. Allmählich wird es auch hügeliger und du steuerst zielsicher auf einen großen Strauch zu. Dahinter verborgen führt eine steinerne Treppe mindestens 1000 Stufen tief ins Erdreich hinab. Du folgst der Treppe und sie endet vor einer kleinen stählernen Türe. Du rufst dein Losungswort und die Tür schwingt auf. Dahinter bietet sich dir ein wahrlich freudiger Anblick, gemütliche, kleine Räumchen, nicht so groß und weit dass man sich drin verlieren könnte, wie sie es in der Menschenwelt sind.`n\r\nWohin dein Auge auch nur blickt, siehst du Zwerge, beim Saufen, Grölen und beim Tratsch. An diesem Ort bist du richtig und hier kannst du es dir für eine Weile gut gehen lassen. Du weißt, dass niemand außer Zwergen hier Zugang hat und setzt dich auf ein großes Bierfass, was du zu leeren beabsichtig`(s`Nt.',0,0,0,'','{\"attack\":\"1\",\"defence\":\"1\"}','{}');
/*!40000 ALTER TABLE `races` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `random_commentary`
--

DROP TABLE IF EXISTS `random_commentary`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `random_commentary` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `section` varchar(30) NOT NULL DEFAULT '',
  `comment` text NOT NULL,
  `chance` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `gap` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `weather` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `month_min` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `month_max` tinyint(3) unsigned NOT NULL DEFAULT '12',
  `hour_min` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `hour_max` tinyint(3) unsigned NOT NULL DEFAULT '24',
  `rldate` date NOT NULL DEFAULT '0000-00-00',
  PRIMARY KEY (`id`),
  KEY `time` (`month_min`,`month_max`,`hour_min`,`hour_max`),
  KEY `section` (`section`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `random_commentary`
--

LOCK TABLES `random_commentary` WRITE;
/*!40000 ALTER TABLE `random_commentary` DISABLE KEYS */;
/*!40000 ALTER TABLE `random_commentary` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `referers`
--

DROP TABLE IF EXISTS `referers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `referers` (
  `refererid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uri` text,
  `count` int(11) DEFAULT NULL,
  `last` datetime DEFAULT NULL,
  `site` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`refererid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `referers`
--

LOCK TABLES `referers` WRITE;
/*!40000 ALTER TABLE `referers` DISABLE KEYS */;
/*!40000 ALTER TABLE `referers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `riddles`
--

DROP TABLE IF EXISTS `riddles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `riddles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `riddle` mediumtext NOT NULL,
  `answer` tinytext NOT NULL,
  `enabled` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `enabled` (`enabled`)
) ENGINE=InnoDB AUTO_INCREMENT=168 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `riddles`
--

LOCK TABLES `riddles` WRITE;
/*!40000 ALTER TABLE `riddles` DISABLE KEYS */;
INSERT INTO `riddles` VALUES (1,'Vor mir fährt die Polizei, `nHinter mir ne Kutsche. `nRechts von mir ein Flugzeug, `nLinks von mir die Eisenbahn.`nWO BIN ICH?','Karusell',1),(2,'Welcher Igel,`n Eins, zwei, drei,`n Legt ein Mahagohni-Ei?','Kastanie',1),(3,'Was liegt auf dem Rasen,`n und hat 44 Nasen?','Harke;Rechen',1),(4,'Vorne wie ein Kamm,`n Mitten wie ein Lamm,`n Hinten wie ne Sichel,`n nun rat, mein lieber Michel.','Kamel',1),(5,'Eines faulen Vaters Kind,`n und doch schneller als der Wind.','Licht',1),(6,'Was ich lebend steche tot,`n dem helf ich tot aus Todesnot.','Biene',1),(7,'Ri Ra Ripfel `n gelb ist der Zipfel, `n schwarz ist das Loch,`n wo man den Riraripfel drin kocht.','Erdäpfel',1),(8,'Am Anfang bin ich das Gegenteil von Land,`n in der Mitte die heilige Schrift,`n danach eine Geliebte des Zeus`n und am Ende auch noch das Lied`n das den schönsten Platz für Biertrinker bezeichnet.`n Alles in allem bin ich eine sehr respektable Bildungs- und Unterhaltungseinrichtung.','Stadtbibliothek',1),(9,'Mit den Augen kann man ihn sehen,`n aber nicht mit den Händen greifen.','Schatten',1),(10,'Dort hängt es an der Wand,`n Das gibt mir jeden Morgen die Hand.','Handtuch',1),(11,'Solange ich bei meinem Herrn bleibe,`n helfe ich ihm nichts.`n Aber sobald er mich weggibt,`n da helfe ich ihm.','Geld',1),(12,'Was sieht aus wie eine Katze,`n miaut wie eine Katze,`n aber ist keine Katze?','Kater',1),(13,'Ein Tal voll und ein Land voll,`n Und am End ists keine Handvoll.','Nebel',1),(14,'Wer es macht, der sagt es nicht,`n Wer es nimmt, der kennt es nicht,`n Wer es kennt, der nimmt es nicht.','Falschgeld',1),(15,'Er geht durch alle Gassen,`n klopft an Türen und Fenster`n und wird doch von niemand gesehen.','Wind',1),(16,'Wer besiegt selbst den stärksten Mann?','Schlaf;Tod;Zeit',1),(17,'Es kann sprechen und auch stechen,`n ist zugleich ein Vogel und ein Gebrechen.','Star',1),(19,'Verfertigt ists vor langer Zeit,`n doch mehrenteils gemacht erst heut;`n sehr schätzbar ist es seinem Herrn,`n und dennoch hütets niemand gern.','Bett',1),(20,'Der es macht, der will es nicht;`n der es trägt, behält es nicht;`n der es kauft, der braucht es nicht;`n der es hat, der weiß es nicht.','Sarg',1),(21,'Es ist kein Haus,`n doch baut man es,`n man ißt es nicht,`n doch kaut man es,`n wenn mans nicht kaut,`n verbrennt man es.`n Ihr kennt es; sagt:`n Wie nennt man es?','Tabak',1),(22,'Immer ist es nah,`n niemals ist es da.`n Wenn Du denkst, Du seist daran,`n nimmt es andern Namen an.','morgen',1),(23,'Was ist nackter als nackt,`n so nackt, daß es knackt?','Skelett',1),(24,'Rat, wenn du kannst!`n Es nennen einen Wanst`n fünf Zeichen dir,`n und auch die letzten vier.','Bauch',1),(25,'Ich habe Flügel, rate Kind,`n Doch flieg ich nur im Kreise,`n Und singen tu ich, wenn der Wind`n Mir vorpfeift, laut und leise;`n Was ihr den Feldern abgewinnt,`n Kau ich auf meine Weise,`n Doch - was mir durch die Kehle rinnt,`n Das mundet euch als Speise.','Windmühle',1),(26,'Du brichst es,`n sobald du es nennst.','Stille',1),(27,'Mein Erstes, das ist nicht die Sonne.`n Mein Zweites bringt Wahres nicht ans Licht.`n Drum geb ich oft nur trügerische Wonne`n Und stets ein ungewisses Licht.','Mondschein',1),(28,'Ich helfe Kisten laden,`n doch mach ich auch Charaden.','Hebel',1),(29,'Ich kenn ein warmes Haus,`n es hat drei Türen für rein und raus.`n Geht man morgens rein ins Haus,`n schauen unten Füße raus.`n Abends geht man wieder raus.`n Sag mir doch, wie heißt dies Haus?','Hose',1),(30,'Ich habe nur ein Angesicht.`n Es wird erhellt von fremdem Licht.`n Erhellts mich nicht,`n das fremde Licht,`n sieht man mich nicht.','Mond',1),(31,'Wenn du vorwärts mich beherrscht,`n darfst du fröhlich lachen;`n rückwärts kann dir alles sein,`n was die Gegner machen.','Lage; egal',1),(32,'Bin der Nachbar von Sonne und Sterne,`n regle das Wasser und tu dies sehr gerne.`n Bist Du verliebt, denkst Du müßtest verschmachten,`n Wirst Du die ganze Nacht mich betrachten.','Mond',1),(33,'Was sitzt still in einer Ecke,`n und reist doch um die ganze Welt?','Briefmarke',1),(34,'Was ist fertig und wird doch täglich neu gemacht?','Bett',1),(35,'Loch an Loch und hält doch!','Netz; Kette; Sieb',1),(36,'Es tuts der Mond, die Sonne`n das Herz, das Eis, der Teig,`n die Tür, die Naht, die Blume,`n die Saat, die Knosp am Zweig.','aufgehen',1),(37,'Getrennt mir heilig -`n vereint abscheulich.','Meineid',1),(38,'Ich habe kein Nest, ich hab\' keinen Bau,`n\r\nich ziehe gemütlich hinaus in die Welt.`n\r\nDoch hab ich immer ein Dach überm Kopf`n\r\nund schlafe niemals unterm Himmelszelt.','Schnecke',1),(39,'Vier Stämperli, vier Plämperli,`n zwei Stuferli zwei Horcherli`n zwei Gugguggerli, ein Heurupferli,`n ein Grasmuffeli und ein Fliegenwädeli.','Kuh',1),(40,'Ich habe keine Füße`n und geh doch auf und ab`n und beiß mich immer tiefer ein,`n bis ich mich durchgebissen hab.','Säge',1),(41,'Es hat keine Flügel und fliegt doch`n es hat keinen Schnabel und beißt doch.','Wind',1),(42,'Wenn man es braucht,`n wirft man es weg,`n wenn man es nicht braucht,`n holt man es wieder zurück.','Anker',1),(43,'Kennst du mich`n so freut es dich;`n kennst du mich nicht`n so suche mich nur emsiglich:`n Du findest mich`n ganz sicherlich.','Lösung; Ergebnis',1),(44,'Man sieht es in der Mitte der Nacht,`n man träumt nicht davon,`n dennoch sieht man es im Schlaf.`n Am Tag kommt man ganz ohne es aus.','C; c',1),(45,'Nicht nur am Tag, nein, auch in finstrer Nacht,`n kann ohne Licht man meilenweit mich sehen.`n Doch wehe dem, dem ich zu nahe komm!`n Im Augenblick ist es um ihn geschehen.','Blitz',1),(46,'Vorwärts Trauer über Trauer,`n rückwärts Glück, doch ohne Dauer.','Sieg; Geist',1),(47,'Ich bin ein Gewinn`n bei Whist und Skat;`n und, läßt man dich drin,`n so schreist du: Verrat!','Stich',1),(48,'Im Häuschen mit fünf Stübchen,`n da wohnen braune Bübchen.`n Nicht Tor noch Tür führn ein und aus ,`n wer sie besucht, der ißt das Haus.`n Wo haben die Bübchen ihre fünf Stübchen?','Apfel',1),(49,'Auf was ich vorwärts ihm mein Geld gegeben,`n ward rückwärts mir der Freund, da ich es wollt erheben.','Bank; Knab',1),(50,'Es bringt den Reiter um sein Roß,`nDen Edelmann um sein Schloß;`n Den Bauern um seinen Ackerpflug:`n Wer das errät, der ist wohl klug.','Würfelspiel; Glücksspiel; Spiel',1),(52,'Wer es tut mit \"aus\", der will verzichten,`n tust du es mit \"ab\", verweigerst du,`n tust du es mit \"ein\", so sagst du zu.`n Wer es tut mit \"zer\", der will vernichten,`n wer es tut mit \"nach\", will sich belehren,`n wer es tut mit \"vor\", den muß man hören,`n wer es tut mit \"über\", der ist flüchtig,`n wer es tut mit \"be\", der gilt als tüchtig.','schlagen',1),(53,'Die Erste ist das in trocknem Zustande,`n Was frisch als die andre wächst in dem Lande.`n Weh! wem das Ganze nur steht zu Gebot,`n Sich dran zu halten, kommt er in Not.','Strohhalm',1),(54,'Rätst du mich recht,`n so hast du falsch geraten;`n und rätst du falsch,`n so hast du recht geraten.','falsch',1),(55,'Die Ersten sind ein Untertan,`n Die Letzte ist ein Untertan,`n Das Ganze ist ein Untertan,`n Der von dem letzten Untertan`n Wird unter den ersten Untertan`n Ganz untertänigst getan.','Stiefelknecht',1),(56,'Es ist heiliger als Gottes Sohn`n und verwerflicher als Satan selbst.`n Die Toten essen es jeden Tag,`n doch essen es die Lebenden,`n so sterben sie langsam.','Nichts',1),(57,'Wenn man mich sieht, so sieht man mich nicht.`n Sieht man mich nicht, so sieht man mich.','Finsternis; Dunkelheit',1),(58,'Mein Rock ist weiß wie Schnee`n und schwarz wie Kohlen.`n Was glänzt, das muß ich mir holen.','Elster',1),(59,'Sie schwitzt nicht bei den größten Hitzen,`n doch, wenn es draußen stürmt und schneit,`n dann sieht man sie zuweilen schwitzen.','Fensterscheibe; Fenster; Glasscheibe; Scheibe',1),(60,'Vers bin ich zur Hälfte,`n zur Hälfte nur Tand,`n errätst du mein Ganzes,`n so hast Du Verstand.','Verstand',1),(61,'Himmlische Tugend,`n scheußlicher Mord,`n Fehler im Kartenspiel -`n alles ein Wort.','vergeben',1),(62,'Zu Köln in der Dome,`nSteht eine gelbe Blume;`n Je länger sie steht`n Je mehr sie vergeht.','Kerze',1),(63,'Ich habe keinen Schneider`n und hab doch sieben Kleider.`n Wer mir sie auszieht,`n der muß weinen,`n und sollt er noch so lustig scheinen.','Zwiebel',1),(64,'Das Erste zu halten ist oft schwer,`n Das andre ist Sache des Glücks gar sehr,`n Das Ganze ist nur ein schwarzer Zwerg`n Und hebt ganz leicht doch einen Berg.','Maulwurf',1),(65,'Ein einziges Mal nur bedürfen wir seiner,`n doch selber entlohnt hat ihn noch keiner.','Totengräber',1),(66,'Am ERSTEN ist es meist sehr kalt,`n das ZWEITE ist als Maß schon alt.`n Das GANZE ist oft eine Zier,`n doch in Gebrauch viel lieber mir.','Polster',1),(67,'Er ist berühmt,`n verdient viel Geld`n - ob Mann ob Frau -`n in der ganzen Welt.','Star',1),(68,'Zwei Löcher hab ich,`n zwei Finger brauch ich.`n So mach ich Langes und Großes klein`n und trenne, was nicht beisammen soll sein.','Schere',1),(69,'Steh ich davor, dann bin ich drin.`n Bin ich drin dann steh ich davor.','Spiegel',1),(70,'Etwas, das alles und jeden verschlingt:`n Baum, der rauscht, Vogel, der singt,`n frißt Eisen, zermalmt den härtesten Stein,`n zerbeißt jedes Schwert, zerbricht jeden Schrein,`n Schlägt Könige nieder, schleift ihren Palast,`n trägt mächtigen Fels fort als leichte Last.','Zeit',1),(71,'Die Sprache kann es nicht entbehren,`n die Zeitung bringt es jeden Tag,`n der Kaufmann brauchts, will er sich nähren,`n und selten fehlt es im Vertrag.','Artikel',1),(72,'Ich habe ein Loch und mach ein Loch`n und schlüpfe auch durch dieses noch.`n Kaum bin ich durch, stopf ichs im Nu`n mit meiner langen Schleppe zu.','Nadel',1),(73,'Ich hab ein eigenes Schloß,`n doch ist das ziemlich klein.`n Es paßt kein Gast, kein Hausgenoß`n zugleich mit mir hinein.','Schlüssel',1),(74,'Der Schrein ohne Deckel, Schlüssel, Scharnier,`n birgt einen goldenen Schatz, glaub es mir.','Ei',1),(75,'Er sah ein Weib, so schön wie keines noch auf Erden,`n gleich einem Wort mit \"ö\". Sie muß die Seine werden!`n Und bald auch konnt - o Glück! - er Wort mit \"a\" sie nennen.`n Da freilich lernt er sie von andrer Seite kennen.','Göttin; Gattin',1),(76,'Bei den Sängern guter Art`n rühmt man meine Milde.`n Wär\' ich in der Mitte hart,`n braus ich durchs Gefilde.','Organ; Orkan',1),(77,'Der Peter ist mit lautem Knallen,`n heut auf das Rätselwort gefallen.`n Zur Mutter humpelt er mit Weinen`n die gibt das Rätselwort dem Kleinen.','Pflaster',1),(78,'Ich bin die größte Straße`n zwischen Himmel und Erde.`n Kein Mensch ist hier gegangen,`n kein Wagen je gefahren.`n Und doch herrscht hier zwischen`n Erde und Himmel`n ein großes Gewimmel.','Milchstrasse; Milchstraße',1),(79,'Es geht und geht schon immerfort`n und kommt doch keinen Schritt vom Ort.','Uhr',1),(80,'Wer ist im Wald der kleine Mann,`n der nur auf einem Bein stehen kann?`n Hat einen großen bunten Hut,`n ist einmal giftig, einmal gut.','Pilz',1),(81,'Kommt zum Flächenmaß des Helden Tugend,`n dann drückt sie! Sie ist schwere Last,`n verdüstert manches Menschen Jugend`n und ist nicht reicher Leute Gast!','Armut',1),(82,'Bin ich Wasser, bin ich Luft,`n Bin ich Geist, bin ich Duft? --`n Etwas von dem allen;`n Fahr hinaus mit Gebraus,`n Und zu Saus und zu Braus`n Laß ichs auch noch knallen.','Champagner; Sekt',1),(83,'Hab keinen Hals,`n auch keinen Kopf`n nicht Arm, noch Bein,`n ich armer Tropf.`n Mal bin ich voll,`n mal bin ich leer.`n Doch immer wiegt`n mein Holz sehr schwer.','Fass; Faß',1),(84,'Es schwebt daher ganz kugelrund,`n durchscheinend, leicht und herrlich bunt.`n Entstanden ists durch einen Hauch -`n lang lebt es nicht, bald platzt sein Bauch.','Seifenblase',1),(85,'Ihr lieben Leute,`n hab sieben Häute.`n Ich schone keinen,`n bring jeden zum Weinen.','Zwiebel',1),(86,'Das erste wächst an deinem Kopf,`n das zweite im Land der Zypressen.`n Das Ganze trifft dich unverhofft,`n hast du was ausgefressen.','Ohrfeige',1),(87,'Was Vögel tun, das sind gewisse Tiere,`n die nicht zwei Beine haben und nicht viere.','Fliegen',1),(88,'Je länger es bereits dauert,`n umso kürzer wird es.','Leben',1),(89,'Das Erste gibts für jedes Ding,`n das Zweite ist ein Wortgebilde;`n wer sich nicht an das Ganze halten kann,`n wird auch Opportunist genannt.','Grundsatz',1),(90,'Was ist das für ein Fuß,`n der immer zittern muß?','Hasenfuß',1),(91,'Er hat einen Kamm und kämmt sich nicht,`n Er hat Sporen und ist kein Ritter,`n Er hat eine Sichel und ist kein Schnitter.','Hahn',1),(92,'Wer es macht, der nennt es nicht.`nWer es sucht, der kennt es nicht.`nFindet er\'s, wird\'s hinterdrein`nnicht mehr, was es war, ihm sein.','Rätsel',1),(94,'Bin ich davor, dann bin ich darin;`nbin ich darin, dann bin ich davor.','Spiegel',1),(95,'Es hängt an der Wand, hat den Hintern verbrannt.','Pfanne',1),(96,'Auf vieren steh\' ich,`nhab einen Rücken,`nund wer\'s grad mag,`nder darf mich drücken.','Stuhl',1),(98,'Wenn man mich sieht, so sieht man mich nicht.`nSieht man mich, so sieht man nicht.','Dunkelheit',1),(102,'Sagt heute, wenn ihr wisst, was morgen gestern ist.','Heute',1),(103,'Ich hab\' einen Hals, ich hab\' einen Bauch `nkeinen Kopf, kein Bein, o Graus!`nUnd kann ich drum selber nicht laufen auch,`nso läuft es doch aus mir heraus.','Flasche',1),(104,'Es geht von Mund zu Mund,`ndoch ist es kein Gerücht.`nGetan wird\'s jede Stund\',`nwenn auch von jedem nicht.`nEs kostet nichts, doch kostet man\'s.`nEs ist ein Nichts, doch möcht\' man\'s ganz.`nUnd allen, allen, die es tun, schmeckt\'s gut.`nWer bin ich nun?','Kuß; Kuss',1),(105,'Neun alte Rehe und vier kleine, die haben wieviel Beine?','Keine;Läufe;0',1),(106,'Der eine hat\'s,`nder andre hat\'s gehabt,`nder dritte hätt\' es gern.','Geld',0),(107,'Ein rotes Gärtlein, ein weißes Geländer:`nes regnet nicht hinein, es schneit nicht hinein,`nist doch immer naß.','Mund',0),(108,'Die Schöpfung hat nur einen, doch jeder Schöpfbrunn\' seinen.','Schöpfer',1),(109,'Ich hab\' ein Loch und mach\' ein Loch und schlüpfe auch durch dieses noch.','Nähnadel;Nadel',1),(110,'Ohne Füße steig\' ich Stiegen,`nohne Flügel kann ich fliegen;`nbeißen kann ich ohne Zähne und vergieße manche Träne.`nDarum jagt mit Saus und Braus man mich meist zum Haus hinaus.`nNur der Metzger mag zuzeiten mich in seiner Kammer leiden.`nSchließlich aber ohne Lohn läßt auch er mich kalt davon.','Rauch',1),(111,'Erst pflückt man mich,`ndann trocknet man mich,`ndann brennt man mich,`ndann rädert man mich,`ndann kocht man mich,`nund dann wirft man mich zur Tür hinaus.','Kaffeebohne',1),(112,'Es hat zwei Flügel und kann nicht fliegen.`nEs hat einen Rücken und kann nicht liegen.`nEs hat ein Bein und kann nicht stehen.`nEs kann gut laufen, aber nicht gehen.','Nase',1),(114,'Wer es wagt, hat keinen Mut.`nWem es fehlt, dem geht es gut.`nWer\'s besitzt, ist bettelarm.`nWem\'s gelingt, ist voller Harm.`nWer es gibt, ist hart wie Stein.`nWer es liebt,der bleibt allein.','Nichts',1),(115,'Es geht ein Mann im Grase, hat eine lange Nase,`nhat rote Stiefel an und dreht sich wie ein Edelmann.','Storch',1),(116,'Das Wesen, das ich meine, hat wie du zwei Beine;`nsetzt du ihm ein Zeichen vor, läuft\'s auf dreimal zwei hervor.','Meise; A-Meise; Ameise',1),(117,'Der arme Tropf hat einen Hut und keinen Kopf und hat dazu nur einen Fuß und keinen Schuh','Pilz',1),(118,'Wer hat Hände und kann nicht reißen?`nWer hat Zähne und kann nicht beißen?`nWer hat Füße und kann nicht gehen?`nWer hat Augen und kann nicht sehen?','Puppe',1),(119,'Ein fins\'trer Geselle`n\r\nverfolgt meine Schritte`n\r\nund lässt mich nicht fliehen`n\r\nso sehr ich auch bitte.','Schatten',1),(120,'Es trägt seinen Herrn und wird von seinem Herrn getragen','Schuh;Schuhsohle',1),(121,'Ich rede ohne Zunge,`nich schreie ohne Lunge,`nich habe auch kein Herz`nund nehm\' doch teil an Freud\' und Schmerz.','Turmglocke;Glocke',1),(122,'Verschwindet es aus dem Gesicht, dann liebt man\'s nicht.','Gicht',1),(123,'Nun sage, wer kann:`nWie heißt dieser Mann,`nso er verschwindet,nur Fisch sich findet','Fischer',1),(124,'Ich bin am dunkelsten,`nwenn es am hellsten ist:`nam wärmsten, wenn es am kältesten ist;`nam kältesten, wenn es am wärmsten ist.','Keller',1),(125,'Niemand und keiner gingen in ein leeres Haus. Niemand ging heraus, keiner ging heraus,wer blieb nun noch drin?','Und',1),(126,'Die Sonne kocht\'s,`ndie Hand bricht\'s`nder Fuß tritt\'s,`nder Mund genießts','Wein;Traube',1),(127,'Was will ein jeder werden, was will doch keiner sein?','Alt',1),(129,'Lies oder miß von vorn, miß oder lies von hinten -`ndu wirst mich immer gleich in Sinn und Länge finden.','Elle',1),(130,'Ritt ein Männlein über Land gewickelt und gewackelt,`nhat ein Kleid von lauter Tand gezickelt und gezackelt.','Schmetterling',0),(131,'Schnibelhölzchen, Schnabelhölzchen, Hündlein auf der Eichen.`nHin ist hin, Sinn ist drin und von uns zwei Zeichen.','Unsinn',1),(132,'Kaltes mach\' ich warm, Heißes mach\' ich kalt.`nReich hat mich und arm; wer lang mich hat, wird alt.','Atem',1),(133,'Witschelwatschel gellt über die Brücken und hat dem König sein Bett auf dem Rücken.','Gans',1),(134,'Aus einem Vogel acht entferne, sitzt du darin im Garten gerne.','Laube',1),(135,'Es rüttelt sich und schüttelt sich und macht ein Häuflein unter sich.','Sieb',1),(136,'Ein Haus voller Essen und die Türe vergessen','Ei',1),(137,'Ich bin ein kleines Männchen, hab\'einen runden Kopf,`nund streicht man mir das Köpfchen, dann brennt der ganze Schopf.','Streichholz;Zündholz',1),(138,'Städte hab\' ich, keine Häuser.`nWälder hab\' ich, keine Bäume.`nMeere hab\' ich, keine Fische.`nUnd lieg daheim auf dem Tische.','Landkarte;Karte;Atlas',1),(139,'Wenn du es jagst, so flieht es dich;`nwenn du es fliehst, so jagt es dich.','Schatten',1),(140,'Vers bin ich zur Hälfte, zur Hälfte nur Tand.`nErrätst du mein Ganzes, so hast du Verstand.','Verstand',1),(141,'So man mehr dazu tut, so es kleiner wird.`nSo man mehr davon tut, so es größer wird.','Loch',1),(142,'Ich bin ein Nichts und doch geschätzt, geehrt;`ndenn zugesellt, vermehre ich den Wert.','Null; 0',1),(143,'Was eines Dichters Meisterstück, der Dumme macht\'s im Augenblick.','Faust',1),(144,'Im Drehen muß ich gehen,`nund niemand kann es sehen.`nSie müssen alle mit mir fort`nund bleiben doch an ihrem Ort.','Erde; Erdball',1),(145,'Geht immer um den Baum herum und kann doch nicht hinein.','Rinde',1),(146,'Wo du stehst, da steht es. Wo du gehst, da geht es.`n Wo du ruhst, da ruht es. Was du tust, das tut es.','Schatten',1),(147,'Es steht auf einem Bein, ist kugelrund und trägt das Herz im Kopf.','Kohlkopf;Kohl',1),(148,'Will man vieles von mir haben, muß man mich zuvor begraben.','Samenkorn;Korn;Same;Samen',1),(149,'Ich habe Wasser und bin nicht naß,`nich habe Feuer und bin nicht heiß,`nich hänge am Kreuz und bin nicht tot,`nich gelte Tonnen und wiege kein Lot.','Diamant',1),(150,'Es ist weg und bleibt weg,`nist Tag und Nacht weg,`nund jedermann sieht es doch.','Weg',1),(152,'Einem zu enge, zweien zu recht, dreien zu weit.','Geheimnis',1),(153,'Man trifft es meist vorangestellt dem Affen, Wurf, Korb, Esel, Held`nvor Beeren sieht man es am Baum,`nvor Schellen aber ist\'s kein Traum;`nvor Seuche, Werk, Tier ist\'s zu finden, beim Löwen aber - ist es hinten.','Maul',1),(154,'Im unbegrenzten Reiche ziehn ihrer viele`ngar mannigfachgestalt zu unbekanntem Ziele.`nDas Zweite lädt zum Ruhen ein im Tageshasten.`nDoch keinem Müden schenkt das Ganze stilles Rasten.','Wolken;Bank;Wolkenbank',1),(155,'Ich hab\' ein Ding im Sinn, wohl lieben es die Mädchen traut, es liegt um ihre zarte Haut, doch stecken Nägel drin.','Handschuh',1),(156,'Die Erste frisst. Der Zweite isst.`nDas Dritte wird gefressen, das Ganze wird gegessen.','Sauerkraut',1),(157,'Ein Vorhang aus Luft und Duft gewoben, und wie der Wind geschwind zerstoben.','Nebel',1),(158,'Welcher Igel eins-zwei-drei legt ein Mahagoni-Ei?','Kastanie',1),(159,'Man ißt es nicht, man trinkt es nicht, und schmeckt doch gut.','Kuß',1),(160,'Noch nackter als nackt, so nackt daß es knackt.','Skelett',1),(161,'Wer steht nebeneinander und kann sich nicht sehen?','Augen;Auge',1),(162,'Tragen kann man es, zählen kann man es nicht.','Kopfhaar;Haar',1),(163,'Vor wem muß jeder den Hut abnehmen?','Frisör',1),(164,'Man schlüpft in ein Loch, kommt aus dreien heraus,`nund ist man heraus, so ist man erst recht drin.','Pullover',1),(165,'Wenn von sieben Schwestern jede einen Bruder hat,wieviel Geschwister sind sie zusammen?','8;acht',1),(166,'Durch Hitze, nicht durch Frost vom Norden,bin ich aus Wasser zu Schnee geworden.','Salz',1),(167,'Was brennt Tag und Nacht und verbrennt doch nicht?','Brennessel',1);
/*!40000 ALTER TABLE `riddles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rp_worlds`
--

DROP TABLE IF EXISTS `rp_worlds`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rp_worlds` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `return_name` varchar(255) NOT NULL,
  `return` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rp_worlds`
--

LOCK TABLES `rp_worlds` WRITE;
/*!40000 ALTER TABLE `rp_worlds` DISABLE KEYS */;
INSERT INTO `rp_worlds` VALUES (1,'`ÓG`6r`Òenzla`6n`Ód','`ÓW`6e`Òr Atrahor durch das Stadttor verlässt, spürt vielleicht noch kurz den Blick der Wachen in seinem Rücken, doch jener wendet sich schnell wieder denen zu, die daran \r\n\r\ninteressiert sind, Atrahor zu betreten. Vorbei führt der Feldweg an den hier lagernden Zigeunern und anderem Gesindel, bis hin zu weitläufigen Feldern und dem ebenfalls nicht fernen Wald. Was dann \r\n\r\nfolgt, ist eine Weggabelung; der eine Pfad führt ins Landesinnere, der andere – kaum ausgebaut und vernachlässigt – zum Lager der Expedition, der letzten Bastion vor den dunklen Land`6e`Ón.`0','Stadttor','dorftor.php'),(2,'`(S`)e`7i`etenga`7s`)s`(e','`(I`)n `7d`eieser Seitengasse, welche vom belebten Marktplatz wegführt, befinden sich weitere Geschäfte und freie Händler. Manche Fassaden mögen nicht so ansehnlich \r\rsein, wie von jenen Läden, die ihre Waren zentraler anbieten, doch auch hier patrouillieren die Stadtwachen und sorgen dafür, dass keine Diebe die Taschen zahlender Kunden leeren. Unterschiedlichste \r\rGerüche liegen in der Luft, welche bereits vom Gefeilsche und den Anpreisungen der Händler erfüllt ist. Wer hier nicht findet, was sein Herz begehrt, wird sich wohl auf eine lange Suche außerhalb dieser \r\rStadt einstellen müs`(s`)e`7n.`0','Marktplatz','market.php'),(3,'`}H`Ia`tfenanl`ta`Ig`}e','`}V`Ii`tele Schiffe unterschiedlicher Größe haben hier angelegt, von kleinen Ruderbooten über Fischkutter bis hin zu großen Segelbooten. In der Nähe unterhalten sich \r\reinige Seeleute; die Möwen überfliegen kreischend jene Händler, welche auf die Ankunft ihrer Frachtschiffe warten. Selbst ein Priester des Meeresgottes ist hier anzutreffen, vermutlich um unerfahrenen \r\rSeefahrern und Passagieren verschiedene Fetische gegen Seeungeheuer und andere Schrecken des Meeres für bare Münze anzubieten.\r\n\r\nAuf manchen Schiffen herrscht schon stetiger Betrieb; jene werden \r\rwohl auf die nächste Flut warten, welche sie zurück ins Meer trägt. Dort warten schließlich mehr oder weniger unbekannte Inseln und Ländern darauf, erforscht zu werd`Ie`}n.`0','Hafen','hafen.php'),(4,'`ìU`Yn`;t`Terst`;a`Yd`ìt','`ìN`Yu`;r `Teine steinerne Platte, in welche ein fast verblasstes Zeichen eingeritzt wurde, verbirgt den Eingang, welcher zu den Katakomben unter die Stadt führt, hier \r\n\r\ntummeln sich neben Ratten und anderen Ungeziefer auch Schmuggler und Diebe. Gerüchten zufolge existiert neben der Gesellschaft oberhalb der Erde genau hier, mehr oder weniger unbemerkt, eine eigene \r\n\r\nUnterstadt, welche gänzlich anderen Gesetzen gehorcht. Wie jene genau aussehen wissen wohl nur die Bewohner und regelmäßigen Besucher dieser steinernen Gä`;n`Yg`ìe.','Stadtzentrum','village.php');
/*!40000 ALTER TABLE `rp_worlds` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rp_worlds_members`
--

DROP TABLE IF EXISTS `rp_worlds_members`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rp_worlds_members` (
  `id` int(255) unsigned NOT NULL AUTO_INCREMENT,
  `rportid` int(255) NOT NULL,
  `acctid` int(255) NOT NULL,
  `position` int(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `position` (`position`),
  KEY `acctid` (`acctid`),
  KEY `rportid` (`rportid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rp_worlds_members`
--

LOCK TABLES `rp_worlds_members` WRITE;
/*!40000 ALTER TABLE `rp_worlds_members` DISABLE KEYS */;
/*!40000 ALTER TABLE `rp_worlds_members` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rp_worlds_places`
--

DROP TABLE IF EXISTS `rp_worlds_places`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rp_worlds_places` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `world` int(255) NOT NULL,
  `acctid` int(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `short` varchar(120) NOT NULL,
  `priv_show_short` tinyint(1) NOT NULL DEFAULT '0',
  `description` text NOT NULL,
  `restricted` tinyint(1) NOT NULL DEFAULT '0',
  `parent` int(255) NOT NULL DEFAULT '0',
  `date` datetime NOT NULL,
  `rang` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `world` (`world`),
  KEY `acctid` (`acctid`),
  KEY `restricted` (`restricted`),
  KEY `parent` (`parent`),
  KEY `date` (`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rp_worlds_places`
--

LOCK TABLES `rp_worlds_places` WRITE;
/*!40000 ALTER TABLE `rp_worlds_places` DISABLE KEYS */;
/*!40000 ALTER TABLE `rp_worlds_places` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rp_worlds_places_keys`
--

DROP TABLE IF EXISTS `rp_worlds_places_keys`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rp_worlds_places_keys` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `placeid` int(255) NOT NULL,
  `acctid` int(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `placeid` (`placeid`,`acctid`),
  KEY `acctid` (`acctid`),
  KEY `placeid_2` (`placeid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rp_worlds_places_keys`
--

LOCK TABLES `rp_worlds_places_keys` WRITE;
/*!40000 ALTER TABLE `rp_worlds_places_keys` DISABLE KEYS */;
/*!40000 ALTER TABLE `rp_worlds_places_keys` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rp_worlds_positions`
--

DROP TABLE IF EXISTS `rp_worlds_positions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rp_worlds_positions` (
  `id` int(255) unsigned NOT NULL AUTO_INCREMENT,
  `rportid` int(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `edit` tinyint(1) NOT NULL DEFAULT '0',
  `build` tinyint(1) NOT NULL DEFAULT '0',
  `keys` tinyint(1) NOT NULL DEFAULT '0',
  `positions` tinyint(1) NOT NULL DEFAULT '0',
  `ranks` tinyint(1) NOT NULL DEFAULT '0',
  `cleanup` tinyint(1) NOT NULL DEFAULT '0',
  `allrooms` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `rportid` (`rportid`),
  KEY `edit` (`edit`),
  KEY `build` (`build`),
  KEY `keys` (`keys`),
  KEY `positions` (`positions`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rp_worlds_positions`
--

LOCK TABLES `rp_worlds_positions` WRITE;
/*!40000 ALTER TABLE `rp_worlds_positions` DISABLE KEYS */;
/*!40000 ALTER TABLE `rp_worlds_positions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rpbios`
--

DROP TABLE IF EXISTS `rpbios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rpbios` (
  `id` int(255) unsigned NOT NULL AUTO_INCREMENT,
  `acctid` int(255) NOT NULL,
  `pageid` int(255) NOT NULL DEFAULT '0',
  `parent` int(255) NOT NULL DEFAULT '0',
  `titel` varchar(255) NOT NULL,
  `content` mediumtext NOT NULL,
  `css` mediumtext NOT NULL,
  `sort` int(255) NOT NULL,
  `activ` tinyint(1) NOT NULL DEFAULT '1',
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `see_anon` tinyint(1) NOT NULL DEFAULT '0',
  `see_demo` tinyint(1) NOT NULL DEFAULT '0',
  `see_reg` tinyint(1) NOT NULL DEFAULT '1',
  `see_friends` tinyint(1) NOT NULL DEFAULT '1',
  `fonts` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `acctid` (`acctid`),
  KEY `pageid` (`pageid`),
  KEY `parent` (`parent`),
  KEY `sort` (`sort`),
  KEY `activ` (`activ`),
  KEY `deleted` (`deleted`),
  KEY `see_anon` (`see_anon`),
  KEY `see_demo` (`see_demo`),
  KEY `see_reg` (`see_reg`),
  KEY `see_friends` (`see_friends`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rpbios`
--

LOCK TABLES `rpbios` WRITE;
/*!40000 ALTER TABLE `rpbios` DISABLE KEYS */;
/*!40000 ALTER TABLE `rpbios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rpbios_config`
--

DROP TABLE IF EXISTS `rpbios_config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rpbios_config` (
  `id` int(255) unsigned NOT NULL AUTO_INCREMENT,
  `acctid` int(255) NOT NULL,
  `profi` tinyint(1) NOT NULL DEFAULT '1',
  `css` mediumtext NOT NULL,
  `config` text NOT NULL,
  `friends` text NOT NULL,
  `exclude` text NOT NULL,
  `see_anon` tinyint(1) NOT NULL DEFAULT '0',
  `see_demo` tinyint(1) NOT NULL DEFAULT '0',
  `see_reg` tinyint(1) NOT NULL DEFAULT '1',
  `see_friends` tinyint(1) NOT NULL DEFAULT '1',
  `fonts` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `acctid` (`acctid`),
  KEY `profi` (`profi`),
  KEY `see_anon` (`see_anon`),
  KEY `see_demo` (`see_demo`),
  KEY `see_reg` (`see_reg`),
  KEY `see_friends` (`see_friends`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rpbios_config`
--

LOCK TABLES `rpbios_config` WRITE;
/*!40000 ALTER TABLE `rpbios_config` DISABLE KEYS */;
INSERT INTO `rpbios_config` VALUES (1,1,1,'','','','',0,0,1,1,'');
/*!40000 ALTER TABLE `rpbios_config` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `runes_extrainfo`
--

DROP TABLE IF EXISTS `runes_extrainfo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `runes_extrainfo` (
  `id` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) DEFAULT NULL,
  `seltenheit` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `hinweis` text,
  `buchstabe` char(3) DEFAULT NULL,
  `ausrichtung` varchar(32) DEFAULT NULL,
  `tpl_id` varchar(10) NOT NULL DEFAULT 'r_dummy',
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  KEY `seltenheit` (`seltenheit`),
  KEY `buchstabe` (`buchstabe`),
  KEY `ausrichtung` (`ausrichtung`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `runes_extrainfo`
--

LOCK TABLES `runes_extrainfo` WRITE;
/*!40000 ALTER TABLE `runes_extrainfo` DISABLE KEYS */;
INSERT INTO `runes_extrainfo` VALUES (1,'Fehu',1,'Zuviel an Entstehungs-Energie führt zu Chaos!','F','Wohlstand','r_fehu'),(2,'Uruz',15,'Zuviel dieser erdigen Kraft, führt zu einer erdrückenden Schwere!','U','Stärke','r_uruz'),(3,'Thurisaz',30,'Unkontrollierte Kraft kann Schäden verursachen!','TH','Autorität','r_thurisaz'),(4,'Ansuz',45,'Unheil droht, wenn die Worte außer Kontrolle geraten!','A','Sprache und Wort','r_ansuz'),(5,'Raidho',60,'Alles verläuft rund, solange man sich nicht gegen die Erfordernisse der Zeit wehrt.','R','Lebens- und Weltenzyklen','r_raidho'),(6,'Kenaz',75,'Zuviel Bewusstheit führt zu Bewusstlosigkeit!','K','Erleuchtung, Einsicht','r_kenaz'),(7,'Gebo',90,'Unausgeglichenheit im Geben oder Nehmen führt zu Mangel!','G','Geschenk','r_gebo'),(8,'Wunjo',100,'Probleme sollten angepackt werden, statt über ihre Lösungen zu grübeln!','W','Ausgewogenheit','r_wunjo'),(9,'Hagalaz',114,'Keine Angst vor Herausforderungen!','H','Herausforderung','r_hagalaz'),(10,'Naudiz',126,'Zuviel Tatkraft ist genauso wenig förderlich wie zu wenig!','N','Bedürfnis','r_naudiz'),(11,'Isa',138,'Zuviel Ruhe und Klarheit führen zu Verhärtung','I','Stillstand','r_isa'),(12,'Jera',147,'Es ist die Zeit der Ernte - Zeit der Fülle und Freude, aber auch der harten Arbeit!','J','Ernte','r_jera'),(13,'Eiwaz',156,'Gibt man sich seiner Angst hin, können Zeiten der Veränderung sich verzögern oder sehr schmerzhaft werden.','E','Transformation','r_eiwaz'),(14,'Pethro',166,'Niemand hat Macht über einen, wenn man es nicht zulässt!','P','Entscheidung','r_pethro'),(15,'Algiz',175,'Der Elch spürt Gefahr und ist schnell - es ist gut auf seine Intuition zu hören!','Z','Schutz','r_algiz'),(16,'Sowilo',184,'Zuviel der Sonnenkraft führt zu großen Verbrennungen!','S','Glück','r_sowilo'),(17,'Teiwaz',193,'Die größte Zielstrebigkeit nützt nichts, wenn man sein Ziel verfehlt!','T','Einweihung','r_teiwaz'),(18,'Berkana',202,'Es ist die Zeit der Aussaat - bis zur Ernte ist es noch lang!','B','Neubeginn','r_berkana'),(19,'Ehwaz',211,'Soll Energie gebündelt eingesetzt werden, müssen wir besonders achtsam sein, um ein \'Ausbrechen\' der Kräfte zu vermeiden.','E','Fortschritt','r_ehwaz'),(20,'Mannaz',220,'Befindet man sich in einem Zustand der Ausgeglichenheit, bedeutet das noch lange nicht, dass man auch weniger Achtsam sein darf!','M','Lebenslauf','r_mannaz'),(21,'Laguz',229,'Nur wenn wir ehrlich mit unseren Gefühlen umgehen, können wir wahrhaftig dem Leben begegnen.','L','Einklang mit der Welt','r_laguz'),(22,'Ingwaz',238,'Wenn etwas in Ruhe reifen soll, muss man selbst die entsprechende Ruhe bis zur Reife beibehalten.','NG','die innere Kraft','r_ingwaz'),(23,'Dagaz',243,'Eins bedingt das andere, auch wenn die Dinge sich verändern - so sind sie von ihrem Ursprung her doch eins.','D','Licht','r_dagaz'),(24,'Othala',250,'Zuhause bedeutet Sicherheit, doch nichts ist wirklich sicher. Zuhause bedeutet auch Arbeit, Auseinandersetzung und Aufrechterhalten.','O','Konzentration','r_othala');
/*!40000 ALTER TABLE `runes_extrainfo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `settings` (
  `setting` varchar(50) NOT NULL DEFAULT '',
  `value` mediumtext,
  PRIMARY KEY (`setting`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES ('abakus_times_used','0'),('abgbc_active','0'),('abgbc_url',''),('actdaypart','0'),('activategamedate','1'),('ad_conversion',''),('ad_enable','0'),('ad_enabled','0'),('ad_html',''),('allletter_up_allow','0'),('allowgemtransfer','1'),('allowgoldtransfer','1'),('amtsgems','0'),('amtskasse','0'),('archive_yom_anabled','1'),('archive_yom_limit','500'),('archive_yom_mod_limit','500'),('armorclasses','{\"0\":\"Fundst\\u00fccke\",\"1\":\"Einfaches Leder\",\"2\":\"Holzf\\u00e4llerkleidung\",\"3\":\"Wolfsfell\",\"4\":\"Rostiger Kettenschutz\",\"5\":\"Drachenkrieger-Kleidung\",\"6\":\"Bronze-R\\u00fcstung\",\"7\":\"Zwergenr\\u00fcstung\",\"8\":\"Zauber-Ringe\",\"9\":\"Magische Ringe\",\"10\":\"Pegasus Kreationen\",\"11\":\"Krimskram\",\"12\":\"Drachen-R\\u00fcstungen\",\"13\":\"Importware\",\"14\":\"Yazata-Ware\"}'),('autofight','1'),('automaster','1'),('automatic_header_generation','1'),('automatic_header_length','60'),('avatare','1'),('battlesimulator','{\"creaturename\":\"`\\u0026Schrein-W\\u00e4chter`0\",\"creaturelevel\":9,\"creatureweapon\":\"Zeigefinger der Unw\\u00fcrdigkeit\",\"creatureattack\":19.75,\"creaturedefense\":18.4,\"creaturehealth\":515,\"diddamage\":0,\"maze\":1}'),('beggarmax','30000'),('beleidgterpirat','0'),('beta','0'),('bioextranotesmaxlength','15000'),('blockdupeemail','0'),('blocknewchar','0'),('borrowperlevel','100'),('bountyfee','10'),('bountylevel','3'),('bountymax','200'),('bountymin','10'),('bushesgold','0'),('business','nahdude81@hotmail.com'),('callvendor','2'),('callvendormax','5'),('castlegemdesc','0.1'),('castlegolddesc','50'),('CASTLEMOVES','0'),('CASTLEVISITS','0'),('castle_gems','4'),('castle_gold','5750'),('castle_turns','1'),('castle_turns_wk','4'),('chat_post_len','15000'),('chat_post_len_long','15000'),('chat_post_len_max','30000'),('chat_who_is_here','0'),('ci_goldpresse','0'),('cleanupinterval','43200'),('cmd','_xclick'),('cost_dragonpoints_change','500'),('cowardage','70'),('cowardlevel','1'),('coward_title_enabled','0'),('creaturebalance','0.33'),('criticalchars','0123456789*_|.,;:!?^(){}&$/#~[]@=<>+\"°\''),('cs','1'),('currency_code','EUR'),('dailyspecial','Waldsee'),('dayparts','3'),('daysperday','12'),('DDL-balance','-8'),('DDL-cristals','5'),('DDL-days','0'),('DDL-medal','39'),('DDL-order','1'),('DDL-restart','24'),('DDL-state','8'),('DDL_act_order','1'),('DDL_balance_lose','-10'),('DDL_balance_malus','8'),('DDL_balance_push','25'),('DDL_balance_win','10'),('DDL_comments','0'),('DDL_comments_req','2'),('DDL_comments_req_act','2'),('DDL_new_order','2'),('DDL_opps','0'),('deathjackpot','0'),('deathjackpotmax','10000'),('defaultlanguage','de'),('defaultskin','yar2'),('demouser_acctid','0'),('demouser_last_IP','0'),('demouser_public','0'),('dgbiomax','20000'),('dgfightssuf','10'),('dgfightssufperiod','2'),('dggetcompoints','0'),('dggpgoldcost','50000'),('dgguildfightsday','7'),('dgguildfoundgems','100'),('dgguildfoundgold','100000'),('dgguildfound_k','20'),('dgguildmax','230'),('dgguildpaymentsin','8'),('dgguildpaymentsout','8'),('dgimmune','6'),('dgkingdays','9'),('dglastking','2016-02-17 03:00:01'),('dgmaxbuilds','30'),('dgmaxgemsin','25'),('dgmaxgemstransfer','1'),('dgmaxggoldin','10000'),('dgmaxgoldin','10000'),('dgmaxgoldtransfer','250'),('dgmaxmembers','14'),('dgmaxregalia','15'),('dgmaxregaliaparts','20'),('dgmaxregaliaval','18'),('dgmaxtaxfails','3'),('dgmindkapply','3'),('dgminmembers','5'),('dgminmembertribute','5'),('dgminregaliaval','2'),('dgplayerfights','1'),('dgregaliagpcost','100'),('dgregalialeft','10 '),('dgregaliaprice','14'),('dgstartgems','5'),('dgstartgold','5000'),('dgstartpoints','5'),('dgstartregalia','1'),('dgtaxdays','24'),('dgtaxgems','1'),('dgtaxgold','3000'),('dgtaxmod','1'),('dgtopguild',''),('dgtrsmaxgems','500'),('dgtrsmaxgold','500000'),('dgvotedays','0'),('dgvotedaysmax','170'),('dg_invent_out_price','0.20'),('dispnextday','1'),('dkcounterges','0'),('donations_ges','0'),('dorffestmuetze',''),('dropmingold','0'),('emailonmail','1'),('enable_commentemail','1'),('enable_modcall','1'),('expirecontent','180'),('expirenewacct','180'),('expireoldacct','180'),('expiretrashacct','180'),('expirevacationacct','360'),('expire_accounts','1'),('expire_donationpoints','300'),('expire_sendmail_before','7'),('exsearch_limit','10'),('exsearch_time','30'),('famous_deleted_chars_min_DKs','30'),('fightsforinterest','5'),('firstletter_up','1'),('forestbal','1.55'),('forestdkbal','28.5'),('foresthpbal','6'),('forestspecial_gruft_lastkilled',''),('form_submit','Speichern'),('forum',''),('forward_yom_admin_enable','1'),('forward_yom_enable','0'),('forward_yom_keep_copy','1'),('forward_yom_maximum_depth','1'),('freeorkburg','30000'),('fuerst','Niemand'),('fuerst_donations','12045'),('fuerst_schuld','{}'),('fuerst_tomaten',''),('gameadminemail','none@localhost'),('gamedate','0000-00-00'),('gamedateformat','%d. %F %y'),('gameoffsetseconds','10500'),('gametimeformat','H:i'),('gold_in_well','24'),('gravefightsperday','10'),('guardreq','25'),('guard_max_imprison','2'),('guildinvitationcost','4'),('guild_own_description_maxlength','3000'),('gypsy_maxselledgems','100'),('hasegg','0'),('history_edit_enabled','1'),('houseabandonedmintime','864000'),('housebuildcostgems','50'),('housebuildcostgold','50000'),('housedesclen','3000'),('houseextdks','10'),('houseextsellenabled','0'),('housefreekeys','9'),('housefreekeysplus','9'),('housefreerooms','2'),('housefreeroomsgems','10'),('housefreeroomsgold','5000'),('housefreeroomsplus','4'),('housegetdks','3'),('houseinitrooms','5'),('housekeylvl','5'),('housemaxextensions','2'),('housemaxextensionsplus','2'),('housemaxgemsout','50'),('housemaxkeys','100'),('housemaxkeysplus','100'),('housemaxrooms','10'),('housemaxroomsplus','20'),('housetrsgemsmax','50'),('housetrsgoldmax','15000'),('housetrsshare','0'),('htmleditor_enabled','1'),('html_tidy_enabled','0'),('idols_activated','1'),('idols_acttivated','1'),('inboxlimit','500'),('innfee','10%'),('invent_badweight','500'),('invent_maxweight','500'),('item_name',''),('item_number',''),('jackpot','0'),('jslib_buildID','18'),('judgereq','35'),('kitchen_gourmetpts','1'),('kitchen_toppot','dnrgrgl'),('kleineswesen','{\"0\":0,\"1\":\"Petersen\",\"2\":0}'),('kudzu','0'),('lastcleanup','2016-02-20 23:00:00'),('lasthangman',''),('lastlogin','2016-02-21 01:00:49'),('lastparty','1454273390'),('libdp','250'),('lib_alternative_author','`^Allgemeine Veröffentlichung`0'),('limithp','30'),('locale','de_DE'),('locksentence','8'),('logdnet','1'),('logdnetserver','http://logdnet.lotgd.net/'),('loginbanner','...'),('longbiomaxlength','25000'),('lottery_stack',''),('lottonumber','181'),('lowslumlevel','2'),('lurevendor','40000'),('mailsizelimit','8096'),('mail_sender_address','no-reply@localhost'),('maxagepvp','55'),('maxales','30'),('maxamtsgems','100'),('maxbounties','1'),('maxbudget','1500000'),('maxcolors','-1'),('maxdp_dk','50'),('maxhouses','1300'),('maxinbank','10000'),('maxinterest','15'),('maxitemsgemsfactor','5000'),('maxitemsin','8000'),('maxonline','170'),('maxprison','2'),('maxpvpage','50'),('maxsentence','10'),('maxtaxes','2000'),('maxtransferout','400'),('max_dragonpoints_change','20'),('max_symp','10'),('max_yom_contacts','50'),('message2mail_activated','1'),('mininterest','5'),('mintaxes','0'),('mintransferlev','2'),('min_age','16'),('min_party_level','1000000'),('modinboxlimit','500'),('modoldmail','180'),('moon_date','9'),('mount_biomaxlength','10000'),('mount_maxcolors','10'),('msg_chars_max','3'),('multimaster','0'),('namechange_number','0'),('namemaxlen','25'),('nameminlen','3'),('name_casechange','1'),('name_maxcolors','17'),('nav_help_enabled','1'),('newdaysemaphore','0000-00-00 00:00:00'),('newdragonkill',''),('newplayer','Admin'),('newplayerstartgold','100'),('no_shipping','1'),('numberofguards','18'),('numberofjudges','6'),('numberofpriests','6'),('numberofwitches','6'),('oldmail','180'),('oldspiritamount','44'),('old_symp_vote_list',''),('onlinetop','0'),('onlinetoptime','0'),('paidales','0'),('paidale_by',''),('paidgold','0'),('party_duration','0.5'),('party_force_party','0'),('party_jackpotgems','2'),('party_jackpotgold','500'),('party_japarty_jackpotgemsckpotgems','1'),('paypalemail','mail@localhost'),('paypal_author_enabled','0'),('paypal_email','mail@localhost'),('paypal_enabled','0'),('paypal_server_enabled','0'),('petitionemail','no-reply@localhost'),('prangerfrucht','1'),('priestreq','15'),('prisonchange','1'),('pvp','1'),('pvpattgain','1'),('pvpattlose','5'),('pvpday','1'),('pvpdefgain','1'),('pvpdeflose','3'),('pvpimmunity','20'),('pvpimmu_daysaftercrime','7'),('pvpmaxdkxploss','24'),('pvpmindkxploss','0'),('pvpminexp','1000'),('pvptimeout','600'),('pvptimeout_houses','900'),('pvp_immu_return','1'),('quest_activ','1'),('race_casualties',''),('race_change_allowed','0'),('rebirth_dks','1'),('recoveryage','100'),('recoveryexp','100'),('requireemail','0'),('requirevalidemail','0'),('resurrection_turns_loss','25'),('rpdon_dpcomment','1'),('rpdon_mincomments','7'),('rpdon_minlen','250'),('rpdon_sections','village,marketplace,gardens,gardens_swing,dorfamt,fishing,pool,inn,prison,library,guildquarter,Courtyard,hunterlodge,office_sovereign,party_main,well,slums,spelunke,oldhouse,wolkeninsel,temple,party_dancefloor,pvparena,salon,shade,witch,nebeltal,nebelwald,nebelfluss,nebelhaus,nebelberg,nebelhoehle,dorftor,pranger,richtplatz,richtplatz_galgen,wald_dunklerwald,wald_felshang,wald_weggabelung,wald_ruine,wald_ruine_innen,wald_glockenturm,wald_hoehle,wald_ritualplatz,wald_moor,schneider,grassyfield,meer_strand,meer_turm,meer_wrack,meer_hafen,tempel_der_weisen,well_of_urd,badehaus,lockermale,lockerfemale,menbath,femalebath,spielplatz,viertel_theater,viertel_onstage,viertel_pavillon,viertel_tanz,viertel_park,viertel_platz,greatoaktree,viertel_palace,zirkus,zigeuner,tanner,ropeway,perfume,mainstreet,butcher,bakery,bookstore,barber,pirates_lager,pirates_spelunke,pirates,lowercity_gruft,lowercity_keller,lowercity_katakomben,lowercity_hoehle,lowercity_see,kreuzung,krankenlager'),('rpdon_sections_plus',''),('rss_description',''),('rss_enable_motc_feed','1'),('rss_enable_motd_feed','1'),('rss_feed_address',''),('rss_file_abs_path',''),('rss_file_rel_path',''),('rss_image','LOTGD Webfeed'),('rss_item_count','10'),('rss_link','http://www.domain.de'),('rss_motc_description',''),('rss_motc_feed_address',''),('rss_motc_title',''),('rss_motd_description',''),('rss_motd_feed_address',''),('rss_motd_title','Nachrichten'),('rss_title','MOTD RSS Feed'),('runes_classid','19'),('runes_count_diff','0'),('runes_count_newday','0'),('runes_dummytpl','r_dummy'),('saved_igmonth','0'),('scummbar_logo_stolen','1'),('selfdelete','0'),('selledgems','0'),('serverdesc','`7Beschreibung'),('serverurl','http://www.domain.de/'),('server_address','http://www.domain.de/'),('server_address_no_protocoll','www.domain.de'),('server_meta_description','...'),('server_meta_keywords','...'),('server_name','Testserver'),('server_source_available','0'),('showman_theater_show_description',''),('showman_theater_show_donation','0'),('showman_theater_show_fee',''),('showman_theater_show_free_tickets','0'),('showman_theater_show_id',''),('showman_theater_show_name',''),('show_yom_contacts','1'),('soap','0'),('spaceinname','1'),('specialkeys','1'),('specialtybonus','1'),('squirrel_maxcolors','8'),('stables_mount_editor_cost_gems_basis','20'),('stables_mount_editor_cost_gold_basis','5000'),('sugroups','{\"0\":{\"0\":\"Spieler\",\"1\":\"Spieler\",\"2\":{},\"3\":\"0\",\"4\":\"\"},\"1\":{\"0\":\"Entwickler\\/in\",\"1\":\"Entwickler\",\"2\":{\"1\":\"1\",\"89\":\"1\",\"27\":\"1\",\"67\":\"1\",\"70\":\"1\",\"55\":\"1\",\"82\":\"1\",\"6\":\"1\",\"7\":\"1\",\"12\":\"1\",\"13\":\"1\",\"14\":\"1\",\"15\":\"1\",\"21\":\"1\",\"22\":\"1\",\"23\":\"1\",\"24\":\"1\",\"25\":\"1\",\"50\":\"1\",\"26\":\"1\",\"53\":\"1\",\"39\":\"1\",\"40\":\"1\",\"17\":\"1\",\"86\":\"1\",\"9\":\"1\",\"51\":\"1\",\"5\":\"1\",\"35\":\"1\",\"54\":\"1\",\"78\":\"1\",\"10\":\"1\",\"16\":\"1\",\"18\":\"1\",\"42\":\"1\",\"423\":\"1\",\"76\":\"1\",\"84\":\"1\",\"2\":\"1\",\"41\":\"1\",\"3\":\"1\",\"4\":\"1\",\"37\":\"1\",\"38\":\"1\",\"68\":\"1\",\"80\":\"1\",\"11\":\"1\",\"47\":\"1\",\"46\":\"1\",\"72\":\"1\",\"75\":\"1\",\"48\":\"1\",\"31\":\"1\",\"79\":\"1\",\"43\":\"1\",\"32\":\"1\",\"33\":\"1\",\"52\":\"1\",\"90\":\"1\",\"77\":\"1\",\"29\":\"1\",\"8\":\"1\",\"73\":\"1\"},\"3\":\"1\",\"4\":true},\"2\":{\"0\":\"Administrator\\/in\",\"1\":\"Administratoren\",\"2\":{\"1\":\"1\",\"89\":\"1\",\"27\":\"1\",\"67\":\"1\",\"70\":\"1\",\"55\":\"1\",\"82\":\"1\",\"6\":\"1\",\"7\":\"1\",\"12\":\"1\",\"13\":\"1\",\"14\":\"1\",\"15\":\"1\",\"21\":\"1\",\"22\":\"1\",\"23\":\"1\",\"24\":\"1\",\"25\":\"1\",\"50\":\"1\",\"26\":\"1\",\"53\":\"1\",\"39\":\"1\",\"40\":\"1\",\"17\":\"1\",\"86\":\"1\",\"9\":\"1\",\"51\":\"1\",\"5\":\"1\",\"35\":\"1\",\"54\":\"1\",\"78\":\"1\",\"10\":\"1\",\"16\":\"1\",\"18\":\"1\",\"42\":\"1\",\"423\":\"1\",\"76\":\"1\",\"84\":\"1\",\"2\":\"1\",\"41\":\"1\",\"3\":\"1\",\"4\":\"1\",\"37\":\"1\",\"38\":\"1\",\"68\":\"1\",\"80\":\"1\",\"11\":\"1\",\"47\":\"1\",\"46\":\"1\",\"72\":\"1\",\"75\":\"1\",\"48\":\"1\",\"31\":\"1\",\"79\":\"1\",\"43\":\"1\",\"32\":\"1\",\"33\":\"1\",\"52\":\"1\",\"90\":\"1\",\"77\":\"1\",\"29\":\"1\",\"8\":\"1\",\"73\":\"1\"},\"3\":\"1\",\"4\":true},\"3\":{\"0\":\"Moderator\\/in\",\"1\":\"Moderatoren\",\"2\":{\"1\":\"1\",\"89\":\"1\",\"70\":\"1\",\"55\":\"1\",\"82\":\"1\",\"48\":\"1\",\"32\":\"1\",\"33\":\"1\",\"52\":\"1\",\"90\":\"1\"},\"3\":\"1\",\"4\":true},\"4\":{\"0\":\"`tEhrenmitglied`\\u0026\",\"1\":\"`tEhrenmitglieder`\\u0026\",\"2\":{},\"3\":\"0\",\"4\":false},\"5\":{\"0\":\"Grottenolm\",\"1\":\"Grottenolme\",\"2\":{\"1\":\"1\",\"70\":\"1\",\"55\":\"1\",\"82\":\"1\"},\"3\":\"1\",\"4\":false}}'),('superuser','0'),('superuser_silvester','0'),('su_extended_text_installed','1'),('symp_active','1'),('symp_dk_lock','1'),('symp_per_acc','3'),('tax','0'),('taxchange','1'),('taxfee','20'),('taxprison','0'),('taxrate','10'),('teamname','Team'),('tempelgold','0'),('temple_id1','0'),('temple_id2','0'),('temple_priest_id','0'),('temple_priest_name',' '),('temple_status','3'),('temple_witch_id','0'),('titlemaxlen','33'),('titleminlen','4'),('title_array','{\"0\":{\"0\":\"Fremder\",\"1\":\"Fremde\"},\"1\":{\"0\":\"Stallbursche\",\"1\":\"Stallmagd\"},\"2\":{\"0\":\"Knecht\",\"1\":\"Magd\"},\"3\":{\"0\":\"Leibeigener\",\"1\":\"Leibeigene\"},\"4\":{\"0\":\"Bote\",\"1\":\"Botin\"},\"5\":{\"0\":\"Lehnsmann\",\"1\":\"Lehnsfrau\"},\"6\":{\"0\":\"Knappe\",\"1\":\"Zofe\"},\"7\":{\"0\":\"Landstreicher\",\"1\":\"Landstreicherin\"},\"8\":{\"0\":\"Abenteurer\",\"1\":\"Abenteurerin\"},\"9\":{\"0\":\"Spurenleser\",\"1\":\"Spurenleserin\"},\"10\":{\"0\":\"Sp\\u00e4her\",\"1\":\"Sp\\u00e4herin\"},\"11\":{\"0\":\"J\\u00e4ger\",\"1\":\"J\\u00e4gerin\"},\"12\":{\"0\":\"Waldl\\u00e4ufer\",\"1\":\"Waldl\\u00e4uferin\"},\"13\":{\"0\":\"Bauer\",\"1\":\"B\\u00e4uerin\"},\"14\":{\"0\":\"Gro\\u00dfbauer\",\"1\":\"Gro\\u00dfb\\u00e4uerin\"},\"15\":{\"0\":\"Gutshofverwalter\",\"1\":\"Gutshofverwalterin\"},\"16\":{\"0\":\"Gutsherr\",\"1\":\"Gutsherrin\"},\"17\":{\"0\":\"H\\u00e4ndler\",\"1\":\"H\\u00e4ndlerin\"},\"18\":{\"0\":\"Gro\\u00dfh\\u00e4ndler\",\"1\":\"Gro\\u00dfh\\u00e4ndlerin\"},\"19\":{\"0\":\"B\\u00fcrger\",\"1\":\"B\\u00fcrgerin\"},\"20\":{\"0\":\"Ratsherr\",\"1\":\"Ratsfrau\"},\"21\":{\"0\":\"Verwalter\",\"1\":\"Verwalterin\"},\"22\":{\"0\":\"Senator\",\"1\":\"Senatorin\"},\"23\":{\"0\":\"B\\u00fcrgermeister\",\"1\":\"B\\u00fcrgermeisterin\"},\"24\":{\"0\":\"W\\u00e4chter\",\"1\":\"W\\u00e4chterin\"},\"25\":{\"0\":\"K\\u00e4mpfer\",\"1\":\"K\\u00e4mpferin\"},\"26\":{\"0\":\"Gladiator\",\"1\":\"Gladiatorin\"},\"27\":{\"0\":\"S\\u00f6ldner\",\"1\":\"S\\u00f6ldnerin\"},\"28\":{\"0\":\"Krieger\",\"1\":\"Kriegerin\"},\"29\":{\"0\":\"Standartentr\\u00e4ger\",\"1\":\"Standartentr\\u00e4gerin\"},\"30\":{\"0\":\"Herold\",\"1\":\"Herold\"},\"31\":{\"0\":\"Legion\\u00e4r\",\"1\":\"Legion\\u00e4rin\"},\"32\":{\"0\":\"Centurio\",\"1\":\"Centurioness\"},\"33\":{\"0\":\"Schwertmeister\",\"1\":\"Schwertmeisterin\"},\"34\":{\"0\":\"Waffenmeister\",\"1\":\"Waffenmeisterin\"},\"35\":{\"0\":\"Veteran\",\"1\":\"Veteranin\"},\"36\":{\"0\":\"Held\",\"1\":\"Heldin\"},\"37\":{\"0\":\"Unterh\\u00e4ndler\",\"1\":\"Unterh\\u00e4ndlerin\"},\"38\":{\"0\":\"Soldat\",\"1\":\"Soldatin\"},\"39\":{\"0\":\"Kadett\",\"1\":\"Kadett\"},\"40\":{\"0\":\"Junker\",\"1\":\"Junkerin\"},\"41\":{\"0\":\"F\\u00e4hnrich\",\"1\":\"F\\u00e4hnrich\"},\"42\":{\"0\":\"Leutnant\",\"1\":\"Leutnant\"},\"43\":{\"0\":\"Hauptmann\",\"1\":\"Hauptmann\"},\"44\":{\"0\":\"Major\",\"1\":\"Major\"},\"45\":{\"0\":\"Oberst\",\"1\":\"Oberst\"},\"46\":{\"0\":\"General\",\"1\":\"General\"},\"47\":{\"0\":\"Feldherr\",\"1\":\"Feldherrin\"},\"48\":{\"0\":\"Heermeister\",\"1\":\"Heermeisterin\"},\"49\":{\"0\":\"Ritter\",\"1\":\"Ritterin\"},\"50\":{\"0\":\"Kriegsf\\u00fcrst\",\"1\":\"Kriegsf\\u00fcrstin\"},\"51\":{\"0\":\"Gelehrter\",\"1\":\"Gelehrte\"},\"52\":{\"0\":\"Edler\",\"1\":\"Edle\"},\"53\":{\"0\":\"Freiherr\",\"1\":\"Freifrau\"},\"54\":{\"0\":\"Baron\",\"1\":\"Baroness\"},\"55\":{\"0\":\"F\\u00fcrst\",\"1\":\"F\\u00fcrstin\"},\"56\":{\"0\":\"Herzog\",\"1\":\"Herzogin\"},\"57\":{\"0\":\"Graf\",\"1\":\"Gr\\u00e4fin\"},\"58\":{\"0\":\"Lord\",\"1\":\"Lady\"},\"59\":{\"0\":\"Schatzmeister\",\"1\":\"Schatzmeisterin\"},\"60\":{\"0\":\"Magister\",\"1\":\"Magister\"},\"61\":{\"0\":\"Botschafter\",\"1\":\"Botschafterin\"},\"62\":{\"0\":\"W\\u00e4chter der Krone\",\"1\":\"W\\u00e4chterin der Krone\"},\"63\":{\"0\":\"Prinz\",\"1\":\"Prinzessin\"},\"64\":{\"0\":\"Kronprinz\",\"1\":\"Kronprinzessin\"},\"65\":{\"0\":\"K\\u00f6nig\",\"1\":\"K\\u00f6nigin\"},\"66\":{\"0\":\"Kaiser\",\"1\":\"Kaiserin\"},\"67\":{\"0\":\"Patriarch\",\"1\":\"Matriarchin\"},\"68\":{\"0\":\"Imperator\",\"1\":\"Imperatorin\"},\"69\":{\"0\":\"Drachent\\u00f6ter\",\"1\":\"Drachent\\u00f6terin\"},\"70\":{\"0\":\"Drachenreiter\",\"1\":\"Drachenreiterin\"},\"71\":{\"0\":\"Drachenlord\",\"1\":\"Drachenlady\"},\"72\":{\"0\":\"Weiser\",\"1\":\"Weise\"},\"73\":{\"0\":\"Bischof\",\"1\":\"Bisch\\u00f6fin\"},\"74\":{\"0\":\"Weihbischof\",\"1\":\"Weihbisch\\u00f6fin\"},\"75\":{\"0\":\"Kardinal\",\"1\":\"Kardin\\u00e4lin\"},\"76\":{\"0\":\"Papst\",\"1\":\"P\\u00e4pstin\"},\"77\":{\"0\":\"Prophet\",\"1\":\"Prophetin\"},\"78\":{\"0\":\"Medium\",\"1\":\"Medium\"},\"79\":{\"0\":\"Seele\",\"1\":\"Seele\"},\"80\":{\"0\":\"Heiler\",\"1\":\"Heilerin\"},\"81\":{\"0\":\"Seliger\",\"1\":\"Selige\"},\"82\":{\"0\":\"Heiliger\",\"1\":\"Heilige\"},\"83\":{\"0\":\"Engel\",\"1\":\"Engel\"},\"84\":{\"0\":\"Erzengel\",\"1\":\"Erzengel\"},\"85\":{\"0\":\"Kraft\",\"1\":\"Kraft\"},\"86\":{\"0\":\"Macht\",\"1\":\"Macht\"},\"87\":{\"0\":\"Cherub\",\"1\":\"Cherub\"},\"88\":{\"0\":\"Seraph\",\"1\":\"Seraph\"},\"89\":{\"0\":\"Gigant\",\"1\":\"Gigantin\"},\"90\":{\"0\":\"Titan\",\"1\":\"Titanin\"},\"91\":{\"0\":\"Erztitan\",\"1\":\"Erztitanin\"},\"92\":{\"0\":\"Legende\",\"1\":\"Legende\"},\"93\":{\"0\":\"Mythos\",\"1\":\"Mythos\"},\"94\":{\"0\":\"G\\u00f6tterbote\",\"1\":\"G\\u00f6tterbotin\"},\"95\":{\"0\":\"G\\u00f6tter-Lehrling\",\"1\":\"G\\u00f6tter-Lehrling\"},\"96\":{\"0\":\"Halbgott\",\"1\":\"Halbg\\u00f6ttin\"},\"97\":{\"0\":\"Untergott\",\"1\":\"Unterg\\u00f6ttin\"},\"98\":{\"0\":\"Gott\",\"1\":\"G\\u00f6ttin\"},\"99\":{\"0\":\"Ursprung\",\"1\":\"Ursprung\"},\"100\":{\"0\":\"Allmacht\",\"1\":\"Allmacht\"}}'),('title_maxcolors','17'),('totalkeg','24'),('townname','Testserver'),('transferperlevel','100'),('transferreceive','4'),('treasurelastacc','0'),('turns','15'),('unaccepted_namechange','1'),('user_list_chat_status','71'),('user_rename','500'),('vacation_ban_time','7'),('vampire_tittytwister','1'),('vendor','0'),('wald_steintext',''),('wallchangetime','600'),('wall_author',''),('wall_chgtime',''),('wall_msg',''),('wartung','0'),('wartungallowed',''),('weaponclasses','{\"0\":\"Fundst\\u00fccke\",\"1\":\"Waffen eines Knappen\",\"2\":\"Schwerter\",\"3\":\"Langschwerter\",\"4\":\"Bastardschwerter\",\"5\":\"Highlander-Schwerter\",\"6\":\"Krumms\\u00e4bel\",\"7\":\"Kampf\\u00e4xte\",\"8\":\"Schlagwaffen\",\"9\":\"Asiatische Waffen\",\"10\":\"Pfeil und Bogen\",\"11\":\"Mighty Es Hinterlassenschaften\",\"12\":\"Zauberspr\\u00fcche\",\"13\":\"Schleudern\",\"14\":\"Zweih\\u00e4nder\",\"15\":\"Hieb- und Stichwaffen\"}'),('weapon_maxcolors','8'),('weather',''),('witchvisits','5'),('witch_id1','0'),('witch_id2','0'),('witch_status','3'),('witch_witch_id','0'),('witch_witch_name',' '),('wk_castle_turns','6');
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `skins`
--

DROP TABLE IF EXISTS `skins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `skins` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `folder` varchar(100) DEFAULT NULL,
  `type` varchar(20) DEFAULT NULL,
  `superuser` tinyint(3) unsigned DEFAULT '1',
  `activated` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`,`activated`),
  KEY `activated` (`activated`),
  KEY `superuser` (`superuser`),
  KEY `type` (`type`),
  KEY `folder` (`folder`),
  KEY `name` (`name`),
  KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `skins`
--

LOCK TABLES `skins` WRITE;
/*!40000 ALTER TABLE `skins` DISABLE KEYS */;
INSERT INTO `skins` VALUES (1,'yar2','yar2','skin',0,1);
/*!40000 ALTER TABLE `skins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `special_category`
--

DROP TABLE IF EXISTS `special_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `special_category` (
  `category_id` int(11) NOT NULL AUTO_INCREMENT,
  `category_name` char(100) NOT NULL DEFAULT 'forest',
  `subcategory_name` char(255) NOT NULL DEFAULT '' COMMENT 'Momentan nicht in Gebrauch',
  PRIMARY KEY (`category_id`),
  KEY `category` (`category_name`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `special_category`
--

LOCK TABLES `special_category` WRITE;
/*!40000 ALTER TABLE `special_category` DISABLE KEYS */;
INSERT INTO `special_category` VALUES (1,'forest',''),(2,'village',''),(3,'superuser',''),(4,'inn',''),(5,'outhouse',''),(6,'gardens',''),(7,'graveyard',''),(8,'houses_inside',''),(9,'village_texts','');
/*!40000 ALTER TABLE `special_category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `special_events`
--

DROP TABLE IF EXISTS `special_events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `special_events` (
  `row_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `filename` varchar(255) DEFAULT '0',
  `author` varchar(100) DEFAULT 'Atrahor Team',
  `category_id` int(11) NOT NULL DEFAULT '0',
  `descr` text,
  `public_description` text,
  `prio` int(5) unsigned DEFAULT '0',
  `dk` int(5) unsigned DEFAULT '0',
  `anzahl` int(11) unsigned DEFAULT '0',
  `released` tinyint(3) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`row_id`),
  KEY `filename` (`filename`),
  KEY `prio_dk` (`prio`,`dk`,`released`),
  KEY `category_id` (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=176 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `special_events`
--

LOCK TABLES `special_events` WRITE;
/*!40000 ALTER TABLE `special_events` DISABLE KEYS */;
INSERT INTO `special_events` VALUES (1,'alter.php','Atrahor Team',1,'Man trifft auf einen Altar, auf dem man sich verschiedene Dinge nehmen kann und positive oder negative Ergebnisse erhält...Gold, Special Anwendungen, EXP,etc.',NULL,3,0,0,1),(2,'aphrodite.php','Atrahor Team',1,'Quickie mit einer Göttin.',NULL,2,0,0,1),(3,'audrey.php','Atrahor Team',1,'Eine verrückte Frau, bei der man einen WK verlieren oder bis zu 5 bekommen kann.',NULL,2,0,0,1),(4,'bushes.php','Atrahor Team',1,'Entweder man verliert all sein Gold und bekommt DP dafür, oder man findet welches (oder auch mal gar nichts).',NULL,2,0,0,1),(5,'castle.php','Atrahor Team',1,'Möglichkeit, die Orkburg zu betreten. Spieler ohne DK müssen sich den Weg freikämpfen.',NULL,1,0,0,1),(6,'cookies.php','Atrahor Team',1,'Man findet einen Keks im Wald. Das hat manchmal positive, meist negative Auswirkungen',NULL,1,1,0,1),(7,'darkhorse.php','Atrahor Team',1,'Man findet einen Weg in die Darkhorse Taverne.',NULL,0,0,0,1),(8,'distress.php','Atrahor Team',1,'Man bekommt den Auftrag, jemanden zu retten und hat 3 Orte zur Auswahl. Von Tod über Charme bis Reichtümer ist alles möglich.',NULL,1,3,0,1),(9,'fairy1.php','Atrahor Team',1,'Fee verlangt einen Edelstein, gibt auch meist Belohnung. Wenn man sich weigert besteht die Möglichkeit zu einem ~entzückenden~ Flauschihasen zu werden.','',2,1,0,1),(10,'findgem.php','Atrahor Team',1,'Man findet einen Edelstein.',NULL,0,0,0,1),(11,'findgold.php','Atrahor Team',1,'Man findet etwas Gold.','',0,0,0,1),(12,'findtreasure.php','Atrahor Team',1,'Ein hohler Baumstamm in dem man eine Schatzkarte, einen Edelstein oder wütende Bewohner finden kann.',NULL,1,0,0,1),(13,'gladiator.php','Atrahor Team',1,'Ein Gladiator, der den Spieler aber einer gewissen Anzahl Arenapunkte belohnt.',NULL,1,2,0,1),(14,'glowingstream.php','Atrahor Team',1,'Ein kleiner Bach, man kann davon trinken und man kann es sein lassen. Belohnung oder Tod.',NULL,0,0,0,1),(15,'goldenegg.php','Atrahor Team',1,'Wenn das Goldene Ei keinen Besitzer hat, kann man es mitnehmen, ansonsten erfährt man, wer es hat.',NULL,1,1,0,1),(16,'goldmine.php','Atrahor Team',1,'Neue Mine (by Maris)\r\nAusführlicher, mehr Möglichkeiten. Edelsteine werden nicht angetastet.','',1,1,0,1),(17,'grassyfield.php','Atrahor Team',1,'Eine Lichtung, auf der man die LP regeneriert und das Tier seine Runden zurückbekommt. Da auch Zugang über \\\"spuken\\\" durch die Unterwelt sehr gerne zugespammt.',NULL,0,0,0,1),(18,'lake.php','Atrahor Team',1,'Ein See, bei dem man einen Überblick über seinen Charme und sein Ansehen bekommen kann, oder vom Wasser trinken - auf eigene Gefahr.',NULL,1,0,0,1),(19,'necromancer.php','Atrahor Team',1,'Ein unheimlicher alter Mann mit Verbindung zur Unterwelt.',NULL,1,0,0,1),(20,'oldmanbet.php','Atrahor Team',1,'Man muss in 6 Zügen eine Zahl zwischen 1 und 100 erraten.',NULL,1,0,0,1),(21,'oldmanpretty.php','Atrahor Team',1,'Bekomme einen Charmepunkt.',NULL,1,0,0,1),(22,'oldmantown.php','Atrahor Team',1,'Bringe einen alten Mann zurück ins Dorf und erhalte eine Belohnung (Charme oder ES).',NULL,1,0,0,1),(23,'oldmanugly.php','Atrahor Team',1,'Verliere einen Charmpunkt.',NULL,0,0,0,1),(24,'riddles.php','Atrahor Team',1,'Man bekommt ein Rätsel gestellt, bei richtiger Lösung gibt es eine Belohnung.',NULL,1,0,0,1),(25,'sacrificealtar.php','Atrahor Team',1,'Ein Altar auf dem man sich selber, Blumen oder einen Edelstein opfern kann. Nur wie die Götter auf das Opfer reagieren, das wissen nur.. die Götter eben.',NULL,1,0,0,1),(26,'skillmaster.php','Atrahor Team',1,'Erhöhe deinen Skillevel für einen Edelstein.',NULL,2,0,0,1),(27,'slump.php','Atrahor Team',1,'Man verliert Gold das vom letzten Drachentöter und dem neuesten Spieler aufgesammelt wird.',NULL,2,0,0,1),(28,'smith.php','Atrahor Team',1,'Die Möglichkeit für einen Edelstein eventuell die Waffe verbessern zu lassen.',NULL,1,0,0,1),(29,'stonehenge.php','Atrahor Team',1,'Man kann den Steinkreis betreten oder es lassen.. vom Tod bis hin zum Edelstein ist dort alles zu finden.','',2,0,0,1),(30,'stumble.php','Atrahor Team',1,'Man verliert ein paar LP, kann sterben, wenn man zu wenige hat.',NULL,2,0,0,1),(31,'tempel.php','Atrahor Team',1,'Dies ist der Tempel wo man eine Summe Spenden kann \\\"100..500..1000..5000\\\"\r\nund wen man der jenige ist der den Pott vollgemacht hat..10000 Goldstücke ..so erscheint eine Gottheit mit einer Belohnung.',NULL,2,0,0,1),(32,'vampire.php','Atrahor Team',1,'Der gute alte Vampir. Nimmt bei Spielern, die über LP-Grenze sind, LP, ansonsten, bei Spielern, die mehr als 250 Charme besitzen, den Charme. Kann durch LP-Geschenk besänftigt werden.\r\nAnsonsten gar nichts.',NULL,3,0,0,1),(33,'wannabe.php','Atrahor Team',1,'Man wird vom Ritter Möchtegern angegriffen, den man verfolgen kann - oder es lassen.',NULL,2,0,0,1),(34,'waterfall.php','Atrahor Team',1,'Der Pfad führt zu einem Wasserfall wo man so ziemlich alles bekommen kann. Und sterben.',NULL,2,0,0,1),(35,'randdragon.php','Atrahor Team',1,'Der Grüne Drache, eine reizende Begegnung, solange man sich schön unterwürfig zeigt.',NULL,2,0,0,1),(36,'bridge.php','Atrahor Team',1,'Eine Brücke im Wald mit sehr geringen Überlebenschancen.',NULL,1,0,0,1),(37,'pig.php','Atrahor Team',1,'Ein Wildschwein, das man verfolgen kann.. kann Gold oder ES bringen und man verliert ein paar LP.',NULL,0,0,0,1),(39,'riverbath.php','Atrahor Team',1,'Kleine Chance auf einen Edelstein.',NULL,1,0,0,1),(40,'remains.php','Atrahor Team',1,'Man findet die Leiche eines anderen Kriegers und kann sie begraben oder durchsuchen.',NULL,1,0,0,1),(41,'graeultat.php','Atrahor Team',1,'Leichen im Wald, ist man dumm genug, sie sich anzusehen, macht man vermutlich bekanntschaft mit einer sehr unfreundlichen Hexe.',NULL,1,1,0,1),(42,'edelsteinbrunnen.php','Atrahor Team',1,'Chance, bis zu 5 Edelsteine zu verdoppeln oder zu verlieren.',NULL,1,0,0,1),(43,'searchdwarfs.php','Atrahor Team',1,'Ein Traum bringt den Spieler auf die Idee, ein paaar Zwerge ausrauben zu gehen (als Zwerg, einen Verwandten besuchen zu gehen).',NULL,1,0,0,1),(44,'forestlake.php','Atrahor Team',1,'Der Trampelpfad für Verliebte.',NULL,2,0,0,1),(45,'jewelrymaker.php','Atrahor Team',1,'Für alles Gold ein kleines Stück Elfenkunst bekommen, das oftmals ein paar Edelsteine wert ist.',NULL,1,0,0,1),(46,'terronville.php','Atrahor Team',1,'Ein seltsamer Kerl, der dem Spieler einen Nestaffen verkauft, ihm Gold stiehlt oder ihm Erfahrung bringt.',NULL,2,0,0,1),(47,'statue.php','Atrahor Team',1,'Eine Statue, die gerne mit Angr und Vert Werten spielt ;)',NULL,2,5,0,1),(48,'weather.php','Atrahor Team',1,'Abhängig vom Wetter werden dem Spieler ein paar Attribute geändert, er bekommt Schnupfen, Mücken nerven ihn etc.',NULL,1,0,0,1),(49,'healer_special.php','Atrahor Team',1,'Ein Heiler rückt entweder einen Trank, der schadet oder heilt raus oder einen Gutschein für Golindas Hütte.',NULL,1,0,0,1),(50,'race.php','Atrahor Team',1,'Man wird zum Rennen/Wettkampf mit dem jeweiligen Tier herausgefordert.. bei Niederlage kann man die Hälfte der ES verlieren - FINGER WEG *g*',NULL,1,0,0,1),(51,'stiefel.php','Atrahor Team',1,'Ein grauenhaft stinkender Stiefel in dem manchmal etwas steckt. Dummerweise kann man auch erschossen werden, wenn man hineinsieht.',NULL,1,0,0,1),(52,'time.php','Atrahor Team',1,'Je nach Spielzeit ein anderes kleines Ereignis.',NULL,1,0,0,1),(53,'cliff.php','Atrahor Team',1,'Unebenes Gelände, man kann LP verlieren oder kurzzeitig hinzubekommen, das Tier kann alle Runden verlieren oder man findet einen Edelstein.',NULL,1,0,0,1),(54,'schimaere.php','Atrahor Team',1,'Eine Schimaere.. greife sie an und finde raus, ob sie echt ist. Kann perm LP geben/nehmen oder andere nette Dinge.',NULL,1,3,0,1),(55,'bellerophontes.php','Atrahor Team',1,'Man findet den Turm von Bellerophotes im Wald und hat die Chance Gold&ES, perm. LP oder Exp zu bekommen oder zu sterben.',NULL,2,0,0,1),(56,'liana.php','Atrahor Team',1,'Lianen, die einfach so im Wald rumhängen.. können Exp oder WK geben, aber auch LP verlieren lassen.',NULL,1,0,0,1),(57,'trapper.php','Atrahor Team',1,'Ein Trapper.. man kann LP verlieren, Gold bekommen oder Pilze mit verschiedener Wirkung erwerben.',NULL,1,0,0,1),(58,'moocher.php','Atrahor Team',1,'Man wird von Räubern überfallen und hat die Wahl, das Gold herzugeben oder gegen die Übermacht zu kämpfen. Hat man kein Gold dabei und gibt es her = -1 perm. LP!',NULL,2,8,0,1),(59,'derfremde.php','Atrahor Team',1,'Man begegnet Ramius im Wald. Kann Gefallen bringen, den \\\"Ramius\\\' Sklaven\\\" Titel, einen töten oder Gold geben.','',1,1,0,1),(60,'cruxis.php','Atrahor Team',1,'Special für High Level Charaktere, man bekämpft Engel, die sehr schwer zu besiegen sind, die Belohnung ist gering',NULL,4,30,0,0),(62,'surprise.php','Atrahor Team',1,'Überraschungsgeschenk an bestimmten Spieler.',NULL,1,0,0,1),(63,'bumpiness.php','Atrahor Team',1,'Man tritt in ein Loch und verliert viele LP/das Tier verliert viele Runden.',NULL,3,0,0,1),(64,'eaoden.php','Atrahor Team',1,'Möglichkeit, für 10 Edelsteine das starke Kettenhemd eines Kriegers zu bekommen, oder das schlechte dünne Kettenhemd. Kann bei wiederholtem Besuch noch stärker werden.','',4,5,0,0),(65,'bregomil.php','Atrahor Team',1,'Man kann sich für 1-5 Edelsteine Übungsgeräte anfertigen lassen, die zufällig genau wie ein Spieler nach Wahl aussehen.',NULL,1,5,0,1),(66,'uhr.php','Atrahor Team',1,'Spieler findet eine Uhr und kann an den Zeigern drehen. Er wird dadurch jünger oder älter, kann dabei auch umkommen.',NULL,1,5,0,1),(67,'earthshrine.php','Atrahor Team',1,'Nettes, kleines Behemoth-Monster, bei Sieg Chance, das Mal der Erde zu erlangen.',NULL,1,10,0,1),(68,'kubus.php','Atrahor Team',1,'Ein Fremder, der einen verführen will.. kann sich auch als \\\"netter\\\" Incubus/Succubus herausstellen.',NULL,1,0,0,1),(69,'may.php','Maris',1,'Aus Trophäen lässt sich etwas fertigen\r\nund im Haus verwenden...','',2,10,0,1),(70,'wolves.php','Atrahor Team',1,'Ein Knappe kann sich als nützlich erweisen und eine Stufe aufsteigen... oder sterben.',NULL,3,11,0,1),(71,'goblin.php','Atrahor Team',1,'Triff einen Goblin im Wald, der Dir etwas zu trinken anbietet.','',1,1,0,1),(72,'ogre.php','Atrahor Team',1,'Verwertung von Trophäen mit doch recht netten Belohnungen.',NULL,2,0,0,1),(73,'frogger.php','Atrahor Team',1,'Man soll einen Frosch/eine Kröte von ihrem Fluch befreien. Es kann eine Belohnung geben, oder aber der Fluch geht auf einen selbst über.','',2,1,0,1),(74,'gruft.php','Atrahor Team',1,'Kleiner Dungeon mit der Möglichkeit, auf Gegner zu treffen, Insigniensplitter und andere Schätze abzuräumen.',NULL,3,0,0,1),(75,'magicdoor.php','Atrahor Team',1,'Eine Tür im Wald. Kann WK geben, LP, Gold, Gems, Gefallen.',NULL,1,5,0,1),(77,'trunktrap.php','Alucard',1,'Ein Baumstamm, der auf den Spieler zurast. Da kann man nur noch hoffen, die richtige Entscheidung getroffen zu haben.','',3,0,0,1),(78,'findregalia.php','Atrahor Team',1,'Teil des Gildensystems, Finden von Insigniensplittern.',NULL,1,3,0,1),(79,'waldmaer.php','Atrahor Team',1,'Ein kleines Mädchen, das sich verlaufen hat - oder doch ein Monster, das so nur ahnungslose Wanderer täuschen will?',NULL,1,0,0,1),(80,'calevents.php','Salator',1,'Datumsabhängige Ereignisse.','',1,0,0,1),(82,'randomyom.php','Maris',1,'Eine blinde Brieftaube liefert eine Nachricht aus... an irgendwen... vielleicht...','',2,0,0,1),(83,'cairn.php','Maris',1,'Eines von 5 einzigartigen Idolen, die besondere Kräfte verleihen.','',2,1,0,1),(84,'deadmask.php','Atrahor Team',1,'Diese Maske lässt dich den Tag erneut erleben. Aber vorsicht vor dem Dahakra',NULL,4,0,0,0),(85,'kudzu.php','Atrahor Team',1,'Chance auf Macadamia-Nüsse und eine kleine Spielerfalle.',NULL,1,0,0,1),(98,'elforc.php','Atrahor Team',1,'keine Beschreibung vorhanden',NULL,2,0,0,0),(100,'whitelilies.php','Salator',1,'1000 weiße Lilien kaufen und einen Toten erwecken. Chance auf eigenen Tod.\r\n','',2,0,0,1),(101,'village_brunnenmonster.php','Atrahor Team',2,'keine Beschreibung vorhanden',NULL,3,0,0,1),(102,'village_dunkle_gasse.php','Atrahor Team',2,'keine Beschreibung vorhanden',NULL,3,0,0,1),(104,'forestchurch.php','Valas/Salator',1,'Special für Auserwählte, Niederlage wird teuer','',3,20,0,1),(105,'inn_brawl.php','Atrahor Team',4,'Kleine Prügelei',NULL,2,0,0,1),(108,'oldmanrevenge.php','Atrahor Team',1,'Rache am alten Mann mit dem hässlichen Stock',NULL,2,1,0,1),(109,'towel.php','Salator',5,'NICHT freischalten, wird von outhouse.php aufgerufen. Gag-Event in Anlehnung an \\\'Per Anhalter durch die Galaxis\\\'','',4,1000,0,0),(110,'inn_drunkard.php','Atrahor Team',4,'keine Beschreibung vorhanden',NULL,2,0,0,1),(111,'inn_jealousy.php','Atrahor Team',4,'keine Beschreibung vorhanden',NULL,3,0,0,1),(112,'langer_atem.php','Atrahor Team',1,'Man bekommt den Buff \\\"Langer Atem\\\"',NULL,2,0,0,1),(113,'outhouse_breathe.php','Atrahor Team',5,'Kleines Trollscheisse Addon fürs Klo',NULL,2,0,0,1),(114,'gardens_gardensea.php','Atrahor Team',6,'Kleine Wanderung am Gartensee',NULL,2,0,0,1),(115,'fire.php','Laulajatar',1,'Spieler findet ein Lagerfeuer, kann dort Abenteurern oder Dieben begenen. Verlust von ES möglich.','',2,0,0,1),(116,'gardens_magpie.php','Atrahor Team',6,'Man trifft eine Elster im Garten und kann ihr Gold geben, oder sie verscheuchen. Man gewinnt max. 1 WK',NULL,2,0,0,1),(117,'lumberjack.php','Atrahor Team',1,'Malträtiere einen Baum und verbessere oder verschlechtere dadurch deine Waffe (sterben ist auch möglich)',NULL,3,0,0,1),(118,'village_marblegame.php','Atrahor Team',2,'Man kann mit Kindern Murmeln spielen, dabei Glasperlen gewinnen oder verlieren. Enthält 2. Teil des Tausch-Quests.',NULL,1,0,0,1),(119,'graveyard_lights.php','Atrahor Team',7,'Man findet ein Licht auf dem Friedhof. Man gewinnt oder verliert Gefallen oder Seelenpunkte',NULL,2,0,0,1),(120,'graveyard_falling.php','Atrahor Team',7,'Eine seele fällt vom Himmel. Helfe ihr oder beklaue sie.',NULL,2,0,0,1),(121,'traurige.php','Atrahor Team',1,'keine Beschreibung vorhanden',NULL,2,0,0,1),(122,'forest_thick_shrubbery.php','Atrahor Team',1,'Man landet auf einer Lichtung hinter einem Gebüsch',NULL,2,0,0,1),(123,'ranger.php','Atrahor Team',1,'Ein Waldhüter achtet darauf dass man nicht durch Schonungen latscht, nur auf Reitwegen reitet, nicht total besoffen rumrennt. \r\nWer >8 Kerkertage abzusitzen hat wird ins Gefängnis gebracht.',NULL,2,0,0,1),(126,'houses_rat.php','Atrahor Team',8,'Die Hausratte begrüßt die Bewohner',NULL,2,0,0,1),(127,'inn_hehler.php','Atrahor Team',4,'Ein Hehler in der Schenke',NULL,2,0,0,1),(129,'forest_monkey_island.php','Atrahor Team',1,'Homeage an Monkey Island',NULL,2,0,0,1),(130,'seeddealer.php','Atrahor Team',1,'Ein aufdringlicher Rosenverkäufer, der auch alles aus der Kategorie Saatgut im Angebot hat. Die Mondblume ist der \\\"Geheimausgang\\\".','',4,0,0,0),(131,'kleineswesen.php','O.Wellinghoff/Salator',1,'Begegnung mit einem Däumling, man kann selbst Däumling werden.','',2,0,0,1),(132,'forest_portal.php','Atrahor Team',1,'Man gelangt in eine Parallelwelt in der nur Skelette leben. Vielfältige Möglichkeiten.','',1,20,0,1),(133,'findsquirrel.php','Salator',1,'Falls es herrenlose Eichhörnchen gibt und man selbst höchstens 2 dabei hat bekommt man ein Eichhörnchen.','',3,0,0,0),(134,'gardens_bluebird.php','Atrahor Team',6,'keine Beschreibung vorhanden',NULL,2,0,0,1),(135,'beleidgterpirat.php','Atrahor Team',1,'Besiege einen Pirat mit Worten','',3,0,0,1),(136,'forest_schamane.php','Atrahor Team',1,'keine Beschreibung vorhanden',NULL,2,0,0,1),(137,'mysticsea.php','Atrahor Team',1,'Verwunschener See im Wald mit den Optionen Weglaufen, Schwimmen, Stein springen lassen, Bäume ansehen. Kleine Boni, man kann auch sterben (5 % Expverlust).',NULL,2,0,0,0),(138,'forest_black_jewels.php','Dragonslayer',1,'Bei angabe des korrekten Juwels erhält man das Juwel, welches je nach DK Anzahl eine bestimmte Anzahl an Anwendungen besitzt.','Du triffst eine Hexe im Wald, die dir für eine richtige Antwort ein wertvolles Juwel gibt.',2,0,0,1),(139,'kiste.php','Harthas',1,'Griff in eine Kiste mit Gewinn/Verlust von Gold/Es/Hitpoints','Eine interessante Kiste die dieser Alte Mann da vor sich her trägt',2,0,0,1),(141,'basilisk.php','Laulajatar',1,'Man kann aus drei Waffen wählen und sollte die Spuren richtig deuten, sonst ist man in der Höhle gleich sehr tot.','',2,0,0,1),(142,'blind.php','Laulajatar',1,'Sone Tusse, die wissen will, welcher Monat es ist. Den Edelstein als Belohnung gibt es immer, aber wenn man falsch antwortet Charmeverlust','',1,0,0,1),(143,'inn_guess_numbers.php','Dragonslayer',4,'Man stirbt nur wenn man spielt und dann abbricht bevor man zweimal getippt hat.','Bam Bam der Oger will nuuur Spieeel\\\'n',2,0,0,1),(144,'houses_torture.php','Dragonslayer',8,'Schreibt ein Zufallskommentar in die Eingangshalle','Eine Folterkammer im Keller? Spass im ganzen Haus!',2,0,0,0),(145,'forest_sternensteine.php','Dragonslayer',1,'Man findet Sternensteine (max 20) und kann diese sammeln. 10 davon geben einen +1WK Bonus und 10 einen -1WK Malus. Die Steine können gesammelt werden. Werden sie zurückgegeben bekommt man einen Bonus.','Sammle Sternensteine!',2,0,0,1),(146,'forest_haendlerin.php','Dragonslayer',1,'keine Beschreibung vorhanden','',2,0,0,0),(147,'forest_sirene.php','Dragonslayer',1,'keine Beschreibung vorhanden','',2,0,0,0),(148,'inn_pickup.php','Atrahor Team',4,'keine Beschreibung vorhanden','',1,10,0,1),(149,'runemaster.php','Alucard',1,'Man trifft den Runenmeister im Wald.','',2,5,0,1),(150,'herdsman.php','Atrahor Team',1,'Der Tierstall aus den Gilden als Specialevent','Du triffst einen Schäfer und kannst dir einige seltene Tiere ausborgen.',2,5,0,1),(151,'hase.php','Atrahor Team',1,'Man kann einen verletzten Hasen pflegen (+3CH -1WK) oder ignorieren (-3CH) oder als Kochzutat mitnehmen','',1,0,0,1),(152,'corpse.php','Gargamel',1,'Man findet ein Mordopfer und kann dieses begraben/durchsuchen/Stadtwache rufen','',2,0,0,1),(153,'kingsgold.php','Atrahor Team',1,'keine Beschreibung vorhanden','',4,0,0,0),(154,'cedriks_delivery.php','Asgarath',1,' füllt Cedriks gelagerten Ale wieder auf, wenn diese zu knapp sind','',3,0,0,1),(155,'oldmanmoney.php','Atrahor Team',1,'Der alte Zausel raubt einen aus oder schenkt Gold','',2,0,0,1),(156,'donation.php','Salator',1,'Obskure Spendensammler treiben sich im Wald herum. Man kann etwas spenden und Segen bekommen, oder die Kreatur bekämpfen, oder gar nichts tun.','',0,0,0,1),(157,'forest_kerker.php','Atrahor Team',1,'keine Beschreibung vorhanden','',2,0,0,1),(159,'village_fourty_frigging_frogs.php','Dragonslayer',2,'Ein Froschmob - äh Flashmob','Ein Froschmob - äh Flashmob',2,0,0,1),(160,'forest_godsblade.php','Dyan',1,'Klinge in Stein berühren und ein paar positive oder negative Effekte abgreifen.','Eine besondere Klinge in einer verlassenen Ruine. Was mag sich dahinter verbergen?',2,0,0,1),(161,'forest_shrubbery.php','Dragonslayer',1,'Man bekommt ein GEBÜSCH','Der fahrende gebüschhändler ist im Wald unterwegs',3,0,0,1),(162,'halloween.php','Ysandre',1,'keine Beschreibung vorhanden','',4,0,0,1),(164,'inn_pickup_ladies.php','Atrahor Team',4,'keine Beschreibung vorhanden','',1,10,0,1),(166,'village_halloween_kids.php','Atrahor Team',2,'keine Beschreibung vorhanden','',4,0,0,0),(167,'palasathene.php','Atrahor Team',1,'keine Beschreibung vorhanden','',4,0,0,0),(168,'village_veryholy.php','Atrahor Team',1,'keine Beschreibung vorhanden','',4,0,0,0),(169,'charlie.php','Atrahor Team',1,'keine Beschreibung vorhanden','',3,0,0,1),(170,'forest_scumm.php','Dragonslayer',1,'Man findet Buchstaben des Scum Logos oder verkauft das Logo','Ein kleiner Humunkulus hat es auf Buchstaben abgesehen',2,0,0,1),(171,'cloverfield.php','Atrahor Team',1,'keine Beschreibung vorhanden','',2,0,0,1),(172,'easter.php','Atrahor Team',1,'keine Beschreibung vorhanden','',0,0,0,0),(173,'forest_dragonmother.php','Atrahor Team',0,'keine Beschreibung vorhanden',NULL,0,0,0,1),(174,'moor.php','Atrahor Team',0,'keine Beschreibung vorhanden',NULL,0,0,0,1),(175,'toonworld.php','Atrahor Team',0,'keine Beschreibung vorhanden',NULL,0,0,0,1);
/*!40000 ALTER TABLE `special_events` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `specialty`
--

DROP TABLE IF EXISTS `specialty`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `specialty` (
  `specid` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `filename` varchar(50) NOT NULL DEFAULT '',
  `specname` varchar(50) NOT NULL DEFAULT '',
  `usename` varchar(50) NOT NULL DEFAULT '',
  `author` varchar(50) NOT NULL DEFAULT '',
  `active` enum('0','1') DEFAULT NULL,
  `category` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`specid`),
  KEY `active` (`active`),
  KEY `category` (`category`),
  KEY `author` (`author`),
  KEY `specname` (`specname`),
  KEY `filename` (`filename`),
  KEY `usename` (`usename`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `specialty`
--

LOCK TABLES `specialty` WRITE;
/*!40000 ALTER TABLE `specialty` DISABLE KEYS */;
INSERT INTO `specialty` VALUES (1,'specialty_darkarts','Dunkle Künste','darkart','','1','Magie'),(2,'specialty_magic','Mystische Kräfte','magic','','1','Magie'),(3,'specialty_thievery','Diebeskünste','thievery','','1','Fähigkeiten'),(4,'specialty_heroism','Heldentum','heroism','Maris','1','Kampfkünste'),(5,'specialty_jugglery','Gaukelei','jugglery','Maris','1','Fähigkeiten'),(6,'specialty_transmutation','Verwandlungsmagie','transmutation','Maris','1','Magie'),(7,'specialty_druid','Druidenzauber','druid','Maris','1','Magie'),(8,'specialty_cattiness','Heimtücke','cattiness','Maris','1','Fähigkeiten'),(9,'specialty_wisdom','Weisheit','wisdom','für Tauschquest','1','Fähigkeiten'),(10,'specialty_elemental','Elementarmagie','elemental','Laulajatar','1','Magie'),(11,'specialty_nothingspecial','Nichts Besonderes','nothingspecial','Laulajatar','1','Fähigkeiten'),(12,'specialty_healing','Heilkünste','healing','Laulajatar','1','Fähigkeiten'),(13,'specialty_ranged','Fernkampf','ranged','Laulajatar','1','Kampfkünste'),(14,'specialty_melee','Nahkampf','melee','Laulajatar','1','Kampfkünste'),(15,'specialty_unarmed','Waffenloser Kampf','unarmed','Laulajatar','1','Kampfkünste'),(16,'specialty_illusion','Illusionsmagie','illusion','Laulajatar','1','Magie'),(17,'specialty_whitemagic','Weiße Magie','whitemagic','Laulajatar','1','Magie');
/*!40000 ALTER TABLE `specialty` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stammbaum`
--

DROP TABLE IF EXISTS `stammbaum`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stammbaum` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `acctid` int(255) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `gtag` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `stag` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `status` int(1) NOT NULL,
  `sex` int(1) NOT NULL,
  `bast_vater` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `bast_mutter` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `ehepartner` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `ehepartner_sex` int(1) NOT NULL DEFAULT '0',
  `ep_gtag` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `ep_stag` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `lft` int(255) NOT NULL,
  `rgt` int(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `acctid` (`acctid`),
  KEY `name` (`name`),
  KEY `status` (`status`),
  KEY `sex` (`sex`),
  KEY `ehepartner_sex` (`ehepartner_sex`),
  KEY `lft` (`lft`),
  KEY `rgt` (`rgt`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stammbaum`
--

LOCK TABLES `stammbaum` WRITE;
/*!40000 ALTER TABLE `stammbaum` DISABLE KEYS */;
/*!40000 ALTER TABLE `stammbaum` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sympathy_votes`
--

DROP TABLE IF EXISTS `sympathy_votes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sympathy_votes` (
  `voteid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `from_user` int(10) unsigned NOT NULL DEFAULT '0',
  `to_user` int(10) unsigned NOT NULL DEFAULT '0',
  `timestamp` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`voteid`),
  KEY `timestamp` (`timestamp`),
  KEY `from_user` (`from_user`),
  KEY `to_user` (`to_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Saves all Sympathie Votes for one period';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sympathy_votes`
--

LOCK TABLES `sympathy_votes` WRITE;
/*!40000 ALTER TABLE `sympathy_votes` DISABLE KEYS */;
/*!40000 ALTER TABLE `sympathy_votes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `syslog`
--

DROP TABLE IF EXISTS `syslog`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `syslog` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `actor` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `target` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `message` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `date` (`date`),
  KEY `actor` (`actor`),
  KEY `target` (`target`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `syslog`
--

LOCK TABLES `syslog` WRITE;
/*!40000 ALTER TABLE `syslog` DISABLE KEYS */;
/*!40000 ALTER TABLE `syslog` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `taunts`
--

DROP TABLE IF EXISTS `taunts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `taunts` (
  `tauntid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `taunt` text,
  `editor` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`tauntid`)
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `taunts`
--

LOCK TABLES `taunts` WRITE;
/*!40000 ALTER TABLE `taunts` DISABLE KEYS */;
INSERT INTO `taunts` VALUES (24,'`5\"`6Ich werde viel Spaß mit `4%x`6 von %w`6 haben!`5\", freut sich %W`5.','anpera'),(25,'`5\"`6Aah, also `bdafür`b ist so ein `4%X`6 gut!`5\" ruft %W','anpera'),(27,'`5%W`5 wurde belauscht, als er \"`4%w`6 war einfach keine Herausforderung für mich!`5\" sagte.','anpera'),(29,'`5\"`6`bARRRGGGGGGG`b!!`5\" schreit %w`5 frustriert.','anpera'),(30,'`5\"`6Kann ich wirklich `bso`b schwächlich sein?`5\", heult %w`5.','anpera'),(34,'`5\"`6Ich habe London gesehen, ich habe Frankreich gesehen, und ich habe `4%w`4\'s`6 Unterhose gesehen!`5\" jubelt  %W`5.','anpera'),(35,'`5\"`6Die Hütte des Heilers kann dir jetzt nicht mehr helfen, `4%w`6!,`5\" scherzt %W`5.','anpera'),(36,'`5%W grinst:  \"`6Du bist zu langsam. Du bist zu schwach.`5\"','anpera'),(37,'`5%w`5 schlägt den Kopf gegen einen Stein... \"`6Mist, Mist, Mist!`5\"','anpera'),(44,'`5%W raunzt: \"`6Komm wieder wenn du gelernt hast zu kämpfen!`5\"','anpera'),(45,'`5\"`6Nächstes mal iss dein Gemüse!`5\" schlägt %W`5 vor.','anpera'),(49,'`5\"`6Selbst meine Oma kann mit `4%x`6 besser umgehen als du!`5\" spottet %W`5.','anpera');
/*!40000 ALTER TABLE `taunts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `trivia`
--

DROP TABLE IF EXISTS `trivia`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `trivia` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `question` varchar(255) DEFAULT NULL,
  `answer` text,
  `solution` text,
  `correct` smallint(5) unsigned NOT NULL DEFAULT '0',
  `quiz_mode` smallint(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `trivia`
--

LOCK TABLES `trivia` WRITE;
/*!40000 ALTER TABLE `trivia` DISABLE KEYS */;
/*!40000 ALTER TABLE `trivia` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_online_newyear`
--

DROP TABLE IF EXISTS `user_online_newyear`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_online_newyear` (
  `acctid` bigint(20) DEFAULT NULL,
  `given` tinyint(1) NOT NULL DEFAULT '0',
  UNIQUE KEY `acctid` (`acctid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_online_newyear`
--

LOCK TABLES `user_online_newyear` WRITE;
/*!40000 ALTER TABLE `user_online_newyear` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_online_newyear` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_uploads_pictures`
--

DROP TABLE IF EXISTS `user_uploads_pictures`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_uploads_pictures` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned DEFAULT NULL,
  `small_letter` varchar(255) DEFAULT NULL,
  `ext_url` text NOT NULL,
  `ext` varchar(3) NOT NULL DEFAULT 'jpg',
  `author` varchar(255) DEFAULT NULL,
  `text` varchar(350) DEFAULT NULL,
  `time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `checkedby` int(7) unsigned NOT NULL DEFAULT '0' COMMENT 'Id des Kontrollörs',
  `status` int(7) unsigned NOT NULL DEFAULT '0' COMMENT 'Kontrollstatus',
  `comments` text NOT NULL,
  `directory` int(11) NOT NULL DEFAULT '0',
  UNIQUE KEY `id` (`id`),
  KEY `userid` (`userid`),
  KEY `small_letter` (`small_letter`),
  KEY `ext` (`ext`),
  KEY `author` (`author`),
  KEY `time` (`time`),
  KEY `checkedby` (`checkedby`),
  KEY `status` (`status`),
  KEY `directory` (`directory`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Hier werden Infos über Bilder gesammelt...';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_uploads_pictures`
--

LOCK TABLES `user_uploads_pictures` WRITE;
/*!40000 ALTER TABLE `user_uploads_pictures` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_uploads_pictures` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `valhalla`
--

DROP TABLE IF EXISTS `valhalla`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `valhalla` (
  `acctid` int(11) unsigned NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `birth` date NOT NULL DEFAULT '0000-00-00',
  `char_birthdate` varchar(11) NOT NULL,
  `death` date NOT NULL DEFAULT '0000-00-00',
  `dragonkills` smallint(4) unsigned NOT NULL DEFAULT '0',
  `sex` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `race` char(3) NOT NULL DEFAULT '',
  `bio` text NOT NULL,
  `comments` text NOT NULL,
  `name_clean` varchar(255) NOT NULL,
  PRIMARY KEY (`acctid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Ruhmeshalle gelöschter Charaktere';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `valhalla`
--

LOCK TABLES `valhalla` WRITE;
/*!40000 ALTER TABLE `valhalla` DISABLE KEYS */;
/*!40000 ALTER TABLE `valhalla` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `weapons`
--

DROP TABLE IF EXISTS `weapons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `weapons` (
  `weaponid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `weaponname` varchar(128) DEFAULT NULL,
  `value` int(11) NOT NULL DEFAULT '0',
  `damage` int(11) NOT NULL DEFAULT '1',
  `level` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`weaponid`),
  KEY `level` (`level`),
  KEY `damage` (`damage`),
  KEY `value` (`value`)
) ENGINE=InnoDB AUTO_INCREMENT=252 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `weapons`
--

LOCK TABLES `weapons` WRITE;
/*!40000 ALTER TABLE `weapons` DISABLE KEYS */;
INSERT INTO `weapons` VALUES (1,'Harke',48,1,0),(2,'Maurerkelle',225,2,0),(3,'Spaten',585,3,0),(4,'Beil',990,4,0),(5,'Gartenhacke',1575,5,0),(6,'Fackel',2250,6,0),(7,'Mistgabel',2790,7,0),(8,'Schaufel',3420,8,0),(9,'Heckenschere',4230,9,0),(10,'Axt',5040,10,0),(11,'Schnitzmesser',5850,11,0),(12,'Rostige Holzfälleraxt',6840,12,0),(13,'billige Holzfälleraxt',8010,13,0),(14,'scharfe Holzfälleraxt',9000,14,0),(15,'große Holzfälleraxt',10350,15,0),(16,'Kieselsteine',48,1,1),(17,'Steine',225,2,1),(18,'Felsen',585,3,1),(19,'Kleiner Ast',990,4,1),(20,'Großer Ast',1575,5,1),(21,'Dick gepolsterter Kampfstab',2250,6,1),(22,'Dünn gepolsterter Kampfstab',2790,7,1),(23,'Hölzerne Fassdaube',3420,8,1),(24,'Hölzernes Übungsschwert',4230,9,1),(25,'Stumpfes Bronzekurzschwert',5040,10,1),(26,'Gut verarbeitetes Bronzekurzschwert',5850,11,1),(27,'Rostiges Stahlkurzschwert',6840,12,1),(28,'Stumpfes Stahlkurzschwert',8010,13,1),(29,'Scharfes Stahlkurzschwert',9000,14,1),(30,'Stahlkurzschwert eines Knappen',10350,15,1),(31,'Stumpfes Bronzeschwert',48,1,2),(32,'Bronzeschwert',225,2,2),(33,'Gutes Bronzeschwert',585,3,2),(34,'Stumpfes Eisenschwert',990,4,2),(35,'Eisenschwert',1575,5,2),(36,'geweihtes Schwert',9000,14,2),(37,'gutes Eisenschwert',2250,6,2),(38,'Rostiges Stahlschwert',2790,7,2),(39,'stumpfes Stahlschwert',3420,8,2),(40,'gutes Stahlschwert',4230,9,2),(41,'graviertes Stahlschwert',5040,10,2),(42,'Juwelenbesetztes Stahlschwert',5850,11,2),(43,'Schwert mit goldenem Griff',6840,12,2),(44,'Schwert mit platinbesetztem Griff',8010,13,2),(45,'Schwert der Meister',10350,15,2),(46,'Stahllangschwert',48,1,3),(47,'Gehärtetes Stahllangschwert',585,3,3),(48,'Poliertes Stahllangschwert',225,2,3),(49,'Gutes Stahllangschwert',990,4,3),(50,'Perfektes Stahllangschwert',1575,5,3),(51,'Graviertes Stahllangschwert',2250,6,3),(52,'Stahllangschwert mit silbernem Griff',2790,7,3),(53,'Stahllangschwert mit goldenem Griff',3420,8,3),(54,'Stahllangschwert mit massivgoldenem Griff',4230,9,3),(55,'Stahllangschwert mit massivplatinem Griff',5040,10,3),(56,'Mondsilber Langschwert',5850,11,3),(57,'Herbstgold Langschwert',6840,12,3),(58,'Elfensilber Langschwert',8010,13,3),(59,'Verzaubertes Langschwert',9000,14,3),(60,'Wolfmasters Langschwert',10350,15,3),(61,'Schlechtes Bastardschwert',48,1,4),(62,'Makelhaftes Bastardschwert',225,2,4),(63,'Bastardschwert aus Eisen',585,3,4),(64,'Bastardschwert aus Stahl',990,4,4),(65,'Gutes Bastardschwert aus Eisen',1575,5,4),(66,'Perfektes Bastardschwert aus Eisen',2250,6,4),(67,'Runenbastartschwert',2790,7,4),(68,'Bastardschwert mit Bronzeeinlage',3420,8,4),(69,'Bastardschwert mit Silbereinlage',4230,9,4),(70,'Bastardschwert mit Goldeinlage',5040,10,4),(71,'Nachtsilber Bastardschwert',5850,11,4),(72,'Morgengold Bastardschwert',6840,12,4),(73,'Elfengold Bastardschwert',8010,13,4),(74,'Geweihtes Bastardschwert',9000,14,4),(75,'Nobles Bastardschwert',10350,15,4),(76,'Makelhaftes Highlander Eisenschwert',48,1,5),(77,'Poliertes Highlander Eisenschwert',225,2,5),(78,'Rostiges Highlander Stahlschwert',585,3,5),(79,'Highlander Stahlschwert',990,4,5),(80,'Edles Highlander Stahlschwert',1575,5,5),(81,'Schottisches Breitschwert',2250,6,5),(82,'Kriegsschwert der Wikinger',2790,7,5),(83,'Barbarenschwert',3420,8,5),(84,'Schottisches Basket-Hilt Schwert',4230,9,5),(85,'Agincourt Stahlschwert',5040,10,5),(86,'Keltisches Nahkampfschwert',5850,11,5),(87,'Nordmann Schwert',6840,12,5),(88,'Schwert eines Ritters',8010,13,5),(89,'Highlanderschwert des Löwen',9000,14,5),(90,'Highlanderschwert des Drachentöters',10350,15,5),(91,'Zwei zerbrochene Kurzschwerter',48,1,6),(92,'Zwei Kurzschwerter',225,2,6),(93,'Eiserner Krummsäbel',585,3,6),(94,'Ausbalancierte Krummsäbel',990,4,6),(95,'Angelaufene Stahlkrummsäbel',1575,5,6),(96,'Rostige Stahlkrummsäbel',2250,6,6),(97,'Stahlkrummsäbel',2790,7,6),(98,'Bronzener Stahlkrummsäbel',3420,8,6),(99,'Goldener Stahlkrummsäbel',4230,9,6),(100,'Platin Stahlkrummsäbel',5040,10,6),(101,'Diamantgehärteter Krummsäbel',5850,11,6),(102,'Perfekt verarbeiteter Krummsäbel',6840,12,6),(103,'Geweihter Krummsäbel',8010,13,6),(104,'Meisterhafter Krummsäbel',9000,14,6),(105,'Krummsäbel des Einhorns',10350,15,6),(106,'Angeschlagene eiserne Axt',48,1,7),(107,'Eisenaxt',225,2,7),(108,'Rostige Stahlaxt',585,3,7),(109,'Edle Stahlaxt',990,4,7),(110,'Holzfälleraxt',1575,5,7),(111,'Niederwertige Kampfaxt',2250,6,7),(112,'Mittelmässige Kampfaxt',2790,7,7),(113,'Hochwertige Kampfaxt',3420,8,7),(114,'Zweischneidige Axt',4230,9,7),(115,'Zweischneidige Kampfaxt',5040,10,7),(116,'Goldverzierte Kampfaxt',5850,11,7),(117,'Platinverzierte Kampfaxt',6840,12,7),(118,'Geweihte Kampfaxt',8010,13,7),(119,'Kampfaxt des Zwergenschmieds',9000,14,7),(120,'Kampfaxt eines Zwergenkriegers',10350,15,7),(121,'Zerbrochene Eisenkeule',48,1,8),(122,'Beschädigte Eisenkeule',225,2,8),(123,'Polierte Eisenkeule',585,3,8),(124,'Gut verarbeitete Eisenkeule',990,4,8),(125,'Polierte Stahlkeule',1575,5,8),(126,'Gut verarbeitete Stahlkeule',2250,6,8),(127,'Schlechte Doppelkeule',2790,7,8),(128,'Gute Doppelkeule',3420,8,8),(129,'Kampfkeule',4230,9,8),(130,'Kampfkeule des Kriegshäuptlings',5040,10,8),(131,'Morgenstern des Kriegshäuptlings',5850,11,8),(132,'Diamantener Morgenstern',6840,12,8),(133,'Morgenstern der Zwerge',8010,13,8),(134,'Morgenstern des Kriegslords',9000,14,8),(135,'Geweihter Morgenstern',10350,15,8),(136,'Stiefelmesser',48,1,9),(137,'Wurfmesser',225,2,9),(138,'Totschläger',585,3,9),(139,'Wurfstern',990,4,9),(140,'Hira-Shuriken',1575,5,9),(141,'Wurfpfeil',2250,6,9),(142,'Atlatl',2790,7,9),(143,'Qilamitautit Bolo',3420,8,9),(144,'Kriegs Quoait',4230,9,9),(145,'Cha Kran',5040,10,9),(146,'Fei Piau',5850,11,9),(147,'Jen Piau',6840,12,9),(148,'Gau dim Piau',8010,13,9),(149,'Verzauberte Wurfaxt',9000,14,9),(150,'Teksolo\'s Ninja Sterne',10350,15,9),(151,'Farmerbogen & Holzpfeile',48,1,10),(152,'Farmerbogen & Steinspitzen',225,2,10),(153,'Farmerbogen & Stahlspitzen',585,3,10),(154,'Jagdbogen & Holzpfeile',990,4,10),(155,'Jagdbogen & Steinspitzen',1575,5,10),(156,'Jagdbogen & Stahlspitzen',2250,6,10),(157,'Försterbogen & Holzpfeile',2790,7,10),(158,'Försterbogen & Steinspitzen',3420,8,10),(159,'Försterbogen & Stahlspitzen',4230,9,10),(160,'Langbogen',5040,10,10),(161,'Armbrust',5850,11,10),(162,'Elfischer Langbogen',6840,12,10),(163,'Elfischer Langbogen & Feuerpfeile',8010,13,10),(164,'Elfischer Langbogen & Zauberpfeile',9000,14,10),(165,'Langbogen des Elfenkönigs',10350,15,10),(166,'MightyE\'s Langschwert',225,2,11),(167,'MightyE\'s Kurzschwert',48,1,11),(168,'MightyE\'s Bastard Schwert',585,3,11),(169,'MightyE\'s Krummsäbel',990,4,11),(170,'MightyE\'s Kriegsaxt',1575,5,11),(171,'MightyE\'s Wurfhammer',2250,6,11),(172,'MightyE\'s Morgenstern',2790,7,11),(173,'MightyE\'s Sportbogen',3420,8,11),(174,'MightyE\'s Florett',4230,9,11),(175,'MightyE\'s Säbel',5040,10,11),(176,'MightyE\'s Lichtlanze',5850,11,11),(177,'MightyE\'s Wakizashi',6840,12,11),(178,'MightyE\'s 2-händige Kriegsaxt',8010,13,11),(179,'MightyE\'s 2-händiges Kriegsschwert',9000,14,11),(180,'MightyE\'s Claymore',10350,15,11),(181,'Zauberspruch des Feuers',48,1,12),(182,'Zauberspruch des Erdbebens',225,2,12),(183,'Zauberspruch der Flut',585,3,12),(184,'Zauberspruch der Stürme',990,4,12),(185,'Zauberspruch der Kontrolle',1575,5,12),(186,'Zauberspruch der Blitze',2250,6,12),(187,'Zauberspruch der Schwäche',2790,7,12),(188,'Zauberspruch der Angst',3420,8,12),(189,'Zauberspruch des Giftes',4230,9,12),(190,'Zauberspruch der Besessenheit',5040,10,12),(191,'Zauberspruch der Hoffnungslosigkeit',5850,11,12),(192,'Zauberspruch der Fledermausabwehr',6840,12,12),(193,'Zauberspruch der Wolfabwehr',8010,13,12),(194,'Zauberspruch der Einhornabwehr',9000,14,12),(195,'Zauberspruch der Drachenabwehr',10350,15,12),(196,'Schleuder & Holzsplitter',48,1,13),(197,'Schleuder & Kieselstein',225,2,13),(198,'Schleuder & großer Stein',585,3,13),(199,'Schleuder & Bleikugel',990,4,13),(200,'Schleuder & Stahlkugel',1575,5,13),(201,'Schleuder & Silberkugel',2250,6,13),(202,'Schleuder mit Mehrfachschuss',2790,7,13),(203,'Schleuder & Feuerkugel',3420,8,13),(204,'Schleuder & magische Kugel',4230,9,13),(205,'Silberne Schleuder der Echsen',5040,10,13),(206,'Goldene Schleuder der Echsen',5850,11,13),(207,'Schleuder & faule Eier',6840,12,13),(208,'Schleuder des Echsenbauern',8010,13,13),(209,'Schleuder des Echsenkriegers',9000,14,13),(210,'Schleuder des Echsenkönigs',10350,15,13),(211,'König Arthus Excalibur',6840,12,14),(212,'Schicksalsklinge',5850,11,14),(213,'Shadowblade',3420,8,14),(214,'Eisatem',4230,9,14),(215,'Höllenklinge',5040,10,14),(227,'Runenbesetzter Zweihänder',225,2,14),(228,'Ritterlicher Zweihänder',990,4,14),(229,'Drachenkopfzweihänder',1575,5,14),(230,'Zweihänder des Rächers',585,3,14),(231,'Gassenhauer',48,1,14),(232,'Seelenschnitter',2790,7,14),(233,'Katana der Attentäter',2250,6,14),(234,'Andúril',10350,15,14),(235,'Siebenstreich',9000,14,14),(236,'Thor\'s Hammer',8010,13,14),(237,'Camrosklinge',10350,15,15),(238,'Buyasta-Dolch',48,1,15),(239,'Adlerschwert',225,2,15),(240,'Vanant-Axt',585,3,15),(241,'Mahre-Dolch',990,4,15),(242,'Spinnenschwert',1575,5,15),(243,'Agas-Streitkolben',2250,6,15),(244,'Kerena Wurfaxt',2790,7,15),(245,'Schlangenschwert',3420,8,15),(246,'Dena-Dolch',4230,9,15),(247,'Löwenschwert',5040,10,15),(248,'Haomaklinge',5850,11,15),(249,'Asto-Streitkolben',6840,12,15),(250,'Skorpionschwert',8010,13,15),(251,'Zarichschwert',9000,14,15);
/*!40000 ALTER TABLE `weapons` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `weather_texts`
--

DROP TABLE IF EXISTS `weather_texts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `weather_texts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `category` int(11) DEFAULT NULL,
  `text` text,
  `php` text,
  `weather` text,
  `enabled` tinyint(3) unsigned DEFAULT '0',
  `revised` tinyint(3) unsigned DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `category` (`category`),
  KEY `enabled` (`enabled`),
  KEY `revised` (`revised`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `weather_texts`
--

LOCK TABLES `weather_texts` WRITE;
/*!40000 ALTER TABLE `weather_texts` DISABLE KEYS */;
/*!40000 ALTER TABLE `weather_texts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `weather_texts_categories`
--

DROP TABLE IF EXISTS `weather_texts_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `weather_texts_categories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `category` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `weather_texts_categories`
--

LOCK TABLES `weather_texts_categories` WRITE;
/*!40000 ALTER TABLE `weather_texts_categories` DISABLE KEYS */;
/*!40000 ALTER TABLE `weather_texts_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `xmas_card_log`
--

DROP TABLE IF EXISTS `xmas_card_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `xmas_card_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sender` int(10) unsigned NOT NULL,
  `receiver` int(10) unsigned NOT NULL,
  `year` smallint(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `xmas_card_log`
--

LOCK TABLES `xmas_card_log` WRITE;
/*!40000 ALTER TABLE `xmas_card_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `xmas_card_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `yom_adressbuch`
--

DROP TABLE IF EXISTS `yom_adressbuch`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `yom_adressbuch` (
  `row_id` int(11) NOT NULL AUTO_INCREMENT,
  `acctid` int(11) NOT NULL DEFAULT '0',
  `player` int(11) NOT NULL DEFAULT '0',
  `descr` varchar(80) NOT NULL DEFAULT '',
  `order` smallint(3) unsigned NOT NULL DEFAULT '0' COMMENT 'Ordnung',
  PRIMARY KEY (`row_id`),
  KEY `acctid` (`acctid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `yom_adressbuch`
--

LOCK TABLES `yom_adressbuch` WRITE;
/*!40000 ALTER TABLE `yom_adressbuch` DISABLE KEYS */;
/*!40000 ALTER TABLE `yom_adressbuch` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-02-21 19:29:38
