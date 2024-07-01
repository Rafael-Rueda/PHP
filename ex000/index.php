<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HTML TO PHP</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <?php
    $value1 = $_GET['number1'] ?? '';
    $value2 = $_GET['number2'] ?? '';
    if ($value2 == 0 || $value2 == '') {
        $value2 = 1;
    };

    if ($value1 == '') {
        $value1 = 0;
    }
    ?>

    <header>
        <h1>Digite um numero: </h1>
    </header>
    <section>
        <form action="<?= $_SERVER["PHP_SELF"] ?>" method="get">
            <label for="number1">Dividendo</label>
            <input type="number" name="number1" id="number1" value="<?= $value1 ?>">
            <label for="number2">Divisor</label>
            <input type="number" name="number2" id="number2" value="<?= $value2 ?>">
            <input type="submit" value="Send">
        </form>
    </section>

    <?php
    if (isset($_GET['number1']) && isset($_GET['number2'])) {

        ?>

        <section>
            <h2>Result</h2>
            <div style="font-size: 32px; display: grid; grid-template-columns: 1fr 1fr; grid-template-rows: 1fr 1fr;">
                <span style="padding: 16px 32px; grid-column: 1; grid-row: 1;">
                    <? echo $value1; ?>
                </span>
                <span
                    style="padding: 16px 32px; grid-column: 2; grid-row: 1; border-left: 2px solid black; border-bottom: 2px solid black;">
                    <? echo $value2; ?>
                </span>
                <span style="padding: 16px 32px; grid-column: 1; grid-row: 2;">
                    <? echo $value1 % $value2; ?>
                </span>
                <span style="padding: 16px 32px; grid-column: 2; grid-row: 2; border-left: 2px solid black;">
                    <? echo floor($value1 / $value2); ?>
                </span>
            </div>
        </section>

        <?php
    }
    ?>
</body>

</html>