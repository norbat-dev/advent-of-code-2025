<?php 

class PaperRolls
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

                if( $this->positions_array[$row_key][$cell_key] != '@' )
                {
                    continue;
                }

                for( $i = $row_key - 1; $i <= $row_key + 1; $i++ )
                {
                    for( $j = $cell_key - 1; $j <= $cell_key + 1; $j++ )
                    {
                        if( !isset( $this->positions_array[$i][$j] )  )
                        {
                            continue;
                        }
                        
                        if( $this->positions_array[$i][$j] == '@' AND !( $i == $row_key AND $j == $cell_key ) )
                        {
                            $near_rolls++;
                        }
                    }
                }

                if( $near_rolls < 4 ){
                    $this->how_may_rolls_can_get++;
                }
            }
        }
    }

    public function get_how_may_rolls_can_get()
    {
        return $this->how_may_rolls_can_get;
    }

}



$rolls = new PaperRolls;
$rolls->load_input( 'input.txt' )->make_array();
$rolls->calculate_every_roll();
echo '[Part 1] Total paper rolls can get: ' . $rolls->get_how_may_rolls_can_get() .  PHP_EOL;

?>