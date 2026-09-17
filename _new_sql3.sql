ALTER TABLE `user_account` ADD `gender` INT NOT NULL DEFAULT '0' COMMENT 'جنسیت مشتری' AFTER `birthday`; 

CREATE TABLE `user_metrics` (
  `id_main` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `height` decimal(5,2) NOT NULL COMMENT 'قد',
  `weight` decimal(5,2) NOT NULL COMMENT 'وزن',
  `wrist` decimal(5,2) NOT NULL COMMENT 'دور مچ',
  `neck` decimal(5,2) NOT NULL COMMENT 'دور گردن',
  `waist` decimal(5,2) NOT NULL COMMENT 'دور کمر',
  `hip` decimal(5,2) NOT NULL COMMENT 'دور باسن ویژه خانم ها',
  `goal` int(11) NOT NULL COMMENT 'هدف',
  `experience_level` int(11) NOT NULL COMMENT 'سابقه تمرین',
  `training_days` int(11) NOT NULL COMMENT 'تعداد روزهای قابل تمرین',
  `training_location` int(11) NOT NULL COMMENT 'محل تمرین',
  `equipment` int(11) NOT NULL COMMENT 'تجهیزات',
  `body_type` int(11) NOT NULL COMMENT 'مدل بدن',
  `activity_level` int(11) NOT NULL COMMENT 'فعالیت روزانه',
  `preferred_meals` varchar(50) COLLATE utf8_persian_ci NOT NULL COMMENT 'تعداد وعده های غذایی',
  `supplement_budget` int(11) NOT NULL COMMENT 'بودجه هزینه ایی دوره',
  `physical_limitations` varchar(250) COLLATE utf8_persian_ci NOT NULL COMMENT 'محدودیت‌ها و آسیب‌های مرتبط ویژه برنامه های ورزشی',
  `metabolic_diseases` varchar(250) COLLATE utf8_persian_ci NOT NULL COMMENT 'بیماری ها متابولیک ویژه بسته های غذایی',
  `allergies` varchar(250) COLLATE utf8_persian_ci NOT NULL COMMENT 'آلرژی ها',
  `dietary_restrictions` varchar(250) COLLATE utf8_persian_ci NOT NULL COMMENT 'محدودیت‌های غذایی',
  `question1` int(11) NOT NULL,
  `question2` int(11) NOT NULL,
  `question3` int(11) NOT NULL,
  `question4` int(11) NOT NULL,
  `question5` int(11) NOT NULL,
  `question6` int(11) NOT NULL,
  `question7` int(11) NOT NULL,
  `date_create` varchar(20) COLLATE utf8_persian_ci NOT NULL COMMENT 'تاریخ ثبت',
  `ip_last_edit` varchar(20) COLLATE utf8_persian_ci NOT NULL,
  `user_last_edit` varchar(50) COLLATE utf8_persian_ci NOT NULL,
  `date_last_edit` varchar(20) COLLATE utf8_persian_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci COMMENT='اطلاعات متریک کاربر';

-- Indexes for table `user_metrics`
ALTER TABLE `user_metrics`  ADD PRIMARY KEY (`id_main`),  ADD KEY `id_user` (`id_user`);


CREATE TABLE `health_conditions` (
  `id_main` int(11) NOT NULL AUTO_INCREMENT, 
  `id_user` int(11) NOT NULL,
  `id_table` int(11) NOT NULL COMMENT 'نوع جدول بیماری، محدودیت فیزیکی و یا آلرژی و ...',
  `id_category` int(11) NOT NULL COMMENT 'کد گروه بندی',
  `tetr` text COLLATE utf16_persian_ci NOT NULL COMMENT 'عنوان',  
  `tags` text COLLATE utf16_persian_ci NOT NULL COMMENT 'کلیدواژه ها',  
  `active` int(11) NOT NULL DEFAULT '1' COMMENT 'وضعیت',
  `id_sort` int(11) NOT NULL DEFAULT '1',
  `isdeleted` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf16 COLLATE=utf16_persian_ci COMMENT='جدول بیماری ها و محدودیت های حرکتی و آلرژی ها';

-- Indexes for table `health_conditions`
ALTER TABLE `health_conditions`  ADD PRIMARY KEY (`id_main`), ADD KEY `id_user` (`id_user`);


/* 
ALTER TABLE `messenger_list` ADD `id_category` INT NOT NULL COMMENT 'کد طبقه بندی پروفایل ویدئو در آپارات' AFTER `password_aparat`; 

ALTER TABLE `setting` ADD `youtube_token` TEXT NOT NULL COMMENT 'توکن دریافتی از گوگل برای اتصال به یوتوب' AFTER `google_client_secret`; 

ALTER TABLE `messenger_list` ADD `yotube_client_id` TEXT NOT NULL COMMENT 'کلاینت آی دی یوتوب' AFTER `id_category`, ADD `yotube_client_secret` TEXT NOT NULL COMMENT 'کلاینت سیکرت یوتوب' AFTER `yotube_client_id`; 

ALTER TABLE `messenger_archive` ADD INDEX(`idrec`);

ALTER TABLE `product` ADD `videofile` TEXT NOT NULL COMMENT 'فایل ویدئو معرفی محصول' AFTER `seo_rate2`, ADD `soundfile` TEXT NOT NULL COMMENT 'توضیحات صوتی معرفی محصول' AFTER `videofile`, ADD `pdffile` TEXT NOT NULL COMMENT 'پی دی اف معرفی محصول' AFTER `soundfile`; 

ALTER TABLE `setting` ADD `sms_otp` INT NOT NULL DEFAULT '0' COMMENT 'وضعیت فعالیت otp در ورود به بخش مدیریت سامانه' AFTER `sms_variable`; 
ALTER TABLE `setting` ADD `sms_time` INT NOT NULL DEFAULT '5' COMMENT 'مدت زمان اعتبار پیامک otp' AFTER `sms_otp`; 

ALTER TABLE `manager_account` ADD `sms_otp` INT NOT NULL DEFAULT '0' COMMENT 'نوع فعالیت پیامک فعال سازی' AFTER `parentid`; 

ALTER TABLE `manager_account` ADD `fail_login` INT NOT NULL DEFAULT '0' COMMENT 'تعداد دفعات ورود غیرمجاز' AFTER `sms_otp`; 

ALTER TABLE `manager_account` ADD `otp_cookie_token` VARCHAR(100) NOT NULL COMMENT 'کد توکن کاربر زمانی که بدون پسورد وارد می شود' AFTER `fail_login`; 

ALTER TABLE `user_active_code` ADD INDEX(`timesend`);



ALTER TABLE `setting` ADD `show_ostan_city` INT NOT NULL DEFAULT '1' COMMENT 'نمایش استان و شهر در آدرس' AFTER `youtube_token`;
ALTER TABLE `setting` ADD `show_product_property` INT NOT NULL DEFAULT '1' COMMENT 'نمایش عنوان ویژگی در معرفی محصول' AFTER `show_ostan_city`; 
ALTER TABLE `setting` ADD `apikey_neshan` TEXT NOT NULL COMMENT 'کد api اتصال به نشان' AFTER `show_product_property`, ADD `gps_shop` TEXT NOT NULL COMMENT 'کد gps فروشگاه' AFTER `apikey_neshan`; 

ALTER TABLE `user_account` ADD `birthday` VARCHAR(10) NOT NULL AFTER `date`; 
CREATE TABLE IF NOT EXISTS `user_addres` (
  `id_main` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `tetr` varchar(90) COLLATE utf8_persian_ci NOT NULL COMMENT 'عنوان آدرس',
  `id_ostan` int(11) NOT NULL COMMENT 'استان',
  `id_city` int(11) NOT NULL COMMENT 'شهر',
  `id_sector` int(11) NOT NULL COMMENT 'محله',
  `addres` text COLLATE utf8_persian_ci NOT NULL,
  `gps` varchar(90) COLLATE utf8_persian_ci NOT NULL COMMENT 'لوکیشن gps',
  `zipcode` varchar(20) COLLATE utf8_persian_ci NOT NULL COMMENT 'کدپستی',
  `ip_last_edit` varchar(20) COLLATE utf8_persian_ci NOT NULL,
  `date_last_edit` varchar(20) COLLATE utf8_persian_ci NOT NULL,
  PRIMARY KEY (`id_main`),
  KEY `id_user` (`id_user`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci COMMENT='آدرس مشتریان';
COMMIT;

ALTER TABLE `discount` ADD `id_topics` TEXT NOT NULL COMMENT 'دسته بندی ها' AFTER `active`; 
ALTER TABLE `order_title` ADD `id_addres_customer` INT NOT NULL COMMENT 'کد آدرس مشتری' AFTER `email_customer`; 

ALTER TABLE `user_addres` ADD `recip_me` INT NOT NULL DEFAULT '0' COMMENT 'تحویل گیرنده خودم یا دیگری' AFTER `zipcode`, ADD `recip_name` VARCHAR(90) NOT NULL COMMENT 'نام تحویل گیرنده' AFTER `recip_me`, ADD `recip_mobile` VARCHAR(20) NOT NULL COMMENT 'شماره موبایل تحویل گیرنده' AFTER `recip_name`; 

ALTER TABLE `user_addres` ADD `plaque` VARCHAR(20) NOT NULL COMMENT 'پلاک' AFTER `addres`,
 ADD `unit` VARCHAR(20) NOT NULL COMMENT 'واحد' AFTER `plaque`; 
 ALTER TABLE `user_addres` ADD `isdeleted` INT NOT NULL DEFAULT '0' AFTER `recip_email`; 

ALTER TABLE `user_addres` ADD `recip_email` VARCHAR(90) NOT NULL COMMENT 'ایمیل تحویل گیرنده' AFTER `recip_mobile`; 
 
 ALTER TABLE `order_title` ADD `plaque` VARCHAR(20) NOT NULL COMMENT 'پلاک' AFTER `zipcode`, ADD `unit` VARCHAR(20) NOT NULL COMMENT 'واحد' AFTER `plaque`, ADD `gps` VARCHAR(90) NOT NULL COMMENT 'gps' AFTER `unit`; 
 
 
 ALTER TABLE `order_title` ADD `reciver_name` VARCHAR(90) NOT NULL COMMENT 'تحویل گیرنده' AFTER `gps`, ADD `reciver_mobile` VARCHAR(20) NOT NULL COMMENT 'موبایل تحویل گیرنده' AFTER `reciver_name`; 
 
 ALTER TABLE `user_addres` ADD `distance_to_shop` FLOAT NOT NULL AFTER `gps`; 
 
 ALTER TABLE `setting` ADD `user_type_register` INT NOT NULL DEFAULT '1' COMMENT 'نوع ثبت نام کاربران با موبایل یا ایمیل و یا هردو' AFTER `gps_shop`, ADD `user_type_login` INT NOT NULL DEFAULT '1' COMMENT 'نحوه ورود کاربران با رمز یا پیامک' AFTER `user_type_register`, ADD `apikey_neshan_web` TEXT NOT NULL COMMENT 'کد api برنامه نشان برای نقشه وب' AFTER `user_type_login`, ADD `show_gps` INT NOT NULL DEFAULT '1' COMMENT 'نمایش نقشه' AFTER `apikey_neshan_web`; 
 ALTER TABLE `setting` ADD `must_give_gps` INT NOT NULL DEFAULT '0' COMMENT 'الزام ثبت gps در آدرس مشتری' AFTER `show_gps`, ADD `type_show_gps` INT NOT NULL DEFAULT '1' COMMENT 'برنامه نمایش دهنده نقشه' AFTER `must_give_gps`; 
 
 ALTER TABLE `setting` ADD `sms_time_users` INT NOT NULL DEFAULT '2' COMMENT 'مدت زمان اعتبار پیامک برای ورود مشتریان' AFTER `user_type_login`; 
 
 ALTER TABLE `setting` ADD `active_loger` INT NOT NULL DEFAULT '1' COMMENT 'وضعیت فعالیت آمار بازدید داخلی' AFTER `type_show_gps`, ADD `active_search_ip` INT NOT NULL DEFAULT '0' COMMENT 'وضعیت جستجوی آی پی از بانک داخلی' AFTER `active_loger`; 
 
 
 ALTER TABLE `user_token` CHANGE `token` `token` CHAR(64) CHARACTER SET ascii COLLATE ascii_general_ci NOT NULL; 
 ALTER TABLE `user_token` CHANGE `contor` `expires_at` DATETIME NOT NULL; 
 
 CREATE TABLE IF NOT EXISTS `sms_portal` (
  `id_main` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_persian_ci NOT NULL COMMENT 'نام سامانه',
  `smsuser` text COLLATE utf8_persian_ci NOT NULL COMMENT 'نام کاربری اتصال',
  `smspass` text COLLATE utf8_persian_ci NOT NULL COMMENT 'اسم رمز اتصال',
  `smstype` int(11) NOT NULL COMMENT 'نوع سامانه پیامکی',
  `smsnumber` TEXT NOT NULL COMMENT 'سرشماره ارسال پیامک', 
  `id_pattern` text COLLATE utf8_persian_ci NOT NULL COMMENT 'کد پترن',
  `code_pattern1` text COLLATE utf8_persian_ci NOT NULL COMMENT 'متغیر استفاده شده در پترن',
  `code_pattern2` text COLLATE utf8_persian_ci NOT NULL,
  `code_pattern3` text COLLATE utf8_persian_ci NOT NULL,
  `code_pattern4` text COLLATE utf8_persian_ci NOT NULL,
  `code_pattern5` text COLLATE utf8_persian_ci NOT NULL,
  `message` text COLLATE utf8_persian_ci NOT NULL COMMENT 'نمونه متن ارسالی',
  `apikey` text COLLATE utf8_persian_ci NOT NULL,
  `active` int(11) NOT NULL DEFAULT '1' COMMENT 'وضعیت فعالیت',
  `default_send` int(11) NOT NULL DEFAULT '0' COMMENT 'پیش فرض ارسال پیامک',
  `default_otp1` int(11) NOT NULL DEFAULT '0' COMMENT 'پیش فرض ارسال پیامک لاگین',
  `default_otp2` int(11) NOT NULL DEFAULT '0' COMMENT 'پیش فرض ارسال پیامک ثبت نام',
  `default_otp3` int(11) NOT NULL DEFAULT '0' COMMENT 'پیش فرض ارسال پیامک ریکاور پسورد',
  `default_otp4` int(11) NOT NULL DEFAULT '0' COMMENT 'پیش فرض ارسال پیامک خرید',
  `default_otp5` int(11) NOT NULL DEFAULT '0' COMMENT 'پیش فرض ارسال پیامک متفرقه',
  `isdeleted` int(11) NOT NULL DEFAULT '0',
  `date_last_edit` varchar(20) COLLATE utf8_persian_ci NOT NULL,
  `user_last_edit` varchar(20) COLLATE utf8_persian_ci NOT NULL,
  `ip_last_edit` varchar(20) COLLATE utf8_persian_ci NOT NULL,
  PRIMARY KEY (`id_main`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci COMMENT='فهرست سامانه های اتصال به پیامک در سامانه';
COMMIT;
 
 
ALTER TABLE `user_account` CHANGE `password` `password` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_persian_ci NOT NULL; 


ALTER TABLE `order_title` ADD `tetr_addres` VARCHAR(90) NOT NULL COMMENT 'عنوان آدرس مشتری' AFTER `reciver_mobile`; 


ALTER TABLE `order_receipt` ADD `amount` BIGINT NOT NULL DEFAULT '0' COMMENT 'مبلغ پرداختی' AFTER `id_ref`, ADD `verify_status` INT NOT NULL DEFAULT '0' COMMENT 'وضعیت' AFTER `amount`;  


ALTER TABLE `order_receipt` ADD `log` TEXT NOT NULL AFTER `last_ip`; 

ALTER TABLE `user_account` ADD `ref_page` VARCHAR(90) NOT NULL COMMENT 'صفحه ورود کاربر' AFTER `lastlogin`; 

CREATE TABLE IF NOT EXISTS `short_links` (
  `id_main` int(11) NOT NULL AUTO_INCREMENT,
  `long_url` text COLLATE utf8_persian_ci NOT NULL,
  `clicks` int(11) NOT NULL DEFAULT '0',
  `date_create` varchar(20) COLLATE utf8_persian_ci NOT NULL,
  `ip_last_edit` varchar(20) COLLATE utf8_persian_ci NOT NULL,
  `date_last_edit` varchar(20) COLLATE utf8_persian_ci NOT NULL,
  `user_last_edit` varchar(50) COLLATE utf8_persian_ci NOT NULL,
  PRIMARY KEY (`id_main`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci COMMENT='لینک های کوتاه';
COMMIT;

ALTER TABLE short_links AUTO_INCREMENT = 100;
ALTER TABLE `short_links` ADD `tetr` VARCHAR(90) NOT NULL COMMENT 'عنوان' AFTER `id_main`; 
ALTER TABLE `short_links` ADD `dsc` TEXT NOT NULL COMMENT 'توضیحات' AFTER `tetr`; 
ALTER TABLE `short_links` ADD `active` INT NOT NULL DEFAULT '1' COMMENT 'وضعیت فعالیت' AFTER `dsc`; 


ALTER TABLE `setting` ADD `show_notification` INT NOT NULL DEFAULT '1' COMMENT 'وضعیت فعالیت نوتیفیکیشن در پنل' AFTER `active_search_ip`, ADD `delay_notification` INT NOT NULL DEFAULT '60' COMMENT 'مقدار ثانیه تأخیر در فرخوانی نوتیفیکیشن' AFTER `show_notification`; 

ALTER TABLE `setting` ADD `last_read_order` VARCHAR(20) NOT NULL COMMENT 'آخرین زمان فراخوانی سفارشات فروش' AFTER `delay_notification`; 

ALTER TABLE `setting` ADD `show_discount` INT NOT NULL DEFAULT '1' COMMENT 'نمایش کد تخفیف در بخش تسویه حساب' AFTER `last_read_order`, ADD `show_shipping_time` INT NOT NULL DEFAULT '1' COMMENT 'نمایش زمان ارسال در بخش تسویه حساب' AFTER `show_discount`, ADD `weekends` TEXT NOT NULL COMMENT 'روزهای تعطیل در هفته' AFTER `show_shipping_time`, ADD `working_hours_in_week` TEXT NOT NULL COMMENT 'ساعات کاری در طول هفته' AFTER `weekends`; 



ALTER TABLE `setting` ADD `order_preparation` INT NOT NULL COMMENT 'مدت زمان آماده سازی سفارش براساس دقیقه' AFTER `working_hours_in_week`; 

ALTER TABLE `order_title` ADD `shipping_time` TEXT NOT NULL COMMENT 'زمان ارسال' AFTER `price_total`; 



--
-- Table structure for table `holiday`
--


CREATE TABLE IF NOT EXISTS `holiday` (
  `id_main` int(11) NOT NULL AUTO_INCREMENT,
  `date` varchar(10) COLLATE utf8_persian_ci NOT NULL,
  `dsc` text COLLATE utf8_persian_ci NOT NULL COMMENT 'توضیحات',
  `date_last_edit` varchar(20) COLLATE utf8_persian_ci NOT NULL,
  PRIMARY KEY (`id_main`),
  KEY `date` (`date`)
) ENGINE=MyISAM AUTO_INCREMENT=30 DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci COMMENT='تعطیلات سال';


INSERT INTO `holiday` (`id_main`, `date`, `dsc`, `date_last_edit`) VALUES
(1, '1405/01/01', 'عید نوروز', ''),
(2, '1405/01/02', 'عید نوروز', ''),
(3, '1405/01/03', 'عید نوروز', ''),
(4, '1405/01/04', 'عید نوروز', ''),
(5, '1405/01/12', 'روز جمهوری اسلامی ایران', ''),
(6, '1405/01/13', 'روز طبیعت', ''),
(7, '1405/01/25', '25 شوال شهادت امام جعفر صادق (ع) - قمری', ''),
(8, '1405/03/06', '10 ذی الحجه عید سعید قربان - قمری', ''),
(9, '1405/03/14', 'سالروز رحلت امام خمینی (ره)', ''),
(10, '1405/03/14', '18 ذی الحجه عید سعید غدیر خم - قمری', ''),
(11, '1405/04/03', '9 محرم تاسوعای حسینی - قمری', ''),
(12, '1405/04/04', '10 محرم عاشورای حسینی - قمری', ''),
(13, '1405/05/13', '20 صفر اربعین حسینی - قمری', ''),
(14, '1405/05/21', '28 صفر رحلت حضرت رسول (ص) - شهادت امام حسن مجتبی (ع) - قمری ', ''),
(15, '1405/05/22', 'آخر صفر شهادت امام رضا (ع) - قمری', ''),
(16, '1405/06/08', '17 ربیع اول ولادت حضرت رسول (ص) و ولادت حضرت امام جعفر صادق (ع) - قمری', ''),
(17, '1405/08/22', '3 جمادی ثانی شهادت حضرت فاطمه زهرا (س) - قمری', ''),
(18, '1405/10/02', '13 رجب ولادت امیر المؤمنین امام علی (ع) - قمری', ''),
(19, '1405/10/16', '27 رجب عید مبعث  - قمری', ''),
(20, '1405/11/04', '15 شعبان ولادت حضرت مهدی (عج) - قمری', ''),
(21, '1405/11/22', 'سالروز پیروزی انقلاب اسلامی ایرانی', ''),
(22, '1405/12/09', '21 رمضان - شهادت امام علی (ع) - قمری', ''),
(23, '1405/12/19', 'عید فطر - قمری', ''),
(24, '1405/12/20', 'تعطیلی دوم روز عید فطر - قمری', ''),
(25, '1405/12/29', 'ملی شدن صنعت نفت', '');
COMMIT;


--
-- Table structure for table `sys_tables`
--

CREATE TABLE IF NOT EXISTS `sys_tables` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `table_name` varchar(90) COLLATE utf8_persian_ci DEFAULT NULL COMMENT 'نام جدول اصلی',
  `table_label` varchar(90) COLLATE utf8_persian_ci NOT NULL COMMENT 'نام فارسی جدول',
  `delete_fieldname` varchar(50) COLLATE utf8_persian_ci NOT NULL COMMENT 'فیلد حذف اگر دارد',
  `sort_fieldname` varchar(50) COLLATE utf8_persian_ci NOT NULL COMMENT 'فیلد مرتب سازی ',
  `date_field` text COLLATE utf8_persian_ci NOT NULL COMMENT 'فیلد تاریخ جهت فیلتر کردن',
  `parent_table` varchar(90) COLLATE utf8_persian_ci NOT NULL COMMENT 'جدول پدر',
  `relation_table` text COLLATE utf8_persian_ci NOT NULL,
  `date_last_edit` varchar(20) COLLATE utf8_persian_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

--
-- Dumping data for table `sys_tables`
--

INSERT INTO `sys_tables` (`id`, `table_name`, `table_label`, `delete_fieldname`, `sort_fieldname`, `date_field`, `parent_table`, `relation_table`, `date_last_edit`) VALUES
(11, 'holiday', 'تعطیلات سال', '', 'date_last_edit', 'date', '', '', '1405/02/14 20:46:17'),
(12, 'order_detils', 'ریز سفارشات ', '', '', '', 'order_title', 'order_title.id_main = order_detils.id_order', '1405/02/12 18:56:43'),
(13, 'product', 'محصولات', 'isdeleted', 'date_last_edit', 'pdate', '', '', '1405/02/14 20:48:32'),
(4, 'order_title', 'سفارش ها', 'isdeleted', 'date_last_edit', 'date', '', '', '1405/02/31 12:52:00'),
(14, 'product_price', 'مشخصات و لیست قیمت محصولات', '', '', '', 'product', 'product.id = product_price.id_product', '1405/02/14 12:53:43'),
(15, 'user_account', 'مشتریان (کاربران) سایت', 'isdeleted', 'date_last_edit', 'date', '', '', '1405/02/14 20:48:09'),
(16, 'weblog', 'وبلاگ', '', 'date_last_edit', 'date', '', '', '1405/02/14 20:48:16'),
(17, 'user_addres', 'آدرس مشتریان', 'isdeleted', '', '', '', 'user_account.id = user_addres.id_user', '1405/03/18 12:29:23'),
(18, 'city', 'شهرها', '', '', '', '', '', '1405/03/18 21:19:51'),
(19, 'ostan', 'استان', '', '', '', '', '', '1405/03/18 21:20:21');
COMMIT;



--
-- Database: `luckyburger_main`
--

-- --------------------------------------------------------

--
-- Table structure for table `sys_fields`
--

CREATE TABLE IF NOT EXISTS `sys_fields` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_table` int(11) DEFAULT NULL,
  `table_name` varchar(90) COLLATE utf8_persian_ci DEFAULT NULL,
  `field_name` varchar(90) COLLATE utf8_persian_ci DEFAULT NULL,
  `field_label` varchar(90) COLLATE utf8_persian_ci DEFAULT NULL,
  `field_type` varchar(50) COLLATE utf8_persian_ci DEFAULT NULL,
  `field_required` int(11) DEFAULT '0',
  `field_unique` int(11) DEFAULT '0',
  `field_length` int(11) DEFAULT '0',
  `field_autoincrement` int(11) NOT NULL DEFAULT '0',
  `idsort` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `id_table` (`id_table`),
  KEY `field_name` (`field_name`)
) ENGINE=MyISAM AUTO_INCREMENT=275 DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci;

--
-- Dumping data for table `sys_fields`
--

INSERT INTO `sys_fields` (`id`, `id_table`, `table_name`, `field_name`, `field_label`, `field_type`, `field_required`, `field_unique`, `field_length`, `field_autoincrement`, `idsort`) VALUES
(254, 17, 'user_addres', 'unit', 'واحد', 'varchar', 1, 0, 20, 0, 9),
(154, 13, 'product', 'open_comment', 'ثبت دیدگاه برای این محصول', 'int', 0, 0, 1, 0, 28),
(153, 13, 'product', 'password', 'رمز برای نمایش محصول', 'text', 0, 0, 0, 0, 27),
(150, 13, 'product', 'countdn', 'تعداد بازدید محصول', 'int', 0, 0, 10, 0, 24),
(149, 13, 'product', 'vote_count', 'تعداد امتیازدهندگان', 'int', 0, 0, 11, 0, 23),
(148, 13, 'product', 'vote_sum', 'مجموع امتیازات', 'bigint', 0, 0, 20, 0, 22),
(258, 17, 'user_addres', 'recip_me', 'تحویل گیرنده خودم یا دیگری', 'int', 0, 0, 11, 0, 13),
(259, 17, 'user_addres', 'recip_name', 'نام تحویل گیرنده', 'varchar', 0, 0, 90, 0, 14),
(260, 17, 'user_addres', 'recip_mobile', 'شماره موبایل تحویل گیرنده', 'varchar', 0, 0, 20, 0, 15),
(142, 13, 'product', 'active', 'وضعیت انتشار', 'int', 1, 0, 1, 0, 16),
(141, 13, 'product', 'id_topics', 'کد دسته بندی ها', 'text', 0, 0, 0, 0, 15),
(140, 13, 'product', 'url', 'آدرس url', 'text', 0, 0, 0, 0, 14),
(139, 13, 'product', 'tags', 'برچسب ها', 'text', 0, 0, 0, 0, 13),
(138, 13, 'product', 'photo_product', 'تصویر محصول', 'text', 0, 0, 0, 0, 12),
(137, 13, 'product', 'matn', 'توضیحات اصلی متن', 'longtext', 0, 0, 0, 0, 11),
(136, 13, 'product', 'leds', 'عنوان دوم محصول', 'varchar', 0, 0, 250, 0, 10),
(135, 13, 'product', 'tetr', 'عنوان محصول', 'varchar', 1, 0, 250, 0, 9),
(134, 13, 'product', 'etime', 'ساعت انقضا', 'varchar', 0, 0, 5, 0, 8),
(132, 13, 'product', 'ptime', 'ساعت انتشار', 'varchar', 1, 0, 10, 0, 6),
(133, 13, 'product', 'edate', 'تاریخ انقضا', 'varchar', 0, 0, 10, 0, 7),
(131, 13, 'product', 'pdate', 'تاریخ انتشار', 'varchar', 1, 0, 10, 0, 5),
(130, 13, 'product', 'date_create', 'تاریخ ایجاد', 'varchar', 1, 0, 20, 0, 4),
(261, 17, 'user_addres', 'recip_email', 'ایمیل تحویل گیرنده', 'varchar', 0, 0, 90, 0, 16),
(128, 13, 'product', 'type_product', 'ساختار محصول', 'int', 1, 0, 11, 0, 2),
(127, 13, 'product', 'id', 'کد محصول', 'int', 1, 1, 20, 1, 1),
(126, 12, 'order_detils', 'total_price', 'قیمت کل ', 'bigint', 1, 0, 20, 0, 10),
(245, 12, 'order_detils', 'weight', 'وزن محصول', 'int', 1, 0, 11, 0, 9),
(124, 12, 'order_detils', 'price_discount', 'قیمت با تخفیف محصول', 'bigint', 0, 0, 20, 0, 8),
(244, 12, 'order_detils', 'price', 'قیمت کامل محصول', 'bigint', 1, 0, 20, 0, 7),
(113, 11, 'holiday', 'id_main', 'کد شمارنده', 'int', 1, 1, 11, 1, 1),
(114, 11, 'holiday', 'date', 'تاریخ', 'varchar', 1, 1, 10, 0, 2),
(115, 11, 'holiday', 'dsc', 'توضیحات', 'text', 1, 0, 0, 0, 3),
(118, 12, 'order_detils', 'id_order', 'شماره فاکتور', 'int', 1, 0, 11, 0, 2),
(119, 12, 'order_detils', 'id_product', 'کدمحصول', 'int', 1, 0, 11, 0, 3),
(120, 12, 'order_detils', 'id_price', 'کد قیمت محصول', 'int', 1, 0, 11, 0, 4),
(121, 12, 'order_detils', 'name', 'عنوان محصول با جزئیات ویژگی ها', 'text', 1, 0, 0, 0, 5),
(122, 12, 'order_detils', 'number', 'تعداد سفارش محصول', 'int', 1, 0, 11, 0, 6),
(64, 4, 'order_title', 'date_save', 'تاریخ ثبت سفارش', 'varchar', 1, 0, 20, 0, 2),
(63, 4, 'order_title', 'id_main', 'شماره فاکتور', 'int', 0, 1, 11, 1, 1),
(65, 4, 'order_title', 'date', 'تاریخ فاکتور یا سفارش', 'varchar', 1, 0, 10, 0, 3),
(66, 4, 'order_title', 'id_customer', 'کد مشتری', 'int', 1, 0, 11, 0, 4),
(67, 4, 'order_title', 'name_customer', 'نام مشتری', 'varchar', 1, 0, 90, 0, 5),
(68, 4, 'order_title', 'mobile_customer', 'موبایل مشتری', 'varchar', 1, 0, 20, 0, 6),
(70, 4, 'order_title', 'id_addres_customer', 'کد آدرس مشتری', 'int', 0, 0, 11, 0, 8),
(71, 4, 'order_title', 'id_ostan', 'کد استان محل سکونت مشتری', 'int', 0, 0, 11, 0, 9),
(72, 4, 'order_title', 'id_city', 'شهر مشتری', 'int', 0, 0, 11, 0, 10),
(73, 4, 'order_title', 'addres', 'آدرس مشتری', 'text', 0, 0, 0, 0, 11),
(74, 4, 'order_title', 'zipcode', 'کدپستی', 'varchar', 0, 0, 20, 0, 12),
(75, 4, 'order_title', 'plaque', 'پلاک', 'varchar', 0, 0, 20, 0, 13),
(76, 4, 'order_title', 'unit', 'واحد', 'varchar', 0, 0, 20, 0, 14),
(77, 4, 'order_title', 'gps', 'gps', 'varchar', 0, 0, 90, 0, 15),
(78, 4, 'order_title', 'reciver_name', 'تحویل گیرنده', 'varchar', 0, 0, 90, 0, 16),
(79, 4, 'order_title', 'reciver_mobile', 'موبایل تحویل گیرنده', 'varchar', 0, 0, 20, 0, 17),
(80, 4, 'order_title', 'tetr_addres', 'عنوان آدرس مشتری', 'varchar', 0, 0, 90, 0, 18),
(81, 4, 'order_title', 'id_discount', 'کد تخفیف', 'int', 0, 0, 11, 0, 19),
(82, 4, 'order_title', 'name_discount', 'عنوان تخفیف', 'varchar', 0, 0, 90, 0, 20),
(83, 4, 'order_title', 'code_discount', 'کد تخفیف', 'varchar', 0, 0, 20, 0, 21),
(84, 4, 'order_title', 'type_discount', 'نوع تخفیف', 'int', 0, 0, 11, 0, 22),
(85, 4, 'order_title', 'price_discount', 'مبلغ تخفیف', 'bigint', 0, 0, 20, 0, 23),
(86, 4, 'order_title', 'id_send', 'کد ارسال کننده', 'int', 0, 0, 11, 0, 24),
(87, 4, 'order_title', 'name_send', 'عنوان ارسال کننده', 'varchar', 0, 0, 90, 0, 25),
(88, 4, 'order_title', 'type_send', 'روش ارسال', 'int', 0, 0, 11, 0, 26),
(89, 4, 'order_title', 'price_send', 'هزینه ارسال', 'bigint', 0, 0, 20, 0, 27),
(90, 4, 'order_title', 'trackingcode_send', 'کد پیگیری مرسوله', 'varchar', 0, 0, 50, 0, 28),
(91, 4, 'order_title', 'status_send', 'وضعیت ارسال', 'int', 0, 0, 11, 0, 29),
(92, 4, 'order_title', 'weight_send', 'وزن مرسوله ارسال به گرم', 'bigint', 0, 0, 20, 0, 30),
(93, 4, 'order_title', 'date_send', 'زمان ارسال', 'varchar', 0, 0, 20, 0, 31),
(94, 4, 'order_title', 'date_recive', 'زمان دریافت مرسوله', 'varchar', 0, 0, 20, 0, 32),
(95, 4, 'order_title', 'id_payment', 'کد نحوه پرداخت', 'int', 0, 0, 11, 0, 33),
(96, 4, 'order_title', 'name_payment', 'تیتر نحوه پرداخت', 'varchar', 0, 0, 90, 0, 34),
(97, 4, 'order_title', 'type_payment', 'نوع درگاه', 'int', 0, 0, 11, 0, 35),
(98, 4, 'order_title', 'account_number', 'شماره حساب', 'varchar', 0, 0, 20, 0, 36),
(99, 4, 'order_title', 'price_payment', 'مبلغ پرداختی', 'bigint', 0, 0, 20, 0, 37),
(100, 4, 'order_title', 'dsc_payment', 'توضیحات پرداخت', 'text', 0, 0, 0, 0, 38),
(101, 4, 'order_title', 'status_payment', 'وضعیت پرداخت', 'int', 1, 0, 11, 0, 39),
(102, 4, 'order_title', 'date_payment', 'تاریخ سررسید', 'varchar', 0, 0, 10, 0, 40),
(103, 4, 'order_title', 'payment_recive', 'تاریخ تحویل', 'varchar', 0, 0, 10, 0, 41),
(104, 4, 'order_title', 'status_order', 'وضعیت سفارش', 'int', 1, 0, 11, 0, 42),
(105, 4, 'order_title', 'dsc', 'توضیحات سفارش', 'text', 0, 0, 0, 0, 43),
(106, 4, 'order_title', 'price_total', 'جمع سفارش ها', 'bigint', 1, 0, 20, 0, 44),
(107, 4, 'order_title', 'shipping_time', 'زمان ارسال', 'text', 0, 0, 0, 0, 45),
(155, 13, 'product', 'open_handling', 'نمایش فرم استعلام قبل از ثبت سفارش خرید', 'int', 0, 0, 11, 0, 29),
(156, 13, 'product', 'best_saller', 'محصول پرفروش', 'int', 0, 0, 11, 0, 30),
(157, 13, 'product', 'spectioal', 'محصول ویژه', 'int', 0, 0, 11, 0, 31),
(253, 17, 'user_addres', 'plaque', 'پلاک', 'varchar', 1, 0, 20, 0, 8),
(164, 13, 'product', 'videofile', 'فایل ویدئو معرفی محصول', 'text', 0, 0, 0, 0, 38),
(165, 13, 'product', 'soundfile', 'توضیحات صوتی معرفی محصول', 'text', 0, 0, 0, 0, 39),
(166, 13, 'product', 'pdffile', 'پی دی اف معرفی محصول', 'text', 0, 0, 0, 0, 40),
(255, 17, 'user_addres', 'gps', 'لوکیشن gps', 'varchar', 0, 0, 90, 0, 10),
(256, 17, 'user_addres', 'distance_to_shop', 'فاصله تا فروشگاه', 'float', 0, 0, 0, 0, 11),
(257, 17, 'user_addres', 'zipcode', 'کدپستی', 'varchar', 1, 0, 20, 0, 12),
(265, 15, 'user_account', 'password', 'اسم رمز', 'md5()', 1, 0, 90, 0, 5),
(172, 14, 'product_price', 'id_product', 'کد محصول', 'int', 1, 0, 11, 0, 2),
(173, 14, 'product_price', 'id_tag', 'شناسه محصول', 'varchar', 0, 0, 90, 0, 3),
(174, 14, 'product_price', 'price1', 'قیمت اصلی', 'bigint', 1, 0, 20, 0, 4),
(175, 14, 'product_price', 'price2', 'قیمت با تخفیف', 'bigint', 0, 0, 20, 0, 5),
(176, 14, 'product_price', 'cash', 'موجودی', 'int', 1, 0, 11, 0, 6),
(177, 14, 'product_price', 'cash_unlimited', 'موجودی نامحدود', 'int', 0, 0, 1, 0, 7),
(178, 14, 'product_price', 'weight', 'وزن', 'int', 1, 0, 11, 0, 8),
(179, 14, 'product_price', 'min_sale', 'حداقل خرید', 'int', 0, 0, 11, 0, 9),
(180, 14, 'product_price', 'max_sale', 'حداکثر خرید', 'int', 0, 0, 11, 0, 10),
(181, 14, 'product_price', 'sale_unilimited', 'خرید نامحدود', 'int', 0, 0, 11, 0, 11),
(182, 14, 'product_price', 'product_length', 'طول بسته', 'int', 0, 0, 11, 0, 12),
(183, 14, 'product_price', 'product_width', 'عرض بسته', 'int', 0, 0, 11, 0, 13),
(184, 14, 'product_price', 'product_height', 'ارتفاع بسته', 'int', 0, 0, 11, 0, 14),
(186, 15, 'user_account', 'id_main', 'کد شمارنده', 'int', 1, 1, 10, 1, 1),
(187, 15, 'user_account', 'name', 'نام و نام خانوادگی', 'varchar', 1, 0, 90, 0, 2),
(188, 15, 'user_account', 'email', 'ایمیل', 'varchar', 0, 0, 90, 0, 3),
(189, 15, 'user_account', 'mobile', 'شماره موبایل', 'varchar', 1, 0, 20, 0, 4),
(190, 15, 'user_account', 'phone', 'تلفن', 'varchar', 0, 0, 50, 0, 5),
(192, 15, 'user_account', 'addres', 'آدرس', 'text', 0, 0, 0, 0, 7),
(193, 15, 'user_account', 'id_ostan', 'کد استان', 'int', 0, 0, 11, 0, 8),
(194, 15, 'user_account', 'id_city', 'کد شهر', 'int', 0, 0, 11, 0, 9),
(195, 15, 'user_account', 'zipcode', 'کدپستی', 'varchar', 0, 0, 20, 0, 10),
(196, 15, 'user_account', 'userphoto', 'تصویر پروفایل', 'varchar', 0, 0, 90, 0, 11),
(197, 15, 'user_account', 'dsc', 'توضیحات', 'text', 0, 0, 0, 0, 12),
(202, 15, 'user_account', 'date', 'تاریخ ثبت نام', 'varchar', 0, 0, 20, 0, 17),
(203, 15, 'user_account', 'birthday', 'تاریخ تولد', 'varchar', 0, 0, 10, 0, 18),
(206, 15, 'user_account', 'ref_page', 'صفحه ورود کاربر', 'varchar', 0, 0, 90, 0, 21),
(211, 16, 'weblog', 'id', 'کد شمارنده', 'int', 1, 1, 10, 1, 1),
(212, 16, 'weblog', 'user_create', 'کاربر ایجاد کننده مطلب', 'varchar', 0, 0, 50, 0, 2),
(213, 16, 'weblog', 'date', 'تاریخ نمایش', 'varchar', 0, 0, 10, 0, 3),
(214, 16, 'weblog', 'time', 'ساعت نمایش', 'varchar', 0, 0, 10, 0, 4),
(215, 16, 'weblog', 'tetr', 'عنوان بلاگ', 'text', 0, 0, 0, 0, 5),
(216, 16, 'weblog', 'leds', 'خلاصه متن', 'text', 0, 0, 0, 0, 6),
(217, 16, 'weblog', 'show_header', 'نمایش فهرست مطالب\r\n', 'int', 0, 0, 11, 0, 7),
(218, 16, 'weblog', 'header', 'فهرست مطالب', 'longtext', 0, 0, 0, 0, 8),
(219, 16, 'weblog', 'matn', 'متن وبلاگ', 'longtext', 0, 0, 0, 0, 9),
(220, 16, 'weblog', 'photo_news', 'لینک تصویر وبلاگ', 'text', 0, 0, 0, 0, 10),
(221, 16, 'weblog', 'photo_matn', 'تصویر متن', 'text', 0, 0, 0, 0, 11),
(222, 16, 'weblog', 'tags', 'کلید واژه ها', 'text', 0, 0, 0, 0, 12),
(224, 16, 'weblog', 'id_topics', 'کد دسته بندی ها', 'text', 0, 0, 0, 0, 14),
(227, 16, 'weblog', 'countdn', 'تعداد بازدید', 'int', 0, 0, 10, 0, 17),
(230, 16, 'weblog', 'videofile', 'لینک ویدئو وبلاگ', 'text', 0, 0, 0, 0, 20),
(231, 16, 'weblog', 'soundfile', 'لینک صوت وبلاگ', 'text', 0, 0, 0, 0, 21),
(232, 16, 'weblog', 'pdffile', 'لینک پی دی اف وبلاگ', 'text', 0, 0, 0, 0, 22),
(233, 16, 'weblog', 'id_releations', 'کد اخبار مرتبط', 'text', 0, 0, 0, 0, 23),
(234, 16, 'weblog', 'password', 'رمز جهت نمایش وبلاگ', 'text', 0, 0, 0, 0, 24),
(252, 17, 'user_addres', 'addres', 'آدرس', 'text', 1, 0, 0, 0, 7),
(251, 17, 'user_addres', 'id_sector', 'محله', 'int', 0, 0, 11, 0, 6),
(249, 17, 'user_addres', 'id_ostan', 'استان', 'int', 0, 0, 11, 0, 4),
(250, 17, 'user_addres', 'id_city', 'شهر', 'int', 0, 0, 11, 0, 5),
(248, 17, 'user_addres', 'tetr', 'عنوان آدرس', 'varchar', 1, 0, 90, 0, 3),
(247, 17, 'user_addres', 'id_user', 'کد کاربر', 'int', 1, 0, 11, 0, 2),
(246, 17, 'user_addres', 'id_main', 'کد شمارنده', 'int', 0, 1, 11, 1, 1),
(264, 17, 'user_addres', 'date_last_edit', 'تاریخ آخرین ویرایش', 'varchar', 0, 0, 20, 0, 19),
(266, 14, 'product_price', 'id', 'کد قیمت', 'int', 1, 1, 11, 1, 1),
(267, 19, 'ostan', 'id', 'کد استان', 'int', 0, 1, 11, 1, 1),
(268, 19, 'ostan', 'name', 'نام استان', 'varchar', 0, 0, 30, 0, 2),
(270, 19, 'ostan', 'tel_prefix', 'پیش شماره تلفن استان', 'varchar', 0, 0, 3, 0, 4),
(271, 18, 'city', 'id', 'کد شهر', 'int', 0, 1, 11, 0, 1),
(272, 18, 'city', 'name', 'نام شهر', 'varchar', 0, 0, 30, 0, 2),
(274, 18, 'city', 'id_ostan', 'کد استان', 'int', 0, 0, 11, 0, 4);
COMMIT;


CREATE TABLE IF NOT EXISTS `sys_import` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type_input` int(11) NOT NULL DEFAULT '1' COMMENT 'نوع ورودی',
  `inputfile_name` text COLLATE utf8_persian_ci NOT NULL COMMENT 'نام فایل اکسل ورودی',
  `inputlink_url` text COLLATE utf8_persian_ci NOT NULL COMMENT 'لینک api ورودی',
  `header_url` text COLLATE utf8_persian_ci NOT NULL COMMENT 'تنظیمات هدر لینک api',
  `parameter_url` text COLLATE utf8_persian_ci NOT NULL COMMENT 'پارامترهای ارسالی لینک api',
  `data_json` int(11) NOT NULL DEFAULT '0' COMMENT 'اطلاعات ارسالی به api جیسون باشد',
  `firstrow_header` int(11) NOT NULL DEFAULT '0' COMMENT 'اولین سطر فایل اکسل هدر هست',
  `overwrite_record` int(11) NOT NULL DEFAULT '0' COMMENT 'رکوردهای تکراری',
  `id_table` int(11) NOT NULL COMMENT 'کد جدول اصلی',
  `table_name` text COLLATE utf8_persian_ci NOT NULL COMMENT 'نام جدول اصلی',
  `table_label` text COLLATE utf8_persian_ci NOT NULL COMMENT 'عنوان جدول',
  `content_file` longtext COLLATE utf8_persian_ci NOT NULL COMMENT 'متن فایل',
  `count_infile` int(11) NOT NULL COMMENT 'تعداد رکورد موجود در فایل یا لینک',
  `count_all` int(11) NOT NULL COMMENT 'تعداد رکورد خوانده شده',
  `status` varchar(50) COLLATE utf8_persian_ci NOT NULL COMMENT 'وضعیت خواندن لینک یا فایل',
  `convert_listfield` text COLLATE utf8_persian_ci NOT NULL COMMENT 'نقشه تطابق فیلدها',
  `content_export` text COLLATE utf8_persian_ci NOT NULL COMMENT 'گزارش عملکرد خروجی',
  `date_last_edit` varchar(20) COLLATE utf8_persian_ci NOT NULL,
  `user_last_edit` varchar(50) COLLATE utf8_persian_ci NOT NULL,
  `ip_last_edit` varchar(20) COLLATE utf8_persian_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_persian_ci COMMENT='آرشیو عملیات ورود اطلاعات';
COMMIT;


ALTER TABLE `manager_account` ADD `user_create` VARCHAR(90) NOT NULL COMMENT 'مدیر ایجاد کننده این اکانت' AFTER `otp_cookie_token`; 

*/