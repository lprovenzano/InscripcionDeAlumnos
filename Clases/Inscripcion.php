<?php
/*
*	Entidad Inscripcion
*/
require_once "Archivo.php";

class Inscripcion{

	/* =============================================================================================
    		API 
       =============================================================================================
    */
    /* 
    =====================================
    		Inscribe un alumno
    =====================================
    */
    public static function inscribirAlumno($nombre, $apellido, $email, $materia, $codigoMateria){
    	
    	Archivo::crearArchivo("inscripciones");
    	$arrayAlumnos = Archivo::__Leer("Archivos/alumnos.json");
    	$arrayMaterias = Archivo::__Leer("Archivos/materias.json");
    	$arrayIncripciones = Archivo::__Leer("Archivos/inscripciones.json");
    	
    	$cupoActual; //Auxiliar de cupo
		$i = -1; //Contador de indices array key->value

    	//Booleanos
    	$hayAlumno  = false;
    	$puedeInscribirse = false;
    	$hayMateria = false;

    	//Verifico que exista el alumno por medio de la pk
    	foreach ($arrayAlumnos as $claveAlumno => $valorAlumno) {
    		if(strtolower($valorAlumno["email"])==strtolower($email)){
    			$hayAlumno = true;
    		}

    	}

    	//Verifico que exista la materia por medio de la pk si el alumno existe
    	if($hayAlumno)
    	{
	    	foreach ($arrayMaterias as $claveMateria => $valorMateria) {
				$i++;
	    		if(strtolower($valorMateria["codigoMateria"]) == strtolower($codigoMateria)){
	    			$hayMateria = true;
					$cupoActual = $valorMateria["cupo"];
					break;
				}
	    	}
	    	if($hayMateria){
	    		if($cupoActual>0){
					$puedeInscribirse = true;
	    		}
	    		else{
	    			return "No hay cupo :(";
	    		}
	    	}else{
	    		return "No existe el código de materia ". $codigoMateria;
	    	}
    	}
    	else{
    		return "No existe el alumno ". $nombre. " " . $apellido . " (".$email.")";
    	}

    	if($puedeInscribirse){

    		//Inscribo al alumno
    		$arrayIncripcion = [
    			"nombre" => $nombre,
    			"apellido" => $apellido,
    			"email" => $email,
    			"materia" => $materia,
    			"codigoMateria" => $codigoMateria,
    		];

			$arrayIncripciones [] = $arrayIncripcion;
			
    		try{
		    	if(file_exists("Archivos/inscripciones.json")){
			    	$file = fopen("Archivos/inscripciones.json", "w+");
					Archivo::__Escribir($arrayIncripciones, $file);
					$arrayMaterias[$i]["cupo"] -= 1;
		    	}
		    }catch(Exception $e){
	     		throw $e->getMessage() . "\n";
	     	}
			 
	     	//Reescribo las materias con sus nuevos cupos
	     	try{
		    	if(file_exists("Archivos/materias.json")){
			    	$file = fopen("Archivos/materias.json", "w+");
			    	Archivo::__Escribir($arrayMaterias, $file);
		    	}
		    }catch(Exception $e){
	     		throw $e->getMessage() . "\n";
			 }
    	}
		
	}
	
	/*
	===================================================
    		Retorna las inscripciones actuales
    ====================================================
	*/
	
	public static function inscripciones()
	{
		if(isset($_GET["apellido"]) && !empty($_GET["apellido"])){
			$apellido = $_GET["apellido"];
		}
		if(isset($_GET["materia"]) && !empty($_GET["materia"])){
			$materia = $_GET["materia"];
		}
		
		$arrayInscripciones = [];
		$tabla ="";
		try{
			if(file_exists("Archivos/inscripciones.json")){
				$arrayInscripciones = Archivo::__Leer("Archivos/inscripciones.json");
			}
			if(empty($arrayInscripciones)){
				return "No hay inscripciones aún.";
			}
		}catch(Exception $e){
			throw $e->getMessage() . "\n";
		}
		
		//Abro la tabla
		$tabla .="<table style='width:100%;text-align:center'><tr>";
		
		//Tomo cualquier array, todos tienen misma clave=>valor
		$cabecerasTablas = array_keys($arrayInscripciones[0]); 
		
		//Genero la cabecera
		foreach ($cabecerasTablas as $itemTitulo) {
			$tabla.="<th>" . $itemTitulo . "</th>";
		}

		$tabla .="</tr>";
		
		//Cuerpo de la tabla
		
		if(!empty($materia))
		{
			foreach(array_values($arrayInscripciones) as $item){
				if(strtolower($item["materia"]) == strtolower($materia)){
					$tabla.="<tr>
						 	 <td>" . $item["nombre"] . "</td>";
					$tabla.="<td>" . $item["apellido"] . "</td>";
					$tabla.="<td>" . $item["email"] . "</td>";
					$tabla.="<td>" . $item["materia"] . "</td>";
					$tabla.="<td>" . $item["codigoMateria"] . "</td>
							 </tr>";
				}
				
			}
			$tabla .="</table>";
		}
		else if(!empty($apellido))
		{
			foreach(array_values($arrayInscripciones) as $item){
				if(strtolower($item["apellido"]) == strtolower($apellido)){
					$tabla.="<tr>
						 	 <td>" . $item["nombre"] . "</td>";
					$tabla.="<td>" . $item["apellido"] . "</td>";
					$tabla.="<td>" . $item["email"] . "</td>";
					$tabla.="<td>" . $item["materia"] . "</td>";
					$tabla.="<td>" . $item["codigoMateria"] . "</td>
							 </tr>";
				}
				
			}
			$tabla .="</table>";
		}else{
			
			foreach ($arrayInscripciones as $clave => $valor) {
				$tabla.="<tr>
						 <td>" . $valor["nombre"] . "</td>";
				$tabla.="<td>" . $valor["apellido"] . "</td>";
				$tabla.="<td>" . $valor["email"] . "</td>";
				$tabla.="<td>" . $valor["materia"] . "</td>";
				$tabla.="<td>" . $valor["codigoMateria"] . "</td>
						</tr>";
			}
			$tabla .="</table>";
		}
		
		return $tabla;
		
	}
}