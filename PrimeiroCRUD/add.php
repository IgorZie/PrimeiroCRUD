<?php
$conexao = mysqli_connect('localhost', 'root', '', 'school');

echo "Conectado com sucesso" . "<br>";


$addId = $_POST["add_id"];
$addNome = $_POST["add_nome"];
$addCPF = str_replace('.', '', str_replace('-', '', $_POST['add_cpf']));
$addEmail = $_POST["add_email"];
$addCep = str_replace('.', '', str_replace('-', '', $_POST["add_cep"]));
$addUF = $_POST["add_uf"];
// A função substr separa uma string no php, nesse caso pegamos o Telefone inserido no formulário,
// e separamos o DDD do número e colocamos em suas respectivas variaveis.


$query =  "INSERT INTO students (Name, Identification, Email, RecordDate, ZipCode, State)
               VALUES
               ('$addNome', '$addCPF', '$addEmail', NOW(), '$addCep', '$addUF')";


mysqli_query($conexao, $query);


$queryStu = "UPDATE students stu
            SET
            stu.ChangeDate=NOW(), stu.Name='$addNome', stu.Identification='$addCPF', stu.Email='$addEmail', stu.ZipCode='$addCep', stu.State='$addUF'
            WHERE
            stu.Id = $addId ";
        


if(isset($_POST["add_id"]) && $addId <> ""){

    mysqli_query($conexao, $queryStu);

    echo "Alterado com sucesso!";

} else  {

    echo "Erro na alteração";

}



header('Location: http://localhost/PrimeiroCRUD/index.php');

?>