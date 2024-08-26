<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "trabalhoprova";

// Criando a conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificando a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION['user_id'];
$firstname = $_POST['firstname'];
$lastname = $_POST['lastname'];
$email = $_POST['email'];
$cpf = $_POST['cpf'];
$endereco = $_POST['endereco'];
$birthdate = $_POST['birthdate'];
$gender = $_POST['gender'];

// Verifica se o usuário quer alterar a senha
if (!empty($_POST['password'])) {
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $query = "UPDATE usuarios SET firstname=?, lastname=?, email=?, cpf=?, endereco=?, pass=?, birthdate=?, gender=? WHERE id=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssssssi", $firstname, $lastname, $email, $cpf, $endereco, $password, $birthdate, $gender, $user_id);
} else {
    $query = "UPDATE usuarios SET firstname=?, lastname=?, email=?, cpf=?, endereco=?, birthdate=?, gender=? WHERE id=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssssssi", $firstname, $lastname, $email, $cpf, $endereco, $birthdate, $gender, $user_id);
}

// Executa a atualização
if ($stmt->execute()) {
    // Atualiza as variáveis de sessão com os novos valores
    $_SESSION['user_firstname'] = $firstname;
    $_SESSION['user_lastname'] = $lastname;
    $_SESSION['user_email'] = $email;
    $_SESSION['user_cpf'] = $cpf;
    $_SESSION['user_endereco'] = $endereco;
    $_SESSION['user_birthdate'] = $birthdate;
    $_SESSION['user_gender'] = $gender;

    // Redireciona para a página de configuração com uma mensagem de sucesso
    header("Location: update_account.html?success=1");
} else {
    // Em caso de erro, redireciona com uma mensagem de erro
    header("Location: erro.html?error=1");
}

$stmt->close();
$conn->close();
?>