<?php
require_once 'config.php';
class userModel
{
    protected $db;
    public function __construct()
    {
        $this->db = new PDO(
            "mysql:host=" . MYSQL_HOST . ";dbname=" . MYSQL_DB . ";charset=utf8", MYSQL_USER, MYSQL_PASS
        );
    }
    function getUserByUsername($username)
    {
        $query = $this->db->prepare('SELECT * FROM usuario WHERE user = ?');
        $query->execute([$username]);
        $user = $query->fetch(PDO::FETCH_OBJ);
        return $user;
    }
}
