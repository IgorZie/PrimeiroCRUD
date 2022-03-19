<?php
$conexao = mysqli_connect('localhost', 'root', '', 'school');

$arquivo = fopen('RelatorioUsuarios.csv', 'w');

$filtrar =  $_GET['filtro'];

if (isset($_GET['filtro'])) {

    $queryRel = "SELECT *,
    stu.Id AS Id_Cadastro,
    CONCAT(SUBSTR(Identification,1,3),'.',SUBSTR(Identification,4,3),'.',SUBSTR(Identification,7,3),'-',SUBSTR(Identification,10,2)) AS Identification,
    DATE_FORMAT(RecordDate, '%d-%m-%Y  %H:%i:%s') AS RecordDate,
    DATE_FORMAT(ChangeDate, '%d-%m-%Y  %H:%i:%s') AS ChangeDate,
    GROUP_CONCAT(concat('(',con.AreaCode,')', con.PhoneNumber) separator '-' ) AS Telefone
    
    FROM students stu
    
    LEFT JOIN contacts con ON stu.Id=con.IdStudents 

    WHERE (Name LIKE '%$filtrar%' OR Identification LIKE '%$filtrar%' OR Email LIKE '%$filtrar%' OR RecordDate LIKE '%$filtrar%' OR ChangeDate LIKE '%$filtrar%' OR State LIKE '%$filtrar%' OR PhoneNumber LIKE '%$filtrar%')
    GROUP BY stu.Name;";

    $queryBanco = mysqli_query($conexao, $queryRel);

    fwrite($arquivo, 'Id' . ';' . 'Nome' . ';' . 'CPF' . ';' . 'Email' . ';' . 'Data Cadastro' . ';' . 'CEP' . ';' . utf8_decode('Alteração de Cadastro') . ';' . 'UF' . ';' . 'Telefone' . ';');

    fwrite($arquivo, "\r\n");

    while ($arrayRelatorio = mysqli_fetch_array($queryBanco)) {


        fwrite($arquivo, $arrayRelatorio['Id_Cadastro'] . ";" . utf8_decode($arrayRelatorio['Name']) . ";" . $arrayRelatorio['Identification'] . ";" . $arrayRelatorio['Email'] . ";" . $arrayRelatorio['RecordDate'] . ";" . $arrayRelatorio['ZipCode'] . ";" . $arrayRelatorio['ChangeDate'] . ";" . $arrayRelatorio['State'] . ";" . $arrayRelatorio['Telefone'] . ";");

        fwrite($arquivo, "\r\n");

    }
}


fclose($arquivo);

?>

<html>
    <head>
        <script>
            window.close();
        </script>
    </head>
</html>