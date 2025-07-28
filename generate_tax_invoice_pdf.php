<?php
require 'vendor/autoload.php';
use Dompdf\Dompdf;

ob_start();
include 'print_tax_invoice.php'; // ไฟล์ HTML ที่จะ render
$html = ob_get_clean();

$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("tax_invoice_$order_id.pdf", ["Attachment" => false]); // false = แสดงหน้าเว็บ
?>
<div class="text-end mt-4">
  <p>ลงชื่อผู้มีอำนาจ:</p>
  <img src="images/signature.png" width="120">
  <p>(....................................)</p>
</div>
