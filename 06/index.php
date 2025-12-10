<?php

class Math
{
    private $file_handler;
    private $columns = [];
    private $columns_results = [];
    private $sum_result;

    public function load_input( string $file_name )
    {
        $this->file_handler = fopen( $file_name ,'r');
        return $this;
    }

    public function retrieve_dataset()
    {
        if( ! $this->file_handler )
        {
            return;
        }

        while ( $line = fgets( $this->file_handler ) )
        {
            preg_match_all( '/[0-9]+|[+*]/', $line, $matches );
            for( $i = 0; $i < count( $matches[0] ); $i++ )
            {
                $this->columns[$i][] = $matches[0][$i];
            }
        }
        fclose( $this->file_handler );
    }

    public function caluclate()
    {
        foreach( $this->columns as $column )
        {
            $count = count( $column ) - 1;
            $operation = $column[$count];
            unset( $column[$count] );
            if( $operation == '+' )
            {
                $this->columns_results[] = array_sum( $column );
            } else {
                $this->columns_results[] = array_product( $column );
            }
        }
    }

    public function sum_results()
    {
        $this->sum_result = array_sum( $this->columns_results );
    }

    public function get_sum_results()
    {
        return $this->sum_result;
    }
}


$math = new Math;
$math->load_input( 'input.txt' )->retrieve_dataset();
$math->caluclate();
$math->sum_results();
echo '[Part 1] Grand total: ' . $math->get_sum_results() .  PHP_EOL;

?>