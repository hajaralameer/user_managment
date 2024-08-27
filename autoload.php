<?php

// تعريف الدالة autoload لتحميل الملفات المطلوبة تلقائيًا
spl_autoload_register(function ($class) {
    // تحديد المجلد الذي يحتوي على الملفات
    $base_dir = __DIR__ . '/src/';

    // استبدال namespace بالفاصلة المائلة الأمامية
    $file = $base_dir . str_replace('\\', '/', $class) . '.php';

    // التحقق من وجود الملف
    if (file_exists($file)) {
        require $file;
    }
});

// تضمين مكتبة PHPMailer يدويًا
require_once 'src/PHPMailer.php';
require_once 'src/SMTP.php';
require_once 'src/Exception.php';

?>
