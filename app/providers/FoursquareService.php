<?php

class FoursquareService
{
    private $client_id = 'KPAV0PKSSWPJ13OOWWXJ3YQ0YIISIQXDFMYPQ42I2U5CDPVQ';
    private $client_secret = 'HTZHAVPGZY1A24D0LKV24OOWE24KG2MJP3Y2TWYBUPBQ20SO';
    private $version = '20140501';

    public function getVenuesNear($latitude, $longitude, $limit = 3)
    {
        /*
            Example call

            https://api.foursquare.com/v2/venues/explore
            ?client_id=KPAV0PKSSWPJ13OOWWXJ3YQ0YIISIQXDFMYPQ42I2U5CDPVQ
            &client_secret=HTZHAVPGZY1A24D0LKV24OOWE24KG2MJP3Y2TWYBUPBQ20SO
            &ll=41.173408,-8.583088
            &v=20140501
            &limit=3
            &intent=browse
         */

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => 'https://api.foursquare.com/v2/venues/explore?'
                            . 'client_id=' . $this->client_id . '&'
                            . 'client_secret=' . $this->client_secret . '&'
                            . 'll=' . $latitude . ',' . $longitude . '&'
                            . 'v=' . $this->version . '&'
                            . 'limit=' . $limit . '&'
                            . 'intent=browse'
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        return json_decode($response);
    }

}