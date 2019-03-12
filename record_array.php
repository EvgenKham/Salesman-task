<?php
require_once 'functions.php';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $count = count($_POST['array']);
    $arr = $_POST['array'];
    
    //Приводим полученый массив в "человеческий" вид
    $start = procc_input_arr($arr);
    $finish = procc_input_arr($arr);  
    show_table($start);
    
    $min_in_row = search_min_row($finish);
    $temp = reduction_row($min_in_row, $finish);       
    $min_in_column = search_min_column($temp);
    $finish = reduction_column($min_in_column, $temp);
    
     //Создаем массив содержащий матрицы по которым производятся вычисления
    $array_matrix = array();
    $array_matrix[] = $finish;                //Запись первой матрицы в мaссив

    //Нижняя граница
    $border_low = search_border($min_in_row, $min_in_column);  

    //Ищем максимальный ноль
    $position_zero = zero_and_max_value($finish);
    
    //Создаем массив результатов вычислений по таблицам
    $array_answers = array();                 //Массив результатов
    $id = 0;
    $id_parent = 0;
    $root = array(                            //Первый результат
        "id" => $id,                          //Идентификатор
        "border" => $border_low,              //Нижняя граница
        "bifurcation" => true,                //Разбиение матрицы
        "included" => false                   //Включение результата в конечный маршрут
        );
    
    $array_answers[] = $root;

    $bif = PHP_INT_MAX;
    $optimal = array();
    for($rebs = 0; $rebs < $bif; $rebs++) {
        
        if (empty($optimal)) {
            $id_parent = $root["id"];
        }
        else {
            $id_parent = $optimal["optimal_id"];
            $array_answers[$id_parent]["bifurcation"] = true;
            $finish = $array_matrix[$id_parent];
            $position_zero = zero_and_max_value($finish);            
        }
        
        //Выполняется, если существует более одного путь
        if (!(last_way ($finish))) {

            //Производим разбиение
            //Левая матрица будет содержать маршруры включают выбраную дугу
            $left_matrix = reduction_left_matrix($finish, $position_zero);
//            show_table($left_matrix);
            $min_in_row = search_min_row($left_matrix);

            $temp = reduction_row($min_in_row, $left_matrix);
            $min_in_column = search_min_column($temp);

            $finish_left = reduction_column($min_in_column, $temp);
//            show_table($finish);
            $array_matrix[] = $finish_left;           //Запись левой матрицы в массив

            //Определение нижней границы в левой матрице
            $border_left = search_border($min_in_row, $min_in_column);
            if (empty($optimal))
                $border_left += $border_low;
            else 
                $border_left += $optimal["optimal_border"];

            $id++;
            $left_result =  array(                    //Левый результат
                "id" => $id,                          //Идентификатор
                "id_parent" => $id_parent,            //Идентификатор родителя
                "border" => $border_left,             //Нижняя граница
                "bifurcation" => false,               //Разбиение матрицы
                "pos_zero"  => $position_zero,        //Локализация максимального нуля
                "included" => false                   //Включение результата в конечный маршрут
            );
            $array_answers[] = $left_result;
//            print_r($left_result);
//            echo "<br>";


            //Правая матрица будет содержать маршуры исключая выбраную дугу
            $right_matrix = reduction_right_matrix($finish, $position_zero);
            $right_matrix = not_loops($right_matrix);
//            show_table($right_matrix);
            $min_in_row = search_min_row($right_matrix);

            $temp = reduction_row($min_in_row, $right_matrix);
            $min_in_column = search_min_column($temp);

            $finish_right = reduction_column($min_in_column, $temp);
//           show_table($finish_right);
            $array_matrix[] = $finish_right;         //Запись правой матрицы в массив

            //Определение нижней границы в правой матрице
            $border_right = search_border($min_in_row, $min_in_column);
            if (empty($optimal))
                $border_right += $border_low;
            else 
                $border_right += $optimal["optimal_border"];

            $id++;
            $right_result =  array(                   //Правый результат
                "id" => $id,                          //Идентификатор
                "id_parent" => $id_parent,            //Идентификатор родителя
                "border" => $border_right,            //Нижняя граница
                "bifurcation" => false,               //Разбиение матрицы
                "pos_zero"  => $position_zero,        //Локализация максимального нуля
                "included" => true                    //Включение результата в конечный маршрут
            );
            $array_answers[] = $right_result;
//            print_r($right_result);
//            echo "<br>";

            //Поиск наилучшего пути
            $optimal = search_optimal_way ($array_answers);
        }
        
        //Выполняется если остался последний столбец и строка в выбраной матрице (последний город на пути)
        else {

            $id_parent = $optimal["optimal_id"];
            $finish = $array_matrix[$id_parent];
//            show_table($finish);
            $array_answers[$id_parent]["bifurcation"] = true;            
            $id ++;
            $left_last_border = PHP_INT_MAX;
            $position = last_point ($finish);
            
            $leaf_left = array (                      //Последний левый результат
                "id" => $id,                          //Идентификатор
                "id_parent" => $id_parent,            //Идентификатор родителя
                "border" => $left_last_border,        //Нижняя граница
                "bifurcation" => false,               //Разбиение матрицы
                "pos_zero"  => $position,             //Локализация максимального нуля
                "included" => false                   //Включение результата в конечный маршрут
        );
            $array_answers[] = $leaf_left;
            
            $id ++;
            $right_last_border = $array_answers[$id_parent]["border"] + 0 ;
            
            $leaf_right = array (                     //Последний правый результат
                "id" => $id,                          //Идентификатор
                "id_parent" => $id_parent,            //Идентификатор родителя
                "border" => $right_last_border,       //Нижняя граница
                "bifurcation" => false,               //Разбиение матрицы
                "pos_zero"  => $position,             //Локализация максимального нуля
                "included" => true                    //Включение результата в конечный маршрут
        );

            $array_answers[] = $leaf_right;       
            break;  
        }
    }
    
    //Выборка из всего дерева результатов нужной ветви
    $points_result_way = array();
    $size_answer = count($array_answers);

    $points_result_way[] = $array_answers[$size_answer - 1]["pos_zero"];
    $last_link = $array_answers[$size_answer - 1]["id_parent"];
    $bor = $array_answers[$size_answer - 1]["border"];
    
    for ($i = 1; $i < $size_answer; $i++) {
        
        $points_result_way[] = $array_answers[$last_link]["pos_zero"];
        $last_link = $array_answers[$last_link]["id_parent"];

        if ($last_link == 0)
            break;
    }
    
    //Вывод массива вероятных ответов
//    foreach ($array_answers as $value) {
//        print_r ($value);
//        echo "<br>";     
//    }
    
    //Перевод результирющих значений в отображаемый вид
    $s = count($points_result_way);
    for ($i = 0; $i < $s; $i++) {
        $points_result_way[$i]["key_0"]++;
        $points_result_way[$i]["key_1"]++;
    }
    
    //Вывод ответа на дисплей
    display ($points_result_way, $bor); 
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Решение</title>
    </head>
    <body>
        <br>
        <a href="input_form.php">Повторить</a>
        <br>
        <a href="c:\Server\data\htdocs\smp.by\index.php">На главную</a>
    </body>
</html>