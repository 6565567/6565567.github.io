<?php

require 'autoload.php';

$input = $_POST['input'] ?? $_COOKIE['input'] ?? '';
$inputFormat = $_POST['input_format'] ?? $_COOKIE['input_format'] ?? 'tsv';
$outputFormat = $_POST['output_format'] ?? $_COOKIE['output_format'] ?? 'json';

$result = '';

function decodeTable(string $input, string $separator): array {
    $rows = [];
    $lines = explode("\n", trim($input));
    $headers = array_map(
            'trim',
            explode($separator, array_shift($lines))
    );

    foreach ($lines as $line) {
        $values = array_map(
                'trim',
                explode($separator, $line)
        );
        $rows[] = array_combine($headers, $values);
    }
    return $rows;
}

function encodeTable(array $rows, string $separator): string {
    if (!$rows) {
        return '';
    }
    $output = '';
    $headers = array_keys($rows[0]);
    $output .= implode($separator, $headers).PHP_EOL;
    foreach ($rows as $row) {
        $output .= implode($separator, $row).PHP_EOL;
    }
    return $output;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    setcookie('input', $input, time() + 3600);
    setcookie('input_format', $inputFormat, time() + 3600);
    setcookie('output_format', $outputFormat, time() + 3600);
    $rows = [];
    switch ($inputFormat) {
        case 'tsv':
            $rows = decodeTable($input, "\t");
            break;
        case 'csv':
            $rows = decodeTable($input, ',');
            break;
        case 'ssv':
            $rows = decodeTable($input, ';');
            break;
        case 'json':
            $rows = json_decode($input, true) ?? [];
            break;
        case 'yaml':
            $rows = yaml_parse($input) ?? [];
            break;
    }

    switch ($outputFormat) {
        case 'tsv':
            $result = encodeTable($rows, "\t");
            break;
        case 'csv':
            $result = encodeTable($rows, ',');
            break;
        case 'ssv':
            $result = encodeTable($rows, ';');
            break;
        case 'json':
            $result = json_encode(
                    $rows,
                    JSON_PRETTY_PRINT
            );
            break;
        case 'yaml':
            $result = yaml_emit($rows);
            break;
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Konwerter</title>
</head>
<body>

<form method="post">
    <label>Dane wejściowe:</label><br>
    <textarea
            name="input"
            rows="12"
            cols="100"
    ><?= htmlspecialchars($input) ?></textarea>

    <br><br>

    <label>Format wejściowy:</label>

    <select name="input_format">
        <option value="tsv"
                <?= 'tsv' === $inputFormat ? 'selected' : '' ?>>
            TSV
        </option>
        <option value="csv"
                <?= 'csv' === $inputFormat ? 'selected' : '' ?>>
            CSV
        </option>
        <option value="ssv"
                <?= 'ssv' === $inputFormat ? 'selected' : '' ?>>
            SSV
        </option>
        <option value="json"
                <?= 'json' === $inputFormat ? 'selected' : '' ?>>
            JSON
        </option>
        <option value="yaml"
                <?= 'yaml' === $inputFormat ? 'selected' : '' ?>>
            YAML
        </option>
    </select>
    <br><br>
    <label>Format wyjściowy:</label>
    <select name="output_format">
        <option value="tsv"
                <?= 'tsv' === $outputFormat ? 'selected' : '' ?>>
            TSV
        </option>
        <option value="csv"
                <?= 'csv' === $outputFormat ? 'selected' : '' ?>>
            CSV
        </option>
        <option value="ssv"
                <?= 'ssv' === $outputFormat ? 'selected' : '' ?>>
            SSV
        </option>
        <option value="json"
                <?= 'json' === $outputFormat ? 'selected' : '' ?>>
            JSON
        </option>
        <option value="yaml"
                <?= 'yaml' === $outputFormat ? 'selected' : '' ?>>
            YAML
        </option>
    </select>

    <br><br>

    <button type="submit">
        Konwertuj
    </button>

</form>

<hr>

<pre><?= htmlspecialchars($result) ?></pre>

</body>
</html>