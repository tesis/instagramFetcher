<?php
/**
 * This file is part of the Tesis framework.
 *
 * PHP version 5.6
 *
 * @author     Tereza Simcic <tereza.simcic@gmail.com>
 * @copyright  2014-2015 Tesis, Tereza Simcic
 * @license    MIT
 * @link       https://github.com/tesis/instagramFetcher
 *
 */

namespace Tesis\Socials\Instagram;

/**
 * Class InstagramFetcher
 *
 * PHP version 5.6
 *
 * @package    Photos
 * @author     Tereza Simcic <tereza.simcic@gmail.com>
 *
 * InstagramFetcher fethces images from Instagram
 *
 */
class InstagramFetcher{

    /**
     * @access public
     * @var string
     * clientId
     */
    public $consumerKey;
    /**
     * @access public
     * @var string
     * secretId
     */
    public $consumerSecret;
    /**
     * @access public
     * @var int
     *
     */
    public $locationDistance;
    /**
     * @access public
     * @var string
     *
     */
    public $baseUrl;
    //pagination related
    /**
     * @access public
     * @var string
     *
     */
    public $nextURL;
    /**
     * @access public
     * @var string
     *
     */
    public $nextMaxID;
    /**
     * @access public
     * @var int
     *
     */
    public $maxNumPhotos;
    //search by distance
    /**
     * @access public
     * @var string
     *
     */
    public $latitude;
    /**
     * @access public
     * @var string
     *
     */
    public $longitude;
    /**
     * @access public
     * @var int
     *
     */
    public $defaultDistance;
    /**
     * @access public
     * @var int
     *
     */
    public $maxDistance;
    /**
     * @access public
     * @var string
     * A unix timestamp. All media returned will be taken later than this timestamp.
     */
    public $minTimestamp;
    /**
     * @access public
     * @var string
     * A unix timestamp. All media returned will be taken earlier than this timestamp.
     */
    public $maxTimestamp;
    /**
     * @access public
     * @var string
     *
     */
    public $url;

    //for pagination
    /**
     * @access public
     * @var string
     *
     */
    public $nextUrl;
    /**
     * @access public
     * @var array
     *
     */
    public $uniqueRecords;
    /**
     * @access public
     * @var array
     *
     */
    public $data;

    const SOURCE_ENDPOINT = 'https://api.instagram.com/v1/';

    public function __construct() {
        $this->baseUrl = self::SOURCE_ENDPOINT;

        //Default is 1km (distance=1000), max distance is 5km.
        $this->locationDistance = 30000; //30km

        //default by Instagram
        $this->maxNumPhotos = 5000; //not considered as a good option IMO
        $this->defaultDistance = 1000;
        $this->maxDistance = 5000;
        $this->url = '';
        $this->nextUrl = '';
        $this->uniqueRecords = '';
        $this->data = [];

        $this->consumerKey = CLIENT_ID;
        $this->consumerSecret = CLIENT_SECRET;
    }
    /**
     * photosByLocation find photos by location
     *
     * @param array $searchParams parameters needed for search: $latitude,
     *                            $longitude,
     *                            optional: distance if less than 5km,
     *                            min_timestamp, max_timestam
     *
     * @return string
     *
     * example url:
     * //https://api.instagram.com/v1/media/search?lat=41.902784&lng=12.496366
     *        &client_id=2b9d627017ab4ea48c6308af09744f85
    */
    public function photosByLocation(array $searchParams=null)
    {
        if(is_null($searchParams)){
            throw new \Exception(MISSING_ARGUMENTS);
        }

        //passed parameters
        $array = [
                  'latitude',
                  'longitude',
                  'min_timestamp',
                  'max_timestamp',
                  'distance'
                 ];

        foreach ( $searchParams as $key => $value )
        {
			if ( in_array( $key, $array ) && !empty($value))
            {
				$$key = $value;
			}
		}

        $stringArr = [];
        if(empty($latitude) || empty($longitude))
        {
            throw new \Exception(MISSING_ARGUMENTS);
        }
        $stringArr[] = "lat=".$latitude;
        $stringArr[] = "lng=".$longitude;

        if(!empty($min_timestamp))
        {
            $stringArr[] = "min_timestamp=".$min_timestamp;
        }
        if(!empty($max_timestamp))
        {
            $stringArr[] = "max_timestamp=".$max_timestamp;
        }
        if(!empty($distance))
        {
            $stringArr[] = "distance=".$distance;
        }
        else
        {
            $stringArr[] = "distance=".$this->locationDistance;
        }

        $string = implode('&', $stringArr);
        $param = 'media/search?'. $string;

        $this->url = $this->buildUrl($param, true);
        //echo "just echoing\n" . $this->url . "\n";
    }
    /**
     * recentTags
     *
     * @param string $tag a tag
     *
     * @return string
     *
     * ex url:
     * //https://api.instagram.com/v1/tags/marjana/media/recent?client_id=2b9d627017ab4ea48c6308af09744f85
     *
    */
    public function recentTags($tag = '')
    {
        if(empty($tag)) return false;

        $param = 'tags/'. $tag .'/media/recent';
        $this->url = $this->buildUrl($param);
    }

     /**
     * buildUrl
     *
     * @param string $params building block for the query
     * @param bool   $multi  if true, there are multiple params, client_id appeded with '&'
     *                       otherwise with '?'
     *
     * @return
     *
    */
    public function buildUrl($params='', $multi=false)
    {
        if(empty($params)) return false;

        $url = $this->baseUrl . $params;
        $delim = '?';
        if($multi == true)
        {
            $delim = '&';
        }
        $url .= $delim . 'client_id='. $this->consumerKey;

        return $url ? $url : false;
    }
    /**
     * goCurl retrieve data from url once it is built and json_decode results
     *
     * @param string $url the url from where data will be fetched
     *
     * @return array/bool
     *
    */
    public function goCurl($url='', $true=false)
    {
        if(empty($url)) return false;

        $options = [
                    CURLOPT_HEADER         => false,
                    CURLOPT_URL            => $url,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_TIMEOUT        => 10,
                    CURLOPT_SSL_VERIFYPEER => false
                  ];

        $ch = curl_init();
        curl_setopt_array($ch, $options);

        if(curl_exec($ch) === false)
        {
            $exception = new \Exception('cUrl error: '.curl_error($ch));
            //this error is pretty common on my system :S
            error_log(__CLASS__.__METHOD__. 'Exception: '. 'cUrl error: '
                      .curl_error($ch));
            return false;
        }
        $result = curl_exec($ch);

        curl_close($ch);

        if($true == true)
        {
            $result = json_decode($result, true);//if you want in arrays
        }
        else
        {
            $result = json_decode($result);
        }

        return !empty($result) ? $result : false;
    }

    /**
     * photosById fetch photo by id - maybe would be useful, for now is not
     *
     * @param int $id str_id of the photo
     *
     * @return
     *
     * example url:
     * //https://api.instagram.com/v1/media/679742639032117260_1125818209?
     *           client_id=2b9d627017ab4ea48c6308af09744f85
     *
    */
    public function photosById($id='')
    {
        if(empty($id)) return false;

        $param = 'media/' . $id;
        $this->url = $this->buildUrl($param);
    }
    /**
     * popularPhotos get most popular photos, not useful for phlowSource
     *
     * @return
     *
     * example url:
     * //https://api.instagram.com/v1/media/popular?access_token=ACCESS-TOKEN
     *
    */
    public function popularPhotos()
    {

        $param = 'media/popular';
        $this->url = $this->buildUrl($param);
    }
    /**
     * photosByTagName get photos by tag name, not really useful for phlowSource
     *
     * @return
     *
     * example url:
     *  https://api.instagram.com/v1/tags/marjana?
     *          client_id=2b9d627017ab4ea48c6308af09744f85
     * example output:
     *   {"meta":{"code":200},"data":{"media_count":866,"name":"marjana"}}
    */
    public function photosByTagName($tag='')
    {
        if(empty($tag)) return false;


        $param = 'tags/' . $tag;
        $this->url = $this->buildUrl($param);//we get only count, etc
    }
    public function searchTag($q='')
    {
        if(empty($q)) return false;

        $param = 'search?q=' . $q;
        $this->url = $this->buildUrl($param);
    }
    /**
     * parseData get results only for images, from default number of results
     *            without pagination (parse them here)
     *
     * @param object $res  the object from instagram
     * @param string $type type of results we search for(default images)
     *
     * @return array/bool
     * need:
     * tags - extract 'bad words'
     * caption->text(if missing seems here are tags, maybe extract tags out?)
     * images(low_resoulution, thumbnail, standard_resolution)
     * id
     * for tags search: next_url, next_max_id
     * for media search by geolocation: max_timestamp
     *
    */
    public function parseData($res='', $type='image')
    {
        if(empty($res)) return false;

        $arr = [];
        $ids = [];
        $uniqueRecords = []; //extracting only unique records, for first time these are all

        if($res->meta->code != 200) return false;

        for($i=0; $i < sizeof($res->data); $i++)
        {
            $arr[$i]['latitude'] = $arr[$i]['longitude'] = $arr[$i]['tags'] = $arr[$i]['caption'] = '';
            //extract images
            if($res->data[$i]->type != 'image') continue;
            if(!in_array($res->data[$i]->id, $uniqueRecords))
            {
                $ids[] = $res->data[$i]->id;
                $arr[$i]['id'] = $res->data[$i]->id;
                if(!empty($res->data[$i]->tags)){
                    $arr[$i]['tags'] = $res->data[$i]->tags;
                }
                if(!empty($res->data[$i]->caption))
                {
                    $arr[$i]['caption'] = $res->data[$i]->caption->text;
                }
                $arr[$i]['standard'] = $res->data[$i]->images->standard_resolution->url;
                $arr[$i]['thumbnail'] = $res->data[$i]->images->thumbnail->url;
                //location might have some text as well, without lat and lng
                if(
                      !empty($res->data[$i]->location->latitude)
                   && !empty($res->data[$i]->location->longitude)
                   )
                {
                    $arr[$i]['latitude'] = $res->data[$i]->location->latitude;
                    $arr[$i]['longitude'] = $res->data[$i]->location->longitude;
                }
                array_push($uniqueRecords, $res->data[$i]->id);
            }
        }

        //-- save locally for comparison
        $this->uniqueRecords = $uniqueRecords;
        //push next_url if we search by tag, we need paginated results, or at least - next_max_id
        if(isset($res->pagination->next_url))
        {
            //Session::put('inNextUrl', $res->pagination->next_url);
            $this->nextUrl = $res->pagination->next_url;
        }
        $this->data = $arr;

        return $this;
    }
}
