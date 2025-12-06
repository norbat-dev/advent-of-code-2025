<?php 

class Ingredient
{
    
    private $file_handler;
    private $fresh_ingredients = 0;
    private $ingredients_IDs = [];
    private $fresh_ingredients_ID_ranges = [];

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

    public function get_fresh_ingredients()
    {
        return $this->fresh_ingredients;
    }

}



$ingredient = new Ingredient;
$ingredient->load_input( 'input.txt' )->retrieve_dataset();
$ingredient->calculate_fresh_ingredients();
echo '[Part 1] Total fresh ingredients: ' . $ingredient->get_fresh_ingredients() .  PHP_EOL;
?>