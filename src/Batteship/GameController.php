<?php

namespace Battleship;

use InvalidArgumentException;

class GameController
{
    public static function checkShips(array $fleet)
    {
        foreach ($fleet as $ship) {
            if ($ship->isSunken()) {

            }
        }
    }

    public static function checkIsHit(array &$fleet, $shot)
    {
        if ($fleet == null) {
            throw new InvalidArgumentException("ships is null");
        }

        if ($shot == null) {
            throw new InvalidArgumentException("shot is null");
        }

        foreach ($fleet as &$ship) {
            foreach ($ship->getPositions() as $position) {
                if ($position == $shot) {
                    $ship->incrementHits();
                    return true;
                }
            }
        }

        return false;
    }

    public static function initializeShips(array $fleet, array $data): array
    {
        foreach ($data as $name => $item) {
            $size = count($item['positions'] ?? []);
            $ship = new Ship($name, $size, $item['color']);

            foreach ($item['positions'] as $position) {
                array_push($ship->getPositions(), new Position($position[0], $position[1]));
            }

            $fleet[] = $ship;
        }

        return $fleet;
    }

    public static function isShipValid($ship)
    {
        return count($ship->getPositions()) == $ship->getSize();
    }

    public static function getRandomPosition()
    {
        $rows = 8;
        $lines = 8;

        $letter = Letter::value(random_int(0, $lines - 1));
        $number = random_int(0, $rows - 1);

        return new Position($letter, $number);
    }
}