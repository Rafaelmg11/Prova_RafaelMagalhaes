<?php
    session_start();
    require_once 'conexao.php';

    if ($_SESSION['perfil'] != 1 && $_SESSION['perfil'] !=2 && $_SESSION['perfil'] !=3){
        echo "<script>alert('Acesso Negado'); window.location.href='principal.php'</script>";
        exit();
    }

    //INICIALIZA A VARIAVEL PARA EVITAR ERROS
    $clientes = [];

    //SE O FORMULARIO FOR ENVIADO, BUSCA O CLIENTE PELO ID OU NOME
    if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['busca'])){
        $busca = trim($_POST['busca']);

        //VERIFICA SE A BUSCA É UM NÚMERO (id OU UM nome)
        if (is_numeric($busca)){
            $sql = "SELECT * FROM cliente WHERE id_cliente = :busca ORDER BY nome_cliente ASC";
            $stmt = $pdo -> prepare($sql);
            $stmt -> bindParam(':busca',$busca, PDO::PARAM_INT);
        }else{
            $sql = "SELECT * FROM cliente WHERE nome_cliente LIKE :busca_nome ORDER BY nome_cliente ASC";
            $stmt = $pdo -> prepare($sql);
            $stmt -> bindValue(':busca_nome', "$busca%", PDO::PARAM_STR);
        }
    }else{
        $sql = "SELECT * FROM cliente ORDER BY nome_cliente ASC";
        $stmt = $pdo -> prepare($sql);
    }

    $stmt -> execute();
    $clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar Cliente</title>
    <link rel="stylesheet" href="styles.css">
    <script src="validacoes.js"></script>
</head>
<body>
    <?php include "barra_menu.php";?>
    <h2>Lista de Clientes</h2>
    <!-- FORMULARIO PARA BUSCAR USUARIOS-->
    <form action="buscar_cliente.php" method="POST" id="form_busca">
        <label for="busca">Digite o ID ou NOME (opcional): </label>
        <input type="text" id="busca" name="busca" pattern="[A-Za-zÀ-ÿ0-9 ]+" title="Não é permitido usar símbolos.">
        <button type="submit">Pesquisar</button>
    </form>    

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