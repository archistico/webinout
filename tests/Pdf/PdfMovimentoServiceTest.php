<?php

namespace App\Tests\Pdf;

use App\Entity\{Macro, Meso, Micro, Movimento, TipoPagamento};
use App\Pdf\PdfMovimentoService;
use PHPUnit\Framework\TestCase;

class PdfMovimentoServiceTest extends TestCase
{
    private function movement(?string $note): Movimento
    {
        $macro = (new Macro())->setNome('Uscita');
        $meso = (new Meso())->setNome('Casa')->setPadre($macro);
        $micro = (new Micro())->setNome('Utenze')->setPadre($meso);
        return (new Movimento())
            ->setData(new \DateTimeImmutable('2026-11-09'))
            ->setCategoria($micro)
            ->setTipo((new TipoPagamento())->setDescrizione('Carta di credito'))
            ->setImporto(12.34)
            ->setNote($note);
    }

    public function testEmptyReportProducesOneValidA4Page(): void
    {
        $date = new \DateTimeImmutable('2026-11-09 23:30:00 UTC');
        $pdf = (new PdfMovimentoService())->creaPdf([], $date);
        self::assertSame(1, $pdf->getNumPages());
        self::assertEqualsWithDelta(210, $pdf->getPageWidth(), 0.01);
        self::assertEqualsWithDelta(297, $pdf->getPageHeight(), 0.01);
        $output = $pdf->Output('empty.pdf', 'S');
        self::assertStringStartsWith('%PDF-', $output);
        self::assertStringContainsString('%%EOF', $output);
        self::assertSame('2026-11-09 23:30:00 +00:00', $date->format('Y-m-d H:i:s P'));
    }

    public function testOrdinaryRowRemainsOnOnePageWithNullValues(): void
    {
        $movement = $this->movement(null)->setTipo(null)->setImporto(null);
        $pdf = (new PdfMovimentoService())->creaPdf([$movement], new \DateTimeImmutable('2026-11-09'));
        self::assertSame(1, $pdf->getNumPages());
        self::assertStringStartsWith('%PDF-', $pdf->Output('single.pdf', 'S'));
    }

    public function testOversizedNoteContinuesWithoutPaginationLoop(): void
    {
        $pdf = (new PdfMovimentoService())->creaPdf([
            $this->movement(str_repeat('Nota completa: città, caffè e più informazioni. ', 500)),
            $this->movement('Movimento successivo alla nota lunga.'),
        ], new \DateTimeImmutable('2026-11-09'));
        self::assertGreaterThan(1, $pdf->getNumPages());
        self::assertLessThan(20, $pdf->getNumPages());
        self::assertStringStartsWith('%PDF-', $pdf->Output('long.pdf', 'S'));
    }
}
