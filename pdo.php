<?php
    class PDO_connection extends PDO{

        private $DBSettings = 'settings.ini';

        public function __construct(){
            $cnn = $this->start();
            parent::__construct($cnn['cnn']['dns'],$cnn['cnn']['username'],$cnn['cnn']['password']
                ,array(PDO::ATTR_PERSISTENT => true));
            parent::setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            parent::setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION); 
        }
        
        private function start(){
            try {
                if($settings = parse_ini_file($this->DBSettings,true)){
                    $connection = array(
                        "cnn" => array(
                            "dns" =>  $settings['database']['driver'].
                                ':host'.$settings['database']['host'].
                                ';port='.$settings['database']['port'].
                                ';dbname='.$settings['database']['schema'].
                                ';charset:'.$settings['database']['charset'],
                            "username" => $settings['database']['username'],
                            "password" => $settings['database']['password']
                        )
                    );
                }
                return $connection;
            } catch (Exception $er) {
                die("Error: ".$er->getMessage());
            }
        }
    }
?>