<?php

session_start();

$folder = $_SESSION['folder'];
$filename = $folder. "/question1.txt";
$file_handle = fopen($filename, "a+");

// открываем файл для чтения, затем очищаем его
// весь текст из файла будет в этой переменной
$comments = fread($file_handle, filesize($filename));
fclose($file_handle); // закрываем этот дескриптор 



if (!empty($_POST['posted'])) {
    // создаем файл и затем
    // сохраняем в нем тест из $_POST['question1']
    $question1 = $_POST['question1'];
    $file_handle = fopen($filename, "w+");
    // полная перезапись файла
    if (flock($file_handle, LOCK_EX)) {
        //эксклюзивная блокировка
        if (fwrite($file_handle, $question1) == FALSE) {
            echo "Не могу записать в файл {$filename}";
        }
        flock($file_handle, LOCK_UN);
        // освобождаем блокировку 
    } 
    
    // закрываем файл и перенаправляем браузер на следующую страницу
    fclose($file_handle);
    header("Location: list.php");
} else {
?>
<html>
    <head>
        <title>Файлы & папки-онлайн опрос</title>
    </head>


    <table border=0><tr><td>
        Пожалуйста введите ваш ответ на следующий вопрос нашего опроса:
        </td></tr>
        <tr bgcolor=lightblue><td>
            Что вы думаете о состоянии мировой экономики? <br/>
            Можете ли вы помочь нам исправить ситуацию?
        </td></tr>
        <tr><td>
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method=POST>
                <input type="hidden" name="posted" value=1>
                <br/>
                <textarea name="question1" rows=12 cols=35><?=$comments?></textarea>
                </td></tr>
                <tr><td> 
                <input type="submit" name="submit" value="Отправить">
            </form></td></tr>
    </table>
<?php } ?>