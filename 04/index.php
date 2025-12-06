<?php 

class PapperRolls
{
    
    private $file_handler;
    private $positions_array = [];
    private $how_may_rolls_can_get = 0;

    public function load_input( string $file_name )
    {
        $this->file_handler = fopen( $file_name ,'r');
        return $this;
    }

    public function make_array()
    {
        if( ! $this->file_handler )
        {
            return;
        }

        while ( $line = fgets( $this->file_handler ) )
        {
            $this->positions_array[] = str_split($line);
        }

        fclose( $this->file_handler );
    }

    public function calculate_every_roll()
    {
        foreach( $this->positions_array as $row_key => $row )
        {
            foreach( $row as $cell_key => $cell )
                {
                $near_rolls = 0;

                echo 'processing el('. $row_key . ', ' . $cell_key .')' . PHP_EOL ;

                for( $i = $row_key - 1; $i <= $row_key + 1; $i++ )
                {
                    for( $j = $cell_key - 1; $j <= $cell_key + 1; $j++ )
                    {
                        // var_dump( $this->positions_array[$i][$j] );
                        echo 'calculating el('. $i . ', ' . $j .')' . PHP_EOL ;

                        if( !isset( $this->positions_array[$i][$j] )  )
                        {
                            continue;
                        }
                        // var_dump( isset( $this->positions_array[$i][$j] ) );
                        // var_dump( $this->positions_array[$i][$j] == '@' );
                        // var_dump( $i != $row_key );
                        // var_dump( $j != $cell_key );
                        if( isset( $this->positions_array[$i][$j] ) AND $this->positions_array[$i][$j] == '@' AND $i != $row_key AND $j != $cell_key )
                        {
                            $near_rolls++;
                            echo 'el('. $i . ', ' . $j .')' . PHP_EOL ;
                        }
                        // var_dump( $this->how_may_rolls_can_get );
                    }
                }
                echo $near_rolls . PHP_EOL;
                if( $near_rolls < 4 ){
                    $this->how_may_rolls_can_get++;
                }
            }
        }

        var_dump( $this->how_may_rolls_can_get );
    }
}



$rolls = new PapperRolls;
$rolls->load_input( 'test.txt' )->make_array();
$rolls->calculate_every_roll();

?>