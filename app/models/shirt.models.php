<?php
require_once 'config.php';
class shirtModel
{
    protected $db;
    public function __construct(){
        // Conectar a la base de datos
        $this->db = new PDO("mysql:host=" . MYSQL_HOST . ";dbname=" . MYSQL_DB . ";charset=utf8", MYSQL_USER, MYSQL_PASS);
        $this->_deploy(); // Llamar a la función _deploy() al construir el modelo
    }
    private function _deploy(){
        $query = $this->db->query('SHOW TABLES');
        $tables = $query->fetchAll();

        // Si no existen tablas, crear las necesarias
        if (count($tables) == 0) {
            $sql =<<<END
                CREATE TABLE IF NOT EXISTS `camiseta` (
                `id_camiseta` int(10) NOT NULL,
                `imagen` varchar(100) NOT NULL,
                `id_equipo` int(10) DEFAULT NULL,
                `temporada` varchar(4) NOT NULL,
                `tipo` text NOT NULL,
                `precio` varchar(10) NOT NULL,
                PRIMARY KEY (`id_camiseta`),
                FOREIGN KEY (`id_equipo`) REFERENCES `equipo`(`id_equipo`)
                );

                CREATE TABLE IF NOT EXISTS `equipo` (
                `id_equipo` int(10) NOT NULL,
                `nombre` text NOT NULL,
                `ciudad` text NOT NULL,
                `estadio` text NOT NULL,
                PRIMARY KEY (`id_equipo`)
                );

                CREATE TABLE IF NOT EXISTS `usuario` (
                    `id` int(11) NOT NULL,
                    `user` varchar(50) NOT NULL,
                    `password` char(200) NOT NULL
                    PRIMARY KEY (`id`)
                );

                -- Añade más tablas aquí si lo necesitas
                END;

            // Ejecutar el SQL para crear las tablas
            $this->db->exec($sql);
            echo "Base de datos desplegada correctamente.\n";
        }
    }

    //Devuelve las camisetas de la base de datos
    function getShirts($orderBy = false)
    {
        $sql = 'SELECT * FROM camiseta';

        if($orderBy){
            switch($orderBy){
                case 'precio':
                    $sql .= ' ORDER BY precio ';
                    break;
            }
        }
        $query = $this->db->prepare($sql);
        $query->execute();
        $shirts = $query->fetchAll(PDO::FETCH_OBJ);

        return $shirts;
    }
    //Devuelve camiseta segun su id
    function getShirtById($id)
    {
        $query = $this->db->prepare('SELECT * FROM camiseta WHERE id_camiseta = ?');
        $query->execute([$id]);
        $shirt = $query->fetch(PDO::FETCH_OBJ);
        return $shirt;
    }
    //Devuelve equipo segun id
    function getTeamById($id)
    {
        $query = $this->db->prepare('SELECT * FROM equipo WHERE id_equipo = :id');
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->execute();
        $team = $query->fetch(PDO::FETCH_OBJ);
        return $team;
    }
    //devuelve equipos
    function getTeams()
    {
        $query = $this->db->prepare('SELECT * FROM equipo');
        $query->execute();
        $teams = $query->fetchAll(PDO::FETCH_OBJ);
        return $teams;
    }
    //agrega camiseta a la base de datos
    function addShirt($image, $id_team, $season, $type, $price)
    {
        $query = $this->db->prepare('INSERT INTO camiseta (imagen, id_equipo ,temporada, tipo, precio) VALUES (?, ?, ?, ?, ?)');
        $query->execute([$image, $id_team, $season, $type, $price]);
        return $this->db->lastInsertId();
    }

    //borra camiseta de la base de datoss
    function deleteShirt($id)
    {
        $query = $this->db->prepare('DELETE FROM camiseta WHERE id_camiseta = ?');
        $query->execute([$id]);
    }

    //Modificar camiseta
    function updateShirt($id, $id_team, $season, $type, $price)
    {
        $query = $this->db->prepare('UPDATE camiseta SET id_equipo = ?, temporada = ?, tipo = ?, precio = ? WHERE id_camiseta = ?');
        $query->execute([$id_team, $season, $type, $price, $id]);
    }
}
