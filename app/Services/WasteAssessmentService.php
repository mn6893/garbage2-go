<?php

namespace App\Services;

/**
 * Waste Assessment Service
 *
 * This service processes AI vision results and applies business logic
 * for waste categorization, volume calculation, and disposal requirements.
 */
class WasteAssessmentService
{
    private $wasteCategories;
    private $hazardousItems;
    private $recyclableItems;
    private $volumeMultipliers;

    public function __construct()
    {
        $this->initializeWasteCategories();
        $this->initializeHazardousItems();
        $this->initializeRecyclableItems();
        $this->initializeVolumeMultipliers();
    }

    /**
     * Process AI analysis results and enhance with business logic
     *
     * @param array $aiResults Raw AI analysis results
     * @param array $metadata Additional metadata (location, service type, etc.)
     * @return array Enhanced assessment with business rules applied
     */
    public function processAnalysis(array $aiResults, array $metadata = []): array
    {
        if (!$aiResults['success']) {
            return $aiResults;
        }

        // Enhance waste type classification
        $enhancedWasteType = $this->enhanceWasteTypeClassification($aiResults['wasteType'], $aiResults['wasteTypes']);

        // Refine volume estimation
        $refinedVolume = $this->refineVolumeEstimation($aiResults['volumeEstimate'], $enhancedWasteType);

        // Apply safety and compliance checks
        $complianceCheck = $this->performComplianceCheck($aiResults['hazardousItems'], $enhancedWasteType);

        // Enhance recommendations with local knowledge
        $enhancedRecommendations = $this->enhanceRecommendations(
            $aiResults['recommendations'],
            $enhancedWasteType,
            $aiResults['hazardousItems'],
            $metadata
        );

        // Calculate disposal requirements
        $disposalRequirements = $this->calculateDisposalRequirements($enhancedWasteType, $aiResults['hazardousItems']);

        return array_merge($aiResults, [
            'wasteType' => $enhancedWasteType['primary'],
            'wasteSubtypes' => $enhancedWasteType['subtypes'],
            'wasteCategory' => $enhancedWasteType['category'],
            'volumeEstimate' => $refinedVolume,
            'complianceCheck' => $complianceCheck,
            'recommendations' => $enhancedRecommendations,
            'disposalRequirements' => $disposalRequirements,
            'processedAt' => date('Y-m-d H:i:s'),
            'confidence' => $this->adjustConfidenceScore($aiResults['confidence'], $complianceCheck)
        ]);
    }

    /**
     * Enhance waste type classification with business rules
     *
     * @param string $primaryType
     * @param array $allTypes
     * @return array
     */
    private function enhanceWasteTypeClassification(string $primaryType, array $allTypes): array
    {
        $category = 'general';
        $subtypes = [];

        // Map AI-identified types to business categories
        foreach ($allTypes as $type) {
            $normalizedType = strtolower(trim($type));

            foreach ($this->wasteCategories as $cat => $keywords) {
                foreach ($keywords as $keyword) {
                    if (strpos($normalizedType, $keyword) !== false) {
                        $category = $cat;
                        $subtypes[] = $type;
                        break 2;
                    }
                }
            }
        }

        // Determine primary type from category
        $enhancedPrimary = $this->mapCategoryToPrimaryType($category, $primaryType);

        return [
            'primary' => $enhancedPrimary,
            'category' => $category,
            'subtypes' => array_unique($subtypes),
            'originalPrimary' => $primaryType
        ];
    }

    /**
     * Refine volume estimation with business logic
     *
     * @param array $volumeData
     * @param array $wasteTypeData
     * @return array
     */
    private function refineVolumeEstimation(array $volumeData, array $wasteTypeData): array
    {
        $category = $volumeData['category'];
        $multiplier = $this->volumeMultipliers[$wasteTypeData['category']] ?? 1.0;

        // Adjust estimates based on waste type
        $adjustedBags = round($volumeData['bags'] * $multiplier);
        $adjustedCubicYards = round($volumeData['cubicYards'] * $multiplier, 1);

        // Recalculate category if needed
        if ($adjustedBags <= 2) {
            $category = 'small';
        } elseif ($adjustedBags <= 5) {
            $category = 'medium';
        } elseif ($adjustedBags <= 10) {
            $category = 'large';
        } else {
            $category = 'extra_large';
        }

        return [
            'category' => $category,
            'description' => $this->generateVolumeDescription($category, $adjustedBags),
            'bags' => $adjustedBags,
            'cubicYards' => $adjustedCubicYards,
            'originalEstimate' => $volumeData,
            'adjustmentFactor' => $multiplier
        ];
    }

    /**
     * Perform compliance and safety checks
     *
     * @param array $hazardousItems
     * @param array $wasteTypeData
     * @return array
     */
    private function performComplianceCheck(array $hazardousItems, array $wasteTypeData): array
    {
        $issues = [];
        $warnings = [];
        $specialRequirements = [];

        // Check for hazardous materials
        foreach ($hazardousItems as $item) {
            $normalizedItem = strtolower(trim($item));

            if (in_array($normalizedItem, $this->hazardousItems['prohibited'])) {
                $issues[] = "Prohibited item detected: {$item} - requires special disposal";
                $specialRequirements[] = "hazardous_disposal";
            } elseif (in_array($normalizedItem, $this->hazardousItems['restricted'])) {
                $warnings[] = "Restricted item: {$item} - may require additional fees";
                $specialRequirements[] = "restricted_handling";
            }
        }

        // Category-specific compliance checks
        switch ($wasteTypeData['category']) {
            case 'construction':
                $specialRequirements[] = "construction_permit";
                $warnings[] = "Construction debris may require additional permits";
                break;
            case 'electronic':
                $specialRequirements[] = "electronic_recycling";
                $warnings[] = "Electronic waste requires certified recycling facility";
                break;
            case 'organic':
                $warnings[] = "Organic waste should be composted when possible";
                break;
        }

        return [
            'status' => empty($issues) ? 'compliant' : 'non_compliant',
            'issues' => $issues,
            'warnings' => $warnings,
            'specialRequirements' => array_unique($specialRequirements),
            'canProceed' => empty($issues)
        ];
    }

    /**
     * Enhance recommendations with local knowledge
     *
     * @param array $baseRecommendations
     * @param array $wasteTypeData
     * @param array $hazardousItems
     * @param array $metadata
     * @return array
     */
    private function enhanceRecommendations(array $baseRecommendations, array $wasteTypeData, array $hazardousItems, array $metadata): array
    {
        $enhanced = $baseRecommendations;

        // Add category-specific recommendations
        switch ($wasteTypeData['category']) {
            case 'construction':
                $enhanced[] = "Consider renting a construction dumpster for ongoing projects";
                $enhanced[] = "Separate metal and wood for better recycling rates";
                break;
            case 'electronic':
                $enhanced[] = "Take advantage of manufacturer take-back programs";
                $enhanced[] = "Ensure data is properly wiped from electronic devices";
                break;
            case 'organic':
                $enhanced[] = "Consider composting organic waste to reduce disposal costs";
                $enhanced[] = "Separate food waste from other organic materials";
                break;
            case 'hazardous':
                $enhanced[] = "Contact local hazardous waste facility for proper disposal";
                $enhanced[] = "Never mix different types of hazardous materials";
                break;
        }

        // Add location-specific recommendations
        if (isset($metadata['location'])) {
            $enhanced[] = "Check local municipal recycling programs in your area";
        }

        // Add seasonal recommendations
        $month = date('n');
        if ($month >= 3 && $month <= 5) { // Spring
            $enhanced[] = "Spring cleaning season - consider donating usable items";
        } elseif ($month >= 9 && $month <= 11) { // Fall
            $enhanced[] = "Fall cleanup season - yard waste can often be composted";
        }

        return array_unique($enhanced);
    }

    /**
     * Calculate disposal requirements and methods
     *
     * @param array $wasteTypeData
     * @param array $hazardousItems
     * @return array
     */
    private function calculateDisposalRequirements(array $wasteTypeData, array $hazardousItems): array
    {
        $methods = [];
        $facilities = [];
        $timeline = 'standard'; // standard, urgent, scheduled

        switch ($wasteTypeData['category']) {
            case 'general':
                $methods[] = 'standard_collection';
                $facilities[] = 'municipal_landfill';
                break;
            case 'construction':
                $methods[] = 'construction_disposal';
                $facilities[] = 'construction_facility';
                $timeline = 'scheduled';
                break;
            case 'electronic':
                $methods[] = 'electronic_recycling';
                $facilities[] = 'certified_electronics_recycler';
                break;
            case 'hazardous':
                $methods[] = 'hazardous_disposal';
                $facilities[] = 'hazardous_waste_facility';
                $timeline = 'urgent';
                break;
            case 'organic':
                $methods[] = 'composting';
                $methods[] = 'organic_collection';
                $facilities[] = 'composting_facility';
                break;
        }

        return [
            'methods' => $methods,
            'preferredMethod' => $methods[0] ?? 'standard_collection',
            'facilities' => $facilities,
            'timeline' => $timeline,
            'estimatedProcessingDays' => $this->getProcessingDays($timeline),
            'requiresPermit' => in_array($wasteTypeData['category'], ['construction', 'hazardous'])
        ];
    }

    /**
     * Adjust confidence score based on compliance and business rules
     *
     * @param int $baseConfidence
     * @param array $complianceCheck
     * @return int
     */
    private function adjustConfidenceScore(int $baseConfidence, array $complianceCheck): int
    {
        $adjusted = $baseConfidence;

        // Reduce confidence if there are compliance issues
        if (!$complianceCheck['canProceed']) {
            $adjusted -= 20;
        } elseif (!empty($complianceCheck['warnings'])) {
            $adjusted -= 10;
        }

        // Ensure confidence stays within valid range
        return max(0, min(100, $adjusted));
    }

    /**
     * Initialize waste categories and keywords
     */
    private function initializeWasteCategories(): void
    {
        $this->wasteCategories = [
            'general' => ['household', 'mixed', 'general', 'trash', 'garbage'],
            'construction' => ['construction', 'demolition', 'drywall', 'lumber', 'concrete', 'brick'],
            'electronic' => ['electronic', 'computer', 'television', 'phone', 'appliance'],
            'hazardous' => ['hazardous', 'chemical', 'paint', 'battery', 'oil', 'toxic'],
            'organic' => ['organic', 'food', 'yard', 'green', 'compost', 'biodegradable'],
            'recyclable' => ['recyclable', 'cardboard', 'paper', 'plastic', 'metal', 'glass']
        ];
    }

    /**
     * Initialize hazardous items lists
     */
    private function initializeHazardousItems(): void
    {
        $this->hazardousItems = [
            'prohibited' => [
                'asbestos', 'radioactive', 'medical waste', 'infectious waste',
                'explosive', 'flammable liquid', 'compressed gas'
            ],
            'restricted' => [
                'paint', 'batteries', 'motor oil', 'pesticides', 'fluorescent bulbs',
                'electronics', 'tires', 'mattresses'
            ]
        ];
    }

    /**
     * Initialize recyclable items
     */
    private function initializeRecyclableItems(): void
    {
        $this->recyclableItems = [
            'paper', 'cardboard', 'plastic bottles', 'glass bottles', 'aluminum cans',
            'steel cans', 'electronics', 'batteries', 'clothing', 'furniture'
        ];
    }

    /**
     * Initialize volume multipliers for different waste types
     */
    private function initializeVolumeMultipliers(): void
    {
        $this->volumeMultipliers = [
            'general' => 1.0,
            'construction' => 1.5,
            'electronic' => 0.8,
            'hazardous' => 0.6,
            'organic' => 1.2,
            'recyclable' => 0.9
        ];
    }

    /**
     * Map category to primary type description
     *
     * @param string $category
     * @param string $originalType
     * @return string
     */
    private function mapCategoryToPrimaryType(string $category, string $originalType): string
    {
        $typeMap = [
            'general' => 'Mixed Household Waste',
            'construction' => 'Construction Debris',
            'electronic' => 'Electronic Waste',
            'hazardous' => 'Hazardous Materials',
            'organic' => 'Organic Waste',
            'recyclable' => 'Recyclable Materials'
        ];

        return $typeMap[$category] ?? $originalType;
    }

    /**
     * Generate volume description
     *
     * @param string $category
     * @param int $bags
     * @return string
     */
    private function generateVolumeDescription(string $category, int $bags): string
    {
        $descriptions = [
            'small' => "Small load ({$bags} bags or less)",
            'medium' => "Medium load ({$bags} bags)",
            'large' => "Large load ({$bags} bags)",
            'extra_large' => "Extra large load ({$bags}+ bags)"
        ];

        return $descriptions[$category] ?? "Estimated {$bags} bags";
    }

    /**
     * Get processing days for timeline
     *
     * @param string $timeline
     * @return int
     */
    private function getProcessingDays(string $timeline): int
    {
        $days = [
            'standard' => 3,
            'urgent' => 1,
            'scheduled' => 7
        ];

        return $days[$timeline] ?? 3;
    }
}