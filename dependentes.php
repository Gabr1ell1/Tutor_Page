<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
</head>
<body>

<?php
include_once 'Conectar.php';
class dependentes
{
    private $id_responsavel;
    private $nome;
    private $nasc;
    private $id_dependente;
    private $cpf;
    private $id_sexo;
    private $tel_emergencia;
    private $endereco;
    private $foto;

    private $conn;

//parte 2. getters settes

public function __construct(){
    $this->conn= Conectar::getInstance();

    if(!$this->conn){
        throw new Exception("Erro ao conectar");
    }
}

// Métodos getters e setters para $id
public function getId_responsavel(){
    return $this ->id_responsavel;
}
public function setId_responsavel($id_responsavel){
    $this->id_responsavel = $id_responsavel;
}

// Métodos getters e setters para $nome
public function getNome(){
    return $this ->nome;
}
public function setNome($nome){
    $this->nome = $nome;
}

// Métodos getters e setters para $cpf
public function getCPF(){
    return $this ->cpf;
}
public function setCPF($cpf){
    $this->cpf = $cpf;
}


// Métodos getters e setters para $telefone
public function getId_dependente(){
    return $this ->id_dependente;
}
public function setId_dependente($id_dependente){
    $this->id_dependente = $id_dependente;
}

// Métodos getters e setters para $nasc
public function getNasc(){
    return $this ->nasc;
}
public function setNasc($nasc){
    $this->nasc = $nasc;
}

// Métodos getters e setters para $genero
public function getId_sexo(){
    return $this ->id_sexo;
}
public function setId_sexo($id_sexo){
    $this->id_sexo = $id_sexo;
}

// Métodos getters e setters para $senha
public function getTel_emergencia(){
    return $this ->tel_emergencia;
}
public function setTel_emergencia($tel_emergencia){
    $this->tel_emergencia = $tel_emergencia;
}

// Métodos getters e setters para $senhaAtual
public function getEndereco(){
    return $this ->endereco;
}
public function setEndereco($endereco){
    $this->endereco = $endereco;
}


// Métodos getters e setters para $senhaAtual
public function getFoto(){
    return $this ->foto;
}
public function setFoto($foto){
    $this->foto = $foto;
}


//Cadastrar responsável
function cadastrar()
{
    try
    {
        $this-> conn = new Conectar();
        $sql = $this->conn->prepare("Insert into dependente values (null,?,?,?,?,?,?,?)");

        @$sql-> bindParam(1, $this->getNome(), PDO::PARAM_STR);
        @$sql-> bindParam(2, $this->getTel_emergencia(), PDO::PARAM_STR);
        @$sql-> bindParam(3, $this->getCPF(), PDO::PARAM_STR);
        @$sql-> bindParam(4, $this->getEndereco(), PDO::PARAM_STR);
        @$sql-> bindParam(5, $this->getNasc(), PDO::PARAM_STR);
        @$sql-> bindParam(6, $this->getFoto(), PDO::PARAM_STR);

        if($sql->execute()==1)
        {
            return "Registro salvo com sucesso";
        }
        $this->conn = null;
    }
    catch(PDOException $exc)
    {
        echo "Erro ao salvar o registro. ".$exc->getMessage();
    }

}

//listar um dependente pelo id
function listar($id_dependente) {
    try {
        // Obtém a instância da conexão
        $conexao = Conectar::getInstance();

        // Prepara a consulta para buscar um único dependente
        $sql = $conexao->prepare("SELECT * FROM dependentes WHERE id_dependente = :id_dependente");
        $sql->bindParam(':id_dependente', $id_dependente, PDO::PARAM_INT);
        $sql->execute();

        // Retorna um único registro
        return $sql->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $exc) {
        echo "Erro ao executar consulta: " . $exc->getMessage();
        return false;
    }
}


public function listarPorResponsavel($id_responsavel) {
    $sql = "SELECT id_dependente, nome FROM dependentes WHERE id_responsavel = :id_responsavel";
   
    if($this->conn){
    $stmt = $this->conn->prepare($sql);
    $stmt->bindParam(':id_responsavel', $id_responsavel);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC); // Retorna todos os dependentes
    }else{
        throw new Exception("FALHOU");
    }
}




public function alterar()
{
    try {
        $this->conn = new Conectar();

        $sql = $this->conn->prepare("UPDATE dependentes SET tel_emergencia = ?, endereco = ? WHERE id_dependente = ?");
        $tel_emergencia = $this->getTel_emergencia();
        $endereco = $this->getEndereco();
        $id_dependente = $this->getId_dependente();
      
        $sql->bindParam(1, $tel_emergencia, PDO::PARAM_STR);
        $sql->bindParam(2, $endereco, PDO::PARAM_STR);
        $sql->bindParam(3, $id_dependente, PDO::PARAM_STR);

        $executado = $sql->execute();
        $this->conn = null;

        if ($executado) {
            return "Registro alterado com sucesso!";
        } else {
            return "Erro ao alterar o registro.";
        }
    } catch (PDOException $exc) {
        echo "Erro ao salvar o registro: " . $exc->getMessage();
    }
}

public function atualizarFotoPerfil($id_dependente, $nomeArquivo)
{
    $this->conn = new Conectar();
    echo "Caminho da nova foto: " . $nomeArquivo; // Debug
    $sql = $this->conn->prepare("UPDATE dependentes SET foto = :foto WHERE id_dependente = :id_dependente");
    $sql->bindParam(':foto', $nomeArquivo, PDO::PARAM_STR);
    $sql->bindParam(':id_dependente', $id_dependente, PDO::PARAM_INT);
    return $sql->execute();
}

public function removerFotoPerfil($id_dependente)
{
    $this->conn = new Conectar();
    
    // Busca o caminho da foto atual
    $sql = $this->conn->prepare("SELECT foto FROM dependentes WHERE  = ?");
    $sql->bindParam(1, $id_dependente, PDO::PARAM_INT);
    $sql->execute();
    $foto = $sql->fetch(PDO::FETCH_ASSOC)['foto'];

    // Se a foto existir no diretório, apaga o arquivo
    if ($foto && file_exists($foto)) {
        unlink($foto); // Remove a foto fisicamente
    }

    // Atualiza o banco de dados, removendo o valor da foto
    $sql = $this->conn->prepare("UPDATE dependentes SET foto = NULL WHERE id_dependente = :id_dependente");
    $sql->bindParam(':id_dependente', $id_dependente, PDO::PARAM_INT);
    return $sql->execute();
}


}
?>
</body>
</html>










</body>
</html>
