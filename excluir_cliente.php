<?php
    session_start();
    require_once 'conexao.php';

    //VERIFICA SE O USUARIO TEM PERMISSÃO
    //SUPONDO QUE O PERFIL 1 SEJA O adm

    if ($_SESSION['perfil']!=1) {
        echo "<script>alert('Acesso Negado'); window.location.href='principal.php'</script>";
        exit();
    }

    //INICIALIZA AS VARIAVEIS
    $cliente = null;

    //BUSCA TODOS OS CLIENTES CADASTRADOS EM ORDEM ALFABETICA
    $sql = "SELECT * FROM cliente ORDER BY nome_cliente ASC";
    $stmt = $pdo -> prepare($sql);
    $stmt -> execute();
    $clientes = $stmt -> fetchAll(PDO:: FETCH_ASSOC);

    //SE UM ID FOR PASSADO VIA GET, EXCLUI O cliente
    if (isset($_GET['id']) && is_numeric($_GET['id'])){
        $id_cliente = $_GET['id'];

        //EXCLUI O CLIENTE DO BANCO DE DADOS
        $sql = "DELETE FROM cliente WHERE id_cliente = :id";
        $stmt = $pdo -> prepare($sql);
        $stmt -> bindParam(':id', $id_cliente,PDO::PARAM_INT);

        if ($stmt -> execute()){
            echo "<script>alert('Cliente excluido com Sucesso!'); window.location.href='excluir_cliente.php'</script>";
        }else{
            echo "<script>alert('Erro ao excluir cliente'); window.location.href='excluir_cliente.php'</script>";
        }
    }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excluir Cliente</title>
    <link rel="stylesheet" href="styles.css">

</head>
<body>
    <?php include "barra_menu.php"?>
    <h2>Excluir Cliente</h2>

    <?php if(!empty($clientes)):?>
        <div class="tabela-container">
            <table border="1" class="listar-tabela">
                <tr>
                    <th>Id</th>
                    <th>Nome</th>
                    <th>Endereco</th>
                    <th>Telefone</th>
                    <th>Email</th>
                    <th>Ações</th>
                </tr>


            <?php foreach ($clientes as $cliente):?>
                <tr>
                    <td><?=htmlspecialchars($cliente['id_cliente'])?></td>
                    <td><?=htmlspecialchars($cliente['nome_cliente'])?></td>
                    <td><?=htmlspecialchars($cliente['endereco'])?></td>
                    <td><?=htmlspecialchars($cliente['telefone'])?></td>
                    <td><?=htmlspecialchars($cliente['email'])?></td>
                    <td><a href="excluir_cliente.php?id=<?=htmlspecialchars($cliente['id_cliente'])?>" onclick="return confirm('Tem certeza que deseja excluir este cliente?')">Excluir</a></td>
                </tr>
            <?php endforeach;?>
            </table>
        </div>

    <?php else:?>
        <p>Nenhum Cliente encontrado</p>
    <?php endif;?>

    <a href="principal.php" class="voltar">Voltar</a>
        
</body>
</html>