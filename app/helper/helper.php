<?php

use App\Models\Addon;
use App\Models\Appsetting;
use App\Models\Contact;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Mpdf\Mpdf;

function nf($val)
{
    return number_format((float)($val), 2, '.', ' ');
}


function sendToCRM($data)
{

    $client = new Client();
    $data['company_id'] = 1;

    try {
        $res = $client->request('POST', 'https://work.zeghen.com/lead-form/leadStore', [
            'form_params' => $data,
            'headers' => array('accept' => 'application/json')
        ]);
        $code =  $res->getStatusCode();
        $body = json_decode($res->getBody());
        $success = true;
    } catch (ClientException $e) {
        $req = $e->getResponse();
        $body = json_decode($req->getBody()->getContents());
        $code = $req->getStatusCode();
    }
    $success = $code == 200;
    // dd($code, $body);
    return $success;
}

function token()
{
    $i = 0;
    $t = '';
    while ($i < 20) {
        $t .= rand(1, 9);
        $i++;
    }

    $e = Contact::where('token', $t)->first();
    if ($e) {
        return token();
    }
    return $t;
}


function base64_to_image($base64_string, $output_file)
{
    $data = explode(',', $base64_string);
    $exe = explode('/', $data[0]);
    $exe = "." . explode(';', $exe[1])[0];
    $output_file .= '.jpeg';
    $ifp = fopen($output_file, 'wb');
    fwrite($ifp, base64_decode($data[1]));
    fclose($ifp);
    return $output_file;
}

function makedir($dir)
{
    $dir = "tmp/$dir";
    if (!file_exists($dir)) {
        mkdir($dir, 0777, true);
    }
    return $dir;
}

function buildPDF(array $data): Mpdf
{
    $html = '<h1 class="text-center mb-3"><b>Power Calculator Result</b></h1>';
    $totwattspeak = $totwattsday = $totwattsmonth = 0;
    $set = Appsetting::first();
    $cost = (float) @$set->cost;
    $peakload = (float) @$set->peakload;

    $image = (array) $data['image'];
    $appliance = (array) $data['appliance'];
    $qty = (array) $data['qty'];
    $watts = (array) $data['watts'];
    $onperday = (array) $data['onperday'];

    $html .= '
            <table class="table table-striped table-hover mt-3" style="color:black;">
                <thead>
                    <tr>
                        <th colspan="6">
                            <b>Items</b>
                        </th>
                    </tr>
                </thead>
                <tr>
                    <th></th>
                    <th>Appliance</th>
                    <th class="text-center">Qty</th>
                    <th class="text-center">Watts</th>
                    <th class="text-center">Hours On per day</th>
                    <th class="text-end">Watts hours per day</th>
                </tr>';

    foreach ($appliance as $k => $v) :
        $_qty = (int) @$qty[$k];
        $_watts = (float) @$watts[$k];
        $_onperday = (float) @$onperday[$k];
        $_totwatts = $_qty * $_watts * $_onperday;
        $totwattsday += $_totwatts;

        $html .= "<tr>
                    <td> " . ($k + 1) . "</td>
                    <td>$v</td>
                    <td class='text-center'> $_qty</td>
                    <td class='text-center'> " . nf($_watts) . " " . ($_watts > 1 ? 'Watts' : 'Watt') . "
                    </td>
                    <td class='text-center'> $_onperday</td>
                    <td class='text-end'> " . nf($_totwatts) . "  " . ($_totwatts > 1 ? 'Watts' : 'Watt') . "
                    </td>
                </tr>";
    endforeach;

    $totwattsmonth = ($totwattsday / 1000) * 30;
    $costmonth = $totwattsmonth * $cost;
    $totwattspeak += $totwattsday * $peakload;

    $html .= "
            </table>
            <table style='color:black;' class='table table-striped table-hover mt-3'>
                <thead>
                    <tr>
                        <th colspan='6'>
                            <b>Power consumption</b>
                        </th>
                    </tr>
                </thead>
                <tr>
                    <td colspan='3'>Total Watts - (Peak Load)</td>
                    <td colspan='3' class='text-end'> " . nf($totwattspeak) . "
                            " . ($totwattspeak > 1 ? 'Watts' : 'Watt') . "
                    </td>
                </tr>
                <tr>
                    <td colspan='3'>Total Watt Hours per Day </td>
                    <td colspan='3' class='text-end'> " . nf($totwattsday) . "
                            " . ($totwattsday > 1 ? 'Watts' : 'Watt') . "
                    </td>
                </tr>
                <tr>
                    <td colspan='3'>Kilowatt Hours Per Month</td>
                    <td colspan='3' class='text-end'>
                        <span class='badge bg-danger'>
                            " . nf($totwattsmonth) . "
                                " . ($totwattsmonth > 1 ? 'Watts' : 'Watt') . "
                        </span>
                    </td>
                </tr>
            </table>
            <table style='color:black;' class='table table-striped table-hover mt-3'>
                <thead>
                    <tr>
                        <th colspan='6'>
                            <b>Cost estimation</b>
                        </th>
                    </tr>
                </thead>
                <tr>
                    <td colspan='3'>1 kW</td>
                    <td colspan='3' class='text-end'> $cost USD</td>
                </tr>
                <tr>
                    <td colspan='3'>Cost Per Month</td>
                    <td colspan='3' class='text-end'>
                        <span class='badge bg-danger'> " . nf($costmonth) . " USD</span>
                    </td>
                </tr>
            </table>
            ";

    $addons = (array) @$data['addons'];
    $addons = Addon::whereIn('id', $addons)->get();

    $totaddon = 0;
    $html .= "
            <table class='table table-striped table-hover mt-3' style='color:black;'>";
    if (count($addons)) {
        $html .= "
            <thead>
                <tr>
                    <th colspan='3'>
                        <b>Addons</b>
                    </th>
                </tr>
                <tr>
                    <th></th>
                    <th>Name</th>
                    <th class='text-end'>Cost</th>
                </tr>
            </thead>
            <tbody>";
        foreach ($addons as $k => $v) {
            $html .= "
                    <tr>
                        <td>" . ($k + 1) . "</td>
                        <td>$v->name </td>
                        <td class='text-end'>$v->cost USD</td>
                    </tr>";
            $totaddon += (float) @$v->cost;
        }
        $html .= "</tbody>";
    }

    $html .= "<tfoot>";

    if (count($addons)) {
        $html .= "
            <tr>
                <td>
                    Total addons
                </td>
                <td colspan='2' class='text-end'>
                    <span class='badge bg-danger'>
                        " . nf($totaddon) . " USD
                    </span>
                </td>
            </tr>";
    }
    $html .= "
            <tr>
                <td>
                    Total cost
                </td>
                <td colspan='2' class='text-end'>
                    <span class='badge bg-danger'>
                        " . nf($totaddon + $costmonth)  . " USD
                    </span>
                </td>
            </tr>
        </tfoot>
    </table>";

    $html .= '<h1 class="text-center mb-3 mt-5"><b>Images</b></h1>';
    foreach ($appliance as $k => $v) {
        $img = @$image[$k];
        $dir =  makedir($data['email']);
        $f = base64_to_image($img, "$dir/$k");
        $html .= "<span class='m-1'>
                    <img style='width: 100px; height: 100px; object-fit:contain;' class='img-thumbnail m-1' src='$f' >
                    <span>" . ($k + 1) . "</span>
                    </span>";
    }

    $mpdf = new \Mpdf\Mpdf();
    $mpdf->SetDisplayMode('fullpage');

    $mpdf->SetHTMLHeader('
        <div style="text-align: right; font-weight: bold;">
            Zeghen
        </div>');
    $mpdf->SetHTMLFooter('
        <table width="100%">
            <tr>
                <td width="33%">{DATE Y-n-d H:i:s}</td>
                <td width="33%" align="center">{PAGENO}/{nbpg}</td>
                <td width="33%" style="text-align: right;">Zeghen</td>
            </tr>
        </table>');

    // ini_set('memory_limit', '900000M');
    // ini_set("pcre.backtrack_limit", "900000000");

    // echo $html;
    // die;

    $stylesheet = file_get_contents('css/bootstrap.min.css');
    $stylesheet .= file_get_contents('css/style.css');
    $mpdf->WriteHTML($stylesheet, 1);
    $mpdf->WriteHTML($html, 2);
    // $mpdf->Output('PowerCalculatorResult.pdf', 'D');
    return $mpdf;
}
