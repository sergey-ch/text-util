<?php

namespace CSV;


interface ConfigInterface
{
    public function getUsersFilePath();

    public function getTextsPath();

    public function getTextsOutputPath();

    public function getDateFromFormat();

    public function getDateToFormat();

    public function getDateFromRegex();
}