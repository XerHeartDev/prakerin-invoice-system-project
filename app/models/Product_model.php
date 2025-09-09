<?php

class Product_model
{
    private $table = "product";
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getAllProduct()
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
                sku LIKE :search OR
                name LIKE :search OR
                description LIKE :search OR
                price LIKE :search OR
                unit LIKE :search OR
                stock LIKE :search
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

    public function getActiveProduct()
    {
        $this->db->query(
            "SELECT
                        *
                    FROM
                        $this->table
                    WHERE
                        status_code = 1
        "
        );
        return $this->db->resultSet();
    }

    public function getById($id)
    {
        $this->db->query("SELECT * FROM $this->table WHERE id = :id");
        $this->db->bind(":id", $id);
        return $this->db->single();
    }

    public function insert($data)
    {
        $this->db->query("INSERT INTO product (status_code, sku, name, description, price, unit, stock)
                                        VALUES (:status_code, :sku, :name, :description, :price, :unit, :stock)");
        $this->db->bind(':status_code', $data['status_code']);
        $this->db->bind(':sku', $data['sku']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':price', $data['price']);
        $this->db->bind(':unit', $data['unit']);
        $this->db->bind(':stock', $data['stock']);

        $this->db->execute();
        return $this->db->rowCount();
    }

    public function update($data)
    {
        $this->db->query('UPDATE product SET
            status_code = :status_code,
            sku = :sku,
            name = :name,
            description = :description,
            price = :price,
            unit = :unit,
            stock = :stock
            WHERE id = :id
        ');
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':status_code', $data['status_code']);
        $this->db->bind(':sku', $data['sku']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':price', $data['price']);
        $this->db->bind(':unit', $data['unit']);
        $this->db->bind(':stock', $data['stock']);

        $this->db->execute();
        return $this->db->rowCount();
    }

    public function delete($id)
    {
        $this->db->query("DELETE FROM product WHERE id = :id");
        $this->db->bind(":id", $id);
        $this->db->execute();
        return $this->db->rowCount();
    }
}
