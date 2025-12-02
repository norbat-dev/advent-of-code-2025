<?php

class scanID
{
    
    private $file_handler;
    private $ids;
    private $invalid_ids = [];
    private $sum_invalid_ids = 0;

    public function load_input( string $file_name )
    {
        $this->file_handler = fopen( $file_name ,'r');
        return $this;
    }

    public function read_ids_ranges()
    {
        if( ! $this->file_handler )
        {
            return;
        }

        while ( $line = fgets( $this->file_handler ) )
        {
            $this->ids = explode( ',', $line );
        }
    }

    public function find_invalid()
    {
        foreach( $this->ids as $id_range )
        {
            $first_id = (int) explode( '-', $id_range)[0];
            $last_id = (int) explode( '-', $id_range)[1];
            for( $current = $first_id; $current <= $last_id; $current++  ){
                $id_length = strlen( (string) $current );
                if( $id_length % 2 != 0 ){
                    continue;
                }
                if( substr( (string) $current, 0, $id_length / 2) == substr( (string) $current, $id_length / 2, $id_length ) )
                {
                    $this->invalid_ids[] = $current;
                }
            }
        }
    }

    public function sum_invalid()
    {
        $this->invalid_ids = array_unique( $this->invalid_ids );
        foreach( $this->invalid_ids as $invalid_id ){
            $this->sum_invalid_ids += (int) $invalid_id;
        }
    }

    public function get_sum_invalid_ids(){
        return $this->sum_invalid_ids;
    }

    public function reset_sum()
    {
        $this->sum_invalid_ids = 0;
        $this->invalid_ids = [];
    }

    function find_invalid_more_complex()
    {
        foreach( $this->ids as $id_range )
        {
            $first_id = (int) explode( '-', $id_range)[0];
            $last_id = (int) explode( '-', $id_range)[1];
            for( $current = $first_id; $current <= $last_id; $current++  ){
                $pattern_max_lenght = floor( strlen( (string) $current ) / 2 );
                for( $patter_length = 1; $patter_length  <= $pattern_max_lenght; $patter_length++)
                {
                    if( count( array_unique( str_split($current, $patter_length) ) ) === 1 )
                    {
                        $this->invalid_ids[] = $current;
                    }
                }
            }
        }
    }

}



$IDs = new scanID;
$IDs->load_input( 'input.txt' )->read_ids_ranges();
$IDs->find_invalid();
$IDs->sum_invalid();
echo '[Part 1] Sum of invalid IDs is equal to ' . $IDs->get_sum_invalid_ids() .  PHP_EOL;
$IDs->reset_sum();
$IDs->find_invalid_more_complex();
$IDs->sum_invalid();
echo '[Part 2] Sum of invalid IDs is equal to ' . $IDs->get_sum_invalid_ids() .  PHP_EOL;

?>