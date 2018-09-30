<?php

require_once "Archivo.php";

/**
 * Entidad alumno
 */
class Alumno 
{
	public $nombre;
	public $apellido;
	public $email;
	public $foto;
	
	function __construct($nombre, $apellido, $email){
		$this->nombre = $nombre;
		$this->apellido = $apellido;
		$this->email = $email;
	}

	function getEmail()
	{
		return $this->email;
	}

	public function obtenerNombreArchivo($ruta)
    {
		$extension = pathinfo($ruta);
		//Fuerzo que todo sea JPG así no se genera duplicidad de imágenes del mismo alumno con distinta extensión
		//Así backupeo todas.
		if($extension["extension"]== "jpg")
		{
			return $this->apellido . "." . $extension["extension"];
		}
		else{
			return $this->apellido . ".jpg";
		}
	}
	
	public static function __Existe($destino)
    {
        if(file_exists("./backUp"))
        {
        }
        else
        {
            mkdir("./backUp",7777);
        }

        if(file_exists($destino))
        {
            return true;
        }

        return false;
    }

    public static function MoverFichero($origen, $destino)
    {
        $rutaNueva = pathinfo($destino);
        $fecha = date("dmY-H_i_s");
        return rename($origen, $rutaNueva["dirname"] . "/" . $rutaNueva["filename"] . "_" .$fecha . "." . $rutaNueva["extension"]);
    }

    /* =============================================================================================
    		API 
       =============================================================================================
    */

    /* 
    =======================
    	Agrega un alumno 
    =======================
    */
	public static function cargarAlumno(){
	
		Archivo::crearArchivo("alumnos");
		Archivo::crearCarpetaFotos("./Fotos");
		Archivo::crearCarpetaFotos("./backUp");

		$arrayAlumnos = Archivo::__Leer("Archivos/alumnos.json");

		$unAlumno = new Alumno($_POST["nombre"], $_POST["apellido"], $_POST["email"], $_FILES["foto"]);

		$nombreDelArchivo = $_FILES["foto"]["name"];

	    $archivoConPath = "Fotos/".$unAlumno->obtenerNombreArchivo($nombreDelArchivo);
	    
	    move_uploaded_file($_FILES["foto"]["tmp_name"], $archivoConPath);

	    $datosAlumno = [
	        "nombre" => $unAlumno->nombre,
	        "apellido" => $unAlumno->apellido,
	        "email" => $unAlumno->email,
	        "foto" => $archivoConPath
	    ];

	    $arrayAlumnos [] = $datosAlumno;

	    try{

	    	if(file_exists("Archivos/alumnos.json")){
		    	$file = fopen("Archivos/alumnos.json", "c");
		    	Archivo::__Escribir($arrayAlumnos, $file);
	    	}
	    }catch(Exception $e){
     		throw $e->getMessage() . "\n";
     	}

	}

	/* 
	========================================================
    	Retorna un alumno dado que exista su apellido en JSON
    =========================================================
    */

	public static function consultarAlumno($consulta){
		
		$dato = strtolower($consulta);

		$arrayAlumnos = Archivo::__Leer("Archivos/alumnos.json");

		foreach ($arrayAlumnos as $clave => $valor) {
			if(strtolower($valor["apellido"])==$dato)
			{
				return $valor;
			}
			
		}

		return "No existe alumno con apellido " . $dato;
	}

	/* 
	========================================================
    			Modifica un alumno
    =========================================================
    */
	public static function modificarAlumno(){
		Archivo::crearArchivo("alumnos");
		Archivo::crearCarpetaFotos("./Fotos");
		Archivo::crearCarpetaFotos("./backUp");
		$arrayAlumnos = Archivo::__Leer("Archivos/alumnos.json");
		$existe = false;
		$i = -1;

		$unAlumno = new Alumno($_POST["nombre"], $_POST["apellido"], $_POST["email"], $_FILES["foto"]);

		$nombreDelArchivo = $_FILES["foto"]["name"];

		foreach ($arrayAlumnos as $clave => $valor) {
			$i++;
			if($valor["email"]==$unAlumno->email){
				$viejoAlumno = new Alumno($valor["nombre"], $valor["apellido"], $valor["email"]);
				$existe = true;
				break;
			}
		}

		
		if($existe){
			$archivoConPath = "Fotos/" . $viejoAlumno->obtenerNombreArchivo($nombreDelArchivo);
			if(Alumno::__Existe($archivoConPath))
    		{
        		Alumno::MoverFichero($archivoConPath, "./backUp/".$viejoAlumno->obtenerNombreArchivo($nombreDelArchivo));
    		}
		
			move_uploaded_file($_FILES["foto"]["tmp_name"], $archivoConPath);

			$arrayAlumnos[$i]["nombre"] = $unAlumno->nombre;
			$arrayAlumnos[$i]["apellido"] = $unAlumno->apellido;
		}

		try{
			if(file_exists("Archivos/alumnos.json")){
				$file = fopen("Archivos/alumnos.json", "w+");
				Archivo::__Escribir($arrayAlumnos, $file);
			}
		}catch(Exception $e){
			throw $e->getMessage() . "\n";
		}
	}

	/* 
	========================================================
    		Trae todos los alumnso y genera una tabla
    =========================================================
    */

	public static function alumnos(){
		$arrayAlumnos = [];
		$tabla ="";
		if(!file_exists("Archivos/alumnos.json")){
			return "No hay alumnos aún.";
		}

		$arrayAlumnos = Archivo::__Leer("Archivos/alumnos.json");
		
		//Abro la tabla
		$tabla .="<table style='width:100%;text-align:center'><tr>";
		
		//Tomo cualquier array, todos tienen misma clave=>valor
		$cabecerasTablas = array_keys($arrayAlumnos[0]); 
		
		//Genero la cabecera
		foreach ($cabecerasTablas as $itemTitulo) {
			$tabla.="<th>" . $itemTitulo . "</th>";
		}

		$tabla .="</tr>";
		
		//Cuerpo de la tabla

		foreach ($arrayAlumnos as $clave => $valor) {
			$tabla.="<tr>";
			$tabla.="<td>" . $valor["nombre"] . "</td>";
			$tabla.="<td>" . $valor["apellido"] . "</td>";
			$tabla.="<td>" . $valor["email"] . "</td>";
			$tabla.="<td><img style='width:100px;height:100px' src='" . $valor["foto"] . "' alt='foto alumno'></td>
					</tr>";
		}
		$tabla .="</table>";
		
		return $tabla;
	}

	

    
}