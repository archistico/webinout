<?php

namespace App\Pdf;

use App\Entity\Movimento;
use App\Twig\AppExtension;
use NumberFormatter;

class PdfMovimentoService
{
    public function __construct(private readonly AppExtension $categoryIcons = new AppExtension())
    {
    }

    /** @param Movimento[] $movimenti */
    public function creaPdf(array $movimenti, \DateTimeInterface $data): PdfMovimentoTemplate
    {
        $printedAt = \DateTimeImmutable::createFromInterface($data)->setTimezone(new \DateTimeZone('Europe/Rome'));
        $formatter = new NumberFormatter('it_IT', NumberFormatter::CURRENCY);
        $totals = ['Entrata' => 0, 'Uscita' => 0];
        $dates = [];
        foreach ($movimenti as $movement) {
            $dates[] = $movement->getData()->format('Y-m-d');
            $type = $movement->getCategoria()->getPadre()->getPadre()->getNome();
            if (array_key_exists($type, $totals)) {
                $totals[$type] += (int) round(($movement->getImporto() ?? 0) * 100);
            }
        }
        sort($dates);
        $period = $dates
            ? 'Dal '.(new \DateTimeImmutable($dates[0]))->format('d/m/Y').' al '.(new \DateTimeImmutable($dates[count($dates) - 1]))->format('d/m/Y')
            : 'Nessun movimento registrato';

        $pdf = new PdfMovimentoTemplate('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->setReportContext($printedAt, $period, count($movimenti));
        $pdf->SetCreator('INOUT');
        $pdf->SetAuthor('INOUT');
        $pdf->SetTitle('Movimenti - '.$printedAt->format('d/m/Y'));
        $pdf->SetSubject('Riepilogo e dettaglio dei movimenti');
        $pdf->SetMargins(12, 44, 12);
        $pdf->SetHeaderMargin(10);
        $pdf->SetFooterMargin(12);
        $pdf->SetAutoPageBreak(true, 19);
        $pdf->setFontSubsetting(true);
        $pdf->SetFont('dejavusans', '', 9);
        $pdf->setCellHeightRatio(1.3);
        $pdf->AddPage();

        $this->drawSummary($pdf, $formatter, $totals);
        $pdf->SetY(76);
        $pdf->SetFont('dejavusans', '', 9);
        $pdf->SetTextColor(22, 40, 62);
        $pdf->setCellPaddings(0, 0, 0, 0);
        $html = '<table width="100%" cellpadding="5" cellspacing="0" border="0">'
            .'<thead><tr style="background-color:#16283e;color:#ffffff;font-size:8pt;font-weight:bold;">'
            .'<th width="12%">DATA</th><th width="51%">CATEGORIA E NOTE</th><th width="20%">PAGAMENTO</th><th width="17%" align="right">IMPORTO</th>'
            .'</tr></thead><tbody>';
        foreach ($movimenti as $index => $movement) {
            $micro = $movement->getCategoria();
            $meso = $micro->getPadre();
            $type = $meso->getPadre()->getNome();
            $note = trim($movement->getNote() ?? '');
            $payment = $movement->getTipo()?->getDescrizione() ?? 'Non indicato';
            $amount = $movement->getImporto();
            $cents = (int) round(($amount ?? 0) * 100);
            $signedAmount = ($type === 'Uscita' ? -$cents : $cents) / 100;
            $formattedAmount = $amount === null ? 'Non indicato' : (($type === 'Entrata' && $signedAmount > 0 ? '+' : '').$formatter->formatCurrency($signedAmount, 'EUR'));
            $color = match ($type) { 'Entrata' => '#087f5b', 'Uscita' => '#b42335', default => '#16283e' };
            $icon = $this->categoryIcon($type, $meso->getNome(), $micro->getNome());
            $background = $index % 2 === 0 ? '#ffffff' : '#f5f8fb';
            $details = $type.' / '.$meso->getNome()."\n".$micro->getNome().($note !== '' ? "\n".$note : '');
            // Ordinary rows stay together. Oversized notes can continue across pages.
            $height = max(14, $pdf->getStringHeight(90, $details), $pdf->getStringHeight(32, $payment));
            $keepTogether = $height < 210 ? ' nobr="true"' : '';
            $cellStyle = 'border-bottom:0.5px solid #dee5ed;';
            $html .= '<tr'.$keepTogether.' style="background-color:'.$background.';">'
                .'<td width="12%" valign="top" align="center" style="'.$cellStyle.'font-size:8pt;">'.$movement->getData()->format('d/m/Y')
                .'<br /><img src="@'.base64_encode($icon).'" width="24" height="24" /></td>'
                .'<td width="51%" valign="top" style="'.$cellStyle.'">'
                .'<span style="font-size:7.5pt;color:#64748b;">'.self::escape($type.' / '.$meso->getNome()).'</span><br />'
                .'<b>'.self::escape($micro->getNome()).'</b>'
                .($note !== '' ? '<br /><span style="font-size:8pt;color:#475569;">'.nl2br(self::escape($note)).'</span>' : '')
                .'</td>'
                .'<td width="20%" valign="top" style="'.$cellStyle.'font-size:8pt;color:#475569;">'.self::escape($payment).'</td>'
                .'<td width="17%" valign="top" align="right" style="'.$cellStyle.'font-size:9pt;color:'.$color.';"><b>'.self::escape($formattedAmount).'</b></td>'
                .'</tr>';
        }
        if (!$movimenti) {
            $html .= '<tr><td colspan="4" align="center" style="color:#64748b;">Nessun movimento da esportare.</td></tr>';
        }
        $html .= '</tbody></table>';
        $pdf->writeHTML($html, true, false, true, false, '');
        $pdf->lastPage();

        return $pdf;
    }

    private static function escape(?string $text): string
    {
        return htmlspecialchars($text ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }

    private function categoryIcon(string $macro, string $meso, string $micro): string
    {
        $svg = str_replace('currentColor', '#64748b', $this->categoryIcons->icona($macro, $meso, $micro));
        $document = new \DOMDocument();
        $document->loadXML($svg, LIBXML_NONET);
        // TCPDF needs explicit shape styles instead of inherited SVG root styles.
        foreach ($document->getElementsByTagName('*') as $element) {
            if (!in_array($element->localName, ['path', 'circle', 'rect', 'line', 'polyline', 'polygon', 'ellipse'], true)) {
                continue;
            }
            foreach (['fill' => 'none', 'stroke' => '#64748b', 'stroke-width' => '2', 'stroke-linecap' => 'round', 'stroke-linejoin' => 'round'] as $attribute => $value) {
                if (!$element->hasAttribute($attribute)) {
                    $element->setAttribute($attribute, $value);
                }
            }
        }

        return $document->saveXML($document->documentElement);
    }

    private function drawSummary(PdfMovimentoTemplate $pdf, NumberFormatter $formatter, array $totals): void
    {
        $cards = [
            ['ENTRATE', $totals['Entrata'], [8, 127, 91], [239, 249, 245]],
            ['USCITE', -$totals['Uscita'], [180, 35, 53], [253, 243, 244]],
            ['SALDO', $totals['Entrata'] - $totals['Uscita'], [22, 40, 62], [240, 245, 250]],
        ];
        foreach ($cards as $index => [$label, $cents, $color, $background]) {
            $x = 12 + $index * 63;
            $pdf->SetFillColor(...$background);
            $pdf->RoundedRect($x, 45, 60, 22, 2, '1111', 'F');
            $pdf->SetTextColor(100, 116, 139);
            $pdf->SetFont('dejavusans', 'B', 7.5);
            $pdf->SetXY($x + 4, 48);
            $pdf->Cell(52, 4, $label, 0, 1);
            $pdf->SetTextColor(...$color);
            $value = $formatter->formatCurrency($cents / 100, 'EUR');
            $pdf->SetFont('dejavusans', 'B', 13);
            if ($pdf->GetStringWidth($value) > 52) {
                $pdf->SetFont('dejavusans', 'B', 9);
            }
            $pdf->SetXY($x + 4, 55);
            $pdf->Cell(52, 7, $value, 0, 1);
        }
        $pdf->SetFont('dejavusans', '', 7);
        $pdf->SetTextColor(100, 116, 139);
        $pdf->SetXY(12, 69);
        $pdf->Cell(186, 4, 'Saldo = entrate meno uscite dei movimenti elencati.', 0, 1);
    }
}
