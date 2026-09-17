<?php
include('topmain.php');
$type = isset($_GET['type']) ? (int)$_GET['type'] : 0;
$has_type = ($type > 0);
$title_main = ' ابزار محاسبه‌گر رایگان وی کوچ ';
$canonical  = "$canonical/calculator/$type";
$schema = '';
$schema_url = $canonical;

/*
|--------------------------------------------------------------------------
| اطلاعات ورودی‌ها
|--------------------------------------------------------------------------
*/
$input_info = array(
    'weight' => array('title' => 'وزن', 'unit' => 'کیلوگرم', 'step' => '0.1'),
    'height' => array('title' => 'قد', 'unit' => 'سانتی‌متر', 'step' => '0.1'),
    'age' => array('title' => 'سن', 'unit' => 'سال', 'step' => '1'),
    'body_fat' => array('title' => 'درصد چربی بدن', 'unit' => 'درصد', 'step' => '0.1'),
    'bmr' => array('title' => 'BMR', 'unit' => 'کیلوکالری در روز', 'step' => '1'),
    'tdee' => array('title' => 'TDEE', 'unit' => 'کیلوکالری در روز', 'step' => '1'),
    'calories' => array('title' => 'کالری روزانه', 'unit' => 'کیلوکالری', 'step' => '1'),
    'protein' => array('title' => 'پروتئین', 'unit' => 'گرم', 'step' => '0.1'),
    'fat' => array('title' => 'چربی', 'unit' => 'گرم', 'step' => '0.1'),
    'carbs' => array('title' => 'کربوهیدرات', 'unit' => 'گرم', 'step' => '0.1'),
    // ورودی‌های جدید
    'reps' => array('title' => 'تعداد تکرار', 'unit' => 'تکرار', 'step' => '1'),
    'resting_hr' => array('title' => 'ضربان قلب استراحت', 'unit' => 'ضربان بر دقیقه', 'step' => '1'),
    'intensity' => array('title' => 'شدت تمرین', 'unit' => 'درصد', 'step' => '5'),
    'fat_kg' => array('title' => 'مقدار چربی', 'unit' => 'کیلوگرم', 'step' => '0.1'),
    'duration' => array('title' => 'مدت تمرین', 'unit' => 'ساعت', 'step' => '0.5')
);

/*
|--------------------------------------------------------------------------
| توابع محاسبه خودکار (پشت صحنه)
|--------------------------------------------------------------------------
*/
function calc_bmr($v) {
    // فرمول پیش‌فرض Mifflin-St Jeor
    if ($v['gender'] === 'female') {
        return (10 * $v['weight']) + (6.25 * $v['height']) - (5 * $v['age']) - 161;
    }
    return (10 * $v['weight']) + (6.25 * $v['height']) - (5 * $v['age']) + 5;
}

function calc_tdee($v) {
    $bmr = calc_bmr($v);
    $activity = isset($v['activity']) ? (float)$v['activity'] : 1.55;
    return $bmr * $activity;
}

/*
|--------------------------------------------------------------------------
| لیست فرمول‌ها
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
| لیست فرمول‌ها (به‌روزرسانی‌شده با توضیحات تخصصی)
|--------------------------------------------------------------------------
*/
$formulas = array(
    1 => array(
        'title' => 'BMI', 
        'short' => 'شاخص توده بدنی', 
        'category' => 'ترکیب بدن', 
        'color' => 'primary', 
        'description' => 'BMI نسبت وزن به قد را بررسی می‌کند و یک روش ساده برای ارزیابی اولیه وضعیت وزن است.<br>نتیجه می‌تواند نشان دهد وزن فرد در محدوده کم‌وزن، وزن مناسب، اضافه‌وزن یا چاقی قرار دارد.<br><strong>نکته:</strong> BMI میزان عضله و چربی بدن را به‌طور مستقیم اندازه‌گیری نمی‌کند؛ بنابراین برای ورزشکاران عضلانی ممکن است تفسیر دقیقی نداشته باشد.', 
        'formula' => 'BMI = Weight ÷ Height²', 
        'inputs' => array('weight', 'height'), 
        'handler' => 'bmi', 
        'unit' => '', 
        'decimals' => 1
    ),
    2 => array(
        'title' => 'BMR کلاسیک', 
        'short' => 'مقایسه فرمول‌های معتبر', 
        'category' => 'متابولیسم', 
        'color' => 'info', 
        'description' => 'متابولیسم پایه (BMR) مقدار انرژی‌ای است که بدن در حالت استراحت مطلق برای فعالیت‌های حیاتی مصرف می‌کند.<br>این ابزار به‌طور همزمان نتیجه را بر اساس دو فرمول معتبر و پرکاربرد (Mifflin-St Jeor و Harris-Benedict) محاسبه و مقایسه می‌کند تا دید دقیق‌تری به شما بدهد.', 
        'formula' => 'Mifflin-St Jeor و Harris-Benedict (بر اساس جنسیت، وزن، قد و سن)', 
        'inputs' => array('gender', 'weight', 'height', 'age'), 
        'handler' => 'bmr_combined', 
        'unit' => 'kcal/day', 
        'decimals' => 0
    ),
    // نکته: آیتم شماره 4 (Harris-Benedict جداگانه) حذف شد چون در همین ابزار ادغام گردید.    
/*    
    2 => array(
        'title' => 'BMR - Mifflin-St Jeor', 
        'short' => 'متابولیسم پایه', 
        'category' => 'متابولیسم', 
        'color' => 'success', 
        'description' => 'BMR مقدار انرژی‌ای است که بدن در حالت استراحت برای انجام فعالیت‌های حیاتی مانند تنفس، گردش خون و عملکرد اندام‌ها مصرف می‌کند.<br>این عدد یکی از پایه‌های محاسبه کالری موردنیاز روزانه است و جنسیت در محاسبه آن مؤثر است.', 
        'formula' => "مردان: (10×W) + (6.25×H) - (5×A) + 5 <br> زنان: (10×W) + (6.25×H) - (5×A) - 161", 
        'inputs' => array('gender', 'weight', 'height', 'age'), 
        'handler' => 'mifflin', 
        'unit' => 'kcal/day', 
        'decimals' => 1
    ),
    4 => array(
        'title' => 'BMR - Harris-Benedict', 
        'short' => 'BMR کلاسیک', 
        'category' => 'متابولیسم', 
        'color' => 'info', 
        'description' => 'فرمول کلاسیک Harris-Benedict برای تخمین انرژی موردنیاز پایه بدن استفاده می‌شود.<br>با انتخاب جنسیت، ضریب مناسب اعمال شده و با ترکیب این مقدار با سطح فعالیت، می‌توان نیاز تقریبی کالری روزانه را محاسبه کرد.', 
        'formula' => 'مردان: 88.362 + 13.397 W + 4.799H - 5.677A <br> زنان: 447.593 + 9.247W + 3.098H - 4.330A', 
        'inputs' => array('gender', 'weight', 'height', 'age'), 
        'handler' => 'harris', 
        'unit' => 'kcal/day', 
        'decimals' => 0
    ),
*/    
    6 => array(
        'title' => 'BMR ورزشکاران ', 
        'short' => 'بر اساس توده بدون چربی', 
        'category' => 'متابولیسم', 
        'color' => 'warning', 
        'description' => 'این فرمول برای ورزشکاران و افرادی با توده عضلانی بالا طراحی شده است.<br>سیستم به‌طور خودکار ابتدا درصد چربی بدن شما را بر اساس BMI و سن تخمین می‌زند، سپس BMR را بر اساس توده بدون چربی (LBM) محاسبه می‌کند که برای ورزشکاران دقیق‌تر است.', 
        'formula' => 'Body Fat % = (1.20 × BMI) + (0.23 × Age) - (10.8 × Gender) - 5.4<br>LBM = W × (1 - BF/100)<br>BMR = 370 + (21.6 × LBM)', 
        'inputs' => array('gender', 'weight', 'height', 'age'), 
        'handler' => 'katch_athlete', 
        'unit' => 'kcal/day', 
        'decimals' => 1
    ),
    7 => array(
        'title' => 'TDEE', 
        'short' => 'کل انرژی مصرفی', 
        'category' => 'مصرف انرژی', 
        'color' => 'info', 
        'description' => 'TDEE مقدار تقریبی کالری‌ای است که بدن در طول یک روز با سطح فعالیت انتخابی شما مصرف می‌کند.<br>این عدد شامل انرژی پایه بدن (BMR) و فعالیت‌های روزانه است و نقطه شروع مناسبی برای تنظیم کالری رژیم می‌باشد.', 
        'formula' => 'TDEE = BMR × ضریب فعالیت', 
        'inputs' => array('gender', 'weight', 'height', 'age', 'activity'), 
        'handler' => 'tdee_dynamic', 
        'unit' => 'kcal/day', 
        'decimals' => 0
    ),
    12 => array(
        'title' => 'کاهش وزن استاندارد', 
        'short' => 'کسری 10 تا 20 درصد', 
        'category' => 'هدف کالری', 
        'color' => 'success', 
        'description' => 'در این روش، کالری دریافتی کمی کمتر از نیاز روزانه بدن (TDEE) تعیین می‌شود تا کاهش وزن به شکل تدریجی و قابل مدیریت انجام شود.<br>میزان کسری کالری باید متناسب با شرایط فرد تنظیم شود.', 
        'formula' => 'Target = TDEE × 0.80 تا 0.90', 
        'inputs' => array('gender', 'weight', 'height', 'age', 'activity'), 
        'handler' => 'range_80_90', 
        'unit' => 'kcal/day', 
        'decimals' => 0
    ),
    13 => array(
        'title' => 'کاهش وزن تهاجمی', 
        'short' => 'کسری 20 تا 30 درصد', 
        'category' => 'هدف کالری', 
        'color' => 'danger', 
        'description' => 'در این روش کسری کالری بیشتری نسبت به کاهش وزن استاندارد ایجاد می‌شود.<br><strong>هشدار:</strong> استفاده طولانی‌مدت از کسری شدید کالری می‌تواند دریافت مواد مغذی و عملکرد ورزشی را تحت تأثیر قرار دهد؛ بنابراین برای همه افراد مناسب نیست.', 
        'formula' => 'Target = TDEE × 0.70 تا 0.80', 
        'inputs' => array('gender', 'weight', 'height', 'age', 'activity'), 
        'handler' => 'range_70_80', 
        'unit' => 'kcal/day', 
        'decimals' => 0
    ),
    14 => array(
        'title' => 'افزایش حجم کنترل‌شده', 
        'short' => 'Lean Bulk', 
        'category' => 'هدف کالری', 
        'color' => 'success', 
        'description' => 'برای افزایش توده عضلانی، کالری دریافتی کمی بالاتر از نیاز روزانه (TDEE) قرار می‌گیرد.<br>هدف این روش حمایت از رشد عضله با کنترل و به حداقل رساندن افزایش چربی بدن است.', 
        'formula' => 'Target = TDEE + 250 تا 400 kcal', 
        'inputs' => array('gender', 'weight', 'height', 'age', 'activity'), 
        'handler' => 'range_plus_250_400', 
        'unit' => 'kcal/day', 
        'decimals' => 0
    ),
    15 => array(
        'title' => 'افزایش حجم بالا', 
        'short' => 'Bulk', 
        'category' => 'هدف کالری', 
        'color' => 'warning', 
        'description' => 'در این روش میزان کالری مازاد بیشتر است و می‌تواند افزایش وزن سریع‌تری ایجاد کند.<br>بخشی از این افزایش وزن ممکن است به شکل چربی باشد؛ بنابراین مقدار کالری باید متناسب با هدف و شرایط فرد انتخاب شود.', 
        'formula' => 'Target = TDEE + 500 تا 700 kcal', 
        'inputs' => array('gender', 'weight', 'height', 'age', 'activity'), 
        'handler' => 'range_plus_500_700', 
        'unit' => 'kcal/day', 
        'decimals' => 0
    ),
    16 => array(
        'title' => 'حفظ وزن', 
        'short' => 'Maintenance', 
        'category' => 'هدف کالری', 
        'color' => 'primary', 
        'description' => 'در حالت حفظ وزن، کالری دریافتی تقریباً با کالری مصرفی روزانه (TDEE) برابر در نظر گرفته می‌شود.<br>هدف، حفظ وزن فعلی و ترکیب بدن در طول زمان است.', 
        'formula' => 'Target = TDEE', 
        'inputs' => array('gender', 'weight', 'height', 'age', 'activity'), 
        'handler' => 'maintenance', 
        'unit' => 'kcal/day', 
        'decimals' => 0
    ),
    17 => array(
        'title' => 'پروتئین در کاهش وزن', 
        'short' => 'حفظ عضله', 
        'category' => 'ماکروها', 
        'color' => 'success', 
        'description' => 'پروتئین کافی در دوره کاهش وزن می‌تواند به حفظ توده عضلانی، افزایش احساس سیری و حمایت از ریکاوری کمک کند.<br>مقدار مناسب پروتئین به وزن، ترکیب بدن، فعالیت و شرایط فرد بستگی دارد.', 
        'formula' => 'Protein = 1.8 تا 2.2 g × W', 
        'inputs' => array('weight'), 
        'handler' => 'range_protein_18_22', 
        'unit' => 'g/day', 
        'decimals' => 0
    ),
    18 => array(
        'title' => 'پروتئین حجم', 
        'short' => 'عضله‌سازی', 
        'category' => 'ماکروها', 
        'color' => 'primary', 
        'description' => 'در دوره افزایش حجم، دریافت پروتئین کافی برای حمایت از ساخت و ترمیم بافت عضلانی اهمیت دارد.<br>بهتر است پروتئین روزانه در چند وعده توزیع شود.', 
        'formula' => 'Protein = 1.6 تا 2.2 g × W', 
        'inputs' => array('weight'), 
        'handler' => 'range_protein_16_22', 
        'unit' => 'g/day', 
        'decimals' => 0
    ),
    19 => array(
        'title' => 'پروتئین ورزشکار حرفه‌ای', 
        'short' => 'پروتئین بالا', 
        'category' => 'ماکروها', 
        'color' => 'warning', 
        'description' => 'ورزشکاران حرفه‌ای به دلیل حجم و شدت بالاتر تمرین ممکن است به پروتئین بیشتری نسبت به افراد کم‌تحرک نیاز داشته باشند.<br>مقدار دقیق باید بر اساس رشته ورزشی، حجم تمرین و هدف تعیین شود.', 
        'formula' => 'Protein = 1.8 تا 2.4 g × W', 
        'inputs' => array('weight'), 
        'handler' => 'range_protein_18_24', 
        'unit' => 'g/day', 
        'decimals' => 0
    ),
    20 => array(
        'title' => 'پروتئین سالمندان', 
        'short' => 'حفظ توده عضلانی', 
        'category' => 'ماکروها', 
        'color' => 'info', 
        'description' => 'با افزایش سن، توجه به دریافت پروتئین کافی اهمیت بیشتری پیدا می‌کند، زیرا حفظ توده و عملکرد عضلات اهمیت ویژه‌ای دارد.<br>نیاز دقیق به شرایط فرد، سطح فعالیت و وضعیت سلامت بستگی دارد.', 
        'formula' => 'Protein = 1.2 تا 1.6 g × W', 
        'inputs' => array('weight'), 
        'handler' => 'range_protein_12_16', 
        'unit' => 'g/day', 
        'decimals' => 0
    ),
    21 => array(
        'title' => 'چربی استاندارد', 
        'short' => 'محاسبه چربی', 
        'category' => 'ماکروها', 
        'color' => 'warning', 
        'description' => 'چربی یکی از سه درشت‌مغذی اصلی است و در تأمین انرژی، ساخت هورمون‌ها و جذب برخی ویتامین‌ها نقش دارد.<br>مقدار مصرف باید متناسب با نیاز انرژی و الگوی غذایی فرد تنظیم شود.', 
        'formula' => 'Fat = 0.8 تا 1.0 g × W', 
        'inputs' => array('weight'), 
        'handler' => 'range_fat_08_10', 
        'unit' => 'g/day', 
        'decimals' => 0
    ),
    22 => array(
        'title' => 'چربی کتوژنیک', 
        'short' => 'سهم انرژی از چربی', 
        'category' => 'ماکروها', 
        'color' => 'warning', 
        'description' => 'در رژیم کتوژنیک، سهم کربوهیدرات بسیار محدود و سهم چربی بالاتر است.<br>نسبت دقیق درشت‌مغذی‌ها باید بر اساس نوع رژیم و شرایط فرد تعیین شود.', 
        'formula' => 'Fat = 70 تا 75٪ کالری ÷ 9', 
        'inputs' => array('calories'), 
        'handler' => 'range_fat_keto', 
        'unit' => 'g/day', 
        'decimals' => 0
    ),
    23 => array(
        'title' => 'کربوهیدرات استاندارد', 
        'short' => 'کالری باقی‌مانده', 
        'category' => 'ماکروها', 
        'color' => 'primary', 
        'description' => 'کربوهیدرات یکی از منابع اصلی انرژی بدن، به‌ویژه هنگام فعالیت بدنی است.<br>پس از تعیین پروتئین و چربی، کالری باقی‌مانده به کربوهیدرات اختصاص داده می‌شود و نیاز به آن بر اساس سطح فعالیت تغییر می‌کند.', 
        'formula' => 'Carb g = [Calories - (Protein×4) - (Fat×9)] ÷ 4', 
        'inputs' => array('calories', 'protein', 'fat'), 
        'handler' => 'carbs_remaining', 
        'unit' => 'g/day', 
        'decimals' => 0
    ),
    24 => array(
        'title' => 'کالری پروتئین', 
        'short' => 'تبدیل پروتئین به کالری', 
        'category' => 'ماکروها', 
        'color' => 'success', 
        'description' => 'هر گرم پروتئین تقریباً ۴ کیلوکالری انرژی فراهم می‌کند.<br>این ابزار مقدار کالری حاصل از پروتئین موجود در رژیم را محاسبه می‌کند.', 
        'formula' => 'Protein kcal = Protein g × 4', 
        'inputs' => array('protein'), 
        'handler' => 'protein_kcal', 
        'unit' => 'kcal', 
        'decimals' => 0
    ),
    25 => array(
        'title' => 'کالری کربوهیدرات', 
        'short' => 'تبدیل کربوهیدرات به کالری', 
        'category' => 'ماکروها', 
        'color' => 'primary', 
        'description' => 'هر گرم کربوهیدرات تقریباً ۴ کیلوکالری انرژی فراهم می‌کند.<br>با این ابزار می‌توان سهم انرژی کربوهیدرات را محاسبه کرد.', 
        'formula' => 'Carb kcal = Carb g × 4', 
        'inputs' => array('carbs'), 
        'handler' => 'carb_kcal', 
        'unit' => 'kcal', 
        'decimals' => 0
    ),
    26 => array(
        'title' => 'کالری چربی', 
        'short' => 'تبدیل چربی به کالری', 
        'category' => 'ماکروها', 
        'color' => 'warning', 
        'description' => 'هر گرم چربی تقریباً ۹ کیلوکالری انرژی دارد.<br>بنابراین حتی مقدار نسبتاً کمی چربی می‌تواند کالری قابل توجهی به رژیم اضافه کند.', 
        'formula' => 'Fat kcal = Fat g × 9', 
        'inputs' => array('fat'), 
        'handler' => 'fat_kcal', 
        'unit' => 'kcal', 
        'decimals' => 0
    ),
    27 => array(
        'title' => 'درصد پروتئین', 
        'short' => 'سهم انرژی پروتئین', 
        'category' => 'ماکروها', 
        'color' => 'success', 
        'description' => 'این شاخص نشان می‌دهد چه درصدی از کالری روزانه رژیم از پروتئین تأمین می‌شود.', 
        'formula' => '(Protein kcal ÷ Total kcal) × 100', 
        'inputs' => array('protein', 'calories'), 
        'handler' => 'protein_percent', 
        'unit' => '%', 
        'decimals' => 1
    ),
    28 => array(
        'title' => 'درصد کربوهیدرات', 
        'short' => 'سهم انرژی کربوهیدرات', 
        'category' => 'ماکروها', 
        'color' => 'primary', 
        'description' => 'این مقدار نشان می‌دهد چه درصدی از کالری روزانه از کربوهیدرات تأمین می‌شود.', 
        'formula' => '(Carb kcal ÷ Total kcal) × 100', 
        'inputs' => array('carbs', 'calories'), 
        'handler' => 'carb_percent', 
        'unit' => '%', 
        'decimals' => 1
    ),
    29 => array(
        'title' => 'درصد چربی', 
        'short' => 'سهم انرژی چربی', 
        'category' => 'ماکروها', 
        'color' => 'warning', 
        'description' => 'این شاخص سهم کالری دریافتی از چربی را نسبت به کل کالری روزانه نشان می‌دهد.', 
        'formula' => '(Fat kcal ÷ Total kcal) × 100', 
        'inputs' => array('fat', 'calories'), 
        'handler' => 'fat_percent', 
        'unit' => '%', 
        'decimals' => 1
    ),
    30 => array(
        'title' => 'وزن ایده‌آل - Devine', 
        'short' => 'وزن مرجع', 
        'category' => 'وزن هدف', 
        'color' => 'info', 
        'description' => 'وزن ایده‌آل یک عدد ثابت برای همه افراد نیست. این ابزار یک محدوده تخمینی وزن مناسب بر اساس قد ارائه می‌کند.<br>ترکیب بدن، میزان عضله و شرایط فرد نیز باید در تفسیر نتیجه در نظر گرفته شود.', 
        'formula' => 'مردان: 50 + 2.3×inches over 60in | زنان: 45.5 + 2.3×inches over 60in', 
        'inputs' => array('gender', 'height'), 
        'handler' => 'devine', 
        'unit' => 'kg', 
        'decimals' => 1
    ),
    31 => array(
        'title' => 'کاهش وزن هفتگی', 
        'short' => '0.5 تا 1 درصد وزن', 
        'category' => 'کنترل وزن', 
        'color' => 'danger', 
        'description' => 'این ابزار میزان کاهش وزن پیشنهادی را نسبت به وزن فعلی محاسبه می‌کند.<br>کاهش وزن بهتر است به‌صورت تدریجی و با حفظ توده عضلانی و دریافت مواد مغذی کافی انجام شود.', 
        'formula' => 'Weekly loss = 0.5٪ تا 1٪ × Weight', 
        'inputs' => array('weight'), 
        'handler' => 'weekly_loss', 
        'unit' => 'kg/week', 
        'decimals' => 2
    ),
    32 => array(
        'title' => 'آب روزانه', 
        'short' => '35 تا 45 میلی‌لیتر × وزن', 
        'category' => 'هیدراتاسیون', 
        'color' => 'info', 
        'description' => 'آب برای عملکرد طبیعی بدن، تنظیم دما، انتقال مواد مغذی و عملکرد عضلات ضروری است.<br>این ابزار مقدار تقریبی نیاز روزانه به مایعات را تخمین می‌زند؛ اما نیاز واقعی با گرما، تعریق، فعالیت و نوع ورزش تغییر می‌کند.', 
        'formula' => 'Water = 35 تا 45 ml × Weight', 
        'inputs' => array('weight'), 
        'handler' => 'water', 
        'unit' => 'ml/day', 
        'decimals' => 0
    ),
    33 => array(
        'title' => 'فیبر روزانه', 
        'short' => '14 گرم به ازای هر 1000 kcal', 
        'category' => 'تغذیه', 
        'color' => 'success', 
        'description' => 'فیبر به سلامت دستگاه گوارش، احساس سیری و تنظیم قند خون کمک می‌کند.<br>نیاز روزانه به فیبر به میزان انرژی دریافتی و الگوی غذایی فرد وابسته است.', 
        'formula' => 'Fiber = 14 g × (Calories ÷ 1000)', 
        'inputs' => array('calories'), 
        'handler' => 'fiber', 
        'unit' => 'g/day', 
        'decimals' => 1
    ),
/*
    34 => array(
        'title' => 'تعداد وعده‌های غذایی', 
        'short' => 'تقسیم رژیم', 
        'category' => 'وعده‌بندی', 
        'color' => 'secondary', 
        'description' => 'تعداد وعده‌های غذایی را می‌توان بر اساس برنامه روزانه، هدف، ترجیحات غذایی و شرایط فرد تعیین کرد.<br>تعداد وعده‌ها به‌تنهایی عامل تعیین‌کننده کاهش یا افزایش وزن نیست.', 
        'formula' => 'معمولاً 3 تا 6 وعده', 
        'inputs' => array(), 
        'handler' => 'meal_count', 
        'unit' => 'وعده', 
        'decimals' => 0
    ),
*/    
    35 => array(
        'title' => 'تقسیم پروتئین وعده‌ای', 
        'short' => 'پروتئین هر وعده', 
        'category' => 'وعده‌بندی', 
        'color' => 'success', 
        'description' => 'به جای دریافت تمام پروتئین روزانه در یک یا دو وعده، می‌توان آن را در چند وعده توزیع کرد.<br>این کار باعث می‌شود دریافت پروتئین در طول روز منظم‌تر باشد و مقدار کل پروتئین روزانه بین وعده‌ها تقسیم شود.', 
        'formula' => 'Protein per meal = Total Protein ÷ Meals', 
        'inputs' => array('protein', 'meals'), 
        'handler' => 'protein_per_meal', 
        'unit' => 'g/meal', 
        'decimals' => 1
    ),
    36 => array(
        'title' => 'کربوهیدرات اطراف تمرین', 
        'short' => '30 تا 50 درصد', 
        'category' => 'زمان‌بندی تغذیه', 
        'color' => 'primary', 
        'description' => 'توزیع کربوهیدرات در اطراف زمان تمرین می‌تواند به تأمین انرژی تمرین و حمایت از ریکاوری کمک کند.<br>مقدار و زمان‌بندی مناسب به نوع و شدت ورزش بستگی دارد.', 
        'formula' => 'Training carbs = 30٪ تا 50٪ × Total carbs', 
        'inputs' => array('carbs'), 
        'handler' => 'training_carbs', 
        'unit' => 'g', 
        'decimals' => 0
    ),
/*    
    37 => array(
        'title' => 'وعده قبل تمرین', 
        'short' => 'Pre Workout', 
        'category' => 'زمان‌بندی تغذیه', 
        'color' => 'primary', 
        'description' => 'وعده قبل تمرین برای تأمین انرژی و آماده‌سازی بدن برای فعالیت طراحی می‌شود.<br>ترکیب آن باید با توجه به فاصله تا تمرین، شدت ورزش و تحمل گوارشی فرد انتخاب شود.', 
        'formula' => 'کربوهیدرات متوسط + پروتئین', 
        'inputs' => array(), 
        'handler' => 'pre_workout', 
        'unit' => '', 
        'decimals' => 0
    ),
    38 => array(
        'title' => 'وعده بعد تمرین', 
        'short' => 'Post Workout', 
        'category' => 'زمان‌بندی تغذیه', 
        'color' => 'success', 
        'description' => 'بعد از تمرین، دریافت مواد غذایی مناسب به تأمین انرژی و حمایت از فرآیند ریکاوری کمک می‌کند.<br>ترکیبی مناسب از پروتئین و کربوهیدرات می‌تواند در بسیاری از برنامه‌های ورزشی کاربرد داشته باشد.', 
        'formula' => 'پروتئین + کربوهیدرات', 
        'inputs' => array(), 
        'handler' => 'post_workout', 
        'unit' => '', 
        'decimals' => 0
    ),
*/    
    39 => array(
        'title' => 'کالری صبحانه', 
        'short' => '20 تا 30 درصد', 
        'category' => 'وعده‌بندی', 
        'color' => 'warning', 
        'description' => 'این ابزار مقدار پیشنهادی کالری صبحانه را بر اساس کل کالری روزانه و الگوی تقسیم وعده‌ها تخمین می‌زند.', 
        'formula' => 'Breakfast = 20٪ تا 30٪ × Daily calories', 
        'inputs' => array('calories'), 
        'handler' => 'breakfast', 
        'unit' => 'kcal', 
        'decimals' => 0
    ),
    40 => array(
        'title' => 'کالری ناهار', 
        'short' => '30 تا 40 درصد', 
        'category' => 'وعده‌بندی', 
        'color' => 'warning', 
        'description' => 'مقدار کالری ناهار بر اساس نیاز انرژی روزانه و نحوه توزیع کالری بین وعده‌ها محاسبه می‌شود.', 
        'formula' => 'Lunch = 30٪ تا 40٪ × Daily calories', 
        'inputs' => array('calories'), 
        'handler' => 'lunch', 
        'unit' => 'kcal', 
        'decimals' => 0
    ),
    41 => array(
        'title' => 'کالری شام', 
        'short' => '25 تا 35 درصد', 
        'category' => 'وعده‌بندی', 
        'color' => 'warning', 
        'description' => 'کالری شام بخشی از نیاز انرژی روزانه است که بر اساس کل کالری و ساختار وعده‌های غذایی تعیین می‌شود.', 
        'formula' => 'Dinner = 25٪ تا 35٪ × Daily calories', 
        'inputs' => array('calories'), 
        'handler' => 'dinner', 
        'unit' => 'kcal', 
        'decimals' => 0
    ),
    42 => array(
        'title' => 'میان‌وعده اول', 
        'short' => '10 تا 15 درصد', 
        'category' => 'وعده‌بندی', 
        'color' => 'secondary', 
        'description' => 'این ابزار مقدار کالری اختصاص‌یافته به میان‌وعده اول را بر اساس برنامه غذایی روزانه تخمین می‌زند.', 
        'formula' => 'Snack = 10٪ تا 15٪ × Daily calories', 
        'inputs' => array('calories'), 
        'handler' => 'snack', 
        'unit' => 'kcal', 
        'decimals' => 0
    ),
    43 => array(
        'title' => 'میان‌وعده دوم', 
        'short' => '10 تا 15 درصد', 
        'category' => 'وعده‌بندی', 
        'color' => 'secondary', 
        'description' => 'برای افرادی که به دو میان‌وعده در طول روز نیاز دارند، این ابزار مقدار کالری مناسب میان‌وعده دوم را از مجموع کالری روزانه تعیین می‌کند.', 
        'formula' => 'Snack = 10٪ تا 15٪ × Daily calories', 
        'inputs' => array('calories'), 
        'handler' => 'snack', 
        'unit' => 'kcal', 
        'decimals' => 0
    ),
    // ==========================================
    // فرمول‌های جدید: درصد چربی، 1RM، ضربان قلب و هیدراتاسیون
    // ==========================================
    51 => array(
        'title' => 'درصد چربی بدن (تخمین)', 
        'short' => 'Body Fat %', 
        'category' => 'ترکیب بدن', 
        'color' => 'info', 
        'description' => 'این فرمول درصد چربی بدن را بر اساس BMI و سن تخمین می‌زند.<br>این روش برای ارزیابی اولیه مناسب است، اما برای دقت بیشتر، اندازه‌گیری مستقیم (با کالیپر یا BIA) توصیه می‌شود.', 
        'formula' => 'مردان: (1.20 × BMI) + (0.23 × سن) - 16.2<br>زنان: (1.20 × BMI) + (0.23 × سن) - 5.4', 
        'inputs' => array('gender', 'weight', 'height', 'age'), 
        'handler' => 'body_fat_estimate', 
        'unit' => '%', 
        'decimals' => 1
    ),
    53 => array(
        'title' => 'یک تکرار بیشینه (1RM)', 
        'short' => 'قدرت حداکثر', 
        'category' => 'ابزارهای تمرینی', 
        'color' => 'warning', 
        'description' => '1RM بیشترین وزنه‌ای است که می‌توانید یک تکرار کامل با آن انجام دهید.<br>این ابزار با استفاده از دو فرمول معتبر (Epley و Brzycki)، 1RM را بر اساس وزنه و تعداد تکرارهای انجام‌شده تخمین می‌زند.', 
        'formula' => 'Epley: W × (1 + R/30)<br>Brzycki: W × (36 ÷ (37 - R))', 
        'inputs' => array('weight', 'reps'), 
        'handler' => 'one_rm_combined', 
        'unit' => 'kg', 
        'decimals' => 1
    ),
    55 => array(
        'title' => 'حداکثر ضربان قلب', 
        'short' => 'Max HR', 
        'category' => 'ابزارهای تمرینی', 
        'color' => 'danger', 
        'description' => 'حداکثر ضربان قلب تخمینی، بیشترین تعداد ضربان قلب در دقیقه در زمان فعالیت شدید است.<br>این عدد برای تعیین محدوده‌های تمرینی قلبی–عروقی استفاده می‌شود.', 
        'formula' => '220 - سن', 
        'inputs' => array('age'), 
        'handler' => 'max_heart_rate', 
        'unit' => 'bpm', 
        'decimals' => 0
    ),
    56 => array(
        'title' => 'ضربان قلب هدف (Karvonen)', 
        'short' => 'Target HR', 
        'category' => 'ابزارهای تمرینی', 
        'color' => 'success', 
        'description' => 'روش Karvonen با استفاده از ضربان قلب استراحت و حداکثر ضربان قلب، محدوده ضربان قلب هدف را برای تمرین محاسبه می‌کند.<br>این روش برای تنظیم شدت تمرینات هوازی کاربردی است.', 
        'formula' => '((Max HR - HR استراحت) × شدت%) + HR استراحت', 
        'inputs' => array('age', 'resting_hr', 'intensity'), 
        'handler' => 'target_hr_karvonen', 
        'unit' => 'bpm', 
        'decimals' => 0
    ),
    57 => array(
        'title' => 'معادل کالری چربی', 
        'short' => 'محاسبه کات', 
        'category' => 'تغذیه', 
        'color' => 'warning', 
        'description' => 'از نظر انرژی، هر کیلوگرم چربی بدن تقریباً معادل 7700 کیلوکالری است.<br>این عدد برای برنامه‌ریزی کاهش وزن استفاده می‌شود (مثلاً برای کاهش 1 کیلوگرم در هفته، باید 1100 کالری کسری روزانه ایجاد کنید).', 
        'formula' => '1 کیلوگرم چربی = 7700 کیلوکالری', 
        'inputs' => array('fat_kg'), 
        'handler' => 'fat_calories_equiv', 
        'unit' => 'kcal', 
        'decimals' => 0
    ),
    58 => array(
        'title' => 'آب حین تمرین', 
        'short' => 'هیدراتاسیون', 
        'category' => 'هیدراتاسیون', 
        'color' => 'info', 
        'description' => 'در طول تمرین، بدن از طریق تعریق آب و الکترولیت از دست می‌دهد.<br>این ابزار مقدار آب موردنیاز حین تمرین را بر اساس مدت زمان و شدت فعالیت تخمین می‌زند.', 
        'formula' => 'آب پایه + (500 تا 1000 ml × ساعت تمرین)', 
        'inputs' => array('weight', 'duration'), 
        'handler' => 'water_during_workout', 
        'unit' => 'ml', 
        'decimals' => 0
    ),    
    // نکته: اگر نیاز به اضافه کردن آیتم‌های 44 تا 58 (ضرایب فعالیت، درصد چربی و ابزارهای تمرینی) دارید، می‌توانید با همین الگو آن‌ها را به انتهای آرایه اضافه کنید.
);
/*
|--------------------------------------------------------------------------
| محاسبات (استفاده از توابع هوشمند)
|--------------------------------------------------------------------------
*/
function calculate_formula($handler, $v)
{
    switch ($handler) {
        case 'bmi':
            $h = $v['height'] / 100;
            return $h > 0 ? $v['weight'] / ($h * $h) : 0;
            
        case 'mifflin': return calc_bmr($v);
        case 'harris':
            if ($v['gender'] === 'female') return 447.593 + (9.247 * $v['weight']) + (3.098 * $v['height']) - (4.330 * $v['age']);
            return 88.362 + (13.397 * $v['weight']) + (4.799 * $v['height']) - (5.677 * $v['age']);
            
        case 'katch':
            $lbm = $v['weight'] * (1 - ($v['body_fat'] / 100));
            return 370 + (21.6 * $lbm);
            
        case 'tdee_dynamic':
            return calc_tdee($v);

        case 'range_80_90': $tdee = calc_tdee($v); return array($tdee * .80, $tdee * .90);
        case 'range_70_80': $tdee = calc_tdee($v); return array($tdee * .70, $tdee * .80);
        case 'range_plus_250_400': $tdee = calc_tdee($v); return array($tdee + 250, $tdee + 400);
        case 'range_plus_500_700': $tdee = calc_tdee($v); return array($tdee + 500, $tdee + 700);
        case 'maintenance': return calc_tdee($v);

        case 'range_protein_18_22': return array($v['weight'] * 1.8, $v['weight'] * 2.2);
        case 'range_protein_16_22': return array($v['weight'] * 1.6, $v['weight'] * 2.2);
        case 'range_protein_18_24': return array($v['weight'] * 1.8, $v['weight'] * 2.4);
        case 'range_protein_12_16': return array($v['weight'] * 1.2, $v['weight'] * 1.6);
        case 'range_fat_08_10': return array($v['weight'] * .8, $v['weight']);
        case 'range_fat_keto': return array(($v['calories'] * .70) / 9, ($v['calories'] * .75) / 9);
        case 'carbs_remaining': return ($v['calories'] - ($v['protein'] * 4) - ($v['fat'] * 9)) / 4;
        
        case 'protein_kcal': return $v['protein'] * 4;
        case 'carb_kcal': return $v['carbs'] * 4;
        case 'fat_kcal': return $v['fat'] * 9;
        
        case 'protein_percent': return $v['calories'] > 0 ? (($v['protein'] * 4) / $v['calories']) * 100 : 0;
        case 'carb_percent': return $v['calories'] > 0 ? (($v['carbs'] * 4) / $v['calories']) * 100 : 0;
        case 'fat_percent': return $v['calories'] > 0 ? (($v['fat'] * 9) / $v['calories']) * 100 : 0;
        
        case 'devine':
            $inches = max(0, ($v['height'] - 152.4) / 2.54);
            return ($v['gender'] === 'female') ? 45.5 + (2.3 * $inches) : 50 + (2.3 * $inches);
            
        case 'weekly_loss': return array($v['weight'] * .005, $v['weight'] * .01);
        case 'water': return array($v['weight'] * 35, $v['weight'] * 45);
        case 'fiber': return ($v['calories'] / 1000) * 14;
        case 'meal_count': return '۳ تا ۶ وعده';
        case 'protein_per_meal': return $v['meals'] > 0 ? $v['protein'] / $v['meals'] : 0;
        case 'training_carbs': return array($v['carbs'] * .30, $v['carbs'] * .50);
        case 'breakfast': return array($v['calories'] * .20, $v['calories'] * .30);
        case 'lunch': return array($v['calories'] * .30, $v['calories'] * .40);
        case 'dinner': return array($v['calories'] * .25, $v['calories'] * .35);
        case 'snack': return array($v['calories'] * .10, $v['calories'] * .15);
        
        // ==========================================
        // موارد ترکیبی (بازگرداندن آرایه انجمنی برای تفسیر دقیق)
        // ==========================================
        case 'bmr_combined':
            $w = $v['weight']; $h = $v['height']; $a = $v['age']; $g = $v['gender'];
            $mifflin = ($g === 'female') ? (10 * $w) + (6.25 * $h) - (5 * $a) - 161 : (10 * $w) + (6.25 * $h) - (5 * $a) + 5;
            $harris = ($g === 'female') ? 447.593 + (9.247 * $w) + (3.098 * $h) - (4.330 * $a) : 88.362 + (13.397 * $w) + (4.799 * $h) - (5.677 * $a);
            return array('mifflin' => $mifflin, 'harris' => $harris);

        case 'katch_athlete':
            $h_m = $v['height'] / 100;
            $bmi = $v['weight'] / ($h_m * $h_m);
            $bf = ($v['gender'] === 'female') ? (1.20 * $bmi) + (0.23 * $v['age']) - 5.4 : (1.20 * $bmi) + (0.23 * $v['age']) - 16.2;
            $bf = max(3, min($bf, 60));
            $lbm = $v['weight'] * (1 - ($bf / 100));
            $bmr = 370 + (21.6 * $lbm);
            return array('body_fat' => $bf, 'lbm' => $lbm, 'bmr' => $bmr);

        case 'body_fat_estimate':
            $h_m = $v['height'] / 100;
            $bmi = $v['weight'] / ($h_m * $h_m);
            $bf = ($v['gender'] === 'female') ? (1.20 * $bmi) + (0.23 * $v['age']) - 5.4 : (1.20 * $bmi) + (0.23 * $v['age']) - 16.2;
            return max(3, min($bf, 60));

        case 'one_rm_combined':
            $w = $v['weight']; $r = $v['reps'];
            $epley = $w * (1 + ($r / 30));
            $brzycki = $w * (36 / (37 - $r));
            return array('epley' => $epley, 'brzycki' => $brzycki, 'avg' => ($epley + $brzycki) / 2);

        case 'max_heart_rate':
            return 220 - $v['age'];

        case 'target_hr_karvonen':
            $max_hr = 220 - $v['age'];
            return (($max_hr - $v['resting_hr']) * ($v['intensity'] / 100)) + $v['resting_hr'];

        case 'fat_calories_equiv':
            return $v['fat_kg'] * 7700;

        case 'water_during_workout':
            $base = $v['weight'] * 30;
            $min = $base + (500 * $v['duration']);
            $max = $base + (1000 * $v['duration']);
            return array($min, $max);
    }
    return null;
}
/*
|--------------------------------------------------------------------------
| تفسیر نتایج فرمول
|--------------------------------------------------------------------------
*/

function generate_interpretation($type, $values, $result) {
    $txt = "";
    // کمکی برای استخراج مقدار از آرایه یا عدد ساده
    $val = function($r, $key = null) {
        if ($key && is_array($r)) return $r[$key];
        if (is_array($r) && isset($r[0])) return $r[0]; // برای بازه‌ها، مقدار اول را بگیر
        return $r;
    };

    switch ($type) {
        case 1:
            $bmi = $val($result);
            $cat = ($bmi < 18.5) ? "کم‌وزن" : (($bmi < 25) ? "محدوده وزن معمول" : (($bmi < 30) ? "اضافه‌وزن" : "چاقی"));
            $txt = "BMI شما " . fa_number($bmi, 1) . " است و در محدوده <strong>{$cat}</strong> قرار می‌گیرد. این شاخص یک ارزیابی اولیه است و میزان عضله و چربی بدن را به‌طور مستقیم اندازه‌گیری نمی‌کند.";
            break;
        case 2:
            $txt = "انرژی پایه بدن شما بر اساس فرمول Mifflin-St Jeor حدود <strong>" . fa_number($val($result, 'mifflin')) . "</strong> و بر اساس Harris-Benedict حدود <strong>" . fa_number($val($result, 'harris')) . "</strong> کیلوکالری در روز تخمین زده می‌شود. نیاز واقعی روزانه شما با درنظرگرفتن فعالیت بدنی بیشتر از این مقدار خواهد بود.";
            break;
        case 6:
            $txt = "بر اساس توده بدون چربی بدن، انرژی پایه شما حدود <strong>" . fa_number($val($result, 'bmr')) . "</strong> کیلوکالری در روز تخمین زده می‌شود. (درصد چربی تخمینی: " . fa_number($val($result, 'body_fat'), 1) . "٪)";
            break;
        case 7:
            $txt = "برای حفظ وزن فعلی، با سطح فعالیت انتخاب‌شده، نیاز انرژی روزانه شما تقریباً <strong>" . fa_number($val($result)) . "</strong> کیلوکالری است.";
            break;
        case 12:
            $txt = "برای کاهش وزن تدریجی، کالری هدف روزانه شما حدود <strong>" . fa_number($val($result, 0)) . " تا " . fa_number($val($result, 1)) . "</strong> کیلوکالری تعیین شده است. هدف، ایجاد کسری انرژی قابل مدیریت و حفظ عملکرد و توده عضلانی است.";
            break;
        case 13:
            $txt = "کالری هدف شما برای کاهش وزن سریع‌تر حدود <strong>" . fa_number($val($result, 0)) . " تا " . fa_number($val($result, 1)) . "</strong> کیلوکالری است. استفاده از کسری شدید کالری باید با توجه به شرایط فردی و وضعیت تغذیه‌ای انجام شود.";
            break;
        case 14:
            $txt = "برای افزایش حجم کنترل‌شده، کالری هدف روزانه شما حدود <strong>" . fa_number($val($result, 0)) . " تا " . fa_number($val($result, 1)) . "</strong> کیلوکالری است.";
            break;
        case 15:
            $txt = "مازاد انرژی بیشتر می‌تواند سرعت افزایش وزن را بالا ببرد، اما احتمال افزایش چربی نیز بیشتر می‌شود. کالری هدف پیشنهادی: <strong>" . fa_number($val($result, 0)) . " تا " . fa_number($val($result, 1)) . "</strong> کیلوکالری.";
            break;
        case 16:
            $txt = "برای حفظ تقریبی وزن فعلی، نیاز انرژی روزانه شما حدود <strong>" . fa_number($val($result)) . "</strong> کیلوکالری است.";
            break;
        case 17: case 18: case 19: case 20:
            $txt = "نیاز تخمینی پروتئین روزانه شما حدود <strong>" . fa_number($val($result, 0)) . " تا " . fa_number($val($result, 1)) . "</strong> گرم است. دریافت کافی پروتئین برای حمایت از رشد، ریکاوری و حفظ توده عضلانی اهمیت حیاتی دارد.";
            break;
        case 21:
            $txt = "مقدار چربی پیشنهادی در این محاسبه حدود <strong>" . fa_number($val($result, 0)) . " تا " . fa_number($val($result, 1)) . "</strong> گرم در روز است.";
            break;
        case 22:
            $txt = "در رژیم کتوژنیک، سهم کربوهیدرات بسیار پایین‌تر از الگوی غذایی معمول است. چربی پیشنهادی: <strong>" . fa_number($val($result, 0)) . " تا " . fa_number($val($result, 1)) . "</strong> گرم.";
            break;
        case 23:
            $txt = "مقدار کربوهیدرات روزانه شما حدود <strong>" . fa_number($val($result)) . "</strong> گرم محاسبه شده است.";
            break;
        case 24: case 25: case 26:
            $macro = ($type == 24) ? "پروتئین (۴ کالری/گرم)" : (($type == 25) ? "کربوهیدرات (۴ کالری/گرم)" : "چربی (۹ کالری/گرم)");
            $txt = "کالری حاصل از {$macro}: <strong>" . fa_number($val($result)) . "</strong> کیلوکالری.";
            break;
        case 27: case 28: case 29:
            $name = ($type == 27) ? "پروتئین" : (($type == 28) ? "کربوهیدرات" : "چربی");
            $txt = "حدود <strong>" . fa_number($val($result), 1) . "٪</strong> از انرژی رژیم شما از {$name} تأمین می‌شود.";
            break;
        case 30: case 31:
            $txt = "وزن تخمینی شما حدود <strong>" . fa_number($val($result), 1) . "</strong> کیلوگرم است. این عدد یک تخمین است و نباید به‌عنوان وزن ایده‌آل قطعی در نظر گرفته شود. ترکیب بدن و شرایط فردی نیز باید ارزیابی شود.";
            break;
        case 32:
            $txt = "کاهش وزن بهتر است تدریجی باشد. کاهش پیشنهادی: <strong>" . fa_number($val($result, 0), 2) . " تا " . fa_number($val($result, 1), 2) . "</strong> کیلوگرم در هفته. حفظ عضله و دریافت کافی مواد مغذی اهمیت دارد.";
            break;
        case 33:
            $txt = "نیاز تقریبی مایعات شما <strong>" . fa_number($val($result, 0)/1000, 1) . " تا " . fa_number($val($result, 1)/1000, 1) . "</strong> لیتر در روز است. در هوای گرم یا تعریق زیاد، این نیاز افزایش می‌یابد.";
            break;
        case 35:
            $txt = "برای توزیع بهتر پروتئین، مقدار تقریبی <strong>" . fa_number($val($result), 1) . "</strong> گرم پروتئین برای هر وعده در نظر گرفته شده است.";
            break;
        case 36:
            $txt = "توزیع کربوهیدرات در اطراف تمرین می‌تواند به تأمین انرژی و ریکاوری کمک کند. مقدار پیشنهادی: <strong>" . fa_number($val($result, 0)) . " تا " . fa_number($val($result, 1)) . "</strong> گرم.";
            break;
        case 39: case 40: case 41: case 42: case 43:
            $meal = ($type==39)?"صبحانه":(($type==40)?"ناهار":(($type==41)?"شام":(($type==42)?"میان‌وعده اول":"میان‌وعده دوم")));
            $txt = "حدود <strong>" . fa_number($val($result, 0)) . " تا " . fa_number($val($result, 1)) . "</strong> کیلوکالری برای {$meal} در نظر گرفته شده است.";
            break;
        case 51:
            $txt = "تخمین درصد چربی بدن شما <strong>" . fa_number($val($result), 1) . "٪</strong> است. این شاخص نسبت چربی به وزن کل بدن را نشان می‌دهد و برای بررسی تغییرات ترکیب بدن کاربرد دارد.";
            break;
        case 53:
            $txt = "حداکثر وزنه یک‌تکراری تخمینی شما (میانگین دو فرمول) حدود <strong>" . fa_number($val($result, 'avg'), 1) . "</strong> کیلوگرم است. این مقدار تخمینی است و با تکنیک و خستگی تغییر می‌کند.";
            break;
        case 55:
            $txt = "حداکثر ضربان قلب تخمینی شما حدود <strong>" . fa_number($val($result)) . "</strong> ضربه در دقیقه است. این عدد تخمینی است و ممکن است با مقدار واقعی تفاوت داشته باشد.";
            break;
        case 56:
            $txt = "برای شدت تمرینی انتخاب‌شده، محدوده ضربان قلب هدف شما حدود <strong>" . fa_number($val($result)) . "</strong> ضربه در دقیقه است.";
            break;
        case 57:
            $txt = "هر کیلوگرم چربی بدن تقریباً معادل ۷۷۰۰ کیلوکالری است. انرژی معادل چربی واردشده: <strong>" . fa_number($val($result)) . "</strong> کیلوکالری. این فقط معادل انرژی غذایی است.";
            break;
        case 58:
            $txt = "میزان آب موردنیاز حین تمرین به مدت و شدت فعالیت بستگی دارد. نیاز تخمینی: <strong>" . fa_number($val($result, 0)) . " تا " . fa_number($val($result, 1)) . "</strong> میلی‌لیتر. در تمرین‌های طولانی به الکترولیت‌ها نیز توجه کنید.";
            break;
        default:
            $txt = "محاسبه با موفقیت انجام شد. برای تفسیر دقیق‌تر با متخصص تغذیه مشورت کنید.";
    }
    return $txt;
}
/*
|--------------------------------------------------------------------------
| تبدیل اعداد و توابع کمکی
|--------------------------------------------------------------------------
*/
function fa_number($number, $decimals = 0) {
    if (is_string($number)) return $number;
    $text = number_format((float)$number, $decimals, '.', ',');
    return strtr($text, array('0'=>'۰','1'=>'۱','2'=>'۲','3'=>'۳','4'=>'۴','5'=>'۵','6'=>'۶','7'=>'۷','8'=>'۸','9'=>'۹'));
}
function h($text) { 
    return strip_tags($text, '<br><br/><b><i><u><strong><em><span>');
}

$selected = isset($formulas[$type]) ? $formulas[$type] : null;
$values = array();
$result = null;
$error = '';

/*
|--------------------------------------------------------------------------
| دریافت و اعتبارسنجی فرم
|--------------------------------------------------------------------------
*/
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $selected) {
    foreach ($selected['inputs'] as $input) {
        if ($input === 'gender') {
            $values['gender'] = isset($_POST['gender']) && $_POST['gender'] === 'female' ? 'female' : 'male';
            continue;
        }
        if ($input === 'activity') {
            $raw = isset($_POST['activity']) ? trim($_POST['activity']) : '';
            if ($raw === '' || !is_numeric($raw)) {
                $error = 'لطفاً سطح فعالیت بدنی را انتخاب کنید.';
                break;
            }
            $values['activity'] = (float)$raw;
            continue;
        }
        if ($input === 'meals') {
            $raw = isset($_POST['meals']) ? trim($_POST['meals']) : '';
            if ($raw === '' || !is_numeric($raw)) { $error = 'لطفاً تعداد وعده‌ها را وارد کنید.'; break; }
            $values['meals'] = (float)$raw;
            continue;
        }
        
        $raw = isset($_POST[$input]) ? str_replace(',', '.', trim($_POST[$input])) : '';
        if ($raw === '' || !is_numeric($raw)) {
            $error = 'لطفاً همه مقادیر موردنیاز را وارد کنید.';
            break;
        }
        $values[$input] = (float)$raw;
    }

    if ($error === '') {
        if (isset($values['weight']) && ($values['weight'] <= 0 || $values['weight'] > 500)) $error = 'وزن واردشده معتبر نیست.';
        if (isset($values['height']) && ($values['height'] <= 0 || $values['height'] > 300)) $error = 'قد واردشده معتبر نیست.';
        if (isset($values['age']) && ($values['age'] <= 0 || $values['age'] > 120)) $error = 'سن واردشده معتبر نیست.';
        if (isset($values['body_fat']) && ($values['body_fat'] < 0 || $values['body_fat'] > 70)) $error = 'درصد چربی بدن باید بین 0 تا 70 باشد.';
        if (isset($values['calories']) && $values['calories'] <= 0) $error = 'کالری باید بیشتر از صفر باشد.';
        if (isset($values['meals']) && ($values['meals'] <= 0 || $values['meals'] > 20)) $error = 'تعداد وعده‌ها معتبر نیست.';
        if (isset($values['activity']) && !in_array($values['activity'], array(1.2, 1.375, 1.55, 1.725, 1.9))) $error = 'سطح فعالیت معتبر نیست.';

        if (isset($values['reps']) && ($values['reps'] <= 0 || $values['reps'] > 30)) 
            $error = 'تعداد تکرار باید بین 1 تا 30 باشد.';

        if (isset($values['resting_hr']) && ($values['resting_hr'] < 30 || $values['resting_hr'] > 120)) 
            $error = 'ضربان قلب استراحت معتبر نیست.';

        if (isset($values['intensity']) && ($values['intensity'] < 10 || $values['intensity'] > 100)) 
            $error = 'شدت تمرین باید بین 10 تا 100 درصد باشد.';

        if (isset($values['duration']) && $values['duration'] <= 0) 
            $error = 'مدت تمرین باید بیشتر از صفر باشد.';

        
        if ($selected['handler'] === 'carbs_remaining') {
            $remaining = $values['calories'] - ($values['protein'] * 4) - ($values['fat'] * 9);
            if ($remaining < 0) $error = 'کالری پروتئین و چربی از کل کالری بیشتر است.';
        }
    }
    if ($error === '') {
        $result = calculate_formula($selected['handler'], $values);
    }
}

$tetr2 = $selected ? h($selected['title']) . ' | ' : '';
$title_main = $tetr2 . $title_main;
if ($selected) {
    // حذف HTML و آماده‌سازی برای متا
    $clean_desc = strip_tags($selected['description']);
    $meta_dsc = mb_substr($clean_desc, 0, 160);
    
    // برای $meta هم فقط متن ساده بگذارید (بدون HTML)
    $meta = $title_main . ', ' . $selected['category'];
}

include('top.php');
?>

<style>
/* ۱. اصلاح کانتینر اصلی برای جلوگیری از هل داده شدن کل صفحه به چپ */
.calculator-page {
    width: 100%;
    max-width: 100vw;
    overflow-x: hidden;
    padding-left: 15px;
    padding-right: 15px;
    box-sizing: border-box;
}

/* ۲. جلوگیری از بیرون‌زدگی جعبه فرمول و نتایج در صفحات کوچک */
.formula-box, .result-box {
    max-width: 100%;
    word-wrap: break-word;
    overflow-wrap: break-word;
}
.formula-code {
    word-break: break-word;
    white-space: pre-wrap; /* اجازه شکستن خطوط طولانی فرمول */
    direction:ltr;
}
.calculator-title{
    font-weight:700;
}

.formula-box{
    background:#f8f9fa;
    border:1px solid #e9ecef;
    border-radius:10px;
    padding:14px 16px;
}

.formula-code{
    direction:rtl;
    text-align:left;
    unicode-bidi:embed;
    font-family:Arial, sans-serif;
    font-size:15px;
    line-height:2;
}

.formula-item{
    margin-bottom:10px;
}

.formula-item .btn{
    min-height:58px;
    text-align:right;
    white-space:normal;
    border-radius:10px;
    transition:all .2s ease;
}

.formula-item .btn:hover{
    transform:translateY(-2px);
    box-shadow:0 5px 14px rgba(0,0,0,.12);
}

.formula-number{
    display:inline-block;
    width:34px;
    height:34px;
    line-height:34px;
    text-align:center;
    border-radius:50%;
    background:rgba(255,255,255,.22);
    margin-left:8px;
    font-weight:bold;
}

.result-box{
    border-radius:12px;
}

.result-value{
    font-size:30px;
    font-weight:700;
    direction:ltr;
    text-align:center;
    unicode-bidi:embed;
}

.gender-box{
    display:flex;
    gap:10px;
}

.gender-box label{
    flex:1;
    margin:0;
    cursor:pointer;
}

.gender-box input{
    display:none;
}

.gender-box span{
    display:block;
    border:1px solid #dee2e6;
    border-radius:8px;
    padding:11px;
    text-align:center;
    background:#fff;
}

.gender-box input:checked + span{
    background:#007bff;
    border-color:#007bff;
    color:#fff;
}

/* ۳. چیدمان بهینه و بدون باگ دکمه‌ها در موبایل */
@media (max-width: 768px) {
    #calculatorMenu .card-body {
        display: flex;
        flex-direction: column; /* چیدمان ستونی (زیر هم) برای حذف کامل اسکرول افقی */
        gap: 8px;
        padding: 12px;
    }

    #calculatorMenu .card-body .btn {
        width: 100% !important; /* دکمه تمام‌عرض برای لمس راحت‌تر در موبایل */
        display: block !important; /* غلبه بر inline-block پیش‌فرض بوت‌استرپ */
        white-space: normal !important; /* شکستن متن‌های خیلی طولانی */
        word-wrap: break-word;
        text-align: right !important;
        margin: 0 !important; /* خنثی کردن ml-1 و mb-2 که در لبه‌ها باعث بیرون‌زدگی می‌شدند */
        padding: 10px 14px;
        font-size: 14px;
        line-height: 1.5;
        box-sizing: border-box;
    }
}
</style>
<main>


<div class="calculator-page">
<div class="container">
    <div class="card mt-4 mb-4">
        <div class="card-header p-0">
            <button type="button" class="btn btn-light btn-block text-right d-flex align-items-center justify-content-between" data-toggle="collapse" data-target="#calculatorMenu" aria-expanded="<?php echo $has_type ? 'false' : 'true'; ?>" aria-controls="calculatorMenu" style="border:0; border-radius:0; padding:12px 15px; font-weight:bold;">
                <span><i class="fas fa-calculator ml-2"></i>فهرست ابزارهای محاسباتی</span>
                <i class="fas <?php echo $has_type ? 'fa-chevron-down' : 'fa-chevron-up'; ?>" id="calculatorMenuArrow"></i>
            </button>
        </div>
        <div id="calculatorMenu" class="collapse <?php echo $has_type ? '' : 'show'; ?>">
            <div class="card-body">
                <?php foreach ($formulas as $id => $item){?>
                <a href="/calculator/<?php echo $id; ?>" class="btn <?php echo $item['color']; ?> btn-sm mb-2 ml-1"><?php echo htmlspecialchars($item['title']); ?></a>
                <?php } ?>
            </div>
        </div>
    </div>
    <script>
    $(document).ready(function () {
        $('#calculatorMenu').on('show.bs.collapse', function () { $('#calculatorMenuArrow').removeClass('fa-chevron-down').addClass('fa-chevron-up'); });
        $('#calculatorMenu').on('hide.bs.collapse', function () { $('#calculatorMenuArrow').removeClass('fa-chevron-up').addClass('fa-chevron-down'); });
    });
    </script>

    <?php if ($selected) { ?>
    <div class="card calculator-card">
        <div class="card-body p-3 p-md-4">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <div class="text-muted small mb-1">فرمول شماره <?php echo $type; ?></div>
                    <h2 class="h4 calculator-title mb-0"><?php echo h($selected['title']); ?></h2>
                </div>
                <a href="#" class="btn btn-outline-secondary btn-sm" onclick="$('#calculatorMenu').collapse('show');">همه فرمول‌ها</a>
            </div>
            <p class="text-muted"><?php echo h($selected['description']); ?></p>
            <div class="formula-box mb-4 d-none">
                <div class="font-weight-bold mb-2">فرمول:</div>
                <div class="formula-code"><?php echo h($selected['formula']); ?></div>
            </div>

            <?php if ($error !== '') { ?> <div class="alert alert-danger"><?php echo h($error); ?></div> <?php } ?>

            <form method="post">
                <?php foreach ($selected['inputs'] as $input) { ?>
                    <?php if ($input === 'gender') { ?>
                        <div class="form-group">
                            <label class="font-weight-bold d-block">جنسیت</label>
                            <div class="gender-box">
                                <label><input type="radio" name="gender" value="male" <?php echo (!isset($values['gender']) || $values['gender'] === 'male') ? 'checked' : ''; ?>><span>مرد</span></label>
                                <label><input type="radio" name="gender" value="female" <?php echo (isset($values['gender']) && $values['gender'] === 'female') ? 'checked' : ''; ?>><span>زن</span></label>
                            </div>
                        </div>
                    <?php } elseif ($input === 'activity') { ?>
                        <!-- فیلد جدید سطح فعالیت -->
                        <div class="form-group">
                            <label class="font-weight-bold">سطح فعالیت بدنی</label>
                            <select name="activity" class="form-control" required>
                                <option value="1.2" <?php echo (isset($values['activity']) && $values['activity'] == 1.2) ? 'selected' : ''; ?>>کم‌تحرک (ضریب 1.2)</option>
                                <option value="1.375" <?php echo (isset($values['activity']) && $values['activity'] == 1.375) ? 'selected' : ''; ?>>فعالیت سبک (ضریب 1.375)</option>
                                <option value="1.55" <?php echo (isset($values['activity']) && $values['activity'] == 1.55) ? 'selected' : ''; ?>>فعالیت متوسط (ضریب 1.55)</option>
                                <option value="1.725" <?php echo (isset($values['activity']) && $values['activity'] == 1.725) ? 'selected' : ''; ?>>فعالیت زیاد (ضریب 1.725)</option>
                                <option value="1.9" <?php echo (isset($values['activity']) && $values['activity'] == 1.9) ? 'selected' : ''; ?>>ورزشکار حرفه‌ای / فعالیت بسیار زیاد (ضریب 1.9)</option>
                            </select>
                        </div>
                    <?php } elseif ($input === 'meals') { ?>
                        <div class="form-group">
                            <label class="font-weight-bold">تعداد وعده‌ها</label>
                            <input type="number" name="meals" class="form-control" min="1" max="20" step="1" value="<?php echo isset($values['meals']) ? h($values['meals']) : ''; ?>" required>
                        </div>
                    <?php } else { ?>
                        <?php $info = $input_info[$input]; $value = isset($values[$input]) ? $values[$input] : ''; ?>
                        <div class="form-group">
                            <label class="font-weight-bold"><?php echo h($info['title']); ?></label>
                            <div class="input-group">
                                <input type="number" name="<?php echo h($input); ?>" class="form-control" value="<?php echo h($value); ?>" step="<?php echo h($info['step']); ?>" min="0" required>
                                <div class="input-group-append"><span class="input-group-text"><?php echo h($info['unit']); ?></span></div>
                            </div>
                        </div>
                    <?php } ?>
                <?php } ?>

                <?php if (count($selected['inputs']) > 0) { ?>
                    <button type="submit" class="btn btn-primary btn-lg btn-block">محاسبه</button>
                <?php } else { ?>
                    <div class="alert alert-info mb-0">این مورد یک مقدار ثابت یا یک قاعده توضیحی است و ورودی از کاربر نیاز ندارد.</div>
                <?php } ?>
            </form>

            <?php if ($result !== null) { ?>
            <!-- باکس نتیجه محاسبه -->
            <div class="result-box bg-light border mt-4 p-4">
                <div class="text-center text-muted mb-2">نتیجه محاسبه</div>
                <div class="result-value" style="direction:rtl;">
                    <?php
                    // مدیریت نمایش نتایج ترکیبی (آرایه انجمنی)
                    if (is_array($result) && isset($result['mifflin'])) {
                        echo "Mifflin: <strong>" . fa_number(round($result['mifflin'])) . "</strong><br>";
                        echo "Harris: <strong>" . fa_number(round($result['harris'])) . "</strong>";
                    } elseif (is_array($result) && isset($result['epley'])) {
                        echo "Epley: <strong>" . fa_number($result['epley'], 1) . "</strong><br>";
                        echo "Brzycki: <strong>" . fa_number($result['brzycki'], 1) . "</strong><br>";
                        echo "میانگین: <strong>" . fa_number($result['avg'], 1) . "</strong>";
                    } elseif (is_array($result) && isset($result['bmr'])) {
                        echo "چربی تخمینی: <strong>" . fa_number($result['body_fat'], 1) . "٪</strong><br>";
                        echo "توده بدون چربی: <strong>" . fa_number($result['lbm'], 1) . " kg</strong><br>";
                        echo "BMR: <strong>" . fa_number($result['bmr']) . "</strong>";
                    } 
                    // مدیریت نمایش نتایج بازه‌ای (آرایه عددی)
                    elseif (is_array($result) && isset($result[0])) {
                        echo fa_number($result[0], $selected['decimals']);
                        echo ' تا ';
                        echo fa_number($result[1], $selected['decimals']);
                    } 
                    // مدیریت نمایش نتایج تک‌مقداری
                    else {
                        echo fa_number($result, $selected['decimals']);
                    }
                    ?>
                    <?php if ($selected['unit'] !== '') { ?> <small class="d-block text-muted mt-2" style="font-size:14px;direction:ltr;"><?php echo h($selected['unit']); ?></small> <?php } ?>
                </div>
            </div>

            <!-- باکس جدید: تحلیل و تفسیر نتایج -->
            <div class="alert alert-info mt-4 mb-0" style="border-right: 4px solid #17a2b8; background-color: #f8f9fa;">
                <h6 class="font-weight-bold mb-2" style="color: #0c5460;">
                    <i class="fas fa-chart-line ml-1"></i> تحلیل و تفسیر نتیجه
                </h6>
                <p class="mb-0" style="line-height: 1.8; color: #333;">
                    <?php echo generate_interpretation($type, $values, $result); ?>
                </p>
            </div>
            <?php } ?>
            
            <?php if ($type === 1 && $result !== null) { ?>
            <div class="alert alert-info mt-3 mb-0">
                <?php
                if ($result < 18.5) echo 'ارزیابی BMI: کم‌وزن';
                elseif ($result < 25) echo 'ارزیابی BMI: محدوده طبیعی';
                elseif ($result < 30) echo 'ارزیابی BMI: اضافه‌وزن';
                elseif ($result < 35) echo 'ارزیابی BMI: چاقی درجه ۱';
                elseif ($result < 40) echo 'ارزیابی BMI: چاقی درجه ۲';
                else echo 'ارزیابی BMI: چاقی درجه ۳';
                ?>
            </div>
            <?php } ?>

            <div class="mt-4 pt-3 border-top text-muted small" style="line-height:2">
                این ابزارها برای برآورد و آموزش عمومی طراحی شده‌اند و جایگزین ارزیابی تخصصی پزشک یا متخصص تغذیه نیستند.
            </div>
        </div>
    </div>
    <?php } ?>

    <script type="application/ld+json">
    <?php
    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'WebPage',
        '@id' => $schema_url . '#webpage',
        'url' => $schema_url,
        'name' => $type > 0 ? ($formulas[$type]['title'] ?? $title_main) : 'محاسبه‌گرهای ورزشی و تغذیه‌ای ' . $title_main,
        'description' => 'مجموعه محاسبه‌گرها و ابزارهای رایگان وی کوچ برای محاسبه شاخص توده بدنی، متابولیسم پایه، کالری مورد نیاز و...',
        'inLanguage' => 'fa-IR',
        'isPartOf' => ['@type' => 'WebSite', '@id' => "$MainUrl#website", 'url' => "$MainUrl", 'name' => "$title_main"],
        'publisher' => ['@type' => 'Organization', 'name' => "$title_main", 'url' => "$MainUrl"]
    ];
    echo json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    ?>
    </script>
</div>
</div>
</main>

<?php include('bottom.php');?>