<?php

declare(strict_types=1);

namespace App\Src;

use FPDF;

/**
 * BingoPDF class extends FPDF to generate bingo cards.
 */
class BingoPDF extends FPDF
{
    private array $ranges;
    private array $usedNumberSetHashes = [];

    public function __construct()
    {
        parent::__construct();
        $this->ranges = [
            'B' => range(1, 15),
            'I' => range(16, 30),
            'N' => range(31, 45),
            'G' => range(46, 60),
            'O' => range(61, 75),
        ];
    }

    /**
     * Generates a unique bingo card with random numbers.
     *
     * @param array $usedNumbers Numbers already used on the page
     * @param bool $hasFreeSpace Whether to include a free space in the N column
     * @param int $maxAttempts Maximum attempts to generate a unique card
     * @return array The generated card
     * @throws \RuntimeException If a unique card cannot be generated
     */
    private function generateBingoCard(array &$usedNumbers, bool $hasFreeSpace = true, int $maxAttempts = 10): array
    {
        $attempt = 0;
        while ($attempt < $maxAttempts) {
            $card = [];
            $cardNumbers = [];

            foreach (['B', 'I', 'N', 'G', 'O'] as $letter) {
                // Get available numbers (excluding those used on the page)
                $available = array_diff($this->ranges[$letter], $usedNumbers);
                if (count($available) < ($letter === 'N' && $hasFreeSpace ? 4 : 5)) {
                    throw new \RuntimeException("Not enough available numbers for column $letter");
                }

                // Randomly select 5 numbers (or 4 for N with free space)
                $needed = ($letter === 'N' && $hasFreeSpace) ? 4 : 5;
                $selected = [];
                $available = array_values($available); // Reset indices
                for ($i = 0; $i < $needed; $i++) {
                    $index = random_int(0, count($available) - 1);
                    $selected[] = $available[$index];
                    array_splice($available, $index, 1); // Remove used number
                }
                $card[$letter] = $selected;
                $cardNumbers = array_merge($cardNumbers, $selected);
            }

            // Add free space for N column
            if ($hasFreeSpace && isset($card['N'])) {
                array_splice($card['N'], 2, 0, '*');
            }

            // Compute hash of card numbers (sorted to normalize)
            sort($cardNumbers);
            $hash = hash('sha256', json_encode($cardNumbers));

            // Check if this card is unique in the session
            if (!in_array($hash, $this->usedNumberSetHashes)) {
                $this->usedNumberSetHashes[] = $hash;
                // Update used numbers
                foreach ($card as $letter => $numbers) {
                    foreach ($numbers as $num) {
                        if ($num !== '*') {
                            $usedNumbers[] = $num;
                        }
                    }
                }
                return $card;
            }

            $attempt++;
        }

        throw new \RuntimeException('Could not generate a unique bingo card after maximum attempts');
    }

    /**
     * Draws a bingo card at the specified position.
     *
     * @param float $x X-coordinate (mm)
     * @param float $y Y-coordinate (mm)
     * @param string $title Card title
     * @param array $usedNumbers Numbers already used on the page
     * @param string $bgColor Background color (hex)
     * @param string $textColor Text color (hex)
     * @param string $uniqueCode Unique code for the card
     * @return void
     */
    public function drawBingoCard(
        float $x,
        float $y,
        string $title,
        array &$usedNumbers,
        string $bgColor,
        string $textColor,
        string $uniqueCode
    ): void {
        try {
            $card = $this->generateBingoCard($usedNumbers);
        } catch (\RuntimeException $e) {
            // Fallback to a basic card if uniqueness fails (log this in production)
            $card = [];
            foreach (['B', 'I', 'N', 'G', 'O'] as $letter) {
                $card[$letter] = array_slice($this->ranges[$letter], 0, $letter === 'N' ? 4 : 5);
                if ($letter === 'N') {
                    array_splice($card['N'], 2, 0, '*');
                }
            }
        }

        // Convert hex colors to RGB
        $bgR = hexdec(substr($bgColor, 1, 2));
        $bgG = hexdec(substr($bgColor, 3, 2));
        $bgB = hexdec(substr($bgColor, 5, 2));
        $textR = hexdec(substr($textColor, 1, 2));
        $textG = hexdec(substr($textColor, 3, 2));
        $textB = hexdec(substr($textColor, 5, 2));

        // Draw the title with a border
        $this->SetFont('Arial', 'B', 12);
        $this->SetTextColor($textR, $textG, $textB);
        $this->SetXY($x, $y);
        $this->Cell(95, 10, $title, 1, 1, 'C');

        // Draw the unique code below the title
        $this->SetFont('Arial', '', 6);
        $this->SetXY($x, $y + 10);
        $this->Cell(95, 3, 'Code: ' . $uniqueCode, 0, 1, 'C');

        // Set line thickness to 0.4mm
        $this->SetLineWidth(0.4);

        // Draw the card grid
        $this->SetFont('Arial', 'B', 30); // BINGO letters at 30pt, bold
        $this->SetTextColor($textR, $textG, $textB);
        $this->SetFillColor($bgR, $bgG, $bgB);
        $this->SetXY($x, $y + 13);
        $this->Cell(19, 10, 'B', 1, 0, 'C', 1);
        $this->Cell(19, 10, 'I', 1, 0, 'C', 1);
        $this->Cell(19, 10, 'N', 1, 0, 'C', 1);
        $this->Cell(19, 10, 'G', 1, 0, 'C', 1);
        $this->Cell(19, 10, 'O', 1, 0, 'C', 1);

        $this->SetFont('Arial', 'B', 40); // Numbers at 40pt, bold
        for ($i = 0; $i < 5; $i++) {
            $this->SetXY($x, $y + 23 + ($i * 19));
            foreach (['B', 'I', 'N', 'G', 'O'] as $letter) {
                $this->Cell(19, 19, $card[$letter][$i], 1, 0, 'C', 1);
            }
        }

        // Reset colors and line width
        $this->SetTextColor(0, 0, 0);
        $this->SetFillColor(255, 255, 255);
        $this->SetLineWidth(0.2);
    }

    /**
     * Generates a preview PDF with one page of cards.
     *
     * @param string $title Card title
     * @param string $bgColor Background color (hex)
     * @param string $textColor Text color (hex)
     * @param string $uniqueCode Unique code for the cards
     * @return string The PDF content as a string
     */
    public function generatePreview(
        string $title,
        string $bgColor,
        string $textColor,
        string $uniqueCode
    ): string {
        $this->SetMargins(5, 5, 5);
        $this->AddPage('P', 'A4');
        $usedNumbers = [];

        $positions = [
            [7.5, 18.5], // Top-left
            [107.5, 18.5], // Top-right
            [7.5, 153.5], // Bottom-left
            [107.5, 153.5], // Bottom-right
        ];

        for ($i = 0; $i < 4; $i++) {
            $this->drawBingoCard(
                $positions[$i][0],
                $positions[$i][1],
                $title,
                $usedNumbers,
                $bgColor,
                $textColor,
                $uniqueCode
            );
        }

        return $this->Output('S');
    }
}
