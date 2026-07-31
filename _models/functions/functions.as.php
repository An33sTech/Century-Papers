<?php
//don't encrypt this file
function _traitFileInclude($fileName)
{
    $webFile = __DIR__ . '/../traits/' . $fileName;
    include_once($webFile);
}
_traitFileInclude("multi_lang.php");
//Handel Multi_admin language or webLanguage special words


class dbFunction
{
    public $rowCount = '';
    public $rowLastId = '';
    public $rowException = '';
    public $hasException = false;
    private $db;
    private $LONG_VALUE_PREFIX = 'LONG';
    use multi_lang;
    //Handel Multi_admin language or webLanguage special words

    public function __construct()
    {
        if (isset($GLOBALS['db']))
            $this->db = $GLOBALS['db'];
        else
            $this->db = new Database();

        $this->error_reportingIBMS($this->db->showErrorOnLocal, $this->db->showErrorOnLive);
        header('Content-Type: text/html; charset=utf-8');
        header("X-Frame-Options: SAMEORIGIN");
    }

    /**
     * @param bool $localhost
     * @param bool $live
     * Error reportng work on localhost hide on live.
     */
    public function error_reportingIBMS($localhost = true, $live = false)
    {
        if ($_SERVER['HTTP_HOST'] == 'localhost') {
            if ($localhost) {
                error_reporting(-1);
            } else {
                error_reporting(0);
            }
        } else {
            if ($live) {
                error_reporting(-1);
            } else {
                error_reporting(0);
            }
        }
    }

    // simple print_r function

    public function prnt($data)
    {
        echo '<pre>';
        print_r($data);
        echo '</pre>';
    }

    // echo div when task perform succesfully

    public function success($txt, $echo = true)
    {
        if ($echo) {
            echo "<div
                style='text-align: center;
                padding:10px 0;
                border: 1px solid #26b3f7;
                background: #0972a5;
                font-weight: normal;
                color: #fff;
                border-radius:10px;
           '>$txt</div>";
        } else {
            return "<div
                style='text-align: center;
                padding:10px 0;
                border: 1px solid #26b3f7;
                background: #0972a5;
                font-weight: normal;
                color: #fff;
                border-radius:10px;
           '>$txt</div>";
        }
    }

    // echo div when task perform fail

    public function fail($txt, $echo = true)
    {
        if ($echo) {
            echo "<div
            style='text-align: center;
            padding:10px 0;
            border: 1px solid #26b3f7;
            background: rgb(248,108,24);
            font-weight: normal;
            color: #fff;
            border-radius:10px;
            '>$txt</div>";
        } else {
            return "<div
            style='text-align: center;
            padding:10px 0;
            border: 1px solid #26b3f7;
            background: rgb(248,108,24);
            font-weight: normal;
            color: #fff;
            border-radius:10px;
            '>$txt</div>";
        }
    }

    // Error show
    public function warning($txt, $echo = true)
    {
        if ($echo) {
            echo "<div
            style='text-align: center;
            padding:10px 0;
            border: 1px solid #26b3f7;
            background:#FF000E ;
            font-weight: normal;
            color: #fff;
            border-radius:10px;
            '>$txt</div>";
        } else {
            return "<div
            style='text-align: center;
            padding:10px 0;
            border: 1px solid #26b3f7;
            background:#FF000E ;
            font-weight: normal;
            color: #fff;
            border-radius:10px;
            '>$txt</div>";
        }
    }


    /* required query with ? mark in where clause value.. and array of value to set in where clause
       example
        $qry="INSERT INTO staff(id,name) values(?,?)";
        $ary=array($id,$name);
        setRow($qry,ary);

        if ary is not set, and just full query is there, its ok, it will work fine.
    */
    private $editPermission = true;
    private function setEditPermissions($queryCheckForDelete = false)
    {
        //For admin panel edit permissions
        //check before delete/edit/update query
        //need to check admin or webuser? if webuser then send true. else check permission

        global $functions;
        $editPer = $functions->pageEditPermission($queryCheckForDelete);
        if ($editPer !== '' && $editPer !== true && $this->editPermission == true) {
            echo $editPer;
            $this->editPermission = false;
            return false;
        }
        if ($this->editPermission === false) {
            return false;
        }
        return true;
    }
    
    public function setRowOld($query, $arr = null, $tryCatch = true)
    {
        if ($this->setEditPermissions($query) == false) {
            $this->rowCount = 0;
            return false;
        };

        try {
            if ($arr == false) {
                $tryCatch = false;
            }

            if ($this->db->inTransaction()) {
                $tryCatch = false;
            }

            if ($tryCatch)
                $this->db->beginTransaction();

            $sth = $this->db->prepare($query);
            $i = 0;
            if ($arr == null) {
                for ($i = 0; $i < $arr; $i++) {
                    $index = $i + 1;
                    $sth->bindParam($index, $arr[$i]);
                }
            } else {
                for ($i = 0; $i < sizeof($arr); $i++) {
                    $index = $i + 1;
                    $sth->bindParam($index, $arr[$i]);
                }
            }
            $sth->execute();
            $this->rowCount = $sth->rowCount();

            $this->rowLastId = $this->db->lastInsertId();

            if ($tryCatch)
                $this->db->commit();

            $this->error_submit(false);

            return $this->rowLastId;
        } catch (PDOException $e) {
            if ($tryCatch)
                $this->db->rollBack();
            $this->error_submit($e, $query);
            return $e;
        }
    }
    
    /**
     * Executes a database row insertion or update query.
     *
     * This method handles SQL queries that insert or update rows in the database.
     * It supports transaction management and deals with long values that exceed
     * column length limits by splitting them into manageable parts.
     *
     * @param string $query The SQL query to be executed.
     * @param array|null $arr An array of values to bind to the query placeholders. Default is null.
     * @param bool $tryCatch Whether to use try-catch for error handling. Default is true.
     * @return mixed The last inserted ID on success, or a PDOException on failure.
     */
    public function setRow($query, $arr = null, $tryCatch = true)
    {
        // Check and validate edit permissions for the query.
        if ($this->setEditPermissions($query) == false) {
            $this->rowCount = 0;
            return false;
        };

        try {
            // Disable try-catch block if $arr is false or if a transaction is already in progress.
            if ($arr == false) {
                $tryCatch = false;
            }

            if ($this->db->inTransaction()) {
                $tryCatch = false;
            }

            if ($tryCatch)
                $this->db->beginTransaction();

            $sth = $this->db->prepare($query);

            $longValues = [];

            $primaryKeyValue = null;
            
            // Bind parameters to the SQL statement if provided.
            if ($arr != null) {
                if (stripos($query, 'UPDATE') !== false) {
                    $tableName = $this->extractTableName($query);
                    $primaryKey = $this->getPrimaryKey($tableName);
                    $columnNamesWhere = $this->extractColumnNamesAfterWhere($query);
                    if(!empty($columnNamesWhere)){
                        $parameterValues = $this->extractParameterValues($query, $arr);
                        $primaryKeyValue = $this->extractPrimaryKeyValue($tableName, $primaryKey, $columnNamesWhere, $parameterValues);
                    }  
                }
                $tableName = $this->extractTableName($query);
                $columnNames = $this->extractColumnNames($query);

                for ($i = 0; $i < sizeof($arr); $i++) {
                    $columnName = $columnNames[$i % count($columnNames)] ?? "";
                    $columnName = str_replace('`', '', $columnName);

                    $columnLength = $this->getColumnLength($tableName, $columnName);
                    
                    $value = $arr[$i];
                    $valueLength = strlen($value);

                    // Handle values exceeding column length by storing them separately.
                    if ($columnLength !== false && ($valueLength > $columnLength)) {
                        if ($primaryKeyValue !== NULL) {
                            // Check if a long value already exists and delete it if so
                            $identity = $this->LONG_VALUE_PREFIX . ':' . $tableName . ':' . $columnName . ':' . $primaryKeyValue;
                            $this->deleteLongValue($identity);
                        }
                        
                        $longValues[] = [
                            'index' => $i,
                            'columnName' => $columnName,
                            'value' => $value,
                            'columnLength' => $columnLength
                        ];

                        $arr[$i] = null;
                    }

                    $index = $i + 1;
                    $sth->bindParam($index, $arr[$i]);
                }
            }

            $sth->execute();
            $this->rowCount = $sth->rowCount();

            if (stripos($query, 'UPDATE') !== false && $primaryKeyValue !== NULL) {
                $this->rowLastId = $primaryKeyValue;
            }else{
                $this->rowLastId = $this->db->lastInsertId();
            }

            // Handle any long values by storing them in another table and updating the original table.
            foreach ($longValues as $longValue) {
                $primaryKeyValue = $primaryKeyValue ?? $this->rowLastId;
                $identity = $this->LONG_VALUE_PREFIX . ':' . $tableName . ':' . $longValue['columnName'] . ':' . $primaryKeyValue;
                
                $this->handleLongValue('long_values', $identity, $longValue['columnName'], $longValue['value'], $longValue['columnLength']);
                $columnName = $longValue['columnName'];
                
                $primaryKey = $this->getPrimaryKey($tableName);
                
                $updateQuery = "UPDATE $tableName SET $columnName = ? WHERE $primaryKey = ?";
                $stmt = $this->db->prepare($updateQuery);
                $stmt->execute([$identity, $primaryKeyValue]);
            }
            
            if ($tryCatch)
                $this->db->commit();

            $this->error_submit(false);

            return $this->rowLastId;
        } catch (PDOException $e) {
            if ($tryCatch)
                $this->db->rollBack();
            $this->error_submit($e, $query);
            return $e;
        }
    }

    /**
     * Extracts the table name from the SQL query.
     *
     * @param string $query The SQL query.
     * @return string|null The table name, or null if not found.
     */
    private function extractTableName($query)
    {
        preg_match('/\b(INSERT INTO|UPDATE)\b\s*`?(\w+)`?/i', $query, $matches);
        return $matches[2] ?? null;
    }

    /**
     * Extracts the column names from the SQL query.
     *
     * @param string $query The SQL query.
     * @return array An array of column names.
     */
    private function extractColumnNames($query)
    {
        if (preg_match('/\bINSERT INTO\b\s*`?\w+`?\s*\(([^)]+)\)\s*\bVALUES\b\s*\(([^)]+)\)/i', $query, $matches)) {
            $columns = isset($matches[1]) ? $matches[1] : '';
            preg_match_all('/`?(\w+)`?/i', $columns, $columnMatches);
            $values = isset($matches[2]) ? $matches[2] : '';
            preg_match_all('/(?:\?|\'[^\']*\'|[0-9]+)/i', $values, $valueMatches);
            $filteredColumns = [];
            if (is_array($valueMatches)) {
                foreach ($valueMatches[0] as $key => $value) {
                    if ($value === '?') {
                        $filteredColumns[] = $columnMatches[1][$key];
                    }
                }
            }
        
            return $filteredColumns;
        }
        
        if (preg_match('/\bINSERT INTO\b\s*`?\w+`?\s*\bSET\b\s+(.+)/i', $query, $matches)) {
            $columns = isset($matches[1]) ? $matches[1] : '';
            preg_match_all('/`?(\w+)`?\s*=\s*(?:\?|[^,]+)/i', $columns, $columnMatches);
            $filteredColumns = [];
            if(is_array($columnMatches)){
                foreach($columnMatches[0] as $key => $value){
                    if (stripos($value, '?') !== false) {
                        $filteredColumns[] = $columnMatches[1][$key];
                    }
                }
            }
            
            return $filteredColumns;
        }
        
        if (preg_match('/\bUPDATE\b\s*`?\w+`?\s*\bSET\b\s*((?:`?\w+`?\s*=\s*[^,]+,?\s*)+)/i', $query, $matches)) {
            $columns = isset($matches[1]) ? $matches[1] : '';
            preg_match_all('/`?(\w+)`?\s*=\s*[^,]+/i', $columns, $columnMatches);
            return isset($columnMatches[1]) ? array_map('trim', $columnMatches[1]) : [];
        }
    
        return [];
    }


    private function extractColumnNamesAfterWhere($query)
    {
        $parts = preg_split('/\bWHERE\b/i', $query);
    
        // Check if there's a WHERE clause
        if (isset($parts[1])) {
            $whereClause = trim($parts[1]);
            
            $conditions = preg_split('/\bAND\b|\bOR\b/i', $whereClause);
            $columns = [];
            foreach ($conditions as $condition) {
                if(stripos($condition, '?') === false){
                    continue;
                }
                if (preg_match('/(`?\w+`?)\s*[=<>!]/', trim($condition), $matches)) {
                    $columnName = trim($matches[1], '` ');
                    $columns[] = $columnName;
                }
            }
            return $columns;
        }
    
        return [];
    }
    
    private function extractParameterValues($query, $arr)
    {
        $columnNamesWhere = $this->extractColumnNamesAfterWhere($query);

        $numWhereParams = count($columnNamesWhere);
        $whereParams = array_slice($arr, -$numWhereParams);

        if (count($whereParams) !== count($columnNamesWhere)) {
            throw new Exception("Number of parameters for WHERE clause does not match number of columns");
        }

        return array_combine($columnNamesWhere, $whereParams);
    }

    /**
     * Retrieves the maximum length of a column from the database schema.
     *
     * @param string $tableName The name of the table.
     * @param string $columnName The name of the column.
     * @return int The maximum length of the column.
     */
    private function getColumnLength($tableName, $columnName)
    {
        $sql = "SELECT CHARACTER_MAXIMUM_LENGTH FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = ? AND COLUMN_NAME = ?";
        $result = $this->getRow($sql, [$tableName, $columnName]);
        
        if(!$result){
            return false;
        }
        if(is_null($result['CHARACTER_MAXIMUM_LENGTH'])){
            return false;
        }

        return $result['CHARACTER_MAXIMUM_LENGTH'];
    }

    /**
     * Handles long values by splitting them into smaller parts and storing them in a separate table.
     *
     * @param string $tableName The name of the table to store the long values.
     * @param string $identity The unique identifier for the long value.
     * @param string $columnName The name of the column.
     * @param string $value The long value to be split and stored.
     * @param int $maxLength The maximum length of each part.
     */
    private function handleLongValue($tableName, $identity, $columnName, $value, $maxLength)
    {
        $parts = str_split($value, $maxLength);

        foreach ($parts as $sequence => $part) {
            $sql = "INSERT INTO $tableName (identity, column_name, part_value, part_sequence) VALUES (?, ?, ?, ?)";
            $sth = $this->db->prepare($sql);
            $sth->execute([$identity, $columnName, $part, $sequence + 1]);
        }
    }

    /**
     * Retrieves the primary key column name for a given table.
     *
     * @param string $tableName The name of the table.
     * @return string The name of the primary key column.
    */
    private function getPrimaryKey($tableName)
    {
        $sql = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE WHERE TABLE_NAME = ? AND CONSTRAINT_NAME = ?";
        $result = $this->getRow($sql, [$tableName, 'PRIMARY']);
        
        return $result['COLUMN_NAME'] ?? 'id';
    }

    private function deleteLongValue($identity)
    {
        $sql = "DELETE FROM long_values WHERE identity = ?";
        $sth = $this->db->prepare($sql);
        
        $sth->execute([$identity]);
    }
    
    private function extractPrimaryKeyValue($tableName, $primaryKey, $columnNamesWhere, $parameterValues)
    {
        $sql = "SELECT $primaryKey FROM $tableName ";
        
        $conditions = "";
        $params = [];
        $i=0;
        
        foreach ($columnNamesWhere as $column) {
            if ($column !== $primaryKey) {
                if($i>0)$conditions .= " AND";
                $i++;
                $conditions .= " $column = ? ";
                $params[] = $parameterValues[$column] ?? null;
            }
        }
        if(!empty($conditions)){
            $sql .= ' WHERE ' . $conditions;
        }
        
        $stmt2 = $this->db->prepare($sql);
        
        foreach ($params as $index => $value) {
            $stmt2->bindValue($index + 1, $value);
        }
        
        $stmt2->execute();
    
        $result = $stmt2->fetch(PDO::FETCH_ASSOC);
        
        if ($result) {
            return $result[$primaryKey];
        } else {
            return false;
        }
    }

    /**
     * Fetches and reassembles long values stored in a separate table.
     *
     * @param string $identity The unique identifier for the long value.
     * @param string $columnName The name of the column.
     * @return string The reassembled long value.
     */
    public function getLongValue($identity, $columnName)
    {
        try {
            $query = "SELECT part_value, part_sequence FROM long_values WHERE identity = ? AND column_name = ? ORDER BY part_sequence ASC";
            $results = $this->getRows($query, [$identity, $columnName]);

            $longValue = '';
            foreach ($results as $result) {
                $longValue .= $result['part_value'];
            }

            return $longValue;
        } catch (PDOException $e) {
            $this->error_submit($e, $query);
            return false;
        }
    }

    /**
     * Fetches a single row from the database.
     *
     * @param string $query The SQL query to be executed.
     * @param array|null $arr The parameters to bind to the query.
     * @param bool $tryCatch Whether to use transactions and error handling.
     * @param bool $array_with_key Whether the parameters array uses keys.
     * @return array|false The fetched row as an associative array, or false on error.
     */
    public function getRow($query, $arr = null, $tryCatch = true, $array_with_key = false)
    {
        try {
            if ($arr == false) {
                $tryCatch = false;
            }
            if ($this->db->inTransaction()) {
                $tryCatch = false;
            }

            if ($tryCatch)
                $this->db->beginTransaction();

            if (stristr($query, ' LIMIT ') == false) {
                $query .= " LIMIT 1";
            }

            $sth = $this->db->prepare($query);
            $i = 0;

            if ($array_with_key)
                foreach ($arr as $key => $val) {
                    $sth->bindValue(++$i, $val, PDO::PARAM_STR);
                }
            else
                if ($arr == null) {
                for ($i = 0; $i < $arr; $i++) {
                    $index = $i + 1;
                    $sth->bindValue($index, $arr[$i], PDO::PARAM_STR);
                }
            } else {
                for ($i = 0; $i < sizeof($arr); $i++) {
                    $index = $i + 1;
                    $sth->bindValue($index, $arr[$i], PDO::PARAM_STR);
                }
            }


            $sth->execute();
            $this->rowCount = $sth->rowCount();

            if ($tryCatch)
                $this->db->commit();

            $this->error_submit(false);
            $row = $sth->fetch(PDO::FETCH_ASSOC);

            if (is_array($row)) {
                foreach ($row as $key => $value) {
                    if (is_string($value) && strpos($value, $this->LONG_VALUE_PREFIX) === 0) {
                        $row[$key] = $this->getLongValue($value, $key);
                    }
                }
            }

            return $row;
        } catch (PDOException $e) {
            if ($tryCatch)
                $this->db->rollBack();
            $this->error_submit($e, $query);
        }
    }


    /**
     * Fetches multiple rows from the database.
     *
     * @param string $query The SQL query to be executed.
     * @param array|null $arr The parameters to bind to the query.
     * @param bool $tryCatch Whether to use transactions and error handling.
     * @param bool $assoc Whether to fetch results as an associative array.
     * @param bool $array_with_key Whether the parameters array uses keys.
     * @return array|false The fetched rows as an associative array, or false on error.
     */
    public function getRows($query, $arr = null, $tryCatch = true, $assoc = true, $array_with_key = false)
    {
        try {
            if ($arr == false) {
                $tryCatch = false;
            }

            if ($this->db->inTransaction()) {
                $tryCatch = false;
            }

            if ($tryCatch)
                $this->db->beginTransaction();

            $sth = $this->db->prepare($query);
            $i = 0;

            if ($array_with_key)
                foreach ($arr as $key => $val) {
                    $sth->bindValue(++$i, $val, PDO::PARAM_STR);
                }
            else {

                if (is_array($arr)) {
                    for ($i = 0; $i < sizeof($arr); $i++) {
                        $index = $i + 1;
                        $sth->bindValue($index, $arr[$i], PDO::PARAM_STR);
                    }
                } else {
                    for ($i = 0; $i < $arr; $i++) {
                        $index = $i + 1;
                        $sth->bindValue($index, $arr[$i], PDO::PARAM_STR);
                    }
                }
            }

            $sth->execute();
            $this->rowCount = $sth->rowCount();

            if ($tryCatch)
                $this->db->commit();

            $this->error_submit(false);
            $fetchMode = $assoc ? PDO::FETCH_ASSOC : PDO::FETCH_NUM;
            $rows = $sth->fetchAll($fetchMode);

            foreach ($rows as &$row) {
                foreach ($row as $key => $value) {
                    if (is_string($value) && strpos($value, $this->LONG_VALUE_PREFIX) === 0) {
                        $row[$key] = $this->getLongValue($value, $key);
                    }
                }
            }
            return $rows;
        } catch (PDOException $e) {
            if ($tryCatch)
                $this->db->rollBack();
            $this->error_submit($e, $query);
        }
    }

    /**
     * Executes a query to insert or update a row in the database, and optionally inserts related rows into a secondary table.
     *
     * @param string $query The primary SQL query to be executed.
     * @param array $arry The parameters to bind to the primary query.
     * @param string $valFormat The value format for the secondary query.
     * @param array $arry2 The parameters to bind to the secondary query.
     * @param string|false $query2 The optional secondary SQL query to be executed.
     * @param bool $tryCatch Whether to use transactions and error handling.
     * @return int|false The number of affected rows, or false on error.
     */
    public function setRowMultiTable($query, $arry, $valFormat, $arry2, $query2 = false, $tryCatch = true)
    {
        try {
            if ($this->db->inTransaction()) {
                $tryCatch = false;
            }

            if ($tryCatch)
                $this->db->beginTransaction();

            $this->setRow($query, $arry, false);
            $lastId = $this->rowLastId;

            if ($query2 != false) {
                for ($i = 0; $i < sizeof($arry2); $i++) {
                    $query2 .= "('$lastId',$valFormat),";
                }
                $query2 = trim($query2, ",");

                $sth = $this->db->prepare($query2);
                $sth->execute($arry2);
            }

            if ($tryCatch)
                $this->db->commit();

            $this->error_submit(false);
            return $this->rowCount;
        } catch (PDOException $e) {
            if ($tryCatch)
                $this->db->rollBack();
            bs_alert::warning("Some required fields are empty!");
            $this->error_submit($e);
        }
    }


    // submit error to Imedia... PENDING
    private $errorNo = 1;
    public function error_submit($e, $query = '')
    {
        $exec = false;
        if ($e === false) {
            $this->hasException = false;
            $this->rowException = "";
        } else {
            $this->hasException = true;
            $this->rowException = $e->errorInfo[2];

            if ($_SERVER['HTTP_HOST'] == 'localhost' && $this->db->showErrorOnLocal) {
                $exec = true;
            } else if ($this->db->showErrorOnLive) {
                $exec = true;
            }

            if ($exec) {
                if ($this->errorNo <= 4) {
                    $errorCookie = "Exce_" . $this->errorNo;
                    $errorDetailLink = WEB_URL . "/error.php?errorId=$errorCookie";
                    echo $error = "<pre>Manual Exception From Function class. : " . $e->getMessage()
                        . "<br>For Detail :  <a href='$errorDetailLink' target='_blank'>$errorCookie</a></pre>";
                    $error .= "<br>Query : $query <br>";

                    $error_detail = $e->getTrace(); //error throw from where?

                    $error = $error . print_r($error_detail, true);
                    $_SESSION['error'][$errorCookie] = $error;
                    //use of session becase,, cooking show error, or size limit
                    $this->errorNo++;
                }
            }
        }
    }
}
