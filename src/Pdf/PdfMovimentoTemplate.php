<?php

namespace App\Pdf;

use TCPDF;

class PdfMovimentoTemplate extends TCPDF
{
    private \DateTimeImmutable $printedAt;
    private string $period = '';
    private int $movementCount = 0;

    public function setReportContext(\DateTimeInterface $printedAt, string $period, int $count): void
    {
        $this->tcpdflink = false;
        $this->printedAt = \DateTimeImmutable::createFromInterface($printedAt)->setTimezone(new \DateTimeZone('Europe/Rome'));
        $this->period = $period;
        $this->movementCount = $count;
    }

    public function Header()
    {
        $this->setCellPaddings(0, 0, 0, 0);
        $this->SetTextColor(22, 40, 62);
        $this->SetFont('dejavusans', 'B', 11);
        $this->SetXY(12, 10);
        $this->Cell(50, 6, 'INOUT', 0, 0);
        $this->SetTextColor(100, 116, 139);
        $this->SetFont('dejavusans', '', 8);
        $this->Cell(136, 6, 'Esportato il '.$this->printedAt->format('d/m/Y').' alle '.$this->printedAt->format('H:i'), 0, 1, 'R');
        $this->SetTextColor(22, 40, 62);
        $this->SetFont('dejavusans', 'B', 22);
        $this->SetXY(12, 18);
        $this->Cell(186, 11, 'Movimenti', 0, 1);
        $this->SetTextColor(100, 116, 139);
        $this->SetFont('dejavusans', '', 8);
        $this->SetXY(12, 30);
        $count = $this->movementCount.' '.($this->movementCount === 1 ? 'movimento' : 'movimenti');
        $this->Cell(186, 5, $this->period.'   |   '.$count, 0, 1);
        $this->SetDrawColor(29, 151, 142);
        $this->SetLineWidth(0.6);
        $this->Line(12, 38, 198, 38);
    }

    public function Footer()
    {
        $this->SetDrawColor(222, 229, 237);
        $this->SetLineWidth(0.2);
        $this->Line(12, $this->getPageHeight() - 15, 198, $this->getPageHeight() - 15);
        $this->setCellPaddings(0, 0, 0, 0);
        $this->SetXY(12, -12);
        $this->SetTextColor(100, 116, 139);
        $this->SetFont('dejavusans', '', 7.5);
        $this->Cell(110, 5, 'INOUT  /  Report movimenti', 0, 0);
        $this->Cell(76, 5, 'Pagina '.$this->getAliasNumPage().' di '.$this->getAliasNbPages(), 0, 0, 'R');
    }
}
