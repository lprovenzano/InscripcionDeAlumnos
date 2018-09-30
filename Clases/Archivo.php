<?php
/**
 * Entidad Archivos
 */
class Archivo 
{
	/* 
    =====================================
    		Crea un archivo
    =====================================
    */
	public static function crearArchivo($nombre){
		try{
			if(file_exists("./Archivos")){}

			else{
				mkdir("./Archivos",7777);
			}

			if(file_exists("./Archivos")){
				$file = fopen("./Archivos/" . $nombre .".json", "c");
			}

			else{
				    
				mkdir("./Archivos",7777);
				$file = fopen("./Archivos/" . $nombre . ".json", "w+");
			}
		}catch(Exception $e){
     		throw $e->getMessage() . "\n";
     	}
		
	}

	/* 
    =====================================
    		Crea directorio para fotos
    =====================================
    */
	 public static function crearCarpetaFotos($nombre){
	 	try{
	 		if(file_exists($nombre)){}
		
			else{
			    mkdir($nombre,7777);
			}
	 	}catch(Exception $e){
     		throw $e->getMessage() . "\n";
     	}
	}

	public static function __Existe($destino)
    {
    	try{
			if(file_exists("./Archivos")){}
		    
		    else{
		        mkdir("./Archivos",7777);
		    }

		    if(file_exists($destino)){
		        return true;
		    }

		    return false;
    	}catch(Exception $e){
     		throw $e->getMessage() . "\n";
     	}
        
    }
    /* 
    =====================================
    		Escribe en un json
    =====================================
    */
    public static function __Escribir($datos, $file)
    {
    	fwrite($file, json_encode($datos));
    	fclose($file);
    }

    /* 
    ==========================================
    		Lee un json, devuelve array.
    ==========================================
    */
    public static function __Leer($file)
    {
    	$arrayDatos = file_get_contents($file);
    	return json_decode($arrayDatos, true);
    }
}