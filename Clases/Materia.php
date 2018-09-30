<?php
/**
 * Entidad materia
 */
require_once "Archivo.php";

class Materia 
{
	public $nombre;
	public $codigoMateria;
	public $cupo;
	public $aula;
	
	function __construct($nombre, $codigoMateria, $cupo, $aula){
		$this->nombre = $nombre;
		$this->codigoMateria = $codigoMateria;
		if((int)$cupo>0){
			$this->cupo = (int)$cupo;
		}
		else{
			$this->cupo = 0;
		}
		$this->aula = $aula;
	}

	/* =============================================================================================
    		API 
       =============================================================================================
    */

     /* 
    =====================================
    		Agrega una materia
    =====================================
    */
     public static function cargarMateria(){
     	Archivo::crearArchivo("materias");
     	
     	$arrayMaterias = Archivo::__Leer("Archivos/materias.json");

     	$unaMateria = new Materia($_POST["nombre"], $_POST["codigoMateria"],$_POST["cupo"],$_POST["aula"]);

     	$datosMateria = [
     		"nombre" => $unaMateria->nombre,
			"codigoMateria" => $unaMateria->codigoMateria,
     		"cupo" => $unaMateria->cupo,
     		"aula" => $unaMateria->aula
     	];

     	$arrayMaterias [] = $datosMateria;

     	try{
     		if(file_exists("Archivos/materias.json"))
	    	{
		    	$file = fopen("Archivos/materias.json", "c");
		    	Archivo::__Escribir($arrayMaterias, $file);
	    	}
     	}catch(Exception $e){
     		throw $e->getMessage() . "\n";
     	}
     }
}