<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
</head>
<body>

<?php
include_once 'conectar.php';
//parte 1. atributos
class sexo
{
    private $id_sexo;
    private $sexo;
 
    private $conn;


// Métodos getters e setters para $genero
public function getId_sexo(){
    return $this ->id_sexo;
}
public function setId_sexo($id_sexo){
    $this->id_sexo = $id_sexo;
}

// Métodos getters e setters para $senha
public function getSexo(){
    return $this ->sexo;
}
public function setSexo($sexo){
    $this->sexo = $sexo;
}
//pegar sexo pela id
function listar($id_sexo) {
    try {
        $this->conn = new Conectar();

        $sql = $this->conn->prepare("SELECT * FROM sexo WHERE id_sexo = :id_sexo");
        $sql->bindParam(':id_sexo', $id_sexo, PDO::PARAM_INT);

        $sql->execute();
        return $sql->fetch(PDO::FETCH_ASSOC);

         // Depuração
         echo "ID Sexo: $id_sexo<br>";
         var_dump($result);
 
         return $result;

    } catch(PDOException $exc) {
        echo "Erro ao executar consulta: " . $exc->getMessage();
    } finally {
        $this->conn = null;
    }
}
}
?>
</body>
</html>










</body>
</html>