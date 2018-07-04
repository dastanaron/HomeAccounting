<?php

require 'config.php';

if(!empty($_POST)) {
    $deploy = DeployCreateCommand::init($_POST);
    $deploy->gitCommand();
    $deploy->run();
    dump($deploy);
}

?>

<h1>Утилита разворота приложения</h1>

<form action="" method="post">
    <label>
        Ветка с которой получаем приложение
        <input type="text" required name="branch" />
    </label>

    <div class="checkers">
        <div class="checkbox-block">
            <label>
                Выполнить composer install
                <input type="checkbox" name="params[composer]" />
            </label>
        </div>
        <div class="checkbox-block">
            <label>
                Выполнить migration
                <input type="checkbox" name="params[migration]" />
            </label>
        </div>
        <div class="checkbox-block">
            <label>
                Сгенерировать скрипт
                <input type="checkbox" name="script_generate" />
            </label>
        </div>
    </div>

    <button type="submit">Поехали</button>
</form>


<?php
echo "Дирректория проекта: " . PROJECT_PATH;
