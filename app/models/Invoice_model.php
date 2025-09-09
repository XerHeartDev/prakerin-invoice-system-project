<?php

class Invoice_model
{
    private $tableHeader = 'invoice_header';
    private $tableDetail = 'invoice_detail';
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getAllInvoiceHeader()
    {

        $draw = $_POST["draw"] ?? 0;
        $entries_per_page = $_POST["length"] ?? 10;
        $start = $_POST["start"] ?? 0;
        $search = $_POST["search"]["value"] ?? "";
        $order_col = isset($_POST["order"][0]["column"]) ? $_POST["order"][0]["column"] : 0;
        $order_dir = isset($_POST["order"][0]["dir"]) ? $_POST["order"][0]["dir"] : "asc";
        $column = $_POST["columns"][$order_col]["data"] ?? "id";

        // Base Query
        $query =
            "SELECT 
                ih.id, 
                ih.status_invoice, 
                ih.invoice_code, 
                ih.created_at, 
                ih.updated_at,
                c.name,
                IFNULL(SUM(idetail.total), 0) AS total
            FROM 
                $this->tableHeader AS ih
            INNER JOIN client AS c ON 
                ih.client_id = c.id
            LEFT JOIN $this->tableDetail AS idetail ON 
                ih.id = idetail.invoice_id
        ";
        // Search Condition
        $search_condition =
            "WHERE
                ih.status_invoice LIKE :search OR
                ih.invoice_code LIKE :search OR
                ih.created_at LIKE :search OR
                ih.updated_at LIKE :search OR
                c.name LIKE :search OR
                total LIKE :search
        ";
        // Group By
        $group_by =
            "GROUP BY 
                ih.id
        ";
        // Main Query
        $main_query = $query . '' . $group_by;
        // Filtered Query
        $filtered_query = $query . '' . $search_condition . '' . $group_by;

        // Total Records Query
        $query_records_total =
            "SELECT 
                COUNT(*) as recordsTotal
            FROM
                ($main_query) as x
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

        $arr_result = [];
        foreach ($data as $row) {
            $arr_temp_result = []; // array kosong
            $arr_temp_result["id"] = $row['id'];
            $arr_temp_result["status_invoice"] = $row['status_invoice'];
            $arr_temp_result["invoice_code"] = $row['invoice_code'];
            $arr_temp_result["created_at"] = $row['created_at'];
            $arr_temp_result["updated_at"] = $row['updated_at'];
            $arr_temp_result["client_name"] = $row['name'];
            $arr_temp_result["total"] = $row['total'];
            $arr_temp_result["btn_handler"] = [
                "id" => $row['id'],
                "status_invoice" => $row["status_invoice"]
            ];

            $arr_result[] = $arr_temp_result;
        };

        $arr_return = [
            "draw" => $draw,
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $arr_result
        ];

        return $arr_return;
    }

    public function getInvoiceHeaderById($id)
    {
        $this->db->query(
            "SELECT 
                        * 
                    FROM 
                        $this->tableHeader 
                    WHERE 
                        id = :id
            "
        );

        $this->db->bind(":id", $id);
        return $this->db->single();
    }

    public function insertHeader($data)
    {
        $this->db->query(
            "INSERT INTO $this->tableHeader (
                        invoice_code, 
                        client_id
                    )
                    VALUES (
                        :invoice_code, 
                        :client_id
                    )
        "
        );
        $this->db->bindAll([
            "invoice_code" => $data["code"],
            "client_id" => $data["name"],
        ]);

        $this->db->execute();
        return $this->db->rowCount();
    }

    public function updateHeader($data)
    {
        $this->db->query(
            "UPDATE 
                        $this->tableHeader 
                    SET 
                        invoice_code = :invoice_code,
                        client_id = :client_id
                    WHERE 
                        id = :id
        "
        );
        $this->db->bindAll([
            "id" => $data["id"],
            "invoice_code" => $data["code"],
            "client_id" => $data["name"],
        ]);

        $this->db->execute();
        return $this->db->rowCount();
    }

    public function publishHeader($id)
    {
        $this->db->query(
            "UPDATE $this->tableHeader
            SET 
                status_invoice = :status_invoice
            WHERE 
                id = :id
        "
        );
        $this->db->bindAll([
            "id" => $id,
            "status_invoice" => "Published",
        ]);

        $this->db->execute();
        return $this->db->rowCount();
    }

    public function voidHeader($id)
    {
        $this->db->query(
            "UPDATE $this->tableHeader
            SET 
                status_invoice = :status_invoice
            WHERE 
                id = :id
        "
        );
        $this->db->bindAll([
            "id" => $id,
            "status_invoice" => "Void",
        ]);

        $this->db->execute();
        return $this->db->rowCount();
    }

    public function getAllInvoiceDetailByHeaderId($id)
    {
        $draw = $_POST["draw"] ?? 0;
        $entries_per_page = $_POST["length"] ?? 10;
        $start = $_POST["start"] ?? 0;
        $search = $_POST["search"]["value"] ?? "";
        $order_col = isset($_POST["order"][0]["column"]) ? $_POST["order"][0]["column"] : 0;
        $order_dir = isset($_POST["order"][0]["dir"]) ? $_POST["order"][0]["dir"] : "asc";
        $column = $_POST["columns"][$order_col]["data"] ?? "id";

        // Base Query
        $query =
            "SELECT
                *
            FROM 
                $this->tableDetail
            WHERE
                invoice_id = :id
                AND
                sts_detail = 1
        ";
        // Search Condition
        $search_condition =
            "AND (
                created_at LIKE :search OR
                updated_at LIKE :search OR
                product_id LIKE :search OR
                price LIKE :search OR
                quantity LIKE :search OR
                total LIKE :search
            )
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
        $this->db->bindAll([
            "id" => $id
        ]);
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
            "id" => $id,
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
            "id" => $id,
            "search" => '%' . $search . '%'
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

    public function insertDetail($data)
    {
        $this->db->query(
            "INSERT INTO $this->tableDetail (
                        invoice_id,
                        product_id,
                        price,
                        quantity,
                        total,
                        created_at,
                        updated_at
                    )
                    VALUES (
                        :invoice_id,
                        :product_id,
                        :price,
                        :quantity,
                        :total,
                        CURRENT_TIMESTAMP(),
                        CURRENT_TIMESTAMP()
                    )
            "
        );
        $this->db->bindAll([
            "invoice_id" => $data['invoice_id'],
            "product_id" => $data['product_id'],
            "price" => $data['price'],
            "quantity" => $data['quantity'],
            "total" => $data['subtotal']
        ]);

        $this->db->execute();
        return $this->db->rowCount();
    }

    public function updateDetail($data)
    {
        $this->db->query(
            "UPDATE 
                        $this->tableDetail
                    SET
                        product_id = :product_id,
                        price = :price,
                        quantity = :quantity,
                        total = :total,
                        updated_at = CURRENT_TIMESTAMP()
                    WHERE
                        id = :id
            "
        );
        $this->db->bindAll([
            "id" => $data["id"],
            "product_id" => $data["product"],
            "price" => $data["price"],
            "quantity" => $data["quantity"],
            "total" => $data["subtotal"],
        ]);

        $this->db->execute();
        return $this->db->rowCount();
    }

    public function deleteDetail($id)
    {
        $this->db->query(
            "UPDATE $this->tableDetail
            SET 
                sts_detail = :sts_detail,
                updated_at = CURRENT_TIMESTAMP()
            WHERE 
                id = :id
        "
        );
        $this->db->bindAll([
            "id" => $id,
            "sts_detail" => "9",
        ]);

        $this->db->execute();
        return $this->db->rowCount();
    }
}
