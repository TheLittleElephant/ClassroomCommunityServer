<?php
/**
 * Created by PhpStorm.
 * User: Jonathan
 * Date: 10/03/2018
 * Time: 13:53
 */

namespace App\Controller;


use App\Entity\Friend;
use App\Entity\Game;
use App\Entity\GameRequest;
use App\Entity\Question;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncode;
use Symfony\Component\Serializer\Encoder\JsonDecode;

class QuizzController extends Controller
{
    private $serverKey = "1235";

    /**
     * @Route("/")
     */
    public function index() {
        return $this->render("index.html.twig", array());
    }

    /**
     * Allows to be connected to the server
     * @Route("/checkAttending/{key}/{id}")
     * @param string $key
     * @param string $id
     * @return string
     */
    public function login(string $key, string $id) {
        $friends = $this->getDoctrine()->getRepository(Friend::class)->findAll();
        $idIsCorrect = false;
        if($key != $this->serverKey) {
            return $this->json(array("error" => "Key is invalid"));
        }
        foreach($friends as $friend) {
            if($friend->getId() == $id) {
                $idIsCorrect = true;
                $friend->setConnected( 1);
                $this->getDoctrine()->getManager()->persist($friend);
                $this->getDoctrine()->getManager()->flush();
            }
        }

        if(!$idIsCorrect) {
            return $this->json(array("error" => "You cannot be logged"));
        }
        return $this->json(array("ok" => "You are now logged"));
    }

    /**
     * Allows an attending to logout from the server
     * @Route("/logout/{key}/{id}")
     * @param string $key
     * @param string $id
     * @return string|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function logout(string $key, string $id) {
        $friends = $this->getDoctrine()->getRepository(Friend::class)->findAll();
        $idIsCorrect = false;
        if($key != $this->serverKey) {
            return $this->json(array("error" => "Key is invalid"));
        }
        foreach($friends as $friend) {
            if($friend->getId() == $id) {
                $idIsCorrect = true;
                $friend->setConnected( 0);
                $this->getDoctrine()->getManager()->persist($friend);
                $this->getDoctrine()->getManager()->flush();
            }
        }

        if(!$idIsCorrect) {
            return $this->json(array("error" => "You cannot be logout"));
        }
        return $this->json(array("ok" => "You are now logout"));
    }

    /**
     * Allows an attending to get his friends
     * @Route("/getFriends/{key}")
     * @param string $key
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getFriends(string $key) {
        if($key != $this->serverKey) {
            return  $this->json(array("error" => "Key is invalid"));
        }
        $friends = $this->getDoctrine()->getRepository(Friend::class)->findAll();
        return $this->json($friends, 200, array("Content-Type" => "application/json"));
    }

    /**
     * Allows an attending to get questions
     * @Route("/getQuestions/{key}")
     * @param string $key
     * @return Response
     */
    public function getQuestions(string $key) {
        if($key != $this->serverKey) {
            return $this->json(array("error" => "Key is invalid"));
        }
        $questions = $this->getDoctrine()->getRepository(Question::class)->find(1);
        $encoder = new JsonEncoder(new JsonEncode(JSON_UNESCAPED_SLASHES, JSON_UNESCAPED_UNICODE), new JsonDecode(false));
        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getId();
        });
        $serializer = new Serializer(array($normalizer), array($encoder));
        $json = $serializer->serialize($questions, "json");
        return new Response($json, 200, array("Content-Type" => "application/json"));
    }

    /**
     * @Route("/putFriendRequest/{key}/{firstPlayerId}/{secondPlayerId}")
     * @param string $key
     * @param string $firstPlayerId
     * @param string $secondPlayerId
     * @return string
     */
    public function putFriendRequest(string $key, string $firstPlayerId, string $secondPlayerId) {
        if($key != $this->serverKey) {
            return $this->json(array("error" => "Key is invalid"));
        }
        $friends = $this->getDoctrine()->getRepository(Friend::class)->findAll();
        return GameRequest::putFriendRequest($key, $this->serverKey, $firstPlayerId, $secondPlayerId, $friends);
    }

    /**
     * @Route("/checkRequest/{key}/{firstPlayerId}")
     * @param string $key
     * @param string $firstPlayerId
     * @return string
     */
    public function checkRequest(string $key, string $firstPlayerId) {
        if($key != $this->serverKey) {
            return $this->json(array("error" => "Key is invalid"));
        }
        $friends = $this->getDoctrine()->getRepository(Friend::class)->findAll();
        return GameRequest::checkRequest(key, $this->serverKey, $firstPlayerId, $friends);
    }

    /**
     * @Route("/gameResponseRequest/{key}/{firstPlayerId}/{secondPlayerId}/{response}")
     * @param string $key
     * @param string $firstPlayerId
     * @param string $secondPlayerId
     * @param string $response
     * @return JsonResponse
     */
    public function gameResponseRequest(string $key, string $firstPlayerId, string $secondPlayerId, string $response) {
        if($key != $this->serverKey) {
            return $this->json(array("error" => "Key is invalid"));
        }
        $friends = $this->getDoctrine()->getRepository(Friend::class)->findAll();
        GameRequest::gameRequestResponse($key, $this->serverKey, $firstPlayerId, $secondPlayerId, $friends, $response);
    }

    /**
     * @Route("/getFriendRequest/{key}/{firstPlayerId}")
     * @param string $key
     * @param string $firstPlayerId
     * @return string
     */
    public function getFriendResponse(string $key, string $firstPlayerId) {
        if($key != $this->serverKey) {
            return $this->json(array("error" => "Key is invalid"));
        }
        $friends = $this->getDoctrine()->getRepository(Friend::class)->findAll();
        GameRequest::getFriendResponse($key, $this->serverKey, $firstPlayerId, $friends, Game::getGamesList());
    }

    /**
     * @Route("/putResponse/{key}/{id}/{response}")
     * @param string $key
     * @param string $id
     * @param string $response
     * @return JsonResponse
     */
    public function putResponse(string $key, string $id, string $response) {
        if($key != $this->serverKey) {
            return $this->json(array("error" => "Key is invalid"));
        }
        return  Game::updateScore($id, (bool) $response);
    }

    /**
     * @Route("/putResponse/{key}/{id}")
     * @param string $key
     * @param string $id
     * @return JsonResponse
     */
    public function getSecondOpponentScore(string $key, string $id) {
        if($key != $this->serverKey) {
            return $this->json(array("error" => "Key is invalid"));
        }
        return Game::getSecondOpponentScore($id);
    }
}