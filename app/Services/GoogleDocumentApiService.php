<?php

namespace App\Services;

use Google_Client;
use Google_Service_Docs;
use Google_Service_Docs_Document;

class GoogleDocumentApiService
{
    protected Google_Client $client;
    protected Google_Service_Docs $docsService;

    public function __construct(string $accessToken)
    {
        $this->client = new Google_Client();
        $this->client->setAccessToken($accessToken);

        if ($this->client->isAccessTokenExpired()) {
            throw new \Exception('Access token has expired. Please re-authenticate.');
        }

        $this->docsService = new Google_Service_Docs($this->client);
    }

    /**
     * Check if a document exists.
     *
     * @param string $documentId
     * @return bool
     */
    protected function documentExists(string $documentId): bool
    {
        try {
            $this->docsService->documents->get($documentId);
            return true;
        } catch (\Google_Service_Exception $e) {
            if ($e->getCode() === 404) {
                return false;
            }
            throw $e;
        }
    }

    /**
     * Create a new Google Doc with the specified ID.
     *
     * @param string $documentId
     * @return Google_Service_Docs_Document
     * @throws \Exception
     */
    protected function createDocument(string $documentId): Google_Service_Docs_Document
    {
        $document = new Google_Service_Docs_Document([
            'title' => 'Time tracker ' . $documentId,
        ]);
        $createdDoc = $this->docsService->documents->create($document);

        // Google does not allow setting a specific ID during creation.
        // You can store a mapping between the desired ID and the actual ID created.
        return $createdDoc;
    }

    /**
     * Update a Google Doc with the provided content.
     * If the document does not exist, create it first.
     *
     * @param string $documentId
     * @param string $content
     * @return array
     * @throws \Exception
     */
    public function updateDocument(string $documentId, string $content): array
    {
        if (!$this->documentExists($documentId)) {
            $this->createDocument($documentId);
        }

        $requests = [
            new \Google_Service_Docs_Request([
                'insertText' => [
                    'text' => $content,
                    'location' => [
                        'index' => 1, // Insert at the beginning of the document
                    ],
                ],
            ]),
        ];

        $batchUpdateRequest = new \Google_Service_Docs_BatchUpdateDocumentRequest([
            'requests' => $requests,
        ]);

        return $this->docsService->documents->batchUpdate($documentId, $batchUpdateRequest);
    }

    /**
     * Retrieve the content of a Google Doc.
     * If the document does not exist, return an empty value.
     *
     * @param string $documentId
     * @return string
     * @throws \Exception
     */
    public function getDocumentContent(string $documentId): string
    {
        if (!$this->documentExists($documentId)) {
            return '';
        }

        $document = $this->docsService->documents->get($documentId);

        $content = '';
        foreach ($document->getBody()->getContent() as $element) {
            if (isset($element['paragraph']['elements'])) {
                foreach ($element['paragraph']['elements'] as $textElement) {
                    $content .= $textElement['textRun']['content'] ?? '';
                }
            }
        }

        return $content;
    }
}
