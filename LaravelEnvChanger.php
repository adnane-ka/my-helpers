<?php 

namespace Path\To;

Trait LaravelEnvChanger{
    
    /** 
     * change env varriables dynamiclly 
     * @param array 
     * @return boolean
     * */ 
    protected function changeEnv($data = [])
    {
        if(count($data) > 0){

            # Read the .env file
            $env = file_get_contents(base_path() . '/.env');

            # Split string on every " " and write into array
            $env = preg_split('/\s+/', $env);;

            # Loop through given data
            foreach((array)$data as $key => $value){

                # Loop through .env data
                foreach($env as $env_key => $env_value){

                    # Turn the value into an array and stop after the first split
                    # So it's not possible to split e.g. the App-Key by accident
                    $entry = explode("=", $env_value, 2);

                    # Check, if new key fits the actual .env key
                    if($entry[0] == $key){
                        # If yes, overwrite it with the new one
                        $env[$env_key] = $key . "=" . $value;
                    } else {
                        # If not, keep the old one
                        $env[$env_key] = $env_value;
                    }
                }
            }

            # Turn the array back to a String
            $env = implode("\n", $env);

            # Now we can overwrite the .env with the new data
            file_put_contents(base_path() . '/.env', $env);
            
            return true;
        } 
        
        return false;
    }
}