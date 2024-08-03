<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conversor de Números Romanos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .container {
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            max-width: 600px;
            width: 100%;
        }
        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        label {
            margin-bottom: 10px;
        }
        input[type="number"], input[type="text"] {
            width: calc(100% - 22px);
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-bottom: 10px;
        }
        button {
            background: green;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            margin-bottom: 10px;
        }
        button:hover {
            background: black;
        }
        .result, .error {
            text-align: center;
            font-size: 1.2em;
            margin-top: 20px;
        }
        .error {
            color: red;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Conversor de Números Romanos</h1>
        <form method="POST" action="">
            <div>
                <label for="numero">Digite um número inteiro de 1 a 3999: </label>
                <input type="number" id="numero" name="numero" min="1" max="3999">
                <button type="submit" name="convert_to_roman">Converter para Romano</button>
            </div>
            <div>
                <label for="romano">Digite um número romano de I a XIX: </label>
                <input type="text" id="romano" name="romano" pattern="[IVXLCDM]+" title="Digite um número romano válido">
                <button type="submit" name="convert_to_integer">Converter para Inteiro</button>
            </div>
        </form>

        <?php
        class RomanConverter
        {
            private static $romanNumerals = [
                'M' => 1000,
                'CM' => 900,
                'D' => 500,
                'CD' => 400,
                'C' => 100,
                'XC' => 90,
                'L' => 50,
                'XL' => 40,
                'X' => 10,
                'IX' => 9,
                'V' => 5,
                'IV' => 4,
                'I' => 1,
            ];

            public static function toRoman(int $number): string
            {
                if ($number < 1 || $number > 3999) {
                    throw new InvalidArgumentException('Número fora do intervalo deve ser entre 1 e 3999');
                }

                $result = '';
                foreach (self::$romanNumerals as $roman => $value) {
                    while ($number >= $value) {
                        $result .= $roman;
                        $number -= $value;
                    }
                }

                return $result;
            }

            public static function toInteger(string $roman): int
            {
                $roman = strtoupper($roman);
                $length = strlen($roman);
                $result = 0;
                $i = 0;

                while ($i < $length) {
                    $twoChar = substr($roman, $i, 2);
                    if (isset(self::$romanNumerals[$twoChar])) {
                        $result += self::$romanNumerals[$twoChar];
                        $i += 2;
                    } else {
                        $oneChar = substr($roman, $i, 1);
                        if (isset(self::$romanNumerals[$oneChar])) {
                            $result += self::$romanNumerals[$oneChar];
                            $i += 1;
                        } else {
                            throw new InvalidArgumentException('Número romano inválido');
                        }
                    }
                }

                return $result;
            }
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['convert_to_roman']) && isset($_POST['numero'])) {
                $number = intval($_POST['numero']);
                try {
                    $romanNumeral = RomanConverter::toRoman($number);
                    echo "<div class='result'>O número romano para $number é: <strong>$romanNumeral</strong></div>";
                } catch (InvalidArgumentException $e) {
                    echo "<div class='error'>Erro: " . $e->getMessage() . "</div>";
                }
            } elseif (isset($_POST['convert_to_integer']) && isset($_POST['romano'])) {
                $roman = strtoupper($_POST['romano']);
                try {
                    $integerNumber = RomanConverter::toInteger($roman);
                    echo "<div class='result'>O número inteiro para o romano $roman é: <strong>$integerNumber</strong></div>";
                } catch (InvalidArgumentException $e) {
                    echo "<div class='error'>Erro: " . $e->getMessage() . "</div>";
                }
            }
        }
        ?>
    </div>
</body>
</html>
