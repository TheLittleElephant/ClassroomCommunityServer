<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

class GameRequest
{
    /**
     * First player
     * @var string
     */
    private $firstPlayer;
    /**
     * Second player
     * @var string
     */
    private $secondPlayer;
    /**
     * Request response
     * @var boolean
     */
    private $response;

    /**
     * Set of requests
     * @var array
     */
    private $requests = array();

    /**
     * Build a game request
     * @param string $firstPlayer
     * @param string $secondPlayer
     * @param bool $response
     */
    public function __construct(string $firstPlayer, string $secondPlayer, bool $response)
    {
        $this->firstPlayer = $firstPlayer;
        $this->secondPlayer = $secondPlayer;
        $this->response = $response;
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
     * @return bool
     */
    public function isResponse(): bool
    {
        return $this->response;
    }

    /**
     * @param bool $response
     */
    public function setResponse(bool $response): void
    {
        $this->response = $response;
    }

    /**
     * @return array
     */
    public function getRequests(): array
    {
        return $this->requests;
    }

    /**
     * @param array $requests
     */
    public function setRequests(array $requests): void
    {
        $this->requests = $requests;
    }

    /**
     * Allows to add a game request to the requests array
     * @param GameRequest $gameRequest
     */
    public function addRequest(GameRequest $gameRequest): void {
        $this->requests[] = $gameRequest;
    }

    public static function putFriendRequest(string $key, string $serverKey, string $firstPlayerId,
                                     string $secondPlayerId, array $friendsList): string {

        $firstPlayerIdIsCorrect = false;
        $secondPlayerIdIsCorrect = false;

        if($key != $serverKey) {
            return json_encode(array("error" => "Server key is invalid"));
        }

        foreach($friendsList as $friend) {
            if($friend->getId() == $firstPlayerId && $friend->getId() == $secondPlayerId) {
                $firstPlayerIdIsCorrect = true;
                $secondPlayerIdIsCorrect = true;
            }
        }

        if(!$firstPlayerIdIsCorrect && !$secondPlayerIdIsCorrect) {
            return json_encode(array("error" => "You cannot be logged"));
        }

        self::$requests[] = new GameRequest($firstPlayerId, $secondPlayerId, null);
    }

    /**
     * Allows to check if a request is waiting on the server
     * @param string $key
     * @param string $serverKey
     * @param string $firstPlayerId
     * @param array $friendsList
     * @return string
     */
    public static function checkRequest(string $key, string $serverKey, string $firstPlayerId,
                                 array $friendsList): string {

        $firstPlayerIdIsCorrect = false;

        if($key != $serverKey) {
            return json_encode(array("error" => "Server key is invalid"));
        }

        foreach($friendsList as $friend) {
            if($friend->getId() == $firstPlayerId) {
                $firstPlayerIdIsCorrect = true;
            }
        }

        if(!$firstPlayerIdIsCorrect) {
            return json_encode(array("error" => "You cannot be logged"));
        }

        foreach(self::$requests as $request) {
            if ($request->getSecondPlayer() == $firstPlayerId) {
                foreach ($friendsList as $friend) {
                    if ($friend->getId() == $request->getFirstPlayer()) {
                        return $friend->getId();
                    }
                }
            }
        }
    }

    public static function gameRequestResponse(string $key, string $serverKey, string $firstPlayerId,
                                        string $secondPlayerId, array $friendsList, string $response): string {

        $firstPlayerIdIsCorrect = false;
        $secondPlayerIdIsCorrect = false;

        if($key != $serverKey) {
            return json_encode(array("error" => "Server key is invalid"));
        }

        foreach($friendsList as $friend) {
            if($friend->getId() == $firstPlayerId && $friend->getId() == $secondPlayerId) {
                $firstPlayerIdIsCorrect = true;
                $secondPlayerIdIsCorrect = true;
            }
        }

        if(!$firstPlayerIdIsCorrect && !$secondPlayerIdIsCorrect) {
            return json_encode(array("error" => "You cannot be logged"));
        }

        foreach(self::$requests as $request) {
            if($request->getSecondPlayer() == $firstPlayerId && $request->getFirstPlayer() == $secondPlayerId) {
                if($response == "yes") {
                    $request->setResponse(true);
                } else {
                    $request->setResponse(false);
                }
                return json_encode(array("requestResponse" => "Your response has been sent"));
            }
        }

    }

    public static function getFriendResponse(string $key, string $serverKey, string $firstPlayerId, array $friendsList, array $gamesList): string {
        $firstPlayerIdIsCorrect = false;

        if($key != $serverKey) {
            return json_encode(array("error" => "Server key is invalid"));
        }

        foreach($friendsList as $friend) {
            if($friend->getId() == $firstPlayerId) {
                $firstPlayerIdIsCorrect = true;
            }
        }

        if(!$firstPlayerIdIsCorrect) {
            return json_encode(array("error" => "You cannot be logged"));
        }

        $index = 0;
        $i = 0;
        foreach(self::$requests as $request) {
            if($request->getFirstPlayer() == $firstPlayerId) {
                $index = $i;
            } else {
                $i++;
            }
        }

        if(self::$this->getRequests()[i]->getResponse() != null) {
            $gamesList[] = new Game($firstPlayerId, self::$this->getRequests()[i]->getSecondPlayer(), 0, 0);
            unset(self::$this->getRequests()[i]);
            return "yes";
        }

        return "no";
    }
}
