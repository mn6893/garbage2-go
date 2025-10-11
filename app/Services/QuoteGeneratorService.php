<?php

namespace App\Services;

/**
 * Quote Generator Service
 *
 * This service generates accurate pricing quotes based on waste assessment,
 * location, and Canadian provincial regulations and pricing structures.
 */
class QuoteGeneratorService
{
    private $basePricing;
    private $provincialRates;
    private $seasonalMultipliers;
    private $volumePricing;
    private $specialHandlingFees;

    public function __construct()
    {
        $this->initializeBasePricing();
        $this->initializeProvincialRates();
        $this->initializeSeasonalMultipliers();
        $this->initializeVolumePricing();
        $this->initializeSpecialHandlingFees();
    }

    /**
     * Generate a comprehensive quote based on waste assessment
     *
     * @param array $assessmentData Enhanced waste assessment data
     * @param array $customerData Customer information (location, service type, etc.)
     * @return array Detailed quote with pricing breakdown
     */
    public function generateQuote(array $assessmentData, array $customerData = []): array
    {
        try {
            // Determine location-based pricing
            $locationData = $this->processLocationData($customerData);

            // Calculate base pricing
            $baseCost = $this->calculateBaseCost($assessmentData, $locationData);

            // Apply volume-based pricing
            $volumeCost = $this->calculateVolumeCost($assessmentData['volumeEstimate']);

            // Calculate special handling fees
            $specialFees = $this->calculateSpecialHandlingFees($assessmentData);

            // Apply provincial and municipal fees
            $governmentFees = $this->calculateGovernmentFees($locationData, $assessmentData);

            // Apply seasonal adjustments
            $seasonalAdjustment = $this->calculateSeasonalAdjustment();

            // Calculate total and build quote
            $quote = $this->buildQuoteStructure(
                $baseCost,
                $volumeCost,
                $specialFees,
                $governmentFees,
                $seasonalAdjustment,
                $assessmentData,
                $locationData
            );

            return $quote;

        } catch (\Exception $e) {
            error_log('Quote Generation Error: ' . $e->getMessage());
            return $this->getErrorQuote($e->getMessage());
        }
    }

    /**
     * Process customer location data for pricing
     *
     * @param array $customerData
     * @return array
     */
    private function processLocationData(array $customerData): array
    {
        $location = $customerData['location'] ?? '';
        $serviceType = $customerData['service_type'] ?? 'residential';

        // Simple province detection based on location
        $province = $this->detectProvince($location);

        // Determine if it's urban or rural (simplified)
        $isUrban = $this->isUrbanArea($location);

        return [
            'province' => $province,
            'isUrban' => $isUrban,
            'serviceType' => $serviceType,
            'originalLocation' => $location,
            'distanceMultiplier' => $this->calculateDistanceMultiplier($location)
        ];
    }

    /**
     * Calculate base cost for waste disposal
     *
     * @param array $assessmentData
     * @param array $locationData
     * @return array
     */
    private function calculateBaseCost(array $assessmentData, array $locationData): array
    {
        $wasteCategory = $assessmentData['wasteCategory'] ?? 'general';
        $baseRate = $this->basePricing[$wasteCategory] ?? $this->basePricing['general'];

        // Apply provincial rate adjustments
        $provincialMultiplier = $this->provincialRates[$locationData['province']]['multiplier'] ?? 1.0;

        // Apply urban/rural adjustments
        $locationMultiplier = $locationData['isUrban'] ? 1.0 : 1.2;

        // Apply service type multiplier
        $serviceMultiplier = $locationData['serviceType'] === 'commercial' ? 1.3 : 1.0;

        $adjustedRate = $baseRate * $provincialMultiplier * $locationMultiplier * $serviceMultiplier;

        return [
            'baseRate' => $baseRate,
            'adjustedRate' => $adjustedRate,
            'multipliers' => [
                'provincial' => $provincialMultiplier,
                'location' => $locationMultiplier,
                'service' => $serviceMultiplier
            ]
        ];
    }

    /**
     * Calculate volume-based pricing
     *
     * @param array $volumeData
     * @return array
     */
    private function calculateVolumeCost(array $volumeData): array
    {
        $category = $volumeData['category'] ?? 'medium';
        $bags = $volumeData['bags'] ?? 5;
        $cubicYards = $volumeData['cubicYards'] ?? 2;

        $volumeRate = $this->volumePricing[$category] ?? $this->volumePricing['medium'];

        // Calculate based on both bags and cubic yards, take higher value
        $bagCost = $bags * $volumeRate['perBag'];
        $cubicYardCost = $cubicYards * $volumeRate['perCubicYard'];

        $cost = max($bagCost, $cubicYardCost);

        return [
            'category' => $category,
            'cost' => $cost,
            'calculation' => [
                'bags' => $bags,
                'bagRate' => $volumeRate['perBag'],
                'bagCost' => $bagCost,
                'cubicYards' => $cubicYards,
                'cubicYardRate' => $volumeRate['perCubicYard'],
                'cubicYardCost' => $cubicYardCost
            ]
        ];
    }

    /**
     * Calculate special handling fees
     *
     * @param array $assessmentData
     * @return array
     */
    private function calculateSpecialHandlingFees(array $assessmentData): array
    {
        $fees = [];
        $totalFees = 0;

        // Check for special requirements
        $specialRequirements = $assessmentData['disposalRequirements']['methods'] ?? [];

        foreach ($specialRequirements as $requirement) {
            if (isset($this->specialHandlingFees[$requirement])) {
                $fee = $this->specialHandlingFees[$requirement];
                $fees[$requirement] = $fee;
                $totalFees += $fee;
            }
        }

        // Add hazardous material fees
        $hazardousItems = $assessmentData['hazardousItems'] ?? [];
        if (!empty($hazardousItems)) {
            $hazardousFee = count($hazardousItems) * $this->specialHandlingFees['hazardous_per_item'];
            $fees['hazardous_materials'] = $hazardousFee;
            $totalFees += $hazardousFee;
        }

        // Add compliance issues fees
        $complianceCheck = $assessmentData['complianceCheck'] ?? [];
        if (!empty($complianceCheck['issues'])) {
            $complianceFee = count($complianceCheck['issues']) * $this->specialHandlingFees['compliance_issue'];
            $fees['compliance_issues'] = $complianceFee;
            $totalFees += $complianceFee;
        }

        return [
            'fees' => $fees,
            'total' => $totalFees,
            'description' => $this->describeSpecialHandling($fees)
        ];
    }

    /**
     * Calculate government fees and taxes
     *
     * @param array $locationData
     * @param array $assessmentData
     * @return array
     */
    private function calculateGovernmentFees(array $locationData, array $assessmentData): array
    {
        $province = $locationData['province'];
        $provincialData = $this->provincialRates[$province] ?? $this->provincialRates['ON'];

        // Environmental fees
        $environmentalFee = $assessmentData['volumeEstimate']['cubicYards'] * $provincialData['environmentalFeePerCubicYard'];

        // Disposal fees
        $disposalFee = $this->calculateDisposalFee($assessmentData, $provincialData);

        // Calculate taxes
        $subtotal = 0; // Will be calculated later in the main quote
        $gst = $provincialData['gst'];
        $pst = $provincialData['pst'];

        return [
            'environmentalFee' => $environmentalFee,
            'disposalFee' => $disposalFee,
            'gst' => $gst,
            'pst' => $pst,
            'province' => $province,
            'description' => "Provincial fees for {$province}"
        ];
    }

    /**
     * Calculate seasonal adjustment
     *
     * @return array
     */
    private function calculateSeasonalAdjustment(): array
    {
        $month = date('n');
        $multiplier = $this->seasonalMultipliers[$month] ?? 1.0;

        $seasons = [
            'spring' => [3, 4, 5],
            'summer' => [6, 7, 8],
            'fall' => [9, 10, 11],
            'winter' => [12, 1, 2]
        ];

        $currentSeason = 'spring';
        foreach ($seasons as $season => $months) {
            if (in_array($month, $months)) {
                $currentSeason = $season;
                break;
            }
        }

        return [
            'season' => $currentSeason,
            'month' => $month,
            'multiplier' => $multiplier,
            'description' => $this->getSeasonalDescription($currentSeason, $multiplier)
        ];
    }

    /**
     * Build the complete quote structure
     *
     * @param array $baseCost
     * @param array $volumeCost
     * @param array $specialFees
     * @param array $governmentFees
     * @param array $seasonalAdjustment
     * @param array $assessmentData
     * @param array $locationData
     * @return array
     */
    private function buildQuoteStructure(
        array $baseCost,
        array $volumeCost,
        array $specialFees,
        array $governmentFees,
        array $seasonalAdjustment,
        array $assessmentData,
        array $locationData
    ): array {
        // Calculate subtotal
        $subtotal = ($baseCost['adjustedRate'] + $volumeCost['cost'] + $specialFees['total'] +
                    $governmentFees['environmentalFee'] + $governmentFees['disposalFee']) *
                   $seasonalAdjustment['multiplier'];

        // Calculate taxes
        $gstAmount = $subtotal * ($governmentFees['gst'] / 100);
        $pstAmount = $subtotal * ($governmentFees['pst'] / 100);
        $totalTaxes = $gstAmount + $pstAmount;

        // Calculate total
        $total = $subtotal + $totalTaxes;

        // Create price range (±15% for negotiation)
        $priceRange = [
            'min' => round($total * 0.85, 2),
            'max' => round($total * 1.15, 2),
            'estimated' => round($total, 2)
        ];

        return [
            'success' => true,
            'quoteId' => $this->generateQuoteId(),
            'estimatedCost' => [
                'min' => $priceRange['min'],
                'max' => $priceRange['max'],
                'display' => '$' . number_format($priceRange['min'], 2) . '-' . number_format($priceRange['max'], 2)
            ],
            'breakdown' => [
                'baseCost' => round($baseCost['adjustedRate'], 2),
                'volumeCost' => round($volumeCost['cost'], 2),
                'specialFees' => round($specialFees['total'], 2),
                'environmentalFee' => round($governmentFees['environmentalFee'], 2),
                'disposalFee' => round($governmentFees['disposalFee'], 2),
                'seasonalAdjustment' => round($subtotal - ($baseCost['adjustedRate'] + $volumeCost['cost'] + $specialFees['total'] + $governmentFees['environmentalFee'] + $governmentFees['disposalFee']), 2),
                'subtotal' => round($subtotal, 2),
                'gst' => round($gstAmount, 2),
                'pst' => round($pstAmount, 2),
                'totalTaxes' => round($totalTaxes, 2),
                'total' => round($total, 2)
            ],
            'details' => [
                'wasteType' => $assessmentData['wasteType'],
                'volume' => $assessmentData['volumeEstimate']['description'],
                'location' => $locationData['province'],
                'serviceType' => $locationData['serviceType'],
                'confidence' => $assessmentData['confidence'],
                'validUntil' => date('Y-m-d', strtotime('+30 days'))
            ],
            'recommendations' => $this->generatePricingRecommendations($assessmentData, $specialFees),
            'terms' => $this->getQuoteTerms(),
            'generatedAt' => date('Y-m-d H:i:s')
        ];
    }

    /**
     * Generate a unique quote ID
     *
     * @return string
     */
    private function generateQuoteId(): string
    {
        return 'GC-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
    }

    /**
     * Generate pricing recommendations
     *
     * @param array $assessmentData
     * @param array $specialFees
     * @return array
     */
    private function generatePricingRecommendations(array $assessmentData, array $specialFees): array
    {
        $recommendations = [];

        // Volume-based recommendations
        if ($assessmentData['volumeEstimate']['category'] === 'extra_large') {
            $recommendations[] = 'Consider multiple smaller pickups to reduce costs';
        }

        // Special handling recommendations
        if ($specialFees['total'] > 50) {
            $recommendations[] = 'Remove hazardous items separately to reduce special handling fees';
        }

        // Seasonal recommendations
        $month = date('n');
        if (in_array($month, [3, 4, 5, 9, 10, 11])) {
            $recommendations[] = 'Book during off-peak months for potential discounts';
        }

        // Compliance recommendations
        if (!empty($assessmentData['complianceCheck']['warnings'])) {
            $recommendations[] = 'Address compliance warnings to avoid additional fees';
        }

        return $recommendations;
    }

    /**
     * Get quote terms and conditions
     *
     * @return array
     */
    private function getQuoteTerms(): array
    {
        return [
            'validFor' => '30 days',
            'paymentTerms' => 'Payment due upon completion of service',
            'cancellationPolicy' => '24-hour notice required for cancellation',
            'priceGuarantee' => 'Prices guaranteed for quoted volume and waste type',
            'additionalFees' => 'Additional fees may apply for extra volume or different waste types'
        ];
    }

    /**
     * Initialize base pricing structure
     */
    private function initializeBasePricing(): void
    {
        $this->basePricing = [
            'general' => 80,
            'construction' => 120,
            'electronic' => 100,
            'hazardous' => 200,
            'organic' => 60,
            'recyclable' => 40
        ];
    }

    /**
     * Initialize provincial rates and regulations
     */
    private function initializeProvincialRates(): void
    {
        $this->provincialRates = [
            'ON' => [
                'multiplier' => 1.0,
                'gst' => 13.0, // HST
                'pst' => 0.0,
                'environmentalFeePerCubicYard' => 15.0,
                'disposalFeePerTonne' => 45.0
            ],
            'BC' => [
                'multiplier' => 1.2,
                'gst' => 5.0,
                'pst' => 7.0,
                'environmentalFeePerCubicYard' => 18.0,
                'disposalFeePerTonne' => 55.0
            ],
            'AB' => [
                'multiplier' => 0.9,
                'gst' => 5.0,
                'pst' => 0.0,
                'environmentalFeePerCubicYard' => 12.0,
                'disposalFeePerTonne' => 35.0
            ],
            'QC' => [
                'multiplier' => 1.1,
                'gst' => 5.0,
                'pst' => 9.975,
                'environmentalFeePerCubicYard' => 20.0,
                'disposalFeePerTonne' => 50.0
            ]
        ];
    }

    /**
     * Initialize seasonal multipliers
     */
    private function initializeSeasonalMultipliers(): void
    {
        $this->seasonalMultipliers = [
            1 => 1.1,  // January - winter premium
            2 => 1.1,  // February
            3 => 1.2,  // March - spring cleaning
            4 => 1.3,  // April - peak spring
            5 => 1.2,  // May
            6 => 1.0,  // June - standard
            7 => 1.0,  // July
            8 => 1.0,  // August
            9 => 1.1,  // September - fall cleanup
            10 => 1.2, // October - peak fall
            11 => 1.1, // November
            12 => 1.1  // December - holiday cleanup
        ];
    }

    /**
     * Initialize volume pricing
     */
    private function initializeVolumePricing(): void
    {
        $this->volumePricing = [
            'small' => ['perBag' => 25, 'perCubicYard' => 45],
            'medium' => ['perBag' => 20, 'perCubicYard' => 40],
            'large' => ['perBag' => 15, 'perCubicYard' => 35],
            'extra_large' => ['perBag' => 12, 'perCubicYard' => 30]
        ];
    }

    /**
     * Initialize special handling fees
     */
    private function initializeSpecialHandlingFees(): void
    {
        $this->specialHandlingFees = [
            'hazardous_disposal' => 75,
            'electronic_recycling' => 25,
            'construction_disposal' => 50,
            'hazardous_per_item' => 15,
            'compliance_issue' => 30,
            'restricted_handling' => 20
        ];
    }

    // Helper methods...

    private function detectProvince(string $location): string
    {
        $location = strtolower($location);

        $provinceKeywords = [
            'ON' => ['ontario', 'toronto', 'ottawa', 'hamilton', 'mississauga'],
            'BC' => ['british columbia', 'vancouver', 'victoria', 'surrey', 'burnaby'],
            'AB' => ['alberta', 'calgary', 'edmonton', 'red deer'],
            'QC' => ['quebec', 'montreal', 'quebec city', 'laval']
        ];

        foreach ($provinceKeywords as $province => $keywords) {
            foreach ($keywords as $keyword) {
                if (strpos($location, $keyword) !== false) {
                    return $province;
                }
            }
        }

        return 'ON'; // Default to Ontario
    }

    private function isUrbanArea(string $location): bool
    {
        $urbanKeywords = ['toronto', 'montreal', 'vancouver', 'calgary', 'edmonton', 'ottawa', 'mississauga', 'winnipeg'];
        $location = strtolower($location);

        foreach ($urbanKeywords as $city) {
            if (strpos($location, $city) !== false) {
                return true;
            }
        }

        return false;
    }

    private function calculateDistanceMultiplier(string $location): float
    {
        // Simplified distance calculation
        return 1.0;
    }

    private function calculateDisposalFee(array $assessmentData, array $provincialData): float
    {
        $estimatedWeight = $assessmentData['volumeEstimate']['cubicYards'] * 0.5; // Rough weight estimate
        return $estimatedWeight * $provincialData['disposalFeePerTonne'];
    }

    private function describeSpecialHandling(array $fees): string
    {
        if (empty($fees)) {
            return 'No special handling required';
        }

        $descriptions = [];
        foreach ($fees as $type => $amount) {
            $descriptions[] = ucwords(str_replace('_', ' ', $type)) . ": $" . number_format($amount, 2);
        }

        return implode(', ', $descriptions);
    }

    private function getSeasonalDescription(string $season, float $multiplier): string
    {
        if ($multiplier > 1.1) {
            return "Peak {$season} season - higher demand";
        } elseif ($multiplier < 0.95) {
            return "Off-peak {$season} season - reduced rates";
        }

        return "Standard {$season} rates";
    }

    private function getErrorQuote(string $message): array
    {
        return [
            'success' => false,
            'error' => $message,
            'estimatedCost' => [
                'min' => 0,
                'max' => 0,
                'display' => 'Quote unavailable'
            ],
            'generatedAt' => date('Y-m-d H:i:s')
        ];
    }
}