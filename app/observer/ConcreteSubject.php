<?php
// observer/ConcreteSubject.php
class ConcreteSubject implements Subject {
    private $observers = [];
    private $message;

    public function attach(Observer $observer) {
        $this->observers[] = $observer;
    }

    public function detach(Observer $observer) {
        $this->observers = array_filter($this->observers, function ($obs) use ($observer) {
            return $obs !== $observer;
        });
    }

    public function notify() {
        foreach ($this->observers as $observer) {
            $observer->update($this->message);
        }
    }

    public function setMessage($message) {
        $this->message = $message;
        $this->notify();
    }
}
?>
