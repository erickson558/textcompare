<?php

namespace TextCompare\Domain;

final class TextCompareService
{
    /**
     * @return array{operations: array<int, array{type: string, text: string}>, stats: array{leftLines: int, rightLines: int, equalLines: int, similarity: float}}
     */
    public function compare($left, $right)
    {
        $leftLines = $this->splitLines($left);
        $rightLines = $this->splitLines($right);

        $operations = $this->buildLineDiff($leftLines, $rightLines);

        $equalLines = 0;
        foreach ($operations as $operation) {
            if ($operation['type'] === 'equal') {
                $equalLines++;
            }
        }

        $maxLines = max(count($leftLines), count($rightLines), 1);

        return array(
            'operations' => $operations,
            'stats' => array(
                'leftLines' => count($leftLines),
                'rightLines' => count($rightLines),
                'equalLines' => $equalLines,
                'similarity' => round(($equalLines / $maxLines) * 100, 2),
            ),
        );
    }

    /**
     * @return string[]
     */
    private function splitLines($text)
    {
        $normalized = str_replace(["\r\n", "\r"], "\n", trim($text));

        if ($normalized === '') {
            return array();
        }

        return explode("\n", $normalized);
    }

    /**
     * @param string[] $left
     * @param string[] $right
     *
     * @return array<int, array{type: string, text: string}>
     */
    private function buildLineDiff($left, $right)
    {
        $leftCount = count($left);
        $rightCount = count($right);

        $lcs = array_fill(0, $leftCount + 1, array_fill(0, $rightCount + 1, 0));

        for ($i = 1; $i <= $leftCount; $i++) {
            for ($j = 1; $j <= $rightCount; $j++) {
                if ($left[$i - 1] === $right[$j - 1]) {
                    $lcs[$i][$j] = $lcs[$i - 1][$j - 1] + 1;
                } else {
                    $lcs[$i][$j] = max($lcs[$i - 1][$j], $lcs[$i][$j - 1]);
                }
            }
        }

        $operations = array();
        $i = $leftCount;
        $j = $rightCount;

        while ($i > 0 || $j > 0) {
            if ($i > 0 && $j > 0 && $left[$i - 1] === $right[$j - 1]) {
                $operations[] = array('type' => 'equal', 'text' => $left[$i - 1]);
                $i--;
                $j--;
                continue;
            }

            if ($j > 0 && ($i === 0 || $lcs[$i][$j - 1] >= $lcs[$i - 1][$j])) {
                $operations[] = array('type' => 'add', 'text' => $right[$j - 1]);
                $j--;
                continue;
            }

            $operations[] = array('type' => 'remove', 'text' => $left[$i - 1]);
            $i--;
        }

        return array_reverse($operations);
    }
}
