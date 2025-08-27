<?php
    session_start();
    require_once 'conexao.php';

    //VERIFICA SE O USUARIO TEM PERMISSÃO
    //SUPONDO QUE O PERFIL 1 SEJA O adm

    if($_SESSION['perfil']!=1){
        echo "Acesso Negado!";
        exit();
    }
    
    if($_SERVER["REQUEST_METHOD"]=="POST"){
        $nome = $_POST['nome'];
        $email = $_POST['email'];
        $senha = password_hash($_POST['senha'],PASSWORD_DEFAULT);
        $id_perfil = $_POST['id_perfil'];

        $sql = "INSERT INTO usuario(nome, email, senha, id_perfil) VALUES (:nome, :email, :senha, :id_perfil)";
        $stmt = $pdo -> prepare($sql);
        $stmt -> bindParam(':nome', $nome);
        $stmt -> bindParam(':email', $email);
        $stmt -> bindParam(':senha', $senha);
        $stmt -> bindParam(':id_perfil', $id_perfil);

        try{
            $stmt -> execute();
            echo "<script>alert('Usuário Cadastrado com Sucesso!');</script>";
        } catch (PDOException $e){
            echo "<script>alert('Erro ao Cadastrar o Usuário. Email não pode ser repetido');</script>";
        }
    }

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Usuário</title>
    <link rel="stylesheet" href="styles.css">
    
</head>
<body>
    <?php include "barra_menu.php"?>
    <h2>Cadastrar Usuário</h2>
    <form action="cadastro_usuario.php" method="POST" id="form_nome" >
        <label for="nome">Nome: </label>
        <input type="text" id="nome" name="nome" pattern="[A-Za-zÀ-ÿ0-9 ]+" title="Não é permitido usar símbolos."  placeholder="Digite o nome" required>

        <label for="email">E-mail: </label>
        <input type="email" id="email" name="email" required placeholder="Digite o E-mail">

        <label for="senha">Senha: </label>
        <input type="password" id="senha" name="senha" required placeholder="Digite a senha">

        <label for="id_perfil">Perfil: </label>
        <select name="id_perfil" id="id_perfil">
            <option value="1">Administrador</option>
            <option value="2">Secretaria</option>
            <option value="3">Almoxarife</option>
            <option value="4">Cliente</option>
        </select>

        <button type="submit">Salvar</button>
        <button type="reset">Cancelar</button>
    </form>    

    <a href="principal.php" class="voltar">Voltar</a>

    <script src="validacoes.js"></script>
</body>
</html>
