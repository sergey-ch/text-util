<?php

namespace CSV;


class CountAverageLineCountCommand implements Command
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

        $usersFile = new \SplFileObject($this->config->getUsersFilePath());
        $textsDir = new \DirectoryIterator($this->config->getTextsPath());

        $filesPerUser = [];
        $linesPerUser = [];
        foreach ($textsDir as $file) {
            if ($file->isFile()) {
                $fileObject = $file->openFile();

                list($uesrId) = explode('-', $fileObject->getFilename());

                $filesPerUser[$uesrId] = isset($filesPerUser[$uesrId])
                    ? $filesPerUser[$uesrId] + 1
                    : 1;

                // dirty hack for get last line
                $fileObject->seek($fileObject->getSize());
                $linesTotal = $fileObject->key() + 1;
                
                $linesPerUser[$uesrId] = isset($linesPerUser[$uesrId])
                    ? $linesPerUser[$uesrId] + $linesTotal
                    : $linesTotal;
            }
        }
        
        while (!$usersFile->eof()) {
            list($id, $name) = $usersFile->fgetcsv($this->separator);
            
            if (isset($linesPerUser[$id], $filesPerUser[$id])) {
                $avgLines = ceil($linesPerUser[$id] / $filesPerUser[$id]);
            } else {
                $avgLines = 0;
            }
            
            $out[$id] = [$name, $avgLines];
        }
        
        $this->out($out);
    }

    protected function out(array $out)
    {
        echo implode(PHP_EOL, array_map(function($v) {
            return $v[0] .' '. $v[1];
        }, $out));
    }
}