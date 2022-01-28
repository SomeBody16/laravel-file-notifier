<?php

declare(strict_types=1);

namespace Netzindianer\FileNotifier;

use Xtompie\Result\Result;

class FileNotifier
{
    /**
     * @param string $fileName Path to the file
     * @param int $seconds If modified since this seconds, call $sender
     * @param callable $sender Callback to notify with new content. $sender(string $content, string $fileName)
     * @param int $lines How many lines of file pass to $sender
     * @return Result -1 if nothing changed, else return value of $sender
     */
    public function __invoke(
        string $fileName,
        int $seconds,
        callable $sender,
        int $lines,
    ): Result {
        if (!file_exists($fileName) || filemtime($fileName) < time() - $seconds) {
            return Result::ofValue(-1);
        }

        $content = $this->readLastLines($fileName, $lines);
        if (strlen($content) <= 0) {
            return Result::ofValue(-1);
        }

        try {
            return Result::ofValue(
                $sender($content, $fileName)
            );
        }
        catch (\Exception $e) {
            return Result::ofErrorMsg($e->getMessage());
        }
    }

    protected function readLastLines(string $fileName, int $linesNumber): string
    {
        $file = fopen($fileName, 'r');

        $lines = [];
        $currentLine = '';
        for ($pos = -1; fseek($file, $pos, SEEK_END) !== -1 && count($lines) < $linesNumber; $pos--)
        {
            $char = fgetc($file);
            if ($char == PHP_EOL) {
                $lines[] = $currentLine;
                $currentLine = '';
            }
            else {
                $currentLine = $char . $currentLine;
            }
        }

        return join("\n", $lines);
    }

}
