<?php
class classBD extends Database{
	function deleteDataTable($post){
		try{
				$sql = "DELETE FROM {$post['tabla']}  where {$post['columna']} =  {$post['id']} ";
				$query = $this->conn->prepare($sql);
				$query->execute();
				return true;
			}
			catch(PDOException $e){
				echo $e->getMessage();
				return false;
				}
	}
	function getMaxIdTable($tabla,$id){
		try{
				$sql = "SELECT max({$id}) as lastId FROM  {$tabla} ";
				$query = $this->conn->prepare($sql);
				$query->execute();
				$row = $query->fetchAll(PDO::FETCH_ASSOC);
				return $row[0];
			}
			catch(PDOException $e){
				echo $e->getMessage();
				return false;
				}
	}
	function getCountTable($tabla,$campo,$condicion){
		try{
				$sql = "SELECT count({$campo}) as countData FROM  {$tabla}  {$condicion}";
				$query = $this->conn->prepare($sql);
				$query->execute();
				$row = $query->fetchAll(PDO::FETCH_ASSOC);
				return $row[0];
			}
			catch(PDOException $e){
				echo $e->getMessage();
				return false;
				}
	}
	function getDataTable($tabla,$condicion=""){
		try{
				$sql = "SELECT * FROM  {$tabla} {$condicion}";
				$query = $this->conn->prepare($sql);
				$query->execute();
				$row = $query->fetchAll(PDO::FETCH_ASSOC);
				return $row;
			}
			catch(PDOException $e){
				echo $e->getMessage();
				return false;
				}
	}
	function getDataTableMultiples($tablas,$condicion){
		try{
			$camposTablas = array();
				$tables = array();
				foreach ($tablas as $key => $value) {
					if(is_array($value)){
					    for ($i=0; $i <count($value) ; $i++) { 
							array_push($camposTablas, "{$key}.{$value[$i]}");
					    }
					}else{
						array_push($camposTablas, "{$key}.$value");
					}
					array_push($tables, "{$key}");
				}
				$sentenciaSql = array(" SELECT ", implode(",", $camposTablas), " FROM ", implode(",", $tables), $condicion);
				$sql = implode(" ", $sentenciaSql);
				$query = $this->conn->prepare($sql);
				$query->execute();
				$row = $query->fetchAll(PDO::FETCH_ASSOC);
				return $row;
			}
			catch(PDOException $e){
				echo $e->getMessage();
				return false;
				}
	}
	function actualizarDataTable($tabla,$post,$condicion) {
		try{
			unset($post['val']);
				$arraySentencia = array();
				foreach ($post as $key => $value) {
					$sent = " `{$key}` = :{$key} ";
					array_push($arraySentencia, $sent);
				}
				$sentenciaSql =  "UPDATE {$tabla} SET ".implode(',', $arraySentencia) .$condicion;
				$sql = $this->conn->prepare($sentenciaSql);
				foreach ($post as $key2 => &$value2) {
				 	$sql->bindParam(':'.$key2, $value2);
				}
				$sql->execute();
				return true;
			}
			catch(PDOException $e){
				echo $e->getMessage();
				return false;
			}
	}
	function insertarDataTable($tabla,$post) {
		try{
			$sentenciaSql = "INSERT INTO `{$tabla}` (";
				$campos = array();
				foreach ($post as $key => $value) {
					array_push($campos, "`{$key}`");
				}				
				$sentenciaSql .= implode(",", $campos)." ) VALUES ( ";
				$valuesEncode = array();
				foreach ($post as $key2 => $value2) {
					array_push($valuesEncode, ":{$key2}");
				}
				$sentenciaSql .= implode(",", $valuesEncode)." ) ";
				$sql = $this->conn->prepare($sentenciaSql);
				foreach ($post as $key3 => &$value3) {
				 	$sql->bindParam(':'.$key3, $value3);
				}
				$sql->execute();				
				return true;
		}
		catch(PDOException $e){
			echo $e->getMessage();
			return false;
		}
	}
}
$classBD = new classBD();
?>