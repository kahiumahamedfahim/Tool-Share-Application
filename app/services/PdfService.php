<?php
require_once __DIR__ . '/../libs/fpdf.php';

class PdfService extends FPDF
{
    /* =========================
       HEADER (CORE FONT)
       ========================= */
    public function Header(): void
    {
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(0, 10, 'Rental Invoice / Rental Log', 0, 1, 'C');
        $this->Ln(6);
    }

    /* =========================
       BULK RENTAL LOG PDF (ADMIN)
       ========================= */
    public function generateRentalLogPdf(array $logs): void
    {
        // Landscape page
        $this->AddPage('L');

        // Table header font
        $this->SetFont('Arial', 'B', 10);

        // Table header
        $this->Cell(40, 8, 'Log ID', 1);
        $this->Cell(35, 8, 'Tool', 1);
        $this->Cell(30, 8, 'Owner', 1);
        $this->Cell(30, 8, 'Renter', 1);
        $this->Cell(40, 8, 'Rent Period', 1);
        $this->Cell(30, 8, 'Return Date', 1);
        $this->Cell(30, 8, 'Amount', 1);
        $this->Ln();

        // Table body font
        $this->SetFont('Arial', '', 9);

        foreach ($logs as $log) {
            $this->Cell(40, 8, $log['id'], 1);
            $this->Cell(35, 8, $log['tool_name'], 1);
            $this->Cell(30, 8, $log['owner_name'], 1);
            $this->Cell(30, 8, $log['renter_name'], 1);
            $this->Cell(
                40,
                8,
                $log['rent_start_date'] . ' → ' . $log['rent_end_date'],
                1
            );
            $this->Cell(30, 8, $log['return_confirmed_date'], 1);
            $this->Cell(30, 8, '৳ ' . number_format($log['total_amount'], 2), 1);
            $this->Ln();
        }

        $this->Output('D', 'rental_logs.pdf');
        exit;
    }

    /* =========================
       SINGLE RENTAL INVOICE
       (USER / VENDOR / ADMIN)
       ========================= */
    public function generateInvoicePdf(array $log): void
    {
        $this->AddPage();

        $this->SetFont('Arial', 'B', 16);
        $this->Cell(0, 10, 'Rental Invoice', 0, 1, 'C');
        $this->Ln(8);

        $this->SetFont('Arial', '', 11);

        $this->Cell(50, 8, 'Invoice ID:', 0);
        $this->Cell(0, 8, $log['id'], 0, 1);

        $this->Cell(50, 8, 'Tool:', 0);
        $this->Cell(0, 8, $log['tool_name'], 0, 1);

        $this->Cell(50, 8, 'Owner:', 0);
        $this->Cell(0, 8, $log['owner_name'], 0, 1);

        $this->Cell(50, 8, 'Renter:', 0);
        $this->Cell(0, 8, $log['renter_name'], 0, 1);

        $this->Cell(50, 8, 'Rent Period:', 0);
        $this->Cell(
            0,
            8,
            $log['rent_start_date'] . ' → ' . $log['rent_end_date'],
            0,
            1
        );

        $this->Cell(50, 8, 'Return Date:', 0);
        $this->Cell(0, 8, $log['return_confirmed_date'], 0, 1);

        $this->Ln(6);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(50, 10, 'Total Amount:', 0);
        $this->Cell(
            0,
            10,
            '৳ ' . number_format($log['total_amount'], 2),
            0,
            1
        );

        $this->Ln(12);
        $this->SetFont('Arial', 'I', 9);
        $this->Cell(
            0,
            8,
            'This is a system generated invoice.',
            0,
            1,
            'C'
        );

        $this->Output('D', 'invoice_' . $log['id'] . '.pdf');
        exit;
    }
}
