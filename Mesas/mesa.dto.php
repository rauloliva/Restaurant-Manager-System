<?php
    class Mesa{
        private $mesa;
        private $mesero;
        private $estatus;

        public function __GET($k){ return $this->$k; }
	    public function __SET($k, $v){ $this->$k = $v; }
    }
?>