<?php
include_once "../conexao/Conexao.php";
include_once "../model/Usuario.php";
include_once "../dao/UsuarioDAO.php";

// instanciar as classes
$usuario = new Usuario();
$usuariodao = new UsuarioDAO();

// passar os posts - dados
$d = filter_input_array(INPUT_POST);

// Se for gravado com sucesso
if (isset($_POST['cadastrar'])) {
    $usuario->setNome($d['nome']);
    $usuario->setSobrenome($d['sobrenome']);
    $usuario->setIdade($d['idade']);
    $usuario->setSexo($d['sexo']);

    $usuariodao->create($usuario);

    header("Location: ../../");
    exit(); // Encerre o script após o redirecionamento
}

// Se a requisição for Editar (correção: utilize colchetes para acessar $_POST['editar'])
else if (isset($_POST['editar'])) {
    $usuario->setId($d['id']); // Adicione o ID do usuário que está sendo editado
    $usuario->setNome($d['nome']);
    $usuario->setSobrenome($d['sobrenome']);
    $usuario->setIdade($d['idade']);
    $usuario->setSexo($d['sexo']);

    $usuariodao->update($usuario);

    header("Location: ../../");
    exit(); // Encerre o script após o redirecionamento
}

// Se a requisição for Deletar
else if (isset($_GET['del'])) {
    $id = $_GET['del'];
    $usuario->setId($id);
    $usuariodao->delete($usuario);

    header("Location: ../../");
    exit(); // Encerre o script após o redirecionamento
} else {
    header("Location: ../../");
    exit(); // Encerre o script após o redirecionamento
}
?>
