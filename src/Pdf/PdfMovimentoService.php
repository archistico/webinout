<?php

namespace App\Pdf;

use App\Entity\Movimento;
use NumberFormatter;

class PdfMovimentoService
{
    public function creaPdf(array $movimenti, \DateTime $data)
    {
        $pagina_margine_top_header = 10;
        $pagina_margine_top = 27;
        $pagina_margine_left = 10;
        $pagina_margine_right = 10;
        $pagina_margine_bottom = 12;

        $altezza_pagina = 297;
        $larghezza_pagina = 210-$pagina_margine_left-$pagina_margine_right;

        $fmt = new NumberFormatter( 'it_IT', NumberFormatter::CURRENCY );

        // create new PDF document
        $pdf = new PdfMovimentoTemplate(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    
        $pdf->SetCustomHeader($pagina_margine_top_header, $pagina_margine_bottom, $data);

        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('INOUT');
        $pdf->SetTitle('Stampa movimenti eseguito il '. $data->format('d/m/Y'));
        $pdf->SetSubject('INOUT');

        // set margins
        $pdf->SetMargins($pagina_margine_top, $pagina_margine_left, $pagina_margine_right);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin($pagina_margine_bottom);

        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, 13);

        // ---------------------------------------------------------

        $pdf->SetFont('helvetica', '', 10);

        $pdf->SetMargins($pagina_margine_left, $pagina_margine_top, $pagina_margine_right, true);
        $pdf->setCellPaddings(2, 1, 1, 1);
        $pdf->setCellMargins(0, 0, 0, 0);

        $pdf->AddPage();

        $font_altezza_riga1 = 10;
        $font_altezza_riga2 = 8;

        $altezza_riga_1 = 6;
        $altezza_riga_2 = 5;
        
        $larghezza_quando = 23;
        $larghezza_importo = 27;
        $larghezza_categoria = $larghezza_pagina - $larghezza_quando - $larghezza_importo;

        $larghezza_vuoto = $larghezza_quando;
        $larghezza_tipo = $larghezza_importo;
        $larghezza_note = $larghezza_pagina - $larghezza_vuoto - $larghezza_tipo;

        foreach($movimenti as /** @var Movimento */ $t) {
            
            if( ($altezza_pagina - $pdf->getY() - $pagina_margine_bottom) < ($altezza_riga_1 + $altezza_riga_2 + 2)) {
                $pdf->AddPage();
            }

            $quando = $t->getData()->format('d/m/Y');
            $categoria_micro = $t->getCategoria()->getNome();
            $categoria_meso = $t->getCategoria()->getPadre()->getNome();
            $categoria_macro = $t->getCategoria()->getPadre()->getPadre()->getNome();
            $importo = $fmt->formatCurrency($t->getImporto(), "EUR");
            $nota = substr(str_replace("\n", ' ',$t->getNote()), 0, 40);
            $tipo = $t->getTipo()->getDescrizione();

            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetFillColor(220, 220, 220);
            $pdf->SetFont('helvetica', 'B', $font_altezza_riga1);
            $pdf->MultiCell($larghezza_quando, $altezza_riga_1, $quando, 1, 'L', 1, 0, '', '', true, 0, false, true, $altezza_riga_1, 'M');
            $pdf->MultiCell($larghezza_categoria, $altezza_riga_1, $categoria_macro .' | '. $categoria_meso .' | '. $categoria_micro, 1, 'L', 1, 0, '', '', true, 0, false, true, $altezza_riga_1, 'M');
            $pdf->MultiCell($larghezza_importo, $altezza_riga_1, $importo, 1, 'R', 1, 1, '', '', true, 0, false, true, $altezza_riga_1, 'M');

            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetFillColor(255, 255, 255);
            $pdf->SetFont('helvetica', '', $font_altezza_riga2);
            $pdf->MultiCell($larghezza_vuoto, $altezza_riga_2, '', 1, 'L', 1, 0, '', '', true, 0, false, true, $altezza_riga_2, 'M');
            $pdf->MultiCell($larghezza_note, $altezza_riga_2, $nota, 1, 'L', 1, 0, '', '', true, 0, false, true, $altezza_riga_2, 'M');
            $pdf->MultiCell($larghezza_tipo, $altezza_riga_2, $tipo, 1, 'R', 1, 1, '', '', true, 0, false, true, $altezza_riga_2, 'M');

            $pdf->ln(2);
        }        

        // ----------------------------
        $pdf->lastPage();
        return $pdf;
    }
}