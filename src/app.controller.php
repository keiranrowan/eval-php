<?php
session_start();

class AppController {
    private $db = [];

    function __construct() {
        if (!empty($_SESSION['db'])) {
            $this->db = $_SESSION['db'];
        }
    }
    
    public function create($input) {
    	if (empty($input['title'])) {
            return ['error' => 'Title is a required parameter'];
        }

        if (empty($input['author'])){
            return ['error' => 'Author is a required parameter'];
        }

        if (strlen($input['title']) <= 1) {
            return ['error' => 'Title must be longer than 1 character'];
        }

        if (strlen($input['author']) <= 2) {
            return ['error' => 'Author must be longer than 2 characters'];
        }
        
        if (in_array($input['title'], array_column($this->db, "title"))) {
            return ['error' => 'Title must be unique'];
        }

        array_push($this->db, $input);
        $_SESSION['db'] = $this->db;

        // Return created record
        return [
            'error' => '',
            'data' => $input,
        ];
    }

    public function reset() {
        $_SESSION['db'] = [];
    }

    public function find($input) {
        $compare = function($a, $b) {
            return strcmp($a['title'], $b['title']);
        };
                 
        if (empty($input)) {
            usort($this->db, $compare);
            
            return $this->db;
        }
        
        $filtered = array_filter($this->db, function($item) use($input) {
            return $item['author'] === $input;
        });
        
        usort($filtered, $compare);
        
        return $filtered;
    }

}
