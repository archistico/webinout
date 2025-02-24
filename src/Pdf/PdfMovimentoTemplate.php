<?php

namespace App\Pdf;

use TCPDF;

class PdfMovimentoTemplate extends TCPDF {
    
        protected $pagina_margine_top_header;
        protected $pagina_margine_bottom_footer;
        protected $data;
    
        public function SetCustomHeader($pagina_margine_top_header, $pagina_margine_bottom_footer, $data)
        {
            $this->pagina_margine_top_header = $pagina_margine_top_header;
            $this->pagina_margine_bottom_footer = $pagina_margine_bottom_footer;
            $this->data = $data;
        }
    
        public function Header() {
            $this->setCellPaddings(1, 1, 1, 1);
            $this->setCellMargins(0, 0, 0, 0);
            $this->SetY($this->pagina_margine_top_header);
            $this->SetFont('helvetica', '', 10);
            $this->MultiCell(50, 6.5, 'INOUT', 1, 'L', 0, 0, '', '', true);
            $this->MultiCell(90, 6.5, 'STAMPA MOVIMENTI ('.$this->data->format('d/m/Y').')', 1, 'C', 0, 0, '', '', true);
            $this->MultiCell(50, 6.5, 'Modello 01', 1, 'R', 0, 1, '', '', true);
    
            $this->SetFont('helvetica', 'B', 14);
            $this->MultiCell(190, 8, 'MOVIMENTI', 1, 'C', 0, 0, '', '', true);
        }
    
        public function Footer() {
            // Position at - from bottom
            $this->SetY(-$this->pagina_margine_bottom_footer);
            $this->SetFont('helvetica', '', 8);
            $this->Cell(0, 5, 'PAGINA '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
        }
    }