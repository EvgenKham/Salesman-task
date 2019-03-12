<?php

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    if(!($_POST["how_many"]) == 0) {
        $number = trim(strip_tags($_POST['how_many']));

        $table = "<form action='record_array.php' method='post'>" ;
            $table .= "<table border='1'>";

            for($i=0; $i < $number; $i++){
                $table .= "<tr>";
                for($j=0; $j < $number; $j++){
                    if($i==$j){
                        $table .= "<th style='background:pink'><p>&infin;</p></th>";
                    }
                    else {
                        $table .= "<td><input type='number' min='1' max='100' name='array[$i][$j]'/></td>";
                    }
                }
                $table .= "</tr>";
            }
            $table .= "</table>";
            $table .= "<input type='submit' value='решить'>";
            $table .= "</form>";

            echo $table; 
    }
    elseif(($_POST["how_many"]) == null) {
        echo "Вы не ввели количество городов!";     
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Таблица с данными</title>
    </head>
    <body>
        
    </body>
</html>