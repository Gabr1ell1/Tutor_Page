<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <link rel="stylesheet" href="css/estilo.css">
    <style>
    .modal {
        font-size: 1.3rem !important;;
        position: fixed; 
        z-index: 1; 
        left: 0;
        top: 0;
        width: 100%; 
        height: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .modal-conteudo {
        margin: 15% auto;
        background-color: #fff;
        justify-content: center;
        align-items: center;
        border: 1px solid var(#4a90e2);
        width: 80%;
        max-width: 400px;
    
    }
    </style>
</head>
<body>
<div id="modalAlterarSenha" class="modal">
    <div class="modal-conteudo">
        <center><legend><b>Alterar Senha</b></legend></center>

        <form name="responsavel" action="" method="post">
            <p>Senha Atual: 
                <input name="senha" type="text" class="input" size="12" placeholder="Preencha aqui" required>
            </p><br>
            <p>Nova Senha: 
                <input name="novaSenha" type="text" class="input" size="12" placeholder="Preencha aqui" required>
            </p><br>
            <p>Confirmar Senha: 
                <input name="confirmarSenha" type="text" class="input" size="12" placeholder="Preencha aqui" required>
            </p>
            <br>

            <input name="btnsalvar" class="btn3" type="submit" value="Salvar">
            <a href="perfilR.php"><input name="limpar" class="btn3" value="Voltar" type="button"></a>
        </form>
   <br><br><br>

        <?php
        if (isset($_POST['btnsalvar'])) {
            include_once 'responsavel.php';
            
            session_start();
            $_SESSION['id_responsavel'] = 1; 
            $id_responsavel = $_SESSION['id_responsavel']; 

            $senha = $_POST['senha'];
            $novaSenha = $_POST['novaSenha'];
            $confirmarSenha = $_POST['confirmarSenha'];
            
           
            $senha = '123456';
            $hashNovaSenha = password_hash($senha, PASSWORD_DEFAULT);

            if ($novaSenha !== $confirmarSenha) {
                echo "<h3 style='color:red;'>As senhas não coincidem. Por favor, tente novamente.</h3>";
                exit;
        }else{
            
            $r = new Responsavel();
            $r->setId_responsavel($id_responsavel);

            //verificar senha atual
        if ($r->verificarSenha($senha)) 
        {
                $hashNovaSenha = password_hash($novaSenha, PASSWORD_DEFAULT); 
                $resultado = $r->alterarSenha($id_responsavel, $hashNovaSenha);
                echo $resultado;
            } 
            }
        }
        ?>
    </div>
</div>

</body>
</html>