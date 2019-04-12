<?php

namespace CSV;


class UserTextUtil
{
    const SEPARATORS = [
        'comma' => ',',
        'semicolon' => ';'
    ];

    protected $separatorType;

    /**
     * @var ConfigInterface
     */
    protected $config;

    public function __construct(ConfigInterface $config)
    {
        $this->config = $config;
    }

    public function run($file, $separatorType, $commandName, ...$args)
    {
        $this->setSeparatorType($separatorType);

        $commandName = 'CSV\\' . ucfirst($commandName .'Command');

        if (class_exists($commandName)) {
            /**
             * @var Command $command
             */
            $command = new $commandName($this->config, $this->getSeparator());
            $command->run();
        } else {
            throw new \Exception('Command not found');
        }
    }

    protected function checkSeparatorType($separatorType)
    {
        if (in_array($separatorType, self::SEPARATORS)) {
            $this->separatorType = $separatorType;
        } else {
            throw new \Exception('Wrong separator type');
        }
    }

    protected function setSeparatorType($type) {
        $this->separatorType = $type;
    }

    public function getSeparator()
    {
        return self::SEPARATORS[$this->separatorType];
    }
}