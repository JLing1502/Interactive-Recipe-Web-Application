<?php
require_once __DIR__ . '/../models/Event.php';

class EventController
{
    private $eventModel;

    public function __construct()
    {
        $this->eventModel = new Event();
    }

    public function create()
    {
        session_start();

        if (!isset($_SESSION['user']) || empty($_SESSION['user']['UserID'])) {
            die("Unauthorized access.");
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = $_POST['title'] ?? '';
            $description = $_POST['description'] ?? '';
            $datetime = $_POST['datetime'] ?? null;
            $status = $_POST['status'] ?? 'pending';
            $deadline = $_POST['deadline'] ?? null;
            $userId = $_SESSION['user']['UserID'];

            if (empty($title) || empty($description) || empty($datetime) || empty($deadline)) {
                echo "All fields are required.";
                return;
            }

            $data = [
                'Title' => $title,
                'Description' => $description,
                'DateTime' => $datetime,
                'Status' => $status,
                'Deadline' => $deadline,
                'UserID' => $userId
            ];

            $success = $this->eventModel->createEvent($data);

            if ($success) {
                echo "Event created successfully!";
            } else {
                echo "Failed to create event.";
            }
        }
    }

    public function indexByUser($userId)
    {
        return $this->eventModel->getEventsByUser($userId);
    }

    public function delete($eventId)
    {
        return $this->eventModel->deleteEvent($eventId);
    }
}
