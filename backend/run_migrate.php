<?php
$output = [];
exec('C:\laragon\bin\php\php-8.3.30-Win32-vs16-x64\php.exe artisan migrate 2>&1', $output);
file_put_contents('migrate_out.txt', implode("\n", $output));
echo "Migrate command executed and output saved.";
