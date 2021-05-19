<?php

declare(strict_types=1);

namespace yzh52521\ThinkAuth\command;

use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;
use think\facade\Env;

class Publish extends Command
{

    public function configure()
    {
        $this->setName('auth:publish')
            ->setDescription('Publish auth config to config folder');
    }

    public function execute(Input $input, Output $output)
    {
        //获取默认配置文件
        $content = file_get_contents(__DIR__ . '/../../../think-auth/src/config.php');

        $configPath = config_path() . '/';
        $configFile = $configPath . 'auth.php';


        //判断目录是否存在
        if (!file_exists($configPath)) {
            mkdir($configPath, 0755, true);
        }

        //判断文件是否存在
        if (is_file($configFile)) {
            throw new \InvalidArgumentException(sprintf('The config file "%s" already exists', $configFile));
        }

        if (false === file_put_contents($configFile, $content)) {
            throw new \RuntimeException(
                sprintf('The config file "%s" could not be written to "%s"', $configFile, $configPath)
            );
        }

        $output->writeln('create auth config ok');
    }

}
