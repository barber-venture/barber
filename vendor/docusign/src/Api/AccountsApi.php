<?php
/**
 * AccountsApi
 * PHP version 5
 *
 * @category Class
 * @package  DocuSign\eSign
 * @author   http://github.com/swagger-api/swagger-codegen
 * @license  http://www.apache.org/licenses/LICENSE-2.0 Apache Licene v2
 * @link     https://github.com/swagger-api/swagger-codegen
 */
/**
 *  Copyright 2016 SmartBear Software
 *
 *  Licensed under the Apache License, Version 2.0 (the "License");
 *  you may not use this file except in compliance with the License.
 *  You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 *  Unless required by applicable law or agreed to in writing, software
 *  distributed under the License is distributed on an "AS IS" BASIS,
 *  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *  See the License for the specific language governing permissions and
 *  limitations under the License.
 */

/**
 * NOTE: This class is auto generated by the swagger code generator program. 
 * https://github.com/swagger-api/swagger-codegen 
 * Do not edit the class manually.
 */


namespace DocuSign\eSign\Api\AccountsApi;

class GetAccountInformationOptions
{
        
    /**
      * $op 
      * @var string
      */
    protected $op;

    /**
     * Gets op
     * @return string
     */
    public function getOp()
    {
        return $this->op;
    }
  
    /**
     * Sets op
     * @param string $op 
     * @return $this
     */
    public function setOp($op)
    {
        $this->op = $op;
        return $this;
    }
        
    /**
      * $include_account_settings When set to **true**, includes the account settings for the account in the response.
      * @var string
      */
    protected $include_account_settings;

    /**
     * Gets include_account_settings
     * @return string
     */
    public function getIncludeAccountSettings()
    {
        return $this->include_account_settings;
    }
  
    /**
     * Sets include_account_settings
     * @param string $include_account_settings When set to **true**, includes the account settings for the account in the response.
     * @return $this
     */
    public function setIncludeAccountSettings($include_account_settings)
    {
        $this->include_account_settings = $include_account_settings;
        return $this;
    }
    
}


namespace DocuSign\eSign\Api;

use \DocuSign\eSign\Configuration;
use \DocuSign\eSign\ApiClient;
use \DocuSign\eSign\ApiException;
use \DocuSign\eSign\ObjectSerializer;

/**
 * AccountsApi Class Doc Comment
 *
 * @category Class
 * @package  DocuSign\eSign
 * @author   http://github.com/swagger-api/swagger-codegen
 * @license  http://www.apache.org/licenses/LICENSE-2.0 Apache Licene v2
 * @link     https://github.com/swagger-api/swagger-codegen
 */
class AccountsApi
{

    /**
     * API Client
     * @var \DocuSign\eSign\ApiClient instance of the ApiClient
     */
    protected $apiClient;
  
    /**
     * Constructor
     * @param \DocuSign\eSign\ApiClient|null $apiClient The api client to use
     */
    function __construct($apiClient = null)
    {
        if ($apiClient == null) {
            $apiClient = new ApiClient();
            $apiClient->getConfig()->setHost('https://www.docusign.net/restapi');
        }
  
        $this->apiClient = $apiClient;
    }
  
    /**
     * Get API client
     * @return \DocuSign\eSign\ApiClient get the API client
     */
    public function getApiClient()
    {
        return $this->apiClient;
    }
  
    /**
     * Set the API client
     * @param \DocuSign\eSign\ApiClient $apiClient set the API client
     * @return AccountsApi
     */
    public function setApiClient(ApiClient $apiClient)
    {
        $this->apiClient = $apiClient;
        return $this;
    }

    
    /**
     * getAccountInformation
     *
     * Retrieves the account information for the specified account.
     *
     *
     @param string $account_id The external account number (int) or account ID Guid. (required) *
     @param  $options Options for modifying the behavior of the function. (optional)
     * @return \DocuSign\eSign\Model\AccountInformation
     * @throws \DocuSign\eSign\ApiException on non-2xx response
     */
    public function getAccountInformation($account_id, AccountsApi\GetAccountInformationOptions $options = null)
    {
        list($response, $statusCode, $httpHeader) = $this->getAccountInformationWithHttpInfo ($account_id, $options);
        return $response; 
    }


    /**
     * getAccountInformationWithHttpInfo
     *
     * Retrieves the account information for the specified account.
     *
     *
     @param string $account_id The external account number (int) or account ID Guid. (required) *
     @param  $options Options for modifying the behavior of the function. (optional)
     * @return Array of \DocuSign\eSign\Model\AccountInformation, HTTP status code, HTTP response headers (array of strings)
     * @throws \DocuSign\eSign\ApiException on non-2xx response
     */
    public function getAccountInformationWithHttpInfo($account_id, AccountsApi\GetAccountInformationOptions $options = null)
    {
        
        // verify the required parameter 'account_id' is set
        if ($account_id === null) {
            throw new \InvalidArgumentException('Missing the required parameter $account_id when calling getAccountInformation');
        }
  
        // parse inputs
        $resourcePath = "/v2/accounts/{accountId}";
        $httpBody = '';
        $queryParams = array();
        $headerParams = array();
        $formParams = array();
        $_header_accept = ApiClient::selectHeaderAccept(array('application/json'));
        if (!is_null($_header_accept)) {
            $headerParams['Accept'] = $_header_accept;
        }
        $headerParams['Content-Type'] = ApiClient::selectHeaderContentType(array());
  
        
        if ($options != null)
        {
        // query params
        
        
        if ($options->getOp() !== null) {
            $queryParams['op'] = $this->apiClient->getSerializer()->toQueryValue($options->getOp());
        }
        
        if ($options->getIncludeAccountSettings() !== null) {
            $queryParams['include_account_settings'] = $this->apiClient->getSerializer()->toQueryValue($options->getIncludeAccountSettings());
        }
        }
        
        
        // path params
        
        if ($account_id !== null) {
            $resourcePath = str_replace(
                "{" . "accountId" . "}",
                $this->apiClient->getSerializer()->toPathValue($account_id),
                $resourcePath
            );
        }
        // default format to json
        $resourcePath = str_replace("{format}", "json", $resourcePath);

        
        
  
        // for model (json/xml)
        if (isset($_tempBody)) {
            $httpBody = $_tempBody; // $_tempBody is the method argument, if present
        } elseif (count($formParams) > 0) {
            $httpBody = $formParams; // for HTTP post (form)
        }
        
        // make the API Call
        try {
            list($response, $statusCode, $httpHeader) = $this->apiClient->callApi(
                $resourcePath, 'GET',
                $queryParams, $httpBody,
                $headerParams, '\DocuSign\eSign\Model\AccountInformation'
            );
            
            if (!$response) {
                return array(null, $statusCode, $httpHeader);
            }

            return array(\DocuSign\eSign\ObjectSerializer::deserialize($response, '\DocuSign\eSign\Model\AccountInformation', $httpHeader), $statusCode, $httpHeader);
            
        } catch (ApiException $e) {
            switch ($e->getCode()) { 
            case 200:
                $data = \DocuSign\eSign\ObjectSerializer::deserialize($e->getResponseBody(), '\DocuSign\eSign\Model\AccountInformation', $e->getResponseHeaders());
                $e->setResponseObject($data);
                break;
            case 400:
                $data = \DocuSign\eSign\ObjectSerializer::deserialize($e->getResponseBody(), '\DocuSign\eSign\Model\ErrorDetails', $e->getResponseHeaders());
                $e->setResponseObject($data);
                break;
            }
  
            throw $e;
        }
    }
    
    /**
     * listCustomFields
     *
     * Gets a list of custom fields associated with the account.
     *
     *
     @param string $account_id The external account number (int) or account ID Guid. (required)
     * @return \DocuSign\eSign\Model\CustomFields
     * @throws \DocuSign\eSign\ApiException on non-2xx response
     */
    public function listCustomFields($account_id)
    {
        list($response, $statusCode, $httpHeader) = $this->listCustomFieldsWithHttpInfo ($account_id);
        return $response; 
    }


    /**
     * listCustomFieldsWithHttpInfo
     *
     * Gets a list of custom fields associated with the account.
     *
     *
     @param string $account_id The external account number (int) or account ID Guid. (required)
     * @return Array of \DocuSign\eSign\Model\CustomFields, HTTP status code, HTTP response headers (array of strings)
     * @throws \DocuSign\eSign\ApiException on non-2xx response
     */
    public function listCustomFieldsWithHttpInfo($account_id)
    {
        
        // verify the required parameter 'account_id' is set
        if ($account_id === null) {
            throw new \InvalidArgumentException('Missing the required parameter $account_id when calling listCustomFields');
        }
  
        // parse inputs
        $resourcePath = "/v2/accounts/{accountId}/custom_fields";
        $httpBody = '';
        $queryParams = array();
        $headerParams = array();
        $formParams = array();
        $_header_accept = ApiClient::selectHeaderAccept(array('application/json'));
        if (!is_null($_header_accept)) {
            $headerParams['Accept'] = $_header_accept;
        }
        $headerParams['Content-Type'] = ApiClient::selectHeaderContentType(array());
  
        
        
        // path params
        
        if ($account_id !== null) {
            $resourcePath = str_replace(
                "{" . "accountId" . "}",
                $this->apiClient->getSerializer()->toPathValue($account_id),
                $resourcePath
            );
        }
        // default format to json
        $resourcePath = str_replace("{format}", "json", $resourcePath);

        
        
  
        // for model (json/xml)
        if (isset($_tempBody)) {
            $httpBody = $_tempBody; // $_tempBody is the method argument, if present
        } elseif (count($formParams) > 0) {
            $httpBody = $formParams; // for HTTP post (form)
        }
        
        // make the API Call
        try {
            list($response, $statusCode, $httpHeader) = $this->apiClient->callApi(
                $resourcePath, 'GET',
                $queryParams, $httpBody,
                $headerParams, '\DocuSign\eSign\Model\CustomFields'
            );
            
            if (!$response) {
                return array(null, $statusCode, $httpHeader);
            }

            return array(\DocuSign\eSign\ObjectSerializer::deserialize($response, '\DocuSign\eSign\Model\CustomFields', $httpHeader), $statusCode, $httpHeader);
            
        } catch (ApiException $e) {
            switch ($e->getCode()) { 
            case 200:
                $data = \DocuSign\eSign\ObjectSerializer::deserialize($e->getResponseBody(), '\DocuSign\eSign\Model\CustomFields', $e->getResponseHeaders());
                $e->setResponseObject($data);
                break;
            case 400:
                $data = \DocuSign\eSign\ObjectSerializer::deserialize($e->getResponseBody(), '\DocuSign\eSign\Model\ErrorDetails', $e->getResponseHeaders());
                $e->setResponseObject($data);
                break;
            }
  
            throw $e;
        }
    }
    
    /**
     * listSettings
     *
     * Gets account settings information.
     *
     *
     @param string $account_id The external account number (int) or account ID Guid. (required)
     * @return \DocuSign\eSign\Model\AccountSettingsInformation
     * @throws \DocuSign\eSign\ApiException on non-2xx response
     */
    public function listSettings($account_id)
    {
        list($response, $statusCode, $httpHeader) = $this->listSettingsWithHttpInfo ($account_id);
        return $response; 
    }


    /**
     * listSettingsWithHttpInfo
     *
     * Gets account settings information.
     *
     *
     @param string $account_id The external account number (int) or account ID Guid. (required)
     * @return Array of \DocuSign\eSign\Model\AccountSettingsInformation, HTTP status code, HTTP response headers (array of strings)
     * @throws \DocuSign\eSign\ApiException on non-2xx response
     */
    public function listSettingsWithHttpInfo($account_id)
    {
        
        // verify the required parameter 'account_id' is set
        if ($account_id === null) {
            throw new \InvalidArgumentException('Missing the required parameter $account_id when calling listSettings');
        }
  
        // parse inputs
        $resourcePath = "/v2/accounts/{accountId}/settings";
        $httpBody = '';
        $queryParams = array();
        $headerParams = array();
        $formParams = array();
        $_header_accept = ApiClient::selectHeaderAccept(array('application/json'));
        if (!is_null($_header_accept)) {
            $headerParams['Accept'] = $_header_accept;
        }
        $headerParams['Content-Type'] = ApiClient::selectHeaderContentType(array());
  
        
        
        // path params
        
        if ($account_id !== null) {
            $resourcePath = str_replace(
                "{" . "accountId" . "}",
                $this->apiClient->getSerializer()->toPathValue($account_id),
                $resourcePath
            );
        }
        // default format to json
        $resourcePath = str_replace("{format}", "json", $resourcePath);

        
        
  
        // for model (json/xml)
        if (isset($_tempBody)) {
            $httpBody = $_tempBody; // $_tempBody is the method argument, if present
        } elseif (count($formParams) > 0) {
            $httpBody = $formParams; // for HTTP post (form)
        }
        
        // make the API Call
        try {
            list($response, $statusCode, $httpHeader) = $this->apiClient->callApi(
                $resourcePath, 'GET',
                $queryParams, $httpBody,
                $headerParams, '\DocuSign\eSign\Model\AccountSettingsInformation'
            );
            
            if (!$response) {
                return array(null, $statusCode, $httpHeader);
            }

            return array(\DocuSign\eSign\ObjectSerializer::deserialize($response, '\DocuSign\eSign\Model\AccountSettingsInformation', $httpHeader), $statusCode, $httpHeader);
            
        } catch (ApiException $e) {
            switch ($e->getCode()) { 
            case 200:
                $data = \DocuSign\eSign\ObjectSerializer::deserialize($e->getResponseBody(), '\DocuSign\eSign\Model\AccountSettingsInformation', $e->getResponseHeaders());
                $e->setResponseObject($data);
                break;
            case 400:
                $data = \DocuSign\eSign\ObjectSerializer::deserialize($e->getResponseBody(), '\DocuSign\eSign\Model\ErrorDetails', $e->getResponseHeaders());
                $e->setResponseObject($data);
                break;
            }
  
            throw $e;
        }
    }
    
    /**
     * updateSettings
     *
     * Updates the account settings for an account.
     *
     *
     @param string $account_id The external account number (int) or account ID Guid. (required) *
     @param \DocuSign\eSign\Model\AccountSettingsInformation $account_settings_information TBD Description (optional)
     * @return void
     * @throws \DocuSign\eSign\ApiException on non-2xx response
     */
    public function updateSettings($account_id, $account_settings_information = null)
    {
        list($response, $statusCode, $httpHeader) = $this->updateSettingsWithHttpInfo ($account_id, $account_settings_information);
        return $response; 
    }


    /**
     * updateSettingsWithHttpInfo
     *
     * Updates the account settings for an account.
     *
     *
     @param string $account_id The external account number (int) or account ID Guid. (required) *
     @param \DocuSign\eSign\Model\AccountSettingsInformation $account_settings_information TBD Description (optional)
     * @return Array of null, HTTP status code, HTTP response headers (array of strings)
     * @throws \DocuSign\eSign\ApiException on non-2xx response
     */
    public function updateSettingsWithHttpInfo($account_id, $account_settings_information = null)
    {
        
        // verify the required parameter 'account_id' is set
        if ($account_id === null) {
            throw new \InvalidArgumentException('Missing the required parameter $account_id when calling updateSettings');
        }
  
        // parse inputs
        $resourcePath = "/v2/accounts/{accountId}/settings";
        $httpBody = '';
        $queryParams = array();
        $headerParams = array();
        $formParams = array();
        $_header_accept = ApiClient::selectHeaderAccept(array('application/json'));
        if (!is_null($_header_accept)) {
            $headerParams['Accept'] = $_header_accept;
        }
        $headerParams['Content-Type'] = ApiClient::selectHeaderContentType(array());
  
        
        
        // path params
        
        if ($account_id !== null) {
            $resourcePath = str_replace(
                "{" . "accountId" . "}",
                $this->apiClient->getSerializer()->toPathValue($account_id),
                $resourcePath
            );
        }
        // default format to json
        $resourcePath = str_replace("{format}", "json", $resourcePath);

        
        // body params
        $_tempBody = null;
        if (isset($account_settings_information)) {
            $_tempBody = $account_settings_information;
        }
  
        // for model (json/xml)
        if (isset($_tempBody)) {
            $httpBody = $_tempBody; // $_tempBody is the method argument, if present
        } elseif (count($formParams) > 0) {
            $httpBody = $formParams; // for HTTP post (form)
        }
        
        // make the API Call
        try {
            list($response, $statusCode, $httpHeader) = $this->apiClient->callApi(
                $resourcePath, 'PUT',
                $queryParams, $httpBody,
                $headerParams
            );
            
            return array(null, $statusCode, $httpHeader);
            
        } catch (ApiException $e) {
            switch ($e->getCode()) { 
            case 400:
                $data = \DocuSign\eSign\ObjectSerializer::deserialize($e->getResponseBody(), '\DocuSign\eSign\Model\ErrorDetails', $e->getResponseHeaders());
                $e->setResponseObject($data);
                break;
            }
  
            throw $e;
        }
    }
    
    /**
     * listSharedAccess
     *
     * Reserved: Gets the shared item status for one or more users.
     *
     *
     @param string $account_id The external account number (int) or account ID Guid. (required)
     * @return \DocuSign\eSign\Model\AccountSharedAccess
     * @throws \DocuSign\eSign\ApiException on non-2xx response
     */
    public function listSharedAccess($account_id)
    {
        list($response, $statusCode, $httpHeader) = $this->listSharedAccessWithHttpInfo ($account_id);
        return $response; 
    }


    /**
     * listSharedAccessWithHttpInfo
     *
     * Reserved: Gets the shared item status for one or more users.
     *
     *
     @param string $account_id The external account number (int) or account ID Guid. (required)
     * @return Array of \DocuSign\eSign\Model\AccountSharedAccess, HTTP status code, HTTP response headers (array of strings)
     * @throws \DocuSign\eSign\ApiException on non-2xx response
     */
    public function listSharedAccessWithHttpInfo($account_id)
    {
        
        // verify the required parameter 'account_id' is set
        if ($account_id === null) {
            throw new \InvalidArgumentException('Missing the required parameter $account_id when calling listSharedAccess');
        }
  
        // parse inputs
        $resourcePath = "/v2/accounts/{accountId}/shared_access";
        $httpBody = '';
        $queryParams = array();
        $headerParams = array();
        $formParams = array();
        $_header_accept = ApiClient::selectHeaderAccept(array('application/json'));
        if (!is_null($_header_accept)) {
            $headerParams['Accept'] = $_header_accept;
        }
        $headerParams['Content-Type'] = ApiClient::selectHeaderContentType(array());
  
        
        
        // path params
        
        if ($account_id !== null) {
            $resourcePath = str_replace(
                "{" . "accountId" . "}",
                $this->apiClient->getSerializer()->toPathValue($account_id),
                $resourcePath
            );
        }
        // default format to json
        $resourcePath = str_replace("{format}", "json", $resourcePath);

        
        
  
        // for model (json/xml)
        if (isset($_tempBody)) {
            $httpBody = $_tempBody; // $_tempBody is the method argument, if present
        } elseif (count($formParams) > 0) {
            $httpBody = $formParams; // for HTTP post (form)
        }
        
        // make the API Call
        try {
            list($response, $statusCode, $httpHeader) = $this->apiClient->callApi(
                $resourcePath, 'GET',
                $queryParams, $httpBody,
                $headerParams, '\DocuSign\eSign\Model\AccountSharedAccess'
            );
            
            if (!$response) {
                return array(null, $statusCode, $httpHeader);
            }

            return array(\DocuSign\eSign\ObjectSerializer::deserialize($response, '\DocuSign\eSign\Model\AccountSharedAccess', $httpHeader), $statusCode, $httpHeader);
            
        } catch (ApiException $e) {
            switch ($e->getCode()) { 
            case 200:
                $data = \DocuSign\eSign\ObjectSerializer::deserialize($e->getResponseBody(), '\DocuSign\eSign\Model\AccountSharedAccess', $e->getResponseHeaders());
                $e->setResponseObject($data);
                break;
            case 400:
                $data = \DocuSign\eSign\ObjectSerializer::deserialize($e->getResponseBody(), '\DocuSign\eSign\Model\ErrorDetails', $e->getResponseHeaders());
                $e->setResponseObject($data);
                break;
            }
  
            throw $e;
        }
    }
    
    /**
     * listUnsupportedFileTypes
     *
     * Gets a list of unsupported file types.
     *
     *
     @param string $account_id The external account number (int) or account ID Guid. (required)
     * @return \DocuSign\eSign\Model\FileTypeList
     * @throws \DocuSign\eSign\ApiException on non-2xx response
     */
    public function listUnsupportedFileTypes($account_id)
    {
        list($response, $statusCode, $httpHeader) = $this->listUnsupportedFileTypesWithHttpInfo ($account_id);
        return $response; 
    }


    /**
     * listUnsupportedFileTypesWithHttpInfo
     *
     * Gets a list of unsupported file types.
     *
     *
     @param string $account_id The external account number (int) or account ID Guid. (required)
     * @return Array of \DocuSign\eSign\Model\FileTypeList, HTTP status code, HTTP response headers (array of strings)
     * @throws \DocuSign\eSign\ApiException on non-2xx response
     */
    public function listUnsupportedFileTypesWithHttpInfo($account_id)
    {
        
        // verify the required parameter 'account_id' is set
        if ($account_id === null) {
            throw new \InvalidArgumentException('Missing the required parameter $account_id when calling listUnsupportedFileTypes');
        }
  
        // parse inputs
        $resourcePath = "/v2/accounts/{accountId}/unsupported_file_types";
        $httpBody = '';
        $queryParams = array();
        $headerParams = array();
        $formParams = array();
        $_header_accept = ApiClient::selectHeaderAccept(array('application/json'));
        if (!is_null($_header_accept)) {
            $headerParams['Accept'] = $_header_accept;
        }
        $headerParams['Content-Type'] = ApiClient::selectHeaderContentType(array());
  
        
        
        // path params
        
        if ($account_id !== null) {
            $resourcePath = str_replace(
                "{" . "accountId" . "}",
                $this->apiClient->getSerializer()->toPathValue($account_id),
                $resourcePath
            );
        }
        // default format to json
        $resourcePath = str_replace("{format}", "json", $resourcePath);

        
        
  
        // for model (json/xml)
        if (isset($_tempBody)) {
            $httpBody = $_tempBody; // $_tempBody is the method argument, if present
        } elseif (count($formParams) > 0) {
            $httpBody = $formParams; // for HTTP post (form)
        }
        
        // make the API Call
        try {
            list($response, $statusCode, $httpHeader) = $this->apiClient->callApi(
                $resourcePath, 'GET',
                $queryParams, $httpBody,
                $headerParams, '\DocuSign\eSign\Model\FileTypeList'
            );
            
            if (!$response) {
                return array(null, $statusCode, $httpHeader);
            }

            return array(\DocuSign\eSign\ObjectSerializer::deserialize($response, '\DocuSign\eSign\Model\FileTypeList', $httpHeader), $statusCode, $httpHeader);
            
        } catch (ApiException $e) {
            switch ($e->getCode()) { 
            case 200:
                $data = \DocuSign\eSign\ObjectSerializer::deserialize($e->getResponseBody(), '\DocuSign\eSign\Model\FileTypeList', $e->getResponseHeaders());
                $e->setResponseObject($data);
                break;
            case 400:
                $data = \DocuSign\eSign\ObjectSerializer::deserialize($e->getResponseBody(), '\DocuSign\eSign\Model\ErrorDetails', $e->getResponseHeaders());
                $e->setResponseObject($data);
                break;
            }
  
            throw $e;
        }
    }
    
}
