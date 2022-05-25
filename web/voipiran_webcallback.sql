-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 09, 2022 at 02:33 PM
-- Server version: 10.5.15-MariaDB
-- PHP Version: 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `voipiran_webcallback`
--

-- --------------------------------------------------------

--
-- Table structure for table `prompts`
--

CREATE TABLE `prompts` (
  `id` int(11) NOT NULL,
  `prompt` varchar(30) NOT NULL,
  `text` text NOT NULL,
  `description` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `prompts`
--

INSERT INTO `prompts` (`id`, `prompt`, `text`, `description`) VALUES
(1, 'PromptYourNumber', 'شماره مستقیم شما برای برقراری تماس', 'توضیح دلخواه برای فیلد'),
(2, 'PromptYourName', 'نام شما', 'توضیح دلخواه برای فیلد'),
(3, 'Submit', 'درخواست تماس', 'توضیح دلخواه برای فیلد'),
(4, 'Label1', 'درخواست تماس فوری', 'توضیح دلخواه برای فیلد'),
(5, 'Label2', 'سرویس درخواست تماس ویپ ایران', 'توضیح دلخواه برای فیلد'),
(6, 'InvalidNumber', 'شماره وارد شده معتبر نیست', 'توضیح دلخواه برای فیلد'),
(7, 'Calling', 'در حال برقراری تماس', 'توضیح دلخواه برای فیلد'),
(8, 'Notice', 'نام و شماره خود را وارد کنید', 'توضیح دلخواه برای فیلد');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `parameter` varchar(30) NOT NULL,
  `value` text NOT NULL,
  `description` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `parameter`, `value`, `description`) VALUES
(1, 'PbxDestination', '7777', 'شماره مقصد در سیستم تلفنی، به طور مثال شماره صف یا شماره قرار داده شده بر روی IVR'),
(2, 'Direction', 'FirstCallCustomer', 'جهت جریان تماس، اول با مشتری تماس گرفته و به سیستم تلفنی متصل شود یا در ابتدابه سیستم تلفنی ارتباط برقرار شده و سپس با مشتری تماس گرفته شود.'),
(3, 'PbxOutboundPrefix', '', 'پیش شماره لازم در سیستم تلفنی برای ارتباط با خط\r\n شهری'),
(4, 'TrunkTechName', 'SIP/trunkname', 'نام ترانک شهری شما، ابتدای نام باید نوع ارتباط را نیز ذکر کنید.   مثلا اگر نام تراک شهری شما shatel است این فیلد به این صورت خواهد بود: SIP/shatel'),
(5, 'PbxURL', 'https://PBXIPADDRESS:4343/dialer/dial.php', 'آدرس دسترسی به سرور تلفنی استریسکی شما'),
(6, 'CallButtonDisableTime', '10', 'مدت زمان غیر فعال شدن دکمه تماس پس از کلیک کردن بر روی آن به ثانیه');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `prompts`
--
ALTER TABLE `prompts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `prompts_prompt_uindex` (`prompt`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `settings_parameter_uindex` (`parameter`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `prompts`
--
ALTER TABLE `prompts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
