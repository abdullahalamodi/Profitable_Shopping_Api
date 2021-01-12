<?php
include('../Database/Database.php');
class Category
{
    public $id;
    public $name;
    private $database;

    function __construct()
    {
        $db = new Database();
        $this->database = $db->connect();
    }

    public function getCategories()
    {
        return $this->executeFunction(
            "SELECT * from categories",
            null,
            true,
            true,
        );
    }

    public function getCategoryById($id)
    {
        return $this->executeFunction(
            "SELECT * from categories where id=?",
            [$id],
            true,
        );
    }

    public function getCategoryByTitle($title)
    {
        return $this->executeFunction(
            "SELECT * from categories where name like ?",
            [$title],
            true
        );
    }

    public function addCategory()
    {
        return $this->executeFunction(
            "INSERT INTO `categories`(`name`) VALUES (?)",
            [$this->name]
        );
    }

    public function updateCategory($id)
    {
        $response = $this->getCategoryById($id);
        if ($response->case) {
            ($this->name != null) ? $response->data->name =  $this->name : null;
            return
                $this->executeFunction(
                    "UPDATE `categories` SET `name`=? WHERE id = ?",
                    [$response->data->name, $id],
                );
        }
        return $response;
    }

    public function deleteCategory($id)
    {
        return
            $this->executeFunction(
                "DELETE FROM categories WHERE id=?",
                [$id],
            );
    }

    private function executeFunction(
        String $queryText,
        array $params = null,
        bool $isData = false,
        bool $isList = false,
        string $fieledMessage = "request fieled",
        string $successMessage = "request success"
    ) {
        $response = new Response();
        try {
            $query = $this->database->prepare($queryText);
            if ($query->execute($params)) {
                $response->case = true;
                if ($isData) {
                    if ($isList) {
                        $response->data = $query->fetchAll(PDO::FETCH_OBJ);
                    } else
                        $response->data = $query->fetch(PDO::FETCH_OBJ);
                } else
                    $response->data = $successMessage;
            } else {
                $response->case = false;
                $response->data = $fieledMessage;
            }
        } catch (PDOException $e) {
            $response->case = false;
            $response->data = "$fieledMessage cuse : $e";
        }
        return $response;
    }
}
