<?php 
session_start(); 
include_once 'responsavel.php';
include_once 'dependentes.php';
include_once 'sexo.php';

// Verifica e obtém o ID do responsável
$_SESSION['id_responsavel'] = 1; 
$id_responsavel = $_SESSION['id_responsavel']; 

$r = new responsavel();
$res_bd = $r->listar($id_responsavel); 

if (!$res_bd) {
    echo "Responsável não encontrado.";
    exit;
}

// Obtém a foto do responsável ou usa um valor padrão
$fotoR = !empty($res_bd['foto']) ? $res_bd['foto'] : 'img/hernandez.jpg';
?>

<!------------------------------------------>

<?php

// Verificar se o ID do dependente foi passado na URL
if (isset($_GET['id_dependente'])) {
  $id_dependente = $_GET['id_dependente'];

$dep = new dependentes();
$dep_bd = $dep->listar($id_dependente); 

// Obtendo a descrição do sexo com base no id_sexo
$sexo = "";
if (!empty($dep_bd['id_sexo'])) {
    $sexoObj = new sexo();
    $sexoResult = $sexoObj->listar($dep_bd['id_sexo']);
    $sexo = $sexoResult['sexo'] ?? 'Não informado';
}

// Verifica se o dependente foi encontrado
if (!$dep_bd) {
    echo "Dependente não encontrado.";
    exit;
}

// Processamento do formulário de alteração de dados
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tel_emergencia = $_POST['tel_emergencia'] ?? '';
    $endereco = $_POST['endereco'] ?? '';

    $dep->setTel_emergencia($tel_emergencia);
    $dep->setEndereco($endereco);

    if ($dep->alterar()) {
        echo "<p>Dados alterados com sucesso!</p>";
        header("Location: perfil.php");
        exit;
    } else {
        echo "<p>Erro ao alterar os dados.</p>";
    }
}

// Atualizando a foto do dependente
if (isset($_POST['btnAtualizarFoto']) && isset($_FILES['novaFoto'])) {
    $diretorioDestino = 'img/'; // Diretório onde as fotos serão armazenadas
    $nomeArquivo = $diretorioDestino . basename($_FILES['novaFoto']['name']);

    if (move_uploaded_file($_FILES['novaFoto']['tmp_name'], $nomeArquivo)) {
        $dep->atualizarFotoPerfil($id_dependente, $nomeArquivo);
        echo "Foto de perfil atualizada com sucesso!";
    } else {
        echo "Erro ao fazer upload da foto.";
    }
}

// Processando remoção da foto de perfil
if (isset($_POST['btnRemoverFoto'])) {
    $dep->removerFotoPerfil($id_dependente);
    echo "Foto de perfil removida com sucesso!";
}

// Exibe as informações do dependente
$dep_bd = $dep->listar($id_dependente);
if (!$dep_bd) {
    echo "Dependente não encontrado.";
    exit;
}

// Obtém a foto do dependente ou usa um valor padrão
$foto = !empty($dep_bd['foto']) ? $dep_bd['foto'] : 'img/perfil.jpg';
}
?>


<!DOCTYPE html>
<html>
<head>
  <title>Perfil do dependente</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="CSS/estilo.css">
  <link rel="stylesheet" href="css/perfilD.css">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>
<body>
  <!-- Navbar -->
  <div class="collapse" id="navbarToggleExternalContent" data-bs-theme="dark">
  <div class="bg-primary p-4 itens-nav">
      <ul class="nav">
        <li class="nav-item">
          <a class="nav-link active" href="odontokids/home">Home</a>
        </li>
        <hr class="linha">
        
        <li class="nav-item">
              <a class="nav-link active" href="#">Serviços</a>
            </li>
            <hr class="linha">
            
            <li class="nav-item">
              <a class="nav-link active" href="#">Sobre nós</a>
            </li>
            <hr class="linha">
            
            <li class="nav-item">
              <a class="nav-link active" href="#">Contato</a>
            </li>
          </ul>
        </div>
</div>
<nav class="navbar navbar-dark">
  <div class="container-fluid">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarToggleExternalContent"
    aria-controls="navbarToggleExternalContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  
  <div id="div-logo">
    <h1>Odonto kids</h1>
    <img src="img/Logo.svg" alt="Odonto Kids logo">
  </div>
  
  
  <!-- Botão para abrir o pop-up -->
  <button id="openPopup">
    <img src="<?php echo ($fotoR); ?>" alt="Foto de perfil responsavel" style="border-radius: 50%; height:32px; width:32px; border: 1px solid white">
  </button>
  
    <!-- O pop-up lateral -->
    <div id="popup" class="popup">
      <div class="popup-header">
        <div class="popup-title">Meu Perfil</div>
        <div class="popup-close">✕</div>
      </div>
      
      <div class="popup-content">
        <ul>
          <a href="#">
            <li><span>Verificar Agenda</span></li>
          </a>
          <a href="#">
            <li><span>Notificações</span></li>
          </a>
          <a href="perfilR.php">
            <li><span>Minha Conta</span></li>
          </a>
          <hr>
          <a href="#">
            <li><span>Sair</span></li>
          </a>
        </ul>
      </div>
    </div>
  </div>
</nav>


<!-- Contêiner principal da página -->
<div class="container">
        <!-- Cabeçalho da página -->
        <div class="header">
          <div class="user-info">
              <form id="foto-perfil" method="post" enctype="multipart/form-data">
                <img src="<?php echo $foto; ?>" alt="Foto de perfil" style="width: 150px; height: 150px; border-radius: 50%; border: 1px solid gray">
                <br>
                <h2><?php echo htmlspecialchars($dep_bd['nome']); ?></h2>
              <br>
                <button type="submit" class="btn3" name="btnAtualizarFoto">
                    <i class="fas fa-upload"></i> Atualizar Foto
                </button>
                <button type="submit" class="btn2" name="btnRemoverFoto">
                    <i class="fas fa-trash-alt"></i> Remover Foto
                </button>
            </form>
          </div>
      </div>
          <!-- Seção de abas de navegação -->
          <div class="nav-tabs">
            <div class="nav-tab active" data-tab="exames">Informações pessoais</div>
            <div class="nav-tab" data-tab="diagnosticos">Exames</div>
            <div class="nav-tab" data-tab="prognostico">Prontuários</div>
          </div>

          <!-- Aba de informações -->
          <div class="content" id="exames">
            <div class="content-header">
              <div class="title">Informações do dependente:</div>
            </div>
            <div class="content-list">
              <div class="content-item-details">
              <label for="nome">Nome completo:</label>
                      <input type="text" id="nomeDP" name="nomeDP" class="input" disabled value="<?php echo htmlspecialchars($dep_bd['nome']); ?>" required>
                      <br><br>

                      <label for="email">Email do responsável:</label>
                      <input type="text" id="email" name="email" class="input" disabled value="<?php echo htmlspecialchars($res_bd['email']); ?>" required>
                      <br><br>

                      <label for="Telefone">Telefone de Emergência:</label>
                      <input type="text" id="Telefone" name="Telefone" class="input" disabled value="<?php echo htmlspecialchars($dep_bd['tel_emergencia']); ?>" required>
                      <br><br>

                      <label for="Telefone">Endereço:</label>
                      <input type="text" id="endereco" name="endereco" class="input" disabled value="<?php echo htmlspecialchars($dep_bd['endereco']); ?>" required>
                      <br><br>

                      <label for="nasc">Data de Nascimento:</label>
                      <input type="text" id="nasc" name="nasc" class="input" disabled value="<?php echo htmlspecialchars($dep_bd['nasc']); ?>" required>
                      <br><br>

                      <label for="nasc">Sexo:</label>
                      <input type="text" id="sexo" name="sexo" class="input" disabled value="<?php echo htmlspecialchars($sexo); ?>" required>
              </div>
            </div>
            <br><br>
            <button type="button" class="btn3" onclick="openEditar()">Alterar Informações</button>
          </div>
          <hr>
          <!-- Pop-up para alterar informações -->
          <div class="editar-tela" onclick="closeEditar()" style="display: none;">
          <br>
              <div class="editar-content" onclick="event.stopPropagation()">
                <form action="perfil.php" method="post">                     
                    <label for="TelefoneEm">Telefone de Emergência:</label>
                    <input type="text" id="Telefone" name="Telefone" class="input" value="<?php echo htmlspecialchars($dep_bd['tel_emergencia']); ?>" required>
                    
                    <br><br>

                    <label for="Endereco">Endereço:</label>
                    <input type="text" id="endereco" name="endereco" class="input" value="<?php echo htmlspecialchars($dep_bd['endereco']); ?>" required>
                  
                    <br><br>

                    <button type="submit" class="btn3" name="btnalterar">Salvar Alterações</button> 
                    <button type="button" class="btn3" onclick="closeEditar()">Cancelar</button>
                  </form>
              </div>
          </div>

          <!-- Aba de exame -->
          <div class="content" id="diagnosticos">
            <div class="content-header">
              <div class="title">Exames:</div>
            </div>
            <div class="content-list">
              <div class="content-item">
                <div class="content-item-icon">+</div>
                <div class="content-item-details">
                  Exame Diagnóstico.
                  <br>
                  23/05/2023
                </div>
                <div class="content-item-actions">
                  <button class="download-button">Baixar PDF</button>
                </div>
              </div>
            </div>
          </div>

          <!-- Aba de Prontuários -->
          <div class="content" id="prognostico">
            <div class="content-header">
              <div class="title">Prontuários:</div>
            </div>
            <div class="content-list">
              <div class="content-item-details">
                <p>Não há arquivos</p>
              </div>
            </div>
          </div>

</div>


  <script src="JS/perfilAbas.js"></script>
  <script src="js/popupPerfil.js"></script>
  <script>
    function openEditar() {
        document.querySelector('.editar-tela').style.display = 'block';
    }

    function closeEditar() {
        document.querySelector('.editar-tela').style.display = 'none';
    }
    </script>
</body>
</html>
