<?php
// Toda variavel começa com $ e sempre no final de cada script o sinal de ; (ponto e virgula).
// A variavel $conexao, como o próprio nome diz, é a conexão com o servidor local 'school'.
$conexao = mysqli_connect('localhost', 'root', '', 'school');
$editId = "";
$editName = "";
$editCPF = "";
$editEmail = "";
$editCEP = "";
$editUF = "";
$editTelefone = "";
$ordenacao = ' GROUP BY
stu.Name ORDER BY stu.Id';
$filtrar = "";
$queryFiltrar = "";
$list_users = "";
?>

<head>
    <meta charset='utf-8'>
    <title>CRUD com Bootstrap</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="./style.css">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

    <script>
        // Expressões Regulares:
        // g - global (encontra todas as ocorrências)
        // i - insensitive
        // (()()) - grupos (parenteses)
        // | - ou
        // (...)(...)(...) $1 $2 $3 - representando seus respectivos grupos
        // Quantificadores:
        // * (opcionais) 0 ou N {0,}
        // + (obrigatório) 1 ou N {1,}
        // ? (opcionais) 0 ou 1 {0, 1}
        // . - ele significa qualquer caractere
        // {n, m} = {minimo, maximo}
        // \ - caractere de escape
        // [] - conjunto, encontra qualquer coisa dentro desse conjunto
        // ^ - negação
        // RANGE -> [0,9] -> não pode ser do maior para o menor


        function validarForm() {

            var nome = document.getElementById('add_nome').value;
            var cpf = document.getElementById('add_cpf').value;
            var email = document.getElementById('add_email').value;
            var cep = document.getElementById('add_cep').value;
            var uf = document.getElementById('add_uf').value;

            if (nome == "" || nome < 3) {
                alert("Preencha o campo nome.");
                formulario.add_nome.focus();
                return false;
            } else if (cpf.length == "" || cpf.length != 14) {
                alert("Preencha corretamente o CPF.");
                formulario.add_cpf.focus();
                return false;
            } else if (email.indexOf("@") == -1 ||
                email.indexOf(".") == -1 ||
                email == "" ||
                email == null) {
                alert("Por favor, informe um E-mail válido.");
                formulario.add_email.focus();
                return false;
            } else if (cep == "" || cep.length != 10) {
                alert("Preencha corretamente o CEP.");
                formulario.add_cep.focus();
                return false;
            } else if (uf == "" || uf.length > 2 || uf.length < 2) {
                alert("Preencha corretamente o seu UF.");
                formulario.add_uf.focus();
                return false;
            }

            alert('Cadastrado com sucesso!')

        }
    </script>

</head>

<body>

    <?php


    if (isset($_GET['orderby'])) {

        $orderby = $_GET['orderby'];

        switch ($orderby) {
            case 'id':
                $ordenacao = ' GROUP BY
                stu.Name ORDER BY stu.Id DESC';
                break;

            case 'nome':
                $ordenacao = ' GROUP BY
                stu.Name ORDER BY stu.Name ASC';
                break;

            case 'email':
                $ordenacao = ' GROUP BY
                stu.Name ORDER BY stu.Email ASC';
                break;

            case 'recordDate':
                $ordenacao = ' GROUP BY
                stu.Name ORDER BY stu.RecordDate DESC';
                break;

            case 'changeDate':
                $ordenacao = ' GROUP BY
                stu.Name ORDER BY stu.ChangeDate DESC';
                break;
        }
    }


    if (isset($_POST['filtro'])) {

        if (isset($_POST['btn-filtro'])) {
            $ordenacao = "";

            $filtrar = $_POST['filtro'];

            $queryFiltrar = " WHERE (stu.Name LIKE '%$filtrar%' OR stu.Identification LIKE '%$filtrar%' OR stu.Email LIKE '%$filtrar%' OR stu.RecordDate LIKE '%$filtrar%' OR stu.ChangeDate LIKE '%$filtrar%' OR stu.State LIKE '%$filtrar%' OR con.PhoneNumber LIKE '%$filtrar%') GROUP BY stu.Name";
        } else if (isset($_POST['btn-limparFiltro'])){

            $queryFiltrar = "";

        }

    }




    $list_users = 'SELECT *,
    stu.Id AS Id_Cadastro,
    CONCAT(SUBSTR(Identification,1,3),".",SUBSTR(Identification,4,3),".",SUBSTR(Identification,7,3),"-",SUBSTR(Identification,10,2)) AS Identification,
    DATE_FORMAT(RecordDate, "%d-%m-%Y  %H:%i:%s") AS RecordDate,
    DATE_FORMAT(ChangeDate, "%d-%m-%Y  %H:%i:%s") AS ChangeDate,
    GROUP_CONCAT(concat("(",con.AreaCode,")", con.PhoneNumber) separator "-" ) AS Telefone
    
    FROM students stu
    
    LEFT JOIN contacts con ON stu.Id=con.IdStudents' .

        $ordenacao . $queryFiltrar;


    echo '<br>';
    echo '<div class="container-fluid"><form action="./index.php" method="POST" id="form-filtro" name="form-filtro">';
    echo "<input type='text' name='filtro' id='filtro' class='form-control' style='width:300px' value='$filtrar'>"
        . "<div style='margin-top:2px;'>"
        . "<button type='submit' name='btn-filtro' id='btn-filtro' style='cursor: pointer;' class='btn btn-primary'>Pesquisar</button><span>   </span>"
        . "<button type='submit' name='btn-limparFiltro' id='btn-limparFiltro' style='cursor: pointer;' class='btn btn-primary'>Limpar</button>";
    echo '</div></form></div>';


    // Ligação da query com o banco de dados.
    // Primeiro parametro é a variavel da conexão, em seguida a query desejada.
    $results_users = mysqli_query($conexao, $list_users);
    echo '<div class="container-fluid">';
    echo '<table class="table table-striped table-dark">';
    echo '<br> <thead>';
    echo '<tr class="bg-primary"><th scope="col"><a href="index.php?orderby=id" style="text-decoration:none"><font color="white">Id</a></th>'
        . '<th scope="col"><a href="index.php?orderby=nome" style="text-decoration:none"><font color="white">Nome</a></th></th>'
        . '<th scope="col">CPF</th>'
        . '<th scope="col"><a href="index.php?orderby=email" style="text-decoration:none"><font color="white">Email</a></th></th></th>'
        . '<th scope="col"><a href="index.php?orderby=recordDate" style="text-decoration:none"><font color="white">Data do Cadastro</a></th>'
        . '<th scope="col">CEP</th>'
        . '<th scope="col"><a href="index.php?orderby=changeDate" style="text-decoration:none"><font color="white">Alteração de Cadastro</a></th>'
        . '<th scope="col">UF</th>'
        . '<th scope="col">Telefone</th>'
        . '<th colspan=3 scope="col">Ações</th>'
        . '</tr> </thead> <tbody>';
    while ($row_user = mysqli_fetch_array($results_users)) {
        echo "<tr class='bg-info'><td>$row_user[Id_Cadastro]</td>"
            . "<td>$row_user[Name]</td>"
            . "<td>$row_user[Identification]</td>"
            . "<td>$row_user[Email]</td>"
            . "<td>$row_user[RecordDate]</td>"
            . "<td>$row_user[ZipCode]</td>"
            . "<td>$row_user[ChangeDate]</td>"
            . "<td>$row_user[State]</td>"
            . "<td>$row_user[Telefone]</td>"
            . "<td><a href='index.php?id=" . $row_user['Id_Cadastro'] . "&action=edit' name='btn_edit' id='btn_edit'>Editar</a></td>"
            . "<td><a href='index.php?id=" . $row_user['Id_Cadastro'] . "&action=del' name='btn_del' id='btn_del'>Deletar</a></td>"
            . "<td><a href='telefone.php?id=" . $row_user['Id_Cadastro'] . "&action=editTel' name='btn_editTel' id='btn_editTel'>Telefones</a></td>"
            . '</tr>';
    }
    echo '</tbody> </table>';

    if (isset($_GET['id']) && isset($_GET['action'])) {
        $Id_User = $_GET['id'];
        if ($_GET['action'] == "del") {



            $queryDel = "DELETE FROM students WHERE Id=$Id_User";
            $queryContaDel = "DELETE FROM contacts WHERE IdStudents=$Id_User";

            mysqli_query($conexao, $queryDel);
            mysqli_query($conexao, $queryContaDel);

            echo "Deletado com sucesso!";

            header("Location: http://localhost/index.php");
        } else if ($_GET['action'] == "edit") {

            $queryEdit = mysqli_query($conexao, "SELECT * , stu.Id AS Id_Cad

            FROM students stu
            
            LEFT JOIN contacts con on stu.Id = con.IdStudents
            
            WHERE stu.Id=$Id_User");

            $arrayEdit = mysqli_fetch_array($queryEdit);

            if (mysqli_num_rows($queryEdit)) {
                $editId = $arrayEdit['Id_Cad'];
                $editName = $arrayEdit['Name'];
                $editCPF = $arrayEdit['Identification'];
                $editEmail = $arrayEdit['Email'];
                $editCEP = $arrayEdit['ZipCode'];
                $editUF = $arrayEdit['State'];
                $editTelefone = $arrayEdit["AreaCode"] . $arrayEdit["PhoneNumber"];
            } else {
                $editId = "";
                $editName = "";
                $editCPF = "";
                $editEmail = "";
                $editCEP = "";
                $editUF = "";
                $editTelefone = "";
            }
        }
    }



    ?>

    
    <a href="./relatorio.php?filtro=<?=$filtrar?>" target="_blank" id="btn-relatorio" name="btn-relatorio" class="btn btn-primary btn-sm active" role="button" aria-pressed="true">Gerar Arquivo CSV</a>

    



    <div class="div-form">
        <form action="./add.php" method="post" id="formulario" name="formulario">
            <legend class="form-titulo">Cadastro</legend>
            <input type="hidden" name="add_id" id="add_id" value="<?= $editId ?>">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Nome:</label>
                    <input type="text" id="add_nome" name="add_nome" min="3" value="<?= $editName ?>" class="form-control">
                </div>
                <div class="form-group col-md-6">
                    <label>CPF:</label>
                    <input type="text" id="add_cpf" name="add_cpf" maxlength="14" value="<?= $editCPF ?>" class="form-control" onkeypress="$(this).mask('000.000.000-00');">
                </div>
                <div class="form-group col-md-6">
                    <label>Email:</label>
                    <input type="email" name="add_email" id="add_email" value="<?= $editEmail ?>" placeholder="nome@exemplo.com" class="form-control">
                </div>
                <div class="form-group col-md-6">
                    <label>CEP:</label>
                    <input type="text" name="add_cep" id="add_cep" maxlength="10" value="<?= $editCEP ?>" class="form-control" onkeypress="$(this).mask('00.000-000')">
                </div>
                <div class="form-group col-md-6">
                    <label>UF:</label>
                    <input type="text" id="add_uf" name="add_uf" maxlength="2" minlength="2" value="<?= $editUF ?>" class="form-control">
                </div>
                <div class="form-group col-md-6">
                </div>
                <div class="div-button">
                    <button type="submit" name="btn_add" id="btn_add" style="cursor: pointer;" class="btn btn-primary" onclick="return validarForm()">Adicionar</button>
                    <button type="reset" style="cursor: pointer;" class="btn btn-primary">Limpar</button>
                </div>
        </form>
    </div>


</body>