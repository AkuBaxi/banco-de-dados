<?php
// Credenciais do banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "trabalhoprova";

// Criar conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexão
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Obter dados do formulário
$firstname = $_POST['firstname'];
$lastname = $_POST['lastname'];
$email = $_POST['email'];
$pass = password_hash($_POST['pass'], PASSWORD_BCRYPT); // Hash da senha
$cpf =  $_POST['cpf'];
$endereco =  $_POST['endereco'];
$birthdate = $_POST['birthdate'];
$gender = $_POST['gender'];

// Preparar e vincular
$stmt = $conn->prepare("INSERT INTO usuarios (firstname, lastname, email, cpf, endereco, pass, birthdate, gender) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssssss", $firstname, $lastname, $email,  $cpf, $endereco, $pass, $birthdate, $gender);


// Executar a consulta
if ($stmt->execute() === TRUE) {
    echo "New record created successfully";
    header("Location: account_created.html");
} else {
    echo "Error: " . $stmt->error;
    header("Location: index.html");
}

// Fechar conexão
$stmt->close();
$conn->close();
?>