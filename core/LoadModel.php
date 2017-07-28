<?php
class LoadModel{
    function __construct($model){
        require("./models/$model.php");
    }
}

