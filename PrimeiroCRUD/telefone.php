<?php
$conexao = mysqli_connect("localhost", "root", "", "school");
$editId = "";
$editCodPhone = "";
$editTelefone = "";
$codTelefone = "";
?>

<html lang="pt-BR">

<head>
    <meta charset="utf-8">
    <title>Números do Usuário</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="./style.css">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

<body>
    <?php

    $Id_User = $_GET['id'];

    $queryTelefone = "SELECT con.Id AS Id_Telefone,stu.Name AS Nome, concat('(',con.AreaCode,')', con.PhoneNumber) AS Telefone
            FROM contacts con
            JOIN students stu ON con.IdStudents=stu.Id
            WHERE con.IdStudents = $Id_User";

    $resultadosTel = mysqli_query($conexao, $queryTelefone);

    echo '<div class="container-fluid">';
    echo '<table class="table table-striped table-dark"> <br>';
    echo '<thead>';
    echo '<tr class="bg-primary"><th scope="col">Nome</th>'
        . '<th scope="col">Telefone</th>'
        . '<th colspan=2 scope="col">Ações</th>'
        . '</tr> </thead> <tbody>';
    while ($row_user = mysqli_fetch_array($resultadosTel)) {
        echo "<tr class='bg-info'><td>$row_user[Nome]</td>"
            . "<td>$row_user[Telefone]</td>"
            . "<td><a href='telefone.php?cod=" . $row_user['Id_Telefone'] . "&id=$Id_User&action=editTelefone' name='btn_edit' id='btn_edit'>Editar Número</a></td>"
            . "<td><a href='telefone.php?cod=" . $row_user['Id_Telefone'] . "&id=$Id_User&action=delTelefone' name='btn_del' id='btn_del'>Deletar Número</a></td></tr>";
    }
    echo "</tbody> </table>";


    if (isset($_GET['cod']) && isset($_GET['action'])) {
        $codTelefone = $_GET['cod'];
        if ($_GET['action'] == "delTelefone") {

            $queryDelTelefone = "DELETE FROM contacts
            WHERE Id = $codTelefone";

            mysqli_query($conexao, $queryDelTelefone);
            echo "Número deletado com sucesso!";

            header("Location: http://localhost/telefone.php?id=$Id_User&action=editTel");
        } else if ($_GET['action'] == 'editTelefone') {

            $queryEditar = mysqli_query($conexao, "SELECT con.AreaCode, con.PhoneNumber, stu.Id AS Id_Cad

            FROM contacts con
                        
            LEFT JOIN students stu on con.IdStudents=stu.Id
                        
            WHERE con.Id= $codTelefone");

            $arrayEdit = mysqli_fetch_array($queryEditar);

            if (mysqli_num_rows($queryEditar)) {
                $editTelefone = $arrayEdit['AreaCode'] . $arrayEdit['PhoneNumber'];
            }
        }
    }


    ?>

    <form action="./addPhone.php" method="post">
        <legend class="form-titulo">Cadastro Telefone</legend>
        <input type="hidden" name="add_id" id="add_id" value="<?= $Id_User ?>">
        <input type="hidden" name="add_cod" id="add_cod" value="<?= $codTelefone ?>">
        <div class="form-group">
            <div class="input-tel">
                <label>Telefone com DDD:</label>
                <input type="text" id="add_tel" name="add_tel" class="form-control" value="<?= $editTelefone ?>" onkeypress="$(this).mask('(00) 90000-0000')">
            </div>
            <br>
            <div class="div-button">
                <button type="submit" name="btn_add" id="btn_add" style="cursor: pointer;" class="btn btn-primary">Adicionar</button>
            </div>
        </div>

    </form>

    <hr>

    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div v-for="job in job">
                    <div class="text-center">
                        <a href="http://localhost/index.php" class="btn btn-primary btn-lg active" role="button" aria-pressed="true">Página Home</a>
                    </div>
                </div>
            </div>
        </div>
    </div>



</body>


</head>

</html>