<html>
<head>
    <title>Количесво городов</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style_for_form.css">
</head>
<body>
    <form action='action.php', method='post' >
        <fieldset>
            <legend>Количество городов</legend>
            <p><label>Сколько городов </label>
                <input type='number' min='2' max='100' name='how_many' /></p>
        </fieldset>
        <p><input type="submit" value="Отправить"></p>
    </form>
</body>
</html>