<?php
    session_start();
    require_once 'conexao.php';

    //VERIFICA SE O USUARIO TEM PERMISSÃO DE ADM OU DE SECRETÁRIA
    if ($_SESSION['perfil']!=1 and $_SESSION['perfil']!=2) {
        echo "<script>alert('Acesso Negado'); window.location.href='principal.php'</script>";
        exit();
    }

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $id_cliente = $_POST['id_cliente'];
        $nome = $_POST['nome'];
        $endereco = $_POST['endereco'];
        $telefone = $_POST['telefone'];
        $email = $_POST['email'];

        //ATUALIZA OS DADOS DO CLIENTE
        $sql = "UPDATE cliente SET nome_cliente = :nome, endereco = :endereco, telefone = :telefone, email = :email WHERE id_cliente = :id_cliente";
        //$sql = "UPDATE cliente SET nome_cliente = :nome, endereco = :endereco, telefone = :telefone, email = :email, id_funcionario_responsavel = :id_funcionario_responsavel WHERE id_usuario = :id";
        $stmt = $pdo -> prepare($sql);
        $stmt -> bindParam(':nome',$nome);
        $stmt -> bindParam(':endereco',$endereco);
        $stmt -> bindParam(':telefone',$telefone);
        $stmt -> bindParam(':email', $email);
        $stmt -> bindParam(':id_cliente',$id_cliente);

        if ($stmt -> execute()){
            echo "<script>alert('Cliente atualizado com sucesso!'); window.location.href='buscar_cliente.php'</script>";
        }else{
            echo "<script>alert('Erro ao atualizar cliente'); window.location.href='buscar_cliente.php?id=$cliente'</script>";
        }        
    }
?>