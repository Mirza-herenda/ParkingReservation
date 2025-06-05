<?php

require_once __DIR__ . "/BaseService.php";
require_once __DIR__ . "/../dao/MessagesDao.php";

class MessagesService extends BaseService {
    public function __construct() {
        parent::__construct(new MessagesDao());
    }

    public function create_message($data) {
        if (empty($data['emailAdress']) || empty($data['title']) || empty($data['message'])) {
            throw new Exception("Missing required fields: emailAdress, title, or message.");
        }
        return $this->dao->insert($data);
    }

    public function update_message($id, $data) {
        if (!is_array($data)) {
            throw new Exception("Invalid data format. Expected an array.");
        }
        return $this->dao->update($data, $id);
    }

    public function delete_message($id) {
        if (empty($id)) {
            throw new Exception("Message ID is required.");
        }
        return $this->dao->delete($id);
    }
}
?>