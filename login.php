<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "trabalhoprova";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Erro na conexão com o banco de dados: " . $conn->connect_error);
    }

    $email = $_POST["email"];
    $pass = $_POST["pass"];


    $sql = "SELECT pass FROM usuarios WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $pass_hash = $row["pass"];

        if (password_verify($pass, $pass_hash)) {
            $sql = "SELECT id,firstname,lastname,email,cpf,endereco,birthdate,gender FROM usuarios WHERE email = '$email'";
            $result = $conn->query($sql);
            $user = $result->fetch_assoc();

            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_firstname'] = $user['firstname'];
            $_SESSION['user_lastname'] = $user['lastname'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_cpf'] = $user['cpf'];
            $_SESSION['user_endereco'] = $user['endereco'];
            $_SESSION['user_birthdate'] = $user['birthdate'];
            $_SESSION['user_gender'] = $user['gender'];
            header("Location: home.html");
            exit();
        } else {
            // Senha incorreta
            header("Location: erro.html");
            exit();
        }
    } else {
        // Usuário não encontrado
        header("Location: erro.html");
        exit();
    }

    // Fecha a conexão
    $conn->close();
    
}
?>