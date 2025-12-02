<?php

class Dial
{
    private const START = 50;
    private $current = 50;
    public $zero_passes = 0;
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

    public function decrypt_part_two()
    {
        if( ! $this->file_handler )
        {
            return;
        }

        while ( $line = fgets( $this->file_handler ) )
        {
            $this->calc_pass_zeros( $line );
        }
    }

    private function rotate( $command )
    {
        $direction = substr( $command, 0, 1 );
        $steps     = (int) substr( $command, 1 );
        $step   = abs( $steps ) % 100;
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

    private function calc_pass_zeros( $command )
    {
        $direction = substr( $command, 0, 1 );
        $steps     = (int) substr( $command, 1 );
        $step   = abs( $steps ) % 100;
        $full_rotation = floor( abs( $steps ) / 100 );

        if( $direction == 'R' )
        {
            if( $this->current + $step > 100 AND $this->current + $step != 0 )
            {
                $this->zero_passes++;
            }
            $this->current = ( $this->current + $step ) % 100;
        } else {
            if( $this->current - $step < 0 AND $this->current != 0 )
            {
                $this->zero_passes++;
                $this->current = 100 - ( $step - $this->current );
            } else if( $this->current == 0) {
                $this->current = 100 - ( $step - $this->current );
            } else {
                $this->current -= $step;
            }
        }
        $this->current == 100 ? 0 : $this->current;
        if( $this->current == 0 ){
            $this->zero_passes++;
        }

        $this->zero_passes += $full_rotation;
    }

    public function reset_data()
    {
        $this->current = self::START;
    }
}


$dial = new Dial;
echo "Current value: 50" . PHP_EOL;
$dial->load_input( 'input.txt' )->decrypt();
echo "How many zeros: " . $dial->how_many_zeros . PHP_EOL;
$dial->reset_data();
$dial->load_input( 'input.txt' )->decrypt_part_two();
echo "How many zero clicks: " . $dial->zero_passes . PHP_EOL;