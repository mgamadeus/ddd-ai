<?php

declare(strict_types=1);

namespace DDD\Presentation\Api\Batch\AI;

use DDD\Domain\Batch\Services\OpenAIService;
use DDD\Domain\Batch\Services\OpenRouterService;
use DDD\Presentation\Api\Batch\Base\Dtos\BatchReponseDto;
use DDD\Infrastructure\Exceptions\BadRequestException;
use DDD\Infrastructure\Exceptions\InternalErrorException;
use DDD\Presentation\Base\Controller\HttpController;
use DDD\Presentation\Base\OpenApi\Attributes\Summary;
use DDD\Presentation\Base\OpenApi\Attributes\Tag;
use DDD\Presentation\Base\Router\Routes\Post;
use DDD\Presentation\Base\Router\Routes\Route;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\HttpFoundation\Request;

#[Route('/ai')]
#[Tag(group: 'AI', name: 'OpenAI', description: 'OpenAI Endpoints')]
class BatchAIController extends HttpController
{
    /**
     * Call OpenAI Chat Completions API
     * @param Request $request
     * @param OpenAIService $openAIService
     * @return BatchReponseDto
     * @throws BadRequestException
     * @throws InternalErrorException
     * @throws GuzzleException
     */
    #[Post('/openAi/chatCompletions')]
    #[Summary('OpenAI Chat Completions')]
    public function chatCompletions(
        Request $request,
        OpenAIService $openAIService
    ): BatchReponseDto {
        $openAIService->throwErrors = true;
        
        // Extract body and decode to stdClass
        $body = $request->getContent();
        $payload = json_decode($body);
        
        if ($payload === null) {
            throw new BadRequestException('Invalid JSON payload');
        }
        
        $responseDto = new BatchReponseDto();
        $responseDto->responseData = $openAIService->chatCompletions($payload);
        return $responseDto;
    }

    /**
     * Call OpenAI Embeddings API
     * @param Request $request
     * @param OpenAIService $openAIService
     * @return BatchReponseDto
     * @throws BadRequestException
     * @throws InternalErrorException
     * @throws GuzzleException
     */
    #[Post('/openAi/embeddings')]
    #[Summary('OpenAI Create Embedding')]
    public function createEmbedding(
        Request $request,
        OpenAIService $openAIService
    ): BatchReponseDto {
        $openAIService->throwErrors = true;
        
        // Extract body and decode to stdClass
        $body = $request->getContent();
        $payload = json_decode($body);
        
        if ($payload === null) {
            throw new BadRequestException('Invalid JSON payload');
        }
        
        $responseDto = new BatchReponseDto();
        $responseDto->responseData = $openAIService->createEmbedding($payload);
        return $responseDto;
    }

    /**
     * Call OpenRouter Chat Completions API
     * @param Request $request
     * @param OpenRouterService $openRouterService
     * @return BatchReponseDto
     * @throws BadRequestException
     * @throws InternalErrorException
     * @throws GuzzleException
     */
    #[Post('/openRouter/chatCompletions')]
    #[Summary('OpenRouter Chat Completions')]
    public function openRouterChatCompletions(
        Request $request,
        OpenRouterService $openRouterService
    ): BatchReponseDto {
        $openRouterService->throwErrors = true;

        $body = $request->getContent();
        $payload = json_decode($body);

        if ($payload === null) {
            throw new BadRequestException('Invalid JSON payload');
        }

        $responseDto = new BatchReponseDto();
        $responseDto->responseData = $openRouterService->chatCompletions($payload);
        return $responseDto;
    }

    /**
     * Call OpenRouter Embeddings API
     * @param Request $request
     * @param OpenRouterService $openRouterService
     * @return BatchReponseDto
     * @throws BadRequestException
     * @throws InternalErrorException
     * @throws GuzzleException
     */
    #[Post('/openRouter/embeddings')]
    #[Summary('OpenRouter Create Embedding')]
    public function openRouterCreateEmbedding(
        Request $request,
        OpenRouterService $openRouterService
    ): BatchReponseDto {
        $openRouterService->throwErrors = true;

        $body = $request->getContent();
        $payload = json_decode($body);

        if ($payload === null) {
            throw new BadRequestException('Invalid JSON payload');
        }

        $responseDto = new BatchReponseDto();
        $responseDto->responseData = $openRouterService->createEmbedding($payload);
        return $responseDto;
    }
}
