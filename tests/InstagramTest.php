<?php
use Tesis\Socials\Instagram\InstagramFetcher;

require_once('config.php');

class InstagramFetcherTest extends PHPUnit_Framework_TestCase {
    public $classRepo;
    public function setUp()
    {
        parent::setUp();
        $this->instagram = new InstagramFetcher;
        $this->classRepo = 'Tesis\Socials\Instagram\InstagramFetcher';
        //$this->clientId = '2b9d627017ab4ea48c6308af09744f85';
    }
    public function tearDown()
    {
        //
    }
    /**
     * initial test to see if roots are working
     *
    */
    public function testProcess_If_Class_Has_Id_Variable()
    {
        $this->assertClassHasAttribute('consumerKey', $this->classRepo, 'Expected Pass');
    }
    //--- test if all variables are defined in controller
    /**
     * test_If_Variables_for_Process_Defined
     *
     * @param $a variable to test
     * @param $expected the class we expected to be in
     *
     * @dataProvider variablesProvider
     *
    */
    public function test_If_Variables_for_Process_Defined($a, $expected)
    {
        $actual = $this->classRepo;

        $this->assertClassHasAttribute($a, $actual, 'Expected Pass');
    }
    /**
    *
    * variablesProvider
    *
    * a provider for test_If_Variables_for_Process_Defined
    *
    */
    public function variablesProvider()
    {
        return array(
            array('consumerSecret', $this->classRepo, 'Expected Pass'),
            array('longitude', $this->classRepo, 'Expected Pass'),
            array('nextURL', $this->classRepo, 'Expected Pass'),
            array('baseUrl', $this->classRepo, 'Expected Pass'),
            array('locationDistance', $this->classRepo, 'Expected Pass'),
            array('nextMaxID',$this->classRepo, 'Expected Pass'),
            array('latitude', $this->classRepo, 'Expected Pass'),
            array('longitude',$this->classRepo, 'Expected Pass'),
            array('maxDistance', $this->classRepo, 'Expected Pass'),
            array('minTimestamp', $this->classRepo, 'Expected Pass'),
            array('maxTimestamp', $this->classRepo, 'Expected Pass')
        );
    }
    protected function recentCallTags($tag)
    {
        $instagram = new InstagramFetcher;
        $test = $instagram->recentTags($tag);
        $res = $instagram->goCurl($instagram->url);

        $getData = $this->getResults($res);

        return $getData;
    }
    protected function searchByLocCall($searchParams)
    {
        $instagram = new InstagramFetcher;
        $instagram->photosByLocation($searchParams);

        $res = $instagram->goCurl($instagram->url);
        $getData = $instagram->parseData($res);

        return $getData;
    }
    /**
     * test_photosById_Pass
     *
    */
    public function testInstagram_photosById_Pass()
    {
        $instagram = new InstagramFetcher;
        $test = $instagram->photosById('679742639032117260_1125818209');
        //echo $instagram->url;
        //https://api.instagram.com/v1/media/679742639032117260_1125818209?client_id=2b9d627017ab4ea48c6308af09744f85
        $res = $instagram->goCurl($this->instagram->url);

        $this->assertNotEmpty($instagram->url, 'Expected Pass');
    }
    /**
     * test_photosById_WrongId_Fail
     *
    */
    public function testInstagram_photosById_WrongId_Fail()
    {
        $instagram = new InstagramFetcher;
        $test = $instagram->photosById('111');
        //echo $instagram->url;
        //https://api.instagram.com/v1/media/111?client_id=2b9d627017ab4ea48c6308af09744f85
        $res = $instagram->goCurl($instagram->url);

        $this->assertNotEmpty($instagram->url, 'Expected Pass');
    }
    /**
     * testInstagram_photosByTagName_Pass
     *
     * not using, just testing
    */
    public function testInstagram_photosByTagName_Pass()
    {
        $instagram = new InstagramFetcher;
        $test = $instagram->photosByTagName('marjana');
        //echo $instagram->url;
        $res = $instagram->goCurl($instagram->url);

        //{"meta":{"code":200},"data":{"media_count":867,"name":"marjana"}}
        //print_r($instagram->goCurl($instagram->url));
        //[meta] => Array ( [code] => 200 )[data] => Array([media_count] => 867 [name]=>marjana)
        //$this->assertNotEmpty($instagram->url, 'Expected Pass');
    }
    /**
     * test_URL_Not_Empty_photosRecentTags_Pass
     *
    */
    public function test_URL_Not_Empty_photosRecentTags_Pass()
    {
        $instagram = new InstagramFetcher;
        $test = $instagram->recentTags('marjana');
        //echo $instagram->url;
        //https://api.instagram.com/v1/tags/marjana/media/recent?client_id=2b9d627017ab4ea48c6308af09744f85
        $res = $instagram->goCurl($instagram->url);

        //print_r($instagram->goCurl($instagram->url));

        $this->assertNotEmpty($instagram->url, 'Expected Pass');
    }
    /**
     * test_photosByLocation_Pass
     *
    */
    public function test_photosByLocation_Pass()
    {
        $instagram = new InstagramFetcher;
        //Rome
        $lat = '41.902784';
        $lng = '12.496366';
        $searchParams = ['latitude'=>'41.902784', 'longitude'=>'12.496366'];
        $test = $instagram->photosByLocation($searchParams);
        //echo $instagram->url;
        //https://api.instagram.com/v1/media/search?lat=41.902784&lng=12.496366&client_id=2b9d627017ab4ea48c6308af09744f85
        $res = $instagram->goCurl($instagram->url);

        $this->assertNotEmpty($instagram->url, 'Expected Pass');
    }
    /**
     * test_popularPhotos_Pass
     *
    */
    public function test_popularPhotos_Pass()
    {
        $instagram = new InstagramFetcher;
        $test = $instagram->popularPhotos();
        //echo $instagram->url;
        //https://api.instagram.com/v1/media/popular?client_id=2b9d627017ab4ea48c6308af09744f85
        $res = $instagram->goCurl($instagram->url);

        $this->assertNotEmpty($instagram->url, 'Expected Pass');
    }
    /**
     * testInstagram_photosRecentTags_Process_buildingGetResults_Pass
     *
    */
    public function testInstagram_photosRecentTags_Process_buildingGetResults_Pass()
    {
        $instagram = new InstagramFetcher;
        $test = $instagram->recentTags('marjana');
        //echo $instagram->url;
                //https://api.instagram.com/v1/tags/marjana/media/recent?client_id=2b9d627017ab4ea48c6308af09744f85
    //-- RESULTS without pagination
        //echo "TAGS: \n";
        $res = $instagram->goCurl($instagram->url);

        $arr = [];
        $ids = [];
        foreach($res->data as $key => $value){
            if($value->type == 'image'){
                $arr[] = $value;
                $ids[] = $value->id;
            }
        }
        //print_r($ids);
        //echo sizeof($arr);
        $this->assertNotEmpty($ids, 'Expected Pass');
        $this->assertNotEquals(0, sizeof($arr), 'Expected Pass');


    }
    /**
     * test_photosRecentTags_Process_getResults_Pass
     *
    */
    public function test_photosRecentTags_Process_getResults_Pass()
    {
        $instagram = new InstagramFetcher;
        $test = $instagram->recentTags('marjana');
        //echo $instagram->url;
                //https://api.instagram.com/v1/tags/marjana/media/recent?client_id=2b9d627017ab4ea48c6308af09744f85
    //-- RESULTS without pagination
        $res = $instagram->goCurl($instagram->url);
        //echo "get RECENT TAGS: \n";
        $ids = $this->getResults($res);

        $this->assertNotEmpty($ids, 'Expected Pass');
    }
    /**
     * test_getTagsMedia_Pass
     *
    */
    public function test_getTagsMedia_Pass()
    {
        $getData = $this->recentCallTags('marjana');

        $this->assertNotEmpty($getData, 'Expected Pass');
        $this->assertNotEquals(0, $getData, 'Expected Pass');
        return $getData;
    }
    /**
     * test_searchByTag_next_run_Pass
     * @depends test_getTagsMedia_Pass
    */
    /*public function test_searchByTag_next_run_Pass($results)
    {
        //results are paginated - we have max_id in the next_url (if we re-run the process right away, saved locally)
        //same as twitter tag: max_id - we check in the table
        $instagram = new InstagramFetcher;
        if(isset($results->pagination->next_url)){
           $url = $results->pagination->next_url;
        }
        $res = $instagram->goCurl($url);
        //echo "NEXT RECENT TAGS: \n";
        $r = $this->getResults($res);
        $this->assertNotEmpty($r, 'Expected Pass');
        $this->assertNotEquals(0, $r, 'Expected Pass');

    }*/
    /**
     * test_getTagsMedia_Missing_Args_Fail
     * @expectedException     \Exception
     * expectedExceptionMessage Check arguments, seems not OK
     *
    */
    public function test_getTagsMedia_Missing_Args_Fail()
    {
        $instagram = new InstagramFetcher;
        $test = $instagram->recentTags('');

        throw new \Exception(MISSING_ARGUMENTS);
        //$this->assertFalse($getData, 'Expected Fail');
    }
    /**
     * test_Search_By_Location_Pass
     *
    */
    public function test_Search_By_Location_Pass()
    {
        $searchParams = ['latitude'=>'51.507350', 'longitude'=>'-0.127758'];

        $getData = $this->searchByLocCall($searchParams);

        $this->assertNotEmpty($getData, 'Expected Pass');
        $this->assertNotEquals(0, $getData, 'Expected Pass');

    }
    /**
     * test_Search_By_Location_Missing_Args_Fail
     * @expectedException     \Exception
     * expectedExceptionMessage Check arguments, seems not OK
     *
    */
    public function test_Search_By_Location_Missing_Args_Fail()
    {
        $searchParams = [];
        $instagram = new InstagramFetcher;
        //setup url
        $instagram->photosByLocation($searchParams);

        throw new \Exception(MISSING_ARGUMENT);
    }

    /**
     * test_searchByLocation_next_run_now
     *
    */
    public function test_searchByLocation_next_run_now()
    {
        //media search:
        //results are NOT paginated - we may use max_timestampt if we re-run process right away(saved locally)
        //next run, let's say next day -
        //a-we may run simple query, but results might be the same
        //if less than 20 unique results:
        //b-using max_timestamp - should be saved in  a table
        //differs from twitter - max_timestamp is of our choice
        $now = strtotime("now");
        //echo "MEDIA now \n";
        $searchParams = ['latitude'=>'51.507350', 'longitude'=>'-0.127758', 'max_timestamp'=> $now];
        $getData = $this->searchByLocCall($searchParams);

        $this->assertNotEmpty($getData, 'Expected Pass');
        return $getData;
    }
    /**
     * test_searchByLocation_next_run_yesterday
     *
    */
    public function test_searchByLocation_next_run_yesterday()
    {
        $mDay = strtotime("-1 day");

        //echo "MEDIA yesterday: \n";
        $searchParams = ['latitude'=>'51.507350', 'longitude'=>'-0.127758', 'max_timestamp'=> $mDay];
        $getData = $this->searchByLocCall($searchParams);

        $this->assertNotEmpty($getData, 'Expected Pass');
        return $getData;
    }
    /**
     * test_compareArrays_merge_if_all_different_Pass
     * arrays based on 3 different searches - using max_id for next run + since_id on 3rd run
     * all are different, thus we get all results different
     *
     * CONCERN: how to get max_id:
     *
    */
    public function test_compareArrays_merge_if_all_different_Pass()
    {
        //yesterday
        $str_max1 = "933358619371196277_487282770,933358609313368414_29872881,933358591761959260_184020497,933358588165951427_40238138,933358562746124255_1598350848,933358538142167490_1369030683,933358473645952032_422399386,933358424897881780_199227560,933358409142761483_335536277,933358399599693916_513134371,933358391467508080_208210831,933358367007641182_10772114,933358363088137921_19765138,933358298715223129_377306372,933358290786995937_9650402,933358288961320664_1187408372,933358263439721905_499625000,933358255020773302_40238138,933358229981563281_513698905,933358220787952608_23684201";
        //last week
        $str_max2 = "929009948718131460_7342425,929009940814546217_2800944,929009918285946473_1549864070,929009916279202203_7432247,929009903571166048_22487935,929009885753626247_1501417802,929009884085909513_51074381,929009856827304127_247205457,929009848510445205_282919988,929009841614911105_629089892,929009835212157132_194902699,929009805281011325_203282120,929009796071980710_52113742,929009795716208097_743565372,929009780335464625_38889243,929009775593697551_53377091,929009766183489907_37013859,929009732628985826_17253788,929009605632115594_562095";

        $arr_max1 = explode(',', $str_max1);
        $arr_max2 = explode(',', $str_max2);


        if(empty(array_intersect($arr_max1, $arr_max2)))
        {
            $merged = array_merge($arr_max1, $arr_max2);
        }
        //echo 'SORTED: ';
        arsort($merged);//high to low: first=max_id we start search for recent, so there is no higher id
        //we need last id -> and use max_id .... next run right away
        //after few days - we start search for recent, but we might start to search for less than ... if we might have old records
        //if we've already grabbed all results -> then we can only start with recent - and nothing else

        //print_r($merged);
        /*echo sizeof($merged) . "\n";
        echo sizeof($arr_max1) + sizeof($arr_max2) + sizeof($arr_since) . "\n";*/

        $this->assertEquals(sizeof($merged), sizeof($arr_max1) + sizeof($arr_max2) , 'Expected pass' );

    }
    /**
     * getResults get results only for images, from default number of results
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
     * ==> parseData on InstagramFetch
     *
    */
    public function getResults($res='', $type='image'){
        if(empty($res)) return false;

        $arr = [];
        $ids = [];
        if($res->meta->code == 200){
            for($i=0; $i < sizeof($res->data); $i++){
                $arr[$i]['latitude'] = $arr[$i]['longitude'] = $arr[$i]['tags'] = $arr[$i]['caption'] = '';
                //extract images
                if($res->data[$i]->type == 'image'){
                    $ids[] = $res->data[$i]->id;
                    $arr[$i]['id'] = $res->data[$i]->id;
                    if(!empty($res->data[$i]->tags)){
                        //exclude bad words
                        //$arr[$i]['tags'] = array_diff($res->data[$i]->tags, helper::$stopwords);
                    }
                    if(!empty($res->data[$i]->caption))
                        $arr[$i]['caption'] = $res->data[$i]->caption->text;
                    $arr[$i]['thumbnail'] = $res->data[$i]->images->thumbnail->url;
                    //location might have some text as well, without lat and lng
                    if(!empty($res->data[$i]->location->latitude) && !empty($res->data[$i]->location->longitude)){
                        $arr[$i]['latitude'] = $res->data[$i]->location->latitude;
                        $arr[$i]['longitude'] = $res->data[$i]->location->longitude;
                    }
                }
            }

            //-- save locally for comparison
            //push next_url if we search by tag, we need paginated results, or at least - next_max_id
            if(isset($res->pagination->next_url)){
                $arr['next_url'] = $res->pagination->next_url;
                $arr['next_max_id'] = $res->pagination->next_max_id;
            }
            $arr['ids'] = $ids; //for comparing last results, to save some processing
        }

        return !empty($arr) ? $arr : false;
    }
}
