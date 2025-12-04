<?php

class Batteries
{
    
    private $file_handler;
    private $battery_banks = [];
    private $largest_joltage_per_bank = [];
    private $joltage = 0;

    public function load_input( string $file_name )
    {
        $this->file_handler = fopen( $file_name ,'r');
        return $this;
    }

    public function read_battery_banks()
    {
        if( ! $this->file_handler )
        {
            return;
        }

        while ( $line = fgets( $this->file_handler ) )
        {
            $this->battery_banks[] = $line;
        }

        fclose( $this->file_handler );
    }

    public function calculate_largest_joltage()
    {
        foreach( $this->battery_banks as $battery_bank )
        {
            $battery_bank = str_split( trim( $battery_bank ) );
            $first_max = 0;
            $first_max_index = 0;
            $second_max = 0;
            
            //find first biggest
            for( $i = 0; $i < ( count( $battery_bank ) - 1 ); $i++ )
            {
                if( $battery_bank[ $i ] > $first_max ){
                    $first_max = $battery_bank[ $i ];
                    $first_max_index = $i;
                }
            }

            //find second biggest
            for( $j = $first_max_index + 1; $j < count( $battery_bank ); $j++ )
            {
                if( $battery_bank[ $j ] > $second_max ){
                    $second_max = $battery_bank[ $j ];
                }
            }

            $this->largest_joltage_per_bank[] = (int) $first_max . $second_max;
        }
    }

    public function sum_bank_joltage()
    {
        foreach( $this->largest_joltage_per_bank as $largest_joltage )
        {
            $this->joltage += $largest_joltage;
        }
    }

    public function get_total_joltage()
    {
        return $this->joltage;
    }

    public function reset_calulations()
    {
        $this->largest_joltage_per_bank = [];
        $this->joltage = 0;
    }

    public function calculate_largest_joltage_part_2()
    {
        $how_many_bateries_to_select = 12;

        foreach( $this->battery_banks as $battery_bank )
        {
            $battery_bank = str_split( trim( $battery_bank ) );

            $selected_bateries = [];
            $starting_index = 0;
            $temp_max = 0;

            while( count( $selected_bateries ) < $how_many_bateries_to_select )
            {
                $how_many_we_got = count( $selected_bateries );
                for( $i = $starting_index; $i <= ( count( $battery_bank ) - ( $how_many_bateries_to_select - $how_many_we_got ) ); $i++ )
                {
                    if( $battery_bank[ $i ] > $temp_max ){
                        $temp_max = $battery_bank[ $i ];
                        $starting_index = $i;
                    }
                }
                $selected_bateries[] = $temp_max;
                $starting_index++;
                $temp_max = 0;
            }
            $this->largest_joltage_per_bank[] = (int) implode( '', $selected_bateries );
        }
    }

}



$batteries = new Batteries;
$batteries->load_input( 'input.txt' )->read_battery_banks();
$batteries->calculate_largest_joltage();
$batteries->sum_bank_joltage();
echo '[Part 1] Total joltage output: ' . $batteries->get_total_joltage() .  PHP_EOL;
$batteries->reset_calulations();
$batteries->calculate_largest_joltage_part_2();
$batteries->sum_bank_joltage();
echo '[Part 2] Total joltage output: ' . $batteries->get_total_joltage() .  PHP_EOL;


?>