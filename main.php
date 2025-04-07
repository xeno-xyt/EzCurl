<?php
/**
 * EzCurl - A super simple HTTP client library powered by cURL
 * 
 * @author KJ@DigitalOcean
 * @version 1.0.0
 *
 * Why i made this?
 * I want to make HTTP Request simpler for beginners and usable perfomance!
 */

class EzCurl
{
    /**
     * cURL handle
     * @var resource
     */
    private $curl;
    
    /**
     * Response data
     * @var string
     */
    private $response = null;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        if (!extension_loaded('curl')) {
            throw new \RuntimeException('cURL extension is not loaded');
        }
        
        $this->curl = curl_init();
        
        // Set default options
        curl_setopt_array($this->curl, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_USERAGENT => 'EzCurl/1.0',
        ]);
    }
    
    /**
     * Add request body
     * 
     * @param string $contentType Content type of the body
     * @param mixed $data Body data (array for JSON, string for text)
     * @return $this
     */
    public function addBody($contentType, $data)
    {
        // Set content type header
        $this->addHeader('Content-Type', $contentType);
        
        // Process data based on content type
        if (strpos($contentType, 'json') !== false && is_array($data)) {
            $data = json_encode($data);
        }
        
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $data);
        return $this;
    }
    
    /**
     * Add a request header
     * 
     * @param string $name Header name
     * @param string $value Header value
     * @return $this
     */
    public function addHeader($name, $value)
    {
        // Initialize headers array if not exists
        if (!isset($this->headers)) {
            $this->headers = [];
        }
        
        $this->headers[] = "$name: $value";
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, $this->headers);
        return $this;
    }
    
    /**
     * Set Accept header
     * 
     * @param string $type Accepted content type
     * @return $this
     */
    public function accept($type)
    {
        return $this->addHeader('Accept', $type);
    }
    
    /**
     * Make an HTTP request
     * 
     * @param string $method HTTP method (GET, POST, etc.)
     * @param string $url URL to request
     * @return $this
     */
    public function makeRequest($method, $url)
    {
        // Set URL
        curl_setopt($this->curl, CURLOPT_URL, $url);
        
        // Set request method
        $method = strtoupper($method);
        curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, $method);
        
        // Execute request
        $this->response = curl_exec($this->curl);
        return $this;
    }
    
    /**
     * Get response
     * 
     * @return string|null Response body
     */
    public function Response()
    {
        // Auto-detect JSON and decode if accept header was application/json
        if (isset($this->headers)) {
            foreach ($this->headers as $header) {
                if (strpos($header, 'Accept: application/json') !== false) {
                    $json = json_decode($this->response);
                    if (json_last_error() === JSON_ERROR_NONE) {
                        return $json;
                    }
                }
            }
        }
        
        return $this->response;
    }
    
    /**
     * Get HTTP status code
     * 
     * @return int
     */
    public function getStatusCode()
    {
        return curl_getinfo($this->curl, CURLINFO_HTTP_CODE);
    }
    
    /**
     * Close cURL connection
     */
    public function close()
    {
        if (is_resource($this->curl)) {
            curl_close($this->curl);
            $this->curl = null;
        }
    }
    
    /**
     * Destructor
     */
    public function __destruct()
    {
        $this->close();
    }
}
