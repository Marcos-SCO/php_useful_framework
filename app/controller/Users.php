<?php

namespace App\Controller;

use App\Models\User;

class Users extends User
{
    public function __construct()
    {
    }

    public function index()
    {
        $users = $this->select('users');
        if ($users) {
            http_response_code(200);
            echo json_encode($users);
        }
    }

    public function createUser()
    {
        $data = $this->getContents();
        extract($data);

        // Find email in db
        $findUser = $this->customQuery("SELECT * FROM users WHERE email = :email", ["email" => $email]);

        if (!$findUser) {
            $this->insert("users", [
                "name" => $name,
                "last_name" => $last_name,
                "email" => $email,
                "password" => $password,
                "created_at" => date("Y-m-d")
            ]);

            http_response_code(200);
            echo json_encode(
                [
                    "status" => "Usuário criado com sucesso!",
                    $data
                ]
            );
        } else {
            http_response_code(401);
            echo json_encode(
                [
                    "status" => "Erro: Usuário já existe no DB",
                    "response" => $data
                ]
            );
        }
    }

    public function deleteUser()
    {
        $data = $this->getContents();
        extract($data);

        if ($this->delete("users", ["email" => $email])) {
            http_response_code(200);

            echo json_encode(
                [
                    "status" => "Usuário deletado com sucesso!",
                    "response" => $data
                ]
            );
        } else {
            http_response_code(401);
            echo json_encode(
                [
                    "status" => "Usuário não podê ser deletado"
                ]
            );
        }
    }

    public function updateUser()
    {
        $data = $this->getContents();
        extract($data);
        $updateUser = $this->update("users", [
            "name" => $name,
            "last_name" => $last_name,
            "password" => $password,
            "email" => $email
        ], ["email" => "asdass"]);

        if ($updateUser) {
            http_response_code(200);
            echo json_encode(
                [
                    "status" => "Atualizado com sucesso!",
                    "body" => $data
                ]
            );
        } else {
            http_response_code(401);
            echo json_encode(
                [
                    "status" => "Não foi possível atualizar"
                ]
            );
        }
    }

    public function getUser($id)
    {
        http_response_code(200);
        $user = $this->customQuery('SELECT * FROM users where id = :id', ["id" => $id], "fetch");

        if ($user) {
            echo json_encode(
                [
                    "status" => "Usuário encontrado",
                    "response" => $user
                ]
            );
        } else {
            http_response_code(404);

            echo json_encode(
                [
                    "status" => "Usuário não encontrado"
                ]
            );
        }
    }
}
