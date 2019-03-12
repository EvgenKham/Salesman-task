<?php 

//Приведение полученого массива в "человеческий" вид
function procc_input_arr(array $temp) {
    $count = count($temp);
    for($x = 0; $x < $count; $x++) {
        for($y = 0; $y < $count; $y++) {
            
            if($x == $y)
                $temp[$x][$y] = PHP_INT_MAX;
            if(empty($temp[$x][$y]))
                $temp[$x][$y] = 0;
            settype($temp[$x][$y], "int");
        }
    }
    return $temp;
}

//Нахождение минимума по строкам
function search_min_row (array $arr) {
    $count = count($arr);
    $min_in_row = array();
    for($a = 0; $a < $count; $a++) { 
        $min_in_row[$a] = PHP_INT_MAX;
        for($x = 0; $x < $count; $x++) {
            if( ($arr[$a][$x] < $min_in_row[$a]) && ($arr[$a][$x] != PHP_INT_MIN) )
                $min_in_row[$a] = $arr[$a][$x];
        }
    }
    for ($z = 0; $z < $count; $z++) {
        if( $min_in_row[$z] == PHP_INT_MAX) 
            $min_in_row[$z] = 0;
    }
    return $min_in_row;
}

//Нахождение минимума по столбцам
function search_min_column (array $arr) {
    $count = count($arr);
    $min_in_column = array();
    for($a = 0; $a < $count; $a++) {
        $min_in_column[$a] = PHP_INT_MAX;
        for($y = 0; $y < $count; $y++) {
            if( ($arr[$y][$a] < $min_in_column[$a]) && ($arr[$y][$a] != PHP_INT_MIN) )
                $min_in_column[$a] = $arr[$y][$a];   
        }
    }
    for ($i = 0; $i < $count; $i++) {
        if( $min_in_column[$i] == PHP_INT_MAX) 
            $min_in_column[$i] = 0;
    }
    return $min_in_column;
}

//Редукция строк
function reduction_row (array $arr_min_row, array $arr) {
    $count = count($arr);
    for($a = 0; $a < $count; $a++) {
        for($x = 0; $x < $count; $x++)
            if(($arr[$a][$x] != PHP_INT_MAX) && ($arr[$a][$x] != PHP_INT_MIN)){
                $arr[$a][$x] -= $arr_min_row[$a];
            }
    }
    return $arr;
}

//Редукция столбцов
function reduction_column (array $arr_min_col, array $arr) {
    $count = count($arr);
    for($a = 0; $a < $count; $a++) {
        for($x = 0; $x < $count; $x++)
            if( ($arr[$x][$a] != PHP_INT_MAX) && ($arr[$x][$a] != PHP_INT_MIN) )
                $arr[$x][$a] -= $arr_min_col[$a];
    }
    return $arr;
}

//Вычисление оценок нулевых клеток и поиск максимльного нуля
function zero_and_max_value (array $arr) {
    $count = count($arr);
    $temp_max_value = 0;
    $key_0 = 0;
    $key_1 = 0;
    for($f = 0; $f < $count; $f++){
        for($h = 0; $h < $count; $h++){
            $min_row = PHP_INT_MAX;
            $min_column = PHP_INT_MAX;
            
            if($arr[$f][$h] == 0){       
                for($b = 0; $b < $count; $b++){
                    if((($arr[$f][$b] < $min_row) && ($arr[$f][$b] != PHP_INT_MIN) ) && ($b != $h))
                        $min_row = $arr[$f][$b];
                }
                
                for($c = 0; $c < $count; $c++){
                    if((($arr[$c][$h] < $min_column) && ($arr[$c][$h] != PHP_INT_MIN)) && ($c != $f))
                        $min_column = $arr[$c][$h];
                }

                $rating = $min_row + $min_column;
                
                if($temp_max_value == 0){
                    $temp_max_value = $rating;
                    $key_0 = $f;
                    $key_1 = $h;
                }
                elseif(($temp_max_value != 0) && ($temp_max_value < $rating)) {
                    $temp_max_value = $rating;
                    $key_0 = $f;
                    $key_1 = $h;
                }
            }
        }
    }
    if(($key_0 == 0) && ($key_1 == 0)) {
        for($f = 0; $f < $count; $f++){
            for($h = 0; $h < $count; $h++){
                if($arr[$f][$h] == PHP_INT_MAX) {
                    $key_0 = $f;
                    $key_1 = $h;
                }          
            }
        }
    }
        
    $temp = array(
        "key_0" => $key_0,
        "key_1" => $key_1
        );
    return  $temp; 
}

//Редукция матрицы(с исключением столбца и строки) 
function reduction_right_matrix (array $arr, array $point) {
    $count = count($arr);
    for($b = 0; $b < $count; $b++) {
        for($d = 0; $d < $count; $d++) {
            if(($b === $point["key_0"]) || ($d === $point["key_1"])){
                $arr[$b][$d] = PHP_INT_MIN;
            } 
        }
    }
    return $arr;
}

//Редукция матрицы(без исключения) 
function reduction_left_matrix (array $arr, array $point) {
    $k_0 = $point["key_0"];
    $k_1 = $point["key_1"];
    $arr[$k_0][$k_1] = PHP_INT_MAX;
    return $arr;
}

//Определение нижней границы 
function search_border (array $one, array $two) {
    $result = 0;
    $count_o = count($one);
    for($l = 0; $l < $count_o; $l++) {
        $result += $one[$l];
        $result += $two[$l];
    }
    return $result;
}

//Запрет преждевременного замыкания
function not_loops (array $arr) {
        $count = count($arr);
        $crossway_row = 0;
        $crossway_col = 0;
        $number_row = 0;
        $number_col = 0;
        for($e = 0; $e < $count; $e++) {
            $quanity_row = 0;
            $quanity_col = 0;
            for($f = 0; $f < $count; $f++) {
                if (($arr[$e][$f] != PHP_INT_MIN) && ($arr[$e][$f] != PHP_INT_MAX)) {
                    $quanity_row++;
                }
                if ( ($arr[$f][$e] != PHP_INT_MIN) && ($arr[$f][$e] != PHP_INT_MAX) )  {     
                    $quanity_col++;
                }
                if (($f == ($count - 1)) && ($quanity_col > $crossway_col)) {
                    $crossway_col = $quanity_col;
                    $number_col = $e;

                }
                if (($f == ($count - 1)) && ($quanity_row > $crossway_row)){
                    $crossway_row = $quanity_row;
                    $number_row = $e;
                }
            } 
        }
    if (($crossway_row != 0) && ($crossway_col != 0))
        $arr[$number_row][$number_col] = PHP_INT_MAX;
    return $arr;
}

//Поиск наилучшего пути
function search_optimal_way (array $way) {
    $size_way = count($way);
    $optimal = array();
    $optimal_border = PHP_INT_MAX;
    $oprimal_id = 0;
    for($id = $size_way - 1; $id >= 0; $id--){ 
        $last_element = $way[$id]["border"];
        if (($way[$id]["bifurcation"] == false) && ($optimal_border > $last_element)) {
            $optimal_border = $last_element;
            $oprimal_id = $id;
        }       
    }
    $optimal = array(
                "optimal_border" => $optimal_border,
                "optimal_id" => $oprimal_id
                );
    return $optimal;
}

//Исследование матрицы на один оставшийся столбец и строку
function last_way (array $arr) {
    $counter = 0;
    $size_arr = count($arr);
    for ($a = 0; $a < $size_arr; $a++) {
        for ($b = 0; $b < $size_arr; $b++) {
            if($arr[$a][$b] != PHP_INT_MIN)
                $counter++;
        }
    }
    if($counter == 1)
        return true;
    else
        return false;
}

//Вывод последненго пункта
function last_point (array $matrix) {
    $size_matrix = count($matrix);
    $row = 0;
    $col = 0;
    for ($a = 0; $a < $size_matrix; $a++) {
        for ($b = 0; $b < $size_matrix; $b++) {
            if($matrix[$a][$b] == PHP_INT_MAX) {
                $row = $a;
                $col = $b;
            }
        }
    }
    $last_point = array (
        "key_0" => $row,
        "key_1" => $col
    );
    
    return $last_point;
}

//Отображение матрицы (для удобства восприятия)
function show_table (array $arr) {
    $size_arr = count($arr);
    $table = "<table border='1' >";
    for($i = 0; $i < $size_arr; $i++){
            $table .= "<tr>";
            for($j = 0; $j < $size_arr; $j++){
                $temp = $arr[$i][$j];
                if($temp == PHP_INT_MAX)
                    $table .= "<td><p>&infin;</p></td>";
                elseif ($temp == PHP_INT_MIN)
                    $table .= "<td><p>&mdash;</p></td>";
                else
                    $table .= "<td>$temp</td>";
            }
            $table .= "</tr>";
        }  
    $table .= "</table>";
    echo $table;  
}

//Вывод результирующего маршрута на дисплей
function display (array $answers, int $border) {
    $first_point = 1;
    $second_point = 1;

    $s = count($answers);
    for ($i = 0; $i < $s; $i++) {

        if ($answers[$i]["key_0"] == $first_point) {
            $second_point = $answers[$i]["key_1"];

            if ($first_point == 1 )
                echo "Путь: $first_point => $second_point => ";
            elseif ($second_point == 1) 
                echo "$second_point  ";
            else          
                echo "$second_point => ";
            
            $first_point = $second_point;
            if ($second_point == 1) {
                echo "Длинна: $border";
                break;
            }
            $i = -1;
        }        
    }
}
?>