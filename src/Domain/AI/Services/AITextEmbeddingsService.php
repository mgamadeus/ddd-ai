<?php

declare(strict_types=1);

namespace DDD\Domain\AI\Services;

use DDD\Domain\Common\Entities\Texts\Embeddings\TextEmbedding;
use DDD\Domain\Common\Entities\Texts\Text;
use DDD\Infrastructure\Services\Service;

class AITextEmbeddingsService extends Service
{
    /**
     * Returns Vector Embedding
     * @param string $content
     * @return TextEmbedding
     */
    public function generateEmbeddingForText(string $content): TextEmbedding
    {
        $text = new Text($content);
        return $text->embedding;
    }

}