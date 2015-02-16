<?php  
           
    function getDepartmentSalaries($db_host, $db_name, $db_user, $db_password)  {   
        $mysqli = new MySQLI($db_host, $db_user, $db_password, $db_name);
    
        $sql = "select dept_name, avg(salary) as avg_salary from instructor group by dept_name;"; 
        
        $result = $mysqli->query($sql);
        
        $departments = array(); 
        
        while($row = $result->fetch_assoc()) {        
            $departments[] = $row;            
        }       

        $result->free(); 
        $mysqli->close();
        
        return $departments;
    }
    
    function getInstructors($db_host, $db_name, $db_user, $db_password, $orderBy, $order, $limit, $offset)  {   
        $mysqli = new MySQLI($db_host, $db_user, $db_password, $db_name);
        $orderBy = $orderBy === NULL ? "ID" : $orderBy;
        $order = $order === NULL ? "DESC" : $order;
        $limit = $limit === NULL ? 10 : $limit;
        $offset = $offset === NULL ? 0 : $offset;
    
        $sql = sprintf("select * from instructor order by %s %s limit %d offset %d", 
                $orderBy, $order, $limit, $offset); 
        
        $result = $mysqli->query($sql);
        
        $instructors = array(); 
        
        while($row = $result->fetch_assoc()) {        
            $instructors[] = $row;            
        }       

        $result->free(); 
        $mysqli->close();
        
        return $instructors;
    }
    
    function addInstructor($db_host, $db_name, $db_user, $db_password, 
            $id, $name, $dept_name, $salary)  {   
        $mysqli = new MySQLI($db_host, $db_user, $db_password, $db_name);
        
        if (!($stmt = $mysqli->prepare("INSERT INTO instructor(ID, name, dept_name, salary) VALUES (?, ?, ?, ?)"))) {
            echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }
        
        if (!$stmt->bind_param("sssd", $id, $name, $dept_name, $salary)) {
            echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
        }       
    
        $success = $stmt->execute();
                
        $mysqli->close();
        
        return $success;
    }
    
    function deleteInstructor($db_host, $db_name, $db_user, $db_password, $id)  {   
        $mysqli = new MySQLI($db_host, $db_user, $db_password, $db_name);
        
        if (!($stmt = $mysqli->prepare("DELETE FROM instructor where ID = ?"))) {
            echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }
        
        if (!$stmt->bind_param("s", $id)) {
            echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
        }       
    
        $success = $stmt->execute();
                
        $mysqli->close();
        
        return $success;
    }
?>
