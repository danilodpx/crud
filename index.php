<?php
include_once "./conexao/Conexao.php";
include_once "./dao/UsuarioDAO.php";
include_once "./model/Usuario.php";

// Instância a classe de conexão
$conexao = Conexao::getConexao();

// Instância a classe DAO de usuário
$usuariodao = new UsuarioDAO($conexao);

// Lógica para processar o formulário de adição de usuário
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["cadastrar"])) {
    $nome = $_POST['nome'];
    $sobrenome = $_POST['sobrenome'];
    $idade = $_POST['idade'];
    $sexo = $_POST['sexo'];

    // Cria um novo objeto de usuário
    $novoUsuario = new Usuario();
    $novoUsuario->setNome($nome);
    $novoUsuario->setSobrenome($sobrenome);
    $novoUsuario->setIdade($idade);
    $novoUsuario->setSexo($sexo);

    // Chama o método para adicionar o usuário no banco de dados
    $usuariodao->create($novoUsuario);

    // Redireciona para evitar o reenvio do formulário
    header("Location: index.php");
    exit();
}

// Lógica para processar o formulário de edição de usuário
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["editar"])) {
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $sobrenome = $_POST['sobrenome'];
    $idade = $_POST['idade'];
    $sexo = $_POST['sexo'];

    // Obtém o usuário existente do banco de dados
    $usuarioExistente = $usuariodao->readById($id);

    if ($usuarioExistente) {
        $usuarioExistente->setNome($nome);
        $usuarioExistente->setSobrenome($sobrenome);
        $usuarioExistente->setIdade($idade);
        $usuarioExistente->setSexo($sexo);

        // Chama o método para atualizar o usuário no banco de dados
        $usuariodao->update($usuarioExistente);
    }

    // Redireciona para evitar o reenvio do formulário
    header("Location: index.php");
    exit();
}

// Lógica para processar a exclusão de usuário
if (isset($_GET['del'])) {
    $id = $_GET['del'];
    // Cria um objeto de usuário apenas com o ID
    $usuarioParaExcluir = new Usuario();
    $usuarioParaExcluir->setId($id);

    // Chama o método para excluir o usuário no banco de dados
    $usuariodao->delete($usuarioParaExcluir);

    // Redireciona para evitar o reenvio do formulário
    header("Location: index.php");
    exit();
}

// Agora, vamos exibir a tabela de usuários e o formulário
$listaDeUsuarios = $usuariodao->read();

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <title>CRUD Simples PHP</title>
    <style>
        .menu,
        thead {
            background-color: #bbb !important;
        }

        .row {
            padding: 10px;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-light bg-light menu">
        <div class="container">
            <a class="navbar-brand" href="#">
                CRUD PHP POO
            </a>
        </div>
    </nav>
    <div class="container">
        <form action="index.php" method="POST">
            <div class="row">
                <div class="col-md-3">
                    <label>Nome</label>
                    <input type="text" name="nome" value="" autofocus class="form-control" required />
                </div>
                <div class="col-md-5">
                    <label>Sobrenome</label>
                    <input type="text" name="sobrenome" value="" class="form-control" required />
                </div>
                <div class="col-md-2">
                    <label>Idade</label>
                    <input type="number" name="idade" value="" class="form-control" required />
                </div>
                <div class="col-md-2">
                    <label>Sexo</label>
                    <select name="sexo" class="form-control">
                        <option value="M">Masculino</option>
                        <option value="F">Feminino</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <br>
                    <button class="btn btn-primary" type="submit" name="cadastrar">Cadastrar</button>
                </div>
            </div>
        </form>
        <hr>
        <div class="table-responsive">
            <table class="table table-sm table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Nome</th>
                        <th>Sobrenome</th>
                        <th>Idade</th>
                        <th>Sexo</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($listaDeUsuarios as $usuario) : ?>
                        <tr>
                            <td><?= $usuario->getId() ?></td>
                            <td><?= $usuario->getNome() ?></td>
                            <td><?= $usuario->getSobrenome() ?></td>
                            <td><?= $usuario->getIdade() ?></td>
                            <td><?= $usuario->getSexo() ?></td>
                            <td class="text-center">
                                <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editar<?= $usuario->getId() ?>">
                                    Editar
                                </button>
                                <a href="index.php?del=<?= $usuario->getId() ?>">
                                    <button class="btn btn-danger btn-sm" type="button">Excluir</button>
                                </a>
                            </td>
                        </tr>
                        <!-- Modal -->
                        <div class="modal fade" id="editar<?= $usuario->getId() ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Editar</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="index.php" method="POST">
                                            <div class="row">
                                                <div class="col-md-5">
                                                    <label>Nome</label>
                                                    <input type="text" name="nome" value="<?= $usuario->getNome() ?>" class="form-control" required />
                                                </div>
                                                <div class="col-md-7">
                                                    <label>Sobrenome</label>
                                                    <input type="text" name="sobrenome" value="<?= $usuario->getSobrenome() ?>" class="form-control" required />
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <label>Idade</label>
                                                    <input type="number" name="idade" value="<?= $usuario->getIdade() ?>" class="form-control" required />
                                                </div>
                                                <div class="col-md-3">
                                                    <label>Sexo</label>
                                                    <select name="sexo" class="form-control">
                                                        <?php if ($usuario->getSexo() == 'F') : ?>
                                                            <option value="F">Feminino</option>
                                                            <option value="M">Masculino</option>
                                                        <?php else : ?>
                                                            <option value="M">Masculino</option>
                                                            <option value="F">Feminino</option>
                                                        <?php endif ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-2">
                                                    <br>
                                                    <input type="hidden" name="id" value="<?= $usuario->getId() ?>" />
                                                    <button class="btn btn-primary" type="submit" name="editar">Atualizar</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>

</html>