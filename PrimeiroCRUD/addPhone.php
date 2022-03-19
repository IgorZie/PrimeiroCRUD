<?php
$conexao = mysqli_connect("localhost", "root", "", "school");


$addCod = $_POST['add_cod'];
$addId = $_POST['add_id'];
$ReplaceTelefone = str_replace('(', '',  str_replace(')', '', str_replace(' ', '', str_replace('-', '', $_POST["add_tel"]))));
$addPhone = substr($ReplaceTelefone, 2);
$addDDD = substr($ReplaceTelefone, 0, 2);

$queryAddPhone = "INSERT INTO contacts (AreaCode, PhoneNumber, IdStudents) VALUES ('$addDDD', '$addPhone', '$addId')";


if ($addCod == ""){
    $addNumber = mysqli_query($conexao, $queryAddPhone);
    echo "Número adicionado com sucesso!";
} else {
    echo "Número já cadastrado!";
}



$queryContacts = "UPDATE contacts con
                  SET
                  con.AreaCode='$addDDD', con.PhoneNumber='$addPhone'
                  WHERE
                  con.IdStudents = $addCod";

$queryAlteracao = "UPDATE students stu
                  SET
                  stu.ChangeDate=NOW()
                  WHERE stu.Id = $addId";


if (isset($_POST['add_cod']) && $addCod <> "") {
    mysqli_query($conexao, $queryContacts);
    mysqli_query($conexao, $queryAlteracao);
    echo "Número alterado com sucesso!";
} else {
    echo "Erro na alteração";
}

header ("Location: http://localhost/telefone.php?id=$addId&action=editTel");

?>