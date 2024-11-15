<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil responsável</title>
</head>
<body>

<?php
include_once 'conectar.php';
//parte 1. atributos
class responsavel
{
    private $id_responsavel;
    private $nome;
    private $email;
    private $cpf;
    private $telefone;
    private $nasc;
    private $id_sexo;
    private $senha;
    private $foto;

    private $conn;

//parte 2. getters settes

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
public function setNome($name){
    $this->nome = $name;
}

// Métodos getters e setters para $email
public function getEmail(){
    return $this ->email;
}
public function setEmail($email){
    $this->email = $email;
}


// Métodos getters e setters para $cpf
public function getCPF(){
    return $this ->cpf;
}
public function setCPF($cpf){
    $this->cpf = $cpf;
}


// Métodos getters e setters para $telefone
public function getTelefone(){
    return $this ->telefone;
}
public function setTelefone($telefone){
    $this->telefone = $telefone;
}

// Métodos getters e setters para $nasc
public function getNasc(){
    return $this ->nasc;
}
public function setNasc($nasc){
    $this->nasc = $nasc;
}

// Métodos getters e setters para $id_sexo
public function getId_sexo(){
    return $this ->id_sexo;
}
public function setId_sexo($id_sexo){
    $this->id_sexo = $id_sexo;
}

// Métodos getters e setters para $senha
public function getSenha(){
    return $this ->senha;
}
public function setSenha($senha){
    $this->senha = $senha;
}


// Métodos getters e setters para $foto
public function getFoto(){
    return $this ->foto;
}
public function setFoto($foto){
    $this->foto = $foto;
}

  // Função para verificar a senha atual
  public function verificarSenha($senha) {
    try {
        $this->conn = new Conectar();
        $sql = $this->conn->prepare("SELECT senha FROM responsavel WHERE id_responsavel = ?");
        $sql->bindParam(1, $this->id_responsavel, PDO::PARAM_INT);
        $sql->execute();
        $hashNovaSenha = $sql->fetchColumn();


          /* Exibindo o hash do banco e a senha digitada para depuração
          echo "Hash recuperado do banco: [" . $hashNovaSenha . "]<br>";
          echo "Senha digitada: [" . $senha . "]<br>";*/
  
          // Verifique se o hash foi recuperado corretamente
          if (!$hashNovaSenha) {
              echo "Erro: Hash não encontrado para o id_responsavel.<br>";
              return false;
          }

        if (password_verify($senha, $hashNovaSenha)) {
            return true;
        } else {
            echo "Senha incorreta";
            return false;
        }
    } catch (PDOException $exc) {
        echo "Erro ao verificar senha: " . $exc->getMessage();
        return false;
    }
}

function alterarSenha($id_responsavel, $hashNovaSenha)
{
        try {
            $senha = '123456';
            $hashNovaSenha = password_hash($senha, PASSWORD_DEFAULT);

            $this->conn = new Conectar();
            $sql = $this->conn->prepare("UPDATE responsavel SET senha = ? WHERE id_responsavel = ?");

            $sql->bindParam(1, $hashNovaSenha, PDO::PARAM_STR);
            $sql->bindParam(2, $id_responsavel, PDO::PARAM_INT);

            if ($sql->execute()) {
                return "<p style='text-align: center; color: blue;'> Senha alterada com sucesso! </p>";
                echo $senha;
            } else {
                return "Erro ao alterar a senha.";
            }
        } catch (PDOException $exc) {
            return "Erro: " . $exc->getMessage();
        } 
} 

function listar($id_responsavel) {
    try {
        // Conecta ao banco de dados usando a classe Conectar
        $this->conn = new Conectar();

        // Executa uma consulta SQL para selecionar o e-mail e telefone do responsável
        $sql = $this->conn->prepare("SELECT * FROM responsavel WHERE id_responsavel = :id_responsavel");
        $sql->bindParam(':id_responsavel', $id_responsavel, PDO::PARAM_INT);

        // Executa a consulta SQL e retorna o resultado
        $sql->execute();
        return $sql->fetch(PDO::FETCH_ASSOC);

    } catch(PDOException $exc) {
        echo "Erro ao executar consulta: " . $exc->getMessage();
    } finally {
        $this->conn = null;
    }
}


public function alterar()
{
    try {
        $this->conn = new Conectar();

        $sql = $this->conn->prepare("UPDATE responsavel SET email = ?, telefone = ? WHERE id_responsavel = ?");
        $email = $this->getEmail();
        $telefone = $this->getTelefone();
        $id_responsavel = $this->getId_responsavel();


        $sql->bindParam(1, $email, PDO::PARAM_STR);
        $sql->bindParam(2, $telefone, PDO::PARAM_STR);
        $sql->bindParam(3, $id_responsavel, PDO::PARAM_INT);

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

public function atualizarFotoPerfil($id_responsavel, $nomeArquivo)
{
    $this->conn = new Conectar();
    echo "Caminho da nova foto: " . $nomeArquivo; // Debug
    $sql = $this->conn->prepare("UPDATE responsavel SET foto = :foto WHERE id_responsavel = :id_responsavel");
    $sql->bindParam(':foto', $nomeArquivo, PDO::PARAM_STR);
    $sql->bindParam(':id_responsavel', $id_responsavel, PDO::PARAM_INT);
    return $sql->execute();
}

public function removerFotoPerfil($id_responsavel)
{
    $this->conn = new Conectar();
    
    // Busca o caminho da foto atual
    $sql = $this->conn->prepare("SELECT foto FROM responsavel WHERE id_responsavel = ?");
    $sql->bindParam(1, $id_responsavel, PDO::PARAM_INT);
    $sql->execute();
    $foto = $sql->fetch(PDO::FETCH_ASSOC)['foto'];

    // Se a foto existir no diretório, apaga o arquivo
    if ($foto && file_exists($foto)) {
        unlink($foto); // Remove a foto fisicamente
    }

    // Atualiza o banco de dados, removendo o valor da foto
    $sql = $this->conn->prepare("UPDATE responsavel SET foto = NULL WHERE id_responsavel = :id_responsavel");
    $sql->bindParam(':id_responsavel', $id_responsavel, PDO::PARAM_INT);
    return $sql->execute();
}



}
?>
</body>
</html>










</body>
</html>