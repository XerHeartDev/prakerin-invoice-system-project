<?php

class User_model
{
    private $table = 'user';
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getAllUsers()
    {
        $draw = $_POST["draw"] ?? 0;
        $entries_per_page = $_POST["length"] ?? 10;
        $start = $_POST["start"] ?? 0;
        $search = $_POST["search"]["value"] ?? "";
        $order_col = isset($_POST["order"][0]["column"]) ? $_POST["order"][0]["column"] : 0;
        $order_dir = isset($_POST["order"][0]["dir"]) ? $_POST["order"][0]["dir"] : "asc";
        $column = $_POST["columns"][$order_col]["data"] ?? "id";

        // Main Query
        $query =
            "SELECT 
                * 
            FROM 
                $this->table
        ";
        // Search Condition
        $search_condition =
            "WHERE
                status_code LIKE :search OR
                name LIKE :search OR
                email LIKE :search OR
                phone LIKE :search OR
                city LIKE :search
        ";
        // Filtered Query
        $filtered_query = $query . '' . $search_condition;

        // Total Records Query
        $query_records_total =
            "SELECT 
                COUNT(*) as recordsTotal
            FROM
                ($query) as x
        ";
        $this->db->query($query_records_total);
        $recordsTotal = $this->db->single()["recordsTotal"];

        // Total Filtered Query
        $query_records_filtered =
            "SELECT
                COUNT(*) as filteredTotal
            FROM
                ($filtered_query) as x
        ";
        $this->db->query($query_records_filtered);
        $this->db->bindAll([
            "search" => '%' . $search . '%',
        ]);
        $recordsFiltered = $this->db->single()["filteredTotal"];

        // Data
        $query_data =
            "$filtered_query
            ORDER BY
                $column
                $order_dir
            LIMIT
                $start,
                $entries_per_page
        ";
        $this->db->query($query_data);
        $this->db->bindAll([
            "search" => '%' . $search . '%',
        ]);
        $data = $this->db->resultSet();

        $arr_return = [
            "draw" => $draw,
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $data
        ];

        return $arr_return;
    }

    public function getById($id)
    {
        $this->db->query("SELECT * FROM user WHERE id = :id");
        $this->db->bind(":id", $id);
        return $this->db->single();
    }

    public function getByEmail($email)
    {
        $this->db->query("SELECT * FROM user WHERE email = :email");
        $this->db->bind(":email", $email);
        return $this->db->single();
    }

    public function getPasswordById($id)
    {
        $this->db->query("SELECT password FROM user WHERE id = :id");
        $this->db->bind(":id", $id);
        return $this->db->single()["password"];
    }

    public function insert($data)
    {
        $this->db->query("INSERT INTO user (status_code, name, email, password, phone, city)
                            VALUES (:status_code, :name, :email, :password, :phone, :city)");
        $this->db->bind(':status_code', $data['status_code']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':password', password_hash($data['password'], PASSWORD_DEFAULT));
        $this->db->bind(':phone', $data['phone']);
        $this->db->bind(':city', $data['city']);

        $this->db->execute();
        return $this->db->rowCount();
    }

    public function update($data)
    {
        $password = !empty($data['password']) ?
            password_hash($data['password'], PASSWORD_DEFAULT) : $this->getPasswordById($data['id']);

        $this->db->query('UPDATE user SET
            status_code = :status_code,
            name = :name,
            email = :email,
            password = :password,
            phone = :phone,
            city = :city
            WHERE id = :id
        ');
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':status_code', $data['status_code']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':password', $password);
        $this->db->bind(':phone', $data['phone']);
        $this->db->bind(':city', $data['city']);

        $this->db->execute();
        return $this->db->rowCount();
    }

    public function delete($id)
    {
        $this->db->query("DELETE FROM user WHERE id = :id");
        $this->db->bind(":id", $id);
        $this->db->execute();
        return $this->db->rowCount();
    }
}
