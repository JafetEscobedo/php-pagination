<?php  
/**
* Clase para obtener todos los registros de una o varias tablas
*/
require_once "conection.php";

class PaginationModel extends Conection
{
	private $query;

	public function __construct($query, $custom = false)
	{
		parent::__construct();

		if ($custom)
		{
			$this->query = $query;
		}
		else
		{
			$this->query = "SELECT * FROM " . addslashes($query);
		}
	}

	public function get_rows($start, $range, $order_by, $sort)
	{
		try
		{
			$query = $this->query
				 . " ORDER BY " 
				 . addslashes($order_by) . " " 
				 . addslashes($sort)
				 . " LIMIT :START, :RANGE";
				 
			$query = $this->conection->prepare($query);
			$query->bindParam(":START", $start, PDO::PARAM_INT);
			$query->bindParam(":RANGE", $range, PDO::PARAM_INT);				 
			$query->execute();
			$rows = $query->fetchAll(PDO::FETCH_NUM);
			$query->closeCursor();		
			return $rows;
		}
		catch (Exception $ex)
		{
			return null;
		}
	}

	public function get_columns_names()
	{
		try
		{
			$query = $this->query;
			$query = $this->conection->query($query);			
			$columns = $query->columnCount();
			$result = array();
						
			for ($i = 0; $i < $columns; $i++)
			{
				$column_info = $query->getColumnMeta($i);
				$result[] = $column_info["name"];
			}
			
			$query->closeCursor();
			return $result;			
		}
		catch (Exception $e)
		{
			return null;
		}		
	}

	public function length()
	{
		try
		{
			$query = $this->query;
			$query = $this->conection->query($query);
			$rows = $query->fetchAll(PDO::FETCH_NUM);
			$query->closeCursor();
			return count($rows);
		}
		catch (Exception $e)
		{
			return null;
		}		
	}
}
?>