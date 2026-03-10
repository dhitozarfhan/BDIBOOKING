<?php
$ini_path = 'C:\laragon\bin\php\php-8.3.30-Win32-vs16-x64\php.ini';
file_put_contents($ini_path, "\nextension=pdo_pgsql\nextension=pgsql\n", FILE_APPEND);
echo "Extensions appended successfully.\n";
