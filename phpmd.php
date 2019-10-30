<?php

/**
 * Проверка кода с помощью PHP Mess Detector (phpmd)
 *
 * Данный скрипт исключает сообщения, которые для данного
 * проекта не являются ошибками.
 *
 * Такой способ существенно проще, чем добавлять исключения через
 * phpmd.xml файл
 *
 * @author Alexandr Gorlov
 */

// Сообщения, которые не являются ошибкой для данного проекта
// типичный список для проекта на основе agorlov/lipid
$skipMessages = [
    '__construct accesses the super-global variable $_SERVER',
    '__construct accesses the super-global variable $_POST.',
    '__construct accesses the super-global variable $_GET.',
    'The parameter $POST is not named in camelCase.',
    'The variable $POST is not named in camelCase.',
    'The variable $GET is not named in camelCase.',
    'Avoid variables with short names like $id.',
    'Avoid variables with short names like $db.',
    'Avoid variables with short names like $e.',
    'Avoid variables with short names like $ex.',
    'uses an else expression. Else clauses are basically not necessary',
];


$cmd = "vendor/bin/phpmd . text cleancode,codesize,controversial,design,naming " .
            "--exclude 'vendor,example,tests/_support,adminer'";

$output = [];
$retVal = null;

exec($cmd, $output, $retVal);

if ($retVal === 0) {
    echo "Ошибок не найдено.\n";
    exit; // ошибок не найдено
}

$error = 0;

foreach ($output as $str) {
    if (isMessageOk($str, $skipMessages)) {
        continue;
    }
    $error++;
    echo "$str\n";
}

if ($error > 0) {
    exit(1);
}

echo "Ошибок не найдено.\n";
exit(0);

/**
 * Проверяем, входит ли сообщение в список разрешенных
 *
 * список разрешенных, сообщения, которые не являются ошибкой для
 * данного проекта
 *
 * @param $str
 * @param $messages
 * @return boolean
 */
function isMessageOk(string $str, array $messages): bool
{
    foreach ($messages as $msg) {
        if (strpos($str, $msg) !== false) {
            return true;
        }
    }
    return false;
}
