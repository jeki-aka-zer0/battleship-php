<?php

//use Battleship\Color;

use Battleship\Color;

class Console
{
    function resetForegroundColor()
    {
        echo(Battleship\Color::DEFAULT_GREY);
    }

    function setForegroundColor($color)
    {
        echo($color);
    }

    function println($line = "")
    {
        echo "$line\n";
    }

    public function printMiss($message): void
    {
        $this->setForegroundColor(Color::CADET_BLUE);
        $this->println($message);
        $this->resetForegroundColor();
    }

    public function printHit($message): void
    {
        $this->setForegroundColor(Color::RED);
        $this->println($message);
        $this->resetForegroundColor();
    }

    public function printMessage($message): void
    {
        $this->setForegroundColor(Color::YELLOW);
        $this->println($message);
        $this->resetForegroundColor();
    }

    public function printLine(): void
    {
        $this->println('____________________________________');
    }
}