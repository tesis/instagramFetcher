<?php
/**
 * This file is part of the Tesis framework.
 *
 * PHP version 5.6
 *
 * @author     Tereza Simcic <tereza.simcic@gmail.com>
 * @copyright  2014-2015 Tesis, Tereza Simcic
 * @license    MIT
 * @link       https://github.com/tesis/login
 *
 */

namespace Tesis\Socials\Instagram;

use Tesis\Socials\Instagram\InstagramFetcher;

require_once('config.php');

/**
 * Class Library
 *
 * PHP version 5.6
 *
 * @package    Photos
 * @author     Tereza Simcic <tereza.simcic@gmail.com>
 *
 * Library is a class that connects a project with
 * InstagramFetcher
 *
 */
class Library {

    const SOURCE_NAME = 'Instagram';

    public function __construct(){
        //echo "Test";
    }
    /**
     * getTagsMedia
     *
     * @param string $tag hashtag we search for: can be only string,
     *               including underscore works as well
     *
     * @access public static
     *
     * @return object
     *
    */
    public static function searchByTag($tag='')
    {
        if(!empty($_POST['tag'])){
            $tag = $_POST['tag'];
        }
        if(empty($tag)){
            throw new \Exception(MISSING_ARGUMENTS);
        }

        $instagram = new InstagramFetcher;
        //setup url
        $instagram->recentTags($tag);

        $res = $instagram->goCurl($instagram->url);
        if(!$res)
        {
            throw new \Exception(NO_RECORDS);
        }

        $arr = $instagram->parseData($res);

        return $arr ? $arr : false;

    }

    /**
     * getLocationMedia
     *
     * @param string $lat latitude
     * @param string $lng longitude
     *               optional param is distance: default:1km, max:5km
     *
     * @return object
     *
    */
    public static function searchByLocation(array $searchParams=null)
    {
        if(is_null($searchParams))
        {
            throw new \Exception(MISSING_ARGUMENTS);
        }
        //
        $instagram = new InstagramFetcher;

        $instagram->photosByLocation($searchParams);

        $res = $instagram->goCurl($instagram->url);
        if(!$res)
        {
            throw new \Exception(NO_RECORDS);
        }
        $arr = $instagram->parseData($res);

        return $arr ? $arr : false;
    }

}
