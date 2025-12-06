<?php 

class Ingredient
{
    
    private $file_handler;
    private $fresh_ingredients = 0;
    private $considered_to_be_fresh_ingredients = 0;
    private $ingredients_IDs = [];
    private $fresh_ingredients_ID_ranges = [];

    private $merged_fresh_ingredients_ID_ranges = [];

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
            $line = explode('-', $line);
            if( count($line) > 1 )
            {
                $this->fresh_ingredients_ID_ranges[] = [ (int) $line[0], (int) $line[1] ];
            } else {
                $this->ingredients_IDs[] = (int) $line[0];
            }
        }
        fclose( $this->file_handler );
    }

    public function calculate_fresh_ingredients()
    {
        foreach( $this->ingredients_IDs as $ingredients_ID )
        {
            foreach( $this->fresh_ingredients_ID_ranges as $range_to_check )
            {
                if( ( $range_to_check[0] <= $ingredients_ID) && ($ingredients_ID <= $range_to_check[1] ) )
                {
                    $this->fresh_ingredients++;
                    break;
                }
            }
        }
    }

    public function calculate_considered_to_be_fresh_ingredients()
    {
        //sort asc by range start value
        usort( $this->fresh_ingredients_ID_ranges, function( $a, $b)
        {
            return ($a[0] < $b[0]) ? -1 : 1;
        });

        //merge overlap ranges
        foreach( $this->fresh_ingredients_ID_ranges as $range )
        {
            if( empty( $this->merged_fresh_ingredients_ID_ranges ) )
            {
                $this->merged_fresh_ingredients_ID_ranges[] = $range;
                continue;
            }

            $last_key = count( $this->merged_fresh_ingredients_ID_ranges ) - 1;
            $last = $this->merged_fresh_ingredients_ID_ranges[ $last_key ];
            //chaeck if overlap
            if( $last[1] >= $range[0] ){
                $this->merged_fresh_ingredients_ID_ranges[ $last_key ][1] = ( $last[1] > $range[1] ) ? $last[1] : $range[1];

            } else {
                $this->merged_fresh_ingredients_ID_ranges[] = $range;
            }
        }

        //calculate consider to be fresh
        foreach( $this->merged_fresh_ingredients_ID_ranges as $consider_to_be_fresh_range )
        {
            $this->considered_to_be_fresh_ingredients +=  $consider_to_be_fresh_range[1] - $consider_to_be_fresh_range[0] + 1;
        }

        
    }

    public function get_fresh_ingredients()
    {
        return $this->fresh_ingredients;
    }

    public function get_considered_to_be_fresh_ingredients()
    {
        return $this->considered_to_be_fresh_ingredients;
    }

}



$ingredient = new Ingredient;
$ingredient->load_input( 'input.txt' )->retrieve_dataset();
$ingredient->calculate_fresh_ingredients();
echo '[Part 1] Total fresh ingredients: ' . $ingredient->get_fresh_ingredients() .  PHP_EOL;
$ingredient->calculate_considered_to_be_fresh_ingredients();
echo '[Part 2] Total considered to be fresh ingredients: ' . $ingredient->get_considered_to_be_fresh_ingredients() .  PHP_EOL;
?>