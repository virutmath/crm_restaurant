<?php

/**
 * CURL client PHP
 * @author <dinhtoan1905@gmail.com>
 */

/**
 * Client Interface
 *
 * All clients must implement this interface
 *
 * The 4 http functions just need to return the raw data from the API
 */
interface ClientInterface
{

    function get($url, array $data = array());

    function post($url, array $data = array());

    function put($url, array $data = array());

    function delete($url, array $data = array());

}

/**
 * Curl Client
 *
 * Uses curl to access the API
 */
class CurlClient implements ClientInterface
{

    /**
     * Curl Resource
     *
     * @var curl resource
     */
    protected $curl = null;
    /**
     * Thoi gian bat dau xu ly curl
     */
    protected $start_generate_time = 0;
    protected $timeOutConnect = 2; //thoi gian mac dinh timeout la 1 giay

    /**
     * Constructor
     *
     * Initializes the curl object
     */
    function __construct($timeOut = 1)
    {
        $this->timeOutConnect = $timeOut;
        $this->initializeCurl();
    }

    /**
     * SET authen digest
     *
     * @param string $username ten tk dang nhap authen
     * @param string $password mat khau
     * @access public
     */

    function setAuthenDigest($username, $password)
    {
        curl_setopt($this->curl, CURLOPT_USERPWD, $username . ":" . $password);
        curl_setopt($this->curl, CURLOPT_HTTPAUTH, CURLAUTH_DIGEST);
    }

    /**
     * GET
     *
     * @param string $url URL to send get request to
     * @param array $data GET data
     * @return Response
     * @access public
     */
    public function get($url, array $data = array())
    {
        curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($this->curl, CURLOPT_URL, sprintf("%s?%s", $url, http_build_query($data)));
        $data = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
        return $this->fetch($data);
    }

    /**
     * POST
     *
     * @param string $url URL to send post request to
     * @param array $data POST data
     * @return Response
     * @access public
     */
    public function post($url, array $data = array())
    {
        curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($this->curl, CURLOPT_URL, $url);
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $data);
        $data = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
        return $this->fetch($data);
    }

    /**
     * PUT
     *
     * @param string $url URL to send put request to
     * @param array $data PUT data
     * @return Response
     * @access public
     */
    public function put($url, array $data = array())
    {
        curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, 'PUT');
    }

    /**
     * DELETE
     *
     * @param string $url URL to send delete request to
     * @param array $data DELETE data
     * @return Response
     * @access public
     */
    public function delete($url, array $data = array())
    {
        curl_setopt($this->curl, CURLOPT_URL, sprintf("%s?%s", $url, http_build_query($data)));
        curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
        $data = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
        return $this->fetch($data);
    }

    /**
     * Initialize curl
     *
     * Sets initial parameters on the curl object
     *
     * @access protected
     */
    protected function initializeCurl()
    {
        $this->start_generate_time = MyLog::microtime_float();
        $this->curl = curl_init();
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, false);
        //thoi gian mac dinh cho phep connect
        curl_setopt($this->curl, CURLOPT_TIMEOUT, $this->timeOutConnect);
        curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, true);
    }

    /**
     * Fetch
     *
     * Execute the curl object
     *
     * @return StdClass
     * @access protected
     * @throws ApiException
     */
    protected function fetch($debug_backtrace)
    {
        $raw_response = curl_exec($this->curl);

        $error = curl_error($this->curl);
        $code = curl_getinfo($this->curl, CURLINFO_HTTP_CODE);
        $debug_backtrace[0]["code"] = $code;
        if ($error) {
            //bat dau ghi log loi vao file
            MyLog::checkLogError($error, "curl", $debug_backtrace);
        }
        //curl_close($this->curl);
        //bat dau tinh thoi gian xu ly
        $time_request = MyLog::microtime_float() - $this->start_generate_time;
        //sau day tiep tuc gan lai thoi gian de xu ly tiep
        $this->start_generate_time = MyLog::microtime_float();
        //kiem tra xem time request co bi slow qua ko
        MyLog::checkLogSlow($time_request, "curl", $debug_backtrace);
        return $raw_response;
    }

}