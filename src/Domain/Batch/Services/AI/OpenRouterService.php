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

class OpenRouterService extends Service
{
    protected Client $guzzleClient;

    protected string $apiKey;

    public function __construct()
    {
        $this->apiKey = Config::getEnv('OPENROUTER_KEY');

        $this->guzzleClient = new Client([
            'base_uri' => 'https://openrouter.ai',
            'timeout' => 5 * 60,
        ]);
    }

    public function chatCompletions(stdClass $input): ?stdClass
    {
        return $this->executePost('/api/v1/chat/completions', $input);
    }

    public function createEmbedding(stdClass $input): ?stdClass
    {
        return $this->executePost('/api/v1/embeddings', $input);
    }

    /**
     * @param string $endpoint
     * @param stdClass $input
     * @return stdClass|null
     * @throws InternalErrorException
     */
    protected function executePost(string $endpoint, stdClass $input): ?stdClass
    {
        try {
            $payload = json_decode(json_encode($input), true);

            $response = $this->guzzleClient->post($endpoint, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => "Bearer {$this->apiKey}",
                ],
                'json' => $payload,
            ]);

            $responseBody = $response->getBody()->getContents();
            return json_decode($responseBody);
        } catch (GuzzleException $e) {
            return $this->handleGuzzleException($e);
        } catch (Throwable $t) {
            return $this->handleUnexpectedException($t);
        }
    }

    /**
     * @param GuzzleException $e
     * @return stdClass|null
     * @throws InternalErrorException
     */
    protected function handleGuzzleException(GuzzleException $e): ?stdClass
    {
        $exceptionDetails = new ExceptionDetails();
        $errorMessage = 'OpenRouter API Error';

        if (method_exists($e, 'hasResponse') && $e->hasResponse()) {
            $errorBody = $e->getResponse()->getBody()->getContents();
            $errorData = json_decode($errorBody, true);

            if ($errorData && isset($errorData['error']['message'])) {
                $errorMessage = $errorData['error']['message'];
            }

            $exceptionDetails->addDetail('OpenRouter Error', [
                'statusCode' => $e->getResponse()->getStatusCode(),
                'response' => $errorBody,
            ]);
        }

        $exceptionDetails->addDetail('Guzzle Exception', ['message' => $e->getMessage()]);

        if ($this->throwErrors) {
            throw new InternalErrorException($errorMessage, $exceptionDetails);
        }

        return null;
    }

    /**
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
