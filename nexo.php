<?php
require_once "Modulos.php";

if(isset($_REQUEST["caso"]) && !empty($_REQUEST["caso"])){
	try{
		switch($_REQUEST["caso"]){
	
			case "cargarAlumno":
				Alumno::cargarAlumno();
			break;
			case "consultarAlumno":
				Alumno::consultarAlumno($_GET["apellido"]);
			break;
			case "cargarMateria":
				Materia::cargarMateria();
			break;
			case "inscribirAlumno":
				Inscripcion::inscribirAlumno($_GET["nombre"], $_GET["apellido"], $_GET["email"], $_GET["materia"], $_GET["codigoMateria"]);
			break;
			case "inscripciones":
				Inscripcion::inscripciones();
			break;
			case "modificarAlumno":
				Alumno::modificarAlumno();
			break;
			case "alumnos":
				Alumno::alumnos();
			break;
		
		}
	}catch(Exception $e){
		echo $e->getMessage() . "\n";
	}
}else{
	echo "No existe un caso válido en este contexto.";
}