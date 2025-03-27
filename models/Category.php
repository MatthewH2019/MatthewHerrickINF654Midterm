<?php
class Category{
    // Data Base Stuff
    private $conn;
    private $table = 'categories';

    // Category Properties
    public $id;
    public $category;

    // Constructor with Data Base
    public function __construct($db){
        $this->conn = $db;
    }

    // Get Categories
    public function read_Categories(){
        // Create Query
        $query = 'SELECT c.id, c.category FROM ' . $this->table . ' c';

        // Prepare Statement
        $stmt = $this->conn->prepare($query);

        // Execute Query
        $stmt->execute();

        return $stmt;
    }

    // Get single Category
    public function read_SingleCategory(){
        // Create Query
        $query = 'SELECT c.id, c.category FROM ' . $this->table . ' c WHERE c.id = ?';

        // Prepare Statement
        $stmt = $this->conn->prepare($query);

        //Bind ID
		$stmt->bindParam(1, $this->id);

		// Execute Query
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->category = $row;
    }

    // Create Post
    public function create(){
        // Place Holder Data
        $temp = $this->category;

        $query = 'SELECT c.id, c.category FROM ' . $this->table . ' c WHERE c.category = ?';

        // Prepare Statement
        $stmt = $this->conn->prepare($query);

        // Bind ID
		$stmt->bindParam(1, $this->category);

		// Execute Query
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->category = $row;
       
        if($this->category === false){
            $this->category = $temp;

            // Create Query
            $query = 'INSERT INTO ' . $this->table . ' (category) VALUES (:category)';
            
            // Prepare Statement
            $stmt = $this->conn->prepare($query);

            // Clean Data
            $this->category = htmlspecialchars(strip_tags($this->category));

            // Bind Data
            $stmt->bindParam(':category', $this->category);

            if($stmt->execute()){
                // Place Holder Data
                $this->category = $temp;

                // Create Query
                $query = 'SELECT categories.id, categories.category FROM categories WHERE categories.category = ?';

                // Prepare Statement
                $stmt = $this->conn->prepare($query);

                // Bind ID
                $stmt->bindParam(1, $this->category);
                // Execute Query
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                $this->category = $row;

                echo(json_encode($this->category));
                return true;
            } else {
                printf("Error: %s.\n", $stmt->error);
                return false;
            }
        } else {
            return false;
        }
    }

    // Update Post
	public function update()
	{
        // Place Holder Data
        $temp = $this->category;

        // Create Query
        $query = 'SELECT c.id FROM ' . $this->table . ' c WHERE c.id = ?';

        // Prepare Statement
        $stmt = $this->conn->prepare($query);

        // Bind ID
		$stmt->bindParam(1, $this->id);

		// Execute Query
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->category = $row;
        if($this->category === false){
            echo json_encode(
                array('message' => 'category_id Not found'));
            exit();
        } else {
            // Place Holder Data
            $this->category = $temp;

            // Create Query
            $query = 'UPDATE ' . $this->table . ' SET category = :category WHERE id = :id';

            // Prepare Statement
            $stmt = $this->conn->prepare($query);

            // Clean data
            $this->category = htmlspecialchars(strip_tags($this->category));
            $this->id = htmlspecialchars(strip_tags($this->id));

            // Bind data
            $stmt->bindParam(':category', $this->category);
            $stmt->bindParam(':id', $this->id);
            
            
            if($stmt->execute()){
                // Place Holder Data
                $this->category = $temp;

                // Create Query
                $query = 'SELECT categories.id, categories.category FROM categories WHERE categories.category = ?';

                // Prepare Statement
                $stmt = $this->conn->prepare($query);

                // Bind ID
                $stmt->bindParam(1, $this->category);
                // Execute Query
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                $this->category = $row;

                echo(json_encode($this->category));
                return true;
            } else {
                printf("Error: %s.\n", $stmt->error);
                return false;
            }
        }
	}

    // Delete Post
	public function delete()
	{
        // Place Holder Data
        $temp = $this->id;
        
        // Create Query
        $query = 'SELECT c.id FROM ' . $this->table . ' c WHERE c.id = ?';

        // Prepare Statement
        $stmt = $this->conn->prepare($query);

        // Bind ID
		$stmt->bindParam(1, $this->id);

		// Execute Query
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->id = $row;

        if($this->id === false){ 
            echo json_encode(
                array('message' => 'category_id Not found'));
            exit();
        } else {
            // Place Holder Data
            $this->id = $temp;

            // Create Query
		    $query = 'DELETE FROM quotes WHERE category_id = :id';

		    // Prepare Statement
		    $stmt = $this->conn->prepare($query);

		    // Clean Data
		    $this->id = htmlspecialchars(strip_tags($this->id));

		    // Bind data
		    $stmt->bindParam(':id', $this->id);
        
            if($stmt->execute()){
                // Place Holder Data
                $this->id = $temp;
                
                // Create Query
                $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';

                // Prepare Statement
                $stmt = $this->conn->prepare($query);

                // Clean data
                $this->id = htmlspecialchars(strip_tags($this->id));

                // Bind data
                $stmt->bindParam(':id', $this->id);
                
                if($stmt->execute()){
                    $array = array('id' => $this->id);
                    echo(json_encode($array));
                    return true;
                } else {
                    printf("Error: %s.\n", $stmt->error);
                    return false;
                }
		    } else {
                printf("Error: %s.\n", $stmt->error);
                return false;
            }	
        }
	}
}