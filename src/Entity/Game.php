<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

class Game
{

    /**
     * @var string
     */
    private $firstPlayer;

    /**
     * @var string
     */
    private $secondPlayer;

    /**
     * @var integer
     */
    private $firstPlayerScore;

    /**
     * @var integer
     */
    private $secondPlayerScore;

    /**
     * @var array
     */
    private $gamesList = array();

    /**
     * Game constructor.
     * @param string $firstPlayer
     * @param string $secondPlayer
     * @param int $firstPlayerScore
     * @param int $secondPlayerScore
     */
    public function __construct(string $firstPlayer, string $secondPlayer, int $firstPlayerScore, int $secondPlayerScore)
    {
        $this->firstPlayer = $firstPlayer;
        $this->secondPlayer = $secondPlayer;
        $this->firstPlayerScore = $firstPlayerScore;
        $this->secondPlayerScore = $secondPlayerScore;
    }

    /**
     * @return string
     */
    public function getFirstPlayer(): string
    {
        return $this->firstPlayer;
    }

    /**
     * @param string $firstPlayer
     */
    public function setFirstPlayer(string $firstPlayer): void
    {
        $this->firstPlayer = $firstPlayer;
    }

    /**
     * @return string
     */
    public function getSecondPlayer(): string
    {
        return $this->secondPlayer;
    }

    /**
     * @param string $secondPlayer
     */
    public function setSecondPlayer(string $secondPlayer): void
    {
        $this->secondPlayer = $secondPlayer;
    }

    /**
     * @return int
     */
    public function getFirstPlayerScore(): int
    {
        return $this->firstPlayerScore;
    }

    /**
     * @param int $firstPlayerScore
     */
    public function setFirstPlayerScore(int $firstPlayerScore): void
    {
        $this->firstPlayerScore = $firstPlayerScore;
    }

    /**
     * @return int
     */
    public function getSecondPlayerScore(): int
    {
        return $this->secondPlayerScore;
    }

    /**
     * @param int $secondPlayerScore
     */
    public function setSecondPlayerScore(int $secondPlayerScore): void
    {
        $this->secondPlayerScore = $secondPlayerScore;
    }

    /**
     * @return array
     */
    public static function getGamesList(): array
    {
        return self::$gamesList;
    }

    /**
     * @param array $gamesList
     */
    public function setGamesList(array $gamesList): void
    {
        $this->gamesList = $gamesList;
    }

    public static function updateScore(string $id, bool $response) {
        $result = 0;
        $result = ($response) ? 5 : -5;
        $i = 0;
        $found = false;
        while ($i < count(self::$gamesList) && !$found) {
            if (self::$gamesList[i]->getFirstPlayer() == $id) {
                self::$firstPlayerScore += result;
                $found = true;
            } else if (self::$gamesList[i]->getSecondPlayer() == $id) {
                self::$secondPlayerScore += result;
                $found = true;
            }
        }
    }

    public static function getSecondOpponentScore(string $id) {
        $i = 0;
        while ($i < count(self::$gamesList)) {

            if(self::$gamesList[i]->getFirstPlayer() == $id) {
                return self::$gamesList[i]->getSecondPlayerScore();
            }
            else if(self::$gamesList[i]->getSecondPlayer() == $id) {
                return self::$gamesList[i]->getFirstPlayerScore();
            }
        }
    }
}
