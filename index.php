<?php
if(file_exists('vendor/autoload.php')){
    require 'vendor/autoload.php';
    require 'backup.php';    

    //set access token
    $token = '9V_8OiUWPjAAAAAAAAAADwatoHi0J7O--bDtuJkgd_LTfCmWwI7oZXRMbUD_PfnF';
    $project = 'aprende/dropbox';
    $projectFolder = date('l');

    $bk = new Backup($token,$project,$projectFolder);
    $bk->upload('index.zip');

    echo 'Upload Complete';

} else {
    echo "<h1>Please install via composer.json</h1>";
    echo "<p>Install Composer instructions: <a href='https://getcomposer.org/doc/00-intro.md#globally'>https://getcomposer.org/doc/00-intro.md#globally</a></p>";
    echo "<p>Once composer is installed navigate to the working directory in your terminal/command prompt and enter 'composer install'</p>";
    exit;
}

?>