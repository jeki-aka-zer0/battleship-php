<?php

use Battleship\GameController;
use Battleship\Position;
use Battleship\Letter;
use Battleship\Color;

class App
{
    private const IS_DEV = true;

    private static $myFleet = array();
    private static $enemyFleet = array();
    private static $console;

    static function run()
    {
        self::$console = new Console();
        self::$console->setForegroundColor(Color::MAGENTA);

        self::$console->println("                                     |__");
        self::$console->println("                                     |\\/");
        self::$console->println("                                     ---");
        self::$console->println("                                     / | [");
        self::$console->println("                              !      | |||");
        self::$console->println("                            _/|     _/|-++'");
        self::$console->println("                        +  +--|    |--|--|_ |-");
        self::$console->println("                     { /|__|  |/\\__|  |--- |||__/");
        self::$console->println("                    +---------------___[}-_===_.'____                 /\\");
        self::$console->println("                ____`-' ||___-{]_| _[}-  |     |_[___\\==--            \\/   _");
        self::$console->println(" __..._____--==/___]_|__|_____________________________[___\\==--____,------' .7");
        self::$console->println("|                        Welcome to Battleship                         BB-61/");
        self::$console->println(" \\_________________________________________________________________________|");
        self::$console->println();
        self::$console->resetForegroundColor();
        self::InitializeGame();
        self::StartGame();
    }

    public static function InitializeEnemyFleet()
    {
        $allData = require __DIR__ . '/Batteship/FleetSets.php';
        $size = count($allData);
        $data = $allData[random_int(0, $size)];

        self::$enemyFleet = GameController::initializeShips(self::$enemyFleet, $data);
    }

    public static function getRandomPosition()
    {
        $rows = 8;
        $lines = 8;

        $letter = Letter::value(random_int(0, $lines - 1));
        $number = random_int(0, $rows - 1);

        return new Position($letter, $number);
    }

    public static function InitializeMyFleet()
    {
        $data = require __DIR__ . '/Batteship/FleetSets.php';

        self::$myFleet = GameController::initializeShips(self::$myFleet, $data[0]);

        if (!self::IS_DEV) {
            self::$console->printMessage("Please position your fleet (Game board has size from A to H and 1 to 8) :");

            foreach (self::$myFleet as $ship) {
                self::$console->println();
                self::$console->printMessage(
                    sprintf("Please enter the positions for the %s (size: %s)", $ship->getName(), $ship->getSize())
                );

                for ($i = 1; $i <= $ship->getSize(); $i++) {
                    self::$console->printMessage(sprintf("\nEnter position %s of %s (i.e A3):", $i, $ship->getSize()));
                    $input = readline("");
                    $ship->addPosition($input);
                }
            }
        }
    }

    public static function beep()
    {
        echo "\007";
    }

    public static function InitializeGame()
    {
        self::InitializeMyFleet();
        self::InitializeEnemyFleet();
    }

    public static function StartGame()
    {
        self::$console->println('🔫');

        while (true) {
            self::$console->println("‍👵 Player, it's your turn");
            self::$console->printMessage("Enter coordinates for your shot:");
            $position = readline("");

            // GameController::asd(self::$enemyFleet);
            $isHit = GameController::checkIsHit(self::$enemyFleet, self::parsePosition($position));
            if ($isHit) {
                self::beep();
                self::$console->println('🔫');
            }

            if ($isHit) {
                self::$console->printSuccess("Yeah! Nice hit! 🎯");
            } else {
                self::$console->printError("Miss 🌊");
            }

            self:: printGameResultIfFinished();

            self::$console->println();

            $position = self::getRandomPosition();
            $isHit = GameController::checkIsHit(self::$myFleet, $position);

            $message = sprintf(
                "🤖 Computer shoot in %s%s and %s",
                $position->getColumn(),
                $position->getRow(),
                $isHit ? "hit your ship! 🎯\n" : "miss 🌊"
            );
            if ($isHit) {
                self::$console->printError($message);
            } else {
                self::$console->printSuccess($message);
            }
            if ($isHit) {
                self::beep();

                self::$console->println('Boom 💥');
            }

            self:: printGameResultIfFinished();

            self::$console->println('');
            self::$console->printLine();
            self::$console->println('');
        }
    }

    private static function isMyVictory()
    {
        return GameController::isFleetDead(self::$enemyFleet);
    }

    private static function isComputerVictory()
    {
        return GameController::isFleetDead(self::$myFleet);
    }

    private static function printGameResultIfFinished()
    {
        if (self::isMyVictory()) {
            self::$console->println('My victory!!');
            exit;
        }
        if (self::isComputerVictory()) {
            self::$console->println('Computer victory!!');
            exit;
        }
    }

    public static function parsePosition($input)
    {
        $letter = substr($input, 0, 1);
        $number = substr($input, 1, 1);

        if (!is_numeric($number)) {
            throw new Exception("Not a number: $number");
        }

        return new Position($letter, $number);
    }
}