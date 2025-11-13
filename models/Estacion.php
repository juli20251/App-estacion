<?php
class Estacion {
    public $chipid;
    public $apodo;
    public $ubicacion;
    public $visitas;

    public function __construct($chipid, $apodo, $ubicacion, $visitas) {
        $this->chipid = $chipid;
        $this->apodo = $apodo;
        $this->ubicacion = $ubicacion;
        $this->visitas = $visitas;
    }
}
?>
