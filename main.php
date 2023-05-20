<!DOCTYPE html>
<html>
<head>
    <title>Пример программы на PHP</title>
    <meta charset="utf-8">
</head>
<body>
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <label for="string1">Строка 1:</label>
    <input type="text" name="string1"><br>
    <label for="string2">Строка 2:</label>
    <input type="text" name="string2"><br>
    <label for="string3">Строка 3:</label>
    <input type="text" name="string3"><br>
    <input type="submit" value="Отправить">
</form>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $string1 = $_POST['string1'];
    $string2 = $_POST['string2'];
    $string3 = $_POST['string3'];

    // Получаем уникальные символы из каждой строки
    $uniqueChars1 = array_unique(preg_split('//u', $string1, -1, PREG_SPLIT_NO_EMPTY));
    $uniqueChars2 = array_unique(preg_split('//u', $string2, -1, PREG_SPLIT_NO_EMPTY));
    $uniqueChars3 = array_unique(preg_split('//u', $string3, -1, PREG_SPLIT_NO_EMPTY));

    $combinedString = $string1 . $string2 . $string3; // Объединяем все строки в одну

// Генерируем уникальные цифры для каждого символа
    $uniqueChars = array_values(array_unique(str_split($combinedString))); // Получаем уникальные символы из строки

    $digits = array_fill_keys($uniqueChars, null); // Массив для сопоставления символов с цифрами
    $numbers = [];
    generateDigitMap($digits, $uniqueChars, 0, $string1, $string2, $string3);
}
function generateDigitMap(&$digits, $uniqueChars, $currentIndex, $string1, $string2, $string3) {
    if ($currentIndex === count($uniqueChars)) {
        // Все символы были заменены, выполняем проверку
        checkEquation($string1, $string2, $string3, $digits);
        return;
    }

    $char = $uniqueChars[$currentIndex];
    for ($digit = 0; $digit <= 9; $digit++) {
        if (!in_array($digit, $digits, true)) {
            $digits[$char] = $digit;
            generateDigitMap($digits, $uniqueChars, $currentIndex + 1, $string1, $string2, $string3);
            $digits[$char] = null; // Возвращаем символу значение null для следующей комбинации
        }
    }
}

function checkEquation($string1, $string2, $string3, $digits) {
    global $numbers;

    $number1 = replaceCharsWithDigits($string1, $digits);
    $number2 = replaceCharsWithDigits($string2, $digits);
    $number3 = replaceCharsWithDigits($string3, $digits);

    if ($number1 + $number2 === $number3) {
        $numbers[] = [$number1, $number2, $number3];
    }
}

function replaceCharsWithDigits($string, $digits) {
    $number = '';
    foreach (str_split($string) as $char) {
        $number .= $digits[$char];
    }
    return (int) $number;
}

// Выводим результаты
echo "String 1: $string1<br>";
echo "String 2: $string2<br>";
echo "String 3: $string3<br>";
echo "<br>";

if (empty($numbers)) {
    if (count($uniqueChars) > 10){
        echo "Число различных символов больше 10";
    }
    else{
        echo "Нет комбинаций, при которых сумма первых двух чисел равна третьему числу.";
    }

} else {
    echo "Сумма первых двух чисел равна третьему числу для следующих комбинаций:<br>";
    foreach ($numbers as $combination) {
        $number1 = $combination[0];
        $number2 = $combination[1];
        $number3 = $combination[2];
        echo "$number1 + $number2 = $number3<br>";
    }
}
?>
</body>
</html>