<?php

namespace App\Http\Controllers;

use App\Services\PolyclinicService;
use Carbon\Carbon;
use Mpdf\Mpdf;
USE Mpdf\WatermarkImage;


class PDFController extends Controller
{
    public function __construct(protected PolyclinicService $polyclinicService) {}

    public function generateDoctorSchedulePDF(): void
    {

        Carbon::setLocale('id');

        $date = Carbon::now();
        $currentMonth = $date->translatedFormat('F Y');

        $mpdfConfig = array(
            'mode' => 'utf-8',
            'format' => 'A4',
            'default_font_size' => 12,
            'default_font' => '',
            'margin_top' => 30,
            'margin_left' => 5,
            'margin_right' => 5,
            'margin_header' => 5,
            'margin_footer' => 2,
            'orientation' => 'P'
        );

        $mpdf = new Mpdf($mpdfConfig);
        $mpdf->SetHTMLHeader("
            <table>
                <tr>
                    <td>
                        <img src='images/logo-rs-header.png' alt='LOGO RS UMM' class='logo'>
                    </td>
                    <td class='text-center w-70'>
                        <h3 class='title'>JADWAL POLIKLINIK RSU UNIVERSITAS MUHAMMADIYAH MALANG</h3>
                        <h3 class='title'>SELAMA BULAN $currentMonth</h3>
                    </td>
                    <td>
                        <img src='images/larsi.png' alt='LOGO RS UMM' class='logo'>
                    </td>
                </tr>
            </table>
        ");

        $mpdf->SetHTMLFooter("
            <div>
               <img src='images/docs-footer.png' alt='Footer RSU UMM'>
            </div>
        ");
        $mpdf->SetWatermarkImage(
            public_path() . "/images/watermark.png",
            0.25,
            WatermarkImage::POSITION_CENTER_FRAME,
            WatermarkImage::POSITION_CENTER_PAGE,
            true

        );
        $mpdf->showWatermarkImage = true;
        $polyclinics = $this->polyclinicService->getPolyclinicsWithSchedule();

        $mpdf->WriteHTML(view('utils.pdf.doctor-schedule-pdf', [
            'polyclinics' => $polyclinics,
            'currentMonth' => $currentMonth
        ]));
        $mpdf->Output();
    }
}
