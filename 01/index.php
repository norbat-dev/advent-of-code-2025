<?php

class Dial
{
    private const START = 50;
    private $current = 50;
    public $how_many_zeros = 0;
    private $file_handler;


    public function load_input( string $file_name )
    {
        $this->file_handler = fopen( $file_name ,'r');
        return $this;
    }

    public function close_file(){
        fclose( $this->file_handler );
    }

    public function decrypt()
    {
        if( ! $this->file_handler )
        {
            return;
        }

        while ( $line = fgets( $this->file_handler ) )
        {
            $this->rotate( $line );
        }
    }

    private function rotate( $command )
    {
        // echo "Current value: " . $this->current . ". Next step: " . $command;
        $direction = substr( $command, 0, 1 );
        $step   = (int) substr( $command, 1 ) % 100;
        if( $direction == 'R' )
        {
            $this->current += $step;
            if( $this->current > 99 )
            {
                $this->current -= 100;
            }
        } else {
            $this->current -= $step;
            if( $this->current < 0 )
            {
                $this->current += 100;
            }
        }
        if( $this->current == 0 ){
            $this->how_many_zeros++;
        }
    }
}


$dial = new Dial;
echo "Current value: 50" . PHP_EOL;
$dial->load_input( 'input.txt' )->decrypt();
echo $dial->how_many_zeros . PHP_EOL;