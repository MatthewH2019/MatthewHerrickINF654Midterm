<?php
class Quote{
    // Data Base Stuff
    private $conn;
    private $table = 'quotes';

    // Quote Properties
    public $id;
    public $quote;
    public $author_id;
    public $category_id;

    // Constructor with Data Base
    public function __construct($db){
        $this->conn = $db;
    }

    // Get Quotes
    public function read_Quotes(){
        // Create Query
        $query = 'SELECT
                q.id,
                q.quote,
                authors.author,
                authors.id AS author_id,
                categories.category,
                categories.id AS category_id
                FROM
                    ' . $this->table . ' q
                INNER JOIN
                    authors ON q.author_id = authors.id
                INNER JOIN
                categories ON q.category_id = categories.id';

        // Prepare Statement
        $stmt = $this->conn->prepare($query);

        // Execute Query
        $stmt->execute();

        return $stmt;
    }

    // Get Single Quote
    public function read_SingleQuote(){
        // Create Query
        $query = 'SELECT
                q.id, q.quote,
                authors.author,
                categories.category
                FROM
                ' . $this->table . ' q
                INNER JOIN authors ON q.author_id = authors.id
                INNER JOIN categories ON q.category_id = categories.id
                WHERE q.id = ?';
    

        // Prepare Statement
        $stmt = $this->conn->prepare($query);

        // Bind ID
	    $stmt->bindParam(1, $this->id);

		// Execute Query
		$stmt->execute();

		$row = $stmt->fetch(PDO::FETCH_ASSOC);

        // Set Properties
        $this->quote = $row;
    }

    // Create Post
    public function create(){
        // Place Holder Data
        $tempCategoryId = $this->category_id;
        $tempAuthorId= $this->author_id;
        $tempQuote = $this->quote;

        // Create Query
        $query = 'SELECT authors.id, authors.author FROM authors WHERE authors.id = ?';

        // Prepare Statement
        $stmt = $this->conn->prepare($query);

        // Bind ID
        $stmt->bindParam(1, $this->author_id);

        // Execute Query
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->author_id = $row;

        if($this->author_id === false){
            echo json_encode(array('message' => 'author_id Not Found'));
            exit();
        } else {
            // Place Holder Data
            $this->category_id = $tempCategoryId;
            $this->author_id = $tempAuthorId;
            $this->quote = $tempQuote;

            // Create Query
            $query = 'SELECT categories.id, categories.category FROM categories WHERE categories.id = ?';

            // Prepare Statement
            $stmt = $this->conn->prepare($query);

            // Bind ID
            $stmt->bindParam(1, $this->category_id);

            // Execute Query
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->category_id = $row;
            
            if($this->category_id === false){
                echo json_encode(array('message' => 'category.id Not Found'));
                exit();
            } else {
                // Place Holder Data
                $this->category_id = $tempCategoryId;
                $this->author_id = $tempAuthorId;
                $this->quote = $tempQuote;

                // Create Query
                $query = 'SELECT q.quote, q.id FROM ' . $this->table . ' q  WHERE q.quote = ?';

                // Prepare Statement
                $stmt = $this->conn->prepare($query);

                // Bind ID
                $stmt->bindParam(1, $this->quote);

                // Execute Query
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                $this->quote = $row;
            
                if($this->quote === false){
                    // Place Holder Data
                    $this->category_id = $tempCategoryId;
                    $this->author_id = $tempAuthorId;
                    $this->quote = $tempQuote;
                    
                    // Create Query
                    $query = 'INSERT INTO ' . $this->table . ' (quote, author_id, category_id) VALUES (:quote, :author_id, :category_id)';
            
                    // Prepare Statement
                    $stmt = $this->conn->prepare($query);

                    // Clean Data
                    $this->quote = htmlspecialchars(strip_tags($this->quote));
                    $this->author_id = htmlspecialchars(strip_tags($this->author_id));
                    $this->category_id = htmlspecialchars(strip_tags($this->category_id));
                    
                    // Bind Data
                    $stmt->bindParam(':quote', $this->quote);
                    $stmt->bindParam(':author_id', $this->author_id);
                    $stmt->bindParam(':category_id', $this->category_id);

                   
                    if($stmt->execute()){
                        // Place Holder Data
                        $this->category_id = $tempCategoryId;
                        $this->author_id = $tempAuthorId;
                        $this->quote = $tempQuote;

                        $query = 'SELECT quotes.id, quotes.quote, quotes.author_id, quotes.category_id FROM quotes WHERE quotes.quote = ?';

                        // Prepare Statement
                        $stmt = $this->conn->prepare($query);

                        // Bind ID
                        $stmt->bindParam(1, $this->quote);
                        
                        // Execute Query
                        $stmt->execute();

                        $row = $stmt->fetch(PDO::FETCH_ASSOC);

                        $this->quote = $row;
                        echo(json_encode($this->quote));

                        return true;
                    } else {
                        return false;
                    }
                }
            }
        }
    }

    // Update Quote
	public function update()
	{
        // Place Holder Data
        $tempId = $this->id;
        $tempCategoryId = $this->category_id;
        $tempAuthorId = $this->author_id;
        $tempQuote = $this->quote;
        
        // Create Query
        $query = 'SELECT authors.id FROM authors WHERE authors.id = ?';

        // Prepare Statement
        $stmt = $this->conn->prepare($query);

        // Bind ID
        $stmt->bindParam(1, $this->author_id);

        // Execute Query
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->author_id = $row;

        if($this->author_id === false){ 
            echo json_encode(array('message' => 'author_id Not Found'));
            exit();
        } else {
            // Place Holder Data
            $this->id = $tempId;
            $this->category_id = $tempCategoryId;
            $this->author_id = $tempAuthorId;
            $this->quote = $tempQuote;
            
            $query = 'SELECT categories.id FROM categories WHERE categories.id = ?';

            // Prepare Statement
            $stmt = $this->conn->prepare($query);

            // Bind ID
            $stmt->bindParam(1, $this->category_id);

            // Execute Query
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->category_id = $row;
            
            if($this->category_id === false){ 
                echo json_encode(array('message' => 'category_id Not Found'));
                exit();
            } else {
                // Place Holder Data
                $this->id = $tempId;
                $this->category_id = $tempCategoryId;
                $this->author_id = $tempAuthorId;
                $this->quote = $tempQuote;

                $query = 'SELECT q.id FROM ' . $this->table . ' q  WHERE q.id = ?';

                // Prepare Statement
                $stmt = $this->conn->prepare($query);

                // Bind ID
                $stmt->bindParam(1, $this->id);

                // Execute Query
                $stmt->execute();

                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $this->id = $row;
        
                if($this->id === false){
                    echo json_encode(array('message' => 'No Quotes Found'));
                    exit();
                } else {
                    // Place Holder Data
                    $this->id = $tempId;
                    $this->category_id = $tempCategoryId;
                    $this->author_id = $tempAuthorId;
                    $this->quote = $tempQuote;

                    $query = 'UPDATE ' . $this->table . ' SET quote = :quote, author_id = :author_id, category_id = :category_id WHERE id = :id';

                    // Prepare Statement
                    $stmt = $this->conn->prepare($query);

                    // Clean Data
                    $this->quote = htmlspecialchars(strip_tags($this->quote));
                    $this->author_id = htmlspecialchars(strip_tags($this->author_id));
                    $this->category_id = htmlspecialchars(strip_tags($this->category_id));
                    $this->id = htmlspecialchars(strip_tags($this->id));

                    // Bind Data
                    $stmt->bindParam(':quote', $this->quote);
                    $stmt->bindParam(':author_id', $this->author_id);
                    $stmt->bindParam(':category_id', $this->category_id);
                    $stmt->bindParam(':id', $this->id);

                    if($stmt->execute()){
                        // Place Holder Data
                        $this->id = $tempId;
                        $this->category_id = $tempCategoryId;
                        $this->author_id = $tempAuthorId;
                        $this->quote = $tempQuote;

                        $query = 'SELECT quotes.id, quotes.quote, quotes.author_id, quotes.category_id FROM quotes WHERE quotes.quote = ?';

                        // Prepare Statement
                        $stmt = $this->conn->prepare($query);

                        // Bind ID
                        $stmt->bindParam(1, $this->quote);
                        
                        // Execute Query
                        $stmt->execute();

                        $row = $stmt->fetch(PDO::FETCH_ASSOC);
                        $this->quote = $row;

                        echo(json_encode($this->quote));
                        return true;
                    } else {
                        printf("Error: %s.\n", $stmt->error);
                        return false;
                    }
                }
            }
        }
    }

    // Delete Quote
	public function delete()
	{
        // Place Holder Data
        $temp = $this->id;

        // Create Query
        $query = 'SELECT q.id FROM ' . $this->table . ' q  WHERE q.id = ?';

        // Prepare Statement
        $stmt = $this->conn->prepare($query);

        // Bind ID
		$stmt->bindParam(1, $this->id);

		// Execute Query
		$stmt->execute();

		$row = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->id = $row;
       
        if($this->id === false){
            echo json_encode(array('message' => 'No Quotes Found'));
                exit();
        } else {
            // Place Holder Data
            $this->id = $temp;

            // Create Query
            $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';

            // Prepare Statement
            $stmt = $this->conn->prepare($query);

            // Clean Data
            $this->id = htmlspecialchars(strip_tags($this->id));

            // Bind Data
            $stmt->bindParam(':id', $this->id);
            
            if($stmt->execute()){
                $array = array('id' => $this->id);
                echo(json_encode($array));
                return true;
            } else {
                printf("Error: %s.\n", $stmt->error);
                return false;
            }
        }
	}
}