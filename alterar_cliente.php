<?php
    session_start();
    require_once 'conexao.php';

        //VERIFICA SE O USUARIO TEM PERMISSÃO DE ADM OU DE SECRETÁRIA
        if ($_SESSION['perfil']!=1 and $_SESSION['perfil']!=2) {
            echo "<script>alert('Acesso Negado'); window.location.href='principal.php'</script>";
            exit();
        }

    //INICIALIZA AS VARIAVEIS
    $cliente = null;

    //SE O FORMULARIO FOR ENVIADO, BUSCA O CLIENTE PELO ID OU NOME
    if ($_SERVER["REQUEST_METHOD"] == "POST"){
        if (!empty($_POST['busca_cliente'])){
            $busca = trim($_POST['busca_cliente']);

            //VERIFICA SE A BUSCA É UM NÚMERO(id) OU UM nome
            if (is_numeric($busca)){
                $sql = "SELECT * FROM cliente WHERE id_cliente = :busca";
                $stmt = $pdo -> prepare($sql);
                $stmt -> bindParam(':busca',$busca, PDO::PARAM_INT);
            }else{
                $sql = "SELECT * FROM cliente WHERE nome_cliente LIKE :busca_nome";
                $stmt = $pdo -> prepare($sql);
                $stmt -> bindValue('busca_nome', "$busca%", PDO::PARAM_STR);
            }

            $stmt -> execute();
            $cliente = $stmt -> fetch(PDO::FETCH_ASSOC);

            //SE O CLIENTE NÃO FOR ENCONTRADO, EXIBE UM ALERTA
            if (!$cliente){
                echo "<script>alert('Cliente não encontrado!');</script>";
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alterar Cliente</title>
    <link rel="stylesheet" href="styles.css">
    <script src="scripts.js"></script>
    <script src="validacoes.js"></script>
</head>
<body>
    <?php include "barra_menu.php"?>
    <h2>Alterar Cliente</h2>
    <!-- FORMULARIO PARA BUSCAR CLIENTES-->
    <form action="alterar_cliente.php" method="POST">
        <label for="busca_cliente">Digite o ID ou NOME do cliente: </label>
        <input type="text" id="busca_cliente" name="busca_cliente" required onkeyup="buscarSugestoes()">

        <div id="sugestoes"></div>
        <button type="submit">Buscar</button>
    </form>

    <?php if ($cliente):?>
        <form action="processa_alteracao_cliente.php" method="POST" id="form_nome">
            <input type="hidden" name="id_cliente" value="<?= htmlspecialchars($cliente['id_cliente']) ?>"> 

            <label for="nome">Nome:</label>
            <input type="text" name="nome" id="nome" value="<?=htmlspecialchars($cliente['nome_cliente'])?> " pattern="[A-Za-zÀ-ÿ0-9 ]+" title="Não é permitido usar símbolos." required> 

            <label for="endereco">Endereço:</label>
            <input type="text" name="endereco" id="endereco" value="<?=htmlspecialchars($cliente['endereco'])?>" required> 

            <label for="telefone">Telefone:</label>
            <input type="text" name="telefone" id="telefone" value="<?=htmlspecialchars($cliente['telefone'])?>" required> 

            <label for="email">Email:</label>
            <input type="email" name="email" id="email" value="<?=htmlspecialchars($cliente['email'])?>" required> 


            <button type="submit">Alterar</button>
            <button type="reset">Cancelar</button>
        </form>
    <?php endif;?>

    <a href="principal.php" class="voltar">Voltar</a>

    
</body>
</html>