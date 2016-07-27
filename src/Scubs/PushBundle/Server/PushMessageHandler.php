<?php

namespace Scubs\PushBundle\Server;

use Ratchet\ConnectionInterface;
use Ratchet\Wamp\WampServerInterface;

class PushMessageHandler implements WampServerInterface
{
    protected $subscribedTopics = array();
    protected $clients;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
    }

    public function onSubscribe(ConnectionInterface $conn, $topic) {
        echo "Connection ".$conn->resourceId . " subscribed to topic ". $topic;
        $this->subscribedTopics[$topic->getId()] = $topic;
    }

    public function onUnSubscribe(ConnectionInterface $conn, $topic) {

    }

    public function onOpen(ConnectionInterface $conn) {
        // Store the new connection to send messages to later
        $this->clients->attach($conn);

        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onClose(ConnectionInterface $conn) {
        // The connection is closed, remove it, as we can no longer send it messages
        $this->clients->detach($conn);

        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onCall(ConnectionInterface $conn, $id, $topic, array $params) {
        // In this application if clients send data it's because the user hacked around in console
        $conn->callError($id, $topic, 'You are not allowed to make calls')->close();
    }

    public function onPublish(ConnectionInterface $conn, $topic, $event, array $exclude, array $eligible) {
        // In this application if clients send data it's because the user hacked around in console
        $conn->close();
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }

    /**
     * @param string JSON'ified string we'll receive from ZeroMQ
     */
    public function onMessageReceived($entry) {
        echo 'Received message : ' . $entry . "\n";
        $entryData = json_decode($entry, true);

        // If the lookup topic object isn't set there is no one to publish to
        if (!array_key_exists($entryData['channel'], $this->subscribedTopics)) {
            return;
        }

        $topic = $this->subscribedTopics[$entryData['channel']];

        // re-send the data to all the clients subscribed to that category
        $topic->broadcast($entryData);
    }

}