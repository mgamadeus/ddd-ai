<?php

declare(strict_types=1);

namespace DDD\Domain\Batch\Services\AI;

use DDD\Infrastructure\Exceptions\ExceptionDetails;
use DDD\Infrastructure\Exceptions\InternalErrorException;
use DDD\Infrastructure\Libs\Config;
use DDD\Infrastructure\Services\Service;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use stdClass;
use Throwable;

class OpenAIService extends Service
{
    protected Client $guzzleClient;

    protected string $apiKey;

    public function __construct()
    {
        $this->apiKey = Config::getEnv('OPEN_AI_KEY');

        // Initialize Guzzle client
        $this->guzzleClient = new Client([
            'base_uri' => 'https://api.openai.com',
            'timeout' => 5 * 60,
        ]);
    }

    /**
     * Calls the OpenAI Chat Completions endpoint
     * @param stdClass $input
     * @return stdClass|null
     * @throws InternalErrorException
     */
    public function chatCompletions(stdClass $input): ?stdClass
    {
        try {
            // Convert stdClass to array for JSON encoding
            $payload = json_decode(json_encode($input), true);

            // Make synchronous POST request to chat completions endpoint
            $response = $this->guzzleClient->post('/v1/chat/completions', [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => "Bearer {$this->apiKey}",
                ],
                'json' => $payload,
            ]);

            // Decode response body and return as stdClass
            $responseBody = $response->getBody()->getContents();
            return json_decode($responseBody);
        } catch (GuzzleException $e) {
            return $this->handleGuzzleException($e);
        } catch (Throwable $t) {
            return $this->handleUnexpectedException($t);
        }
    }

    /**
     * Calls the OpenAI Embeddings endpoint
     * @param stdClass $input
     * @return stdClass|null
     * @throws InternalErrorException
     */
    public function createEmbedding(stdClass $input): ?stdClass
    {
        try {
            // Convert stdClass to array for JSON encoding
            $payload = json_decode(json_encode($input), true);

            // Make synchronous POST request to embeddings endpoint
            $response = $this->guzzleClient->post('/v1/embeddings', [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => "Bearer {$this->apiKey}",
                ],
                'json' => $payload,
            ]);

            // Decode response body and return as stdClass
            $responseBody = $response->getBody()->getContents();
            return json_decode($responseBody);
        } catch (GuzzleException $e) {
            return $this->handleGuzzleException($e);
        } catch (Throwable $t) {
            return $this->handleUnexpectedException($t);
        }
    }

    /**
     * Centralized Guzzle exception handling
     * @param GuzzleException $e
     * @return stdClass|null
     * @throws InternalErrorException
     */
    protected function handleGuzzleException(GuzzleException $e): ?stdClass
    {
        $exceptionDetails = new ExceptionDetails();
        $errorMessage = 'OpenAI API Error';
        
        if ($e->hasResponse()) {
            $errorBody = $e->getResponse()->getBody()->getContents();
            $errorData = json_decode($errorBody, true);
            
            if ($errorData && isset($errorData['error']['message'])) {
                $errorMessage = $errorData['error']['message'];
            }
            
            $exceptionDetails->addDetail('OpenAI Error', [
                'statusCode' => $e->getResponse()->getStatusCode(),
                'response' => $errorBody
            ]);
        }
        
        $exceptionDetails->addDetail('Guzzle Exception', ['message' => $e->getMessage()]);
        
        if ($this->throwErrors) {
            throw new InternalErrorException($errorMessage, $exceptionDetails);
        }
        return null;
    }

    /**
     * Centralized unexpected exception handling
     * @param Throwable $t
     * @return stdClass|null
     * @throws InternalErrorException
     */
    protected function handleUnexpectedException(Throwable $t): ?stdClass
    {
        if ($this->throwErrors) {
            $exceptionDetails = new ExceptionDetails();
            $exceptionDetails->addDetail('Unexpected Error', ['message' => $t->getMessage()]);
            throw new InternalErrorException('Unexpected error', $exceptionDetails);
        }
        return null;
    }
}
