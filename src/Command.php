<?php

namespace CSV;


interface Command
{
    public function __construct(ConfigInterface $config, $separator);
    
    public function run();
}