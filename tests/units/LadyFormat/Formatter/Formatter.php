<?php

namespace LadyFormat\Tests\Units\Formatter;

use mageekguy\atoum\test;

class Formatter extends test
{
    public function testCreateTemporaryFile()
    {
        $f = new \mock\LadyFormat\Formatter\Formatter;

        $this
            ->string($filename = $f->createTemporaryFile())
            ->boolean(file_exists($filename))
                ->isFalse()
            ->string($f->createTemporaryFile())
                ->isNotEqualTo($filename)
            ->string($filename = $f->createTemporaryFile('test'))
            ->boolean(file_exists($filename))
                ->isTrue()
            ->string(file_get_contents($filename))
                ->isEqualTo('test')
            ->and(unlink($filename))
            ->if($filename = $f->createTemporaryFile(null, 'html'))
            ->and($info = pathinfo($filename))
            ->string($info['extension'])
                ->isEqualTo('html')
        ;
    }

    public function testReadFile()
    {
        $f = new \mock\LadyFormat\Formatter\Formatter;

        $file = $f->createTemporaryFile('content');

        $this
            ->string($f->readFile($file))
                ->isEqualTo('content')
            ->exception(function () use ($f) {
                $f->readFile('test');
            })
                ->isInstanceOf('LadyFormat\Exception\IOException')
                ->message
                    ->contains('test')
        ;

        unlink($file);
    }

    public function testWriteFile()
    {
        $f = new \mock\LadyFormat\Formatter\Formatter;

        $file = $f->createTemporaryFile();

        $this
            ->boolean(file_exists($file))
                ->isFalse()
            ->if($f->writeFile($file, 'content'))
            ->string(file_get_contents($file))
                ->isEqualTo('content')
            ->exception(function () use ($f, $file) {
                $f->writeFile($file, 'test', false);
            })
                ->isInstanceOf('LadyFormat\Exception\IOException')
                ->message
                    ->contains('already exists')
            ->if($f->writeFile($file, 'test'))
            ->string(file_get_contents($file))
                ->isEqualTo('test')
        ;

        unlink($file);
    }

    public function testMkdir()
    {
        $f = new \mock\LadyFormat\Formatter\Formatter;

        $dir = dirname($f->createTemporaryFile()) . '/test/test';

        $this
            ->boolean(is_dir($dir))
                ->isFalse()
            ->if($f->mkdir($dir))
            ->boolean(is_dir($dir))
                ->isTrue()
        ;

        rmdir($dir);
    }
}
