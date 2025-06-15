-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- 主機： 127.0.0.1
-- 產生時間： 2025-06-15 05:57:04
-- 伺服器版本： 10.4.32-MariaDB
-- PHP 版本： 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 資料庫： `tebook`
--

-- --------------------------------------------------------

--
-- 資料表結構 `horror novels`
--

CREATE TABLE `horror novels` (
  `ID` int(11) NOT NULL COMMENT '主鍵',
  `title` varchar(64) NOT NULL COMMENT '名稱',
  `year` int(4) NOT NULL COMMENT '發行年',
  `writer` varchar(64) NOT NULL COMMENT '作者',
  `Publisher` varchar(64) NOT NULL COMMENT '發行商',
  `price` int(11) NOT NULL COMMENT '價格'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `horror novels`
--

INSERT INTO `horror novels` (`ID`, `title`, `year`, `writer`, `Publisher`, `price`) VALUES
(1, '鬼魅之聲', 2005, '林雨柔', '夜影文創', 339),
(2, '夜半詭談', 2004, '張雅婷', '異界出版', 388),
(3, '詛咒之家', 2010, '李文中', '靈魂書房', 483),
(4, '陰影下的微笑', 2011, '林雨柔', '黑夜文化', 324),
(5, '午夜人偶', 2008, '吳欣怡', '夜影文創', 212),
(6, '血色謎語', 2014, '李文中', '黑夜文化', 304),
(7, '怨靈附身', 2003, '黃建國', '黑夜文化', 295),
(8, '禁忌之森', 2023, '陳志宏', '靈魂書房', 540),
(9, '第七封信', 2001, '張雅婷', '靈魂書房', 282),
(10, '恐懼走廊', 2017, '陳志宏', '黑夜文化', 256),
(11, '窗外的眼睛', 2021, '黃建國', '異界出版', 497),
(12, '無聲尖叫', 2000, '林雨柔', '靈魂書房', 444),
(13, '深夜電話', 2010, '黃建國', '夜影文創', 397),
(14, '紅衣女子', 2012, '李文中', '異界出版', 372),
(15, '墓地筆記', 2024, '吳欣怡', '靈魂書房', 363),
(16, '陰魂不散', 2009, '吳欣怡', '驚悚出版社', 462),
(17, '死神邀請', 2011, '陳志宏', '黑夜文化', 486),
(18, '裂縫中的眼', 2006, '林雨柔', '夜影文創', 308),
(19, '地獄公寓', 2007, '李文中', '黑夜文化', 265),
(20, '咒殺筆記', 2023, '黃建國', '靈魂書房', 417),
(21, '孤島祭壇', 2021, '陳志宏', '異界出版', 479),
(22, '黑霧之夜', 2002, '林雨柔', '夜影文創', 354),
(23, '深山鬼影', 2019, '張雅婷', '異界出版', 533),
(24, '藍燈詭影', 2005, '黃建國', '黑夜文化', 502),
(25, '靈異診所', 2018, '陳志宏', '黑夜文化', 258),
(26, '冤魂夜行', 2004, '吳欣怡', '驚悚出版社', 514),
(27, '怪談筆記', 2003, '李文中', '夜影文創', 432),
(28, '遺忘之屋', 2022, '黃建國', '靈魂書房', 383),
(29, '廢墟學園', 2006, '張雅婷', '異界出版', 449),
(30, '鬼市迷蹤', 2013, '吳欣怡', '驚悚出版社', 535);

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `horror novels`
--
ALTER TABLE `horror novels`
  ADD PRIMARY KEY (`ID`,`title`);

--
-- 在傾印的資料表使用自動遞增(AUTO_INCREMENT)
--

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `horror novels`
--
ALTER TABLE `horror novels`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT COMMENT '主鍵', AUTO_INCREMENT=31;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
