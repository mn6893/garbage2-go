<?php

namespace App\Services;

/**
 * AI Vision Service for Garbage Analysis
 *
 * This service handles communication with OpenAI GPT-4 Vision API
 * to analyze garbage images and extract waste information.
 */
class AIVisionService
{
    private $apiKey;
    private $apiUrl;
    private $model;
    private $maxTokens;
    private $temperature;

    public function __construct()
    {
        // Constants are already loaded from app/Config/Constants.php
        // No need to manually load config.php
        
        $this->apiKey = OPENAI_API_KEY;
        $this->apiUrl = OPENAI_API_URL;
        $this->model = OPENAI_MODEL;
        $this->maxTokens = OPENAI_MAX_TOKENS;
        $this->temperature = OPENAI_TEMPERATURE;
        
        // Validate required configuration
        if (empty($this->apiKey)) {
            throw new \Exception('OpenAI API key is not configured. Please set OPENAI_API_KEY in your .env file.');
        }
    }

    /**
     * Analyze multiple images with form context for intelligent field suggestions
     *
     * @param array $imagePaths Array of image file paths
     * @param array $formContext Form field definitions and options
     * @return array Analysis results with waste types, volumes, recommendations, and form suggestions
     */
    public function analyzeImagesWithFormContext(array $imagePaths, array $formContext = []): array
    {
        try {
            // Prepare images for API
            $imageData = $this->prepareImages($imagePaths);

            if (empty($imageData)) {
                throw new \Exception('No valid images to analyze');
            }

            // Create the context-aware prompt for waste analysis
            $prompt = $this->createContextAwarePrompt($formContext);

            // Prepare messages for OpenAI API
            $messages = [
                [
                    'role' => 'user',
                    'content' => array_merge(
                        [['type' => 'text', 'text' => $prompt]],
                        $imageData
                    )
                ]
            ];

            // Call OpenAI API
            $response = $this->callOpenAIAPI($messages);

            // Parse and structure the response with form suggestions
            return $this->parseContextAwareResponse($response, $formContext);

        } catch (\Exception $e) {
            error_log('AI Vision Service Error: ' . $e->getMessage());
            return $this->getErrorResponse($e->getMessage());
        }
    }

    /**
     * Analyze multiple images and extract waste information (legacy method)
     *
     * @param array $imagePaths Array of image file paths
     * @return array Analysis results with waste types, volumes, and recommendations
     */
    public function analyzeImages(array $imagePaths): array
    {
        try {
            // Prepare images for API
            $imageData = $this->prepareImages($imagePaths);

            if (empty($imageData)) {
                throw new \Exception('No valid images to analyze');
            }

            // Create the prompt for waste analysis
            $prompt = $this->createAnalysisPrompt();

            // Prepare messages for OpenAI API
            $messages = [
                [
                    'role' => 'user',
                    'content' => array_merge(
                        [['type' => 'text', 'text' => $prompt]],
                        $imageData
                    )
                ]
            ];

            // Call OpenAI API
            $response = $this->callOpenAIAPI($messages);

            // Parse and structure the response
            return $this->parseAnalysisResponse($response);

        } catch (\Exception $e) {
            error_log('AI Vision Service Error: ' . $e->getMessage());
            return $this->getErrorResponse($e->getMessage());
        }
    }

    /**
     * Prepare images for OpenAI API by encoding them to base64
     *
     * @param array $imagePaths
     * @return array
     */
    private function prepareImages(array $imagePaths): array
    {
        $imageData = [];
        $processedCount = 0;

        foreach ($imagePaths as $imagePath) {
            if ($processedCount >= AI_MAX_IMAGES) {
                break;
            }

            if (!file_exists($imagePath)) {
                continue;
            }

            $imageInfo = getimagesize($imagePath);
            if ($imageInfo === false) {
                continue;
            }

            // Check if it's a supported image type
            $mimeType = $imageInfo['mime'];
            if (!in_array($mimeType, ['image/jpeg', 'image/png', 'image/gif', 'image/webp'])) {
                continue;
            }

            // Encode image to base64
            $imageContent = file_get_contents($imagePath);
            if ($imageContent === false) {
                continue;
            }

            $base64Image = base64_encode($imageContent);

            $imageData[] = [
                'type' => 'image_url',
                'image_url' => [
                    'url' => "data:{$mimeType};base64,{$base64Image}",
                    'detail' => 'high'
                ]
            ];

            $processedCount++;
        }

        return $imageData;
    }

    /**
     * Create the analysis prompt for OpenAI
     *
     * @return string
     */
    private function createAnalysisPrompt(): string
    {
        return "You are an expert waste management analyst. Analyze the garbage/waste images provided and return a JSON response with the following structure:

{
    \"analysis\": {
        \"wasteTypes\": [\"type1\", \"type2\", ...],
        \"primaryWasteType\": \"dominant waste type\",
        \"volumeEstimate\": {
            \"category\": \"small|medium|large|extra_large\",
            \"description\": \"descriptive volume estimate\",
            \"bags\": estimated_number_of_bags,
            \"cubicYards\": estimated_cubic_yards
        },
        \"hazardousItems\": [\"item1\", \"item2\", ...],
        \"recyclableItems\": [\"item1\", \"item2\", ...],
        \"confidence\": confidence_score_0_to_100
    },
    \"pricing\": {
        \"estimatedCost\": {
            \"min\": minimum_cost_CAD,
            \"max\": maximum_cost_CAD,
            \"currency\": \"CAD\"
        },
        \"factors\": [\"factor1\", \"factor2\", ...]
    },
    \"recommendations\": [
        \"recommendation1\",
        \"recommendation2\",
        \"recommendation3\"
    ],
    \"disposal\": {
        \"method\": \"preferred disposal method\",
        \"specialRequirements\": [\"requirement1\", \"requirement2\"],
        \"environmentalImpact\": \"low|medium|high\"
    }
}

Important guidelines:
- Focus on Canadian waste management practices and pricing
- Consider provincial regulations and disposal fees (Ontario RPRA, BC MMBC, etc.)
- Identify hazardous materials that require special handling (paint, batteries, electronics)
- Estimate volume based on visible items and spatial relationships
- Provide realistic pricing in Canadian dollars (include HST/GST considerations)
- Include eco-friendly recommendations and nearby recycling options
- Be specific about waste types (e.g., \"construction debris\", \"household mixed waste\", \"organic waste\")
- Reference municipal guidelines for waste sorting and collection
- Consider seasonal restrictions (winter collection challenges, organic waste bans)
- Identify items requiring special municipal programs (bulky item pickup, hazardous waste days)

CANADIAN COMPLIANCE FACTORS:
- Check for items requiring manufacturer take-back programs (electronics, tires, batteries)
- Identify organic waste that must be separated (varies by municipality)
- Flag construction materials requiring C&D permits
- Note items banned from regular collection (propane tanks, paint, chemicals)
- Consider provincial beverage container deposit programs

Analyze ALL images provided and give a comprehensive assessment.";
    }

    /**
     * Create context-aware prompt that includes form field information
     *
     * @param array $formContext
     * @return string
     */
    private function createContextAwarePrompt(array $formContext): string
    {
        $basePrompt = "You are an expert waste management analyst with access to a quotation form system. Analyze the garbage/waste images provided and return a JSON response that includes both standard analysis AND intelligent suggestions for form fields.

FORM CONTEXT:
";

        // Add form field options to the prompt
        if (!empty($formContext['fields'])) {
            foreach ($formContext['fields'] as $fieldName => $options) {
                if (is_array($options)) {
                    $basePrompt .= "- {$fieldName}: [" . implode(', ', $options) . "]\n";
                } else {
                    $basePrompt .= "- {$fieldName}: {$options}\n";
                }
            }
        }

        $basePrompt .= "
Return JSON with this structure:

{
    \"analysis\": {
        \"wasteTypes\": [\"type1\", \"type2\", ...],
        \"primaryWasteType\": \"dominant waste type\",
        \"volumeEstimate\": {
            \"category\": \"small|medium|large|extra_large\",
            \"description\": \"descriptive volume estimate\",
            \"bags\": estimated_number_of_bags,
            \"cubicYards\": estimated_cubic_yards
        },
        \"hazardousItems\": [\"item1\", \"item2\", ...],
        \"recyclableItems\": [\"item1\", \"item2\", ...],
        \"confidence\": confidence_score_0_to_100
    },
    \"formSuggestions\": {
        \"service_type\": {
            \"value\": \"suggested_option_from_available_choices\",
            \"confidence\": confidence_score_0_to_100,
            \"reason\": \"explanation for this suggestion\"
        },
        \"frequency\": {
            \"value\": \"suggested_option_from_available_choices\",
            \"confidence\": confidence_score_0_to_100,
            \"reason\": \"explanation for this suggestion\"
        },
        \"comments\": {
            \"value\": \"AI-generated additional details and requirements based on what you see\",
            \"confidence\": confidence_score_0_to_100,
            \"reason\": \"what specific items or conditions led to these comments\"
        }
    },
    \"pricing\": {
        \"estimatedCost\": {
            \"min\": minimum_cost_CAD,
            \"max\": maximum_cost_CAD,
            \"currency\": \"CAD\"
        },
        \"factors\": [\"factor1\", \"factor2\", ...]
    },
    \"recommendations\": [
        \"recommendation1\",
        \"recommendation2\",
        \"recommendation3\"
    ],
    \"disposal\": {
        \"method\": \"preferred disposal method\",
        \"specialRequirements\": [\"requirement1\", \"requirement2\"],
        \"environmentalImpact\": \"low|medium|high\"
    }
}

IMPORTANT GUIDELINES FOR FORM SUGGESTIONS:
- Only suggest values that exist in the provided form field options
- Base service_type on what you actually see: residential items = residential, construction materials = construction, etc.
- Suggest frequency based on the type and amount of waste (large cleanouts = one_time, regular household = weekly/bi_weekly)
- In comments, mention specific items you see that might need special handling (large furniture, electronics, hazardous materials)
- Provide high confidence (80%+) only when you're very certain based on clear visual evidence
- Include Canadian waste management considerations and pricing
- Be specific about volume estimation using visible reference objects

CANADIAN MARKET CONSIDERATIONS:
- Reference provincial waste regulations (Ontario RPRA, BC MMBC, Quebec Recyc-Quebec)
- Consider municipal collection programs and restrictions
- Identify items requiring special disposal (electronics, batteries, paint, tires)
- Factor in seasonal collection challenges (winter access, organics programs)
- Include nearby recycling depot recommendations when applicable
- Consider HST/GST implications for pricing
- Reference manufacturer take-back programs for electronics and appliances
- Note organic waste separation requirements by municipality

Analyze ALL images provided and give a comprehensive assessment with intelligent form field suggestions.";

        return $basePrompt;
    }

    /**
     * Parse context-aware AI response including form suggestions
     *
     * @param string $response
     * @param array $formContext
     * @return array
     */
    private function parseContextAwareResponse(string $response, array $formContext): array
    {
        try {
            // Clean and parse JSON response
            $cleanedResponse = $this->cleanJsonResponse($response);
            $data = json_decode($cleanedResponse, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('Invalid JSON response from AI: ' . json_last_error_msg());
            }

            // Validate and structure the response
            $result = [
                'success' => true,
                'analysis' => $this->validateAnalysisData($data['analysis'] ?? []),
                'formSuggestions' => $this->validateFormSuggestions($data['formSuggestions'] ?? [], $formContext),
                'pricing' => $this->validatePricingData($data['pricing'] ?? []),
                'recommendations' => $data['recommendations'] ?? [],
                'disposal' => $data['disposal'] ?? [],
                'processingTime' => microtime(true) - ($_SERVER['REQUEST_TIME_FLOAT'] ?? microtime(true)),
                'timestamp' => date('Y-m-d H:i:s')
            ];

            return $result;

        } catch (\Exception $e) {
            error_log('Error parsing context-aware AI response: ' . $e->getMessage());
            return $this->getErrorResponse('Failed to parse AI analysis: ' . $e->getMessage());
        }
    }

    /**
     * Validate and sanitize form suggestions from AI
     *
     * @param array $suggestions
     * @param array $formContext
     * @return array
     */
    private function validateFormSuggestions(array $suggestions, array $formContext): array
    {
        $validatedSuggestions = [];

        foreach ($suggestions as $fieldName => $suggestion) {
            if (!isset($formContext['fields'][$fieldName])) {
                continue; // Skip fields not in our form context
            }

            $fieldOptions = $formContext['fields'][$fieldName];

            // Validate suggestion structure
            if (!is_array($suggestion) || !isset($suggestion['value'])) {
                continue;
            }

            // For dropdown fields, validate the suggested value exists in options
            if (is_array($fieldOptions)) {
                if (!in_array($suggestion['value'], $fieldOptions)) {
                    // If suggested value doesn't exist, skip this suggestion
                    continue;
                }
            }

            // Validate confidence score
            $confidence = isset($suggestion['confidence']) ?
                max(0, min(100, (int)$suggestion['confidence'])) : 50;

            $validatedSuggestions[$fieldName] = [
                'value' => $suggestion['value'],
                'confidence' => $confidence,
                'reason' => $suggestion['reason'] ?? 'AI analysis based on uploaded images',
                'suggested' => true
            ];
        }

        return $validatedSuggestions;
    }

    /**
     * Call OpenAI API with prepared messages
     *
     * @param array $messages
     * @return string
     */
    private function callOpenAIAPI(array $messages): string
    {
        $headers = [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->apiKey
        ];

        $data = [
            'model' => $this->model,
            'messages' => $messages,
            'max_tokens' => $this->maxTokens,
            'temperature' => $this->temperature
        ];

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $this->apiUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_TIMEOUT => AI_PROCESSING_TIMEOUT,
            CURLOPT_SSL_VERIFYPEER => true
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            throw new \Exception("cURL Error: " . $error);
        }

        if ($httpCode !== 200) {
            $errorData = json_decode($response, true);
            $errorMessage = $errorData['error']['message'] ?? 'Unknown API error';
            throw new \Exception("OpenAI API Error (HTTP {$httpCode}): " . $errorMessage);
        }

        return $response;
    }

    /**
     * Parse OpenAI API response and structure the data
     *
     * @param string $response
     * @return array
     */
    private function parseAnalysisResponse(string $response): array
    {
        $data = json_decode($response, true);

        if (!isset($data['choices'][0]['message']['content'])) {
            throw new \Exception('Invalid API response format');
        }

        $content = $data['choices'][0]['message']['content'];

        // Extract JSON from the response (in case there's additional text)
        $jsonStart = strpos($content, '{');
        $jsonEnd = strrpos($content, '}');

        if ($jsonStart === false || $jsonEnd === false) {
            throw new \Exception('No valid JSON found in API response');
        }

        $jsonContent = substr($content, $jsonStart, $jsonEnd - $jsonStart + 1);
        $analysisData = json_decode($jsonContent, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Failed to parse analysis JSON: ' . json_last_error_msg());
        }

        // Validate and structure the response
        return $this->validateAndStructureResponse($analysisData);
    }

    /**
     * Validate and structure the AI response
     *
     * @param array $data
     * @return array
     */
    private function validateAndStructureResponse(array $data): array
    {
        // Set defaults for missing fields
        $structured = [
            'success' => true,
            'wasteType' => $data['analysis']['primaryWasteType'] ?? 'Mixed Waste',
            'wasteTypes' => $data['analysis']['wasteTypes'] ?? [],
            'volumeEstimate' => [
                'category' => $data['analysis']['volumeEstimate']['category'] ?? 'medium',
                'description' => $data['analysis']['volumeEstimate']['description'] ?? 'Medium load (4-6 bags)',
                'bags' => $data['analysis']['volumeEstimate']['bags'] ?? 5,
                'cubicYards' => $data['analysis']['volumeEstimate']['cubicYards'] ?? 2
            ],
            'estimatedCost' => [
                'min' => $data['pricing']['estimatedCost']['min'] ?? 150,
                'max' => $data['pricing']['estimatedCost']['max'] ?? 220,
                'display' => '$' . ($data['pricing']['estimatedCost']['min'] ?? 150) . '-' . ($data['pricing']['estimatedCost']['max'] ?? 220)
            ],
            'confidence' => $data['analysis']['confidence'] ?? 90,
            'recommendations' => $data['recommendations'] ?? [
                'Separate recyclable materials for eco-friendly disposal',
                'Schedule pickup during business hours for better rates',
                'Consider multiple smaller loads for cost efficiency'
            ],
            'hazardousItems' => $data['analysis']['hazardousItems'] ?? [],
            'recyclableItems' => $data['analysis']['recyclableItems'] ?? [],
            'disposalMethod' => $data['disposal']['method'] ?? 'Standard collection',
            'environmentalImpact' => $data['disposal']['environmentalImpact'] ?? 'medium',
            'specialRequirements' => $data['disposal']['specialRequirements'] ?? [],
            'processingTime' => round(microtime(true) * 1000) - (round(microtime(true) * 1000) - 2000), // Simulate processing time
            'timestamp' => date('Y-m-d H:i:s')
        ];

        return $structured;
    }

    /**
     * Get error response structure
     *
     * @param string $message
     * @return array
     */
    private function getErrorResponse(string $message): array
    {
        return [
            'success' => false,
            'error' => $message,
            'wasteType' => 'Unable to analyze',
            'volumeEstimate' => [
                'category' => 'unknown',
                'description' => 'Analysis failed',
                'bags' => 0,
                'cubicYards' => 0
            ],
            'estimatedCost' => [
                'min' => 0,
                'max' => 0,
                'display' => 'Contact for quote'
            ],
            'confidence' => 0,
            'recommendations' => [
                'Please contact us directly for a manual quote',
                'Ensure images are clear and well-lit',
                'Try uploading different angles of the waste'
            ],
            'timestamp' => date('Y-m-d H:i:s')
        ];
    }

    /**
     * Clean JSON response from AI to handle potential formatting issues
     *
     * @param string $response
     * @return string
     */
    private function cleanJsonResponse(string $response): string
    {
        $data = json_decode($response, true);

        if (!isset($data['choices'][0]['message']['content'])) {
            throw new \Exception('Invalid API response format');
        }

        $content = $data['choices'][0]['message']['content'];

        // Extract JSON from the response (in case there's additional text)
        $jsonStart = strpos($content, '{');
        $jsonEnd = strrpos($content, '}');

        if ($jsonStart === false || $jsonEnd === false) {
            throw new \Exception('No valid JSON found in API response');
        }

        return substr($content, $jsonStart, $jsonEnd - $jsonStart + 1);
    }

    /**
     * Validate analysis data from AI response
     *
     * @param array $analysis
     * @return array
     */
    private function validateAnalysisData(array $analysis): array
    {
        return [
            'wasteTypes' => $analysis['wasteTypes'] ?? [],
            'primaryWasteType' => $analysis['primaryWasteType'] ?? 'Mixed Waste',
            'volumeEstimate' => [
                'category' => $analysis['volumeEstimate']['category'] ?? 'medium',
                'description' => $analysis['volumeEstimate']['description'] ?? 'Medium load (4-6 bags)',
                'bags' => $analysis['volumeEstimate']['bags'] ?? 5,
                'cubicYards' => $analysis['volumeEstimate']['cubicYards'] ?? 2
            ],
            'hazardousItems' => $analysis['hazardousItems'] ?? [],
            'recyclableItems' => $analysis['recyclableItems'] ?? [],
            'confidence' => max(0, min(100, (int)($analysis['confidence'] ?? 90)))
        ];
    }

    /**
     * Validate pricing data from AI response
     *
     * @param array $pricing
     * @return array
     */
    private function validatePricingData(array $pricing): array
    {
        $min = max(0, (float)($pricing['estimatedCost']['min'] ?? 150));
        $max = max($min, (float)($pricing['estimatedCost']['max'] ?? 220));

        return [
            'estimatedCost' => [
                'min' => $min,
                'max' => $max,
                'currency' => $pricing['estimatedCost']['currency'] ?? 'CAD',
                'display' => '$' . number_format($min) . '-' . number_format($max) . ' CAD'
            ],
            'factors' => $pricing['factors'] ?? []
        ];
    }

    /**
     * Test API connection and configuration
     *
     * @return bool
     */
    public function testConnection(): bool
    {
        try {
            $testMessages = [
                [
                    'role' => 'user',
                    'content' => 'Hello, this is a connection test. Please respond with "Connection successful".'
                ]
            ];

            $response = $this->callOpenAIAPI($testMessages);
            $data = json_decode($response, true);

            return isset($data['choices'][0]['message']['content']);
        } catch (\Exception $e) {
            error_log('AI Vision Service Connection Test Failed: ' . $e->getMessage());
            return false;
        }
    }
}