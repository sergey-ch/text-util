<?php

namespace CSV;


class ReplaceDatesCommand implements Command
{
    /**
     * @var ConfigInterface
     */
    protected $config;
    protected $separator;

    public function __construct(ConfigInterface $config, $separator)
    {
        $this->config = $config;
        $this->separator = $separator;
    }

    public function run()
    {
        $out = [];

        $this->checkDir();

        $usersFile = new \SplFileObject($this->config->getUsersFilePath());
        $textsDir = new \DirectoryIterator($this->config->getTextsPath());

        $replacesPerUser = [];
        foreach ($textsDir as $file) {
            if ($file->isFile()) {
                $fileObject = $file->openFile();
                $outputFile = new \SplFileObject(
                    $this->config->getTextsOutputPath() . DIRECTORY_SEPARATOR . $file->getFilename(),
                    'w'
                );

                list($uesrId) = explode('-', $fileObject->getFilename());
                
                while(!$fileObject->eof()) {
                    $line = $fileObject->fgets();
                    
                    preg_match_all($this->config->getDateFromRegex(), $line, $m);
                    
                    $replace = [];
                    foreach ($m[0] as $date) {
                        $dateObject = \DateTime::createFromFormat($this->config->getDateFromFormat(), $date);
                        $replace[] = $dateObject->format($this->config->getDateToFormat());
                    }
                    
                    $line = str_replace($m[0], $replace, $line);
                    
                    $outputFile->fwrite($line);
                    
                    $replacesPerUser[$uesrId] = isset($replacesPerUser[$uesrId])
                        ? $replacesPerUser[$uesrId] + count($m[0])
                        : count($m[0]);
                }
            }
        }

        while (!$usersFile->eof()) {
            list($id, $name) = $usersFile->fgetcsv($this->separator);
            $replaces = isset($replacesPerUser[$id]) ? $replacesPerUser[$id] : 0;
            $out[$id] = [$name, $replaces];
        }

        $this->out($out);
    }

    protected function out(array $out)
    {
        echo implode(PHP_EOL, array_map(function($v) {
            return $v[0] .' '. $v[1];
        }, $out));
    }
    
    protected function checkDir() {
        if (file_exists($this->config->getTextsOutputPath())) {
            if (!is_dir($this->config->getTextsOutputPath())) {
                throw new \Exception($this->config->getTextsOutputPath() .' should be dir');
            }
        } else {
            mkdir($this->config->getTextsOutputPath());
        }
    }
}