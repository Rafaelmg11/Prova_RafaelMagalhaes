<?php
    session_start();
    require_once 'conexao.php';

    //VERIFICA SE O USUARIO TEM PERMISSÃO
    //SUPONDO QUE O PERFIL 1 SEJA O adm

    if ($_SESSION['perfil']!=1 and $_SESSION['perfil']!=2){
        echo "Acesso Negado!";
        exit();
    }


    if ($_SERVER["REQUEST_METHOD"] == "POST"){
        $nome = $_POST['nome'];
        $endereco = $_POST['endereco'];
        $telefone = $_POST['telefone'];
        $email = $_POST['email'];

        //VERIFICA SE EMAIL JÁ EXISTE
        $ver = $pdo->prepare("SELECT COUNT(*) FROM cliente WHERE email = :email");
        $ver->bindParam(':email', $email);
        $ver->execute();
        $existe = $ver -> fetchAll(PDO:: FETCH_ASSOC);


        if ($existe > 0) {
            echo "<script>alert('Erro: já existe um cliente com esse e-mail.'); window.location.href='cadastro_cliente.php';</script>";
            exit();
        } 

        $sql = "INSERT INTO cliente(nome_cliente, endereco, telefone, email, id_funcionario_responsavel) VALUES (:nome, :endereco, :telefone, :email, :id_funcionario_responsavel)";
        $stmt = $pdo -> prepare($sql);
        $stmt -> bindParam (':nome',$nome);
        $stmt -> bindParam (':endereco',$endereco);
        $stmt -> bindParam (':telefone', $telefone);
        $stmt -> bindParam (':email', $email);
        $stmt -> bindParam (':id_funcionario_responsavel', $id_usuario);

        try{
            $stmt -> execute();
            echo "<script>alert('Cliente Cadastrado com Sucesso!');</script>";
        }catch(PDOException $e){
            echo "<script>alert('Erro ao Cadastrar Cliente! " . $e->getMessage() . "');</script>";
        }
    }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Cliente</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include 'barra_menu.php'?>
    <h2>Cadastro de Cliente</h2>
    <form action="cadastro_cliente.php" method="POST" id="form_nome">
        <label for="nome">Nome: </label>
        <input type="text" id="nome" name="nome" pattern="[A-Za-zÀ-ÿ0-9 ]+" title="Não é permitido usar símbolos."  required>
        
        <label for="endereco">Endereço: </label>
        <input type="text" id="endereco" name="endereco" required>

        <label for="telefone">Telefone: </label>
        <input type="text" id="telefone" name="telefone" required>


        <label for="email">E-mail: </label>
        <input type="email" id="email" name="email" required>

        <button type="submit">Salvar</button>
        <button type="reset">Cancelar</button>
    </form>

    <a href="principal.php" class="voltar">Voltar</a>

    <!--$existe = $ver->fetchColumn();-->
</body>
</html>

