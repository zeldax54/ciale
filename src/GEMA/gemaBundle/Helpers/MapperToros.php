<?php
namespace GEMA\gemaBundle\Helpers;
/**
 * Created by PhpStorm.
 * User: AK0
 * Date: 22/06/2017
 * Time: 2:38
 */
 class MapperToros
{

   public function Mapa()
   {
       return array(
              'nombreinterno'=>0,//Nombre interno o codigo
              'nuevo'=>1,
              'facilidadparto'=>2,
              'CP'=>3,
              'apodo'=>4,
              'nacionalidad'=>5,
              'origen'=>6,
              'nombre'=>7,
              'criador'=>8,
              'propietario'=>9,
              'características'=>10,
              'evaluaciongenetica'=>11,
              'lineagenetica'=>12,
              'padre'=>13,
              'madre'=>14,
              'padrepadre'=>15,//Abuelo paterno
              'padremadre'=>16,//Abuelo materno
              'madrepadre'=>17,//Abuela paterna
              'madremadre'=>18,//Abuela materna
              'padrepadrepadre'=>19,//Bisabuelo paterno
              'madrepadrepadre'=>20,//Bisabuela paterna
              'padremadrepadre'=>21,//Bisabuelo materno paterno (Padre de la madre del padre)
              'madremadrepadre'=>22,//Bisabuela materna paterna (Madre de la madre del padre)
              'padrepadremadre'=>23,//Bisabuelo paterno materna (Padre del padre de la madre)
              'madrepadremadre'=>24,//Bisabuela paterna materna (Madre del padre de la madre)
              'padremadremadre'=>25,//Bisabuelo materno materno (Padre de la madre de la madre)
              'madremadremadre'=>26,//Bisabuela materna materna (Madre de la madre de la madre)
              'rp'=>27,
              'HBA'=>28,
              'senasa'=>29,
              'fechanacimiento'=>30,//Nacimiento en excel
              'ADN'=>31,
              'circunferenciaescrotal'=>32,
              'largogrupa'=>33,
              'anchogrupa'=>34,
              'altogrupa'=>35,
              'largocorporal'=>36,
              'peso'=>37,
              'pn1'=>38,
              'p205d'=>39,
              'p365d'=>40,
              'p550d'=>41,
              'precio'=>44,

       );
   }
}