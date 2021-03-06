<?php
 
class Sqlquery {
    protected $_dbHandle;
    protected $_result;
 
    /**
     * Connect to database
     * @param  string $address host, eg localhost or IP address
     * @param  string $account account username
     * @param  string $pwd     password
     * @param  string $name    database name
     * @return int             1 on success, 0 on failure
     */
    function connect($address, $account, $pwd, $name) {
        $this->_dbHandle = new mysqli($address, $account, $pwd, $name);
        if ($this->_dbHandle) {
            if ($this->_dbHandle->select_db($name)) {
                return 1;
            }
            else {
                return 0;
            }
        }
        else {
            return 0;
        }
    }
 
    /**
     * Disconnect from database
     * @return int 1 on success, 0 on failure
     */
    function disconnect() {
        if ($this->_dbHandle->close() != 0) {
            return 1;
        }  else {
            return 0;
        }
    }


    /**
     * Sanitise string for input into database
     * @param  string $string raw string
     * @return string         escaped string
     */
    function sanitiseString($string){
        return $this->_dbHandle->real_escape_string($string);
    }


    /**
     * Sanitise array or string for input into database
     * @param  mixed $input raw array or string
     * @return mixed        escaped array or string
     */
    function sanitise($input){
        $value = is_array($input) ? array_map(array($this, 'sanitise'), $input) : $this->sanitiseString($input);
        return $value;
    }


    /** Add quotes around a string
     * @param  string $string raw string
     * @return string         quoted string
     */
    function addQuotesString($string){
        return "'" . $string . "'";
    }


    /**
     * Add quotes around all values in an array or string
     * @param  mixed $input raw array or string
     * @return mixed        quoted array or string
     */
    function addQuotes($input){
        $value = is_array($input) ? array_map(array($this, 'addQuotes'), $input) : $this->addQuotesString($input);
        return $value;
    }

     
    /**
     * Select all rows for this table
     * @return array results from this table
     */
    function selectAll() {
        $query = 'SELECT * FROM ' . $this->_table;
        return $this->query($query);
    }
     

    /**
     * Select row by id
     * @param  int $id id of the table row
     * @return array result for this id
     */
    function select($id) {
        $query = 'SELECT * FROM ' . $this->_table . ' WHERE id = ' . $this->sanitise($id);
        return $this->query($query, 1);    
    }

    // To do: swap to prepared statements
    function insert(array $data){
        if (empty($data)) {
            return false;
        }
        
        $data = $this->sanitise($data);
        $data = $this->addQuotes($data);
        $columns = implode(', ', array_keys($data));
        $values = implode(', ', array_values($data));

        $query = 'INSERT INTO ' . $this->_table . ' (' . $columns . ') VALUES (' . $values . ')';

        $this->query($query);
    }
 
     
    /**
     * Execute custom query and return results in array
     * @param  string  $query        sql query to be executed
     * @param  integer $singleResult 1 for if just a single result is wanted, 0 if not
     * @return array                 result of sql query (if any) loaded into an array
     */
    function query($query, $singleResult = 0) {
        $this->_result = $this->_dbHandle->query($query);
        
        if (preg_match("/select/i",$query)) {
        $result = array();
        $table = array();
        $field = array();
        $tempResults = array();
        $numOfFields = $this->_dbHandle->field_count;
        
        for ($i = 0; $i < $numOfFields; ++$i) {
            $fieldInfo = $this->_result->fetch_field();
            array_push($table, $fieldInfo->table);
            array_push($field, $fieldInfo->name);
        } 
         
            while ($row = $this->_result->fetch_row()) {
                for ($i = 0;$i < $numOfFields; ++$i) {
                    $table[$i] = trim(ucfirst($table[$i]),"s");
                    $tempResults[$table[$i]][$field[$i]] = $row[$i];
                }
                if ($singleResult == 1) {
                    $this->freeResult();
                    return $tempResults;
                }
                array_push($result, $tempResults);
            }
            $this->freeResult();
            return($result);
        }
         
    }

    /**
     * Get number of pages of content 
     * @param  integer $resultsPerPage Number of results to be shown on each page
     * @return int                     Number of pages
     */
    function getNumPages($resultsPerPage = 5){
        $query = "SELECT COUNT(*) numResults FROM " . $this->_table;
        $result = $this->query($query);
        $numResults = $result[0]['']['numResults'];
        $numPages = ceil($numResults / $resultsPerPage);
        return $numPages;
    }

    /**
     * Get results for a certain page number 
     * @param  int $pageNumber     Which page to be shown
     * @param  int $resultsPerPage How many results should be shown on each page
     * @return array               Array of results
     */
    function getPage($pageNumber = 1, $resultsPerPage = 5){
        $offset = ($pageNumber - 1) * $resultsPerPage;
        $query = "SELECT * FROM " . $this->_table . " ORDER BY id DESC LIMIT " . $offset . ', ' .  $resultsPerPage;
        return $this->query($query);
    }
 
    /**
     * Get number of rows in result
     * @return int number of rows
     */
    function getNumRows() {
        return $this->_result->num_rows;
    }
 
    /**
     * Free result set
     */
    function freeResult() {
        $this->_result->free();
    }
 
    /**
     * Get error
     * @return string error description
     */
    function getError() {
        return $this->_dbHandle->error;
    }

}