<?php
    session_start();
    require_once 'conexao.php';

    //VERIFICA SE O USUARIO TEM PERMISSÃO DE ADM 
    if ($_SESSION['perfil']!= 1){
        echo "<script>alert('Acesso Negado'); window.location.href='principal.php'</script>";
        exit();
    }

    //INICIALIZA AS VARIAVEIS
    $usuario = null;

    //SE O FORMULARIO FOR ENVIADO, BUSCA O USUARIO PELO ID OU NOME
    if ($_SERVER["REQUEST_METHOD"] == "POST"){
        if (!empty($_POST['busca_usuario'])){
            $busca = trim($_POST['busca_usuario']);

            //VERIFICA SE A BUSCA É UM NÚMERO (id OU UM nome)
            if (is_numeric($busca)){
                $sql = "SELECT * FROM usuario WHERE id_usuario = :busca";
                $stmt = $pdo -> prepare($sql);
                $stmt -> bindParam(':busca', $busca, PDO::PARAM_INT);
            }else{
                $sql = "SELECT * FROM usuario WHERE nome LIKE :busca_nome";
                $stmt = $pdo -> prepare($sql);
                $stmt -> bindValue(':busca_nome', "%$busca%",PDO::PARAM_STR);
            }

            $stmt -> execute();
            $usuario = $stmt -> fetch(PDO::FETCH_ASSOC);

            //SE O USUARIO NÃO FOR ENCONTRADO, EXIBE UM ALERTA
            if (!$usuario){
                echo "<script>alert('Usuario não encontrado!');</script>";
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alterar Usuario</title>
    <link rel="stylesheet" href="styles.css">
    <!--Certifique-se de que o JavaScript está sendo carregado corretamente-->
    <script src="scripts.js"></script>
    <script src="validacoes.js"></script>
    
</head>
<body>
    <?php include "barra_menu.php"?>
    <h2>Alterar Usuário</h2>
    <!-- FORMULARIO PARA BUSCAR USUARIOS-->
    <form action="alterar_usuario.php" method="POST">
        <label for="busca_usuario">Digite o ID ou NOME do usuário: </label>
        <input type="text" id="busca_usuario" name="busca_usuario" required onkeyup="buscarSegestoes()">

        <div id="sugestoes"></div>
        <button type="submit">Buscar</button>
    </form>

    <?php if ($usuario):?>
        <form action="processa_alteracao_usuario.php" method="POST" id="form_nome">
            <input type="hidden" name="id_usuario" value="<?= htmlspecialchars($usuario['id_usuario']) ?>"> 

            <label for="nome">Nome:</label>
            <input type="text" name="nome" id="nome" value="<?=htmlspecialchars($usuario['nome'])?> " pattern="[A-Za-z0-9 ]+" title="Não é permitido usar símbolos." required> 

            <label for="email">Email:</label>
            <input type="email" name="email" id="email" value="<?=htmlspecialchars($usuario['email'])?>" required> 

            <label for="id_perfil">Perfil:</label>
            <select id="id_perfil" name="id_perfil">
                <option value="1" <?=$usuario['id_perfil'] == 1 ? 'selected': ''?>>Administrador</option>
                <option value="2" <?=$usuario['id_perfil'] == 2 ? 'selected': ''?>>Secretaria</option>
                <option value="3" <?=$usuario['id_perfil'] == 3 ? 'selected': ''?>>Almoxarife</option>
                <option value="4" <?=$usuario['id_perfil'] == 4 ? 'selected': ''?>>Cliente</option>
            </select>

            <!--SE O USUARIO LOAGADO FOR adm, EXIBIR OPÇÃO DE ALTERAR senha-->
            <?php if($_SESSION['perfil'] == 1):?>
                <label for="nova_senha">Nova Senha:</label>
                <input type="password" name="nova_senha" id="nova_senha"> 
            <?php endif;?>

            <button type="submit">Alterar</button>
            <button type="reset">Cancelar</button>
        </form>
    <?php endif;?>

    <a href="principal.php" class="voltar">Voltar</a>
  
    
</body>
</html>