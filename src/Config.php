<?php

namespace CSV;


class Config implements ConfigInterface
{
    protected $config;
    
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function getUsersFilePath()
    {
        $this->checkParam('users_file');
        return $this->config['users_file'];
    }

    public function getTextsPath()
    {
        $this->checkParam('texts_path');
        return $this->config['texts_path'];
    }

    public function getTextsOutputPath()
    {
        $this->checkParam('output_texts_path');
        return $this->config['output_texts_path'];
    }

    public function getDateFromFormat()
    {
        $this->checkParam('dates_pattern_from');
        return $this->config['dates_pattern_from'];
    }

    public function getDateToFormat()
    {
        $this->checkParam('dates_pattern_to');
        return $this->config['dates_pattern_to'];
    }

    public function getDateFromRegex()
    {
        $this->checkParam('dates_pattern_regex');
        return $this->config['dates_pattern_regex'];
    }
    
    protected function checkParam($paramName)
    {
        if (!isset($this->config[$paramName])) {
            throw new \Exception('Wrong config parameter');
        }
    }
}