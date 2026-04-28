<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Database;

class AiController
{
    public function analyze(): void
    {
        $input = json_decode(file_get_contents('php://input'), true);
        $imageData = $input['image'] ?? null;
        $isReimagine = $input['reimagine'] ?? false;

        if (!$imageData) {
            json(['error' => 'No image data provided']);
        }

        // Remove prefix if exists (e.g., "data:image/jpeg;base64,")
        if (preg_match('/^data:image\/(\w+);base64,/', $imageData, $type)) {
            $imageData = substr($imageData, strpos($imageData, ',') + 1);
        }

        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT key_value FROM settings WHERE key_name = ?");
        
        $stmt->execute(['gemini_api_key']);
        $apiKey = $stmt->fetchColumn();

        $stmt->execute(['ai_model_name']);
        $model = $stmt->fetchColumn();

        if (!$apiKey || !$model) {
            json(['error' => 'API settings not found']);
        }

        $prompt = "Analyze dominant visual themes and mood of this photo. Return a relevant Bible verse, a short devotional summary, and a full devotion.
        
        STRICT RULING: Skip all internal thinking, reasoning, or monologue. Go straight to the answer.
        
        STRICT REQUIREMENT: Output ONLY a valid JSON object. Do not include any preamble, markdown formatting, or postscript.
        
        JSON Schema:
        {
          \"verseText\": \"[Short, powerful Bible Verse]\",
          \"verseReference\": \"[Book, Chapter, Verse]\",
          \"devotionalSummary\": \"[A concise, 15-word summary of the devotion for image overlay]\",
          \"fullDevotion\": \"[A full 3-4 sentence devotion and implication text]\",
          \"vibeColor\": \"[A HEX color complimenting the photo]\",
          \"uniqueFont\": \"[A Google Font family name for the overlay]\",
          \"textPosition\": \"[top|center|bottom]\"
        }";

        if ($isReimagine) {
            $prompt .= "\nSTRICT INSTRUCTION: This is a 'Reimagine' request. Return a COMPLETELY DIFFERENT verse, devotion, vibe color, font, and text position than a standard interpretation.";
        }

        $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}";

        $payload = [
            "contents" => [
                [
                    "parts" => [
                        ["text" => $prompt],
                        [
                            "inline_data" => [
                                "mime_type" => "image/jpeg",
                                "data" => $imageData
                            ]
                        ]
                    ]
                ]
            ],
            "generationConfig" => [
                "temperature" => $isReimagine ? 0.9 : 0.4,
                "topK" => 32,
                "topP" => 1,
                "maxOutputTokens" => 1024,
                "response_mime_type" => "application/json"
            ]
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // For local XAMPP environments
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($curlError) {
            json(['error' => 'Connection error: ' . $curlError]);
        }

        if ($httpCode !== 200) {
            json(['error' => 'Gemini API call failed with code ' . $httpCode, 'details' => json_decode($response, true)]);
        }

        $result = json_decode($response, true);
        
        // Check for safety blocks
        if (isset($result['candidates'][0]['finishReason']) && $result['candidates'][0]['finishReason'] === 'SAFETY') {
            json(['error' => 'The spiritual lens filtered this image for safety reasons. Please try a different photo.']);
        }

        // Concatenate all text parts (some models like Gemma return thoughts as separate parts)
        $content = '';
        if (isset($result['candidates'][0]['content']['parts'])) {
            foreach ($result['candidates'][0]['content']['parts'] as $part) {
                if (isset($part['text'])) {
                    $content .= $part['text'];
                }
            }
        }
        
        // Robust JSON extraction: Find the first { and the last }
        $cleanJson = null;
        if (preg_match('/\{.*\}/s', $content, $matches)) {
            $jsonCandidate = $matches[0];
            $cleanJson = json_decode($jsonCandidate, true);
        }

        // If that failed, try decoding the whole thing (just in case)
        if (!$cleanJson) {
            $cleanJson = json_decode($content, true);
        }

        if (!$cleanJson) {
            // Log for debugging
            error_log("Failed to parse Gemini response: " . $response);
            json([
                'error' => 'Failed to parse spiritual reflection.', 
                'details' => 'The AI returned an invalid format.',
                'raw_full' => $response // FULL API RESPONSE
            ]);
        }

        json($cleanJson);
    }
}
