<?php

namespace Battleship;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

//use PHPUnit\Util\Exception;

final class GameControllerTests extends TestCase
{
    private $data = [
        'Aircraft Carrier' => [
            'color' => Color::CADET_BLUE,
            'positions' => [
                ['B', 1],
                ['B', 2],
                ['B', 3],
                ['B', 4],
                ['B', 5],
            ],
        ],
    ];

    public function testCheckIsHitTrue()
    {
        $fleet = [];
        $ships = GameController::initializeShips($fleet, $this->data);
        $counter = 0;

        foreach ($ships as $ship) {
            $letter = Letter::$letters[$counter];

            for ($i = 0; $i < $ship->getSize(); $i++) {
                array_push($ship->getPositions(), new Position($letter, $i));
            }

            $counter++;
        }

        $result = GameController::checkIsHit($ships, new Position('A', 1));

        $this->assertTrue($result);
    }

    public function testCheckIsHitFalse()
    {
        $fleet = [];
        $ships = GameController::initializeShips($fleet, $this->data);
        $counter = 0;

        foreach ($ships as $ship) {
            $letter = Letter::$letters[$counter];

            for ($i = 0; $i < $ship->getSize(); $i++) {
                array_push($ship->getPositions(), new Position($letter, $i));
            }

            $counter++;
        }

        $result = GameController::checkIsHit($ships, new Position('H', 1));

        $this->assertFalse($result);
    }

    public function testCheckIsHitPositstionIsNull()
    {
        $fleet = [];
        $data = [];
        $this->expectException(InvalidArgumentException::class);
        GameController::initializeShips($fleet, $data);
        GameController::checkIsHit($fleet, null);
    }

    public function testCheckIsHitShipIsNull()
    {
        $this->expectException(\Error::class);
        GameController::checkIsHit(null, new Position('H', 1));
    }

    public function testIsShipValidFalse()
    {
        $ship = new Ship("TestShip", 3);
        $result = GameController::isShipValid($ship);

        $this->assertFalse($result);
    }

    public function testIsShipValidTrue()
    {
        $positions = [new Position('A', 1), new Position('A', 1), new Position('A', 1)];
        $ship = new Ship("TestShip", 3);
        foreach ($positions as $position) {
            array_push($ship->getPositions(), $position);
        }

        $result = GameController::isShipValid($ship);

        $this->assertTrue($result);
    }
}