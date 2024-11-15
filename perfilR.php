<?php 
session_start(); 
include 'conectar.php';
include 'dependentes.php';
include 'responsavel.php';
include 'sexo.php';

// Definir IDs de sessão para o responsável e dependente
$_SESSION['id_responsavel'] = 1; 
$id_responsavel = $_SESSION['id_responsavel'];


$_SESSION['id_dependente'] = 1; 
$id_dependente = $_SESSION['id_dependente']; 

// **Listar informações do dependente**
$dep = new Dependentes();
$dep_bd = $dep->listar($id_dependente);

if (!$dep_bd) {
    echo "Não há dependentes cadastrados.";
    exit;
}

// **Listar informações do responsável**
$responsavel = new Responsavel();
$res_bd = $responsavel->listar($id_responsavel);

if (!$res_bd) {
    echo "Responsável não encontrado.";
    exit;
}

// Obter a descrição do sexo do responsável
$sexo = "";
if (!empty($res_bd['id_sexo'])) {
    $sexoObj = new Sexo();
    $sexoResult = $sexoObj->listar($res_bd['id_sexo']);
    $sexo = $sexoResult['sexo'] ?? 'Não informado';
}

        //alterar dados
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btnalterar'])) {
            $nome = $_POST['nome'];
            $email = $_POST['email'];
            $telefone = $_POST['telefone'];
            $id_sexo = $_POST['id_sexo'];

            $responsavel->setId_responsavel($id_responsavel);
            $responsavel->setNome($nome);
            $responsavel->setEmail($email);
            $responsavel->setTelefone($telefone);
            $responsavel->setId_sexo($id_sexo);

            if ($responsavel->alterar()) {
                echo "<p>Dados alterados com sucesso!</p>";
                header("Location: perfilR.php");
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
                $res->atualizarFotoPerfil($id_responsavel, $nomeArquivo);
                echo "Foto de perfil atualizada com sucesso!";
            } else {
                echo "Erro ao fazer upload da foto.";
            }
        }

        // Processando remoção da foto de perfil
        if (isset($_POST['btnRemoverFoto'])) {
            $responsavel->removerFotoPerfil($id_responsavel);
            echo "Foto de perfil removida com sucesso!";
        }

        // Exibe as informações do dependente
        $res_bd = $responsavel->listar($id_responsavel);
        if (!$res_bd) {
            echo "Dependente não encontrado.";
            exit;
        }

    // Obtém a foto do dependente ou usa um valor padrão
    $foto = !empty($res_bd['foto']) ? $res_bd['foto'] : 'img/hernandez.jpg';


    //lista todos os dep
    $dep_bd = $dep->listarPorResponsavel($id_responsavel);

?>




<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil responsável</title>
    <!--CSS para o popUp-->
    <link rel="stylesheet" href="CSS/estilo.css">
    <link rel="stylesheet" href="css/perfilR.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
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
             <img src="<?php echo $foto; ?>" alt="Foto de perfil" height="32px" width="32px" style="border-radius: 50%; border: 1px solid white">
            </button>
            
            <!-- O pop-up lateral -->
            <div id="popup" class="popup">
                <!-- Cabeçalho do pop-up -->
                <div class="popup-header">
                    <div class="popup-title">
                        <img src="img/Logo.svg" alt="Odonto Kids logo" width="22px" height="22px"> Meu perfil
                    </div>
                    <!-- Botão para fechar o pop-up -->
                    <button class="popup-close" onclick="closePopup()">✕</button>
                </div>
                
                <!-- Conteúdo do pop-up -->
                <div class="popup-content">
                    <ul>
                        <li><a href="#"><span>Agendamentos</span></a></li>
                        <li><a href="#"><span>Prontuários</span></a></li>
                        <li><a href="perfilR.php"><span>Minha Conta</span></a></li>
                        <hr>
                        <li><a href="#"><span>Sair</span></a></li>
                    </ul>
                </div>
</div>

    </div>
 </nav>

 <!--CONTEUDO-->
 <div class="container">
        <h3 class="titulo">Seja bem-vindo(a), <?php echo htmlspecialchars($res_bd['nome']); ?></h3> 
        <header>
            <form id="foto-perfil" method="post" enctype="multipart/form-data">
                <img src="<?php echo $foto; ?>?timestamp=<?php echo time(); ?>" alt="Foto de perfil" style="width: 150px; height: 150px; border-radius: 50%; border: 1px solid gray">
                <br><br>
                <!--<label for="novaFoto">Alterar foto de perfil:</label>
                <input type="file" name="novaFoto" id="novaFoto" accept="image/*">
    -->
                <button type="submit" class="btn3" name="btnAtualizarFoto">
                    <i class="fas fa-upload"></i> Atualizar Foto
                </button>
                <button type="submit" class="btn2" name="btnRemoverFoto">
                    <i class="fas fa-trash-alt"></i> Remover Foto
                </button>
            </form>
        </header>
        
        <!-- Seção de abas de navegação  -->
        <div class="nav-tabs">
            <div class="nav-tab" data-tab="dados">Dados e Privacidade</div>
            <div class="nav-tab active" data-tab="config">Editar Perfil</div>
            <div class="nav-tab" data-tab="dependentes">Cadastrar Dependentes</div>
            <div class="nav-tab" data-tab="viewdepend">Dependentes</div>
        </div>


            <!-- Dados e Privacidade -->
              <div class="tab-content">
                <!-- ABA DE privacidade -->
                <div class="content" id="dados">
                    <div class="content-header">
                        <div class="title">Dados e Privacidade</div>
                    </div>
                    <br><br>
                    <!-- informações sobre privacidade -->
                    <p>Informações pessoais que você salvou na conta, como seu aniversário ou endereço de e-mail, e opções para gerenciá-las. Esses dados são particulares, não serão publicados!
                    A sua privacidade e a segurança dos seus dados são extremamente importantes para nós. No nosso sistema de agendamento, coletamos e armazenamos apenas as informações necessárias para garantir um atendimento eficiente e seguro. Isso inclui dados como nome, informações de contato, dados de agendamento e, quando aplicável, informações sobre dependentes.
<br><br>
                    Como usamos seus dados:
                    <ul>
                    <li>Agendamentos e Atendimento: Utilizamos as informações fornecidas para processar os agendamentos, confirmar consultas, e garantir que o atendimento seja realizado conforme solicitado.</li>
                    <li>Comunicações: Seus dados de contato (como e-mail e telefone) serão usados para enviar confirmações de agendamento, lembretes e atualizações importantes.</li>
                    <li>Segurança e Suporte: Seus dados são armazenados de maneira segura, com acesso restrito aos profissionais responsáveis pelo seu atendimento, garantindo a confidencialidade e a integridade das informações.</li>
                    </ul>
                    Não compartilhamos suas informações pessoais com terceiros sem o seu consentimento, exceto quando necessário para cumprir com obrigações legais ou fornecer o serviço de maneira adequada.
                    Em caso de dúvidas nos contate por meio de nosso canais de atendimento!
                    </p>

                    <p>Entendemos a importância da segurança da sua conta. Se você deseja alterar sua senha, basta clicar em 'Alterar senha'. A troca de senha é uma maneira simples e eficaz de proteger o acesso à sua conta e garantir que suas informações pessoais permaneçam seguras. <a href="alterarS.php">Alterar Senha</a>
                    </p>
                </div>
            </div>


            <!-- Conteúdos de cada aba -->
            <div class="tab-content">
                <!-- ABA DE Editar Perfil -->
                <div class="content" id="config">
                    <div class="content-header">
                        <div class="title">Editar Perfil</div>
                    </div>
                    <br><br>
                    <div class="sobre">
                        <h2 class="titulo">Sobre</h2>
                
                    <label for="nome">Nome completo:</label>
                    <input type="text" class="input" name="nome" id="nome" disabled value="<?php echo htmlspecialchars($res_bd['nome']); ?>"><br><br>
                
                    <label for="email">Email:</label> 
                    <input type="text" class="input" name="email" id="email" disabled value="<?php echo htmlspecialchars($res_bd['email']); ?>"><br><br>

                    
                    <label for="cpf">CPF:</label>
                    <input type="text" class="input" name="cpf" id="cpf" disabled value="<?php echo htmlspecialchars($res_bd['cpf']); ?>"><br><br>
            
                    <label for="tel">Telefone:</label>
                    <input type="text" class="input" name="tel" id="tel" disabled value="<?php echo htmlspecialchars($res_bd['telefone']); ?>"><br><br>
                    
                
                    <label for="nasc">Data de nascimento:</label>
                    <input type="text" class="input" disabled value="<?php echo htmlspecialchars($res_bd['nasc']); ?>"><br><br>
                

                    <label for="sexo">Sexo:</label>
                    <input type="text" class="input" name="sexo" id="sexo" disabled value="<?php echo htmlspecialchars($sexo); ?>">
                </div>
        
<br><br>
<hr>
            <h2 class="titulo">Alterar informações de contato</h2>
            <p>Os dados como nome, endereço e outros detalhes pessoais são fundamentais para a correta identificação e registro dentro do sistema, e qualquer alteração nesses dados pode afetar o relacionamento e a correspondência com os registros da plataforma. Já o e-mail e o telefone são informações de contato direto, que podem ser atualizadas sem comprometer a estrutura básica do perfil, desde que o novo e-mail ou telefone esteja válido para garantir que as notificações e comunicações sejam corretamente enviadas. Caso queira alterar algum dado clique em 'Alterar informações'.</p>
<br>
                <button type="button" class="btn3" onclick="openEditar()">Alterar Informações</button>
<br><br><br>
            <!-- Pop-up para alterar informações -->
            <div class="editar-tela" onclick="closeEditar()" style="display: none;">
                <div class="editar-content" onclick="event.stopPropagation()">
                    <h4>Alterar Informações de Contato</h4>
                    <br>
                    <!-- Formulário de edição -->
                    <form action="perfilR.php" method="post">
                        <label for="edit-email">Email:</label>
                        <input type="email" class="input" name="email" id="edit-email" required value="<?php echo htmlspecialchars($res_bd['email']); ?>"><br><br>

                        <label for="edit-telefone">Telefone:</label>
                        <input type="tel" class="input" name="telefone" id="edit-telefone" required value="<?php echo htmlspecialchars($res_bd['telefone']); ?>"><br><br>

                        <button type="submit" class="btn3" name="btnalterar">Salvar Alterações</button>
                        <button type="button" class="btn3" onclick="closeEditar()">Cancelar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
 
     <!-- ABA DE Cadastrar Dependentes -->
     <div class="content" id="dependentes">
                <div class="content-header">
                    <div class="title">Cadastrar Dependente</div>
                </div><br>
                <p>Fazendo o cadastro da sua criança e disponibilizando suas informações médicas aqui, permiti que você:
                    <ul>
                        <li>Acesso Rápido a Informações Médicas: Permite que médicos e outros profissionais de saúde tenham acesso imediato ao histórico médico da criança, incluindo alergias, vacinas, medicamentos em uso, condições crônicas, entre outros dados relevantes, facilitando o atendimento em emergências ou consultas.</li><br>
                        
                        <li>Agendamento e Gestão de Consultas: Facilita o agendamento de consultas e o acompanhamento de tratamentos, exames e vacinas, garantindo que todas as necessidades médicas da criança sejam gerenciadas de maneira eficiente.</li><br>
                        
                        <li>Comunicação com Profissionais de Saúde: Possibilita uma comunicação mais direta e rápida com os profissionais de saúde, permitindo o envio de atualizações, relatórios médicos ou pedidos de informação.</li><br>
                        
                        <li>Acesso a Documentos Médicos: Facilita o acesso a documentos médicos importantes, como prescrições, laudos de exames e relatórios médicos, que podem ser necessários em diferentes situações, como viagens, mudanças de escola ou acompanhamento por especialistas.</li>
                    </ul>
                </p>
                <button type="submit" class="btn3">Cadastrar</button>
        </div>

        <!-- ABA DE dependentes -->
        <div class="content" id="viewdepend">
            <div class="content-header">
            <div class="title">Dependentes</div>
            </div>
            <br>
            <!-- Listar todos os dependentes -->
        <?php
            if($dep_bd){
                foreach($dep_bd as $dep_mostrar){
                    echo '<div class="content-item">
                    <div class="content-item-icon">
                            <img src="img/Logo.svg" alt="Odonto Kids logo" class="logo-icon">
                        </div>
                    <div class="content-item-details">
                        ' . htmlspecialchars($dep_mostrar['nome']) . '
                        <br>
                    </div>
            <a href="perfil.php?id_dependente=' . $dep_mostrar['id_dependente'] . '"><button class="info" type="button">Visualizar perfil</button></a>      </div>';
                }
            }else{
                    echo "Nenhum dependente cadastrado";
                }
        ?>   
    </div>



</div>

<br><br><br><br><br><br><br><br><br>


<!--FOOTER-->
<section class="footer">
    <div class="box-container">
        <div class="box">
            <h3>Endereço</h3>
            <p>Endereço: 619 Albuquerque Travessa - Tucano, PI / 60960-761<br>CNPJ: n° 87.313.818/0001-42</p>
        </div>
        <div class="box">
            <h3>E-mail</h3>
            <a href="#" class="link">OdontoKids@gmail.com</a>
            <a href="#" class="link">SuporteOdontoKids@hotmail.com</a>
        </div>
        <div class="box">
            <h3>Ligue</h3>
            <p>+55 0000-0000</p>
            <p>+55 0000-0000</p>
        </div>
    </div>
    <div class="credit">Copyright © 2023 Odonto Kids LTDA</div>
</section>


<!--Script para o popUp-->
<script src="JS/popupPerfil.js"></script>
<script src="JS/perfilAbas.js"></script>
<script>
    function openEditar() {
        document.querySelector('.editar-tela').style.display = 'block';
    }

    function closeEditar() {
        document.querySelector('.editar-tela').style.display = 'none';
    }</script>
</body>
</html>
